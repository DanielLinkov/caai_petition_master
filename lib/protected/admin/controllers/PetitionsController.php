<?php
namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use app\models\Petition;
use app\models\Section;
use app\admin\models\PetitionConfigModel;

class PetitionsController extends \yii\web\Controller{
	public function behaviors(){
		return [
			'access'=>[
				'class'=>AccessControl::className(),
				'rules'=>[
					[
						'allow'=>true,
						'roles'=>['@'],
					],
				],
			],
			'verbs'=>[
				'class'=>VerbFilter::className(),
				'actions'=>[
					'delete'=>['POST']
				]
			]
		];
	}
	public function actionIndex(){
		$dataProvider = new ActiveDataProvider([
			'query'=>Petition::find()->orderBy('name ASC'),
			'sort'=>false,
		]);
		return $this->render('index',compact('dataProvider'));
	}
	public function actionAdd(){
		$model = new Petition;
		if($model->load(Yii::$app->request->post()) && $model->save()){
			Yii::$app->msg->flash("Petition saved","success");
			return $this->redirect(['index']);
		}
		return $this->render('edit',compact('model'));
	}
	public function actionUpdate($id){
		$model = Petition::findOne($id);
		if(!$model)
			throw new \yii\web\NotFoundHttpException("Petition not found");
		if($model->load(Yii::$app->request->post()) && $model->save()){
			Yii::$app->cache->flush();
			Yii::$app->page_cache->flush();
			Yii::$app->msg->flash("Petition updated","success");
			return $this->redirect(['index']);
		}
		return $this->render('edit',compact('model'));
	}
	public function actionDuplicate($id){
		$petition = Petition::findOne($id);
		if(!$petition)
			throw new \yii\web\NotFoundHttpException("Petition not found");
		if(!$petition->getCountSections()){
			Yii::$app->msg->flash("The petition does not have any sections","warning");
			return $this->redirect(['index']);
		}
		$model = new DuplicatePetitionModel;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			$targetPetition = Petition::findOne($model->target_petition_id);
			if(!$targetPetition)
				throw new \yii\web\NotFoundHttpException("Target petition not found");
			if($targetPetition->getCountSections()){
				Yii::$app->msg->flash("The petition <q>$targetPetition->name</q> is not empty (has sections)",'danger');
				return $this->refresh();
			}
			$targetPetition->config = $petition->config;
			$targetPetition->save();
			$id_map = [];
			//Duplicate sections
			foreach(Section::find()->where(['petition_id'=>$id,'parent_id'=>NULL])->all() AS $section){
				$id_map = $id_map + $section->duplicate($model->target_petition_id);
			}
			//Copy images
			$imageDir = Yii::getAlias("@petitionsroot/$model->target_petition_id/images");
			foreach(FileHelper::findFiles(Yii::getAlias("@petitionsroot/$id/images"),['recursive'=>false]) AS $image_path){
				$image_name = substr($image_path,strrpos($image_path,'/')+1);
				if(false === copy($image_path,"$imageDir/$image_name"))
					Yii::$app->msg->flash("Couldn't save $image_name file to $imageDir","danger");
			}
			//Copy css
			if(is_file(Yii::getAlias("@petitionsroot/$id/css/style.css"))){
				$css = file_get_contents(Yii::getAlias("@petitionsroot/$id/css/style.css"));
				if($model->flag_rename_css_rules){
					$patterms = [];
					$replacements = [];
					foreach($id_map AS $oldID=>$newID){
						$patterms[] = "/\.section-$oldID\b/";
						$replacements[] = ".section-$newID";
					}
					$css = preg_replace($patterms,$replacements,$css);
				}
				$cssDir = Yii::getAlias("@petitionsroot/$model->target_petition_id/css/style.css");
				if(false === file_put_contents($cssDir,$css))
					Yii::$app->msg->flash("Couldn't save css file to $cssDir","danger");
			}
			Yii::$app->page_cache->flush();
			Yii::$app->msg->flash("Petition <q>$petition->name</q> duplcated to <q>$targetPetition->name</q>","success");
			return $this->redirect(['index']);
		}
		$petitions = ArrayHelper::map(Petition::find()->where(['!=','id',$id])->all(),'id','name');
		return $this->render('duplicate',compact('petition','model','petitions'));
	}
	public function actionDelete($id){
		$petition = Petition::findOne($id);
		if(!$petition)
			throw new \yii\web\NotFoundHttpException("Petition not found");
		if($petition->getCountSections()){
			Yii::$app->msg->flash("To delete a petition you must delete all it's sections first","danger");
			return $this->redirect(['/sections']);
		}
		Yii::$app->page_cache->flush();
		$petition->delete();
		Yii::$app->msg->flash("Petition deleted","success");
		return $this->redirect(['index']);
	}
	public function actionSelect($id){
		$petition = Petition::findOne($id);
		if(!$petition)
			throw new \yii\web\NotFoundHttpException("Petition not found");
		Yii::$app->session->set('active_petition_id',$id);
		return $this->redirect(['/sections']);
	}
	public function actionConfiguration($id){
		$petition = Petition::findOne($id);
		if(!$petition)
			throw new \yii\web\NotFoundHttpException("Petition not found");
		$model = new PetitionConfigModel;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			$petition->config = $model->serialize();
			$petition->save();
			Yii::$app->page_cache->flush();
			Yii::$app->msg->flash("Petition configuration updated",'success');
			return $this->redirect(['index']);
		}
		$model->unserialize($petition->config);
		return $this->render('configuration',compact('model'));
	}
}

class DuplicatePetitionModel extends \yii\base\Model{
	public $target_petition_id;
	public $flag_rename_css_rules;
	public function rules(){
		return [
			['target_petition_id','required'],
			['flag_rename_css_rules','boolean']
		];
	}
	public function attributeLabels(){
		return [
			'target_petition_id'=>'Target Petition'
		];
	}
}

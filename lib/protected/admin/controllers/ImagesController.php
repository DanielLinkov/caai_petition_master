<?php
namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\helpers\FileHelper;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\web\UploadedFile;

class ImagesController extends \yii\web\Controller{
	use PetitionControllerTrait;
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
		$image_names = [];
		foreach(FileHelper::findFiles(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id.'/images'),['recursive'=>false]) AS $image_name){
			$image_name = substr($image_name,strrpos($image_name,'/')+1);
			$image_names[] = [
				'image_name'=>$image_name
			];
		}
		$dataProvider = new ArrayDataProvider([
			'allModels'=>$image_names,
			'key'=>'image_name'
		]);
		return $this->render('index',compact('dataProvider'));
	}
	public function actionThumbnail($image_name){
		$imagine = new \Imagine\Gd\Imagine();
		$imagine->open(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id."/images/$image_name"))
			->thumbnail(new \Imagine\Image\Box(150,150),\Imagine\Image\ImageInterface::THUMBNAIL_INSET)
			->show(substr($image_name,strrpos($image_name,'.')+1));
	}
	public function actionUpload(){
		$model = new ImageModel;
		if (Yii::$app->request->isAjax){
			$model->setScenario('ajax');
			if($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
		}
		if($model->load(Yii::$app->request->post())){
			$model->image = UploadedFile::getInstance($model,'image');
			if($model->validate()){
				$dirImages = Yii::getAlias("@petitionsroot/".Yii::$app->active_petition->id."/images");
				if(!FileHelper::createDirectory($dirImages))
					throw new \yii\web\ServerErrorHttpException("Can't make directory $dirImages. Check parent dir permissions");
				if($model->name)
					$baseName = $model->name;
				else
					$baseName = Yii::$app->security->generateRandomString(8);
				$image_name = "$baseName.{$model->image->extension}";
				if(!$model->image->saveAs("$dirImages/$image_name"))
					throw new \yii\web\ServerErrorHttpException("Can't save image to $dirImages. Check dir permissions");
				Yii::$app->msg->flash("Image saved","success");
				return $this->redirect(['view','id'=>$image_name]);
			}
		}
		return $this->render('upload',compact('model'));
	}
	public function actionView($id){
		if(!is_file(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id."/images/$id")))
			throw new \yii\web\NotFoundHttpException("Image not found");
		return $this->render('view',compact('id'));
	}
	public function actionUpdate($id){
		if(!is_file(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id."/images/$id")))
			throw new \yii\web\NotFoundHttpException("Image not found");
		$model = new ImageModel;
		$model->original_name = substr($id,0,strrpos($id,'.'));
		if (Yii::$app->request->isAjax){
			$model->setScenario('ajax');
			if($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
		}
		$model->setScenario('update');
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->name != $model->original_name){
				$dirImages = Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id."/images");
				$ext = substr($id,strrpos($id,'.')+1);
				rename("$dirImages/$model->original_name.$ext","$dirImages/$model->name.$ext");
			}
			Yii::$app->msg->flash("Image updated","success");
			return $this->redirect(['index']);
		}
		$model->name = $model->original_name;
		return $this->render('edit',compact('id','model'));
	}
	public function actionDelete($id){
		FileHelper::unlink(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id."/images/$id"));
		Yii::$app->msg->flash("Image deleted","success");
		return $this->redirect(['index']);
	}
	public function actionAjax_get_list(){
		$image_names = [];
		foreach(FileHelper::findFiles(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id.'/images'),['recursive'=>false]) AS $image_name){
			$image_name = substr($image_name,strrpos($image_name,'/')+1);
			$image_names[] = $image_name;
		}
		$this->asJSON($image_names);
	}
}

class ImageModel extends \yii\base\Model{
	public $image;
	public $name;
	public $original_name;
	public function scenarios(){
		return [
			'default'=>['image','name'],
			'update'=>['name'],
			'ajax'=>['name']
		];
	}
	public function rules(){
		return [
			['image','required'],
			['image','file','extensions'=>'jpg,jpeg,png,gif','maxSize'=>1024 * 1024],
			['name','trim'],
			['name','match','pattern'=>'/^[a-zA-Z0-9_-]+$/','message'=>'The name can only contain latin letters, numbers, underscore and hyphen'],
			['name','string','max'=>32],
			['name','uniqueFile','when'=>function($model){return $model->name != $model->original_name;}],
		];
	}
	public function uniqueFile(){
		foreach(FileHelper::findFiles(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id.'/images'),['recursive'=>false]) AS $image_name){
			$image_name = substr($image_name,strrpos($image_name,'/')+1);
			if($this->name == substr($image_name,0,strrpos($image_name,'.'))){
				$this->addError('name','This name is already taken');
				return;
			}
		}
	}
}

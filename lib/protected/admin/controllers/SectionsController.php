<?php
namespace app\admin\controllers;

use Yii;
use yii\helpers\Html;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Petition;
use app\models\Section;

class SectionsController extends \yii\web\Controller{
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
		$dataProvider = new ActiveDataProvider([
			'query'=>Section::find()->andWhere(['parent_id'=>NULL])->orderBy('order_ind ASC'),
			'sort'=>false,
			'pagination'=>false
		]);
		return $this->render('index',compact('dataProvider'));
	}
	public function actionAdd($type){
		$model = new Section;
		$model->type = $type;
		if (Yii::$app->request->isAjax){
			if($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
		}
		$dataModel = $model->getDataModel();
		if($model->load(Yii::$app->request->post()) && $model->validate() && (!$dataModel || $dataModel->load(Yii::$app->request->post()) && $dataModel->validate())){
			if($dataModel)
				$model->data = $dataModel->serialize();
			if($model->save()){
				Yii::$app->page_cache->flush();
				if($dataModel && $dataModel->shouldSaveBeforeEdit()){
					Yii::$app->msg->flash("Section saved and ready to edit","success");
					return $this->redirect(['update','id'=>$model->id]);
				}else{
					Yii::$app->msg->flash("Section saved","success");
					return $this->redirect(Yii::$app->request->post('action') == 'edit' ? ['update','id'=>$model->id] : ['index']);
				}
			}
		}
		return $this->render('edit',compact('model','dataModel'));
	}
	public function actionUpdate($id){
		$model = Section::find()->andWhere(['id'=>$id])->one();
		if(!$model)
			throw new \yii\web\NotFoundHttpException("Section not found");
		$dataModel = $model->getDataModel();
		if($dataModel && $model->data)
			$dataModel->unserialize($model->data);
		if (Yii::$app->request->isAjax){
			if($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				return ActiveForm::validate($model);
			}
		}
		if($model->load(Yii::$app->request->post()) && $model->validate() && (!$dataModel || $dataModel->load(Yii::$app->request->post()) && $dataModel->validate())){
			if($dataModel)
				$model->data = $dataModel->serialize($model->id);
			if($model->save()){
				Yii::$app->page_cache->flush();
				Yii::$app->msg->flash(($model->parent_id ? "Subsection" : "Section") . " saved","success");
				if(Yii::$app->request->post('action') == 'edit' || $dataModel && $dataModel->shouldSaveBeforeEdit() && !$dataModel->isDataComplete()){
					return $this->redirect(['update','id'=>$model->id]);
				}else{
					return $this->redirect($model->parent_id ? ['update','id'=>$model->parent_id] : ['index']);
				}
			}
		}
		return $this->render('edit',compact('model','dataModel'));
	}
	public function actionDelete($id){
		$section = Section::find()->andWhere(['id'=>$id])->one();
		if(!$section)
			throw new \yii\web\NotFoundHttpException("Section not found");
		$section->delete();
		Yii::$app->page_cache->flush();
		Yii::$app->msg->flash("Section deleted","success");
		return $this->redirect(['index']);
	}
	public function renderPreview($section){
		$dataModel = $section->getDataModel();
		ob_start();
		echo Html::beginTag('div',['class'=>"section-box $section->type"]);
		echo Html::tag('div',
				Html::input('text',NULL,"ID: $section->tag_id | class: section-$section->type section-$section->id $section->tag_additional_classes",['class'=>'form-control','readonly'=>true])
				.
				Html::a('<span class="glyphicon glyphicon-pencil"></span>',['update','id'=>$section->id],['class'=>'btn btn-xs btn-warning btn-edit','title'=>"Edit this section"])
		,['class'=>'tag-attributes']);
		switch($section->type){
			case 'text':
				if($section->data){
					$dataModel->unserialize($section->data);
					echo mb_substr(strip_tags($dataModel->content),0,100);
				}
				break;
			case 'image':
				if($section->data){
					$dataModel->unserialize($section->data);
					echo Html::img(Yii::getAlias("@petitions/".Yii::$app->active_petition->id."/images/$dataModel->image_name"));
				}
				break;
			case 'video':
				if($section->data){
					$dataModel->unserialize($section->data);
					echo $dataModel->embed_code;
				}
				break;
			case 'share':
				echo 'Share Section';
				break;
			case 'form':
				echo 'Subscription Form';
				break;
			case 'stack':
				if($section->data){
						$dataModel->unserialize($section->data);
					if(!isset($dataModel->stack) || !is_array($dataModel->stack)){
						echo Html::tag('em','Empty stack');
						break;
					}
					echo Html::beginTag('div',['class'=>"stack-preview orientation-$dataModel->orientation"]);
					foreach($dataModel->stack AS $cell){
						echo Html::beginTag('div',['class'=>'stack-preview-cell']);
						$cell = json_decode($cell);
						if($cell && $cell->id){
							$subsection = Section::findOne($cell->id);
							if($subsection)
								echo $this->renderPreview($subsection);
							else
								echo Html::tag('em','Missing subsection');
						}else
							echo Html::tag('em','Empty cell');
						echo Html::endTag('div');
					}
					echo Html::endTag('div');
					$this->view->registerCss("
						.stack-preview{
							display:flex;
						}
						.stack-preview.orientation-vertical{
							flex-direction:column;
						}
						.stack-preview > .stack-preview-cell{
							text-align:center;
							border:dotted 1px;
							flex-basis:0;
							flex-grow:1;
							flex-shrink:0;
							margin:3px;
						}
					");
				}
				break;
			default:
				echo Html::tag('em',"Unknown type: $section->type");
		}
		echo Html::endTag('div');
		return ob_get_clean();
	}
	public function actionMove_up($id){
		$sections = [];
		foreach(Section::find()->andWhere(['parent_id'=>NULL])->select('id,order_ind')->orderBy('order_ind ASC')->all() AS $ind=>$section){
			$sections[] = $section;
			if($section->id == $id && $ind){
				$section->order_ind = $ind;
				$sections[$ind-1]->order_ind = $ind+1;
				$sections[$ind-1]->save(false);
				Yii::$app->msg->flash("Sections reordered","success");
			}else
				$section->order_ind = $ind + 1;
			$section->save(false);
		}
		Yii::$app->page_cache->flush();
		return $this->redirect(['index']);
	}
	public function actionMove_down($id){
		$sections = [];
		$target_section_met = false;
		foreach(Section::find()->andWhere(['parent_id'=>NULL])->select('id,order_ind')->orderBy('order_ind ASC')->all() AS $ind=>$section){
			$sections[] = $section;
			if($target_section_met){
				$section->order_ind = $ind;
				$sections[$ind-1]->order_ind = $ind+1;
				$sections[$ind-1]->save(false);
				Yii::$app->msg->flash("Sections reordered","success");
				$target_section_met = false;
			}elseif($section->id == $id){
				$target_section_met = true;
				continue;
			}else
				$section->order_ind = $ind + 1;
			$section->save(false);
		}
		Yii::$app->page_cache->flush();
		return $this->redirect(['index']);
	}
}

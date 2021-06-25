<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use kartik\dialog\Dialog;
use rmrevin\yii\fontawesome\FAS;

echo Dialog::widget(['overrideYiiConfirm' => true]);

$this->title = 'Images - ';
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	'Images'
];
$this->registerCss("
.img-thumbnail{
	background:url('/images/checker-pattern.gif');
}
");
echo Html::a(FAS::icon('upload')->fixedWidth().' Upload New Image',['upload'],['class'=>'btn btn-primary pull-right']);
?>
<h2>Images</h2>
<?php
echo GridView::widget([
	'dataProvider'=>$dataProvider,
	'columns'=>[
		'image_name',
		[
			'label'=>'Web Path',
			'format'=>'raw',
			'value'=>function($model){
				return Html::input('text',NULL,Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/{$model['image_name']}"),['class'=>'form-control','readonly'=>true]);
			}
		],
		[
			'label'=>'Preview',
			'format'=>'html',
			'value'=>function($model){
				return Html::a(Html::img(Url::to(['thumbnail','image_name'=>$model['image_name']]),['class'=>'img-thumbnail','title'=>'View']),['view','id'=>$model['image_name']]);
			}
		],
		[
			'class'=>ActionColumn::className(),
			'headerOptions'=>['width'=>80]
		]
	]
]);

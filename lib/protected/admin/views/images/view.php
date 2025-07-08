<?php
use kartik\helpers\Html;

$this->title = 'Image Preview - ';
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	['label'=>'Images','url'=>['index']],
	'Image Preview'
];
$this->registerCss("
.img-thumbnail{
	background:url('/images/checker-pattern.gif');
}
");
echo Html::a(Html::icon('pencil').' Edit',['update','id'=>$id],['class'=>'btn btn-warning pull-right']);
?>
<h2>Image <?=$id?> Preview</h2>
<H4>Web Path: <?=Html::input('text',NULL,Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$id"),['class'=>'form-control','readonly'=>true])?></h4>
<?= Html::img(Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$id"),['class'=>'img-thumbnail']);?>

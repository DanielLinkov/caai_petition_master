<?php
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FAS;

$this->title = "Manage Petition - ";
$this->params['breadcrumbs'] = [
	['label'=>'Petitions','url'=>['index']],
	'Manage Petition'
];
?>
<h2>Manage Petition <q><?=Yii::$app->active_petition->name?></q></h2>
<p><?= Html::a(FAS::icon('puzzle-piece')->fixedWidth().' Edit Sections',['/sections'],['class'=>'btn btn-primary btn-lg']);?>
<p><?= Html::a(FAS::icon('images')->fixedWidth().' Manage Images',['/images'],['class'=>'btn btn-primary btn-lg']);?>

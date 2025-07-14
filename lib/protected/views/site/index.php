<?php
use yii\helpers\Html;

foreach($sections AS $section)
	echo $section->render($page_mask),"\n";

if(Yii::$app->active_petition->config && Yii::$app->active_petition->config->smoothscroll_plugin ?? false){
	$this->registerJsFile('@web/js/jquery.smoothscroll.min.js',['depends'=>'yii\web\JqueryAsset']);
	$this->registerJs("
		$('a[href*=\"#\"]').smoothscroll({
			duration:  400
		});
	");
}
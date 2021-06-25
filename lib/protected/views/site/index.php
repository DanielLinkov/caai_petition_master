<?php
use yii\helpers\Html;

foreach($sections AS $section)
	echo $section->render(),"\n";

$this->registerJsFile('@web/js/jquery.smoothscroll.min.js',['depends'=>'yii\web\JqueryAsset']);
$this->registerJs("
	$('a[href*=\"#\"]').smoothscroll({
	  duration:  400
	});
");

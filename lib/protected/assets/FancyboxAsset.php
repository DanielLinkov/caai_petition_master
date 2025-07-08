<?php
namespace app\assets;

class FancyboxAsset extends \yii\web\AssetBundle{
	public $sourcePath = '@app/assets/fancybox-assets';
	public $css = [
		'jquery.fancybox.min.css'
	];
	public $js = [
		'jquery.fancybox.min.js'
	];
	public $depends = [
		'yii\web\JqueryAsset'
	];
}

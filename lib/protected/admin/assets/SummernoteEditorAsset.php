<?php
namespace app\admin\assets;

class SummernoteEditorAsset extends \yii\web\AssetBundle{
	public $sourcePath = '@app/admin/assets/summernote-assets';
	public $css = [
		'summernote.css',
	];
	public $js = [
		'summernote.js',
	];
	public $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];
}

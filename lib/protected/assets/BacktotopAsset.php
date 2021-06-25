<?php
namespace app\assets;

use yii\web\View;

class BacktotopAsset extends \yii\web\AssetBundle{
	public $sourcePath = '@app/assets/back_to_top-assets';
	public $css = [
		'css/style.css'
	];
	public $js = [
		'js/index.js'
	];
	public $depends = [
		'yii\web\JqueryAsset'
	];
	public function init(){
		\Yii::$app->controller->view->on(View::EVENT_END_BODY,function($event){
			echo <<<EOS
<div class="progress-wrap">
  <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
  <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
  </svg>
</div>
EOS;
		});
	}
}

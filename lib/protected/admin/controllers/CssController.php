<?php
namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;

class CssController extends \yii\web\Controller{
	use PetitionControllerTrait;
	public function behaviors(){
		return [
			'access'=>[
				'class'=>AccessControl::class,
				'rules'=>[
					[
						'allow'=>true,
						'roles'=>['@'],
					],
				],
			],
		];
	}
	public function actionIndex(){
		$css = '';
		$filePath = Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id.'/css/style.css');
		if(is_file($filePath))
			$css = file_get_contents($filePath);
		return $this->render('index',compact('css'));
	}
	public function actionAjax_save(){
		$css = Yii::$app->request->post('css');
		$filePath = Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id.'/css/style.css');
		if(file_put_contents($filePath,$css) === false)
			throw new \yii\web\ServerErrorHttpException("Couldn't save code to $filePath file");
		return $this->asJSON('ok');
	}
}

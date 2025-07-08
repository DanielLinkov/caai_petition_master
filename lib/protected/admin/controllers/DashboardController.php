<?php
namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;

class DashboardController extends \yii\web\Controller{
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
		return $this->render('index');
	}
}

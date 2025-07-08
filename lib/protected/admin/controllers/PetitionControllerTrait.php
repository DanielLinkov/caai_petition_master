<?php
namespace app\admin\controllers;

use Yii;

trait PetitionControllerTrait{
	public function beforeAction($action){
		if(!Yii::$app->active_petition->id){
			Yii::$app->msg->flash("Please select a petition to manage first","warning");
			Yii::$app->response->redirect(['/petitions']);
			return false;
		}
		return parent::beforeAction($action);
	}
}

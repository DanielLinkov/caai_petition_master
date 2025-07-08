<?php
namespace app\components;

class Petition implements \yii\base\BootstrapInterface{
	public $id;
	public $name;
	public $code;
	public $config;
	public function bootstrap($app){
		$app->setAliases([ '@petitionroot' => "@webroot/petitions" ]);
		$petition = \app\models\Petition::findOne(['hostname'=>$_SERVER['HTTP_HOST']]);
		if(!$petition){
			throw new \yii\web\NotFoundHttpException("No petition is registered to this hostname: <b>{$_SERVER['HTTP_HOST']}</b>");
		}
		$this->id = $petition->id;
		$this->name = $petition->name;
		$this->code = $petition->code;
		$this->config = json_decode($petition->config);
		$app->setAliases([ '@petitionroot' => "@webroot/petitions/$petition->id" ]);
	}
}

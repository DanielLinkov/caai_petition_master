<?php
namespace app\admin\components;

class Petition implements \yii\base\BootstrapInterface{
	public $id;
	public $name;
	public function bootstrap($app){
		$petition = \app\models\Petition::findOne($app->session->get('active_petition_id'));
		if(!$petition)
			return;
		$this->id = $petition->id;
		$this->name = $petition->name;
	}
}

<?php
namespace therendstudio\modules\messages\controllers;
use yii\web\Controller;
use yii\web\Session;

class DefaultController extends Controller{
	public function actionIndex($kP){
		$this->layout = $this->module->layout;
		$session = new Session;
		$session->open();
		$title = $session["{$kP}_msg_title"];
		$message = $session["{$kP}_msg_message"];
		$level = $session["{$kP}_msg_level"];
		return $this->render('/message',compact('title','message','level'));
	}
}

?>

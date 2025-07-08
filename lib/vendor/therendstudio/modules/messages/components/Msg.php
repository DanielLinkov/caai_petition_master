<?php
namespace therendstudio\modules\messages\components;

class Msg{
	public $prefix = 'tsm-';
	public function redirectTo($title,$message,$level=''){
		$keyPrefix = str_replace('.','S',uniqid('',true));
		$session = \Yii::$app->session;
		$session->open();
		$session["{$keyPrefix}_msg_title"] = $title;
		if(is_array($message)) $message = print_r($message,true);
		$session["{$keyPrefix}_msg_message"] = $message;
		$session["{$keyPrefix}_msg_level"] = $level;
		\Yii::$app->controller->redirect(array('/messages','kP'=>$keyPrefix));
		\Yii::$app->end();
	}
	public function flash($message,$level){
		if(is_array($message)) $message = print_r($message,true);
		$session = \Yii::$app->session;
		$session->addFlash($this->prefix.$level,$message,true);
	}
}

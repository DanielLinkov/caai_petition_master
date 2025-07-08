<?php
namespace therendstudio\modules\messages\widgets;

use yii\base\Widget;
use yii\bootstrap\Alert;

class FlashWidget extends Widget{
	public $prefix = 'tsm-';
	public function run(){
		$messages = array();
		$levelMap = array(
			'error'=>'danger',
		);
		$output = '';
		foreach(\Yii::$app->session->getAllFlashes() AS $id=>$msgList)
			if(strpos($id,$this->prefix) === 0){
				if(is_array($msgList)){
					$level = substr($id,strlen($this->prefix));
					foreach($msgList AS $message){
						if(isset($messages[$message]) && $messages[$message] == $level)
							continue;	//Duplicate entry
						$messages[$message] = $level;
						$output .= Alert::widget(['options'=>['class'=>"alert-".(isset($levelMap[$level]) ? $levelMap[$level] : $level)],'body'=>$message]);
					}
				}
				\Yii::$app->session->removeFlash($id);
			}
		return $output;
	}
}

?>

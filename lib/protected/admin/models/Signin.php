<?php
namespace app\admin\models;

use Yii;

class Signin extends \yii\base\Model{
	public $username;
	public $password;
	public $captcha;
	private $userIdentity;
	public function getIdentity(){
		return $this->userIdentity;
	}
	public function rules(){
		return [
			[['username','password','captcha'],'required'],
			['captcha','captcha','captchaAction'=>'signin/captcha'],
			[['username','password'],'trim'],
			['password','authenticate'],
		];
	}
	public function authenticate(){
		if($this->hasErrors())
			return;
		$admin = User::findOne(['username'=>$this->username]);
		if(!$admin)
			$this->addError('signin','Invalid credentials');
		elseif(!Yii::$app->security->validatePassword($this->password,$admin->password))
			$this->addError('signin','Invalid credentials');
		elseif(!$admin->flag_enabled)
			$this->addError('signin','Your account is disabled');
		$this->userIdentity = $admin;
	}
	public function attributeLabels(){
		return [
			'captcha'=>'Verify Code',
		];
	}
}

<?php
namespace app\admin\controllers;

use Yii;
use app\admin\models\User;
use app\admin\models\Signin;

class SigninController extends \yii\web\Controller{
	public $layout = 'guest';
	public function actions(){
		return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
		];
	}
	public function actionIndex(){
		if(!Yii::$app->user->isGuest)
			return $this->redirect(['/dashboard']);
		$root = User::findOne(['username'=>'root']);
		if(!$root)
			return $this->redirect(['set_root_password']);
		$model = new Signin;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			Yii::$app->user->login($model->getIdentity());
			return $this->redirect(['/dashboard']);
		}
		return $this->render('index',compact('model'));
	}
	public function actionSet_root_password(){
		$root = User::findOne(['username'=>'root']);
		if($root)
			return $this->redirect(['index']);
		$model = new RootPasswordModel;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			$root = new User;
			$root->role = 'superadmin';
			$root->username = 'root';
			$root->name = 'Root';
			$root->password = Yii::$app->security->generatePasswordHash($model->password);
			if(!$root->save())
				throw new \yii\web\ServerErrorHttpException(print_r($root->getErrors(),true));
			return $this->redirect(['index']);
		}
		return $this->render('set_root_password',compact('model'));
	}
}

class RootPasswordModel extends \yii\base\Model{
	public $password;
	public function rules(){
		return [
			['password','required'],
			['password','string','min'=>6]
		];
	}
}

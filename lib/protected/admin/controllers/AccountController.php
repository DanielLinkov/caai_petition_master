<?php
namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use app\admin\models\ChangePasswordModel;

class AccountController extends \yii\web\Controller{
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
	public function actionSignout(){
		Yii::$app->user->logout();
		return $this->redirect(['/signin']);
	}
	public function actionPreferences(){
		$model = new PreferencesModel;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			Yii::$app->user->identity->preferences = $model->serialize();
			Yii::$app->user->identity->save();
			Yii::$app->msg->flash("Preference saved",'success');
			return $this->refresh();
		}
		$model->unserialize(Yii::$app->user->identity->preferences);
		return $this->render('preferences',compact('model'));
	}
	public function actionChange_password(){
		$model = new ChangePasswordModel;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			Yii::$app->user->identity->password = Yii::$app->security->generatePasswordHash($model->input_password);
			Yii::$app->user->identity->save();
			Yii::$app->msg->flash("Password updated",'success');
			return $this->redirect(['/dashboard']);
		}
		return $this->render('change_password',compact('model'));
	}
}

class PreferencesModel extends \yii\base\Model{
	public $text_editor;
	public $vim_mode;
	public function rules(){
		return [
			['text_editor','string','max'=>12],
			['vim_mode','boolean']
		];
	}
	public function serialize(){
		return json_encode([
			'text_editor'=>$this->text_editor,
			'vim_mode'=>$this->vim_mode
		]);
	}
	public function unserialize($data){
		$data = json_decode($data);
		if(!$data)
			return;
		$this->text_editor = $data->text_editor;
		$this->vim_mode = $data->vim_mode;
	}
}

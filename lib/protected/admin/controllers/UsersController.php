<?php
namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\admin\models\User;
use app\admin\models\UserModel;
use app\admin\models\ChangePasswordModel;

class UsersController extends \yii\web\Controller{
	public function behaviors(){
		return [
			'access'=>[
				'class'=>AccessControl::className(),
				'rules'=>[
					[
						'allow'=>false,
						'roles'=>['?'],
                    ],
                    [
                        'allow'=>false,
                        'matchCallback'=>function(){
                            return Yii::$app->user->identity->role != 'superadmin';
                        }
                    ],
                    [
                        'allow'=>true,
                    ]
				],
			],
			'verbs'=>[
				'class'=>VerbFilter::className(),
				'actions'=>[
					'delete'=>['POST']
				]
			]
		];
	}
    public function actionIndex(){
		$dataProvider = new ActiveDataProvider([
			'query'=>User::find()->orderBy('name ASC'),
			'sort'=>false,
		]);
		return $this->render('index',compact('dataProvider'));
	}
	public function actionToggle_flag_enabled($id){
        $user = User::findOne($id);
        if(!$user)
            throw new \yii\web\NotFoundHttpException("User [id:$id] not found");
        if($user->username == 'root'){
			Yii::$app->msg->flash("You can't disable the root account","danger");
            return $this->redirect(['index']);
        }
        if($user->id == Yii::$app->user->id){
			Yii::$app->msg->flash("You can't disable your own account","danger");
            return $this->redirect(['index']);
		}
		$user->flag_enabled = !$user->flag_enabled;
		$user->save();
        Yii::$app->msg->flash("Status of account of <b>$user->name</b> toggled","success");
        return $this->redirect(['index']);
	}
	public function actionChange_password($id){
        $user = User::findOne($id);
        if(!$user)
            throw new \yii\web\NotFoundHttpException("User [id:$id] not found");
		$model = new ChangePasswordModel;
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			$user->password = Yii::$app->security->generatePasswordHash($model->input_password);
			$user->save();
			Yii::$app->msg->flash("Password of <b>$user->name</b> updated",'success');
			return $this->redirect(['index']);
		}
		return $this->render('change_password',compact('user','model'));
	}
	public function actionAdd(){
		$model = new UserModel;
		if($model->load(Yii::$app->request->post())){
			$permission_flags = 0;
			foreach($model->permission_flags AS $flag)
				$permission_flags |= $flag;
			$model->permission_flags = $permission_flags;
			if($model->validate()){
				$model->password = Yii::$app->security->generatePasswordHash($model->input_password);
				if($model->save()){
					Yii::$app->msg->flash("Account for user <b>$model->name</b> created","success");
					return $this->redirect(['index']);
				}
			}
			$permission_flags_array = [];
			for($bit = 0;$bit < 32;$bit++)
				if($model->permission_flags & (1 << $bit))
					$permission_flags_array[] = (1 << $bit);
			$model->permission_flags = $permission_flags_array;
		}
		return $this->render('edit',compact('model'));
	}
	public function actionUpdate($id){
		$model = User::findOne($id);
        if(!$model)
            throw new \yii\web\NotFoundHttpException("User [id:$id] not found");
		if($model->load(Yii::$app->request->post())){
			$permission_flags = 0;
			foreach($model->permission_flags AS $flag)
				$permission_flags |= $flag;
			$model->permission_flags = $permission_flags;
			if($model->save()){
				Yii::$app->msg->flash("Account for user <b>$model->name</b> updated","success");
				return $this->redirect(['index']);
			}
		}
		$permission_flags_array = [];
		for($bit = 0;$bit < 32;$bit++)
			if($model->permission_flags & (1 << $bit))
				$permission_flags_array[] = (1 << $bit);
		$model->permission_flags = $permission_flags_array;
		return $this->render('edit',compact('model'));
	}
	public function actionDelete($id){
        $user = User::findOne($id);
        if(!$user)
            throw new \yii\web\NotFoundHttpException("User [id:$id] not found");
        if($user->username == 'root'){
			Yii::$app->msg->flash("You can't delete the root account","danger");
            return $this->redirect(['index']);
        }
        if($user->id == Yii::$app->user->id){
			Yii::$app->msg->flash("You can't delete your own account","danger");
            return $this->redirect(['index']);
        }
        $user->delete();
        Yii::$app->msg->flash("Account of <b>$user->name</b> deleted","warning");
        return $this->redirect(['index']);
    }
}
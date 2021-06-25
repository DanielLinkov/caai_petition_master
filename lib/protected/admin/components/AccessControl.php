<?php
namespace app\admin\components;

use Yii;

class AccessControl implements \yii\base\BootstrapInterface{
    public function bootstrap($app){
        if(Yii::$app->request->isAjax || substr(Yii::$app->urlManager->parseRequest(Yii::$app->request)[0],0,6) == 'signin')
            return;
        if($app->user->identity && !$app->user->identity->flag_enabled){
            $app->user->logout();
            $app->msg->flash("Your account was suspended",'danger');
            $app->user->loginUrl = ['/signin'];
            $app->user->loginRequired();
            $app->end();
        }
    }
}

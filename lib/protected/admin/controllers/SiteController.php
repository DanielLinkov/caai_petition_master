<?php

namespace app\admin\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
		if(Yii::$app->user->isGuest)
			return $this->redirect(['/signin']);
		else
			return $this->redirect(['/dashboard']);
    }

}

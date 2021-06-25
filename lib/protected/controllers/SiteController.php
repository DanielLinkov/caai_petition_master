<?php

namespace app\controllers;

use Yii;
use yii\filters\PageCache;
use yii\web\Controller;
use app\models\Section;
use app\models\FormModel;

class SiteController extends Controller
{
	public function behaviors(){
		return [
			[
				'class'=>PageCache::className(),
				'enabled'=>!YII_ENV_DEV,
				'only'=>['index','thanks'],
				'duration'=>0,
				'cache'=>'page_cache',
				'variations'=>[
					Yii::$app->active_petition->id
				]
			]
		];
	}
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }
    public function actionIndex()
    {
		$sections = Section::find()->andWhere(['parent_id'=>NULL])->andWhere('mask_pages & '.Section::MASK_PAGE_MAIN)->orderBy('order_ind ASC')->all();
        return $this->render('index',compact('sections'));
    }
	public function actionThanks(){
		$sections = Section::find()->andWhere(['parent_id'=>NULL])->andWhere('mask_pages & '.Section::MASK_PAGE_THANKS)->orderBy('order_ind ASC')->all();
        return $this->render('index',compact('sections'));
	}
	private function validateMailserver($hostname){
		return getmxrr($hostname,$hosts) && count($hosts);
	}
	public function actionAjax_sign(){
		$model = new FormModel;
		$model->load(Yii::$app->request->post());
		if($model->validate()){
			$hostname = (explode('@',$model->email))[1];
			if(!$this->validateMailserver($hostname))
				return $this->asJSON([
					'status'=>'error',
					'error'=>"Невалиден имейл домейн: <b>$hostname</b>"
				]);
			$response = Yii::$app->api_subscription->subscribe(
				Yii::$app->active_petition->code,
				$model->name,
				$model->email,
				$model->country,
				$model->phone,
				$model->agreed_to_subscribe
			);
			if($response->status == 'error')
				return $this->asJSON($response);
			Yii::$app->page_cache->flush();
			Yii::$app->cache->delete('count_subscriptions_'.Yii::$app->active_petition->id);
			return $this->asJSON($response);
		}else
			return $this->asJSON([
				'status'=>'error',
				'error'=>'Невалидно попълнена форма'
			]);
	}

}

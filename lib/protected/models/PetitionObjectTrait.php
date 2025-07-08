<?php
namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

trait PetitionObjectTrait{
	public function behaviors(){
		return array_merge(parent::behaviors(),[
			[
				'class'			=>	AttributeBehavior::class,
				'attributes'	=>	[
					ActiveRecord::EVENT_BEFORE_INSERT	=>	'petition_id',
				],
				'value'			=>	function(){
					return $this->owner->petition_id ? $this->owner->petition_id : Yii::$app->active_petition->id;
				}
			],
		]);
	}
	public static function find(){
		return parent::find()->andWhere([static::tableName().'.petition_id'=>Yii::$app->active_petition->id]);
	}
}

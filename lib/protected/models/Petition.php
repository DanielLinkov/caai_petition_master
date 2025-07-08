<?php
namespace app\models;

use yii;
use yii\helpers\FileHelper;

class Petition extends \yii\db\ActiveRecord{
	public function getCountSections(){
		return Section::find()->where(['petition_id'=>$this->id])->count();
	}
	public function afterSave($insert,$changedAttributes){
		if($insert){
			FileHelper::createDirectory(Yii::getAlias("@petitionsroot/$this->id/css"));
			FileHelper::createDirectory(Yii::getAlias("@petitionsroot/$this->id/images"));
		}
		parent::afterSave($insert,$changedAttributes);
	}
	public function afterDelete(){
		FileHelper::removeDirectory(Yii::getAlias("@petitionsroot/$this->id/"));
		parent::afterDelete();
	}
	public function rules(){
		return [
			[['name','hostname','code'],'filter','filter'=>'strip_tags'],
			[['name','hostname','code'],'trim'],
			[['name','hostname','code'],'required'],
			[['name','hostname','code'],'unique'],
			['name','string','max'=>64],
			['hostname','string','max'=>64],
			['code','string','max'=>32],
			['config','string','max'=>4096]
		];
	}
}

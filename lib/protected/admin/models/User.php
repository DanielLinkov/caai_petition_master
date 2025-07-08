<?php
namespace app\admin\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface{
	const PF_SECTION_ADD = 1;
	const PF_SECTION_DELETE = 2;
	const PF_SECTION_EDIT_CONTENT = 4;
	const PF_SECTION_EDIT_META = 8;
	const PF_SECTION_EDIT_DESIGN = 16;
	const PF_IMAGE_UPLOAD = 32;
	const PF_IMAGE_DELETE = 64;
	const PF_IMAGE_REPLACE = 128;
	const PF_CSS_EDIT = 256;
	//>>> Interface implementations
	public static function findIdentity($id){
		return self::findOne($id);
	}
	public static function findIdentityByAccessToken($toek,$type=NULL){
		throw new \yii\base\Exception('Not implemented');
	}
	public function getAuthKey(){
		return $this->auth_key;
	}
	public function validateAuthKey($authKey){
		return $this->auth_key == $authKey;
	}
	public function getId(){
		return $this->id;
	}
	//<<<
	public function attributeLabels(){
		return [
			'flag_enabled'=>'Enabled',
		];
	}
	public function rules(){
		return [
			[['role','username','name'],'required'],
			[['username','name'],'filter','filter'=>'htmlentities'],
			['permission_flags','integer'],
			['flag_enabled','boolean'],
			[['role','username'],'string','max'=>16],
			['name','string','max'=>32,'min'=>3],
			['password','string','max'=>128],
			['preferences','string','max'=>1024]
		];
    }
}

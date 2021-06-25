<?php
namespace app\admin\models;

class UserModel extends User{
    public $input_password;
    public $repeat_password;
	public function rules(){
		return array_merge(parent::rules(),[
            [['input_password','repeat_password'],'required'],
			['input_password','string','min'=>6],
			['repeat_password','compare','compareAttribute'=>'input_password'],
		]);
    }
    public function attributeLabels(){
        return [
            'input_password'=>'Password'
        ];
    }
    static public function tableName(){
        return "{{%user}}";
    }
}
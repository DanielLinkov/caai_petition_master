<?php
namespace app\admin\models;

class ChangePasswordModel extends \yii\base\Model{
    public $input_password;
    public $repeat_password;

    public function rules(){
        return [
            [['input_password','repeat_password'],'required'],
            ['input_password','string','min'=>6],
            ['repeat_password','compare','compareAttribute'=>'input_password']
        ];
    }
    public function attributeLabels(){
        return [
            'input_password'=>'New Password'
        ];
    }
}
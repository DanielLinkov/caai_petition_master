<?php
namespace app\models;

use borales\extensions\phoneInput\PhoneInputValidator;

class FormModel extends \yii\base\Model{
	public $name;
	public $email;
	public $country;
	public $country_is_required = false;
	public $phone;
	public $agreed_to_subscribe;
	public function rules(){
		$cLSmall = "абвгдежзийклмнопрстуфхцчшщъьюя";
		$cLCapital = "АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЬЮЯ";
		$rules = [
			[['name','email','agreed_to_subscribe'],'required'],
			[['name','email','phone'],'trim'],
			['email','email','message'=>'Моля въведете валиден имейл адрес'],
			[['name','email'],'string','max'=>64],
			['name','match','pattern'=>"/^[$cLSmall$cLCapital-]+\\s+[$cLSmall$cLCapital-]+$/",'message'=>'Моля, въведете точно две имена на кирилица.'],
			['country','string','max'=>2],
			['phone','string','max'=>24],
			['phone',PhoneInputValidator::class,'message'=>'Моля въведете валиден телефонен номер'],
			['agreed_to_subscribe','boolean']
		];
		if($this->country_is_required){
			$rules[] = ['country','required'];
		}
		return $rules;
	}
	public function attributeLabels(){
		return [
			'name'=>'Име и фамилия',
			'email'=>'Имейл адрес',
			'phone'=>'Телефон (по желание)',
			'country'=>'Страна',
			'agreed_to_subscribe'=>'Искате ли да сте в течение за новини и дейности, с които може да помагате на животните?'
		];
	}
}

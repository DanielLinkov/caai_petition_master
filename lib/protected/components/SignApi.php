<?php
namespace app\components;

use Unirest\Request;
use Unirest\Exception;

class SignApi extends \yii\base\Component{
	const API_ENDPOINT = 'https://crm.caai.bg/data';
	public $api_key;
	public function get_count($petiton_code){
		try{
			$response = Request::post(self::API_ENDPOINT,
				[
					'Content-Type'=>'application/json',
				],
				json_encode([
					'key'=>$this->api_key,
					'count'=>true,
					'petition'=>$petiton_code
				])
			);
		}catch(Exception $exc){
			return (object)[
				'status'=>'error',
				'error'=>$exc->getMessage()
			];
		}
		if($response->code != 200)
			return (object)[
				'status'=>'error',
				'error'=>"Request code is different than 200: $response->code"
			];
		if(!isset($response->body->count))
			return (object)[
				'status'=>'error',
				'error'=>"Api didn't return count"
			];
		return (object)[
			'status'=>'success',
			'count'=>$response->body->count
		];
	}
	public function subscribe($petiton_code,$name,$email,$phone,$agree_newsletter){
		if(preg_match('/(\S+)(\s+(\S+))?/',trim($name),$matches)){
			$fname = $matches[1];
			$lname = isset($matches[3]) ? $matches[3] : '';
		}else
			return (object)[
				'status'=>'error',
				'error'=>"Невалидно име: $name"
			];
		try{
			$response = Request::post(self::API_ENDPOINT,
				[
					'Content-Type'=>'application/json',
				],
				json_encode([
					'key'=>$this->api_key,
					'petition'=>$petiton_code,
					'fname'=>$fname,
					'lname'=>$lname,
					'email'=>$email,
					'phone'=>$phone,
					'agree'=>$agree_newsletter
				])
			);
		}catch(Exception $exc){
			return (object)[
				'status'=>'error',
				'error'=>$exc->getMessage()
			];
		}
		if($response->code != 200)
			return (object)[
				'status'=>'error',
				'error'=>"Request code is different than 200: $response->code"
			];
		if($response->body->success == false)
			return (object)[
				'status'=>'error',
				'error'=>$response->body->error
			];
		return (object)[
			'status'=>'success',
			'pending'=>$response->body->pending
		];
	}
}

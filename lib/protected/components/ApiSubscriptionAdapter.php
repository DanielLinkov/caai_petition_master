<?php
namespace app\components;

use Unirest\Request;
use Unirest\Exception;

class ApiSubscriptionAdapter extends \yii\base\Component{
	//const API_ENDPOINT = 'https://crm.caai.bg/apis/campaign-subscription/v1/';
	const API_ENDPOINT = 'http://caai-server.localsrv.therendstudio.net/apis/campaign-subscription/v1/';
    public $api_key;
    public function get_count($petition_code){
        try{
            $response = Request::post(self::API_ENDPOINT.'get_count',
					[
						'Accept' => 'application/json'
					],
					[
						'api_key'=>$this->api_key,
						'campaignName'=>$petition_code
					]
				);
        }catch(Exception $exc){
			return (object)[
				'status'=>'error',
				'error'=>$exc->getMessage()
			];
		}
		if(isset($response->code) && $response->code != 200)
			return (object)[
				'status'=>'error',
				'error'=>"GetCount=>Request code is different than 200: $response->code: [$response->raw_body]"
			];
		if(!isset($response->body) || !is_object($response->body))
			return (object)[
				'status'=>'error',
				'error'=>"Server responce not-parsable: {$response->raw_body}"
			];
		if(!isset($response->body->status) || $response->body->status == 'error')
			return (object)[
				'status'=>'error',
				'error'=>"Server responded: {$response->body->error}"
			];
		return (object)[
			'status'=>'success',
			'count'=>$response->body->count
		];
    }
	public function subscribe($petition_code,$name,$email,$country,$phone,$agreed_to_subscribe){
		if(preg_match('/(\S+)(\s+(\S+))?/',trim($name),$matches)){
			$fname = $matches[1];
			$lname = isset($matches[3]) ? $matches[3] : '';
		}else
			return (object)[
				'status'=>'error',
				'error'=>"Невалидно име: $name"
			];
		try{
			$response = Request::post(self::API_ENDPOINT.'add',
				[
					'Accept' => 'application/json'
				],
				[
					'api_key'=>$this->api_key,
					'campaignName'=>$petition_code,
					'first_name'=>$fname,
					'last_name'=>$lname,
					'email'=>$email,
					'country'=>$country,
					'phone'=>$phone,
					'to_be_subscribed'=>$agreed_to_subscribe
				]
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
				'error'=>"Add=>Request code is different than 200: $response->code [$response->raw_body]"
			];
		if(!isset($response->body) || !is_object($response->body))
			return (object)[
				'status'=>'error',
				'error'=>"Server response not-parsable: {$response->raw_body}"
			];
		if(!isset($response->body->status) || $response->body->status == 'error')
			return (object)[
				'status'=>'error',
				'error'=>"Server responded: {$response->body->error}"
			];
		return (object)[
			'status'=>'success',
			'pending'=>$response->body->pending
		];
	}
}

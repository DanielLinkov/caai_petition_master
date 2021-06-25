<?php
namespace app\admin\models;

class PetitionConfigModel extends \yii\base\Model{
	public $template_name;
	public $page_title;
	public $page_description;
	public $code_snippets_header;
	public $code_snippets_footer;
	public $favicon;
	public $backtotop_plugin;
	public function rules(){
		return [
			[['template_name','favicon'],'string','max'=>16],
			['page_title','string','max'=>64],
			['page_description','string','max'=>256],
			['code_snippets_header','string','max'=>256],
			['code_snippets_footer','string','max'=>2048],
			['backtotop_plugin','boolean']
		];
	}
	public function serialize(){
		return json_encode([
			'template_name'=>$this->template_name,
			'page_title'=>$this->page_title,
			'page_description'=>$this->page_description,
			'code_snippets_header'=>$this->code_snippets_header,
			'code_snippets_footer'=>$this->code_snippets_footer,
			'favicon'=>$this->favicon,
			'backtotop_plugin'=>$this->backtotop_plugin,
		]);
	}
	public function unserialize($data){
		$data = json_decode($data);
		if(!$data)
			return;
		$this->template_name = $data->template_name;
		$this->page_title = $data->page_title;
		$this->page_description = $data->page_description;
		$this->code_snippets_header = $data->code_snippets_header;
		$this->code_snippets_footer = $data->code_snippets_footer;
		$this->favicon = $data->favicon;
		$this->backtotop_plugin = $data->backtotop_plugin;
	}
}

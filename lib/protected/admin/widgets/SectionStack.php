<?php
namespace app\admin\widgets;

use Yii;
use yii\helpers\Html;
use yii\bootstrap\ButtonDropdown;
use app\models\Section;

class SectionStack extends \yii\widgets\InputWidget{
	public $orientation;
	public $count_cells;
	public function init(){
		parent::init();
		if(empty($this->orientation) || empty($this->count_cells))
			throw new \yii\base\InvalidConfigException("You must specify both 'section_id', 'orientation' and 'count_cells' properties");
	}
	public function run(){
		$stack = $this->model->{$this->attribute};
		echo Html::beginTag('div',['id'=>"$this->id-container-$this->orientation"]);
		for($ind=0;$ind<$this->count_cells;$ind++){
			echo Html::activeHiddenInput($this->model,"$this->attribute[]",['id'=>"$this->id-hidden-$ind",'value'=>$stack[$ind]]);
			$cell = json_decode($stack[$ind]);
			$subsection = $cell && isset($cell->id) ? Section::findOne($cell->id) : NULL;
			$markup = ButtonDropdown::widget([
					'encodeLabel'=>false,
					'label'=>'Create Subsection',
					'containerOptions'=>['class'=>'_section_stack_insert_btn','style'=>$subsection ? 'display:none;' : NULL],
					'options'=>['class'=>'btn-success'],
					'dropdown'=>[
						'encodeLabels'=>false,
						'items'=>[
							['label'=>Section::typeIcon('text').' Text','url'=>"javascript:_section_stack_set_subsection('$this->id',$ind,'text')"],
							['label'=>Section::typeIcon('image').' Image','url'=>"javascript:_section_stack_set_subsection('$this->id',$ind,'image')"],
							['label'=>Section::typeIcon('video').' Video','url'=>"javascript:_section_stack_set_subsection('$this->id',$ind,'video')"],
							['label'=>Section::typeIcon('share').' Share','url'=>"javascript:_section_stack_set_subsection('$this->id',$ind,'share')"],
							['label'=>Section::typeIcon('form').' Form','url'=>"javascript:_section_stack_set_subsection('$this->id',$ind,'form')"],
							['label'=>Section::typeIcon('stack').' Stack','url'=>"javascript:_section_stack_set_subsection('$this->id',$ind,'stack')"],
						]
					]
				]);
			if($subsection){
				$markup .= Html::tag('div',Html::tag('b',"($subsection->type:$subsection->title)").Html::tag('hr'),['class'=>'_section_stack_subsection_info'])
							.Html::tag('div',
								Html::a('Edit',['/sections/update','id'=>$subsection->id],['class'=>'btn btn-warning btn-sm'])
								.Html::button('Remove',['class'=>'btn btn-danger btn-sm','onclick'=>"_section_stack_remove_subsection('$this->id',$ind)"])
							,['class'=>'btn-group _section_stack_actions_group']);
			}else{
			}
			echo Html::tag('div',$markup,['id'=>"$this->id-cell-$ind"]);
		}
		echo Html::endTag('div');
		Yii::$app->controller->view->registerCss("
			#$this->id-container-horizontal{
				display:flex;
				flex-direction:row;
			}
			#$this->id-container-vertical{
				display:flex;
				flex-direction:column;
			}
			#$this->id-container-$this->orientation > div{
				padding:3px;
				margin:0 2px;
				border:dotted 1px;
				flex-grow:1;
				flex-shrink:0;
				text-align:center;
			}
			#$this->id-container-horizontal > div{
				width:".(100 / $this->count_cells)."%;
			}
		");
		Yii::$app->controller->view->registerJs("
			function _section_stack_remove_subsection(widget_id,cell_ind){
				$('#'+widget_id+'-cell-'+cell_ind)
					.find('._section_stack_subsection_info,._section_stack_actions_group')
						.remove()
					.end()
					.find('._section_stack_insert_btn')
						.show()
				$('#'+widget_id+'-hidden-'+cell_ind)
					.val('');
			}
			function _section_stack_set_subsection(widget_id,cell_ind,type){
				$('#'+widget_id+'-cell-'+cell_ind)
					.append('<div class=\'_section_stack_subsection_info\'><b>('+type+':New subsection)</b></div><div class=\'text-danger\'><small>To edit subsection please save the section first</small></div>')
					.find('._section_stack_insert_btn')
						.hide();
				$('#'+widget_id+'-hidden-'+cell_ind)
					.val(JSON.stringify({type: type}));
			}
		",Yii::$app->controller->view::POS_END);
	}
}

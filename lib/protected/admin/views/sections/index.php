<?php
use yii\helpers\Html;
use yii\bootstrap\ButtonDropdown;
use yii\grid\GridView;
use app\admin\widgets\SectionActionColumn;
use app\models\Section;
use rmrevin\yii\fontawesome\FAS;
use kartik\dialog\Dialog;

echo Dialog::widget(['overrideYiiConfirm' => true]);

$this->registerCss("
	.thumbnail{
	}
	.section-box{
		padding-top:25px;
		padding-bottom:3px;
		position:relative;
	}
	.section-box.image,
	.section-box.video{
		height:150px;
		box-sizing:content-box;
	}
	.section-box.image > *,
	.section-box.video > *{
		max-height:100%;
	}
	.tag-attributes{
		position:absolute;
		top:0;
		width:100%;
		height:25px;
	}
	.tag-attributes input{
		padding:0 3px;
		height:100%;
	}
	.tag-attributes .btn-edit{
		position:absolute;
		right:0;
		top:0;
	}
");
$this->title = 'Sections - ';
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	'Sections'
];
echo ButtonDropdown::widget([
	'encodeLabel'=>false,
	'label'=>FAS::icon('plus')->fixedWidth() . ' Add',
	'containerOptions'=>['class'=>'pull-right'],
	'options'=>['class'=>'btn-success'],
	'dropdown'=>[
		'encodeLabels'=>false,
		'items'=>[
			['label'=>Section::typeIcon('text').' Text','url'=>['add','type'=>'text']],
			['label'=>Section::typeIcon('image').' Image','url'=>['add','type'=>'image']],
			['label'=>Section::typeIcon('video').' Video','url'=>['add','type'=>'video']],
			['label'=>Section::typeIcon('share').' Share','url'=>['add','type'=>'share']],
			['label'=>Section::typeIcon('form').' Form','url'=>['add','type'=>'form']],
			['label'=>Section::typeIcon('stack').' Stack','url'=>['add','type'=>'stack']],
		]
	]
]);
?>
<h2><?=FAS::icon('puzzle-piece')->fixedWidth()?>Sections</h2>
<?php
echo GridView::widget([
	'dataProvider'=>$dataProvider,
	'columns'=>[
		[
			'attribute'=>'type',
			'format'=>'html',
			'value'=>function($model){
				return $model->typeIcon($model->type).'<br/>'.ucwords($model->type);
			}
		],
		[
			'label'=>'Pages',
			'format'=>'html',
			'value'=>function($model){
				$markup = '';
				if($model->mask_pages & $model::MASK_PAGE_MAIN)
					$markup .= Html::tag('div',Html::tag('span','Main Page',['class'=>'label label-primary']));
				if($model->mask_pages & $model::MASK_PAGE_THANKS)
					$markup .= Html::tag('div',Html::tag('span','Thanks Page',['class'=>'label label-success']));
				return $markup;
			}
		],
		'title',
		[
			'label'=>'Preview',
			'format'=>'raw',
			'value'=>function($model){
				return Html::tag('div',$this->context->renderPreview($model),['class'=>'thumbnail']);
			}
		],
		[
			'class'=>SectionActionColumn::class,
			'headerOptions'=>['width'=>70],
			'template'=>'<div class="btn-group btn-block">{move_up} {move_down}</div> {delete}',
			'buttons'=>[
				'move_up'=>function($url,$model){
					return Html::a(FAS::icon('caret-up'),$url,['class'=>'btn btn-primary btn-xs','title'=>'Move the section one position up']);
				},
				'move_down'=>function($url,$model){
					return Html::a(FAS::icon('caret-down'),$url,['class'=>'btn btn-primary btn-xs','title'=>'Move the section one position down']);
				},
			]
		]
	]
]);

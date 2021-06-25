<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use rmrevin\yii\fontawesome\FAS;
use kartik\dialog\Dialog;

echo Dialog::widget(['overrideYiiConfirm' => true]);

$this->title = 'Petitions - ';
$this->params['breadcrumbs'] = [
	'Petitions'
];
echo Html::a(FAS::icon('plus')->fixedWidth() . ' Add',['add'],['class'=>'btn btn-success pull-right']);
?>
<h2><?=FAS::icon('file-signature')?> Petitions</h2>
<div class="alert alert-info"><em>* Tip:</em> <b>To edit a petition page first [select to manage] it from the list below to make it current</b></div>
<?php
echo GridView::widget([
	'dataProvider'=>$dataProvider,
	'columns'=>[
		[
			'attribute'=>'name',
			'format'=>'raw',
			'value'=>function($model){
				return Html::tag('b',Html::encode($model->name))
					.(Yii::$app->active_petition->id == $model->id ? Html::tag('sup','<em><b> *Active*</b></em>',['class'=>'text-danger']) : NULL);
			}
		],
		[
			'attribute'=>'hostname',
			'format'=>'raw',
			'value'=>function($model){
				return Html::a($model->hostname,"http://$model->hostname",['target'=>'_blank']);
			}
		],
		'code',
		[
			'class'=>ActionColumn::className(),
			'header'=>'Actions',
			'headerOptions'=>['width'=>180],
			'template'=>'{select}<br/>{configuration} {duplicate} {update} {delete}',
			'buttons'=>[
				'select'=>function($url,$model){
					return Html::a(FAS::icon('hand-pointer')->fixedWidth() . ' Select to manage',$url,['class'=>'btn btn-danger btn-sm btn-block']);
				},
				'configuration'=>function($url,$model){
					return Html::a(FAS::icon('cog')->fixedWidth() . ' Configuration',$url,['class'=>'btn btn-info btn-xs']);
				},
				'duplicate'=>function($url,$model){
					return Html::a(FAS::icon('clone')->fixedWidth(),$url,['title'=>'Duplicate petition']);
				}
			]
		]
	]
]);
?>

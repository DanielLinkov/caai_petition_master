<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use rmrevin\yii\fontawesome\FAS;
use kartik\dialog\Dialog;

echo Dialog::widget(['overrideYiiConfirm' => true]);

$this->title = 'Users - ';
$this->params['breadcrumbs'] = [
	'Users'
];
echo Html::a(FAS::icon('plus')->fixedWidth() . ' Add',['add'],['class'=>'btn btn-success pull-right']);
?>
<h2><?=FAS::icon('users')?> Users</h2>
<?php
echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        [
            'attribute'=>'name',
        ],
        [
            'attribute'=>'role',
            'headerOptions'=>['width'=>100],
        ],
        [
            'attribute'=>'username',
            'headerOptions'=>['width'=>180],
        ],
        [
            'attribute'=>'flag_enabled',
            'headerOptions'=>['width'=>80],
            'format'=>'raw',
            'value'=>function($model,$key){
                return $model->flag_enabled
                    ? Html::a(Html::tag('b','Yes',['class'=>'btn btn-success btn-sm']),['toggle_flag_enabled','id'=>$key])
                    : Html::a(Html::tag('b','No',['class'=>'btn btn-danger btn-sm']),['toggle_flag_enabled','id'=>$key]);
            }
        ],
        [
            'class'=>ActionColumn::className(),
            'headerOptions'=>['width'=>200],
            'template'=>'<div class="btn-group">{update}{change_password}{delete}</div>',
            'buttons'=>[
                'update'=>function($url,$model,$key){
                    return Html::a('Update',['update','id'=>$key],['class'=>'btn btn-primary btn-sm','title'=>'Update user details']);
                },
                'change_password'=>function($url,$model,$key){
                    return Html::a(FAS::icon('lock')->fixedWidth(),['change_password','id'=>$key],['title'=>'Change user password','class'=>'btn btn-warning btn-sm']);
                },
                'delete'=>function($url,$model,$key){
                    return Html::a(FAS::icon('trash').' Delete',['delete','id'=>$key],['title'=>'Delete user !!!','data-method'=>'post','data-confirm'=>'Are you sure you want to delete this user?<h4>Perhaps you can just disable it?!</h4>','class'=>'btn btn-danger btn-sm']);
                }
            ]
        ]
    ]
]);
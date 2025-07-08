<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Edit Image -';
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	['label'=>'Images','url'=>['index']],
	'Edit Image'
];
?>
<h2>Edit Image</h2>
<?php
echo Html::img(Url::to(['thumbnail','image_name'=>$id]),['class'=>'img-thumbnail']);
echo Html::tag('hr');
$form = ActiveForm::begin([
	'layout'=>'horizontal'
]);
echo $form->field($model,'name',['enableAjaxValidation'=>true])->hint("* The filename of the image");
echo Html::submitButton('Update',['class'=>'btn btn-primary btn-block']);
ActiveForm::end();

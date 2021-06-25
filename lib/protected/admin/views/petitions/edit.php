<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$verb = $model->isNewRecord ? 'Add' : 'Edit';
$this->title = "$verb Petition - ";
$this->params['breadcrumbs'] = [
	['label'=>'Petitions','url'=>['index']],
	"$verb Petition"
];
?>
<h2><?=$verb?> petition <?=!$model->isNewRecord ? "<q>$model->name</q>" : NULL?></h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal',
	'fieldConfig'=>[
		'inputOptions'=>['maxlength'=>true],
	]
]);
echo $form->field($model,'name');
echo $form->field($model,'hostname');
echo $form->field($model,'code');
echo Html::tag('div',Html::submitButton("Save",['class'=>'btn btn-primary btn-block']),['class'=>'col-sm-6 col-sm-offset-3']);
ActiveForm::end();
?>

<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Change password - ';
$this->params['breadcrumbs'] = [
    'Change password'
];
?>
<h2>Change password</h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal',
	'fieldConfig'=>[
		'inputOptions'=>['maxlength'=>true],
	]
]);
echo $form->field($model,'input_password')->passwordInput();
echo $form->field($model,'repeat_password')->passwordInput();

echo Html::tag('div',Html::submitButton("Update",['class'=>'btn btn-primary btn-block']),['class'=>'col-sm-6 col-sm-offset-3']);
ActiveForm::end();
?>
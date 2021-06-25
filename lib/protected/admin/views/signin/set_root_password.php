<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Set root password - ';
$this->registerCss("
.form{
	width:300px;
	margin:100px auto 20px auto;
}
");
?>
<div class="form">
<h3 class="text-center">Set Root Password</h3>
<div class="alert alert-warning">This will create your <b>root</b> account. Please use strong password. You can change your password later</div>
<?php
$form = ActiveForm::begin();
echo $form->field($model,'password');
echo Html::submitButton("Save",['class'=>'btn btn-primary btn-block']);
ActiveForm::end();
?>
</div>

<?php
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use kartik\helpers\Html;

$this->title = 'Sign In - ';
$this->registerCss("
.form{
	width:300px;
	margin:100px auto 20px auto;
}
");
?>
<div class="form">
<h2 class="text-center"><?=Yii::$app->name?></h2>
<h4 class="text-center">Sign In <?=Html::icon('log-in')?></h4>
<?php
$form = ActiveForm::begin();
if($model->hasErrors('signin'))
	echo $form->errorSummary($model);
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'captcha',['inputOptions'=>['class'=>'form-control','autocomplete'=>'off']])->widget(Captcha::class,['captchaAction'=>'signin/captcha']);
echo Html::submitButton("Sign In",['class'=>'btn btn-primary btn-block']);
ActiveForm::end();
?>
</div>

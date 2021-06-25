<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Upload New Image -';
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	['label'=>'Images','url'=>['index']],
	'Upload New Image'
];
?>
<h2>Upload New Image</h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal'
]);
echo $form->field($model,'name',['enableAjaxValidation'=>true])->hint("The filename of the image");
echo $form->field($model,'image')
	->widget(FileInput::className(),[
		'options'=>['accept'=>'image/*'],
		'pluginOptions'=>[
			'showUpload'=>false,
			'allowedFileExtensions'=>['jpg','jpeg','png','gif'],
			'maxFileSize'=>1024,
		]
	]);
echo Html::submitButton('Upload',['class'=>'btn btn-primary btn-block']);
ActiveForm::end();

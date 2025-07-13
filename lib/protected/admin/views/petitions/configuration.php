<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\FileHelper;

$this->title = 'Configuration - ';
$this->params['breadcrumbs'] = [
	['label'=>'Petitions','url'=>['index']],
	"Petition Configuration"
];
$template_names = [
	'brite5',
	'darkly',
	'darkly5',
	'cyborg',
	'cyborg5',
	'cerulan',
	'cerulean5',
	'spacelab',
	'spacelab5',
	'pulse',
	'pulse5',
	'materia',
	'materia5',
	'morph5',
	'lumen',
	'lumen5',
	'slate5',
	'united',
	'united5',
	'vapor5'
];
$image_names = [];
foreach(FileHelper::findFiles(Yii::getAlias('@petitionsroot/'.Yii::$app->request->get('id').'/images'),['recursive'=>false]) AS $image_name){
	$image_name = substr($image_name,strrpos($image_name,'/')+1);
	$image_names[$image_name] = $image_name;
}
?>
<h2>Petition Configuration</h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal',
	'fieldConfig'=>[
		'inputOptions'=>['maxlength'=>true]
	]
]);
echo $form->field($model,'template_name')->dropDownList(array_combine($template_names,$template_names));
echo $form->field($model,'favicon')->dropDownList(array_combine($image_names,$image_names));
echo $form->field($model,'page_title');
echo $form->field($model,'page_description');
echo $form->field($model,'code_snippets_footer')->textArea(['rows'=>10])->hint("To put in page footer <b>(prefered location for performance sake)</b> as raw markup (4096 chars max)");
echo $form->field($model,'code_snippets_header')->textArea(['rows'=>3])->hint("To put in page header as raw markup (256 chars max)");
echo $form->field($model,'backtotop_plugin')->checkbox(['label'=>'Show back-to-top indicator']);
echo Html::submitButton('Update',['class'=>'btn btn-block btn-primary']);
ActiveForm::end();

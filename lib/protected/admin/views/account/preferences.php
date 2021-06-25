<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Preferences - ';
$this->params['breadcrumbs'] = [
	'Account',
	"Preferences"
];
?>
<h2>Preference</h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal'
]);
echo $form->field($model,'text_editor')->dropDownList(['summernote'=>'Summernote (rich editor)','codemirror'=>'CodeMirror (code editor)']);
echo $form->field($model,'vim_mode')->checkbox(['label'=>'Vim mode'])->hint('Applicable for CodeMirror editor only');
echo Html::submitButton('Save',['class'=>'btn btn-block btn-primary']);
ActiveForm::end();

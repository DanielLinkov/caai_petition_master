<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$verb = $model->isNewRecord ? 'Add' : 'Edit';
$this->title = "$verb Petition - ";
$this->params['breadcrumbs'] = [
	['label'=>'Users','url'=>['index']],
	"$verb User account"
];
?>
<h2><?=$verb?> user account <?=!$model->isNewRecord ? "of <q>$model->name</q> (username:<b>$model->username</b>)" : NULL?></h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal',
	'fieldConfig'=>[
		'inputOptions'=>['maxlength'=>true],
	]
]);
echo $form->field($model,'name');
if($model->isNewRecord)
	echo $form->field($model,'username');
echo $form->field($model,'role')->dropDownList(['admin'=>'Administrator (and publisher)','publisher'=>'Publisher (only)']);
echo $form->field($model,'permission_flags')->checkboxList([
	$model::PF_SECTION_ADD=>'Section Add',
	$model::PF_SECTION_DELETE=>'Section Delete',
	$model::PF_SECTION_EDIT_CONTENT=>'Section Edit Content',
	$model::PF_SECTION_EDIT_META=>'Section Edit Meta',
	$model::PF_SECTION_EDIT_DESIGN=>'Section Edit Design',
	$model::PF_IMAGE_UPLOAD=>'Image Upload',
	$model::PF_IMAGE_DELETE=>'Image Delete',
	$model::PF_IMAGE_REPLACE=>'Image Replace',
	$model::PF_CSS_EDIT=>'Css Edit',
])->hint('
<button type="button" class="btn btn-default btn-sm" onclick="$(this).closest(&quot;.form-group&quot;).find(&quot;input&quot;).prop(&quot;checked&quot;,true);">Select All</button>
<button type="button" class="btn btn-default btn-sm" onclick="$(this).closest(&quot;.form-group&quot;).find(&quot;input&quot;).prop(&quot;checked&quot;,false);">Deselect All</button>
');
if($model->isNewRecord){
	echo $form->field($model,'input_password');
	echo $form->field($model,'repeat_password');
}
echo Html::tag('div',Html::submitButton("Save",['class'=>'btn btn-primary btn-block']),['class'=>'col-sm-6 col-sm-offset-3']);
ActiveForm::end();
?>

<?php
$this->registerCss("
label{
	user-select:none;
}
");
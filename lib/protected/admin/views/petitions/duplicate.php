<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Duplicate Petition - ';
$this->params['breadcrumbs'] = [
	['label'=>'Petitions','url'=>['index']],
	'Duplicate Petitioin'
];
?>
<h2>Duplicate Petition <q><?= $petition->name ?></q></h2>
<?php
$form = ActiveForm::begin([
	'layout'=>'horizontal',
]);
echo $form->field($model,'target_petition_id')->dropDownList($petitions,['prompt'=>'[Select an empty target petition]']);
echo $form->field($model,'flag_rename_css_rules')->checkbox(['label'=>'Rename section id css rules (.section-##) with the new section ids']);
echo Html::submitButton('Duplicate',['class'=>'btn btn-primary btn-block']);
ActiveForm::end();

<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\FileHelper;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use rmrevin\yii\fontawesome\FAS;
use kartik\select2\Select2;
use app\admin\widgets\CodemirrorWidget;
use app\models\Section;
use app\admin\assets\SummernoteEditorAsset;
use app\admin\widgets\SectionStack;

SummernoteEditorAsset::register($this);

$verb = $model->isNewRecord ? 'New' : 'Edit';
$this->title = "$verb Section - ";
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	['label'=>'Sections','url'=>['index']],
	"$verb"
];
$this->registerCss("
	.img-thumbnail{
		background-image:url('/images/checker-pattern.gif');
	}
	.CodeMirror{
		height:auto;
	}

");
if($model->parent_id)
	echo Html::a('&lsh; To Parent Section',['update','id'=>$model->parent_id],['class'=>'btn btn-success btn-sm']);
$image_names = [];
foreach(FileHelper::findFiles(Yii::getAlias('@petitionsroot/'.Yii::$app->active_petition->id.'/images'),['recursive'=>false]) AS $image_name){
	$image_name = substr($image_name,strrpos($image_name,'/')+1);
	$image_names[$image_name] = $image_name;
}
$editor_hint = Html::tag('div',"You can switch the editor from your ".Html::a('preferences',['/account/preferences']));
?>
<h2><?=$verb?> <?=Section::typeIcon($model->type)?> <b><?=ucfirst($model->type)?></b> Section <?=!$model->isNewRecord ? "<q>$model->title</q>" : NULL?></h2>
<?php
$form = ActiveForm::begin([
	'layout'=>$model->type != 'text' ? 'horizontal' : 'default',
	'fieldConfig'=>[
		'inputOptions'=>['maxlength'=>true]
	]
]);
echo $form->field($model,'mask_page_main')->checkbox(['label'=>'Visible on main page']);
echo $form->field($model,'mask_page_thanks')->checkbox(['label'=>'Visible on thanks page']);
echo $form->field($model,'title',['enableAjaxValidation'=>true]);
echo $form->field($model,'tag_id');
echo $form->field($model,'tag_additional_classes');
$urlThumbnail = Url::to(['/images/thumbnail']);
$urlFetchImageList = Url::to(['/images/ajax_get_list']);
$this->registerJs("
	function formatImageName(image_name){
		if(!image_name.id)
			return image_name.text;
		return '<img src=\'$urlThumbnail&image_name=' + image_name.text + '\' class=\'img-thumbnail\'/> ' + image_name.text;
	}
",$this->context->view::POS_HEAD);
echo $form->field($model,'background_image')
	->hint(Html::a('Upload more images',['/images/upload'],['target'=>'Images']).' then <button class="btn btn-xs btn-primary" type="button" onclick="populate_background_image_lists()">Refresh Lists</button>')
	->widget(Select2::class,[
		'data'=>[],
		'options'=>['placeholder'=>'None'],
		'pluginOptions'=>[
			'allowClear'=>true,
			'templateResult'=>new JsExpression('formatImageName'),
			'escapeMarkup'=>new JsExpression("function(m) { return m; }")
		]
	]);
echo $form->field($model,'background_image_mobile')
	->widget(Select2::class,[
		'data'=>[],
		'options'=>['placeholder'=>'None'],
		'pluginOptions'=>[
			'allowClear'=>true,
			'templateResult'=>new JsExpression('formatImageName'),
			'escapeMarkup'=>new JsExpression("function(m) { return m; }")
		]
	]);
$this->registerJs("
window.populate_background_image_lists = function(){
	$.getJSON('$urlFetchImageList')
		.done(function(list){
			let selection = $('#section-background_image').val() || ".json_encode($model->background_image).";
			$('#section-background_image')
				.find('option:not(:first-child)')
					.remove()
				.end()
				.append($.map(list,function(image_name){return $('<option></option>').text(image_name).attr('value',image_name).attr('selected',selection == image_name);}));
			selection = $('#section-background_image_mobile').val() || ".json_encode($model->background_image_mobile).";
			$('#section-background_image_mobile')
				.find('option:not(:first-child)')
					.remove()
				.end()
				.append($.map(list,function(image_name){return $('<option></option>').text(image_name).attr('value',image_name).attr('selected',selection == image_name);}));
		});
}
populate_background_image_lists();
");
switch($model->type){
	case 'text':
		$preferences = json_decode(Yii::$app->user->identity->preferences);
		$editor_hint = Html::tag('div','Use the <b>&lt;collapse display="<em>Optional button text</em>"&gt;</b>..long text...<b>&lt;/collapse&gt;</b> tags to surround expandable block of text').$editor_hint;
		switch($preferences ? $preferences->text_editor : NULL){
			case 'codemirror':
			default:
				echo $form->field($dataModel,'content')->widget(CodemirrorWidget::class,[
					'preset'=>'html',
					'presetsDir'=>Yii::getAlias('@app/admin/codemirror_presets'),
					'settings'=>[
						'keyMap'=>$preferences && $preferences->vim_mode ? 'vim' : 'default',
						'viewportMargin'=> new JsExpression('Infinity')
					]
				])->hint($editor_hint);
				echo Html::tag('div',"* In the editor press:<ul><li><b>F11</b> to switch to full screen</li><li><b>Ctrl+Space</b> for autocomplete</li></ul>",['class'=>'alert alert-info']);
				break;
			case 'summernote':
				echo $form->field($dataModel,'content')->textArea()->hint($editor_hint);
				$this->registerJs("
					$('#textdatamodel-content').summernote({
						toolbar: [
							['actions',['undo','redo']],
							['style',['bold','italic','underline','clear']],
							['font',['strikethrough','superscript','subscript']],
							['fontsize',['fontsize']],
							['insert',['ol','ul','link','table','picture','hr']],
							['paragraph',['style','paragraph']],
							['misc',['fullscreen','codeview']]
						],
						height: 380,
					});
				")->hint($editor_hint);
				break;
		}
		break;
	case 'stack':
		echo $form->field($dataModel,'container_tag_additional_classes');
		echo $form->field($dataModel,'orientation')->radioList(['horizontal'=>FAS::icon('ellipsis-h')->fixedWidth().' Horizontal','vertical'=>FAS::icon('ellipsis-v')->fixedWidth().' Vertical'],['encode'=>false]);
		if(empty($model->data)){
			echo $form->field($dataModel,'count_cells')->input('range',['min'=>2,'max'=>4])->hint('<span id="count_cells"></span>');
			$this->registerJs("
				$('#stackdatamodel-count_cells')
					.change(function(event){
						$('#count_cells').text(event.target.value);
					})
					.trigger('change');
			");
		}else{
			echo $form->field($dataModel,'stack')->widget(SectionStack::class,['orientation'=>$dataModel->orientation,'count_cells'=>$dataModel->count_cells]);
		}
		break;
	case 'image':
		echo $form->field($dataModel,'image_name')
			->hint(Html::a('Upload more images',['/images/upload'],['target'=>'Images']).' then <button class="btn btn-xs btn-primary" type="button" onclick="populate_image_list()">Refresh List</button>')
			->widget(Select2::class,[
				'data'=>[],
				'pluginOptions'=>[
					'templateResult'=>new JsExpression('formatImageName'),
					'escapeMarkup'=>new JsExpression("function(m) { return m; }")
				]
			]);
		$this->registerJs("
		window.populate_image_list = function(){
			$.getJSON('$urlFetchImageList')
				.done(function(list){
					let selection = $('#imagedatamodel-image_name').val() || ".json_encode($dataModel->image_name).";
					$('#imagedatamodel-image_name')
						.empty()
						.append($.map(list,function(image_name){return $('<option></option>').text(image_name).attr('value',image_name).attr('selected',selection == image_name);}));
				});
		}
		populate_image_list();
		");
		echo $form->field($dataModel,'is_poppable')->checkbox(['label'=>'Image can be popped by clicking on it']);
		echo $form->field($dataModel,'caption');
		break;
	case 'video':
		echo $form->field($dataModel,'embed_code')->textArea(['rows'=>6]);
		break;
	case 'share':
		echo Html::tag('h3','Facebook meta tags');
		echo $form->field($dataModel,'og_url');
		echo $form->field($dataModel,'og_type');
		echo $form->field($dataModel,'og_title');
		echo $form->field($dataModel,'og_description');
		echo $form->field($dataModel,'og_image')
			->hint(Html::a('Upload more images',['/images/upload']).' then <button class="btn btn-xs btn-primary" type="button" onclick="populate_og_image_list()">Refresh List</button>')
			->widget(Select2::class,[
				'data'=>$image_names,
				'options'=>['placeholder'=>'None'],
				'pluginOptions'=>[
					'allowClear'=>true,
					'templateResult'=>new JsExpression('formatImageName'),
					'escapeMarkup'=>new JsExpression("function(m) { return m; }")
				]
			]);
		$this->registerJs("
		window.populate_og_image_list = function(){
			$.getJSON('$urlFetchImageList')
				.done(function(list){
					let selection = $('#sharedatamodel-og_image').val() || ".json_encode($dataModel->og_image).";
					$('#sharedatamodel-og_image')
						.find('option:not(:first-child)')
							.remove()
						.end()
						.append($.map(list,function(image_name){return $('<option></option>').text(image_name).attr('value',image_name).attr('selected',selection == image_name);}));
				});
		}
		populate_og_image_list();
		");
		break;
	case 'form':
		$preferences = json_decode(Yii::$app->user->identity->preferences);
		switch($preferences ? $preferences->text_editor : NULL){
			case 'codemirror':
			default:
				echo Html::tag('div',Html::tag('div',"* In the editor press:<ul><li><b>F11</b> to switch to full screen</li><li><b>Ctrl+Space</b> for autocomplete</li></ul>$editor_hint",['class'=>'alert alert-info col-sm-6 col-sm-offset-3']),['class'=>'row']);
				echo $form->field($dataModel,'head')->widget(CodemirrorWidget::class,[
					'preset'=>'html',
					'presetsDir'=>Yii::getAlias('@app/admin/codemirror_presets'),
					'settings'=>[
						'keyMap'=>$preferences && $preferences->vim_mode ? 'vim' : 'default',
						'viewportMargin'=> new JsExpression('Infinity')
					]
				])->hint("Use the <b>{count_signed}</b> and <b>{count_remaining}</b> placeholders which will be replaced with actual numbers");
				echo $form->field($dataModel,'terms')->widget(CodemirrorWidget::class,[
					'preset'=>'html',
					'presetsDir'=>Yii::getAlias('@app/admin/codemirror_presets'),
					'settings'=>[
						'keyMap'=>$preferences && $preferences->vim_mode ? 'vim' : 'default',
						'viewportMargin'=> new JsExpression('Infinity')
					]
				]);
				break;
			case 'summernote':
				echo $form->field($dataModel,'head')->textArea()->hint("Use the <b>{count_signed}</b> and <b>{count_remaining}</b> placeholders which will be replaced with actual numbers".$editor_hint);
				echo $form->field($dataModel,'terms')->textArea();
				$this->registerJs("
					$('#formdatamodel-head,#formdatamodel-terms').summernote({
						toolbar: [
							['actions',['undo','redo']],
							['style',['bold','italic','underline','clear']],
							['font',['strikethrough','superscript','subscript']],
							['fontsize',['fontsize']],
							['insert',['ol','ul','link','table','picture','hr']],
							['paragraph',['style','paragraph']],
							['misc',['fullscreen','codeview']]
						],
						height: 180,
					});
				");
				break;
		}
		echo $form->field($dataModel,'button_class');
		echo $form->field($dataModel,'button_text');
		echo $form->field($dataModel,'show_phone_field')->checkbox(['label'=>'Show phone field']);
		echo $form->field($dataModel,'show_country_field')->checkbox(['label'=>'Show counry field']);
		break;
}
echo Html::submitButton('Save & Continue to Edit',['name'=>'action','value'=>'edit','class'=>'btn btn-success btn-lg btn-block']);
echo Html::submitButton('Save',['name'=>'action','class'=>'btn btn-primary btn-lg btn-block']);
ActiveForm::end();
?>

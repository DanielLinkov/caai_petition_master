<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\admin\widgets\CodemirrorWidget;
use rmrevin\yii\fontawesome\FAS;
use dosamigos\formhelpers\GoogleFontPicker;

$this->title = 'Css - ';
$this->params['breadcrumbs'] = [
	['label'=>"Petitions",'url'=>['/petitions']],
	'active_petition',
	"Css"
];
$this->registerCss("
.CodeMirror{
	height:600px;
}
.bfh-selectbox .bfh-selectbox-options{
	width:250px;
}
");
echo Html::button(FAS::icon('save').' Save',['id'=>'save','class'=>'btn btn-primary pull-right']);
?>
<h2>Css Code</h2>
<div class="row">
	<div class="col-sm-2">
		Cyrillic Google Fonts:
		<div><small>See <a href="https://fonts.google.com/?subset=cyrillic" target=_blank>all cyrillic fonts</a></small></div>
	</div>
	<div class="col-sm-4">
		<?= GoogleFontPicker::widget([
			'name'=>'font',
			'selectBox'=>true,
			'clientOptions'=>[
				'subset'=>'cyrillic'
			],
			'clientEvents'=>[
				'change.bfhselectbox'=>'function(event){ $("#a-customize-font").removeClass("disabled").attr("href","https://fonts.google.com/specimen/"+event.target.value.replace(" ","+"));}'
			]
		]);
		?>
	</div>
	<div class="col-sm-6">
		<a id="a-customize-font" target=_blank href="" class="btn btn-primary disabled"><?= FAS::icon('font') ?> Customize & Get Embed Code</a>
	</div>
</div>
<?php
$preferences = json_decode(Yii::$app->user->identity->preferences);
echo CodemirrorWidget::widget([
	'id'=>'css',
	'name'=>'css',
	'value'=>$css,
	'preset'=>'css',
	'presetsDir'=>Yii::getAlias('@app/admin/codemirror_presets'),
	'settings'=>[
		'height'=>500,
		'keyMap'=>$preferences && $preferences->vim_mode ? 'vim' : 'default',
	]
]);
echo Html::tag('div',"* In the editor press:<ul><li><b>F11</b> to switch to full screen</li><li><b>Ctrl+Space</b> for autocomplete</li></ul>",['class'=>'alert alert-info']);
$url_save = Url::to(['ajax_save']);
$this->registerJs("
	$('#save').click(function(event){
		event.target.disabled = true;
		$.post('$url_save',{
			css: cm_css.getValue()
		})
		.done(function(){
		})
		.fail(function(xhr){
			alert('Error: '+xhr.responseText);
		})
		.always(function(){
			event.target.disabled = false;
		});
	});
	document.addEventListener('keydown',function(event){
		if(event.key == 's' && (event.ctrlKey || event.metaKey)){
			event.preventDefault();
			document.getElementById('save').click();
		}
	});
");

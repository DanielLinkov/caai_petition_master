<?php
namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use rmrevin\yii\fontawesome\FAS;
use rmrevin\yii\fontawesome\FAB;
use app\models\FormModel;
use app\assets\FancyboxAsset;
use akavov\countries\widgets\CountriesSelectizeTextInput;
use borales\extensions\phoneInput\PhoneInput;

class Section extends \yii\db\ActiveRecord{
	const MASK_PAGE_MAIN = 1;
	const MASK_PAGE_THANKS = 2;
	public $mask_page_main = 1;
	public $mask_page_thanks;
	use PetitionObjectTrait {
		behaviors as private traitBehaviors;
	}
	public function init(){
		$this->on(self::EVENT_AFTER_FIND,function($event){
			$this->mask_page_main = (boolean)($this->mask_pages & self::MASK_PAGE_MAIN);
			$this->mask_page_thanks = (boolean)($this->mask_pages & self::MASK_PAGE_THANKS);
		});
		return parent::init();
	}
	public function behaviors(){
		return array_merge($this->traitBehaviors(),[
			[
				'class'		=>	AttributeBehavior::class,
				'attributes'=>	[
					ActiveRecord::EVENT_BEFORE_INSERT	=>	'order_ind'
				],
				'value'		=>	function(){
					if($this->parent_id)
						return 0;
					if($this->owner->order_ind)
						return $this->owner->order_ind;
					return \Yii::$app->db->createCommand("SELECT MAX(order_ind) FROM ".$this->tableName()." WHERE parent_id IS NULL AND petition_id=$this->petition_id")->queryScalar() + 1;
				}
			],
			[
				'class'		=>	AttributeBehavior::class,
				'attributes'=>	[
					ActiveRecord::EVENT_BEFORE_INSERT	=>	'mask_pages',
					ActiveRecord::EVENT_BEFORE_VALIDATE	=>	'mask_pages'
				],
				'value'		=>	function(){
					return ($this->mask_page_main ? self::MASK_PAGE_MAIN : 0) | ($this->mask_page_thanks ? self::MASK_PAGE_THANKS : 0);
				}
			]
		]);
	}
	public static function typeIcon($type){
		switch($type){
			case 'text':
				return FAS::icon('font')->fixedWidth();
			case 'image':
				return FAS::icon('image')->fixedWidth();
			case 'video':
				return FAB::icon('youtube')->fixedWidth();
			case 'share':
				return FAS::icon('share-alt')->fixedWidth();
			case 'form':
				return FAS::icon('edit')->fixedWidth();
			case 'stack':
				return FAS::icon('layer-group')->fixedWidth();
			default:
				throw new \yii\base\InvalidArgumentException("Invalid section type: $type");
		}
	}
	public function getDataModel(){
		switch($this->type){
			case 'text':
				return new TextDataModel;
			case 'stack':
				return new StackDataModel;
			case 'image':
				return new ImageDataModel;
			case 'video':
				return new VideoDataModel;
			case 'share':
				return new ShareDataModel;
			case 'form':
				return new FormDataModel;
			default:
				throw new \yii\web\ServerErrorHttpException("Invalid section type: $this->type");
		}
	}
	public function render(){
		$dataModel = $this->getDataModel();
		if($dataModel)
			$dataModel->unserialize($this->data);
		ob_start();
		echo Html::beginTag('section',['id'=>$this->tag_id,'class'=>"section-$this->type section-$this->id $this->tag_additional_classes",'style'=>$this->background_image ? 'background-image:url("'.Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$this->background_image").'");' : NULL]);
		if($this->background_image_mobile)
			Yii::$app->controller->view->registerCss("@media(max-width:767px){section.section-$this->id{background-image:url('".Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$this->background_image_mobile")."') !important;}");
		if(!$this->parent_id)
			echo Html::beginTag('div',['class'=>'container']);
		switch($this->type){
			case 'text':
				echo $dataModel->content;
				Yii::$app->controller->view->registerJs("
					$('.section-text collapse').each(function(ind,collapse){
						$(collapse).replaceWith(
							$('<button class=\"btn btn-lg btn-block btn-expand\"></button>')
								.text(collapse.getAttribute('display') || 'Виж още')
								.data('content',collapse.innerHTML)
								.click(function(event){
									$(this).replaceWith($(this).data('content'));
								})
						)
					});
				");
				break;
			case 'video':
				echo $dataModel->embed_code;
				break;
			case 'image':
				if($dataModel->is_poppable){
					FancyboxAsset::register(Yii::$app->controller->view);
					$imgUrl = Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$dataModel->image_name");
					echo Html::a(Html::img($imgUrl,['class'=>'img-thumbnail']),$imgUrl,['data-fancybox'=>true,'data-caption'=>$dataModel->caption]);
				}else
					echo Html::img(Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$dataModel->image_name"),['class'=>'img-thumbnail']);
				break;
			case 'share':
				if(!isset(Yii::$app->controller->view->params['additional_head_tags']))
					Yii::$app->controller->view->params['additional_head_tags'] = [];
				Yii::$app->controller->view->params['additional_head_tags'][] = [
					'tag'=>'meta',
					'content'=>NULL,
					'attributes'=>[
						'property'=>'og:url',
						'content'=>$dataModel->og_url
					]
				];
				Yii::$app->controller->view->params['additional_head_tags'][] = [
					'tag'=>'meta',
					'content'=>NULL,
					'attributes'=>[
						'property'=>'og:type',
						'content'=>$dataModel->og_type
					]
				];
				Yii::$app->controller->view->params['additional_head_tags'][] = [
					'tag'=>'meta',
					'content'=>NULL,
					'attributes'=>[
						'property'=>'og:title',
						'content'=>$dataModel->og_title
					]
				];
				Yii::$app->controller->view->params['additional_head_tags'][] = [
					'tag'=>'meta',
					'content'=>NULL,
					'attributes'=>[
						'property'=>'og:description',
						'content'=>$dataModel->og_description
					]
				];
				if($dataModel->og_image)
					Yii::$app->controller->view->params['additional_head_tags'][] = [
						'tag'=>'meta',
						'content'=>NULL,
						'attributes'=>[
							'property'=>'og:image',
							'content'=>Yii::getAlias('@petitions/'.Yii::$app->active_petition->id."/images/$dataModel->og_image")
						]
					];
				echo Html::a(Html::img('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjZmZmZmZmIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgY2xhc3M9ImZlYXRoZXIgZmVhdGhlci1mYWNlYm9vayI+CiAgPHBhdGggZD0iTTE4IDJoLTNhNSA1IDAgMCAwLTUgNXYzSDd2NGgzdjhoNHYtOGgzbDEtNGgtNFY3YTEgMSAwIDAgMSAxLTFoM3oiPjwvcGF0aD4KPC9zdmc+Cg==').' Споделете във Facebook','https://www.facebook.com/sharer/sharer.php?u='.Yii::$app->request->hostInfo,['class'=>'btn btn-share btn-facebook','data-type'=>'facebook','target'=>'_blank']);
				echo Html::a(Html::img('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAYAAADDPmHLAAAD4klEQVR4nO3d4XHaTBCAYUpICZTgDkwHTgdRB3EHpoO4g9BBvg6gg7gD6MB08H4/VnKA4BiwpD1J7zPDTGzHw2lvT9o7HfJsJkmSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJI0JcAc8Aev61djWX/8EvgFfstuqFtWduuU6v4D5De+1ABbtH4WuBny9oeNP/bjkjFB3/Lr+nemcQYAVUGW34xRxqm/LFrg78x5z4DvHSbZKONwcdQAafwUoC3Etb9srUUPcE8n1+8z/2TOx0f94GqAC2tRF51/qa/bx9wrYnAQgNQmAqv8+f7PKOu407wQiJQmAL/V7Z1idac88Iw69Iaref6l6bs+q824+b3nQhqYo/A28MOZ6gI8TAOCpp7bMP2xJN57q1y+OZwJ7xjz6Z7OLEwBibtzpSOC4GC3B+ItBLk8AiGvzosO2nJuSZam6Os6icF0CNH7Swdmg3f77lKrtYysWUXXf4pUWawNuS8QuVG0d02AQxc6ttsC3FtqQnQDjL/jeQztTry1RSd90aSB38Ydb2z0KtDv6Xoka4SGxDVfrKraDQSx4tO0tGfhghGEC5OqpA7ZEQnwn7sbdHbx/1iIQYALMZrPZDPgvsQ/Wie/9kh37IhBTwi4uBaXbZMc+DSfXZmKzxGemhUO0SQp/PmBJnH4fDr53B+wye6Rnq8QuyMXx/PuVuCP2RFTu2/77IsUyux/SkL8CV4Lx3/V7D7ffCxiTaS4BN5hm5d/YZ8c/HeVtxOjTJjv+6YjLwNSmfo1ldvyLQEwHp2ja1/8GcRaY0twfYJcd96IwvSnhc3bMi0P+xow+TXf+/y9MIwl22XEuGnE5GPPMYJkd4+IRhWHWR7W6Nt09gNciduusGM8sYZUd0+IRW7buqT8dW//7gbhbOHTz7PgWj/Ge+lfZsR0ExrkmsMfRfzn+fnLI0C2zYzooxPV/LFPBHVb+12M8i0KL7FgOFvCc3Xuf5Jr/ZzHcTSPjfsZPn4hHtg6pJrDqbxvDWh5eZMdrtIhEWFLuhtIqO0aTQSTDgpgxbFO7PSyzYzI5RBKskzseXOrtH/GHHLIe73rI6V6fOP7jCtmq7HhMBjHi17n9/WaP1X73iP0APyjjVN94wX397SNuAt3z5wHKJXV6Y5kdp8EiKvZn4tm86/q1TevK6zjq20Js9RrKPf8dFnrdIKr5UhNhDzxmx2gSiDNCKev8GxzxOYgaoaL/5wbuiNpknh0D1Q6SoYvPBeyJJKvs9IHgz42fR+Ju4KZ+ndsnsD/4+aYe3Y/178+zj0WSJEmSJEmSJEmSJEmSJEmSJEmSJEmSJEm6yP/mNsIyktYWNgAAAABJRU5ErkJggg==').' Споделете в Twitter','https://www.twitter.com/share?url='.Yii::$app->request->hostInfo,['class'=>'btn btn-share btn-twitter','data-type'=>'twitter','target'=>'_blank']);
				Yii::$app->controller->view->registerJs("
					$('a.btn-share').click(function(event){
						switch(event.target.getAttribute('data-type')){
							case 'facebook':
								open(event.target.href,'sharer','width=555,height=771,top=100,left='+(outerWidth/2 - 277));
								break;
							case 'twitter':
								open(event.target.href,'sharer','width=555,height=493,top=100,left='+(outerWidth/2 - 277));
								break;
						}
						event.preventDefault();
					});
				");
				break;
			case 'stack':
				$cellMarkup = '';
				$col_width_class = 12 / count($dataModel->stack);
				foreach($dataModel->stack AS $ind=>$cell){
					$cell = json_decode($cell);
					if($cell && isset($cell->id)){
						$section = self::findOne($cell->id);
						if($section){
							$cellMarkup .= Html::tag('div',$section->render(),['class'=>$dataModel->orientation == 'vertical' ? "col row-$ind" : "col-md-$col_width_class column-$ind"]);
							continue;
						}
					}
					$cellMarkup .= Html::tag('div',NULL,['class'=>'col']);
				}
				echo Html::tag('div',$cellMarkup,['class'=>"row $dataModel->container_tag_additional_classes ".($dataModel->orientation == 'vertical' ? 'flex-column' : NULL)]);
				break;
			case 'form':
				$count_subscriptions = Yii::$app->cache->getOrSet('count_subscriptions_'.Yii::$app->active_petition->id,function(){
					$response = Yii::$app->api_subscription->get_count(Yii::$app->active_petition->code);
					if($response->status == 'success')
						return $response->count;
					echo Html::tag('div',"Error: $response->error",['class'=>'alert alert-danger']);
					return 0;
				},YII_ENV_DEV ? 0.1 : NULL);
				$thresholds = [500, 1000, 2000, 5000, 10000, 15000, 20000, 25000, 30000, 35000, 40000, 80000, 500000];
				for($ind=0;$ind < count($thresholds) && $count_subscriptions > $thresholds[$ind];$ind++);
				echo str_replace(['{count_signed}','{count_remaining}'],[$count_subscriptions,$ind >= count($thresholds) ? '&ndash;' : $thresholds[$ind] - $count_subscriptions],$dataModel->head);
				$ind = min($ind,count($thresholds)-1);
				$percent = min(100,floor($count_subscriptions * 100 / $thresholds[$ind]));
				echo Html::tag('div',Html::tag('div',"$percent %",['class'=>'progress-bar','style'=>"width: $percent%"]),['class'=>'progress']);
				$model = new FormModel;
				if(!empty($dataModel->show_country_field))
					$model->country_is_required = true;
				$form = ActiveForm::begin([
					'action'=>['ajax_sign'],
					'id'=>"form-$this->id",
					'layout'=>'horizontal',
					'fieldConfig'=>[
						'inputOptions'=>['maxlength'=>true],
						'horizontalCssClasses'=>[
							'label' => 'col-sm-5 col-form-label',
							'wrapper'=>'col-sm-7',
						],
					]
				]);
				echo Html::beginTag('fieldset');
				echo $form->field($model,'name')->textInput(['autocomplete'=>'off','spellcheck'=>'false']);
				echo $form->field($model,'email')->textInput(['autocomplete'=>'off','spellcheck'=>'false']);;
				if(!empty($dataModel->show_country_field)){
					echo $form->field($model,'country')->widget(CountriesSelectizeTextInput::class,[
						'countryModelNamespace'=>'app\models\Country',
						'customRender' => [
							'item'  => '<div> <span class="flag flag-icon-background flag-icon-{item.alpha}">&nbsp;</span>&nbsp;<span class="name">{escape(item.name_bg)}</span></div>',
							'option'  => '<div> <span class="flag flag-icon-background flag-icon-{item.alpha}">&nbsp;</span>&nbsp;<span class="name">{escape(item.name_bg)}</span></div>',
						],
						'clientOptions' => [
							'valueField' => 'alpha',
							'labelField' => 'name_en',
							'searchField' => ['name_en', 'name_bg'],
							'closeAfterSelect' => true,
							'maxItems' => 1,
							'items'=>['bg'],
						],
					]);
					Yii::$app->controller->view->registerCss(".selectize-control .flag{display: inline-block;width:20px;height: 15px;}");
				}
				if(!empty($dataModel->show_phone_field)){
					echo $form->field($model,'phone')->widget(PhoneInput::class,[
						'jsOptions'=>[
							'preferredCountries'=>['bg']
						]
					]);
					Yii::$app->controller->view->registerCss(".field-formmodel-phone .invalid-feedback{display:block;}");
				}
				echo $form->field($model,'agreed_to_subscribe',['horizontalCssClasses'=>['label'=>'col-sm-12 col-form-label','wrapper'=>'col-sm-12']])->radioList(['1'=>'Да','0'=>'Не'],['itemOptions'=>['tag'=>'span']]);
				echo Html::tag('div',NULL,['class'=>'success-message','style'=>'display:none']);
				echo Html::endTag('fieldset');
				echo $dataModel->terms;
				echo Html::submitButton($dataModel->button_text,['class'=>$dataModel->button_class]);
				echo Html::tag('div',Html::img('/images/exclamation-triangle.svg',['height'=>18]).Html::tag('span',NULL,['class'=>'error-message']),['class'=>'alert alert-danger error-box','style'=>'display:none']);
				ActiveForm::end();
				$url_redirect = Url::to(['/blagodarim']);
				Yii::$app->controller->view->registerJs("
					$('#form-$this->id')
						.submit(function(event){
							event.preventDefault();
						})
						.bind('beforeSubmit',function(event){
							let self = this;
							$(self).find('button').prop('disabled',true);
							$(self).find('.error-box').hide();
							$.post(event.target.action,
								$(this).serializeArray(),
								'json'
							).done(function(response){
								switch(response.status){
									case 'error':
										$(self).find('.error-message').html(response.error).parent().fadeIn();
										break;
									case 'success':
										var fieldset = $(self).find('fieldset');
										var agreed_to_subscribe = +$('#form-$this->id input[name=\"FormModel[agreed_to_subscribe]\"]:checked').val();
										fieldset.css('min-height',fieldset.height()+'px');
										fieldset.find('.form-group').remove();
										$(self).find('button').remove();
										if(agreed_to_subscribe && response.pending)
											fieldset
												.find('.success-message')
													.append('<h2>Благодарим за Вашия глас!</h2><p>На Вашата поща изпратихме заявка за <strong class=\"text-danger\">потвърждение на имейл адрес</strong>. Кликнете върху връзката, съдържаща се в писмото. Ако не виждате имейла за потвърждение, проверете в папката <big><b>SPAM</b></big>. Може да отнеме до една минута, преди да получите имейла. <p>Само заедно можем да помогнем на животните<p class=\"text-center\"><img src=\"/images/heart.svg\" height=50/>')
													.fadeIn();
										else
											location.assign(\"$url_redirect\");
										break;
								}
							})
							.fail(function(xhr){
								$(self).find('.error-message').html(xhr.responseText).parent().fadeIn();
							})
							.always(function(){
								$(self).find('button').prop('disabled',false);
							});
						});
				");
				break;
		}
		if(!$this->parent_id)
			echo Html::endTag('div');
		echo Html::endTag('section');
		return ob_get_clean();
	}
	public function duplicate($petition_id,$parent_id=NULL){
		$id_map = [];
		$newSection = new Section;
		$newSection->petition_id = $petition_id;
		$newSection->parent_id = $parent_id;
		$newSection->mask_pages = $this->mask_pages;
		$newSection->order_ind = $this->order_ind;
		$newSection->title = $this->title;
		$newSection->type = $this->type;
		$newSection->tag_id = $this->tag_id;
		$newSection->tag_additional_classes = $this->tag_additional_classes;
		$newSection->background_image = $this->background_image;
		switch($this->type){
			case 'text':
			case 'video':
			case 'image':
			case 'share':
			case 'form':
				$newSection->data = $this->data;
				break;
			case 'stack':
				$dataModel = new StackDataModel;
				if($this->data){
					$dataModel->unserialize($this->data);
					$newDataModel = new StackDataModel;
					$newDataModel->stack = [];
					$newDataModel->container_tag_additional_classes = $dataModel->container_tag_additional_classes;
					$newDataModel->orientation = $dataModel->orientation;
					$newDataModel->count_cells = $dataModel->count_cells;
					if(!$newSection->save())
						throw new \yii\web\ServerErrorHttpException("Couldn't save new section: ".print_r($newSection->getErrors(),true));
					if(is_array($dataModel->stack)){
						foreach($dataModel->stack AS $cell){
							$cell = json_decode($cell);
							if($cell && $cell->id){
								$cellSection = Section::find()->where(['id'=>$cell->id])->one();
								if($cellSection){
									$id_map = $id_map + $cellSection->duplicate($petition_id,$newSection->id);
									$newDataModel->stack[] = json_encode(['id'=>$id_map[$cell->id]]);
								}else
									$newDataModel->stack[] = NULL;
							}else
								$newDataModel->stack[] = NULL;
						}
					}
					$newSection->data = $newDataModel->serialize($newSection->id);
				}
				break;
		}
		if(!$newSection->save())
			throw new \yii\web\ServerErrorHttpException("Couldn't save new section: ".print_r($newSection->getErrors(),true));
		$id_map[$this->id] = $newSection->id;
		return $id_map;
	}
	public function rules(){
		return [
			[['mask_page_main','mask_page_thanks'],'boolean'],
			[['title','type'],'required'],
			['title','filter','filter'=>'strip_tags'],
			[['title','tag_id','tag_additional_classes'],'trim'],
			['tag_id','match','pattern'=>'/^[a-zA-Z0-9_-]+$/','message'=>'Only alphanumeric characters, underscore and hyphen are allowed'],
			['tag_id','string','max'=>16],
			['tag_additional_classes','string','max'=>64],
			[['background_image','background_image_mobile'],'string','max'=>32],
			['data','string','max'=>0xFFFF]
		];
	}
	public function beforeSave($insert){
		return parent::beforeSave($insert);
	}
	public function afterDelete(){
		if($this->type == 'stack'){
			$dataModel = new StackDataModel;
			$dataModel->unserialize($this->data);
			foreach($dataModel->stack AS $cell){
				$cell = json_decode($cell);
				if($cell && isset($cell->id)){
					$section = self::findOne($cell->id);
					if($section)
						$section->delete();
				}
			}
		}
		return parent::afterDelete();
	}
}
abstract class DataModel extends \yii\base\Model{
	abstract public function serialize($section_id=NULL);
	abstract public function unserialize($data);
	public function shouldSaveBeforeEdit(){
		return false;
	}
	public function isDataComplete(){
		return true;
	}
}
class TextDataModel extends DataModel{
	public $content;
	public function rules(){
		return [
			['content','required'],
			['content','trim']
		];
	}
	public function serialize($section_id=NULL){
		return json_encode(['content'=>$this->content]);
	}
	public function unserialize($data){
		$this->content = json_decode($data)->content;
	}
}
class VideoDataModel extends DataModel{
	public $embed_code;
	public function rules(){
		return [
			['embed_code','required'],
			['embed_code','trim']
		];
	}
	public function serialize($section_id=NULL){
		return json_encode(['embed_code'=>$this->embed_code]);
	}
	public function unserialize($data){
		$this->embed_code = json_decode($data)->embed_code;
	}
}
class StackDataModel extends DataModel{
	public $container_tag_additional_classes;
	public $orientation;
	public $count_cells = 2;
	public $stack;
	private $has_new_subsections = false;
	public function rules(){
		return [
			['container_tag_additional_classes','string','max'=>64],
			[['orientation','count_cells'],'required'],
			['stack','safe'],
			['count_cells','integer'],
		];
	}
	public function serialize($section_id=NULL){
		$subsectionIDs = [];
		if($this->stack){
			foreach($this->stack AS $ind=>$cell){
				$cell = json_decode($cell);
				if($cell && isset($cell->type)){	//Need to create subsection
					$subsection = new Section;
					$subsection->parent_id = $section_id;
					$subsection->title = 'New subsection';
					$subsection->type = $cell->type;
					if(!$subsection->save())
						throw new \yii\web\ServerErrorHttpException(print_r($subsection->getErrors(),true));
					$this->stack[$ind] = json_encode(['id'=>$subsection->id]);
					$subsectionIDs[] = $subsection->id;
					$this->has_new_subsections = true;
				}
				elseif($cell && $cell->id)
					$subsectionIDs[] = $cell->id;
			}
		}
		if($section_id)
			foreach(Section::find()->where(['parent_id'=>$section_id])->andWhere(['not in','id',$subsectionIDs])->all() AS $abandonedSubsection)
				$abandonedSubsection->delete();
		return json_encode([
			'container_tag_additional_classes'=>$this->container_tag_additional_classes,
			'orientation'=>$this->orientation,
			'count_cells'=>$this->count_cells,
			'stack'=>$this->stack
		]);
	}
	public function unserialize($data){
		$data = json_decode($data);
		$this->container_tag_additional_classes = $data->container_tag_additional_classes;
		$this->orientation = $data->orientation;
		$this->count_cells = $data->count_cells;
		$this->stack = isset($data->stack) && is_array($data->stack) ? $data->stack : array_fill(0,$this->count_cells,NULL);
	}
	public function shouldSaveBeforeEdit(){
		return true;
	}
	public function isDataComplete(){
		if(!$this->stack)
			return false;
		if($this->has_new_subsections)
			return false;
		return true;
	}
}
class ImageDataModel extends DataModel{
	public $image_name;
	public $is_poppable;
	public $caption;
	public function rules(){
		return [
			[['image_name'],'required'],
			['is_poppable','boolean'],
			['image_name','string','max'=>32],
			['caption','string','max'=>64]
		];
	}
	public function serialize($section_id=NULL){
		return json_encode([
			'image_name'=>$this->image_name,
			'is_poppable'=>(int)$this->is_poppable,
			'caption'=>$this->caption
		]);
	}
	public function unserialize($data){
		$data = json_decode($data);
		$this->image_name = $data->image_name;
		$this->is_poppable = $data->is_poppable;
		$this->caption = $data->caption;
	}
}
class ShareDataModel extends DataModel{
	public $og_url;
	public $og_type = 'website';
	public $og_title;
	public $og_description;
	public $og_image;
	public function rules(){
		return [
			[['og_url','og_type','og_title','og_description','og_image'],'string','max'=>128]
		];
	}
	public function serialize($section_id=NULL){
		return json_encode([
			'og_url'=>$this->og_url,
			'og_type'=>$this->og_type,
			'og_title'=>$this->og_title,
			'og_description'=>$this->og_description,
			'og_image'=>$this->og_image
		]);
	}
	public function unserialize($data){
		$data = json_decode($data);
		$this->og_url = $data->og_url;
		$this->og_type = $data->og_type;
		$this->og_title = $data->og_title;
		$this->og_description = $data->og_description;
		$this->og_image = $data->og_image;
	}
}
class FormDataModel extends DataModel{
	public $head;
	public $terms;
	public $button_class;
	public $button_text;
	public $show_phone_field = true;
	public $show_country_field = false;
	public function rules(){
		return [
			[['head','terms','button_text'],'required'],
			['button_class','string'],
			[['show_phone_field','show_country_field'],'boolean'],
		];
	}
	public function serialize($section_id=NULL){
		return json_encode([
			'head'=>$this->head,
			'terms'=>$this->terms,
			'button_class'=>$this->button_class,
			'button_text'=>$this->button_text,
			'show_phone_field'=>(int)$this->show_phone_field,
			'show_country_field'=>(int)$this->show_country_field,
		]);
	}
	public function unserialize($data){
		$data = json_decode($data);
		$this->head = $data->head;
		$this->terms = $data->terms;
		$this->button_class = $data->button_class;
		$this->button_text = $data->button_text;
		$this->show_phone_field = isset($data->show_phone_field) ? $data->show_phone_field : false;
		$this->show_country_field = isset($data->show_country_field) ? $data->show_country_field : false;
	}
}

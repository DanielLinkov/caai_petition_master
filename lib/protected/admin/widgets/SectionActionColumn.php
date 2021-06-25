<?php
namespace app\admin\widgets;

use Yii;
use yii\helpers\Html;

class SectionActionColumn extends \yii\grid\ActionColumn{
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
						$color = 'btn-default';
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
						$color = 'btn-warning';
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
						$color = 'btn-danger';
                        break;
                    default:
                        $title = ucfirst($name);
						$color = 'btn-default';
                }
                $options = array_merge([
					'class'	=>	"btn btn-block btn-xs $color",
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}

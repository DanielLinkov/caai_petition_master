<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
	public function init(){
		if(Yii::$app->active_petition->config)
			$this->css[] = 'css/themes/'.Yii::$app->active_petition->config->template_name.'.css';
		else
			$this->css[] = 'css/bootstrap.min.css';
		$this->css[] = Yii::getAlias('@petitions/'.Yii::$app->active_petition->id.'/css/style.css');
		parent::init();
	}
}

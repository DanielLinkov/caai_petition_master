<?php
/**
 * DependAssetBundle.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace acerix\yii\minify\tests\unit\data;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class DependAssetBundle
 * @package acerix\yii\minify\tests\unit\data
 */
class DependAssetBundle extends AssetBundle
{

    public $js = [
        'depend.js',
    ];

    public $css = [
        'depend.css',
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

    public $depends = [
        'acerix\yii\minify\tests\unit\data\JQueryAssetBundle',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/source';

        parent::init();
    }
}

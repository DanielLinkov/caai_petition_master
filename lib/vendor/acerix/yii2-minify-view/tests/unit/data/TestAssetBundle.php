<?php
/**
 * TestAssetBundle.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace acerix\yii\minify\tests\unit\data;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class TestAssetBundle
 * @package acerix\yii\minify\tests\unit\data
 */
class TestAssetBundle extends AssetBundle
{

    public $js = [
        'test.js',
    ];

    public $css = [
        'test.css',
    ];

    public $jsOptions = [
        'position' => View::POS_READY,
    ];

    public $cssOptions = [
        'media' => 'all',
    ];

    public $depends = [
        'acerix\yii\minify\tests\unit\data\DependAssetBundle',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/source';

        return parent::init();
    }
}

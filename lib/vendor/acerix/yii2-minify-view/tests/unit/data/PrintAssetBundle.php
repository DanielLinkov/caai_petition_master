<?php
/**
 * PrintAssetBundle.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace acerix\yii\minify\tests\unit\data;

use yii\web\AssetBundle;

/**
 * Class TestAssetBundle
 * @package acerix\yii\minify\tests\unit\data
 */
class PrintAssetBundle extends AssetBundle
{

    public $css = [
        'print.css',
    ];

    public $cssOptions = [
        'media' => 'print',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/source';

        parent::init();
    }
}

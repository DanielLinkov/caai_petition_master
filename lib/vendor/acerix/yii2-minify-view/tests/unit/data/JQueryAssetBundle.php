<?php
/**
 * JQueryAssetBundle.php
 * @author Revin Roman
 * @link https://rmrevin.ru
 */

namespace acerix\yii\minify\tests\unit\data;

use yii\web\AssetBundle;

/**
 * Class JQueryAssetBundle
 * @package acerix\yii\minify\tests\unit\data
 */
class JQueryAssetBundle extends AssetBundle
{

    public $js = [
        '//code.jquery.com/jquery-3.2.1.slim.min.js',
    ];

    public $jsOptions = [
        'position' => \acerix\yii\minify\View::POS_HEAD,
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/source';

        return parent::init();
    }
}

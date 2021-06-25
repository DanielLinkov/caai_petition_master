<?php
namespace app\admin\widgets;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use conquer\helpers\Json;
use conquer\codemirror\CodemirrorAsset;

/**
 * @author Andrey Borodulin
 */
class CodemirrorWidget extends \conquer\codemirror\CodemirrorWidget
{

    public function registerAssets()
    {
        $view = $this->getView();
        $id = $this->options['id'];
        $settings = $this->settings;
        $assets = $this->assets;
        if ($this->preset) {
            $preset = $this->getPreset($this->preset);
            if (isset($preset['settings'])) {
                $settings = ArrayHelper::merge($preset['settings'], $settings);
            }
            if (isset($preset['assets'])) {
                $assets = ArrayHelper::merge($preset['assets'], $assets);
            }
        }
        $settings = Json::encode($settings);
        $js = "cm_".str_replace('-','__',$id)." = CodeMirror.fromTextArea(document.getElementById('$id'), $settings);";
        $view->registerJs($js);
        CodemirrorAsset::register($this->view, $assets);
    }
}

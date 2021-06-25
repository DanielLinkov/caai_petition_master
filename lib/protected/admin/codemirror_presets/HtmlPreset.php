<?php

use conquer\codemirror\CodemirrorAsset;
use yii\web\JsExpression;

return [
	'assets'=>[
		CodemirrorAsset::ADDON_HINT_SHOW_HINT,
		CodemirrorAsset::ADDON_HINT_XML_HINT,
		CodemirrorAsset::ADDON_HINT_HTML_HINT,
		CodemirrorAsset::MODE_XML,
		CodemirrorAsset::MODE_HTMLMIXED,
		CodemirrorAsset::ADDON_COMMENT,
		CodemirrorAsset::ADDON_DISPLAY_FULLSCREEN,
		CodemirrorAsset::KEYMAP_VIM,
		CodemirrorAsset::THEME_NIGHT,
	],
	'settings'=>[
		'mode'=>'text/html',
		'theme'=>'night',
        'lineNumbers'=> true,
        'indentUnit' => 4,
        'indentWithTabs' => true,
        'extraKeys' => [
			"Ctrl-Space" => "autocomplete",
            "F11" => new JsExpression("function(cm){cm.setOption('fullScreen', !cm.getOption('fullScreen'));}"),
        ],
	]
];

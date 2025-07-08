<?php

use conquer\codemirror\CodemirrorAsset;
use yii\web\JsExpression;

return [
	'assets'=>[
		CodemirrorAsset::ADDON_HINT_SHOW_HINT,
		CodemirrorAsset::ADDON_HINT_CSS_HINT,
		CodemirrorAsset::MODE_CSS,
		CodemirrorAsset::ADDON_COMMENT,
		CodemirrorAsset::ADDON_DISPLAY_FULLSCREEN,
        CodemirrorAsset::ADDON_EDIT_MATCHBRACKETS,
        CodemirrorAsset::ADDON_EDIT_CLOSEBRACKETS,
        CodemirrorAsset::ADDON_CONTINUECOMMENT,
		CodemirrorAsset::KEYMAP_VIM,
		CodemirrorAsset::THEME_NIGHT,
	],
	'settings'=>[
		'theme'=>'night',
        'lineNumbers'=> true,
        'matchBrackets'=>true,
		'autoCloseBrackets'=>true,
        'continueComments' => "Enter",
        'indentUnit' => 4,
        'indentWithTabs' => true,
        'extraKeys' => [
			"Ctrl-Space" => "autocomplete",
            "F11" => new JsExpression("function(cm){cm.setOption('fullScreen', !cm.getOption('fullScreen'));}"),
        ],
	]
];

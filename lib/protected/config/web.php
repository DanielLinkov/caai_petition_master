<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
	'language'=>'bg',
	'name'	=>	'Petition Master',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','active_petition'],
    'aliases' => [
		'@vendor'=>	dirname(dirname(__DIR__)) . '/vendor',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
		'@petitions'	=>	'/petitions',
    ],
    'components' => [
		'view'=>[
			'class'=>'acerix\yii\minify\View',
			'enableMinify'=>false && !YII_ENV_DEV,
			'concatCss'=>false,
			'minifyCss'=>true,
			'expandImports'=>false,
			'concatJs'=>true,
			'minifyJs'=>true,
			'minifyOutput'=>true,
			'minifyPath'=>'@petitionroot/minify',
			'jsPosition'=>[yii\web\View::POS_END],
			'compressOptions'=> [ 'jsMinifier'=>[ 'JSMin\JSMin','minify' ] ]
		],
		'assetManager'=>[
			'bundles'=>[
				'yii\web\JqueryAsset'=>[
					'js'=>[ 'jquery.min.js' ]
                ],
                'yii\bootstrap\BootstrapAsset'=>[
                    'js'=>[],
                    'css'=>[],
                ],
                'dosamigos\selectize\SelectizeAsset'=>[
                    'js'=>[
                        'js/standalone/selectize.min.js'
                    ]
                ]
			]
		],
		'session' => [
			'class' => 'yii\web\Session',
			'name' => '',
		],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'RtnOhiQepPmOVTxXI8r0YZ8emYtfPfD-',
			'enableCsrfValidation' => false, // Disable CSRF validation for API requests
			'enableCsrfCookie' => false, // Disable CSRF cookie for API requests
        ],
        'page_cache' => [
            'class' => 'yii\caching\FileCache',
			'cachePath'=> '@runtime/page_cache'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'active_petition'	=>	[
			'class'	=>	'app\components\Petition'
		],
        'api_subscription'=>[
			'class'=>'app\components\ApiSubscriptionAdapter',
			'api_key' => 'khr5L3fGe'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'blagodarim'=>'site/thanks'
            ],
        ],
    ],
    'params' => $params,
];
if (false && YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1','46.40.126.189'],
    ];
}
return $config;

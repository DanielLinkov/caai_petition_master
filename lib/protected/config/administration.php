<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
	'name'	=>	'Petition Master Admin',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','access_control','active_petition'],
	'controllerNamespace'=>'app\\admin\\controllers',
	'viewPath'	=>	dirname(__DIR__) . '/admin/views',
    'aliases' => [
		'@vendor'=>	dirname(dirname(__DIR__)) . '/vendor',
		'@therendstudio'	=>	'@vendor/therendstudio',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
		'@petitionsroot'=>	dirname(dirname(dirname(__DIR__))) . '/petitions',
		'@petitions'	=>	'/petitions',
    ],
    'components' => [
        'access_control'=>[
            'class'=>'app\admin\components\AccessControl',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'RtnOhiQepPmOVTxXI8r0YZ8emYtfPfD-',
        ],
        'page_cache' => [
            'class' => 'yii\caching\FileCache',
			'cachePath'=> '@runtime/page_cache'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\admin\models\User',
			'loginUrl'	=>	['/signin']
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'active_petition'	=>	[
			'class'	=>	'app\admin\components\Petition'
		],
		'msg'=>[
			'class'=>'therendstudio\modules\messages\components\Msg',
		],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
					'logFile'	=>	'@runtime/admin/logs/app.log',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1','46.40.126.189'],
    ];
}

return $config;

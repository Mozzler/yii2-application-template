<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@webapp' => '@app/project/app',
        '@console' => '@app/project/console'
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'c2cy7924c4792c949c2r9y7c',
            'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/myapp'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
		        ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/user'],
		    ],
        ],
        'view' => [
	        'class' => 'mozzler\base\yii\web\View',
            'renderers' => [
		        'twig' => [
	                'class' => 'yii\twig\ViewRenderer',
	                'cachePath' => '@runtime/Twig/cache',
	                // Array of twig options:
	                'options' => [
	                    'auto_reload' => true,
	                ],
	                'globals' => ['html' => '\yii\helpers\Html'],
	                'uses' => ['yii\bootstrap'],
	            ]
	        ],
	        'defaultExtension' => 'twig'
        ],
        'mozzler' => [
		    'class' => 'mozzler\base\Mozzler'
	    ],
    ],
    'modules' => [
	    'web' => [
		    'class' => 'mozzler\web\Module'
	    ],
	    'auth' => [
		    'class' => 'mozzler\webauth\Module'
	    ],
	    'v1' => [
		    'class' => 'app\apiv1\Module'
	    ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

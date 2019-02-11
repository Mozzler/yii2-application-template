<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Project Name',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','v1','oauth2','rbac'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset'
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
            'class' => 'mozzler\base\components\cache',
            'cacheCollection' => 'app.cache'
        ],
        'session' => [
            'class' => 'yii\mongodb\session',
            'sessionCollection' => 'app.session'
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
        'mongodb' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
//		        ['class' => 'mozzler\base\yii\rest\MongoUrlRule', 'controller' => 'v1/<controllername>'],
		    ],
        ],
        'view' => [
	        'class' => 'mozzler\base\yii\web\View',
            'renderers' => [
		        'twig' => [
	                'class' => 'yii\twig\ViewRenderer',
	                'cachePath' => '@runtime/Twig/cache',
	                'options' => [
	                    'auto_reload' => true,
	                    'autoescape' => false
	                ],
	                'globals' => [
	                	'html' => [
	                		'class' => '\yii\helpers\Html'
	                	],
	                	'arrayhelper' => [
		                	'class' => '\yii\helpers\ArrayHelper'
	                	],
	                	't' => [
	                		'class' => '\mozzler\base\components\Tools'
	                	]
	                ],
	            ]
	        ],
	        'defaultExtension' => 'twig'
        ],
        't' => [
            'class' => '\mozzler\base\components\Tools'
        ],
        'mozzler' => [
		    'class' => 'mozzler\base\components\Mozzler'
	    ],
	    'rbac' => [
		    'class' => 'mozzler\rbac\components\RbacManager',
            'traceEnabled' => YII_DEBUG ? true : false,
            'ignoredCollections' => [
                'app.session'
            ]
	    ],
	    'formatter' => [
		    'datetimeFormat' => 'Y-m-d H:i:s',
		    'dateFormat' => 'Y-m-d'
	    ]
    ],
    'modules' => [
	    'gridview' =>  [
        	'class' => '\kartik\grid\Module'
        ],
	    'mozzlerBase' => [
		    'class' => '\mozzler\base\Module'
	    ],
	    'v1' => [
		    'class' => 'app\apiv1\Module'
	    ],
	    'oauth2' => [
	        'class' => '\mozzler\auth\OauthModule'
	    ],
	    'auth' => [
	        'class' => '\mozzler\auth\Module'
	    ]
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
    
    $config['components']['view']['renderers']['twig']['extensions'] = ['Twig_Extension_Debug'];
}

return $config;

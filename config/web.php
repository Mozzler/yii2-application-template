<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

use kartik\datecontrol\Module as DateControlModule;
$commonConfig = require __DIR__ . '/common.php';

$localConfig = [];
if (is_readable(__DIR__ . '/local.php')) {
    include __DIR__ . '/local.php';
}

$twigConfig = [
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
];

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
            'class' => 'mozzler\auth\web\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'authTimeout' => 86400, // last a day
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
		        'twig' => $twigConfig
	        ],
	        'defaultExtension' => 'twig'
        ],
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
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
    
            // format settings for saving each date attribute (PHP format example)
            // always store as unix epoch
            'saveSettings' => [
                DateControlModule::FORMAT_DATE => 'php:U', // saves as unix timestamp
                DateControlModule::FORMAT_TIME => 'php:U',
                DateControlModule::FORMAT_DATETIME => 'php:U',
            ],
    
            // set your display timezone
            'displayTimezone' => 'Australia/Adelaide',
    
            // set your timezone for date saved to db
            'saveTimezone' => 'UTC',
    
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
    
            // use ajax conversion for processing dates from display format to save format.
            'ajaxConversion' => true,
    
            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                DateControlModule::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true,'todayHighlight'=>true]], // example
                DateControlModule::FORMAT_DATETIME => [], // setup if needed
                DateControlModule::FORMAT_TIME => [], // setup if needed
            ]
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

return \yii\helpers\ArrayHelper::merge($commonConfig, $config, $localConfig);

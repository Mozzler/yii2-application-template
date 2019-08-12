<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$commonConfig = require __DIR__ . '/common.php';

$localConfig = [];
if (is_readable(__DIR__ . '/local.php')) {
    include __DIR__ . '/local.php';
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','rbac'],
    'controllerNamespace' => 'app\console',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'deployManager' => [
            'class' => 'mozzler\base\components\DeployManager',
            'versionParam' => 'apiVersionNumber',
            'init' => [
                'indexes' => [
                    'command' => 'deploy/sync',
                    'params' => []
                ],
                'adminUser' => [
                    'command' => 'auth/init-credentials',
                    'params' => []
                ],
                'config' => [
                    'command' => 'config/init',
                    'params' => []
                ]
            ],
            'redeploy' => [
                'indexes' => [
                    'command' => 'deploy/sync',
                    'params' => []
                ],
                'config' => [
                    'command' => 'config/init',
                    'params' => []
                ]
            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mongodb' => $db,
        'rbac' => [
		    'class' => 'mozzler\rbac\components\RbacManager',
		    'traceEnabled' => YII_DEBUG ? true : false
	    ],
        'mozzler' => [
		    'class' => 'mozzler\base\components\Mozzler'
	    ]
    ],
    'params' => $params,
    'controllerMap' => [
        'deploy' => [
            'class' => 'mozzler\base\commands\DeployController'
        ],
        'auth' => [
            'class' => 'mozzler\auth\commands\AuthController'
        ],
        'stubs' => [
            'class' => 'bazilio\stubsgenerator\StubsController',
        ],
    ],
    'modules' => [
        'auth' => [
            'class' => 'mozzler\auth\Module',
            'initialCredentials' => [
                'status' => 'active',
                'username' => 'user@example.com',
                'password' => 'the_password',
                'firstName' => 'First',
                'lastName' => 'Admin',
                'roles' => ['registered', 'admin']
            ]
        ]
    ],

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return \yii\helpers\ArrayHelper::merge($commonConfig, $config, $localConfig);

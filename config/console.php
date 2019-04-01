<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

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
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => YII_DEBUG ? ['error', 'warning', 'info', 'trace'] : ['error', 'warning']
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
        ]
    ],
    'modules' => [
        'auth' => [
            'class' => 'mozzler\auth\Module'
        ]
    ]
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

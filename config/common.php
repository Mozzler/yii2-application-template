<?php

// This is for the base components (and other config).
// Which are shared between the web and console versions.

$twigConfig = [
    'class' => 'yii\twig\ViewRenderer',
    'cachePath' => '@runtime/Twig/cache',
    'options' => [
        'auto_reload' => true,
        'autoescape' => false
    ],
    'globals' => [
        'html' => [
            'class' => 'yii\helpers\Html'
        ],
        'arrayhelper' => [
            'class' => 'yii\helpers\ArrayHelper'
        ],
        't' => [
            'class' => 'mozzler\base\components\Tools'
        ]
    ],
];

$commonConfig = [
    'name' => 'tzm-questionnaire',
    'components' => [
        'cache' => [
            'class' => 'mozzler\base\components\Cache',
            'cacheCollection' => 'app.cache'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'enableSwiftMailerLogging' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'plugins' => [
                    ['class' => 'Openbuildings\Swiftmailer\CssInlinerPlugin']
                ],
                "username" => '',
                "password" => '',
                "host" => '',
                "port" => 465,
                "encryption" => 'ssl'
            ],
            'viewPath' => '@app/views/emails',
            'view' => [
                'class' => 'mozzler\base\yii\web\View',
                'renderers' => [
                    'twig' => $twigConfig
                ]
            ],
            'htmlLayout' => '@app/views/layouts/emails/html.twig',
            'textLayout' => '@app/views/layouts/emails/text.twig'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    // Log the emails
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['yii\swiftmailer\Logger::add'],
                    'logFile' => '@app/runtime/logs/email.log',
                ],
            ],
        ],
        't' => [
            'class' => 'mozzler\base\components\Tools'
        ],
        'mozzler' => [
            'class' => 'mozzler\base\components\Mozzler'
        ],
        'rbac' => [
            'class' => 'mozzler\rbac\components\RbacManager',
            'traceEnabled' => false,
            'ignoredCollections' => [
                'app.session',
                'app.config'
            ]
        ],
        'formatter' => [
            'datetimeFormat' => 'd M Y H:i',
            'dateFormat' => 'd M Y',
            'timeZone' => 'Australia/Adelaide'
        ],
        'taskManager' => [
            'class' => 'mozzler\base\components\TaskManager',
        ],
        'cronManager' => [
            'class' => 'mozzler\base\components\CronManager',
//            'entries' => [
//                'exampleCronEntry' => [
//                    'class' => 'app\cron\exampleCronEntry', // The CronEntry who's defaults this overrides
//                    'active' => true,
//                    'scriptClass' => "app\scripts\ExampleCronEntryScript",
//                    'config' => [],
//                    'minutes' => '*',
//                    'hours' => '*',
//                    'months' => '*',
//                    'dayMonth' => '*',
//                    'dayWeek' => '*',
//                    'timezone' => 'Australia/Adelaide',
//                    'timeoutSeconds' => 120
//                ]
//            ]
        ],
    ],
    'container' => [
        'definitions' => [
//            'mozzler\base\models\Config' => [
//                'class' => 'app\models\Config',
//            ],
//            'mozzler\auth\models\oauth\OAuthClient' => [
//                'class' => 'app\models\OAuthClient',
//            ],
        ],
    ],
];

return $commonConfig;

<?php
$webConfig = require __DIR__ . '/web.php';
$db = require __DIR__ . '/test_db.php';
$params = require __DIR__ . '/test_params.php';

/**
 * Application configuration shared by all test types
 */
$config = [
    'id' => 'basic-tests',
    'bootstrap' => ['log', 'v1', 'oauth2', 'rbac', 'debug'],
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => $webConfig['components'],
    'modules' => $webConfig['modules'],
    'params' => $params,
];

$config['components']['mailer']['useFileTransport'] = true;
$config['components']['assetManager']['basePath'] = __DIR__ . '/../web/assets';
$config['components']['urlManager']['showScriptName'] = true;
$config['components']['request']['cookieValidationKey'] = 'test';
$config['components']['request']['enableCsrfValidation'] = 'false';
$config['components']['mongodb'] = $db;
$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    'allowedIPs' => ['127.0.0.1', '::1', '*'],
];

return $config;
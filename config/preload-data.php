<?php

use mozzler\auth\models\oauth\OAuthClient;

return [
    OAuthClient::class => [
        [
            'name' => isset(\Yii::$app->name) ? \Yii::$app->name : "app",
            'client_id' => isset(\Yii::$app->name) ? str_replace(' ', '_', strtolower(\Yii::$app->name)) . '-id' : "app" . '-id',
            'client_secret' => isset(\Yii::$app->name) ? str_replace(' ', '_', strtolower(\Yii::$app->name)) . '-secret' : "app" . '-secret',
        ],
    ]

];
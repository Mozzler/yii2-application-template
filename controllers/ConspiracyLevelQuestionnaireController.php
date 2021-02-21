<?php

namespace app\controllers;

use mozzler\base\controllers\ModelController as BaseController;
use yii\helpers\ArrayHelper;


class ConspiracyLevelQuestionnaireController extends BaseController
{
    public $modelClass = 'app\models\ConspiracyLevelQuestionnaire';


    public static function rbac()
    {
        return ArrayHelper::merge(parent::rbac(), [
            // 'public' = Not Logged in
            // 'registered' = Logged in
            // 'admin' = Administrators
            'public' => [
                'create' => [
                    'grant' => true // Example of denying an action to logged in users
                ],
                'view' => [
                    'grant' => true
                ],
                'update' => [
                    'grant' => false
                ],
                'index' => [
                    'grant' => false
                ],
                'delete' => [
                    'grant' => false
                ]
            ],

            'registered' => [
                'create' => [
                    'grant' => true // Example of denying an action to logged in users
                ],
                'view' => [
                    'grant' => true
                ],
                'update' => [
                    'grant' => false
                ],
                'index' => [
                    'grant' => true
                ],
                'delete' => [
                    'grant' => false
                ]
            ],
            // Example which ensures admins can do everything (although this is the default)
            'admin' => [
                'create' => [
                    'grant' => true
                ],
                'view' => [
                    'grant' => true
                ],
                'update' => [
                    'grant' => true
                ],
                'index' => [
                    'grant' => true
                ],
                'delete' => [
                    'grant' => true
                ]
            ]
        ]);
    }
}

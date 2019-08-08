<?php

namespace app\models;

use mozzler\auth\models\User as BaseModel;
use yii\helpers\ArrayHelper;

/**
 * Class Device
 * @package app\models
 *
 * Devices are mobile app logins created without a user account
 * The mobile app creates a random mobileDeviceId and it's own password.
 */
class Device extends BaseModel
{

    public static $usernameField = 'mobileDeviceId';
    protected static $collectionName = 'app.device';

    public const MOBILE_DEVICE_TYPE_IOS = 'iOS';
    public const MOBILE_DEVICE_TYPE_ANDROID = 'Android';
    public const MOBILE_DEVICE_TYPE_UNKNOWN = 'Unknown';

    protected function modelConfig()
    {
        return ArrayHelper::merge(parent::modelConfig(), [
            'label' => 'Device',
            'labelPlural' => 'Devices'
        ]);
    }

    public function modelIndexes()
    {
        return ArrayHelper::merge(parent::modelIndexes(), [
            'uniqueMobileDeviceId' => [
                'columns' => ['mobileDeviceId' => 1],
                'options' => [
                    'unique' => 1
                ],
                'duplicateMessage' => ['Mobile device already exists']
            ]
        ]);
    }

    /**
     * Need to ensure the Drakes role has full access to view devices
     */
    public static function rbac()
    {
        return ArrayHelper::merge(parent::rbac(), [
            'admin' => [
                'delete' => [
                    'grant' => false
                ],
                'update' => [
                    'grant' => false
                ],
                'insert' => [
                    'grant' => false
                ]
            ],
            'public' => [
                'insert' => [
                    'permitApi' => 'app\policies\PermitDeviceCreatePolicy'
                ]
            ]
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Don't set the name as the user's firstName and lastName, the Device doesn't require that
        unset($behaviors['UserSetNameBehavior']);

        return $behaviors;
    }

    protected function modelFields()
    {
        $fields = ArrayHelper::merge(parent::modelFields(), [
            'name' => [
                'required' => true
            ],
            'passwordHash' => [
                'type' => 'Password',
                'label' => 'Password hash',
            ],
            'lastLoggedIn' => [
                'type' => 'Timestamp',
                'label' => 'Last logged in'
            ],
            'deviceMetadata' => [
                // For saving the device model info
                'type' => 'Text',
                'label' => 'Device metadata'
            ],
            'mobileDeviceId' => [
                'type' => 'Text',
                'label' => 'Device ID',
                'required' => true
            ],
        ]);

        unset($fields['email']);
        unset($fields['firstName']);
        unset($fields['lastName']);

        return $fields;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LIST] = ['mobileDeviceId', 'status', 'createdAt'];
        $scenarios[self::SCENARIO_CREATE] = ['id', 'name', 'mobileDeviceId', 'password'];
        $scenarios[self::SCENARIO_VIEW] = ['id', 'name', 'mobileDeviceId', 'deviceMetadata', 'createdAt', 'updatedAt'];
        $scenarios[self::SCENARIO_VIEW_API] = ['id', 'name', 'mobileDeviceId', 'createdAt', 'updatedAt'];
        $scenarios[self::SCENARIO_UPDATE] = [];
//	    $scenarios[self::SCENARIO_DEFAULT] = ['name', 'mobileDeviceId', 'createdAt'];
        $scenarios[self::SCENARIO_SEARCH] = ['mobileDeviceId', 'id'];
        unset($scenarios[self::SCENARIO_SIGNUP]);

        return $scenarios;
    }


}
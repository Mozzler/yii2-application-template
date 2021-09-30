<?php

namespace app\models;

use mozzler\base\models\behaviors\AssignUnnamedNameBehaviour;
use mozzler\base\models\behaviors\AuditLogBehaviour;
use mozzler\base\models\Model as Model;

use yii\helpers\ArrayHelper;

/**
 * Class BaseModel
 * @package app\models
 */
class BaseModel extends Model
{

    CONST FIELDS_AUTO_GENERATED = ['createdAt', 'updatedAt', 'createdUserId', 'updatedUserId'];

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = self::getCreateAttributes();
        $scenarios[self::SCENARIO_UPDATE] = $scenarios[self::SCENARIO_CREATE];
        $scenarios[self::SCENARIO_VIEW] = ArrayHelper::merge($scenarios[self::SCENARIO_CREATE], self::FIELDS_AUTO_GENERATED); // Set the scenario view to show everything on the Admin Control Panel by default
        $scenarios[self::SCENARIO_LIST_API] = $scenarios[self::SCENARIO_VIEW];
        $scenarios[self::SCENARIO_VIEW_API] = ArrayHelper::merge($scenarios[self::SCENARIO_CREATE], self::FIELDS_AUTO_GENERATED);
        return $scenarios;
    }


    public function behaviors()
    {
        // All models have an auditable (history) behaviour
        return ArrayHelper::merge(parent::behaviors(), [
            'auditable' =>
                [
                    'class' => AuditLogBehaviour::class,
                    'auditLogAttributes' => $this->scenarios()[self::SCENARIO_AUDITABLE],
                    'skipUpdateOnClean' => true,
                ],
            'assignName' => AssignUnnamedNameBehaviour::class
        ]);
    }

    /**
     * Returns a list of the attributes which the user can create/update
     * Which means it skips those that are auto-generated.
     *
     * @return array
     */
    public function getCreateAttributes()
    {
        // A list of the attributes, but without the auto-generated fields
        return array_values(array_diff(array_keys($this->modelFields()), ['id', '_id', 'updatedAt', 'createdAt', 'createdUserId', 'updatedUserId']));
    }


    /**
     * User Id By Default
     *
     * Example usage:
     *
     * 'userId' => [
     * 'label' => 'User',
     * 'type' => 'RelateOne',
     * 'relatedModel' => 'app\models\User',
     * 'relatedField' => '_id',
     * 'required' => true,
     * 'rules' => [
     * 'default' => ['value' => $this->getUserId()]
     * ]
     * ]
     */
    public function getUserId()
    {
        // -- Get the logged in user by default
        $user = \Yii::$app->user->getIdentity();

        if (!empty($user)) {
            return $user->getId();
        }
        return null;
    }

}
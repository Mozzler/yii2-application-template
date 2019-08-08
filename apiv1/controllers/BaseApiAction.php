<?php

namespace app\apiv1\actions;

use app\models\BaseModel;
use mozzler\base\actions\BaseModelAction as BaseAction;


/**
 * Class BaseApiAction
 * @package app\apiv1\actions
 *
 * You can extend the API controllers from here to share
 * any project specific common code for the API controllers
 * 
 * Although the User Controller is somewhat specific
 */
class BaseApiAction extends BaseAction
{
    /**
     * Return Error
     *
     * Allows returning a standardised error response.
     *
     * Example in an action:
     * > return $this->returnError('Invalid parent Story', 'parentId');
     *
     * The response would be a 422 Data Validation failed containing the JSON:
     * [{
     *   "field": "parentId",
     *   "message": "Invalid parent Story"
     * }]
     *
     * @param string $errorMessage Error Message
     * @param string $param attribute
     * @return BaseModel
     * @throws \yii\base\InvalidConfigException
     */
    public function returnError($errorMessage, $param = '')
    {
        $model = \Yii::$app->t::createModel(BaseModel::class);
        $model->addError($param, $errorMessage);
        return $model;
    }
}
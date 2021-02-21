<?php

namespace app\models\behaviours;

use app\models\BaseQuestionnaireModel;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

class SetAnswerTotalBehaviour extends AttributeBehavior
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->attributes = [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'answerTotal',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'answerTotal'
        ];
    }

    protected function getValue($event)
    {
        /** @var BaseQuestionnaireModel $model */
        $model = $event->sender;
        return $model->getQuestionsTotal();
    }
}

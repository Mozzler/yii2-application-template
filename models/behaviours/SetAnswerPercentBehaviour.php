<?php

namespace app\models\behaviours;

use app\models\BaseQuestionnaireModel;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

class SetAnswerPercentBehaviour extends AttributeBehavior
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->attributes = [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'answerPercent',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'answerPercent'
        ];
    }

    protected function getValue($event)
    {
        /** @var BaseQuestionnaireModel $model */
        $model = $event->sender;
        return number_format(($model->getQuestionsTotal() / $model->getMaxPoints()) * 100, 2);
    }
}

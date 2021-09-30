<?php

namespace app\models;

use app\models\behaviours\SetAnswerPercentBehaviour;
use app\models\behaviours\SetAnswerTotalBehaviour;
use mozzler\base\models\Model as Model;

use yii\helpers\ArrayHelper;

/**
 * Class BaseQuestionnaireModel
 * @package app\models
 *
 * @property $answerTotal integer
 * @property $answerPercent double
 */
class BaseQuestionnaireModel extends Model
{

    public $maxPoints; //  e.g If 3 questions with pointsPerQuestion 7 then it's a total max of 21
    public $pointsPerQuestion = 7;

    const FIELDS_AUTO_GENERATED = ['createdAt', 'updatedAt', 'createdUserId', 'updatedUserId'];

    protected function modelFields()
    {
        return ArrayHelper::merge(parent::modelFields(), [
            'answerTotal' => [
                'type' => 'Double',
                'label' => 'Answer Total',
            ],
            'answerPercent' => [
                'type' => 'Double',
                'label' => 'Answer Percent',
            ],
        ]);
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = $scenarios[self::SCENARIO_CREATE];
        $scenarios[self::SCENARIO_VIEW] = ArrayHelper::merge($scenarios[self::SCENARIO_CREATE], ['answerTotal', 'answerPercent'], self::FIELDS_AUTO_GENERATED); // Set the scenario view to show everything on the Admin Control Panel by default
        $scenarios[self::SCENARIO_LIST] = ['name', 'answerTotal', 'answerPercent'];
        $scenarios[self::SCENARIO_LIST_API] = $scenarios[self::SCENARIO_VIEW];
        $scenarios[self::SCENARIO_VIEW_API] = ArrayHelper::merge($scenarios[self::SCENARIO_CREATE], self::FIELDS_AUTO_GENERATED);
        return $scenarios;
    }


    public function behaviors()
    {
        // All models have an auditable (history) behaviour
        return ArrayHelper::merge(parent::behaviors(), [
            'setAnswerTotal' => ['class' => SetAnswerTotalBehaviour::class],
            'setAnswerPercent' => ['class' => SetAnswerPercentBehaviour::class],
        ]);
    }

    public function getQuestionFieldNames()
    {
        // Assuming it starts with 'question'
        $modelFields = $this->getCachedModelFields();
        $questionFields = [];
        foreach ($modelFields as $modelFieldName => $modelField) {
            if (substr($modelFieldName, 0, 8) === 'question') {
                $questionFields[] = $modelFieldName;
            }
        }
        return $questionFields;
    }

    public function getQuestionsTotal()
    {
        $total = 0;
        $questionFieldNames = $this->getQuestionFieldNames();
        foreach ($questionFieldNames as $fieldName) {
            $total += $this->__get($fieldName);
        }
        return $total;
    }

    public function getMaxPoints()
    {
        if (isset($this->maxPoints)) {
            return $this->maxPoints;
        }
        $numberOfQuestionFieldNames = count($this->getQuestionFieldNames());
        if ($numberOfQuestionFieldNames === 0) {
            $this->maxPoints = 0;
        } else {
            $this->maxPoints = $this->pointsPerQuestion * $numberOfQuestionFieldNames;
        }
        return $this->maxPoints;
    }

}
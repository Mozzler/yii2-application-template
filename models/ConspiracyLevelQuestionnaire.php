<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * Class conspiracyLevelQuestionaire
 * @package app\models
 *
 * If you define the @ property fields then you'll get IDE autocomplete
 *
 * @property $question1plots integer
 * @property $question2democracy integer
 * @property $question2unknownRulers integer

 *
 */
class ConspiracyLevelQuestionnaire extends BaseQuestionnaireModel
{

//    public $maxPoints = 3 * 7; // 3 questions, a total of 7 so 21 is the max answer
    protected static $collectionName = 'questionnaire.conspiracyLevel';

    protected function modelConfig()
    {
        return ArrayHelper::merge(parent::modelConfig(), [
            'label' => ' Questionnaire Conspiracy Level',
            'labelPlural' => 'Conspiracy Level'
        ]);
    }

    public function modelIndexes()
    {
        return ArrayHelper::merge(parent::modelIndexes(), [
            //Speed up searches
//            'exampleDeviceId' => [
//                'columns' => ['deviceId' => 1],
//            ]
        ]);
    }

    public static function rbac()
    {
        return ArrayHelper::merge(parent::rbac(), [
            'public' => [
                'find' => [
                    'grant' => true
                ],
                'insert' => [
                    'grant' => true
                ],
                'update' => [
                    'grant' => false
                ],
                'delete' => [
                    'grant' => false
                ]
            ],
        ]);
    }

    protected function modelFields()
    {
        $fields = ArrayHelper::merge(parent::modelFields(), [
            'question1plots' => [
                'type' => 'Integer',
                'label' => 'Much of our lives are being controlled by plots hatched in Secret Places',
                'required' => true,
                'hint' => "Answer from 0-7 with 0 being least agree and 7 being most agree",
                'rules' => [
                    'integer' => ['max' => 7, 'min' => 0],
                ]
            ],
            'question2democracy' => [
                'type' => 'Integer',
                'label' => 'Even though we live in a Democracy a few people always run things anyway',
                'required' => true,
                'hint' => "Answer from 0-7 with 0 being least agree and 7 being most agree",
                'rules' => [
                    'integer' => ['max' => 7, 'min' => 0]
                ]
            ],
            'question2unknownRulers' => [
                'type' => 'Integer',
                'label' => 'The people who really run the country are not known to the voters.',
                'required' => true,
                'hint' => "Answer from 0-7 with 0 being least agree and 7 being most agree",
                'rules' => [
                    'integer' => ['max' => 7, 'min' => 0]
                ]
            ],

        ]);

        return $fields;
    }

}
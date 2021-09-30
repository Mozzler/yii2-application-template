<?php

namespace app\models;

use yii\helpers\ArrayHelper;

/**
 * Class Example
 * @package app\models
 *
 * This is an example model containing a variety of field types and customisations
 *
 * If you define the @ property fields then you'll get IDE autocomplete
 *
 * @property $value double
 * @property $type string
 * @property $deviceId string
 * @property $description string
 *
 */
class Example extends BaseModel
{
    protected static $collectionName = 'app.example';

    public const TYPE_NEW = 'new';
    public const TYPE_EXISTING = 'existing';

    protected function modelConfig()
    {
        return ArrayHelper::merge(parent::modelConfig(), [
            'label' => 'Example',
            'labelPlural' => 'Examples'
        ]);
    }

    public function modelIndexes()
    {
        return ArrayHelper::merge(parent::modelIndexes(), [
            //Speed up searches
            'exampleDeviceId' => [
                'columns' => ['deviceId' => 1],
            ]
        ]);
    }

    public static function rbac()
    {
        return ArrayHelper::merge(parent::rbac(), [
        ]);
    }

    protected function modelFields()
    {
        $fields = ArrayHelper::merge(parent::modelFields(), [
            'value' => [
                'type' => 'Double',
                'label' => 'Value',
                'widgets' => [
                    'view' => [
                        'class' => 'mozzler\base\widgets\model\view\CurrencyField',
                    ],
                    'grid' => [
                        'class' => 'mozzler\base\widgets\yii\GridWidgetColumn',
                        'widgetClass' => 'mozzler\base\widgets\model\view\CurrencyField',
                        'widgetConfig' => [
                            'numberFormatterOptions' => [
                                \NumberFormatter::MIN_FRACTION_DIGITS => 2,
                                \NumberFormatter::MAX_FRACTION_DIGITS => 2,
                            ],

                        ]
                    ]
                ],
                'rules' => [
                    'required' => [
                        'when' => function ($model) {
                            return 'new' === $model->carNewOrDemo;
                        },
                        'whenClient' => "function (attribute, value) { return $('#example-type').val() == '" . Example::TYPE_NEW . "'; }"
                    ],
                    'default' => [
                        'value' => 0,
                        'when' => function ($model) {
                            return Example::TYPE_EXISTING === $model->type;
                        }
                    ]
                ]
            ],
            'type' => [
                'type' => 'SingleSelect',
                'label' => 'Status',
                'options' => [
                    self::TYPE_NEW => 'New',
                    self::TYPE_EXISTING => 'Existing',
                ],
            ],
            'deviceId' => [
                'type' => 'RelateOne',
                'relatedModel' => 'app\models\Device',
                'relatedField' => '_id',
                'label' => 'Device Id',
            ],
            'description' => [
                'type' => 'TextLarge',
                'label' => 'Description',
            ],
        ]);

        return $fields;
    }

}
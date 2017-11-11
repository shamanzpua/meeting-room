<?php
namespace common\models\meeting;

use yii\behaviors\BlameableBehavior;
use yii\mongodb\ActiveRecord;
use Yii;
use UrbanIndo\Yii2\Queue\Job;

/**
 * Class Meeting
 * @package common\models\meeting
 */
class Meeting extends ActiveRecord
{

    /**
     * @return array
     */
    public function attributes()
    {
        return ['_id', 'created_by', 'name', 'start', 'end'];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'blameable' => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start', 'end'], 'required'],
            [['start', 'end'], 'date', 'format' => 'php:' . \DateTime::ATOM],
            ['start', 'compare', 'compareAttribute' => 'end', 'operator' => '<='],
            [['start', 'end'], 'validateFreeRoom'],
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateFreeRoom($attribute, $params)
    {
        $meeting = static::find()
            ->andFilterCompare('start', $this->$attribute, '$lte')
            ->andFilterCompare('end', $this->$attribute, '$gte')
            ->one();
        if ($meeting) {
            $this->addError($attribute, 'Meeting room isn\' t free');
        }
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->addToQueue();
    }

    /**
     * add to queue
     */
    public function addToQueue()
    {
        $route = 'meeting/send-push';
        $data = ['meetingId' => (string) $this->_id];
        Yii::$app->queue->post(new Job(['route' => $route, 'data' => $data]));
    }


    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            '_id',
            'created_by',
            'start',
            'end',
        ];
    }
}

<?php

namespace frontend\modules\kiosk\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "tb_caller".
 *
 * @property int $caller_ids running
 * @property int $q_ids
 * @property int $service_sec_id
 * @property int $counter_service_id ชื่อช่องบริการ
 * @property string $call_timestp เวลาที่เรียก
 * @property int $created_by ผู้เรียก
 * @property string $created_at
 * @property int $updated_by
 * @property string $updated_at
 * @property string $call_status รอเรียก/เรียกแล้ว/Hold
 */
class TbCaller extends \yii\db\ActiveRecord
{
    const STATUS_CALLING = 'calling';
    const STATUS_HOLD = 'hold';
    const STATUS_CALLEND = 'callend';
    const STATUS_FINISHED = 'finished';
    const STATUS_END = 'end';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_caller';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q_ids', 'qtran_ids', 'service_sec_id', 'counter_service_id', 'created_by', 'updated_by'], 'integer'],
            [['call_timestp', 'created_at', 'updated_at'], 'safe'],
            [['call_status'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'caller_ids' => Yii::t('app', 'running'),
            'q_ids' => Yii::t('app', 'Q Ids'),
            'qtran_ids' => Yii::t('app', 'Qtran Ids'),
            'service_sec_id' => Yii::t('app', 'Service Sec ID'),
            'counter_service_id' => Yii::t('app', 'ชื่อช่องบริการ'),
            'call_timestp' => Yii::t('app', 'เวลาที่เรียก'),
            'created_by' => Yii::t('app', 'ผู้เรียก'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'call_status' => Yii::t('app', 'รอเรียก/เรียกแล้ว/Hold'),
        ];
    }

    public function getCounterservice()
    {
        return $this->hasOne(TbCounterservice::className(), ['counterserviceid' => 'counter_service_id']);
    }
}

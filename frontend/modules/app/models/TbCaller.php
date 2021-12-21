<?php

namespace frontend\modules\app\models;

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
 * @property int $qtran_ids
 * @property int $servicegroupid
 * @property int $counter_service_id ชื่อช่องบริการ
 * @property string $call_timestp เวลาที่เรียก
 * @property int $created_by ผู้เรียก
 * @property string $created_at
 * @property int $updated_by
 * @property string $updated_at
 * @property string $call_status รอเรียก/เรียกแล้ว/Hold
 * @property int $service_profile_id โปรไฟล์
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
            [['q_ids', 'qtran_ids', 'servicegroupid', 'counter_service_id', 'created_by', 'updated_by', 'service_profile_id'], 'integer'],
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
            'caller_ids' => 'running',
            'q_ids' => 'Q Ids',
            'qtran_ids' => 'Qtran Ids',
            'servicegroupid' => 'Servicegroupid',
            'counter_service_id' => 'ชื่อช่องบริการ',
            'call_timestp' => 'เวลาที่เรียก',
            'created_by' => 'ผู้เรียก',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'call_status' => 'รอเรียก/เรียกแล้ว/Hold',
            'service_profile_id' => 'โปรไฟล์',
        ];
    }

    /**
     * @inheritdoc
     * @return TbCallerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbCallerQuery(get_called_class());
    }

    public function getTbCounterservice()
    {
        return $this->hasOne(TbCounterservice::className(), ['counterserviceid' => 'counter_service_id']);
    }
}

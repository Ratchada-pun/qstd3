<?php

namespace frontend\modules\kiosk\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "tb_qtrans".
 *
 * @property int $ids
 * @property int $q_ids
 * @property int $service_sec_id รหัสแผนก
 * @property int $counter_service_id ช่องบริการ/ห้อง
 * @property int $doctor_id
 * @property string $checkin_date เวลาลงทะเบียนแผนก
 * @property string $checkout_date เวลาออกแผนก
 * @property int $service_status_id
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class TbQtrans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_qtrans';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at','checkin_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
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
            [['q_ids', 'service_sec_id'], 'unique', 'targetAttribute' => ['q_ids', 'service_sec_id']],
            [['q_ids'], 'required'],
            [['q_ids', 'service_sec_id', 'counter_service_id', 'doctor_id', 'service_status_id', 'created_by', 'updated_by'], 'integer'],
            [['checkin_date', 'checkout_date', 'created_at', 'updated_at'], 'safe'],
            [['counter_service_id'], 'required', 'on' => 'endq'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ids' => Yii::t('app', 'Ids'),
            'q_ids' => Yii::t('app', 'Q Ids'),
            'service_sec_id' => Yii::t('app', 'รหัสแผนก'),
            'counter_service_id' => Yii::t('app', 'ช่องบริการ/ห้อง'),
            'doctor_id' => Yii::t('app', 'Doctor ID'),
            'checkin_date' => Yii::t('app', 'เวลาลงทะเบียนแผนก'),
            'checkout_date' => Yii::t('app', 'เวลาออกแผนก'),
            'service_status_id' => Yii::t('app', 'Service Status ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function scenarios()
    {
        $sn = parent::scenarios();
        $sn['endq'] = ['counter_service_id'];
        return $sn;
    }
}

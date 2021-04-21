<?php

namespace frontend\modules\app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "tb_qtrans".
 *
 * @property int $ids
 * @property int $q_ids คิวไอดี
 * @property int $counter_service_id ช่องบริการ/ห้อง
 * @property int $doctor_id แพทย์
 * @property string $checkin_date เวลาลงทะเบียนแผนก
 * @property string $checkout_date เวลาออกแผนก
 * @property int $service_status_id สถานะ
 * @property string $created_at วันที่สร้าง
 * @property string $updated_at วันที่แก้ไข
 * @property int $created_by ผู้บันทึก
 * @property int $updated_by ผู้แก้ไข
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
                'value' => Yii::$app->formatter->asDate('now','php:Y-m-d H:i:s')
            ],
            // [
            //     'class' => BlameableBehavior::className(),
            //     'createdByAttribute' => 'created_by',
            //     'updatedByAttribute' => 'updated_by',
            // ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q_ids', 'counter_service_id', 'doctor_id', 'service_status_id', 'created_by', 'updated_by','servicegroupid'], 'integer'],
            [['checkin_date', 'checkout_date', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ids' => 'Ids',
            'q_ids' => 'คิวไอดี',
            'servicegroupid' => 'ชื่อบริการ',
            'counter_service_id' => 'ช่องบริการ/ห้อง',
            'doctor_id' => 'แพทย์',
            'checkin_date' => 'เวลาลงทะเบียนแผนก',
            'checkout_date' => 'เวลาออกแผนก',
            'service_status_id' => 'สถานะ',
            'created_at' => 'วันที่สร้าง',
            'updated_at' => 'วันที่แก้ไข',
            'created_by' => 'ผู้บันทึก',
            'updated_by' => 'ผู้แก้ไข',
        ];
    }

    /**
     * @inheritdoc
     * @return TbQtransQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbQtransQuery(get_called_class());
    }

    public function getTbCounterservice()
    {
        return $this->hasOne(TbCounterservice::className(), ['counterserviceid' => 'counter_service_id']);
    }

    public function getTbQuequ()
    {
        return $this->hasOne(TbQuequ::className(), ['q_ids' => 'q_ids']);
    }
}

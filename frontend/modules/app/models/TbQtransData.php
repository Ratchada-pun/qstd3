<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_qtrans_data".
 *
 * @property int $trans_ids
 * @property int $ids
 * @property int $q_ids คิวไอดี
 * @property int $servicegroupid ชื่อบริการ
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
class TbQtransData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_qtrans_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ids', 'q_ids', 'servicegroupid', 'counter_service_id', 'doctor_id', 'service_status_id', 'created_by', 'updated_by'], 'integer'],
            [['checkin_date', 'checkout_date', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trans_ids' => Yii::t('app', 'Trans Ids'),
            'ids' => Yii::t('app', 'Ids'),
            'q_ids' => Yii::t('app', 'คิวไอดี'),
            'servicegroupid' => Yii::t('app', 'ชื่อบริการ'),
            'counter_service_id' => Yii::t('app', 'ช่องบริการ/ห้อง'),
            'doctor_id' => Yii::t('app', 'แพทย์'),
            'checkin_date' => Yii::t('app', 'เวลาลงทะเบียนแผนก'),
            'checkout_date' => Yii::t('app', 'เวลาออกแผนก'),
            'service_status_id' => Yii::t('app', 'สถานะ'),
            'created_at' => Yii::t('app', 'วันที่สร้าง'),
            'updated_at' => Yii::t('app', 'วันที่แก้ไข'),
            'created_by' => Yii::t('app', 'ผู้บันทึก'),
            'updated_by' => Yii::t('app', 'ผู้แก้ไข'),
        ];
    }
}

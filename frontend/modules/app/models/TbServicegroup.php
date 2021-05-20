<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_servicegroup".
 *
 * @property int $servicegroupid เลขที่กลุ่มบริการ
 * @property string $servicegroup_name ชื่อกลุ่มบริการ
 * @property int $servicegroup_order ลำดับการแสดง
 *
 * @property TbService[] $tbServices
 */
class TbServicegroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_servicegroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['servicegroup_order', 'servicegroup_name'], 'required'],
            [['servicegroup_type_id', 'servicegroup_order', 'subservice_status', 'servicegroup_status', 'show_on_kiosk', 'show_on_mobile', 'servicestatus_default'], 'integer'],
            [['servicegroup_code'], 'string', 'max' => 50],
            [['servicegroup_name'], 'string', 'max' => 100],
            [['servicegroup_prefix'], 'string', 'max' => 2],
            [['servicegroup_clinic'], 'string', 'max' => 1],
        ];
    }

     /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'servicegroupid' => 'เลขที่กลุ่มบริการ',
            'servicegroup_code' => 'ชื่อกลุ่มบริการ',
            'servicegroup_type_id' => 'รหัสประเภทกลุ่มบริการ',
            'servicegroup_name' => 'ชื่อกลุ่มบริการ',
            'servicegroup_prefix' => 'ตัวอักษร/ตัวเลข นำหน้าคิว',
            'servicegroup_order' => 'ลำดับการแสดง',
            'subservice_status' => 'สถานะการเปิดsubmenu',
            'servicegroup_status' => 'สถานะการเปิดแผนก',
            'show_on_kiosk' => 'แสดงบน kisok',
            'show_on_mobile' => 'แสดงผลบน mobile app',
            'servicestatus_default' => 'ค่าเริ่มต้นของการทำรายการบน mobile',
            'servicegroup_clinic' => 'T=เป็นคลีนิคตรวจรักษา F=เป็นแผนกอื่นๆ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbServices()
    {
        return $this->hasMany(TbService::className(), ['service_groupid' => 'servicegroupid']);
    }

    public function getServicesTicket()
    {
        return $this->hasMany(TbService::className(), ['service_groupid' => 'servicegroupid'])->where(['service_status' => 1]);
    }

    /**
     * @inheritdoc
     * @return TbServicegroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbServicegroupQuery(get_called_class());
    }
}

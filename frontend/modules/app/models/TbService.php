<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_service".
 *
 * @property int $serviceid เลขที่บริการ
 * @property string $service_name ชื่อบริการ
 * @property int $service_groupid เลขที่กลุ่มบริการ
 * @property string $service_route ลำดับการบริการ
 * @property int $prn_profileid แบบการพิมพ์บัตรคิว
 * @property int $prn_copyqty จำนวนพิมพ์ต่อครั้ง
 * @property string $service_prefix ตัวอักษร/ตัวเลข นำหน้าคิว
 * @property int $service_numdigit จำนวนหลักหมายเลขคิว
 * @property string $service_status สถานะคิว
 * @property int $service_md_name_id ชื่อแพทย์
 *
 * @property TbServicegroup $serviceGroup
 */
class TbService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_name','prn_profileid','prn_copyqty','service_prefix','service_numdigit','service_status'], 'required'],
            [['service_groupid', 'prn_profileid', 'prn_copyqty', 'service_numdigit', 'service_md_name_id','print_by_hn','quickly','show_on_kiosk','prn_profileid_quickly'], 'integer'],
            [['service_name','btn_kiosk_name'], 'string', 'max' => 100],
            [['service_route'], 'string', 'max' => 11],
            [['service_prefix'], 'string', 'max' => 2],
            [['service_status'], 'string', 'max' => 10],
            [['service_groupid'], 'exist', 'skipOnError' => true, 'targetClass' => TbServicegroup::className(), 'targetAttribute' => ['service_groupid' => 'servicegroupid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serviceid' => 'เลขที่บริการ',
            'service_name' => 'ชื่อบริการ',
            'service_groupid' => 'เลขที่กลุ่มบริการ',
            'service_route' => 'ลำดับการบริการ',
            'prn_profileid' => 'แบบการพิมพ์บัตรคิว',
            'prn_copyqty' => 'จำนวนพิมพ์ต่อครั้ง',
            'service_prefix' => 'ตัวอักษร/ตัวเลข นำหน้าคิว',
            'service_numdigit' => 'จำนวนหลักหมายเลขคิว',
            'service_status' => 'สถานะคิว',
            'service_md_name_id' => 'ชื่อแพทย์',
            'print_by_hn' => 'ออกบัตรคิวโดยใช้ HN',
            'quickly' => 'แสดงปุ่มคิวด่วน',
            'show_on_kiosk' => 'แสดงปุ่ม Kiosk',
            'btn_kiosk_name' => 'ชื่อปุ่ม Kiosk',
            'prn_profileid_quickly' => 'แบบการพิมพ์บัตรคิวด่วน'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceGroup()
    {
        return $this->hasOne(TbServicegroup::className(), ['servicegroupid' => 'service_groupid']);
    }

    /**
     * @inheritdoc
     * @return TbServiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbServiceQuery(get_called_class());
    }
}

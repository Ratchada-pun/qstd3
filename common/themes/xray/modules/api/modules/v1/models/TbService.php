<?php

namespace xray\modules\api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "tb_service".
 *
 * @property int $serviceid เลขที่บริการ
 * @property string|null $service_name ชื่อบริการ
 * @property int|null $service_groupid เลขที่กลุ่มบริการ
 * @property string|null $service_route ลำดับการบริการ
 * @property int|null $prn_profileid แบบการพิมพ์บัตรคิว
 * @property int|null $prn_profileid_quickly แบบการพิมพ์บัตรคิวด่วน
 * @property int|null $prn_copyqty จำนวนพิมพ์ต่อครั้ง
 * @property string|null $service_prefix ตัวอักษร/ตัวเลข นำหน้าคิว
 * @property int|null $service_numdigit จำนวนหลักหมายเลขคิว
 * @property string|null $service_status สถานะคิว
 * @property int|null $service_md_name_id ชื่อแพทย์
 * @property int|null $print_by_hn ออกบัตรคิวโดยใช้ HN
 * @property int|null $quickly คิวด่วน
 * @property int|null $show_on_kiosk แสดงปุ่ม Kiosk
 * @property int|null $show_on_mobile แสดงปุ่มบน mobile app
 * @property string|null $btn_kiosk_name ชื่อปุ่ม Kiosk
 * @property string|null $main_dep รหัสแผนก
 * @property int|null $service_type_id ประเภทบริการ
 * @property string|null $service_pic ภาพถ่าย base64
 */
class TbService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_groupid', 'prn_profileid', 'prn_profileid_quickly', 'prn_copyqty', 'service_numdigit', 'service_md_name_id', 'print_by_hn', 'quickly', 'show_on_kiosk', 'show_on_mobile', 'service_type_id'], 'integer'],
            [['service_name', 'btn_kiosk_name'], 'string', 'max' => 100],
            [['service_route'], 'string', 'max' => 11],
            [['service_prefix'], 'string', 'max' => 2],
            [['service_status'], 'string', 'max' => 10],
            [['main_dep'], 'string', 'max' => 50],
            [['service_pic'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'serviceid' => 'เลขที่บริการ',
            'service_name' => 'ชื่อบริการ',
            'service_groupid' => 'เลขที่กลุ่มบริการ',
            'service_route' => 'ลำดับการบริการ',
            'prn_profileid' => 'แบบการพิมพ์บัตรคิว',
            'prn_profileid_quickly' => 'แบบการพิมพ์บัตรคิวด่วน',
            'prn_copyqty' => 'จำนวนพิมพ์ต่อครั้ง',
            'service_prefix' => 'ตัวอักษร/ตัวเลข นำหน้าคิว',
            'service_numdigit' => 'จำนวนหลักหมายเลขคิว',
            'service_status' => 'สถานะคิว',
            'service_md_name_id' => 'ชื่อแพทย์',
            'print_by_hn' => 'ออกบัตรคิวโดยใช้ HN',
            'quickly' => 'คิวด่วน',
            'show_on_kiosk' => 'แสดงปุ่ม Kiosk',
            'show_on_mobile' => 'แสดงปุ่มบน mobile app',
            'btn_kiosk_name' => 'ชื่อปุ่ม Kiosk',
            'main_dep' => 'รหัสแผนก',
            'service_type_id' => 'ประเภทบริการ',
            'service_pic' => 'ภาพถ่าย base64',
        ];
    }
}

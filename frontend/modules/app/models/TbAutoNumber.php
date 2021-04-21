<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_auto_number".
 *
 * @property int $auto_number_id ไอดี
 * @property int $prefix_id ตัวอักษรหน้าเลขคิว
 * @property int $service_group_id กลุ่มบริการ, กลุ่มแผนก, กลุ่มห้องตรวจ
 * @property int $service_id แผนก, ห้องตรวจ, จุดบริการ
 * @property int $appoint_split แยกคิวนัด,ไม่นัด
 * @property string $number หมายเลขคิว
 * @property int $prefix_succession ออกเลขคิวต่อเนื่องแผนกที่มีตัวอักษรนำหน้าเลขคิวเดียวกัน
 * @property string $updated_at วันที่อัพเดทเลขคิว
 */
class TbAutoNumber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_auto_number';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prefix_id', 'service_group_id', 'appoint_split', 'number', 'prefix_succession'], 'required'],
            [['prefix_id', 'service_group_id', 'service_id', 'appoint_split', 'prefix_succession'], 'integer'],
            [['updated_at'], 'safe'],
            [['number'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auto_number_id' => 'ไอดี',
            'prefix_id' => 'ตัวอักษรหน้าเลขคิว',
            'service_group_id' => 'กลุ่มบริการ, กลุ่มแผนก, กลุ่มห้องตรวจ',
            'service_id' => 'แผนก, ห้องตรวจ, จุดบริการ',
            'appoint_split' => 'แยกคิวนัด,ไม่นัด',
            'number' => 'หมายเลขคิว',
            'prefix_succession' => 'ออกเลขคิวต่อเนื่องแผนกที่มีตัวอักษรนำหน้าเลขคิวเดียวกัน',
            'updated_at' => 'วันที่อัพเดทเลขคิว',
        ];
    }
}

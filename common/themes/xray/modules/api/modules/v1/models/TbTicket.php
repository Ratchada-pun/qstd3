<?php

namespace xray\modules\api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "tb_ticket".
 *
 * @property int $ids
 * @property string $hos_name_th ชื่อ รพ. ไทย
 * @property string|null $hos_name_en ชื่อ รพ. อังกฤษ
 * @property string|null $template แบบบัตรคิว
 * @property string|null $default_template ต้นฉบับบัตรคิว
 * @property string|null $logo_path
 * @property string|null $logo_base_url
 * @property string $barcode_type รหัสโค้ด
 * @property int|null $status สถานะการใช้งาน
 */
class TbTicket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hos_name_th', 'barcode_type'], 'required'],
            [['template', 'default_template'], 'string'],
            [['status'], 'integer'],
            [['hos_name_th', 'hos_name_en', 'logo_path', 'logo_base_url', 'barcode_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ids' => 'Ids',
            'hos_name_th' => 'ชื่อ รพ. ไทย',
            'hos_name_en' => 'ชื่อ รพ. อังกฤษ',
            'template' => 'แบบบัตรคิว',
            'default_template' => 'ต้นฉบับบัตรคิว',
            'logo_path' => 'Logo Path',
            'logo_base_url' => 'Logo Base Url',
            'barcode_type' => 'รหัสโค้ด',
            'status' => 'สถานะการใช้งาน',
        ];
    }
}

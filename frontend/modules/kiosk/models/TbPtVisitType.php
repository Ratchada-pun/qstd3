<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_pt_visit_type".
 *
 * @property int $pt_visit_type_id รหัสประเภท
 * @property string $pt_visit_type ชื่อประเภท
 */
class TbPtVisitType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_pt_visit_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pt_visit_type', 'pt_visit_type_prefix', 'pt_visit_type_digit'], 'required'],
            [['pt_visit_type_digit'], 'integer'],
            [['pt_visit_type', 'pt_visit_type_prefix'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pt_visit_type_id' => 'รหัสประเภท',
            'pt_visit_type' => 'ชื่อประเภท',
            'pt_visit_type_prefix' => 'ตัวอักษร/ตัวเลข นำหน้าคิว',
            'pt_visit_type_digit' => 'จำนวนหลักหมายเลขคิว',
        ];
    }
}

<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_counterservice_type".
 *
 * @property int $tb_counterservice_typeid
 * @property string $tb_counterservice_type
 * @property int $q_waiting_status สถานะรอ
 * @property int $q_calling_status สถานะเรียก
 */
class TbCounterserviceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_counterservice_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tb_counterservice_type', 'q_waiting_status', 'q_calling_status'], 'required'],
            [['q_waiting_status', 'q_calling_status'], 'integer'],
            [['tb_counterservice_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tb_counterservice_typeid' => 'Tb Counterservice Typeid',
            'tb_counterservice_type' => 'ชื่อประเภท',
            'q_waiting_status' => 'สถานะรอ',
            'q_calling_status' => 'สถานะเรียก',
        ];
    }
}

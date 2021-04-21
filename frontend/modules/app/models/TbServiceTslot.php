<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_service_tslot".
 *
 * @property int $tslotid รหัสช่วงเวลา
 * @property int $serviceid
 * @property string $t_slot_begin ช่วงเวลาเริ่ม
 * @property string $t_slot_end ช่วงเวลาสิ้นสุด
 * @property int $q_limit 1 =จำกัดคิว 0 = ไม่จำกัดคิว 
 * @property int $q_limitqty จำนวนคิว
 */
class TbServiceTslot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_service_tslot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serviceid', 'q_limit', 'q_limitqty'], 'integer'],
            [['t_slot_begin', 't_slot_end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tslotid' => 'รหัสช่วงเวลา',
            'serviceid' => 'Serviceid',
            't_slot_begin' => 'ช่วงเวลาเริ่ม',
            't_slot_end' => 'ช่วงเวลาสิ้นสุด',
            'q_limit' => '1 =จำกัดคิว 0 = ไม่จำกัดคิว ',
            'q_limitqty' => 'จำนวนคิว',
        ];
    }
}

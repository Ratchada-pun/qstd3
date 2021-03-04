<?php

namespace frontend\modules\app\models\mobile;

use Yii;

/**
 * This is the model class for table "tb_quequ".
 *
 * @property int $q_ids running
 * @property string $q_num หมายเลขคิว
 * @property string $q_timestp วันที่ออกคิว
 * @property int $q_arrive_time เวลามาถึง
 * @property int $q_appoint_time เวลานัดหมาย
 * @property int $pt_id
 * @property string $q_vn Visit number ของผู้ป่วย
 * @property string $q_hn หมายเลข HN ผู้ป่วย
 * @property string $pt_name ชื่อผู้ป่วย
 * @property int $pt_visit_type_id ประเภท
 * @property int $pt_appoint_sec_id แผนกที่นัดหมาย
 * @property int $serviceid ประเภทบริการ
 * @property int $servicegroupid
 * @property int $quickly คิวด่วน
 * @property int $q_status_id สถานะ
 * @property int $doctor_id
 * @property int $counterserviceid เลขที่ช่องบริการ
 * @property string $created_at วันที่บันทึก
 * @property string $updated_at วันที่แก้ไข
 */
class TbQuequ extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_quequ';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['q_timestp', 'created_at', 'updated_at'], 'safe'],
            [['q_arrive_time', 'q_appoint_time', 'pt_id', 'pt_visit_type_id', 'pt_appoint_sec_id', 'serviceid', 'servicegroupid', 'quickly', 'q_status_id', 'doctor_id', 'counterserviceid'], 'integer'],
            [['q_num', 'q_vn', 'q_hn'], 'string', 'max' => 20],
            [['pt_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'q_ids' => 'running',
            'q_num' => 'หมายเลขคิว',
            'q_timestp' => 'วันที่ออกคิว',
            'q_arrive_time' => 'เวลามาถึง',
            'q_appoint_time' => 'เวลานัดหมาย',
            'pt_id' => 'Pt ID',
            'q_vn' => 'Visit number ของผู้ป่วย',
            'q_hn' => 'หมายเลข HN ผู้ป่วย',
            'pt_name' => 'ชื่อผู้ป่วย',
            'pt_visit_type_id' => 'ประเภท',
            'pt_appoint_sec_id' => 'แผนกที่นัดหมาย',
            'serviceid' => 'ประเภทบริการ',
            'servicegroupid' => 'Servicegroupid',
            'quickly' => 'คิวด่วน',
            'q_status_id' => 'สถานะ',
            'doctor_id' => 'Doctor ID',
            'counterserviceid' => 'เลขที่ช่องบริการ',
            'created_at' => 'วันที่บันทึก',
            'updated_at' => 'วันที่แก้ไข',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TbQuequQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbQuequQuery(get_called_class());
    }
}

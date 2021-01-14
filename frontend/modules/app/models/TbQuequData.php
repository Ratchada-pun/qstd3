<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_quequ_data".
 *
 * @property int $ids
 * @property int $q_ids running
 * @property string $q_num หมายเลขคิว
 * @property string $q_timestp วันที่ออกคิว
 * @property int $pt_id
 * @property string $q_vn Visit number ของผู้ป่วย
 * @property string $q_hn หมายเลข HN ผู้ป่วย
 * @property string $pt_name ชื่อผู้ป่วย
 * @property int $pt_visit_type_id ประเภท
 * @property int $pt_appoint_sec_id แผนกที่นัดหมาย
 * @property int $serviceid ประเภทบริการ
 * @property int $servicegroupid
 * @property int $q_status_id สถานะ
 * @property string $doctor_id แพทย์
 * @property string $created_at วันที่บันทึก
 * @property string $updated_at วันที่แก้ไข
 */
class TbQuequData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_quequ_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q_ids', 'pt_id', 'pt_visit_type_id', 'pt_appoint_sec_id', 'serviceid', 'servicegroupid', 'q_status_id'], 'integer'],
            [['q_timestp', 'created_at', 'updated_at'], 'safe'],
            [['q_num', 'q_vn', 'q_hn'], 'string', 'max' => 20],
            [['pt_name'], 'string', 'max' => 200],
            [['doctor_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ids' => Yii::t('app', 'Ids'),
            'q_ids' => Yii::t('app', 'running'),
            'q_num' => Yii::t('app', 'หมายเลขคิว'),
            'q_timestp' => Yii::t('app', 'วันที่ออกคิว'),
            'pt_id' => Yii::t('app', 'Pt ID'),
            'q_vn' => Yii::t('app', 'Visit number ของผู้ป่วย'),
            'q_hn' => Yii::t('app', 'หมายเลข HN ผู้ป่วย'),
            'pt_name' => Yii::t('app', 'ชื่อผู้ป่วย'),
            'pt_visit_type_id' => Yii::t('app', 'ประเภท'),
            'pt_appoint_sec_id' => Yii::t('app', 'แผนกที่นัดหมาย'),
            'serviceid' => Yii::t('app', 'ประเภทบริการ'),
            'servicegroupid' => Yii::t('app', 'Servicegroupid'),
            'q_status_id' => Yii::t('app', 'สถานะ'),
            'doctor_id' => Yii::t('app', 'แพทย์'),
            'created_at' => Yii::t('app', 'วันที่บันทึก'),
            'updated_at' => Yii::t('app', 'วันที่แก้ไข'),
        ];
    }
}

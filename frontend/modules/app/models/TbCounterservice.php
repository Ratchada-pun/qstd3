<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_counterservice".
 *
 * @property int $counterserviceid เลขที่ช่องบริการ
 * @property string $counterservice_name ชื่อช่องบริการ
 * @property int $counterservice_callnumber หมายเลข
 * @property int $counterservice_type ประเภท
 * @property int $servicegroupid กลุ่มบริการ
 * @property int $userid ผู้ให้บริการ (1,2,3 หรือ all)
 * @property string $serviceid เรียก serviceid
 * @property int $sound_stationid เครื่องเล่นเสียงที่
 * @property int $sound_id ไฟล์เสียง
 * @property string $counterservice_status สถานะ
 *
 * @property TbCounterserviceType $counterserviceType
 */
class TbCounterservice extends \yii\db\ActiveRecord
{
    public $items_sort;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_counterservice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counterservice_name', 'counterservice_callnumber', 'sound_id', 'counterservice_status', 'sound_service_id'], 'required'],
            [['counterservice_callnumber', 'counterservice_type', 'servicegroupid', 'userid', 'sound_stationid', 'sound_id', 'service_order', 'sound_en_id', 'sound_service_en_id'], 'integer'],
            [['counterservice_name'], 'string', 'max' => 100],
            [['serviceid'], 'string', 'max' => 20],
            [['counterservice_status'], 'string', 'max' => 10],
            [['items_sort'], 'safe'],
            [['counterservice_type'], 'exist', 'skipOnError' => true, 'targetClass' => TbCounterserviceType::className(), 'targetAttribute' => ['counterservice_type' => 'counterservice_typeid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'counterserviceid' => 'เลขที่ช่องบริการ',
            'counterservice_name' => 'ชื่อช่องบริการ',
            'counterservice_callnumber' => 'หมายเลข',
            'counterservice_type' => 'ประเภท',
            'servicegroupid' => 'กลุ่มบริการ',
            'userid' => 'ผู้ให้บริการ (1,2,3 หรือ all)',
            'serviceid' => 'เรียก serviceid',
            'sound_stationid' => 'เครื่องเล่นเสียงที่',
            'sound_id' => 'ไฟล์เสียงหมายเลข',
            'counterservice_status' => 'สถานะ',
            'sound_service_id' => 'เสียงบริการ',
            'service_order' => 'จัดเรียง',
            'sound_en_id' => 'ไฟล์เสียงหมายเลข(ENG)',
            'sound_service_en_id' => 'ไฟล์เสียงบริการ(ENG)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCounterserviceType()
    {
        return $this->hasOne(TbCounterserviceType::className(), ['counterservice_typeid' => 'counterservice_type']);
    }

    public function getTbSound()
    {
        return $this->hasOne(TbSound::className(), ['sound_id' => 'sound_id']);
    }

    public function getSoundService()
    {
        return $this->hasOne(TbSound::className(), ['sound_id' => 'sound_service_id']);
    }

    /**
     * @inheritdoc
     * @return TbCounterserviceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbCounterserviceQuery(get_called_class());
    }
}

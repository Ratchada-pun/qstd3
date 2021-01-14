<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_counterservice".
 *
 * @property int $counterserviceid เลขที่บริการ
 * @property string $counterservice_name ชื่อบริการ
 * @property int $counterservice_callnumber
 * @property int $counterservice_type ประเภทบริการ
 * @property int $servicegroupid
 * @property int $userid ผู้ให้บริการ (1,2,3 หรือ all)
 * @property int $sec_id แผนก
 * @property int $sound_stationid
 * @property int $sound_typeid ประเภทเสียง
 * @property string $counterservice_status
 */
class TbCounterservice extends \yii\db\ActiveRecord
{
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
            [['counterservice_name', 'sound_path','sound_service_number','sound_typeid','sound_service_name','counterservice_type','sec_id'], 'required'],
            [['counterservice_callnumber', 'counterservice_type', 'servicegroupid', 'userid', 'sec_id', 'sound_stationid', 'sound_typeid','sound_service_number'], 'integer'],
            [['counterservice_name', 'sound_path','sound_service_name'], 'string', 'max' => 255],
            [['counterservice_status'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'counterserviceid' => Yii::t('app', 'เลขที่บริการ'),
            'counterservice_name' => Yii::t('app', 'ชื่อบริการ'),
            'counterservice_callnumber' => Yii::t('app', 'Counterservice Callnumber'),
            'counterservice_type' => Yii::t('app', 'ประเภทบริการ'),
            'servicegroupid' => Yii::t('app', 'Servicegroupid'),
            'userid' => Yii::t('app', 'ผู้ให้บริการ (1,2,3 หรือ all)'),
            'sec_id' => Yii::t('app', 'แผนก'),
            'sound_stationid' => Yii::t('app', 'Sound Stationid'),
            'sound_typeid' => Yii::t('app', 'ประเภทเสียง'),
            'counterservice_status' => Yii::t('app', 'Counterservice Status'),
            'sound_path' => Yii::t('app', 'ชื่อโฟร์เดอร์ไฟล์เสียง'),
            'sound_service_number' => Yii::t('app', 'ลำดับที่ให้บริการ'),
            'sound_service_name' => Yii::t('app', 'ชื่อไฟล์เสียงบริการ'),
        ];
    }

    public function getCounterType()
    {
        return $this->hasOne(TbCounterserviceType::className(), ['tb_counterservice_typeid' => 'counterservice_type']);
    }

    public function getServiceMdName()
    {
        return $this->hasOne(TbServiceMdName::className(), ['service_md_name_id' => 'userid']);
    }
}

<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_servicegroup".
 *
 * @property int $servicegroupid เลขที่กลุ่มบริการ
 * @property string $servicegroup_name ชื่อกลุ่มบริการ
 * @property int $servicegroup_order ลำดับการแสดง
 *
 * @property TbService[] $tbServices
 */
class TbServicegroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_servicegroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['servicegroup_order', 'servicegroup_name'], 'required'],
            [['servicegroup_order'], 'integer'],
            [['servicegroup_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'servicegroupid' => 'เลขที่กลุ่มบริการ',
            'servicegroup_name' => 'ชื่อกลุ่มบริการ',
            'servicegroup_order' => 'ลำดับการแสดง',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbServices()
    {
        return $this->hasMany(TbService::className(), ['service_groupid' => 'servicegroupid']);
    }

    public function getServicesTicket()
    {
        return $this->hasMany(TbService::className(), ['service_groupid' => 'servicegroupid'])->where(['service_status' => 1]);
    }

    /**
     * @inheritdoc
     * @return TbServicegroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbServicegroupQuery(get_called_class());
    }
}

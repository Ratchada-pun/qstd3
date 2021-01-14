<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_service_status".
 *
 * @property int $service_status_id
 * @property string $service_status_name สถานะ
 */
class TbServiceStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_service_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_status_name'], 'required'],
            [['service_status_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_status_id' => Yii::t('app', 'Service Status ID'),
            'service_status_name' => Yii::t('app', 'สถานะ'),
        ];
    }
}

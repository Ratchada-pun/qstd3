<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_service_md_name".
 *
 * @property int $service_md_name_id
 * @property string $service_md_name
 */
class TbServiceMdName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_service_md_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_md_name'], 'required'],
            [['service_md_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_md_name_id' => Yii::t('app', 'Service Md Name ID'),
            'service_md_name' => Yii::t('app', 'ชื่อแพทย์'),
        ];
    }
}

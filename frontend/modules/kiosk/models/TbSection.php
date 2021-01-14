<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_section".
 *
 * @property int $sec_id รหัสแผนก
 * @property string $sec_name ชื่อแผนก
 * @property int $sec_firststatus
 */
class TbSection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sec_name', 'sec_firststatus'], 'required'],
            [['sec_firststatus'], 'integer'],
            [['sec_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sec_id' => 'รหัสแผนก',
            'sec_name' => 'ชื่อแผนก',
            'sec_firststatus' => 'สถานะรอ',
        ];
    }
}

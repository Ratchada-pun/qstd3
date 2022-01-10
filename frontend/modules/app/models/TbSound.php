<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_sound".
 *
 * @property int $sound_id
 * @property string $sound_name ชื่อไฟล์
 * @property string $sound_path_name โฟรเดอร์ไฟล์
 * @property string $sound_th เสียงเรียก
 * @property int $sound_type ประเภทเสียง
 * @property string $language ภาษา
 */
class TbSound extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_sound';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sound_name', 'sound_path_name','language'], 'required'],
            [['sound_type'], 'integer'],
            [['sound_name', 'sound_path_name', 'sound_th'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 50],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sound_id' => 'Sound ID',
            'sound_name' => 'ชื่อไฟล์',
            'sound_path_name' => 'โฟรเดอร์ไฟล์',
            'sound_th' => 'เสียงเรียก',
            'sound_type' => 'ประเภทเสียง',
            'language' => 'ภาษา',
        ];
    }

    /**
     * @inheritdoc
     * @return TbSoundQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbSoundQuery(get_called_class());
    }
}

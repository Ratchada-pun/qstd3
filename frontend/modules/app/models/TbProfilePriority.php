<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_profile_priority".
 *
 * @property int $profile_priority_id
 * @property int $profile_priority_seq ลำดับ
 * @property int $service_profile_id โปรไฟล์
 * @property int $service_id งานบริการ
 */
class TbProfilePriority extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_profile_priority';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['profile_priority_seq', 'service_profile_id', 'service_id'], 'required'],
            [['profile_priority_seq', 'service_profile_id', 'service_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'profile_priority_id' => 'Profile Priority ID',
            'profile_priority_seq' => 'ลำดับ',
            'service_profile_id' => 'โปรไฟล์',
            'service_id' => 'งานบริการ',
        ];
    }
}

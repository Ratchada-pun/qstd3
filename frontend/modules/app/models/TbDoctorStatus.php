<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_doctor_status".
 *
 * @property int $ID
 * @property string $Status_T
 * @property string $Status_E
 */
class TbDoctorStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_doctor_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'integer'],
            [['Status_T'], 'string', 'max' => 50],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Status_T' => 'Status T'
        ];
    }

    /**
     * {@inheritdoc}
     * @return TbDoctorStatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbDoctorStatusQuery(get_called_class());
    }
}

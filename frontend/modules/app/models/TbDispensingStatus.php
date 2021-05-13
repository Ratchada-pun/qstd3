<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_dispensing_status".
 *
 * @property int $dispensing_status_id
 * @property string $dispensing_status_des
 */
class TbDispensingStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_dispensing_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dispensing_status_id'], 'required'],
            [['dispensing_status_id'], 'integer'],
            [['dispensing_status_des'], 'string', 'max' => 50],
            [['dispensing_status_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dispensing_status_id' => 'Dispensing Status ID',
            'dispensing_status_des' => 'Dispensing Status Des',
        ];
    }
}

<?php

namespace frontend\modules\app\models\mobile;

use Yii;

/**
 * This is the model class for table "tb_calling_config".
 *
 * @property int $calling_id
 * @property int $notice_queue จำนวนคิวที่แจ้งเตือน
 */
class TbCallingConfig extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_calling_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notice_queue'], 'required'],
            [['calling_id', 'notice_queue'], 'integer'],
            [['notice_queue_status'], 'string', 'max' => 2],
            [['calling_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'calling_id' => 'Calling ID',
            'notice_queue' => 'จำนวนคิวที่แจ้งเตือน',
            'notice_queue_status' => 'สถานะ',
        ];
    }
}

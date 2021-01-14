<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_cid_station".
 *
 * @property int $id
 * @property string $name ชื่อ
 * @property int $status สถานะ
 */
class TbCidStation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_cid_station';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'ชื่อ'),
            'status' => Yii::t('app', 'สถานะ'),
        ];
    }
}

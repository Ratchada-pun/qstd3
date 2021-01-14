<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "lab_items".
 *
 * @property int $lab_items_code
 * @property string $lab_items_name
 * @property string $confirm
 */
class LabItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lab_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lab_items_code'], 'required'],
            [['lab_items_code'], 'integer'],
            [['lab_items_name'], 'string', 'max' => 255],
            [['confirm'], 'string', 'max' => 10],
            [['lab_items_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lab_items_code' => Yii::t('app', 'Lab Items Code'),
            'lab_items_name' => Yii::t('app', 'Lab Items Name'),
            'confirm' => Yii::t('app', 'Confirm'),
        ];
    }
}

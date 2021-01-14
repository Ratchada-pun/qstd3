<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_counterservice_type".
 *
 * @property int $counterservice_typeid
 * @property string $counterservice_type
 * @property int $sound_id
 *
 * @property TbCounterservice[] $tbCounterservices
 */
class TbCounterserviceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_counterservice_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counterservice_type'], 'required'],
            [['sound_id'], 'integer'],
            [['counterservice_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'counterservice_typeid' => 'Counterservice Typeid',
            'counterservice_type' => 'ชื่อประเภท',
            'sound_id' => 'ไฟล์เสียง',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbCounterservices()
    {
        return $this->hasMany(TbCounterservice::className(), ['counterservice_type' => 'counterservice_typeid']);
    }

    public function getTbSound()
    {
        return $this->hasOne(TbSound::className(), ['sound_id' => 'sound_id']);
    }

    /**
     * @inheritdoc
     * @return TbCounterserviceTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbCounterserviceTypeQuery(get_called_class());
    }
}

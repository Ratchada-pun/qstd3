<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_caller_data".
 *
 * @property int $ids
 * @property int $caller_ids running
 * @property int $q_ids
 * @property int $qtran_ids
 * @property int $servicegroupid
 * @property int $counter_service_id ชื่อช่องบริการ
 * @property string $call_timestp เวลาที่เรียก
 * @property int $created_by ผู้เรียก
 * @property string $created_at
 * @property int $updated_by
 * @property string $updated_at
 * @property string $call_status รอเรียก/เรียกแล้ว/Hold
 */
class TbCallerData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_caller_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['caller_ids', 'q_ids', 'qtran_ids', 'servicegroupid', 'counter_service_id', 'created_by', 'updated_by'], 'integer'],
            [['call_timestp', 'created_at', 'updated_at'], 'safe'],
            [['call_status'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ids' => Yii::t('app', 'Ids'),
            'caller_ids' => Yii::t('app', 'running'),
            'q_ids' => Yii::t('app', 'Q Ids'),
            'qtran_ids' => Yii::t('app', 'Qtran Ids'),
            'servicegroupid' => Yii::t('app', 'Servicegroupid'),
            'counter_service_id' => Yii::t('app', 'ชื่อช่องบริการ'),
            'call_timestp' => Yii::t('app', 'เวลาที่เรียก'),
            'created_by' => Yii::t('app', 'ผู้เรียก'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'call_status' => Yii::t('app', 'รอเรียก/เรียกแล้ว/Hold'),
        ];
    }
}

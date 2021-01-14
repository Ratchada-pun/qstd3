<?php

namespace frontend\modules\kiosk\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;
/**
 * This is the model class for table "tb_quequ".
 *
 * @property int $q_ids running
 * @property string $q_num หมายเลขQ
 * @property string $q_timestp วันที่ออกQ
 * @property int $q_status_id สถานะQ
 * @property int $pt_id
 * @property string $q_vn Visit number ของผู้ป่วย
 * @property string $q_hn หมายเลข HN ผู้ป่วย
 * @property string $pt_name ชื่อผู้ป่วย
 * @property int $pt_visit_type_id ประเภท
 * @property int $pt_appoint_sec_id แผนกที่นัดหมาย
 * @property int $doctor_id
 * @property string $created_at วันที่บันทึก
 * @property string $updated_at วันที่แก้ไข
 */
class TbQuequ extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_quequ';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at','q_timestp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression('NOW()'),
            ],
            /*[
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['q_num'],
                ],
                'value' => function ($event) {
                    $maxid = $this->find()->where(['<>', 'q_ids', $this->q_ids])->max('q_ids');
                    $number = null;
                    if($maxid){
                        $model = $this->findOne($maxid);
                        $number = $model['q_num'];
                    }
                    $component = \Yii::createObject([
                        'class' => \common\components\AutoNumber::className(),
                        'prefix' => $this->ptVisitType->pt_visit_type_prefix ? $this->ptVisitType->pt_visit_type_prefix : 'A',
                        'number' => $number,
                        'digit' => $this->ptVisitType->pt_visit_type_digit ? $this->ptVisitType->pt_visit_type_digit : 3,
                    ]);
                    return $component->generate();
                },
            ],*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q_timestp', 'created_at', 'updated_at'], 'safe'],
            [['q_status_id', 'pt_id', 'pt_visit_type_id', 'pt_appoint_sec_id', 'doctor_id'], 'integer'],
            [['q_num', 'q_vn', 'q_hn'], 'string', 'max' => 20],
            [['pt_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'q_ids' => Yii::t('app', 'running'),
            'q_num' => Yii::t('app', 'หมายเลขQ'),
            'q_timestp' => Yii::t('app', 'วันที่ออกQ'),
            'q_status_id' => Yii::t('app', 'สถานะQ'),
            'pt_id' => Yii::t('app', 'Pt ID'),
            'q_vn' => Yii::t('app', 'Visit number ของผู้ป่วย'),
            'q_hn' => Yii::t('app', 'หมายเลข HN ผู้ป่วย'),
            'pt_name' => Yii::t('app', 'ชื่อผู้ป่วย'),
            'pt_visit_type_id' => Yii::t('app', 'ประเภท'),
            'pt_appoint_sec_id' => Yii::t('app', 'แผนกที่นัดหมาย'),
            'doctor_id' => Yii::t('app', 'Doctor ID'),
            'created_at' => Yii::t('app', 'วันที่บันทึก'),
            'updated_at' => Yii::t('app', 'วันที่แก้ไข'),
        ];
    }

    public function getPtVisitType()
    {
        return $this->hasOne(TbPtVisitType::className(), ['pt_visit_type_id' => 'pt_visit_type_id']);
    }

    public function getSection()
    {
        return $this->hasOne(TbSection::className(), ['sec_id' => 'pt_appoint_sec_id']);
    }

    public function runQnum ($data) {
        $maxid = $this->find()->where(['pt_visit_type_id' => $data['pt_visit_type_id']])->max('q_ids');
        $number = null;
        if($maxid){
            $model = $this->findOne($maxid);
            $number = $model['q_num'];
        }
        $visitType = TbPtVisitType::findOne($data['pt_visit_type_id']);
        $component = \Yii::createObject([
            'class' => \common\components\AutoNumber::className(),
            'prefix' => $visitType ? $visitType->pt_visit_type_prefix : 'A',
            'number' => $number,
            'digit' => $visitType ? $visitType->pt_visit_type_digit : 3,
        ]);
        return $component->generate();
    }
}

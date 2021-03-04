<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "tb_doctor".
 *
 * @property int $doc_id
 * @property string $doctor_code
 * @property string $doctor_name
 * @property string $photo
 * @property string $doctor_medid
 * @property string $type_name
 * @property string $spec_name
 * @property int $dept_id
 * @property string $dept_code
 * @property string $dept_name
 * @property int $Status
 * @property string $WorkDay1
 * @property string $WorkDay2
 * @property string $WorkDay3
 * @property string $WorkDay4
 * @property string $WorkDay5
 * @property string $WorkDay6
 * @property string $WorkDay7
 */
class TbDoctor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_doctor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dept_id', 'Status'], 'integer'],
            [['doctor_code', 'doctor_medid'], 'string', 'max' => 7],
            [['doctor_name', 'type_name', 'dept_code', 'dept_name'], 'string', 'max' => 100],
            [['photo', 'spec_name'], 'string', 'max' => 255],
            [['WorkDay1', 'WorkDay2', 'WorkDay3', 'WorkDay4', 'WorkDay5', 'WorkDay6', 'WorkDay7'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doc_id' => 'Doc ID',
            'doctor_code' => 'Doctor Code',
            'doctor_name' => 'Doctor Name',
            'photo' => 'Photo',
            'doctor_medid' => 'Doctor Medid',
            'type_name' => 'Type Name',
            'spec_name' => 'Spec Name',
            'dept_id' => 'Dept ID',
            'dept_code' => 'Dept Code',
            'dept_name' => 'Dept Name',
            'Status' => 'Status',
            'WorkDay1' => 'Work Day1',
            'WorkDay2' => 'Work Day2',
            'WorkDay3' => 'Work Day3',
            'WorkDay4' => 'Work Day4',
            'WorkDay5' => 'Work Day5',
            'WorkDay6' => 'Work Day6',
            'WorkDay7' => 'Work Day7',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TbDoctorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbDoctorQuery(get_called_class());
    }
}

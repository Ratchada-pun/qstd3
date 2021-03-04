<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "SSWQueues".
 *
 * @property int $index
 * @property string $VN
 * @property string $Fullname
 * @property string $HN
 * @property string $doctor
 * @property string $Lab
 * @property string $Xray
 * @property string $SP
 * @property string $PrintTime
 * @property string $ArrivedTime
 * @property string $PrintBillTime
 * @property string $Time1
 * @property string $Time2
 * @property string $ArrivedTime2
 * @property string $WTime
 * @property string $AppTime
 */
class QueuesInterface extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Table_QueuesInterface';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['index'], 'required'],
            [['index'], 'integer'],
            [['Fullname', 'HN', 'doctor', 'lab', 'Xray', 'SP', 'PrintTime', 'ArrivedTime', 'PrintBillTime', 'Time1', 'Time2','UpdateDate','UpdateTime', 'ArrivedTimeC', 'WTime', 'AppTime'], 'string'],
            [['VN'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ลำดับ',
            'VN' => 'VN',
            'Fullname' => 'ชื่อผู้ป่วย',
            'HN' => 'HN',
            'doctor' => 'ชื่อแพทย์',
            'lab' => 'ผลLAB',
            'xray' => 'ผลXray',
            'SP' => 'ผล SP',
            'PrintTime' => 'Print OPD',
            'ArrivedTime' => 'Arrived Time',
            'PrintBillTime' => 'Print Bill Time',
            'Time1' => 'Time1',
            'Time2' => 'Time2',
            'UpdateDate' => 'UpdateDate',
            'UpdateTime' => 'UpdateTime',
            'ArrivedTimeC' => 'เวลาแพทย์กด Arrived',
            'WTime' => 'W Time',
            'AppTime' => 'เวลาที่นัดมา',
        ];
    }
}

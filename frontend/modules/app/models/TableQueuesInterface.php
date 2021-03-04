<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "Table_QueuesInterface".
 *
 * @property int $ID
 * @property string $HN
 * @property string $VN
 * @property string $Fullname
 * @property string $doctor
 * @property string $lab
 * @property string $xray
 * @property string $SP
 * @property string $PrintTime
 * @property string $ArrivedTime
 * @property string $PrintBillTime
 * @property string $Time1
 * @property string $Time2
 * @property string $UpdateDate
 * @property string $UpdateTime
 * @property string $ArrivedTimeC
 * @property string $WTime
 * @property string $AppTime
 */
class TableQueuesInterface extends \yii\db\ActiveRecord
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
            [['ID'], 'required'],
            [['ID'], 'integer'],
            [['HN', 'VN', 'Fullname', 'doctor', 'lab', 'xray', 'SP', 'PrintTime', 'ArrivedTime', 'PrintBillTime', 'Time1', 'Time2', 'ArrivedTimeC', 'WTime', 'AppTime'], 'string'],
            [['UpdateDate'], 'string', 'max' => 10],
            [['UpdateTime'], 'string', 'max' => 5],
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
            'HN' => 'Hn',
            'VN' => 'Vn',
            'Fullname' => 'Fullname',
            'doctor' => 'Doctor',
            'lab' => 'Lab',
            'xray' => 'Xray',
            'SP' => 'Sp',
            'PrintTime' => 'Print Time',
            'ArrivedTime' => 'Arrived Time',
            'PrintBillTime' => 'Print Bill Time',
            'Time1' => 'Time1',
            'Time2' => 'Time2',
            'UpdateDate' => 'Update Date',
            'UpdateTime' => 'Update Time',
            'ArrivedTimeC' => 'Arrived Time C',
            'WTime' => 'W Time',
            'AppTime' => 'App Time',
        ];
    }
}

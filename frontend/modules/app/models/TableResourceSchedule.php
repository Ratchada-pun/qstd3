<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "Table_ResourceSchedule".
 *
 * @property int $ID
 * @property string $Date
 * @property string $STime
 * @property string $ETime
 * @property string $DRCode
 * @property string $DRName
 * @property string $Dayyy
 * @property string $Loccode
 * @property string $UpdateDate
 * @property string $UpdateTime
 * @property string $ResourceText
 */
class TableResourceSchedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Table_ResourceSchedule';
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
            [['Date', 'STime', 'ETime', 'DRCode', 'DRName', 'Dayyy', 'Loccode', 'ResourceText'], 'string'],
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
            'Date' => 'Date',
            'STime' => 'S Time',
            'ETime' => 'E Time',
            'DRCode' => 'Dr Code',
            'DRName' => 'Dr Name',
            'Dayyy' => 'Dayyy',
            'Loccode' => 'Loccode',
            'UpdateDate' => 'Update Date',
            'UpdateTime' => 'Update Time',
            'ResourceText' => 'Resource Text',
        ];
    }
}

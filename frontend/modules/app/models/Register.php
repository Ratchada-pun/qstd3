<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "Table_Register".
 *
 * @property string $VN
 * @property string $HN
 * @property string $FullName
 * @property string $TEL
 * @property string $CareProvNo
 * @property string $CareProv
 * @property string $ServiceID
 * @property string $Time
 * @property string $AppTime
 * @property string $loccode
 * @property string $locdesc
 * @property string $UpdateDate
 * @property string $UpdateTime
 */
class Register extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Table_Register';
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
            [['VN'], 'required'],
            [['HN', 'FullName', 'TEL', 'CareProvNo', 'CareProv', 'ServiceID', 'Time', 'AppTime', 'loccode', 'locdesc'], 'string'],
            [['VN', 'UpdateDate'], 'string', 'max' => 10],
            [['UpdateTime'], 'string', 'max' => 5],
            [['VN'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'VN' => 'Vn',
            'HN' => 'Hn',
            'FullName' => 'Full Name',
            'TEL' => 'Tel',
            'CareProvNo' => 'Care Prov No',
            'CareProv' => 'Care Prov',
            'ServiceID' => 'Service ID',
            'Time' => 'Time',
            'AppTime' => 'App Time',
            'loccode' => 'Loccode',
            'locdesc' => 'Locdesc',
            'UpdateDate' => 'Update Date',
            'UpdateTime' => 'Update Time',
        ];
    }
}

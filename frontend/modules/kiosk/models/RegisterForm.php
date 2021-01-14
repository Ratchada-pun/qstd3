<?php
namespace frontend\modules\kiosk\models;

use Yii;

class RegisterForm extends \yii\base\Model
{
    public $section;
    public $barcode;

    public function rules()
    {
        return [
            [['section','barcode'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'section' => Yii::t('app', 'Section'),
            'barcode' => Yii::t('app', 'Barcode'),
        ];
    }
}
<?php

namespace frontend\modules\app\models;


use yii\base\Model;

class Pharmacy extends Model
{
    public $pharmacy_drug_name;
    public $pharmacy_drug_address;

    public function attributeLabels()
    {
        return [
            'pharmacy_drug_name' => 'ชื่อร้าน',
            'pharmacy_drug_address' => 'ที่อยู่',
        ];
    }

    public function rules()
    {
        return [
            [['pharmacy_drug_name', 'pharmacy_drug_address'], 'required']
        ];
    }
}

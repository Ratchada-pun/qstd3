<?php

namespace frontend\modules\app\models;


use yii\base\Model;

class Personal extends Model
{
    public $hn;
    public $pt_name;

    public function attributeLabels()
    {
        return [
            'hn' => 'HN',
            'pt_name' => 'ชื่อผู้รับบริการ',
        ];
    }

    public function rules()
    {
        return [
            [['hn'], 'required']
        ];
    }
}

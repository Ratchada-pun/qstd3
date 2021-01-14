<?php
namespace frontend\modules\kiosk\models;

use Yii;

class SearchForm extends \yii\base\Model
{
    public $hn;

    public function rules()
    {
        return [
            [['hn'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'hn' => Yii::t('app', 'HN หรือ เลขที่บัตร ปชช.'),
        ];
    }
}
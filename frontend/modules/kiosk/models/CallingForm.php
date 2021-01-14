<?php
namespace frontend\modules\kiosk\models;

use Yii;
use yii\helpers\ArrayHelper;

class CallingForm extends \yii\base\Model
{
    public $section;
    public $counter;
    public $qnum;

    public function rules()
    {
        return [
            [['section','counter'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'section' => \Yii::t('app', 'Section'),
            'counter' => \Yii::t('app', 'Counter'),
            'qnum' => \Yii::t('app', 'Qnum'),
        ];
    }

    public function getDataCounterservice()
    {
        return ArrayHelper::map(TbCounterservice::find()
        ->where(['sec_id' => $this->section,'counterservice_type' => 1])
        ->asArray()
        ->all(),
        'counterserviceid','counterservice_name'
        );
    }

    public function getCounterservice()
    {
        $model = TbCounterservice::findOne($this->counter);
        if($model){
            return $model['counterservice_type'];
        }
        return false;
    }

    public function getDataCounterserviceEx()
    {
        return ArrayHelper::map(TbCounterservice::find()
        ->where(['sec_id' => $this->section, 'counterservice_type' => 2])
        ->asArray()
        ->all(),
        'counterserviceid','counterservice_name'
        );
    }

    public function getDataCounterBd()
    {
        return ArrayHelper::map(TbCounterservice::find()
        ->where(['sec_id' => $this->section])
        ->asArray()
        ->all(),
        'counterserviceid','counterservice_name'
        );
    }
}
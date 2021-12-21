<?php

namespace frontend\modules\app\models;

use Yii;
use yii\icons\Icon;
use yii\helpers\ArrayHelper;

class CallingForm extends \yii\base\Model
{
    public $service_profile;

    public $counter_service;

    public $qnum;

    public function rules()
    {
        return [
            [['service_profile', 'counter_service'], 'required'],
        ];
    }

    public function getDataProfile()
    {
        return ArrayHelper::map(TbServiceProfile::find()->where(['service_profile_status' => 1])->asArray()->all(), 'service_profile_id', 'service_name');
    }

    public function getDataCounter()
    {
        if (!empty($this->service_profile)) {
            $model = TbServiceProfile::findOne($this->service_profile);
            return ArrayHelper::map(TbCounterservice::find()->where([
                'counterservice_type' => $model['counterservice_typeid'],
                'counterservice_status' => 1,
                'counterserviceid' => $model['counterserviceid'],
            ])->orderBy(['service_order' => SORT_ASC])->asArray()->all(), 'counterserviceid', 'counterservice_name');
        } else {
            return [];
        }
    }

    public function getServiceList()
    {
        $badge = [];
        if (!empty($this->service_profile)) {
            $model = TbServiceProfile::findOne($this->service_profile);
            $counters = explode(",", $model->service_id);
            $services = TbService::find()->where(['serviceid' => $counters, 'service_status' => 1])->all();
            foreach ($services as $key => $value) {
                $badge[] = \kartik\helpers\Html::badge(Icon::show('check') . ' (' . $value['service_prefix'] . ') ' . $value['service_name'], ['class' => 'badge badge-success']);
            }
        }
        return implode("\n", $badge);
    }

    public function getDataCounterserviceEx($profile)
    {
        return ArrayHelper::map(
            TbCounterservice::find()
                ->where(['counterservice_type' => $profile['counterservice_typeid'], 'counterservice_status' => 1])
                ->asArray()
                ->orderBy(['service_order' => SORT_ASC])
                ->all(),
            'counterserviceid',
            'counterservice_name'
        );
    }
}

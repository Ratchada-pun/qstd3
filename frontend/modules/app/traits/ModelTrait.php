<?php

namespace frontend\modules\app\traits;

use yii\web\NotFoundHttpException;
use frontend\modules\app\models\TbServiceProfile;
use frontend\modules\app\models\TbServicegroup;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbQuequ;
use frontend\modules\app\models\TbQtrans;
use frontend\modules\app\models\TbTicket;
use frontend\modules\app\models\TbCounterservice;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbDisplayConfig;
use frontend\modules\app\models\TbCounterserviceType;
use frontend\modules\app\models\LabItems;
use frontend\modules\app\models\TbCidStation;

trait ModelTrait
{
    protected function findModelServiceProfile($id)
    {
        if (($model = TbServiceProfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelServiceGroup($id)
    {
        if (($model = TbServicegroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelService($id)
    {
        if (($model = TbService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelQTrans($id)
    {
        if (($model = TbQtrans::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelQuequ($id)
    {
        if (($model = TbQuequ::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelTicket($id)
    {
        if (($model = TbTicket::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelCounterservice($id)
    {
        if (($model = TbCounterservice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelCaller($id)
    {
        if (($model = TbCaller::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelDisplayConfig($id)
    {
        if (($model = TbDisplayConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelCounterserviceType($id)
    {
        if (($model = TbCounterserviceType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findLabs(){
        return LabItems::find()->where(['confirm' => 'Y'])->all();
    }

    protected function findModelKiosk($id)//ตู้ Kiosk
    {
        if (($model = TbCidStation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist. {'. TbCidStation::className().'}');
    }
}
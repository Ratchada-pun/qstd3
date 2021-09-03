<?php
namespace common\traits;


use yii\web\NotFoundHttpException;
use xray\modules\api\modules\v1\models\TbService;
use xray\modules\api\modules\v1\models\TbQueue;
use xray\modules\api\modules\v1\models\TbTicket;

trait ModelTrait {
  protected function findModelQueue($id)
  {
    if (($model = TbQueue::findOne($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException('queue id "' . $id . '" not found.');
    }
  }

  protected function findModelService($id)
  {
    if (($model = TbService::findOne($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException('service id "' . $id . '" not found.');
    }
  }

  protected function findModelTicket($id)
  {
    if (($model = TbTicket::findOne($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException('ticket id "' . $id . '" not found.');
    }
  }
}
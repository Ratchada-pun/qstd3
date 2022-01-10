<?php

namespace xray\modules\api\modules\v1\controllers;

use frontend\modules\app\models\TbQtrans;
use frontend\modules\app\models\TbServiceStatus;
use frontend\modules\app\models\TbTokenNhso;
use xray\modules\api\modules\v1\models\TbQueue;
use xray\modules\api\modules\v1\models\TbService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class KioskController extends ActiveController
{
  public $modelClass = '';

  public function behaviors()
  {
    $behaviors = parent::behaviors();

    $behaviors['authenticator'] = [
      'class' => CompositeAuth::className(),
      'authMethods' => [],
    ];

    $behaviors['verbs'] = [
      'class' => \yii\filters\VerbFilter::className(),
      'actions' => [
        'client-ip' => ['GET'],
        'services' => ['GET'],
        'create-queue' => ['POST'],
      ],
    ];

    // remove authentication filter
    $auth = $behaviors['authenticator'];
    unset($behaviors['authenticator']);

    // add CORS filter
    $behaviors['corsFilter'] = [
      'class' => \yii\filters\Cors::className(),
      'cors' => [
        'Origin' => ['*'],
        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'Access-Control-Request-Headers' => ['*'],
      ],
    ];

    // re-add authentication filter
    $behaviors['authenticator'] = $auth;
    // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
    $behaviors['authenticator']['except'] = ['options', 'login'];

    // setup access
    $behaviors['access'] = [
      'class' => AccessControl::className(),
      'only' => ['index', 'view', 'create', 'update', 'delete'], //only be applied to
      'rules' => [
        [
          'allow' => true,
          'actions' => ['index', 'view', 'create', 'update', 'delete'],
          'roles' => ['admin'],
        ],
      ],
    ];

    return $behaviors;
  }

  public function actionClientIp()
  {
    return $this->getUserIP();
  }

  private function getUserIP()
  {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return current(array_values(array_filter(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
  }

  public function actionServices()
  {
    return TbService::find()->where(['show_on_kiosk' => 1])->asArray()->all();
  }

  public function actionCreateQueue()
  {
    $request = Yii::$app->request;
    $params = $request->bodyParams;

    $transaction = TbQueue::getDb()->beginTransaction();
    try {
      if (!ArrayHelper::getValue($params, 'service_id', null)) {
        throw new HttpException(400, 'invalid service_id.');
      }

      $cid = ArrayHelper::getValue($params, 'cid', null);
      $picture = ArrayHelper::getValue($params, 'picture', null);

      $service = $this->findModelService($params['service_id']);

      $counttslot = (new \yii\db\Query()) //หา slot เวลาที่ต้องสร้างคิว
        ->select([
          'tb_service_tslot.*',
        ])
        ->from('tb_service_tslot')
        ->where(['tb_service_tslot.serviceid' => $service['serviceid']])
        ->count();

      $tslotid = $this->getSlot($service['serviceid']);

      $slot = (new \yii\db\Query()) //หา slot เวลาที่ต้องสร้างคิว
        ->select([
          'tb_service_tslot.*',
        ])
        ->from('tb_service_tslot')
        ->where(['tb_service_tslot.serviceid' => $service['serviceid']])
        ->andWhere('CURRENT_TIME >= tb_service_tslot.t_slot_begin')
        ->andWhere('CURRENT_TIME <= tb_service_tslot.t_slot_end')->one();

      if ($slot) {
        $count = (new \yii\db\Query())
          ->from('tb_quequ')
          ->where([
            'tb_quequ.serviceid' => $service['serviceid'],
            'tb_quequ.tslotid' => $slot['tslotid'],
          ])
          ->andWhere('DATE( tb_quequ.q_timestp ) = CURRENT_DATE')
          ->count();
        $q_balance = $slot['q_limitqty'] - $count;
        if ($q_balance == 0 && $count > 0) { //จำนวน คิว limit
          throw new HttpException(400, 'คิวเต็ม!');
        }
      }

      if ($counttslot > 0 && $tslotid == null) {
        throw new HttpException(400, 'ไม่ได้อยู่ในช่วงเวลาให้บริการ!');
      }

      $model = new TbQueue();
      if ($cid) {
        $oldModel = TbQueue::find()->where([
          'cid' => $cid,
          'serviceid' => $service['serviceid'],
        ])
          ->andWhere('DATE( q_timestp ) = :q_timestp', [':q_timestp' =>  Yii::$app->formatter->asDate('now', 'php:Y-m-d')])
          ->andWhere('q_status_id <> :q_status_id', [':q_status_id' => 4])
          ->one();
        if ($oldModel) {
          $model = $oldModel;
        }
      }

      $queue_no = $model->generateQueueNumber([
        'serviceid' => $service['serviceid'],
        'service_prefix' => $service['service_prefix'],
        'service_numdigit' => $service['service_numdigit'],
      ]);

      $right = ArrayHelper::getValue($params, 'right', null);

      $count = (new \yii\db\Query())
        ->select(['tb_quequ.q_ids'])
        ->from('tb_quequ')
        ->where([
          'tb_quequ.serviceid' => $service['serviceid'],
          'tb_quequ.q_status_id' => 1
        ])
        ->andWhere('DATE( tb_quequ.q_timestp ) = CURRENT_DATE')
        ->andWhere('tb_quequ.q_timestp < :q_timestp', [':q_timestp' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s')])
        ->count();

      $model->setAttributes([
        'q_num' => $queue_no,
        'cid' => ArrayHelper::getValue($params, 'cid', null),
        'pt_name' => ArrayHelper::getValue($params, 'patient_name', null),
        'age' => ArrayHelper::getValue($params, 'age', null),
        'maininscl_name' => ArrayHelper::getValue($params, 'maininscl_name', null),
        'servicegroupid' => $service['service_groupid'],
        'serviceid' => $service['serviceid'],
        'q_status_id' => 1,
        'tslotid' => $tslotid,
        'purchaseprovince_name' => ArrayHelper::getValue($right, 'purchaseprovince_name', null),
        'hsub_name' => ArrayHelper::getValue($right, 'hsub_name', null),
        'hmain_name' => ArrayHelper::getValue($right, 'hmain_name', null),
        'paid_model' => ArrayHelper::getValue($right, 'paid_model', null),
        'hmain_op_name' => ArrayHelper::getValue($right, 'hmain_op_name', null),
        'wating_time' => $count != 0 && !empty($service['average_time']) ? number_format($count * $service['average_time']) : 0,
        'locale' => ArrayHelper::getValue($params, 'locale', Yii::$app->language),
      ]);
      if (!empty($picture) && !empty($cid)) {
        $pt_pic = $this->uploadPicture($picture, Yii::$app->security->generateRandomString());
        $model->pt_pic = $pt_pic;
      }
      if ($model->save()) {
        $modelQstatus = TbServiceStatus::findOne($model['q_status_id']);
        $modelQtrans = TbQtrans::findOne(['q_ids' => $model->q_ids]);
        $queue_left = (new \yii\db\Query()) //คิวรอ
          ->select([
            'count(`tb_quequ`.`q_ids`) as `queue_left`',
          ])
          ->from('`tb_quequ`')
          ->where([
            '`tb_quequ`.`serviceid`' => $service['serviceid'],
          ])
          ->where('q_status_id <> :q_status_id', [':q_status_id' => 4])
          ->andWhere('q_ids < :q_ids', [':q_ids' => $model['q_ids']])
          ->andWhere('DATE(q_timestp) = CURRENT_DATE')
          ->count();
        if (!$modelQtrans) {
          $modelQtrans = new TbQtrans();
        }
        $modelQtrans->setAttributes([
          'q_ids' => $model->q_ids,
          'servicegroupid' => $service['service_groupid'],
          'service_status_id' => $model->q_status_id,
        ]);
        if ($modelQtrans->save()) {
          $transaction->commit();
          return [
            'modelQueue' => $model,
            'modelQtrans' => $modelQtrans,
            'service_status_name' => $modelQstatus['service_status_name'],
            'queue_left' => $queue_left,
          ];
        } else {
          $transaction->rollBack();
          throw new HttpException(422, Json::encode($modelQtrans->errors));
        }
      } else {
        $transaction->rollBack();
        throw new HttpException(422, Json::encode($model->errors));
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
      throw $e;
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

  private function getSlot($serviceid, $tslotid = [])
  {
    $query = (new \yii\db\Query()) //หา slot เวลาที่ต้องสร้างคิว
      ->select([
        'tb_service_tslot.*',
      ])
      ->from('tb_service_tslot')
      ->where(['tb_service_tslot.serviceid' => $serviceid]);
    if ($tslotid) {
      $query->andWhere(['NOT IN', 'tb_service_tslot.tslotid', $tslotid])
        ->andwhere('tb_service_tslot.t_slot_begin >= CURRENT_TIME');
    } else {
      $query->andWhere('CURRENT_TIME >= tb_service_tslot.t_slot_begin')
        ->andWhere('CURRENT_TIME <= tb_service_tslot.t_slot_end');
    }
    $slot = $query->one();

    if ($slot) {
      if ($slot['q_limit'] == 1) { //มีจำนวน limit
        $count = (new \yii\db\Query())
          ->from('tb_quequ')
          ->where([
            'tb_quequ.serviceid' => $serviceid,
            'tb_quequ.tslotid' => $slot['tslotid'],
          ])
          ->andWhere('DATE(q_timestp) = CURRENT_DATE')
          ->count();
        $q_balance = $slot['q_limitqty'] - $count;
        if ($q_balance == 0) { //จำนวน คิว limit
          $tslotid = ArrayHelper::merge($tslotid, [$slot['tslotid']]);
          return $this->getSlot($serviceid, $tslotid);
        } else {
          return $slot['tslotid'];
        }
      } else {
        return $slot['tslotid'];
      }
    }
    return null;
  }

  // ขอข้อมูลสิทธิผู้ป่วยจาก สปสช
  public function actionPtRight($cid = null)
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (empty($cid) || strlen($cid) < 13) {
      return $this->apiValidate('รหัสบัตรประชาชนไม่ถูกต้อง.');
    }

    $client = Yii::$app->soapClient;
    $userToken = TbTokenNhso::find()->orderBy('crearedat DESC')->limit(1)->one();
    if (!$userToken) {
      return $this->apiValidate('กรุณาตั้งค่า Token.');
    }
    $params = [
      'user_person_id' => $userToken['user_person_id'],
      'smctoken' => $userToken['smctoken'],
      'person_id' => $cid,
    ];
    $res = $client->searchCurrentByPID($params);
    $res = (array) $res;
    $data = (array) $res['return'];

    $this->verifyPtRight($data);

    return $data;
  }

  // ตรวจสอบข้อมูลสิทธิ
  private function verifyPtRight($data)
  {
    if (!$data) {
      return $this->apiBadRequest('RESPONSE FAILED');
    } else if ($data['ws_status'] == 'NHSO-00003') {
      return $this->apiValidate(isset($data['ws_status_desc']) ? $data['ws_status_desc'] : 'TOKEN EXPIRE');
    } else if (empty($data['fname'])) {
      return $this->apiDataNotFound('NOT FOUND IN NHSO');
    } elseif (!isset($data['maininscl']) || !isset($data['maininscl_name'])) {
      return $this->apiDataNotFound('ไม่พบข้อมูลสิทธิการรักษา');
    }
  }

  public function apiBadRequest($message = false)
  {
    throw new HttpException(400, $message ? $message : 'Error Bad request.');
  }

  public function apiDataNotFound($message = false)
  {
    throw new HttpException(404, $message ? $message : 'Resource not found.');
  }

  public function apiValidate($message = false)
  {
    throw new HttpException(422, $message ? $message : 'Error validation.');
  }

  private function uploadPicture($picture, $hn) //upload ภาพ

  {
    $img = str_replace('data:image/png;base64,', '', $picture);
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $imageDecode = base64_decode($img);
    $hn = preg_replace('/\s/', '', $hn); // 766809
    $filename = implode('.', [ // 766809.jpg
      $hn,
      'jpg',
    ]);
    // // example 766809 เติม 0 ให้ hn ครบ 7 หลัก
    // $hn = sprintf("%'.07d\n", $hn);

    // $rootDir = substr($hn, 0, 3); // เลข hn 3 ตัวแรก
    // $subDir1 = substr($hn, 3, -2); // เลข hn 3 ตัวถัดมา
    // $subDir2 = substr($hn, 6, -1); // เลข hn ตัวสุดท้าย
    // $driUpload = $rootDir . '/' . $subDir1 . '/' . $subDir2; // จะได้ที่อยู่รูปภาพ จาก hn ตัวอย่าง จะได้ /076/680/9

    $rootImageDir = Yii::getAlias('@frontend/web/uploads');
    // $path1 = $rootImageDir . '/' . $rootDir; // frontend/web/source/opd/076
    // $path2 = $rootImageDir . '/' . $rootDir . '/' . $subDir1; // frontend/web/source/opd/076/680
    // $path3 = $rootImageDir . '/' . $rootDir . '/' . $subDir1 . '/' . $subDir2; // frontend/web/source/opd/076/680/9

    if (!is_dir($rootImageDir)) {
      FileHelper::createDirectory($rootImageDir, 0777);
    }
    // if (is_dir($rootImageDir) && !is_dir($path1)) {
    //   FileHelper::createDirectory($path1, 0777);
    // }
    // if (is_dir($path1) && !is_dir($path2)) {
    //   FileHelper::createDirectory($path2, 0777);
    // }
    // if (is_dir($path2) && !is_dir($path3)) {
    //   FileHelper::createDirectory($path3, 0777);
    //   $isCreateDir = true;
    // }
    if (is_dir($rootImageDir)) {
      $filepath = $rootImageDir . '/' . $filename; // dir = /076/680/9/766809.jpg
      $f = file_put_contents($filepath, $imageDecode);
      if ($f && file_exists($filepath)) {
        return Url::base(true) . Url::to(['/uploads/'  . $filename]);
      } else {
        return null;
      }
    }
  }
}

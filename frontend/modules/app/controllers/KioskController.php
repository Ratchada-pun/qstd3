<?php

namespace frontend\modules\app\controllers;

use common\models\FileStorageItem;
use frontend\modules\app\components\HisQuery;
use frontend\modules\app\models\mobile\TbQuequ;
use frontend\modules\app\models\Patient;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbDrugDispensing;
use frontend\modules\app\models\TbKiosk;
use frontend\modules\app\models\TbQtrans;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbServicegroup;
use frontend\modules\app\models\TbTokenNhso;
use frontend\modules\app\traits\ModelTrait;
use frontend\modules\kiosk\models\TbServiceStatus;
use homer\widgets\tbcolumn\ActionTable;
use homer\widgets\tbcolumn\ColumnData;
use kartik\widgets\ActiveForm;
use Yii;
#models
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class KioskController extends \yii\web\Controller
{
  use ModelTrait;

  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
          ],
          [
            'allow' => true,
            'actions' => ['public-ticket', 'register', 'print-ticket', 'button-list', 'kiosk-ticket'],
            'roles' => ['@', '?'],
          ],
          [
            'allow' => true,
            'actions' => ['led-options', 'pt-right', 'create-queue', 'scan-queue-mobile-qn', 'scan-queue-mobile-hn', 'queue-list'],
            'roles' => ['?'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [],
      ],
      'corsFilter' => [
        'class' => \yii\filters\Cors::className(),
      ],
    ];
  }

  /**
   * @inheritdoc
   */
  public function beforeAction($action)
  {
    if (in_array($action->id, [
      'create-queue',
      'scan-queue-mobile-qn',
      'scan-queue-mobile-hn',
      'get-queue-list',
    ])) {
      $this->enableCsrfValidation = false;
    }

    return parent::beforeAction($action);
  }

  public function actionIndex()
  {
    $this->layout = '@app/views/layouts/main-kiosk.php';
    $serviceGroup = TbServicegroup::find()->orderBy(['servicegroup_order' => SORT_ASC])->all();
    $services = TbService::find()->where(['show_on_kiosk' => 1, 'service_status' => 1])->all();
    return $this->render('index', [
      'service' => $serviceGroup,
      'services' => $services
    ]);
  }

  public function actionCreateTicket($groupid, $serviceid)
  {
    $request = Yii::$app->request;
    $session = Yii::$app->session;
    $modelServiceGroup = $this->findModelServiceGroup($groupid);
    $modelService = $this->findModelService($serviceid);
    $model = new TbQuequ();
    if ($session->has('cid-station')) {
      $model->cid_station = $session->get('cid-station');
    }
    if ($request->isAjax) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      if ($request->isGet) {
        return [
          'title' => '?????????????????????????????? #' . $modelService['service_name'],
          'content' => $this->renderAjax('_form_ticket', [
            'modelServiceGroup' => $modelServiceGroup,
            'modelService' => $modelService,
            'model' => $model,
            'serviceData' => ['groupid' => $groupid, 'serviceid' => $serviceid],
          ]),
          'footer' => '',
        ];
      } elseif ($model->load($request->post())) {
        $postData = $request->post('TbQuequ', []);
        $qData = TbQuequ::find()->where('q_hn LIKE :query')->addParams([':query' => '%' . $postData['q_hn']])->all();
        if ($qData) {
          return "registed";
        } else {
          $his = \Yii::createObject([
            'class' => HisQuery::className(),
            'vstdate' => $postData['vstdate'],
            'hn' => $postData['q_hn'],
            'search_by' => $postData['search_by'],
          ]);
          $respon = $his->getData();
          if ($respon && TbQuequ::find()->where('q_hn LIKE :query')->addParams([':query' => '%' . $respon['hn']])->all()) {
            return "registed";
          }
          return $respon;
        }
      }
    } else {
      throw new MethodNotAllowedHttpException('method not allowed.');
    }
  }

  public function actionRegister()
  {
    $request = Yii::$app->request;
    if ($request->isAjax) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      if ($request->isPost) {
        $data = $request->post();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
          #Service Data
          $service = $this->findModelService($data['serviceid']);
          #Insert QData
          $modelQ = new TbQuequ();
          $modelQ->q_num = $modelQ->genQnum($service);
          $modelQ->q_vn = $data['vn'];
          $modelQ->q_hn = $data['hn'];
          $modelQ->pt_name = $data['fullname'];
          $modelQ->serviceid = $data['serviceid'];
          $modelQ->servicegroupid = $data['groupid'];
          $modelQ->doctor_id = $data['dx_doctor'];
          $modelQ->q_status_id = 1;
          $modelQ->quickly = isset($data['quickly']) ? $data['quickly'] : 0;

          if ($modelQ->save()) {
            #Insert QTrans
            $modelQTran = new TbQtrans();
            $modelQTran->q_ids = $modelQ['q_ids'];
            $modelQTran->service_status_id = 1;
            if (!$modelQTran->save()) {
              $transaction->rollBack();
              return ['status' => 'error', 'message' => 'error', 'validate' => ActiveForm::validate($modelQTran)];
            } else {
              $transaction->commit();
              return ['status' => '200', 'message' => 'success', 'modelQTran' => $modelQTran, 'modelQ' => $modelQ, 'url' => Url::to(['/app/kiosk/print-ticket', 'id' => $modelQ['q_ids']])];
            }
          } else {
            $transaction->commit();
            return ['status' => 'error', 'message' => 'error', 'validate' => ActiveForm::validate($modelQ)];
          }
        } catch (\Exception $e) {
          $transaction->rollBack();
          throw $e;
        } catch (\Throwable $e) {
          $transaction->rollBack();
          throw $e;
        }
      } elseif ($request->isGet) {
        $data = $request->get();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
          #Service Data
          $service = $this->findModelService($data['serviceid']);
          #Insert QData
          $modelQ = new TbQuequ();
          $modelQ->q_num = $modelQ->genQnum($service);
          $modelQ->q_vn = null;
          $modelQ->q_hn = null;
          $modelQ->pt_name = null;
          $modelQ->serviceid = $data['serviceid'];
          $modelQ->servicegroupid = $data['groupid'];
          $modelQ->doctor_id = null;
          $modelQ->q_status_id = 1;
          $modelQ->quickly = isset($data['quickly']) ? $data['quickly'] : 0;

          if ($modelQ->save()) {
            #Insert QTrans
            $modelQTran = new TbQtrans();
            $modelQTran->q_ids = $modelQ['q_ids'];
            $modelQTran->service_status_id = 1;
            if (!$modelQTran->save()) {
              $transaction->rollBack();
              return ['status' => 'error', 'message' => 'error', 'validate' => ActiveForm::validate($modelQTran)];
            } else {
              $transaction->commit();
              return ['status' => '200', 'message' => 'success', 'modelQTran' => $modelQTran, 'modelQ' => $modelQ, 'url' => Url::to(['/app/kiosk/print-ticket', 'id' => $modelQ['q_ids']])];
            }
          } else {
            $transaction->commit();
            return ['status' => 'error', 'message' => 'error', 'validate' => ActiveForm::validate($modelQ)];
          }
        } catch (\Exception $e) {
          $transaction->rollBack();
          throw $e;
        } catch (\Throwable $e) {
          $transaction->rollBack();
          throw $e;
        }
      }
    } else {
      throw new MethodNotAllowedHttpException('method not allowed.');
    }
  }

  public function actionDataQ()
  {
    $request = Yii::$app->request;

    if ($request->isAjax) {
      $query = (new \yii\db\Query())
        ->select(['tb_quequ.*', 'tb_service.*', 'tb_quequ.quickly as quickly1', 'tb_counterservice.counterservice_name', 'tb_service_status.service_status_name'])
        ->from('tb_quequ')
        ->innerJoin('tb_service', 'tb_quequ.serviceid = tb_service.serviceid')
        ->innerJoin('tb_qtrans', 'tb_quequ.q_ids = tb_qtrans.q_ids')
        ->innerJoin('tb_service_status', 'tb_quequ.q_status_id = tb_service_status.service_status_id')
        ->leftJoin('tb_caller', 'tb_qtrans.ids = tb_caller.qtran_ids')
        ->leftJoin('tb_counterservice', 'tb_caller.counter_service_id = tb_counterservice.counterserviceid')
        ->andWhere('DATE(tb_quequ.q_timestp) = CURRENT_DATE')
        ->orderBy('tb_quequ.q_ids DESC');

      $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => [
          'pageSize' => false,
        ],
        'key' => 'q_ids',
      ]);
      $columns = Yii::createObject([
        'class' => ColumnData::className(),
        'dataProvider' => $dataProvider,
        'formatter' => Yii::$app->getFormatter(),
        'columns' => [
          [
            'attribute' => 'q_num',
            'value' => function ($model, $key, $index, $column) {
              return \kartik\helpers\Html::badge($model['q_num'], ['class' => 'badge badge-success']);
            },
            'format' => 'raw',
          ],
          [
            'attribute' => 'q_hn',
          ],
          [
            'attribute' => 'pt_name',
          ],
          [
            'attribute' => 'counterservice_name',
          ],
          [
            'attribute' => 'service_status_name',
          ],
          [
            'attribute' => 'service_name',
            'value' => function ($model, $key, $index, $column) {
              return $model['quickly1'] == 1 ? $model['service_name'] . ' (?????????????????????!)' : $model['service_name'];
            },
          ],
          [
            'attribute' => 'status',
            'value' => function ($model, $key, $index, $column) {
              return $this->getStatus($key);
            },
          ],
          [
            'class' => ActionTable::className(),
            'template' => '{update} {print}',
            'updateOptions' => [
              'role' => 'modal-remote',
            ],
            'buttons' => [
              'update' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-edit"></i>' . ' ???????????????', Url::to(['/app/kiosk/update-queue', 'id' => $key]), [
                  'class' => 'btn btn-primary',
                  'role' => 'modal-remote',
                ]);
              },
              'print' => function ($url, $model, $key) {
                return Html::a('<i class="pe-7s-print"></i>' . ' ????????????????????????????????????', false, [
                  'onclick' => 'return window.open("' . Url::to(['/app/kiosk/print-ticket', 'id' => $key]) . '","myPrint", "width=800, height=600");',
                  'class' => 'btn btn-success',
                  'title' => 'Print',
                ]);
              },
            ],
            'visibleButtons' => [
              'update' => function ($model, $key, $index) {
                return $model['q_status_id'] != 4;
              },
            ],
          ],
        ],
      ]);

      return Json::encode(['data' => $columns->renderDataColumns()]);
    } else {
      throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
    }
  }

  private function getStatus($q_ids)
  {
    $modelQ = TbQuequ::findOne($q_ids);
    $modelQTran = TbQtrans::findOne(['q_ids' => $q_ids]);
    if ($modelQTran['service_status_id'] == 1) { //?????????????????????
      if ($modelQ['servicegroupid'] == 1) { //???????????????????????????
        return '?????????????????????????????? (????????????????????????????????????)';
      } else { //??????????????????????????????
        return '?????????????????????????????? (??????????????????????????????)';
      }
    } elseif ($modelQTran['service_status_id'] == 2) { //????????????????????????
      $modelCaller = TbCaller::findOne(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']]);
      if (!$modelCaller) {
        return '-';
      }
      if ($modelQ['servicegroupid'] == 1) { //???????????????????????????
        if ($modelCaller['call_status'] == 'calling' || $modelCaller['call_status'] == 'callend') {
          if ($modelCaller->tbCounterservice) {
            return '?????????????????????????????? (????????????????????????????????????) ' . $modelCaller->tbCounterservice->counterservice_name;
          } else {
            return '?????????????????????????????? (????????????????????????????????????) ';
          }
        }
      }
      if ($modelQ['servicegroupid'] == 2 && empty($modelQTran['counter_service_id'])) { //
        if ($modelCaller['call_status'] == 'calling' || $modelCaller['call_status'] == 'callend') {
          if ($modelCaller->tbCounterservice) {
            return '?????????????????????????????? (??????????????????????????????) ' . $modelCaller->tbCounterservice->counterservice_name;
          } else {
            return '?????????????????????????????? (??????????????????????????????) ';
          }
        }
      }
      if ($modelQ['servicegroupid'] == 2 && !empty($modelQTran['counter_service_id'])) { //
        $modelCaller = TbCaller::find()->where(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']])->orderBy('caller_ids DESC')->one();
        if (!$modelCaller) {
          return '-';
        }
        if ($modelCaller['call_status'] == 'calling' || $modelCaller['call_status'] == 'callend') {
          if ($modelCaller->tbCounterservice) {
            return '?????????????????????????????? (????????????????????????) ' . $modelCaller->tbCounterservice->counterservice_name;
          } else {
            return '?????????????????????????????? (????????????????????????) ';
          }
        }
      }
    } elseif ($modelQTran['service_status_id'] == 3) { //??????????????????
      $modelCaller = TbCaller::findOne(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']]);
      if (!$modelCaller) {
        return '-';
      }
      if ($modelQ['servicegroupid'] == 1) { //???????????????????????????
        if ($modelCaller['call_status'] == 'hold') {
          if ($modelCaller->tbCounterservice) {
            return '?????????????????? (????????????????????????????????????) ' . $modelCaller->tbCounterservice->counterservice_name;
          } else {
            return '?????????????????? (????????????????????????????????????) ';
          }
        }
      }
      if ($modelQ['servicegroupid'] == 2 && empty($modelQTran['counter_service_id'])) { //
        if ($modelCaller['call_status'] == 'hold') {
          if ($modelCaller->tbCounterservice) {
            return '?????????????????? (??????????????????????????????) ' . $modelCaller->tbCounterservice->counterservice_name;
          } else {
            return '?????????????????? (??????????????????????????????) ';
          }
        }
      }
      if ($modelQ['servicegroupid'] == 2 && !empty($modelQTran['counter_service_id'])) { //
        $modelCaller = TbCaller::find()->where(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']])->orderBy('caller_ids DESC')->one();
        if (!$modelCaller) {
          return '-';
        }
        if ($modelCaller['call_status'] == 'hold') {
          if ($modelCaller->tbCounterservice) {
            return '?????????????????? (????????????????????????) ' . $modelCaller->tbCounterservice->counterservice_name;
          } else {
            return '?????????????????? (????????????????????????) ';
          }
        }
      }
    } elseif ($modelQTran['service_status_id'] == 4) { //
      if ($modelQ['servicegroupid'] == 1) { //???????????????????????????
        return '??????????????????????????? (????????????????????????????????????)';
      }
      if ($modelQ['servicegroupid'] == 2 && empty($modelQTran['counter_service_id'])) { //
        return '??????????????????????????? (??????????????????????????????)';
      }
      if ($modelQ['servicegroupid'] == 2 && !empty($modelQTran['counter_service_id'])) { //
        return '????????????????????? (????????????????????????)';
      }
    } elseif ($modelQTran['service_status_id'] == 10) {
      if ($modelQ['servicegroupid'] == 1) { //???????????????????????????
        return '??????????????????????????? (????????????????????????????????????)';
      }
      if ($modelQ['servicegroupid'] == 2) {
        return '??????????????????????????? (????????????????????????)';
      }
    }
  }

  public function actionPrintTicket($id) //?????????????????????

  {
    $model = $this->findModelQuequ($id);
    $service = $this->findModelService($model['serviceid']);
    $ticket = $this->findModelTicket($service['prn_profileid']);
    $y = \Yii::$app->formatter->asDate('now', 'php:Y');
    $modelDrugDispensing = TbDrugDispensing::findOne(['HN' => $model['q_hn']]);

    $sql = 'SELECT
      count( `tb_quequ`.`q_ids` )
      FROM
        `tb_quequ`
      WHERE
        q_status_id = 1
        AND serviceid = :serviceid
        AND q_ids < :q_ids
        AND DATE( tb_quequ.q_timestp ) = CURRENT_DATE';
    $params = [':serviceid' => $model['serviceid'], ':q_ids' => $id];
    $count = Yii::$app->db->createCommand($sql)
      ->bindValues($params)
      ->queryScalar();

    $attr = [];
    $description = [];
    $keys = array_keys($model->attributeLabels());
    foreach ($keys as $value) {
      $attr['{' . $value . '}'] = $model->{$value};
    }

    $template = '';

    if ($model['locale'] == 'th') {
      $template = $ticket->template_th;
    }
    if ($model['locale'] == 'en') {
      $template = $ticket->template_en;
    }
    // ???????????????????????????????????????????????? 
    if (empty($model['cid'])) {
      if ($model['locale'] == 'th') {
        $template = $ticket->template_th_small;
      }
      if ($model['locale'] == 'en') {
        $template = $ticket->template_en_small;
      }
    }

    $template = strtr($template, ArrayHelper::merge([
      '{hos_name_th}' => $ticket->hos_name_th,
      '{q_hn}' => $model->q_hn,
      '{pt_name}' => $model->pt_name,
      '{q_num}' => $model->q_num,
      '{q_vn}' => $model->q_vn,
      '{q_qn}' => $model->q_qn,
      '{age}' => $model->age,
      '{rx_q}' => $model->rx_q,
      '{pharmacy_drug_name}' => ArrayHelper::getValue($modelDrugDispensing, 'pharmacy_drug_name', ''), //???????????????????????????????????????
      '{pt_visit_type}' => '',
      '{service_name}' => Yii::t('app.frontend', $service['service_name'], [], $model['locale']),
      '{time}' => \Yii::$app->formatter->asDate('now', 'php:d M ' . substr($y, 2)) . ' ' . \Yii::$app->formatter->asDate('now', 'php:H:i'),
      '{user_print}' => Yii::$app->user->isGuest ? 'Kiosk' : Yii::$app->user->identity->profile->name,
      '{qwaiting}' => $count,
      '/img/logo/logo.jpg' => $ticket->logo_path ? $ticket->logo_base_url . '/' . $ticket->logo_path : '/img/logo/logo.jpg',
    ], $attr));
    return $this->renderAjax('print-ticket', [
      'model' => $model,
      'ticket' => $ticket,
      'template' => $template,
      'service' => $service,
      'modelDrugDispensing' => $modelDrugDispensing,
    ]);
  }

  public function actionUpdateQueue($id)
  {
    $request = Yii::$app->request;
    $model = $this->findModelQuequ($id);
    $serviceid = $model['serviceid'];

    if ($request->isAjax) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      if ($request->isGet) {
        return [
          'title' => '?????????????????????????????????????????? #' . $model['q_num'],
          'content' => $this->renderAjax('_form_queue', [
            'model' => $model,
          ]),
          'footer' => '',
        ];
      } elseif ($model->load($request->post())) {
        $data = $request->post('TbQuequ', []);
        if ($serviceid != $data['serviceid']) {
          $service = TbService::findOne($data['serviceid']);
          $model->q_num = $model->genQnum($service);
        }
        if ($model->save()) {
          return [
            'title' => '?????????????????????????????????????????? #' . $model['q_num'],
            'content' => '<span class="text-success">????????????????????????????????????!</span>',
            'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
            'status' => '200',
            'model' => $model,
          ];
        } else {
          return [
            'title' => '?????????????????????????????????????????? #' . $model['q_num'],
            'content' => $this->renderAjax('_form_queue', [
              'model' => $model,
            ]),
            'footer' => '',
          ];
        }
      } else {
        return [
          'title' => '?????????????????????????????????????????? #' . $model['q_num'],
          'content' => $this->renderAjax('_form_queue', [
            'model' => $model,
          ]),
          'footer' => '',
        ];
      }
    } else {
      throw new MethodNotAllowedHttpException('method not allowed.');
    }
  }

  public function actionChildServicegroup()
  {
    $out = [];
    if (isset($_POST['depdrop_parents'])) {
      $id = end($_POST['depdrop_parents']);
      $list = TbService::find()->andWhere(['service_groupid' => $id])->asArray()->all();
      $selected = null;
      if ($id != null && count($list) > 0) {
        $selected = '';
        foreach ($list as $i => $item) {
          $out[] = ['id' => $item['serviceid'], 'name' => $item['service_name']];
          if ($i == 0) {
            $selected = $item['serviceid'];
          }
        }
        // Shows how you can preselect a value
        echo Json::encode(['output' => $out, 'selected' => $selected]);
        return;
      }
    }
    echo Json::encode(['output' => '', 'selected' => '']);
  }

  public function actionButtonList()
  {
    $kiosks = TbKiosk::find()->where(['status' => 1])->all();
    return $this->render('_button_list', [
      'kiosks' => $kiosks,
    ]);
  }

  public function actionKioskTicket($id)
  {
    $this->layout = 'blank';
    $model = TbKiosk::findOne($id);
    $service_ids = !empty($model['service_ids']) ? explode(",", $model['service_ids']) : [];
    $services = [];
    foreach ($service_ids as $service_id) {
      $services[] = TbService::findOne($service_id);
    }
    return $this->render('_kiosk_ticket', [
      'model' => $model,
      'services' => $services,
    ]);
  }

  public function actionPublicTicket()
  {
    $this->layout = 'blank';
    $serviceGroup = TbServicegroup::find()->orderBy(['servicegroup_order' => SORT_ASC])->all();
    return $this->render('public-ticket', [
      'service' => $serviceGroup,
    ]);
  }

  public function actionLedOptions()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    // ??????????????????????????????
    $services = (new \yii\db\Query())
      ->select([
        'tb_service.serviceid',
        'tb_service.service_name',
        'tb_service.service_groupid',
        'tb_service.service_route',
        'tb_service.prn_profileid',
        'tb_service.prn_copyqty',
        'tb_service.service_prefix',
        'tb_service.service_numdigit',
        'tb_service.service_status',
        'tb_service.service_md_name_id',
        'tb_servicegroup.servicegroup_name',
      ])
      ->from('tb_service')
      ->innerJoin('tb_servicegroup', 'tb_service.service_groupid = tb_servicegroup.servicegroupid')
      ->all();
    // ??????????????????????????????
    $counters = (new \yii\db\Query())
      ->select([
        'tb_counterservice.counterserviceid',
        'tb_counterservice.counterservice_name',
        'tb_counterservice.counterservice_callnumber',
        'tb_counterservice.counterservice_type',
        'tb_counterservice.servicegroupid',
        'tb_counterservice.userid',
        'tb_counterservice.serviceid',
        'tb_counterservice.sound_stationid',
        'tb_counterservice.sound_id',
        'tb_counterservice.counterservice_status',
        'tb_counterservice.sound_service_id',
        'tb_counterservice_type.counterservice_type',
      ])
      ->from('tb_counterservice')
      ->innerJoin('tb_counterservice_type', 'tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid')
      ->all();
    return [
      'services' => $services,
      'counters' => $counters,
    ];
  }

  // ????????????????????????????????????????????????????????????????????? ????????????
  public function actionPtRight($cid = null)
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    if (empty($cid) || strlen($cid) < 13) {
      return $this->apiValidate('???????????????????????????????????????????????????????????????????????????.');
    }

    $client = Yii::$app->soapClient;
    $userToken = TbTokenNhso::find()->orderBy('crearedat DESC')->limit(1)->one();
    if (!$userToken) {
      return $this->apiValidate('???????????????????????????????????? Token.');
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

  // ??????????????????????????????????????????????????????
  private function verifyPtRight($data)
  {
    if (!$data) {
      return $this->apiBadRequest('RESPONSE FAILED');
    } else if ($data['ws_status'] == 'NHSO-00003') {
      return $this->apiValidate(isset($data['ws_status_desc']) ? $data['ws_status_desc'] : 'TOKEN EXPIRE');
    } else if (empty($data['fname'])) {
      return $this->apiDataNotFound('NOT FOUND IN NHSO');
    } elseif (!isset($data['maininscl']) || !isset($data['maininscl_name'])) {
      return $this->apiDataNotFound('????????????????????????????????????????????????????????????????????????');
    }
  }

  public function actionCreateQueue() //??????????????? queue
  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $params = Json::decode(\Yii::$app->getRequest()->getRawBody());

    if (!ArrayHelper::getValue($params, 'servicegroupid', null)) {
      throw new HttpException(400, 'invalid servicegroupid.');
    }
    if (!ArrayHelper::getValue($params, 'serviceid', null)) {
      throw new HttpException(400, 'invalid serviceid.');
    }

    $servicegroupid = ArrayHelper::getValue($params, 'servicegroupid', null); //
    $serviceid = ArrayHelper::getValue($params, 'serviceid', null); //?????????????????????????????????
    $created_from = ArrayHelper::getValue($params, 'created_from', null); //????????????????????????????????? 1 kiosk 2 mobile
    $q_status_id = ArrayHelper::getValue($params, 'q_status_id', 1); //???????????????

    // data models
    $modelService = TbService::findOne($serviceid); // ?????????????????????????????????
    if ($modelService == null) {
      throw new NotFoundHttpException('?????????????????????????????????????????????.');
    }
    $modelServiceGroup = TbServicegroup::findOne($servicegroupid); // ?????????????????????????????????
    if ($modelServiceGroup == null) {
      throw new NotFoundHttpException('????????????????????????????????????????????????????????????.');
    }

    $counttslot = (new \yii\db\Query()) //?????? slot ?????????????????????????????????????????????????????????
      ->select([
        'tb_service_tslot.*',
      ])
      ->from('tb_service_tslot')
      ->where(['tb_service_tslot.serviceid' => $serviceid])
      ->count();

    $tslotid = $this->getSlot($serviceid);

    $slot = (new \yii\db\Query()) //?????? slot ?????????????????????????????????????????????????????????
      ->select([
        'tb_service_tslot.*',
      ])
      ->from('tb_service_tslot')
      ->where(['tb_service_tslot.serviceid' => $serviceid])
      ->andWhere('CURRENT_TIME >= tb_service_tslot.t_slot_begin')
      ->andWhere('CURRENT_TIME <= tb_service_tslot.t_slot_end')->one();

    if ($slot) {
      $count = (new \yii\db\Query())
        ->from('tb_quequ')
        ->where([
          'tb_quequ.serviceid' => $serviceid,
          'tb_quequ.tslotid' => $slot['tslotid'],
        ])
        ->andWhere('DATE(q_timestp) = CURRENT_DATE')
        ->count();
      $q_balance = $slot['q_limitqty'] - $count;
      if ($q_balance == 0 && $count > 0) { //??????????????? ????????? limit
        throw new HttpException(400, '?????????????????????!');
      }
    }

    if ($counttslot > 0 && $tslotid == null) {
      throw new HttpException(400, '???????????????????????????????????????????????????????????????????????????????????????!');
    }


    $db = Yii::$app->db;
    $transaction = $db->beginTransaction();

    try {


      $modelQueue = new TbQuequ();
      $q_num = $modelQueue->generateQnumber([
        'serviceid' => $serviceid,
        'service_prefix' => $modelService['service_prefix'],
        'service_numdigit' => $modelService['service_numdigit'],
      ]);

      $modelQueue->setAttributes([

        'q_num' => $q_num,
        'servicegroupid' => $servicegroupid, //?????????????????????????????????
        'serviceid' => $serviceid,
        'created_from' => $created_from,
        'q_status_id' => $q_status_id, //???????????????
        'tslotid' => $tslotid
      ]);

      if ($modelQueue->save()) {

        $modelQstatus = TbServiceStatus::findOne($modelQueue['q_status_id']);
        $modelQtrans = TbQtrans::findOne(['q_ids' => $modelQueue->q_ids]);
        $queue_left = (new \yii\db\Query()) //???????????????
          ->select([
            'count(`tb_quequ`.`q_ids`) as `queue_left`',
          ])
          ->from('`tb_quequ`')
          ->where([
            '`tb_quequ`.`serviceid`' => $serviceid,
          ])
          ->where('q_status_id <> :q_status_id', [':q_status_id' => 4])
          ->andWhere('q_ids < :q_ids', [':q_ids' => $modelQueue['q_ids']])
          ->andWhere('DATE(q_timestp) = CURRENT_DATE')
          ->count();
        if (!$modelQtrans) {
          $modelQtrans = new TbQtrans();
        }
        $modelQtrans->setAttributes([
          'q_ids' => $modelQueue->q_ids,
          'servicegroupid' => $servicegroupid,
          'service_status_id' => $modelQueue->q_status_id,
        ]);
        if ($modelQtrans->save()) {
          $transaction->commit();
          return [
            'modelQueue' => $modelQueue,
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
        throw new HttpException(422, Json::encode($modelQueue->errors));
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  /*
  public function actionCreateQueue() //??????????????? queue

  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $params = Json::decode(\Yii::$app->getRequest()->getRawBody());

    if (!ArrayHelper::getValue($params, 'patient_info', null)) {
      throw new HttpException(400, 'invalid patient_info.');
    }
    // if (!ArrayHelper::getValue($params, 'right', null)) {
    //     throw new HttpException(400, 'invalid right.');
    // }
    if (!ArrayHelper::getValue($params, 'servicegroupid', null)) {
      throw new HttpException(400, 'invalid servicegroupid.');
    }
    if (!ArrayHelper::getValue($params, 'serviceid', null)) {
      throw new HttpException(400, 'invalid serviceid.');
    }
    if (!ArrayHelper::getValue($params, 'created_from', null)) {
      throw new HttpException(400, 'invalid created_from.');
    }

    $patient_info = $params['patient_info']; // ??????????????????????????????????????? pt_name,hn,cid
    $right = $params['right']; //??????????????????
    $appoint = ArrayHelper::getValue($params, 'appoint', null); //???????????????????????????
    $data_visit = ArrayHelper::getValue($patient_info, 'data_visit', null); //???????????????????????????

    $pt_name = ArrayHelper::getValue($patient_info, 'pt_name', null);
    $hn = ArrayHelper::getValue($patient_info, 'hn', null);

    $cid = ArrayHelper::getValue($patient_info, 'cid', null);

    $maininscl_name = ArrayHelper::getValue($right, 'maininscl_name', null); //??????????????????????????????

    $appoint_id = ArrayHelper::getValue($appoint, 'appoint_id', null); //id ?????????????????????
    $doctor_id = ArrayHelper::getValue($appoint, 'doctor_id', null); //???????????????????????????
    $doctor_name = ArrayHelper::getValue($appoint, 'doctor_name', null); //???????????????????????????
    $servicegroupid = ArrayHelper::getValue($params, 'servicegroupid', null); //
    $serviceid = ArrayHelper::getValue($params, 'serviceid', null); //?????????????????????????????????
    $created_from = ArrayHelper::getValue($params, 'created_from', null); //????????????????????????????????? 1 kiosk 2 mobile
    $picture = ArrayHelper::getValue($params, 'picture', null); //??????????????????????????????
    $pt_visit_type_id = ArrayHelper::getValue($params, 'pt_visit_type_id', null); //?????????????????? walkin/????????? walkin
    $quickly = ArrayHelper::getValue($params, 'quickly', null); //??????????????????????????????????????????
    $u_id = ArrayHelper::getValue($params, 'u_id', null); //??????????????????????????????????????? Mobile
    $q_status_id = ArrayHelper::getValue($params, 'q_status_id', 1); //???????????????
    $token = ArrayHelper::getValue($params, 'token');  //???????????????????????????????????????
    $age = ArrayHelper::getValue($patient_info, 'age', null); //????????????
    $age = str_replace(' ', '', $age);
    $age = str_replace('??????', '', $age);
    $countdrug = ArrayHelper::getValue($params, 'countdrug', 0); //
    $qfinace = ArrayHelper::getValue($params, 'qfinace', 0); //

    // data models
    $modelService = TbService::findOne($serviceid); // ?????????????????????????????????
    if ($modelService == null) {
      throw new NotFoundHttpException('?????????????????????????????????????????????.');
    }
    $modelServiceGroup = TbServicegroup::findOne($servicegroupid); // ?????????????????????????????????
    if ($modelServiceGroup == null) {
      throw new NotFoundHttpException('????????????????????????????????????????????????????????????.');
    }
    $qn =  null;
    $vn = null;
    if (is_array($data_visit)) {
      $map_data_visit_qn = ArrayHelper::map($data_visit, 'main_dep', 'qn'); // {010: 2, 020:5}
      $map_data_visit_vn = ArrayHelper::map($data_visit, 'main_dep', 'vn');
      $qn = ArrayHelper::getValue($map_data_visit_qn, $modelService['main_dep'], null);
      $vn = ArrayHelper::getValue($map_data_visit_vn, $modelService['main_dep'], null);
    }
    // if (is_array($data_visit) && !empty($data_visit) && $data_visit != null) {
    //     $visit = array_filter($data_visit, function ($v, $k) use ($modelService) {
    //         return $v['main_dep'] == $modelService['main_dep'];
    //     }, ARRAY_FILTER_USE_BOTH);
    //     $vn = ArrayHelper::getValue($visit, '0.vn', null);
    // }

    if ($appoint) {
      $maininscl_name = ArrayHelper::getValue($appoint, 'appoint_right', null); //??????????????????????????????
    }

    $tslotid = $this->getSlot($serviceid);

    $db = Yii::$app->db;
    $transaction = $db->beginTransaction();

    try {
      $modelQueue = TbQuequ::find()
        ->where([
          'serviceid' => $serviceid,
          'servicegroupid' => $servicegroupid,
          'q_hn' => $hn,
          // 'q_status_id' => 4
        ])
        ->andWhere('DATE(q_timestp) = CURRENT_DATE')
        ->andWhere('q_status_id <> :q_status_id', [':q_status_id' => 4])
        ->orderBy('q_ids DESC')
        ->one();
      if (!$modelQueue) {
        $modelQueue = new TbQuequ();
        $q_num = $modelQueue->generateQnumber([
          'serviceid' => $serviceid,
          'service_prefix' => $modelService['service_prefix'],
          'service_numdigit' => $modelService['service_numdigit'],
        ]);
      } else {
        $maininscl_name = $modelQueue['maininscl_name'];
        // $token = $modelQueue['token'];
        $u_id = $modelQueue['u_id'];
        $tslotid = $modelQueue['tslotid'];
        $q_num = $modelQueue['q_num'];
      }

      $modelQueue->setAttributes([
        'q_num' => $q_num,
        'cid' => $cid,
        'q_hn' => $hn,
        'q_vn' => $vn,
        'q_qn' => $qn,
        'pt_name' => $pt_name,
        'appoint_id' => $appoint_id,
        'servicegroupid' => $servicegroupid, //?????????????????????????????????
        'serviceid' => $serviceid,
        'created_from' => $created_from,
        'q_status_id' => $q_status_id, //???????????????
        'doctor_id' => $doctor_id,
        'doctor_name' => $doctor_name,
        'maininscl_name' => $maininscl_name,
        'pt_visit_type_id' => $pt_visit_type_id,
        'tslotid' => $tslotid,
        'quickly' => 0, //?????????????????????????????????????????? default 0
        'u_id' => $u_id, //??????????????????????????????????????? Mobile
        'token' => $token, //???????????????????????????????????????
        'age' => $age,
        'qfinace' => $qfinace,
        'countdrug' => $countdrug,
        //'q_status_id' => $u_id ? 6 : 1,  //???????????????????????? default 1 ???????????????????????? u_id ???????????????????????? mobile status = 6
      ]);
      if (!empty($picture)) {
        $pt_pic = $this->uploadPicture($picture, $hn);
        $modelQueue->pt_pic = $pt_pic;
      } else {
        $queuePic = TbQuequ::find()
          ->where([
            'q_hn' => $hn,
          ])
          ->andWhere('DATE(q_timestp) = CURRENT_DATE')
          ->andWhere('pt_pic <> :pt_pic', [':pt_pic' => ''])
          ->orderBy('q_ids DESC')
          ->one();
        if ($queuePic) {
          $modelQueue->pt_pic = $queuePic['pt_pic'];
        }
      }

      if ($modelQueue->save()) {

        $modelQstatus = TbServiceStatus::findOne($modelQueue['q_status_id']);
        $modelQtrans = TbQtrans::findOne(['q_ids' => $modelQueue->q_ids]);
        $queue_left = (new \yii\db\Query()) //???????????????
          ->select([
            'count(`tb_quequ`.`q_ids`) as `queue_left`',
          ])
          ->from('`tb_quequ`')
          ->where([
            '`tb_quequ`.`serviceid`' => $serviceid,
          ])
          ->where('q_status_id <> :q_status_id', [':q_status_id' => 4])
          ->andWhere('q_ids < :q_ids', [':q_ids' => $modelQueue['q_ids']])
          ->andWhere('DATE(q_timestp) = CURRENT_DATE')
          ->count();
        if (!$modelQtrans) {
          $modelQtrans = new TbQtrans();
        }
        $modelQtrans->setAttributes([
          'q_ids' => $modelQueue->q_ids,
          'servicegroupid' => $servicegroupid,
          'service_status_id' => $modelQueue->q_status_id,
          'doctor_id' => $doctor_id,
          //'service_status_id' => $u_id ? 6 : 1,  //??????????????? u_id ????????????????????????????????????????????? mobile status = 6
        ]);
        if ($modelQtrans->save()) {
          $transaction->commit();
          if (!empty($modelQueue['token'])) {
            $client = new Client();
            $client->createRequest()
              ->setFormat(Client::FORMAT_JSON)
              ->setMethod('POST')
              ->setUrl(Yii::$app->params['messageURL'])
              ->addHeaders(['content-type' => 'application/json'])
              ->setData([
                'message' => [
                  'data' => [
                    'type' => 'create-queue'
                  ],
                  'notification' => [
                    'title' => '????????????????????????????????????!',
                    'body' => '????????????????????????????????????????????????????????? ' . $modelQueue['q_num']
                  ],
                  'token' => $modelQueue['token']
                ],
              ])
              ->send();
          }
          return [
            'modelQueue' => $modelQueue,
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
        throw new HttpException(422, Json::encode($modelQueue->errors));
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  */
  private function getSlot($serviceid, $tslotid = [])
  {
    $query = (new \yii\db\Query()) //?????? slot ?????????????????????????????????????????????????????????
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
      if ($slot['q_limit'] == 1) { //????????????????????? limit
        $count = (new \yii\db\Query())
          ->from('tb_quequ')
          ->where([
            'tb_quequ.serviceid' => $serviceid,
            'tb_quequ.tslotid' => $slot['tslotid'],
          ])
          ->andWhere('DATE(q_timestp) = CURRENT_DATE')
          ->count();
        $q_balance = $slot['q_limitqty'] - $count;
        if ($q_balance == 0) { //??????????????? ????????? limit
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

  private function uploadPicture($picture, $hn) //upload ?????????

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
    // example 766809 ???????????? 0 ????????? hn ????????? 7 ????????????
    $hn = sprintf("%'.07d\n", $hn);

    $rootDir = substr($hn, 0, 3); // ????????? hn 3 ??????????????????
    $subDir1 = substr($hn, 3, -2); // ????????? hn 3 ????????????????????????
    $subDir2 = substr($hn, 6, -1); // ????????? hn ??????????????????????????????
    $driUpload = $rootDir . '/' . $subDir1 . '/' . $subDir2; // ?????????????????????????????????????????????????????? ????????? hn ???????????????????????? ??????????????? /076/680/9

    $rootImageDir = Yii::getAlias('@frontend/web/uploads');
    $path1 = $rootImageDir . '/' . $rootDir; // frontend/web/source/opd/076
    $path2 = $rootImageDir . '/' . $rootDir . '/' . $subDir1; // frontend/web/source/opd/076/680
    $path3 = $rootImageDir . '/' . $rootDir . '/' . $subDir1 . '/' . $subDir2; // frontend/web/source/opd/076/680/9

    if (!is_dir($rootImageDir)) {
      FileHelper::createDirectory($rootImageDir, 0777);
    }
    if (is_dir($rootImageDir) && !is_dir($path1)) {
      FileHelper::createDirectory($path1, 0777);
    }
    if (is_dir($path1) && !is_dir($path2)) {
      FileHelper::createDirectory($path2, 0777);
    }
    if (is_dir($path2) && !is_dir($path3)) {
      FileHelper::createDirectory($path3, 0777);
      $isCreateDir = true;
    }
    if (is_dir($path3)) {
      $filepath = $rootImageDir . '/' . $driUpload . '/' . $filename; // dir = /076/680/9/766809.jpg
      $f = file_put_contents($filepath, $imageDecode);
      if ($f && file_exists($filepath)) {
        return Url::base(true) . Url::to(['/uploads/' . $driUpload . '/' . $filename]);
      } else {
        return null;
      }
    }
  }

  private function saveImagePatient($patient, $patient_id) // ????????????????????????????????????????????????

  {
    $img = str_replace('data:image/png;base64,', '', $patient['pt_pic']);
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $imageDecode = base64_decode($img);
    // $security = Yii::$app->security->generateRandomString();

    $hn = preg_replace('/\s/', '', $patient['hn']); // 766809
    $filename = implode('.', [ // 766809.jpg
      $hn,
      'jpg',
    ]);

    // example 766809 ???????????? 0 ????????? hn ????????? 7 ????????????
    $hn = sprintf("%'.07d\n", $hn);

    $rootDir = substr($hn, 0, 3); // ????????? hn 3 ??????????????????
    $subDir1 = substr($hn, 3, -2); // ????????? hn 3 ????????????????????????
    $subDir2 = substr($hn, 6, -1); // ????????? hn ??????????????????????????????
    $driUpload = $rootDir . '/' . $subDir1 . '/' . $subDir2; // ?????????????????????????????????????????????????????? ????????? hn ???????????????????????? ??????????????? /076/680/9

    $rootImageDir = Yii::getAlias('@frontend/web/source/opd');
    $path1 = $rootImageDir . '/' . $rootDir; // frontend/web/source/opd/076
    $path2 = $rootImageDir . '/' . $rootDir . '/' . $subDir1; // frontend/web/source/opd/076/680
    $path3 = $rootImageDir . '/' . $rootDir . '/' . $subDir1 . '/' . $subDir2; // frontend/web/source/opd/076/680/9

    if (!is_dir($rootImageDir)) {
      FileHelper::createDirectory($rootImageDir, 0777);
    }
    if (is_dir($rootImageDir) && !is_dir($path1)) {
      FileHelper::createDirectory($path1, 0777);
    }
    if (is_dir($path1) && !is_dir($path2)) {
      FileHelper::createDirectory($path2, 0777);
    }
    if (is_dir($path2) && !is_dir($path3)) {
      FileHelper::createDirectory($path3, 0777);
      $isCreateDir = true;
    }
    if (is_dir($path3)) {
      $filepath = $rootImageDir . '/' . $driUpload . '/' . $filename; // dir = /076/680/9/766809.jpg
      $f = file_put_contents($filepath, $imageDecode);
      if ($f && file_exists($filepath)) {
        $component = Yii::$app->get('fileStorage');
        FileStorageItem::deleteAll(['ref_id' => $patient_id, 'ref_table' => 'tb_quequ']);
        $modelStorage = new FileStorageItem();
        $modelStorage->base_url = $component->baseUrl;
        $modelStorage->path = '/opd/' . $driUpload . '/' . $filename; // /opd/076/680/9/766809.jpg
        $modelStorage->type = FileHelper::getMimeType($filepath);
        $modelStorage->size = filesize($filepath);
        $modelStorage->name = $patient['hn'];
        $modelStorage->ref_id = $patient_id;
        $modelStorage->ref_table = TbQuequ::tableName();
        $modelStorage->component = 'fileStorage';
        $modelStorage->created_at = time();
        if ($modelStorage->save()) {
          return $modelStorage;
        } else {
          throw new HttpException(422, Json::encode($modelStorage->errors));
        }
      } else {
        return null;
      }
    } else {
      return null;
    }
  }

  public function actionScanQueueMobileQn() //update ??????????????? ????????? 6 ????????? mobile ???????????? ??????????????? 1 ?????????????????? kiosk

  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $params = Json::decode(\Yii::$app->getRequest()->getRawBody());
    $qn = ArrayHelper::getValue($params, 'qn', null); //????????????????????? qn

    if (!$qn) {
      throw new HttpException(400, 'invalid qn');
    }

    \Yii::$app->response->format = Response::FORMAT_JSON;
    $db = Yii::$app->db;
    $transaction = $db->beginTransaction();

    try {
      $modelQueue = TbQuequ::find()
        ->where(['q_qn' => $qn, 'q_status_id' => [6]]) //????????????????????????????????? mobile
        ->andWhere('DATE(q_timestp) = CURRENT_DATE')
        ->one();
      if (!$modelQueue) {
        throw new HttpException(404, '??????????????????????????????????????????');
      }
      $modelQTrans = TbQtrans::findOne(['q_ids' => $modelQueue['q_ids']]);

      $modelQueue->q_status_id = 1;
      $modelQTrans->service_status_id = 1;

      if ($modelQueue->save() && $modelQTrans->save()) {
        $transaction->commit();
        if (!empty($modelQueue['token'])) {
          $client = new Client();
          $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl(Yii::$app->params['messageURL'])
            ->addHeaders(['content-type' => 'application/json'])
            ->setData([
              'message' => [
                'data' => [
                  'type' => 'scan-mobile'
                ],
                'notification' => [
                  'title' => '?????????????????????????????????????????????!',
                  'body' => $modelQueue['q_num']
                ],
                'token' => $modelQueue['token']
              ],
            ])
            ->send();
        }
        return [
          'modelQueue' => $modelQueue,
          'modelQTrans' => $modelQTrans,
        ];
      } else if ($modelQueue->errors) {
        $transaction->rollBack();
        throw new HttpException(422, Json::encode($modelQueue->errors));
      } else if ($modelQTrans->errors) {
        $transaction->rollBack();
        throw new HttpException(422, Json::encode($modelQTrans->errors));
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  public function actionScanQueueMobileHn() //update ??????????????? ????????? 6 ????????? mobile ???????????? ??????????????? 1 ?????????????????? kiosk ????????? HN

  {
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $params = Json::decode(\Yii::$app->getRequest()->getRawBody());
    $hn = ArrayHelper::getValue($params, 'hn', null); //????????????????????? hn
    $q_ids = ArrayHelper::getValue($params, 'q_ids', null); //?????????????????????

    if (!$hn) {
      throw new HttpException(400, 'invalid hn');
    }

    \Yii::$app->response->format = Response::FORMAT_JSON;
    $db = Yii::$app->db;
    $transaction = $db->beginTransaction();

    try {
      $modelQueue = TbQuequ::find()
        ->where(['q_hn' => $hn, 'q_status_id' => [6], 'q_ids' => $q_ids]) //????????????????????????????????? mobile
        ->andWhere('DATE(q_timestp) = CURRENT_DATE')
        ->one();
      if (!$modelQueue) {
        throw new HttpException(404, '??????????????????????????????????????????');
      }
      $modelQTrans = TbQtrans::findOne(['q_ids' => $modelQueue['q_ids']]);

      $modelQueue->q_status_id = 1;
      $modelQTrans->service_status_id = 1;

      if ($modelQueue->save() && $modelQTrans->save()) {
        $transaction->commit();
        if (!empty($modelQueue['token'])) {
          $client = new Client();
          $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->setUrl(Yii::$app->params['messageURL'])
            ->addHeaders(['content-type' => 'application/json'])
            ->setData([
              'message' => [
                'data' => [
                  'type' => 'scan-mobile'
                ],
                'notification' => [
                  'title' => '?????????????????????????????????????????????!',
                  'body' => $modelQueue['q_num']
                ],
                'token' => $modelQueue['token']
              ],
            ])
            ->send();
        }
        return [
          'modelQueue' => $modelQueue,
          'modelQTrans' => $modelQTrans,
        ];
      } else if ($modelQueue->errors) {
        $transaction->rollBack();
        throw new HttpException(422, Json::encode($modelQueue->errors));
      } else if ($modelQTrans->errors) {
        $transaction->rollBack();
        throw new HttpException(422, Json::encode($modelQTrans->errors));
      }
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
      throw $e;
    }
  }

  public function actionQueueList($hn) //????????????????????????
  {
    \Yii::$app->response->format = Response::FORMAT_JSON;

    if (!$hn) {
      throw new HttpException(400, 'invalid hn.');
    }
    $q_status = (new \yii\db\Query()) //????????????????????????
      ->select([
        'q.q_ids AS q_ids',
        'q.q_num AS q_num',
        'q.q_hn AS q_hn',
        'q.q_vn AS q_vn',
        'q.q_qn AS q_qn',
        'q.pt_pic AS pt_pic',
        'q.q_status_id AS q_status_id',
        'DATE_FORMAT(q.q_timestp,\'%Y-%m-%d\') as queue_date',
        'TIME_FORMAT(q.q_timestp,\'%H:%i\') as queue_time',
        'q.serviceid AS serviceid',
        'tb_servicegroup.servicegroup_name AS servicegroup_name',
        'tb_deptcode.deptname AS deptname',
        'tb_service_status.service_status_name AS service_status_name',
        '(
						SELECT
							count( `tb_quequ`.`q_ids` )
						FROM
							`tb_quequ`
						WHERE
							q_status_id = 1
							AND serviceid = q.serviceid
							AND q_ids < q.q_ids
							AND DATE( tb_quequ.q_timestp ) = CURRENT_DATE
						) AS queue_left',
      ])
      ->from('tb_quequ as q')
      ->innerJoin('tb_service_status', 'q.q_status_id = tb_service_status.service_status_id')
      ->innerJoin('tb_service', 'q.serviceid = tb_service.serviceid')
      ->innerJoin('tb_servicegroup', 'tb_service.service_groupid = tb_servicegroup.servicegroupid')
      ->leftJoin('tb_deptcode', 'tb_service.main_dep = tb_deptcode.deptcode')
      ->where([
        'q.q_hn' => $hn,
      ])
      ->andWhere('DATE( q.q_timestp ) = CURRENT_DATE')
      ->orderBy('q.q_ids desc')
      ->all();

    if (!$q_status) {
      return [
        'status' => false,
        'message' => '????????????????????????????????? HN ??????????????????????????????????????????????????????!',
        'data' => $q_status,
      ];
    } else {
      return [
        'status' => true,
        'message' => 'success',
        'data' => $q_status,
      ];
    }
  }

  protected function findModelPatient($id)
  {
    if (($model = Patient::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist. {' . Patient::className() . '}');
  }

  protected function findModelQueue($id)
  {
    if (($model = TbQuequ::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist. {' . TbQuequ::className() . '}');
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
}

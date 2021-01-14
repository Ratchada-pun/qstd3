<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use frontend\modules\app\components\HisQuery;
use kartik\widgets\ActiveForm;
use homer\widgets\tbcolumn\ActionTable;
use homer\widgets\tbcolumn\ColumnTable;
use homer\widgets\tbcolumn\ColumnData;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
#models
use frontend\modules\app\models\TbServicegroup;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbQuequ;
use frontend\modules\app\models\TbQtrans;
use frontend\modules\app\models\TbTicket;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbKiosk;
use frontend\modules\app\traits\ModelTrait;

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
						'actions' => ['led-options'],
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

	public function actionIndex()
	{
		$this->layout = '@app/views/layouts/main-kiosk.php';
		$serviceGroup = TbServicegroup::find()->orderBy(['servicegroup_order' => SORT_ASC])->all();
		return $this->render('index', [
			'service' => $serviceGroup,
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
					'title' => 'ออกบัตรคิว #' . $modelService['service_name'],
					'content' => $this->renderAjax('_form_ticket', [
						'modelServiceGroup' => $modelServiceGroup,
						'modelService' => $modelService,
						'model' => $model,
						'serviceData' => ['groupid' => $groupid, 'serviceid' => $serviceid],
					]),
					'footer' => ''
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
						'search_by' => $postData['search_by']
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
				->select(['tb_quequ.*', 'tb_service.*', 'tb_quequ.quickly as quickly1'])
				->from('tb_quequ')
				->innerJoin('tb_service', 'tb_service.serviceid = tb_quequ.serviceid')
				->orderBy('q_ids DESC');

			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => [
					'pageSize' => false,
				],
				'key' => 'q_ids'
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
						'format' => 'raw'
					],
					[
						'attribute' => 'q_hn',
					],
					[
						'attribute' => 'pt_name',
					],
					[
						'attribute' => 'service_name',
						'value' => function ($model, $key, $index, $column) {
							return $model['quickly1'] == 1 ? $model['service_name'] . ' (คิวด่วน!)' : $model['service_name'];
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
							'role' => 'modal-remote'
						],
						'buttons' => [
							'update' => function ($url, $model, $key) {
								return Html::a('<i class="fa fa-edit"></i>' . ' แก้ไข', Url::to(['/app/kiosk/update-queue', 'id' => $key]), [
									'class' => 'btn btn-primary',
									'role' => 'modal-remote'
								]);
							},
							'print' => function ($url, $model, $key) {
								return Html::a('<i class="pe-7s-print"></i>' . ' พิมพ์บัตรคิว', false, [
									'onclick' => 'return window.open("' . Url::to(['/app/kiosk/print-ticket', 'id' => $key]) . '","myPrint", "width=800, height=600");',
									'class' => 'btn btn-success',
									'title' => 'Print',
								]);
							}
						],
					]
				]
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
		if ($modelQTran['service_status_id'] == 1) {//รอเรียก
			if ($modelQ['servicegroupid'] == 1) {//ลงทะเบียน
				return 'รอเรียกคิว (จุดลงทะเบียน)';
			} else {//ซักประวัติ
				return 'รอเรียกคิว (ซักประวัติ)';
			}
		} elseif ($modelQTran['service_status_id'] == 2) {//เรียกคิว
			$modelCaller = TbCaller::findOne(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']]);
			if (!$modelCaller) {
				return '-';
			}
			if ($modelQ['servicegroupid'] == 1) {//ลงทะเบียน
				if ($modelCaller['call_status'] == 'calling' || $modelCaller['call_status'] == 'callend') {
					if ($modelCaller->tbCounterservice) {
						return 'กำลังเรียก (จุดลงทะเบียน) ' . $modelCaller->tbCounterservice->counterservice_name;
					} else {
						return 'กำลังเรียก (จุดลงทะเบียน) ';
					}
				}
			}
			if ($modelQ['servicegroupid'] == 2 && empty($modelQTran['counter_service_id'])) {//
				if ($modelCaller['call_status'] == 'calling' || $modelCaller['call_status'] == 'callend') {
					if ($modelCaller->tbCounterservice) {
						return 'กำลังเรียก (ซักประวัติ) ' . $modelCaller->tbCounterservice->counterservice_name;
					} else {
						return 'กำลังเรียก (ซักประวัติ) ';
					}
				}
			}
			if ($modelQ['servicegroupid'] == 2 && !empty($modelQTran['counter_service_id'])) {//
				$modelCaller = TbCaller::find()->where(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']])->orderBy('caller_ids DESC')->one();
				if (!$modelCaller) {
					return '-';
				}
				if ($modelCaller['call_status'] == 'calling' || $modelCaller['call_status'] == 'callend') {
					if ($modelCaller->tbCounterservice) {
						return 'กำลังเรียก (ห้องตรวจ) ' . $modelCaller->tbCounterservice->counterservice_name;
					} else {
						return 'กำลังเรียก (ห้องตรวจ) ';
					}
				}
			}
		} elseif ($modelQTran['service_status_id'] == 3) {//พักคิว
			$modelCaller = TbCaller::findOne(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']]);
			if (!$modelCaller) {
				return '-';
			}
			if ($modelQ['servicegroupid'] == 1) {//ลงทะเบียน
				if ($modelCaller['call_status'] == 'hold') {
					if ($modelCaller->tbCounterservice) {
						return 'พักคิว (จุดลงทะเบียน) ' . $modelCaller->tbCounterservice->counterservice_name;
					} else {
						return 'พักคิว (จุดลงทะเบียน) ';
					}
				}
			}
			if ($modelQ['servicegroupid'] == 2 && empty($modelQTran['counter_service_id'])) {//
				if ($modelCaller['call_status'] == 'hold') {
					if ($modelCaller->tbCounterservice) {
						return 'พักคิว (ซักประวัติ) ' . $modelCaller->tbCounterservice->counterservice_name;
					} else {
						return 'พักคิว (ซักประวัติ) ';
					}
				}
			}
			if ($modelQ['servicegroupid'] == 2 && !empty($modelQTran['counter_service_id'])) {//
				$modelCaller = TbCaller::find()->where(['qtran_ids' => $modelQTran['ids'], 'q_ids' => $modelQ['q_ids']])->orderBy('caller_ids DESC')->one();
				if (!$modelCaller) {
					return '-';
				}
				if ($modelCaller['call_status'] == 'hold') {
					if ($modelCaller->tbCounterservice) {
						return 'พักคิว (ห้องตรวจ) ' . $modelCaller->tbCounterservice->counterservice_name;
					} else {
						return 'พักคิว (ห้องตรวจ) ';
					}
				}
			}
		} elseif ($modelQTran['service_status_id'] == 4) {//
			if ($modelQ['servicegroupid'] == 1) {//ลงทะเบียน
				return 'เสร็จสิ้น (จุดลงทะเบียน)';
			}
			if ($modelQ['servicegroupid'] == 2 && empty($modelQTran['counter_service_id'])) {//
				return 'เสร็จสิ้น (ซักประวัติ)';
			}
			if ($modelQ['servicegroupid'] == 2 && !empty($modelQTran['counter_service_id'])) {//
				return 'รอเรียก (ห้องตรวจ)';
			}
		} elseif ($modelQTran['service_status_id'] == 10) {
			if ($modelQ['servicegroupid'] == 1) {//ลงทะเบียน
				return 'เสร็จสิ้น (จุดลงทะเบียน)';
			}
			if ($modelQ['servicegroupid'] == 2) {
				return 'เสร็จสิ้น (ห้องตรวจ)';
			}
		}
	}

	public function actionPrintTicket($id)
	{
		$model = $this->findModelQuequ($id);
		$service = $this->findModelService($model['serviceid']);
		$ticket = $this->findModelTicket($service['prn_profileid']);
		$y = \Yii::$app->formatter->asDate('now', 'php:Y');
		$sql = 'SELECT
                Count(tb_quequ.q_ids) as count
                FROM
                tb_quequ
                INNER JOIN tb_qtrans ON tb_qtrans.q_ids = tb_quequ.q_ids
                WHERE
                tb_quequ.servicegroupid = :servicegroupid AND
                tb_quequ.q_ids < :q_ids AND
                tb_qtrans.service_status_id = 1';
		$params = [':servicegroupid' => $model['servicegroupid'], ':q_ids' => $id];
		$count = Yii::$app->db->createCommand($sql)
			->bindValues($params)
			->queryScalar();
		$template = strtr($ticket->template, [
			'{hos_name_th}' => $ticket->hos_name_th,
			'{q_hn}' => $model->q_hn,
			'{pt_name}' => $model->pt_name,
			'{q_num}' => $model->q_num,
			'{pt_visit_type}' => '',
			'{sec_name}' => '',
			'{time}' => \Yii::$app->formatter->asDate('now', 'php:d M ' . substr($y, 2)) . ' ' . \Yii::$app->formatter->asDate('now', 'php:H:i'),
			'{user_print}' => Yii::$app->user->identity->profile->name,
			'{qwaiting}' => $count,
			'/img/logo/logoBBH.png' => $ticket->logo_path ? $ticket->logo_base_url . '/' . $ticket->logo_path : '/img/logo/logoBBH.png'
		]);
		return $this->renderAjax('print-ticket', [
			'model' => $model,
			'ticket' => $ticket,
			'template' => $template,
			'service' => $service
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
					'title' => 'แก้ไขรายการคิว #' . $model['q_num'],
					'content' => $this->renderAjax('_form_queue', [
						'model' => $model,
					]),
					'footer' => ''
				];
			} elseif ($model->load($request->post())) {
				$data = $request->post('TbQuequ', []);
				if ($serviceid != $data['serviceid']) {
					$service = TbService::findOne($data['serviceid']);
					$model->q_num = $model->genQnum($service);
				}
				if ($model->save()) {
					return [
						'title' => 'แก้ไขรายการคิว #' . $model['q_num'],
						'content' => '<span class="text-success">บันทึกสำเร็จ!</span>',
						'footer' => Html::button('Close', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]),
						'status' => '200',
						'model' => $model,
					];
				} else {
					return [
						'title' => 'แก้ไขรายการคิว #' . $model['q_num'],
						'content' => $this->renderAjax('_form_queue', [
							'model' => $model,
						]),
						'footer' => ''
					];
				}
			} else {
				return [
					'title' => 'แก้ไขรายการคิว #' . $model['q_num'],
					'content' => $this->renderAjax('_form_queue', [
						'model' => $model,
					]),
					'footer' => ''
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
		// ชื่อบริการ
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
				'tb_servicegroup.servicegroup_name'
			])
			->from('tb_service')
			->innerJoin('tb_servicegroup', 'tb_service.service_groupid = tb_servicegroup.servicegroupid')
			->all();
		// เคาน์เตอร์
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
				'tb_counterservice_type.counterservice_type'
			])
			->from('tb_counterservice')
			->innerJoin('tb_counterservice_type', 'tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid')
			->all();
		return [
			'services' => $services,
			'counters' => $counters
		];
	}
}

<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\filters\AccessControl;
use frontend\modules\app\models\CallingForm;
use frontend\modules\app\models\TbServiceProfile;
use frontend\modules\app\traits\ModelTrait;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbSoundStation;
use frontend\modules\app\models\TbServicegroup;
use frontend\modules\app\models\TbCounterservice;
use frontend\modules\app\models\TbQuequ;
use frontend\modules\app\models\LabItems;
use frontend\modules\app\models\TbQtrans;
use frontend\modules\app\models\QueuesInterface;
use frontend\modules\app\models\QueuesInterfaceSearch;
use frontend\modules\app\models\TbDoctor;
use frontend\modules\app\models\TbDoctorStatus;
use yii\web\Response;
use kartik\form\ActiveForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * MobileController implements the CRUD actions for TbQuequ model.
 */
class MobiletestController extends Controller
{
    use ModelTrait;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['play-sound', 'autoload-media', 'update-status'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'play-sound',
                            'autoload-media',
                            'update-status',
                            'save-doctorstatus',
                        ],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TbQuequ models.
     * @return mixed
     */
    public function actionFindStatus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = (new \yii\db\Query())
            ->select(['count(tb_quequ.q_num) as qnumber'])
            ->from('tb_quequ')
            ->where(['tb_quequ.q_status_id' => 1])
            ->all(\Yii::$app->db);
        return $this->asJson($query);
    }

    public function timeToSecond()
    {
        $time = date('H:i:s');
        list($h, $m, $s) = explode(':', $time);
        return $h * 3600 + $m * 60 + $s;
    }

    public function actionSaveDocStatus()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            $doctorStatus = TbDoctor::find()
                ->where(['Doctor_name' => $request->post('Doctor_name')])
                ->one();
            $doctorStatus->Status = $request->post('Status');
            if ($doctorStatus->save()) {
                $transaction->commit();
                return [
                    'status' => '200',
                    'message' => 'success',
                ];
            } else {
                $transaction->rollBack();
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($doctorStatus),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
        // return 'test';
    }

    public function actionIndex($profileid = null, $counterid = null)
    {
        $modelForm = new CallingForm();
        $modelProfile = new TbServiceProfile();
        $modelForm->service_profile = $profileid;
        $modelForm->counter_service = $counterid;
        if ($profileid != null) {
            $modelProfile = $this->findModelServiceProfile($profileid);
        }
        $formData = $modelForm;
        $profileData = $modelProfile;
        $services = isset($profileData['service_id'])
            ? explode(',', $profileData['service_id'])
            : null;
        $query = (new \yii\db\Query())
            ->select([
                'tb_qtrans.ids',
                'tb_qtrans.q_ids',
                'tb_qtrans.counter_service_id',
                'DATE_FORMAT(SEC_TO_TIME(tb_quequ.q_appoint_time),\'%H:%i\') as checkin_date',
                'tb_qtrans.service_status_id',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.q_vn as VN',
                'tb_quequ.pt_visit_type_id',
                'tb_quequ.pt_name',
                'tb_quequ.q_status_id',
                'tb_quequ.servicegroupid',
                'tb_counterservice.counterservice_name',
                'tb_service_status.service_status_name',
                'tb_service.service_name',
                'tb_service.serviceid',
                'tb_service.service_prefix',
                'tb_quequ.q_appoint_time as appoint_time',
                'tb_doctor.doctor_name',
                'tb_doctor_status.ID',
                'tb_doctor_status.Status_T',
            ])
            ->from('tb_qtrans')
            ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin(
                'tb_counterservice',
                'tb_counterservice.counterserviceid = tb_qtrans.counter_service_id'
            )
            ->leftJoin(
                'tb_service_status',
                'tb_service_status.service_status_id = tb_qtrans.service_status_id'
            )
            ->leftJoin(
                'tb_service',
                'tb_service.serviceid = tb_quequ.serviceid'
            )
            ->leftJoin('tb_doctor', 'tb_doctor.doc_id = tb_quequ.doctor_id')
            ->leftJoin(
                'tb_doctor_status',
                'tb_doctor_status.ID = tb_doctor.status'
            )
            ->where([
                'tb_quequ.serviceid' => $services,
                'tb_qtrans.counter_service_id' => $formData['counter_service'],
                'tb_qtrans.service_status_id' => [2, 4],
            ])
            // ->andWhere([
            //     'between',
            //     'tb_quequ.q_appoint_time',
            //     intval($this->timeToSecond()),
            //     intval($this->timeToSecond()) + 3600,
            // ])
            // ->orWhere(['tb_qtrans.service_status_id' => 2])
            // ->andWhere('DATE(tb_quequ.created_at) = DATE(NOW())')
            ->orderBy('tb_counterservice.counterserviceid,checkin_date  ASC');
        // ->all();
        $second1 = intval($this->timeToSecond());
        $second2 = intval($this->timeToSecond() + 3600);
        $queryall = (new \yii\db\Query())
            ->select([
                'tb_qtrans.ids',
                'tb_qtrans.q_ids',
                'tb_qtrans.counter_service_id',
                'DATE_FORMAT(SEC_TO_TIME(tb_quequ.q_appoint_time),\'%H:%i\') as checkin_date',
                'tb_qtrans.service_status_id',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.q_vn as VN',
                'tb_quequ.pt_visit_type_id',
                'tb_quequ.pt_name',
                'tb_quequ.servicegroupid',
                'tb_counterservice.counterservice_name',
                'tb_service_status.service_status_name',
                'tb_service.service_name',
                'tb_service.serviceid',
                'tb_service.service_prefix',
                'tb_quequ.q_appoint_time as appoint_time',
                'tb_doctor.doctor_name',
                'tb_doctor_status.Status_T',
            ])
            ->from('tb_qtrans')
            ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin(
                'tb_counterservice',
                'tb_counterservice.counterserviceid = tb_qtrans.counter_service_id'
            )
            ->leftJoin(
                'tb_service_status',
                'tb_service_status.service_status_id = tb_qtrans.service_status_id'
            )
            ->leftJoin(
                'tb_service',
                'tb_service.serviceid = tb_quequ.serviceid'
            )
            ->leftJoin('tb_doctor', 'tb_doctor.doc_id = tb_quequ.doctor_id')
            ->leftJoin(
                'tb_doctor_status',
                'tb_doctor_status.ID = tb_doctor.status'
            )
            ->where([
                'tb_quequ.serviceid' => $services,
                'tb_qtrans.counter_service_id' => $formData['counter_service'],
                'tb_qtrans.service_status_id' => [2, 4],
            ])
            // ->andWhere([
            //     'between',
            //     'tb_quequ.q_appoint_time',
            //     intval($this->timeToSecond()),
            //     intval($this->timeToSecond()) + 3600,
            // ])
            // ->orWhere(['tb_qtrans.service_status_id' => 2])
            // ->andWhere('DATE(tb_quequ.created_at) = DATE(NOW())')
            ->groupBy(['tb_doctor.doctor_name'])
            ->orderBy('tb_counterservice.counterserviceid,checkin_date  ASC')
            ->all();

        $dataProvidercall = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $queryhold = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                // 'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%H:%i:%s\') as checkin_date',
                'DATE_FORMAT(SEC_TO_TIME(tb_quequ.q_appoint_time),\'%H:%i\') as checkin_date',
                'tb_caller.servicegroupid',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.q_vn as VN',
                'tb_quequ.pt_name',
                'tb_quequ.pt_visit_type_id',
                'tb_service_status.service_status_name',
                'tb_counterservice.counterservice_name',
                'tb_service.service_name',
                'tb_service.serviceid',
                'tb_service.service_prefix',
                'tb_doctor.doctor_name',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans', 'tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin(
                'tb_service_status',
                'tb_service_status.service_status_id = tb_qtrans.service_status_id'
            )
            ->innerJoin(
                'tb_counterservice',
                'tb_counterservice.counterserviceid = tb_caller.counter_service_id'
            )
            ->leftJoin(
                'tb_service',
                'tb_service.serviceid = tb_quequ.serviceid'
            )
            ->leftJoin('tb_doctor', 'tb_doctor.doc_id = tb_quequ.doctor_id')
            ->where([
                'tb_quequ.serviceid' => $services,
                'tb_caller.counter_service_id' => $formData['counter_service'],
                'tb_caller.call_status' => 'hold',
            ])
            // ->andWhere([
            //     'between',
            //     'tb_quequ.q_appoint_time',
            //     intval($this->timeToSecond()),
            //     intval($this->timeToSecond()) + 3600,
            // ])
            ->andWhere('DATE(tb_quequ.created_at) = DATE(NOW())')
            ->orderBy('tb_quequ.q_appoint_time ASC');

        $dataProviderhold = new ActiveDataProvider([
            'query' => $queryhold,
            'pagination' => false,
        ]);

        $queryend = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                // 'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%H:%i:%s\') as checkin_date',
                'DATE_FORMAT(SEC_TO_TIME(tb_quequ.q_appoint_time),\'%H:%i\') as checkin_date',
                'tb_caller.servicegroupid',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.q_vn as VN',
                'tb_quequ.pt_name',
                'tb_quequ.pt_visit_type_id',
                'tb_service_status.service_status_name',
                'tb_counterservice.counterservice_name',
                'tb_service.service_name',
                'tb_service.serviceid',
                'tb_service.service_prefix',
                'tb_doctor.doctor_name',
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans', 'tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin(
                'tb_service_status',
                'tb_service_status.service_status_id = tb_qtrans.service_status_id'
            )
            ->innerJoin(
                'tb_counterservice',
                'tb_counterservice.counterserviceid = tb_caller.counter_service_id'
            )
            ->leftJoin(
                'tb_service',
                'tb_service.serviceid = tb_quequ.serviceid'
            )
            ->leftJoin('tb_doctor', 'tb_doctor.doc_id = tb_quequ.doctor_id')
            ->where([
                'tb_quequ.serviceid' => $services,
                'tb_caller.counter_service_id' => $formData['counter_service'],
                'tb_caller.call_status' => 'end',
            ])
            // ->andWhere([
            //     'between',
            //     'tb_quequ.q_appoint_time',
            //     intval($this->timeToSecond()),
            //     intval($this->timeToSecond()) + 3600,
            // ])
            ->andWhere('DATE(tb_quequ.created_at) = DATE(NOW())')
            ->orderBy('tb_quequ.q_appoint_time ASC');

        $dataProviderend = new ActiveDataProvider([
            'query' => $queryend,
            'pagination' => false,
        ]);
        $doctorStatus = TbDoctorStatus::find()
            ->orderBy('ID')
            ->asArray()
            ->all();
        // $this->view->title = 'QueueList';
        return $this->render('index', [
            'listDataProvider' => $dataProvidercall,
            'listHoldProvider' => $dataProviderhold,
            'listEndProvider' => $dataProviderend,
            'modelFormdoctor' => $modelForm,
            'modelProfiledoctor' => $modelProfile,
            'modelForm' => $modelForm,
            'modelProfile' => $modelProfile,
            'doctorStatus' => $doctorStatus,
            'doctor' => $queryall,
            'second1' => $second1,
            'second2' => $second2,
        ]);

        // $searchModel = new TbQuequSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);
    }

    protected function findModel($id)
    {
        if (($model = TbQuequ::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionFindIds()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $counterid = $_POST['counterid'];
        $query = (new \yii\db\Query())
            ->select([
                'tb_quequ.q_ids',
                'tb_qtrans.ids',
                'tb_caller.caller_ids',
                'tb_quequ.q_num as qnumber',
                'tb_quequ.pt_name',
                'tb_quequ.counterserviceid as counter_service_id',
                'tb_quequ.serviceid',
                'tb_quequ.q_status_id',
                'tb_caller.call_status',
            ])
            ->from('tb_quequ')
            ->leftJoin('tb_caller', 'tb_quequ.q_ids = tb_caller.q_ids')
            ->leftJoin('tb_qtrans', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->where([
                // 'tb_quequ.q_status_id' => 1,
                'tb_quequ.q_ids' => $counterid,
            ])
            ->orderBy(['pt_visit_type_id' => SORT_DESC])
            ->limit(1)
            ->all(\Yii::$app->db);
        // $tbqueue = TbQuequ::find()->where(['counterserviceid' => null])->orderBy(['q_ids' => SORT_ASC])->one();
        return $this->asJson($query);
    }

    public function actionCallExaminationRoom()
    {
        // return "TEST";
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $data = $request->post('data', []);
                $dataForm = $request->post('modelForm', []);
                $dataProfile = $request->post('modelProfile', []);
                $modelProfile = $this->findModelServiceProfile(
                    $dataProfile['service_profile_id']
                );
                $counter = $this->findModelCounterservice(
                    $request->post('value')
                );
                $modelQ = $this->findModelQuequ($data['q_ids']);

                $model = new TbCaller();
                $modelQ->q_status_id = 2;
                $model->q_ids = $data['q_ids'];
                $model->qtran_ids = $data['ids'];
                //$model->servicegroupid = $modelProfile['service_groupid'];
                $model->counter_service_id = $request->post('value');
                $model->call_timestp = new Expression('NOW()');
                $model->call_status = TbCaller::STATUS_CALLING;

                $modelTrans = $this->findModelQTrans($data['ids']);
                //$modelTrans->counter_service_id = $dataForm['counter_service'];
                //$modelTrans->servicegroupid = $modelProfile['service_groupid'];
                $modelTrans->service_status_id = 2;
                if ($model->save() && $modelTrans->save() && $modelQ->save()) {
                    $transaction->commit();
                    return [
                        'status' => '200',
                        'message' => 'success',
                        'sound' => $this->getMediaSound(
                            $modelQ['q_num'],
                            $request->post('value')
                        ),
                        'data' => $data,
                        'model' => $model,
                        'modelQ' => $modelQ,
                        'modelProfile' => $modelProfile,
                        'counter' => $counter,
                        'eventOn' => 'tb-waiting',
                        'state' => 'call',
                    ];
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => '500',
                        'message' => 'error',
                        'validate' => ActiveForm::validate($model),
                    ];
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionEndWaitExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $data = $request->post('data', []);
                $dataForm = $request->post('modelForm', []);
                $dataProfile = $request->post('modelProfile', []);
                $modelProfile = $this->findModelServiceProfile(
                    $dataProfile['service_profile_id']
                );
                $counter = $this->findModelCounterservice(
                    $request->post('value')
                );
                $modelQ = $this->findModelQuequ($data['q_ids']);

                $model = new TbCaller();
                $model->q_ids = $data['q_ids'];
                $model->qtran_ids = $data['ids'];
                //$model->servicegroupid = $modelProfile['service_groupid'];
                $model->counter_service_id = $request->post('value');
                $model->call_timestp = new Expression('NOW()');
                $model->call_status = TbCaller::STATUS_CALLEND;

                $modelTrans = $this->findModelQTrans($data['ids']);
                //$modelTrans->counter_service_id = $dataForm['counter_service'];
                //$modelTrans->servicegroupid = $modelProfile['service_groupid'];
                $modelTrans->service_status_id = 10;

                if ($model->save() && $modelTrans->save()) {
                    $transaction->commit();
                    return [
                        'status' => '200',
                        'message' => 'success',
                        'sound' => $this->getMediaSound(
                            $modelQ['q_num'],
                            $request->post('value')
                        ),
                        'data' => $data,
                        'model' => $model,
                        'modelQ' => $modelQ,
                        'modelProfile' => $modelProfile,
                        'counter' => $counter,
                        'eventOn' => 'tb-waiting',
                        'state' => 'end',
                    ];
                } else {
                    $transaction->rollBack();
                    return [
                        'status' => '500',
                        'message' => 'error',
                        'validate' => ActiveForm::validate($model),
                    ];
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionRecallExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data', []);
            $dataForm = $request->post('modelForm', []);
            $dataProfile = $request->post('modelProfile', []);
            $modelProfile = $this->findModelServiceProfile(
                $dataProfile['service_profile_id']
            );
            $counter = $this->findModelCounterservice(
                $dataForm['counter_service']
            );
            $modelQ = $this->findModelQuequ($data['q_ids']);

            $model = $this->findModelCaller($data['caller_ids']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if ($model->save()) {
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound(
                        $modelQ['q_num'],
                        $model['counter_service_id']
                    ),
                    'data' => $data,
                    'model' => $model,
                    'modelQ' => $modelQ,
                    'modelProfile' => $modelProfile,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'recall',
                ];
            } else {
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionHoldExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data', []);
            $dataForm = $request->post('modelForm', []);
            $dataProfile = $request->post('modelProfile', []);
            $modelProfile = $this->findModelServiceProfile(
                $dataProfile['service_profile_id']
            );
            $counter = $this->findModelCounterservice(
                $dataForm['counter_service']
            );

            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQTrans($model['qtran_ids']);
            $modelQtran->service_status_id = 3;
            $model->call_status = TbCaller::STATUS_HOLD;
            if ($model->save() && $modelQtran->save()) {
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'modelProfile' => $modelProfile,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'hold',
                ];
            } else {
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionCallholdExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data', []);
            $dataForm = $request->post('modelForm', []);
            $dataProfile = $request->post('modelProfile', []);
            $modelProfile = $this->findModelServiceProfile(
                $dataProfile['service_profile_id']
            );
            $counter = $this->findModelCounterservice(
                $dataForm['counter_service']
            );
            $modelQ = $this->findModelQuequ($data['q_ids']);

            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQTrans($model['qtran_ids']);
            $modelQtran->service_status_id = 2;
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if ($model->save() && $modelQtran->save()) {
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound(
                        $modelQ['q_num'],
                        $model['counter_service_id']
                    ),
                    'data' => $data,
                    'model' => $model,
                    'modelQ' => $modelQ,
                    'modelProfile' => $modelProfile,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'call-hold',
                ];
            } else {
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionEndholdExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data', []);
            $dataForm = $request->post('modelForm', []);
            $dataProfile = $request->post('modelProfile', []);
            $modelProfile = $this->findModelServiceProfile(
                $dataProfile['service_profile_id']
            );
            $counter = $this->findModelCounterservice(
                $dataForm['counter_service']
            );

            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQTrans($model['qtran_ids']);
            $modelQ = $this->findModelQuequ($modelQtran['q_ids']);
            $modelQtran->service_status_id = 10;
            $modelQtran->checkout_date = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_END;
            if ($model->save() && $modelQtran->save()) {
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'modelQ' => $modelQ,
                    'modelProfile' => $modelProfile,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'end-hold',
                ];
            } else {
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($modelQtran),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionEndExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data', []);
            $dataForm = $request->post('modelForm', []);
            $dataProfile = $request->post('modelProfile', []);
            $modelProfile = $this->findModelServiceProfile(
                $dataProfile['service_profile_id']
            );
            $counter = $this->findModelCounterservice(
                $dataForm['counter_service']
            );

            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQTrans($model['qtran_ids']);
            $modelQ = $this->findModelQuequ($modelQtran['q_ids']);
            $modelQtran->service_status_id = 10;
            $modelQtran->checkout_date = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_END;
            if ($model->save() && $modelQtran->save()) {
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'modelQ' => $modelQ,
                    'modelProfile' => $modelProfile,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'end-hold',
                ];
            } else {
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($modelQtran),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function actionTransferExaminationRoom()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data', []);
            $dataForm = $request->post('modelForm', []);
            $dataProfile = $request->post('modelProfile', []);
            $modelProfile = $this->findModelServiceProfile(
                $dataProfile['service_profile_id']
            );
            $counter = $this->findModelCounterservice(
                $dataForm['counter_service']
            );
            $modelQ = $this->findModelQuequ($data['q_ids']);
            $model = $this->findModelCaller($data['caller_ids']);
            $model->counter_service_id = $request->post('value');
            $modelQtran = $this->findModelQTrans($model['qtran_ids']);
            $modelQtran->counter_service_id = $request->post('value');
            $modelQtran->service_status_id = 4;
            // $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_FINISHED;
            if ($model->save() && $modelQtran->update()) {
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound(
                        $modelQ['q_num'],
                        $model['counter_service_id']
                    ),
                    'data' => $data,
                    'model' => $model,
                    'modelQ' => $modelQ,
                    'modelProfile' => $modelProfile,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'transfer',
                ];
            } else {
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model),
                ];
            }
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }

    public function getMediaSound($qnum, $id)
    {
        $qnum = str_split($qnum);
        $counter = $this->findModelCounterservice($id);
        $modelSound = $counter->tbSound;
        //$counterType = $counter->counterserviceType;
        //$counterSound = $counterType->tbSound;
        $servicesound = $counter->soundService;
        $basePath = '/media/' . $modelSound['sound_path_name'];
        $begin = [$basePath . '/please.wav']; //เชิญหมายเลข
        $end = [
            '/media/' .
            $servicesound['sound_path_name'] .
            '/' .
            $servicesound['sound_name'],
            $basePath . '/' . $modelSound['sound_name'],
            $basePath . '/' . $modelSound['sound_path_name'] . '_Sir.wav',
        ];

        $sound = array_map(function ($num) use ($basePath, $modelSound) {
            return $basePath .
                '/' .
                $modelSound['sound_path_name'] .
                '_' .
                $num .
                '.wav';
        }, $qnum);
        $sound = ArrayHelper::merge($begin, $sound);
        $sound = ArrayHelper::merge($sound, $end);
        return $sound;
    }

    public function actionDataTbholdEx()
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $formData = $request->post('modelForm', []);
            $profileData = $request->post('modelProfile', []);
            $services = isset($profileData['service_id'])
                ? explode(',', $profileData['service_id'])
                : null;
            $labItems = $this->findLabs();

            $query = (new \yii\db\Query())
                ->select([
                    'tb_caller.caller_ids',
                    'tb_caller.q_ids',
                    'tb_caller.qtran_ids',
                    // 'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%H:%i:%s\') as checkin_date',
                    'DATE_FORMAT(SEC_TO_TIME(tb_quequ.q_appoint_time),\'%H:%i\') as checkin_date',
                    'tb_caller.servicegroupid',
                    'tb_caller.counter_service_id',
                    'tb_caller.call_timestp',
                    'tb_quequ.q_num',
                    'tb_quequ.q_hn',
                    'tb_quequ.q_vn as VN',
                    'tb_quequ.pt_name',
                    'tb_service_status.service_status_name',
                    'tb_counterservice.counterservice_name',
                    'tb_service.service_name',
                    'tb_service.serviceid',
                    'tb_service.service_prefix',
                ])
                ->from('tb_caller')
                ->innerJoin('tb_qtrans', 'tb_qtrans.ids = tb_caller.qtran_ids')
                ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
                ->innerJoin(
                    'tb_service_status',
                    'tb_service_status.service_status_id = tb_qtrans.service_status_id'
                )
                ->innerJoin(
                    'tb_counterservice',
                    'tb_counterservice.counterserviceid = tb_caller.counter_service_id'
                )
                ->leftJoin(
                    'tb_service',
                    'tb_service.serviceid = tb_quequ.serviceid'
                )
                ->where([
                    'tb_quequ.serviceid' => $services,
                    'tb_caller.counter_service_id' =>
                        $formData['counter_service'],
                    'tb_caller.call_status' => 'hold',
                ])
                ->andWhere('DATE(tb_quequ.created_at) = DATE(NOW())')
                ->orderBy('tb_quequ.q_appoint_time ASC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids',
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::class,
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    // [
                    //     'attribute' => 'caller_ids',
                    // ],
                    // [
                    //     'attribute' => 'q_ids',
                    // ],
                    [
                        'attribute' => 'q_num',
                        'value' => function ($model, $key, $index, $column) {
                            return \kartik\helpers\Html::badge(
                                $model['q_num'],
                                [
                                    'class' => 'badge',
                                    'style' =>
                                        'background-color:#FF4500;font-size: 16px;',
                                ]
                            );
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'q_hn',
                    ],
                    [
                        'attribute' => 'VN',
                    ],
                    [
                        'attribute' => 'pt_name',
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    // [
                    //     'attribute' => 'checkin_date',
                    // ],
                    // [
                    //     'attribute' => 'counterservice_name',
                    // ],
                    // [
                    //     'attribute' => 'qnumber',
                    //     'value' => function ($model, $key, $index, $column) {
                    //         return $model['q_num'];
                    //     },
                    // ],
                    // [
                    //     'attribute' => 'service_status_name',
                    //     'value' => function ($model, $key, $index, $column) {
                    //         return \kartik\helpers\Html::badge(Icon::show('hourglass-3') . ' ' . $model['service_status_name'], ['class' => 'badge']);
                    //     },
                    //     'format' => 'raw'
                    // ],
                    // [
                    //     'attribute' => 'service_name',
                    // ],
                    // [
                    //     'attribute' => 'serviceid',
                    // ],
                    // [
                    //     'attribute' => 'service_prefix',
                    // ],
                    // [
                    //     'attribute' => 'lab',
                    //     'value' => function ($model) {
                    //         $lab = QueuesInterface::find()->where(['VN' => $model['VN']])->one();
                    //         if ($lab['lab'] === 'ผลออกครบ') {
                    //             return \kartik\helpers\Html::badge($lab['lab'], ['class' => 'badge', 'style' => 'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;']);
                    //         } else if ($lab['lab'] === 'รอผล') {
                    //             return \kartik\helpers\Html::badge($lab['lab'], ['class' => 'badge', 'style' => 'background-color:orange;color:#ffffff;text-align:center;font-size:16px;']);
                    //         } else {
                    //             return $lab['lab'];
                    //         }
                    //     },
                    //     'format' => 'raw'
                    // ],
                    // [
                    //     'attribute' => 'xray',
                    //     'value' => function ($model) {
                    //         $lab = QueuesInterface::find()->where(['VN' => $model['VN']])->one();
                    //         if ($lab['xray'] === 'ผลออกครบ') {
                    //             return \kartik\helpers\Html::badge($lab['xray'], ['class' => 'badge', 'style' => 'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;']);
                    //         } else if ($lab['xray'] === 'รอผล') {
                    //             return \kartik\helpers\Html::badge($lab['xray'], ['class' => 'badge', 'style' => 'background-color:orange;color:#ffffff;text-align:center;font-size:16px;']);
                    //         } else {
                    //             return $lab['xray'];
                    //         }
                    //     },
                    //     'format' => 'raw'
                    // ],
                    // [
                    //     'attribute' => 'SP',
                    //     'value' => function ($model) {
                    //         $lab = QueuesInterface::find()->where(['VN' => $model['VN']])->one();
                    //         if ($lab['SP'] === 'ผลออกครบ') {
                    //             return \kartik\helpers\Html::badge($lab['SP'], ['class' => 'badge', 'style' => 'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;']);
                    //         } else if ($lab['SP'] === 'รอผล') {
                    //             return \kartik\helpers\Html::badge($lab['SP'], ['class' => 'badge', 'style' => 'background-color:orange;color:#ffffff;text-align:center;font-size:16px;']);
                    //         } else {
                    //             return $lab['SP'];
                    //         }
                    //     },
                    //     'format' => 'raw'
                    // ],
                    // [
                    //     'attribute' => 'lab_confirm',
                    //     'value' => function ($model, $key, $index, $column) use ($labItems) {
                    //         $confirm = $this->checkLab($model['q_hn'], $labItems);
                    //         if ($confirm == 'N') {
                    //             return Html::tag('span', 'ยังไม่เสร็จ', ['class' => 'text-warning']);
                    //         } elseif ($confirm == 'Y') {
                    //             return Html::tag('span', 'เสร็จแล้ว', ['class' => 'text-success']);
                    //         } else {
                    //             return Html::tag('span', 'ไม่มี Lab', ['class' => 'text-danger']);
                    //         }
                    //     },
                    //     'format' => 'raw'
                    // ],
                    [
                        'class' => ActionTable::class,
                        'template' => '{call} {end} {transfer}',
                        'buttons' => [
                            'call' => function ($url, $model, $key) {
                                return Html::a('เรียกคิว', $url, [
                                    'class' => 'btn btn-success btn-calling',
                                ]);
                            },
                            'end' => function ($url, $model, $key) {
                                return Html::a('เสร็จสิ้น', $url, [
                                    'class' => 'btn btn-danger btn-end',
                                ]);
                            },
                            'transfer' => function ($url, $model, $key) {
                                return Html::a('Transfer', $url, [
                                    'class' => 'btn btn-primary btn-transfer',
                                ]);
                            },
                        ],
                    ],
                ],
            ]);

            return ['data' => $columns->renderDataColumns()];
        } else {
            throw new NotFoundHttpException(
                'Invalid request. Please do not repeat this request again.'
            );
        }
    }
}

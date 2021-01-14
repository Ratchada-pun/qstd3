<?php

namespace frontend\modules\kiosk\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\kiosk\models\CallingForm;
use yii\web\Response;
use homer\widgets\tbcolumn\ActionTable;
use homer\widgets\tbcolumn\ColumnTable;
use homer\widgets\tbcolumn\ColumnData;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\icons\Icon;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use frontend\modules\kiosk\models\TbSection;
use frontend\modules\kiosk\models\TbCaller;
use frontend\modules\kiosk\models\TbQtrans;
use frontend\modules\kiosk\models\TbCounterservice;
use frontend\modules\kiosk\models\TbQuequ;
use frontend\modules\kiosk\models\TbCounterserviceType;

class CallingController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['play-sound', 'autoload-media'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['play-sound', 'autoload-media'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actionScreeningRoom($secid = null,$counterid = null)
    {
        $session = Yii::$app->session;
        $keySecid = 'calling-secid';
        $keyCounter = 'calling-counterid'; 

        $model = new CallingForm();
        #set sessions
        if (($session->get($keySecid) != $secid)  && ($secid != null)){
            $session->set($keySecid, $secid);
        }elseif($secid == null && !$session->has($keySecid)){
            $session->remove($keySecid);
        }
        if($counterid !== null){
            $session->set($keyCounter, $counterid);
        }
        $model->section = $session->get($keySecid);
        $model->counter = $session->get($keyCounter);

        return $this->render('screening-room',[
            'model' => $model,
        ]);
    }

    public function actionExaminationRoom($secid = null,$counterid = null)
    {
        $session = Yii::$app->session;
        $keySecid = 'calling-examination-secid';
        $keyCounter = 'counter-examination-value';

        $model = new CallingForm();
        #set sessions
        if (($session->get($keySecid) != $secid)  && ($secid != null)){
            $session->set($keySecid, $secid);
        }elseif($secid == null && !$session->has($keySecid)){
            $session->remove($keySecid);
        }
        if($counterid !== null){
            $session->set($keyCounter, $counterid);
        }
        $model->section = $session->get($keySecid);
        $model->counter = $session->get($keyCounter);

        return $this->render('examination-room',[
            'model' => $model,
        ]);
    }

    public function actionBlooddrillRoom($secid = null,$counterid = null)
    {
        $session = Yii::$app->session;
        $keySecid = 'calling-blooddrill-secid';
        $keyCounter = 'counter-blooddrill-value';

        $model = new CallingForm();
        #set sessions
        if (($session->get($keySecid) != $secid)  && ($secid != null)){
            $session->set($keySecid, $secid);
        }elseif($secid == null && !$session->has($keySecid)){
            $session->remove($keySecid);
        }
        if($counterid !== null){
            $session->set($keyCounter, $counterid);
        }
        $model->section = $secid;
        $model->counter = $session->get($keyCounter);

        return $this->render('blooddrill-room',[
            'model' => $model,
        ]);
    }

    public function actionDataTbwaitingSr(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->get('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_qtrans.ids',
                'tb_qtrans.q_ids',
                'tb_qtrans.counter_service_id',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_qtrans.service_status_id',
                'tb_pt_visit_type.pt_visit_type',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name'
            ])
            ->from('tb_qtrans')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->leftJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->where(['tb_qtrans.service_status_id' => $section['sec_firststatus'],'tb_qtrans.service_sec_id' => $request->get('secid')])
            ->orderBy('checkin_date DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{call}',
                        'buttons' => [
                            'call' => function($url, $model, $key){
                                return Html::a('CALL',$url,['class' => 'btn btn-success btn-calling']);
                            },
                        ],
                        /*'urlCreator' => function ( $action, $model, $key, $index) {
                            if($action == 'update'){
                                return Url::to(['/settings/default/create-visit-type']);
                            }
                        }*/
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbwaitingEx(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->post('secid'));
            $service = TbCounterservice::find()->where(['counterserviceid' => $request->post('counter')])->all();
            $types = ArrayHelper::getColumn($service, 'counterservice_type');
            $counterType = TbCounterserviceType::find()->where(['tb_counterservice_typeid' => $types])->all();
            $waitingStatus = ArrayHelper::getColumn($counterType, 'q_waiting_status');
            $query = (new \yii\db\Query())
            ->select([
                'tb_qtrans.ids',
                'tb_qtrans.q_ids',
                'tb_qtrans.counter_service_id',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_qtrans.service_status_id',
                'tb_pt_visit_type.pt_visit_type',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_counterservice.counterservice_name',
                'tb_counterservice_type.q_waiting_status',
                'tb_counterservice_type.q_calling_status'
            ])
            ->from('tb_qtrans')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->leftJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_qtrans.counter_service_id')
            ->innerJoin('tb_counterservice_type','tb_counterservice_type.tb_counterservice_typeid = tb_counterservice.counterservice_type')
            ->where([
                'tb_qtrans.service_status_id' => $waitingStatus,
                'tb_qtrans.counter_service_id' => $request->post('counter'),
                'tb_qtrans.service_sec_id' => $request->post('secid')
            ])
            ->orderBy('checkin_date DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'format' => 'raw',
                        'value' => function($model,$key,$index,$column){
                            return Icon::show('hourglass-3').' '.$model['service_status_name'];
                        },
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{call}',
                        'buttons' => [
                            'call' => function($url, $model, $key){
                                return Html::a('CALL',$url,['class' => 'btn btn-success btn-calling']);
                            },
                        ],
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCallScreeningRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $counter = $this->findModelCounterservice($dataModel['counter']);

            $model = new TbCaller();
            $model->q_ids = $data['q_ids'];
            $model->qtran_ids = $data['ids'];
            $model->service_sec_id = $dataModel['section'];
            $model->counter_service_id = $dataModel['counter'];
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;

            $modelTran = $this->findModelQtran($data['ids']);
            $modelTran->service_status_id = $counter->counterType->q_calling_status;

            if($model->save() && $modelTran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' =>  $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-waiting',
                    'state' => 'call'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionRecallScreeningRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'recall'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionHoldScreeningRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_status = TbCaller::STATUS_HOLD;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCallholdScreeningRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'call-hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionEndholdScreeningRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQtran($model['qtran_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $modelQtran->service_status_id = 9;
            $model->call_status = TbCaller::STATUS_END;
            if($model->save() && $modelQtran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'end-hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($modelQtran)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbcallingSr(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->get('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_caller.service_sec_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_pt_visit_type.pt_visit_type',
                'tb_counterservice.counterservice_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->innerJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->where([
                'tb_caller.service_sec_id' => $request->get('secid'),
                'tb_caller.counter_service_id' => $request->get('counter'),
                'tb_caller.call_status' => ['calling','callend']
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'caller_ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{recall} {hold} {end}',
                        'buttons' => [
                            'recall' => function($url, $model, $key){
                                return Html::a('RECALL',$url,['class' => 'btn btn-success btn-recall']);
                            },
                            'hold' => function($url, $model, $key){
                                return Html::a('HOLD',$url,['class' => 'btn btn-success btn-hold']);
                            },
                            'end' => function($url, $model, $key){
                                return Html::a('END',$url,['class' => 'btn btn-success btn-end','role' => 'modal-remote']);
                            },
                        ],
                        /*'urlCreator' => function ( $action, $model, $key, $index) {
                            if($action == 'update'){
                                return Url::to(['/settings/default/create-visit-type']);
                            }
                        }*/
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbcallingEx(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->post('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_caller.service_sec_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_pt_visit_type.pt_visit_type',
                'tb_counterservice.counterservice_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->innerJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->where([
                'tb_caller.service_sec_id' => $request->post('secid'),
                'tb_caller.counter_service_id' => $request->post('counter'),
                'tb_caller.call_status' => ['calling','callend'],
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'caller_ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{recall} {hold} {end}',
                        'buttons' => [
                            'recall' => function($url, $model, $key){
                                return Html::a('RECALL',$url,['class' => 'btn btn-success btn-recall']);
                            },
                            'hold' => function($url, $model, $key){
                                return Html::a('HOLD',$url,['class' => 'btn btn-success btn-hold']);
                            },
                            'end' => function($url, $model, $key){
                                return Html::a('END',$url,['class' => 'btn btn-success btn-end']);
                            },
                        ],
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbholdSr(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->get('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_caller.service_sec_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_pt_visit_type.pt_visit_type',
                'tb_counterservice.counterservice_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->innerJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->where([
                'tb_caller.service_sec_id' => $request->get('secid'),
                'tb_caller.counter_service_id' => $request->get('counter'),
                'tb_caller.call_status' => 'hold'
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'caller_ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{call} {end}',
                        'buttons' => [
                            'call' => function($url, $model, $key){
                                return Html::a('CALL',$url,['class' => 'btn btn-success btn-calling']);
                            },
                            'end' => function($url, $model, $key){
                                return Html::a('End',$url,['class' => 'btn btn-success btn-end']);
                            },
                        ],
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbholdEx(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->post('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_caller.service_sec_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_pt_visit_type.pt_visit_type',
                'tb_counterservice.counterservice_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->innerJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->where([
                'tb_caller.service_sec_id' => $request->post('secid'),
                'tb_caller.counter_service_id' => $request->post('counter'),
                'tb_caller.call_status' => 'hold'
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'caller_ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{call} {end}',
                        'buttons' => [
                            'call' => function($url, $model, $key){
                                return Html::a('CALL',$url,['class' => 'btn btn-success btn-calling']);
                            },
                            'end' => function($url, $model, $key){
                                return Html::a('END',$url,['class' => 'btn btn-success btn-end']);
                            },
                        ],
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionSubSection() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $id = end($_POST['depdrop_parents']);
            $list = TbCounterservice::find()->andWhere(['sec_id'=>$id])->asArray()->all();
            $selected  = null;
            if ($id != null && count($list) > 0) {
                $selected = '';
                foreach ($list as $i => $counter) {
                    $out[] = ['id' => $counter['counterserviceid'], 'name' => $counter['counterservice_name']];
                    if ($i == 0) {
                        $selected = $counter['counterserviceid'];
                    }
                }
                // Shows how you can preselect a value
                echo Json::encode(['output' => $out, 'selected'=> $selected]);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected'=>'']);
    }

    public function getMediaSound($qnum,$id){
        $qnum = str_split($qnum);
        $counter = $this->findModelCounterservice($id);
        $basePath = "/media/".$counter['sound_path'];
        $begin = [$basePath."/please.wav"];//เชิญหมายเลข
        $end = [
            $basePath.'/'.$counter['sound_service_name'],
            $basePath.'/'.$counter['sound_path'].'_'.$counter['sound_service_number'].'.wav',
            $basePath.'/'.$counter['sound_path'].'_Sir.wav',
        ];

        $sound = array_map(function($num) use ($basePath,$counter) {
            return $basePath.'/'.$counter['sound_path'].'_'.$num.'.wav';
        }, $qnum);
        $sound = ArrayHelper::merge($begin, $sound);
        $sound = ArrayHelper::merge($sound, $end);
        return $sound;
    }

    public function actionPlaySound($secid = null){
        $model = new CallingForm();
        $model->section = $secid;
        return $this->render('play-sound',[
            'model' => $model,
        ]);
    }

    public function actionUpdateStatus($ids){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModelCaller($ids);
            $model->call_status = TbCaller::STATUS_CALLEND;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionAutoloadMedia(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $rows = (new \yii\db\Query())
                ->select([
                    'tb_caller.*', 
                    'tb_qtrans.ids',
                    'tb_quequ.q_num',
                    'tb_counterservice.*'
                ])
                ->from('tb_caller')
                ->where(['tb_caller.call_status' => TbCaller::STATUS_CALLING,'tb_caller.service_sec_id' => $request->get('section')])
                ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
                ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
                ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
                ->innerJoin('tb_counterservice_type','tb_counterservice_type.tb_counterservice_typeid = tb_counterservice.counterservice_type')
                ->orderBy('tb_caller.call_timestp ASC')
                ->all();
            $data = [];
            foreach($rows as $row){
                $data[] = [
                    'sound' => $this->getMediaSound($row['q_num'],$row['counter_service_id']),
                    'model' => $row,
                    'counter' => ['counterservice_type' => $row['counterservice_type']],
                    'data' => $row,
                ];
            }
            return [
                'status' => '200',
                'message' => 'success',
                'rows' => $data,
            ];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionEnd($id){
        $request = Yii::$app->request;
        $model  = $this->findModelCaller($id);
        $modelQ = $this->findModelQ($model['q_ids']);
        $modelQtran = $this->findModelQtran($model['qtran_ids']);
        $counter = $this->findModelCounterservice($model['counter_service_id']);
        $modelQtran->scenario = 'endq';
        if($request->isPost){
            $data = $request->post('TbQtrans',[]);
            $service = $this->findModelCounterservice($data['counter_service_id']);
            $section = $this->findModelSection($service['sec_id']);
            $modelQtran->service_status_id = $service->counterType->q_waiting_status;
            $model->call_status = TbCaller::STATUS_FINISHED;
        }

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            
            if($request->isGet){
                return [
                    'title'     => Icon::show('user-md')."เลือกแพทย์ #".$modelQ['q_num'],
                    'content'   => $this->renderAjax('_form_doctor', [
                        'model' => $model,
                        'modelQ'=> $modelQ,
                        'modelQtran' => $modelQtran,
                        'eventOn' => 'tb-calling',
                        'state' => 'end'
                    ]),
                    'footer'=>  ''
                ];         
            }else if($model->load($request->post()) && $model->save() && $modelQtran->load($request->post()) && $modelQtran->save()){
                return [
                    'title'     =>  Icon::show('user-md')."เลือกแพทย์",
                    'content'   =>  '<span class="text-success">บันทึกสำเร็จ!</span>',
                    'footer'    =>  '',
                    'status'    =>  200,
                    'model'     =>  $modelQtran,
                    'modelQ'    =>  $modelQ,
                    'modelQtran'=>  $modelQtran,
                    'counter'   => $counter,
                    'data'      => $model
                ];         
            }else{           
                return [
                    'title'     =>  Icon::show('user-md')."เลือกแพทย์",
                    'content'   =>  $this->renderAjax('_form_doctor', [
                        'model' => $model,
                        'modelQ'=> $modelQ,
                        'modelQtran' => $modelQtran,
                    ]),
                    'footer'    =>  '',
                    'status'    =>  'error',
                    'validate'  =>  ArrayHelper::merge(ActiveForm::validate($modelQtran), ActiveForm::validate($model)),
                ];         
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionSetcounterValue(){
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $key = 'counter-examination-value';

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $data = $request->post('CallingForm',[]);
            $session->remove($key);
            $session->set($key, $data['counter']);
            return $session->get($key);
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionSetSectionFormvalue(){
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $key = 'calling-secid';

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $session->remove($key);
            $session->set($key, $request->post('secid'));
            return $session->get($key);
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCallExaminationRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $counter = $this->findModelCounterservice($request->post('value'));

            $model = new TbCaller();
            $model->q_ids = $data['q_ids'];
            $model->qtran_ids = $data['ids'];
            $model->service_sec_id = $dataModel['section'];
            $model->counter_service_id = !empty($request->post('value')) ? $request->post('value') : $data['counter_service_id'];
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;

            $modelTran = $this->findModelQtran($data['ids']);
            $modelTran->service_status_id = $counter->counterType->q_calling_status;

            if($model->save() && $modelTran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$request->post('value')),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-waiting',
                    'state' => 'call'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionRecallExaminationRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$model['counter_service_id']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'call'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionHoldExaminationRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_status = TbCaller::STATUS_HOLD;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCallholdExaminationRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$model['counter_service_id']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'call-hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionEndExaminationRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQtran($model['qtran_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_status = TbCaller::STATUS_FINISHED;
            $modelQtran->checkout_date = new Expression('NOW()');
            $modelQtran->service_status_id = 10;
            if($model->save() && $modelQtran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'modelTran' => $modelQtran,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'end'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionEndholdExaminationRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQtran($model['qtran_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_status = TbCaller::STATUS_END;
            $modelQtran->service_status_id = 9;
            if($model->save() && $modelQtran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'modelTran' => $modelQtran,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'end'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    protected function findModelCaller($id)
    {
        if (($model = TbCaller::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelQ($id)
    {
        if (($model = TbQuequ::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelSection($id)
    {
        if (($model = TbSection::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelQtran($id)
    {
        if (($model = TbQtrans::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelCounterservice($id)
    {
        if (($model = TbCounterservice::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDataTbwaitingBd(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->get('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_qtrans.ids',
                'tb_qtrans.q_ids',
                'tb_qtrans.counter_service_id',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_qtrans.service_status_id',
                'tb_pt_visit_type.pt_visit_type',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name'
            ])
            ->from('tb_qtrans')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->leftJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->where([
                'tb_qtrans.service_status_id' => $section['sec_firststatus'],
                'tb_qtrans.service_sec_id' => $request->get('secid')
            ])
            ->orderBy('checkin_date DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{call}',
                        'buttons' => [
                            'call' => function($url, $model, $key){
                                return Html::a('CALL',$url,['class' => 'btn btn-success btn-calling']);
                            },
                        ],
                        /*'urlCreator' => function ( $action, $model, $key, $index) {
                            if($action == 'update'){
                                return Url::to(['/settings/default/create-visit-type']);
                            }
                        }*/
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbcallingBd(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->get('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_caller.service_sec_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_pt_visit_type.pt_visit_type',
                'tb_counterservice.counterservice_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->innerJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->where([
                'tb_caller.service_sec_id' => $request->get('secid'),
                'tb_caller.counter_service_id' => $request->get('counter'),
                'tb_caller.call_status' => ['calling','callend']
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'caller_ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{recall} {hold} {end}',
                        'buttons' => [
                            'recall' => function($url, $model, $key){
                                return Html::a('RECALL',$url,['class' => 'btn btn-success btn-recall']);
                            },
                            'hold' => function($url, $model, $key){
                                return Html::a('HOLD',$url,['class' => 'btn btn-success btn-hold']);
                            },
                            'end' => function($url, $model, $key){
                                return Html::a('END',$url,['class' => 'btn btn-success btn-end']);
                            },
                        ],
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataTbholdBd(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $section = TbSection::findOne($request->get('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.q_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%d/%m/%Y %H:%i:%s\') as checkin_date',
                'tb_caller.service_sec_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_pt_visit_type.pt_visit_type',
                'tb_counterservice.counterservice_name'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans','tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->innerJoin('tb_service_status','tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->where([
                'tb_caller.service_sec_id' => $request->get('secid'),
                'tb_caller.counter_service_id' => $request->get('counter'),
                'tb_caller.call_status' => 'hold'
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'caller_ids',
                    ],
                    [
                        'attribute' => 'q_ids',
                    ],
                    [
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge($model['q_num'],['class' => 'badge badge-success']);
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
                        'attribute' => 'pt_visit_type',
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'counter_service_id',
                    ],
                    [
                        'attribute' => 'checkin_date',
                    ],
                    [
                        'attribute' => 'counterservice_name',
                    ],
                    [
                        'attribute' => 'qnumber',
                        'value' => function($model, $key, $index, $column){
                            return $model['q_num'];
                        },
                    ],
                    [
                        'attribute' => 'service_status_name',
                        'value' => function($model, $key, $index, $column){
                            return \kartik\helpers\Html::badge(Icon::show('hourglass-3').' '.$model['service_status_name'],['class' => 'badge']);
                        },
                        'format' => 'raw'
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{call} {end}',
                        'buttons' => [
                            'call' => function($url, $model, $key){
                                return Html::a('CALL',$url,['class' => 'btn btn-success btn-calling']);
                            },
                            'end' => function($url, $model, $key){
                                return Html::a('End',$url,['class' => 'btn btn-success btn-end']);
                            },
                        ],
                    ]
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCallBlooddrillRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $counter = $this->findModelCounterservice($dataModel['counter']);

            $model = new TbCaller();
            $model->q_ids = $data['q_ids'];
            $model->qtran_ids = $data['ids'];
            $model->service_sec_id = $dataModel['section'];
            $model->counter_service_id = $dataModel['counter'];
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;

            $modelTran = $this->findModelQtran($data['ids']);
            $modelTran->service_status_id = $counter->counterType->q_calling_status;
            $modelTran->counter_service_id = $dataModel['counter'];

            if($model->save() && $modelTran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' =>  $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-waiting',
                    'state' => 'call'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionRecallBlooddrillRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'recall'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionHoldBlooddrillRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_status = TbCaller::STATUS_HOLD;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionEndBlooddrillRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQtran($model['qtran_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_status = TbCaller::STATUS_FINISHED;
            $modelQtran->checkout_date = new Expression('NOW()');
            $modelQtran->service_status_id = 10;
            if($model->save() && $modelQtran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'data' => $data,
                    'model' => $model,
                    'modelTran' => $modelQtran,
                    'counter' => $counter,
                    'eventOn' => 'tb-calling',
                    'state' => 'end'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCallholdBlooddrillRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $model->call_timestp = new Expression('NOW()');
            $model->call_status = TbCaller::STATUS_CALLING;
            if($model->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'call-hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($model)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionEndholdBlooddrillRoom(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $data = $request->post('data',[]);
            $dataModel = $request->post('model',[]);
            $section = $this->findModelSection($dataModel['section']);
            $model = $this->findModelCaller($data['caller_ids']);
            $modelQtran = $this->findModelQtran($model['qtran_ids']);
            $counter = $this->findModelCounterservice($model['counter_service_id']);
            $modelQtran->service_status_id = 9;
            $model->call_status = TbCaller::STATUS_END;
            if($model->save() && $modelQtran->save()){
                return [
                    'status' => '200',
                    'message' => 'success',
                    'sound' => $this->getMediaSound($data['qnumber'],$dataModel['counter']),
                    'data' => $data,
                    'model' => $model,
                    'section' => $section,
                    'counter' => $counter,
                    'eventOn' => 'tb-hold',
                    'state' => 'end-hold'
                ];
            }else{
                return [
                    'status' => '500',
                    'message' => 'error',
                    'validate' => ActiveForm::validate($modelQtran)
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

}

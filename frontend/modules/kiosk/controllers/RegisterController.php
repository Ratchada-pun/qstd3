<?php

namespace frontend\modules\kiosk\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\kiosk\models\RegisterForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;
use homer\widgets\tbcolumn\ActionTable;
use homer\widgets\tbcolumn\ColumnTable;
use homer\widgets\tbcolumn\ColumnData;
use frontend\modules\kiosk\models\TbQtrans;
use frontend\modules\kiosk\models\TbQuequ;
use frontend\modules\kiosk\models\TbSection;
use yii\db\Expression;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

class RegisterController extends \yii\web\Controller
{
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
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [],
            ],
        ];
    }

    public function actionIndex($secid = null)
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $key = 'regis-secid';

        $model = new RegisterForm();

        #set sessions
        if (($session->get($key) != $secid)  && ($secid != null)){
            $session->set($key, $secid);
        }elseif($secid == null && !$session->has($key)){
            $session->remove($key);
        }
        $model->section = $session->get($key);

        return $this->render('index',[
            'model' => $model,
            'secid' => $secid
        ]);
    }

    public function actionDataRegister(){
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
            ->where(['tb_qtrans.service_sec_id' => $request->get('secid')])
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
                        'attribute' => 'service_status_name',
                    ],
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionCheckRegister(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $data = $request->post('RegisterForm',[]);

            $modelQ = TbQuequ::findOne($data['barcode']);//เช็คว่ามีข้อมูลในระบบหรือไม่
            if(!$modelQ){//ถ้าไม่มีข้อมูล
                return ['status' => 'no data','message' => 'ไม่พบข้อมูล'];
            }

            $modelTran = TbQtrans::findOne(['q_ids' => $data['barcode'],'service_sec_id' => $data['section']]);//เช็คว่าลงทะเบียนแล้วหรือยัง
            if($modelTran){//ถ้าลงทะเบียนแล้ว
                return ['status' => 'duplicate','message' => $modelQ['pt_name']];
            }
            return ['status' => '200','message' => 'success','model' => $modelQ,'secid' => $data['section']];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionInsertRegister($q_ids,$secid){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $modelQ = $this->findModelQ($q_ids);
            $modelSection = $this->findModelSection($secid);
            $model = new TbQtrans();
            $model->q_ids = $q_ids;
            $model->service_sec_id = $secid;
            $model->service_status_id = $modelSection['sec_firststatus'];
            $model->doctor_id = $modelQ['doctor_id'];
            if($model->save()){
                return ['status' => '200','message' => 'success','model' => $model,'modelQ' => $modelQ];
            }
            return ['status' => 'error','message' => 'error','validate' => ActiveForm::validate($model)];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
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

}

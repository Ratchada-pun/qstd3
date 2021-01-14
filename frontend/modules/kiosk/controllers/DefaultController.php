<?php

namespace frontend\modules\kiosk\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\modules\kiosk\models\SearchForm;
use frontend\modules\kiosk\models\TbQuequ;
use kartik\widgets\ActiveForm;
use homer\widgets\tbcolumn\ActionTable;
use homer\widgets\tbcolumn\ColumnTable;
use homer\widgets\tbcolumn\ColumnData;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\filters\AccessControl;
use frontend\modules\kiosk\models\TbTicket;
use yii\helpers\Html;
/**
 * Default controller for the `kiosk` module
 */
class DefaultController extends Controller
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
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = new SearchForm();
        return $this->render('index',[
            'model' => $model,
        ]);
    }

    public function actionSearchHn(){
        $request = Yii::$app->request;
        $response = Yii::$app->response;
        $model = new TbQuequ();

        if($request->isAjax){
            $response->format = \yii\web\Response::FORMAT_JSON;
            $dataQ = $request->post('TbQuequ',[]);

            if($model->load($request->post())){
                $model->q_num = $model->runQnum($dataQ);
            }

            if($request->isPost && !$model->load($request->post())){
                $data = $request->post('SearchForm',[]);
                $modelcheck = TbQuequ::findOne(['q_hn' => $data['hn']]);
                if($modelcheck){
                    return [
                        'status' => 'duplicate',
                        'message' => $modelcheck['pt_name'],
                    ];
                }
                $rows = (new \yii\db\Query())
                    ->select(['tb_his_data.*'])
                    ->from('tb_his_data')
                    ->where(['q_hn' => $data['hn']])
                    //->orWhere(['q_vn' => $data['hn']])
                    ->one();
                
                if($rows){
                    return [
                        'status' => 200,
                        'form' => $this->renderAjax('_form_search',[
                            'rows' => $rows,
                            'model' => $model,
                        ]),
                    ];
                }else{
                    return [
                        'status' => 404
                    ];
                }
            }elseif ($model->load($request->post()) && $model->save()) {
                return [
                    'status' => 200,
                    'message' => 'บันทึกสำเร็จ',
                    'model' => $model,
                    'url' => Url::to(['print-card','id' => $model['q_ids']])
                ];
            }else{
                return [
                    'status' => 'error',
                    'validate' => ActiveForm::validate($model),
                ];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataQ(){
        $request = Yii::$app->request;

        if($request->isAjax){
            $query = (new \yii\db\Query())
            ->select(['tb_quequ.*','tb_pt_visit_type.*','tb_section.*'])
            ->from('tb_quequ')
            ->leftJoin('tb_pt_visit_type','tb_pt_visit_type.pt_visit_type_id = tb_quequ.pt_visit_type_id')
            ->leftJoin('tb_section','tb_section.sec_id = tb_quequ.pt_appoint_sec_id')
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
                        /*'value' => function($model, $key, $index, $column){
                            return $model['pt_visit_type_id'] == 1 ? \kartik\helpers\Html::badge($model['pt_visit_type'],['class' => 'badge badge-success']) : \kartik\helpers\Html::badge($model['pt_visit_type'],['class' => 'badge']);
                        },*/
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'sec_name',
                    ],
                    [
                        'class' => ActionTable::className(),
                        'template' => '{print}',
                        'updateOptions' => [
                            'role' => 'modal-remote'
                        ],
                        'buttons' => [
                            'print' => function($url, $model, $key){
                                return Html::a('<i class="pe-7s-print"></i>'.' Print',false,[
                                    'onclick' => 'return window.open("'.Url::to(['/kiosk/default/print-card','id' => $key]).'","myPrint", "width=800, height=600");',
                                    'class' => 'btn btn-success',
                                    'title' => 'Print',
                                ]);
                            }
                        ],
                        // 'urlCreator' => function ( $action, $model, $key, $index) {
                        //     if($action == 'update'){
                        //         return Url::to(['/kiosk/default/create-visit-type']);
                        //     }
                        //     if($action == 'delete'){
                        //         return Url::to(['/kiosk/default/delete-visit-type','id' => $key]);
                        //     }
                        // }
                    ]
                ]
            ]);

            return Json::encode(['data' => $columns->renderDataColumns()]);
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionPrintCard($id){
        $model = TbQuequ::findOne($id);
        $ticket = TbTicket::findOne(['status' => 1]);
        $y = \Yii::$app->formatter->asDate('now', 'php:Y') + 543;
        $template = strtr($ticket->template, [
            '{hos_name_th}' => $ticket->hos_name_th,
            '{q_hn}' => $model->q_hn,
            '{pt_name}' => $model->pt_name,
            '{q_num}' => $model->q_num,
            '{pt_visit_type}' => @$model->ptVisitType->pt_visit_type,
            '{sec_name}' => @$model->section->sec_name,
            '{time}' => \Yii::$app->formatter->asDate('now', 'php:d M '.substr($y, 2)),
            '{user_print}' => Yii::$app->user->identity->profile->name,
            '/img/logo/logo.jpg' => $ticket->logo_path ? $ticket->logo_base_url.'/'.$ticket->logo_path : '/img/logo/logo.jpg'
        ]);
        return $this->renderAjax('print-card',[
            'model' => $model,
            'ticket' => $ticket,
            'template' => $template,
        ]);
    }
}

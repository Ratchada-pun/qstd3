<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\Html;
use homer\widgets\tbcolumn\ActionTable;
use homer\widgets\tbcolumn\ColumnTable;
use homer\widgets\tbcolumn\ColumnData;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
#models
use frontend\modules\app\models\TbDisplayConfig;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\traits\ModelTrait;

class DisplayController extends \yii\web\Controller
{
	use ModelTrait;

	public $layout = 'display';

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($id)
    {
    	$config = $this->findModelDisplayConfig($id);
    	$counter = $this->findModelCounterserviceType($config['counterservice_id']);
        $config->service_id = !empty($config['service_id']) ? explode(",",$config['service_id']) : [];
        $config->counterservice_id = !empty($config['counterservice_id']) ? explode(",",$config['counterservice_id']) : [];
        return $this->render('index',[
        	'config' => $config,
        	'counter' => $counter
        ]);
    }

    public function actionDisplayList()
    {
        $display = TbDisplayConfig::find()->where(['display_status' => 1])->all();
    	return $this->render('display-list',[
    		'displays' => $display,
        ]);
    }

    public function actionDataDisplay(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $config = $request->post('config',[]);
            $query = $this->findDisplayData($config);

            $map = ArrayHelper::map($query,'serviceid','service_prefix');
            $caller_ids = ArrayHelper::getColumn($query,'caller_ids');
            $mapArr = [];
            foreach($map as $m){
                $rows = (new \yii\db\Query())
                        ->select(['tb_quequ.q_num'])
                        ->from('tb_caller')
                        ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
                        ->where(['tb_caller.caller_ids' => $caller_ids,'tb_caller.call_status' => 'calling'])
                        ->andWhere('tb_quequ.q_num LIKE :q_num')
                        ->addParams([':q_num'=> $m.'%'])
                        ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
                        ->one();
                if($rows){
                    $mapArr[$m] = $rows['q_num'];
                }else{
                    $rows = (new \yii\db\Query())
                        ->select(['tb_quequ.q_num'])
                        ->from('tb_caller')
                        ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
                        ->where(['tb_caller.caller_ids' => $caller_ids,'tb_caller.call_status' => 'callend'])
                        ->andWhere('tb_quequ.q_num LIKE :q_num')
                        ->addParams([':q_num'=> $m.'%'])
                        ->orderBy(['tb_caller.call_timestp' => SORT_DESC])
                        ->one();
                    if($rows){
                        $mapArr[$m] = $rows['q_num'];
                    }
                }
            }

            $count = count($query);
            if($count < $config['display_limit']){
                $array = [];
                for($i = $count; $i < $config['display_limit']; $i++){
                    $random = Yii::$app->getSecurity()->generateRandomString(32);
                    $arr = [
                        'caller_ids' => $random,
                        'q_num' => '-',
                        'call_timestp' => date('Y-m-d H:i:s'),
                        'counterservice_callnumber' => '-',
                        'serviceid' => '',
                        'service_name' => '',
                        'service_prefix' => ''
                    ];
                    $array[] = $arr;
                }
                $query = ArrayHelper::merge($query, $array);
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $query,
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
                        'attribute' => 'q_num',
                        'value' => function($model, $key, $index, $column){
                            return Html::tag('text',$model['q_num'],['class' => trim($model['q_num'])]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'service_number',
                        'value' => function($model, $key, $index, $column){
                            return Html::tag('text',$model['counterservice_callnumber'],['class' => trim($model['q_num'])]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'call_timestp',
                    ],
                    [
                        'attribute' => 'service_name',
                    ],
                    [
                        'attribute' => 'serviceid',
                    ],
                    [
                        'attribute' => 'service_prefix',
                    ],
                ]
            ]);

            return ['data' => $columns->renderDataColumns(),'mapArr' => $mapArr];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataDisplayHold(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $config = $request->post('config',[]);
            $query = (new \yii\db\Query())
            ->select([
            	'tb_caller.caller_ids',
                'GROUP_CONCAT(tb_quequ.q_num) AS q_num'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->innerJoin('tb_counterservice_type','tb_counterservice_type.counterservice_typeid = tb_counterservice.counterservice_type')
            ->leftJoin('tb_service','tb_service.serviceid = tb_quequ.serviceid')
            ->where([
            	'tb_caller.call_status' => 'hold',
            	'tb_counterservice_type.counterservice_typeid' => $config['counterservice_id'],
                'tb_service.serviceid' => $config['service_id']
            ])
            ->orderBy('tb_caller.call_timestp DESC');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'caller_ids'
	        ]);

            if($dataProvider->getTotalCount() <= 0){
				return ['data' => [['q_num' => '-']]];
            }else{
            	$columns = Yii::createObject([
	                'class' => ColumnData::className(),
	                'dataProvider' => $dataProvider,
	                'formatter' => Yii::$app->getFormatter(),
	                'columns' => [
	                    [
	                        'attribute' => 'q_num',
	                        'value' => function($model, $key, $index, $column){
	                            return Html::tag('marquee',$model['q_num'],['direction' => 'left']);
	                        },
	                        'format' => 'raw'
	                    ],
	                ]
	            ]);

	            return ['data' => $columns->renderDataColumns()];
            }
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionLab(){
        $model = $this->findModelDisplayConfig(4);
        return $this->render('display-lab',['config' => $model]);
    }

    public function actionDataDisplaylab(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $params = [':vstdate' => date('Y-m-d'),':confirm' => 'N'];
            $sql = 'SELECT
                    `lab_order`.`lab_order_number` AS lab_order_number,
                    `vn_stat`.`vn` AS `vn`,
                    `vn_stat`.`hn` AS `hn`,
                    `vn_stat`.`vstdate` AS `vstdate`,
                    concat( `patient`.`pname`, \' \', `patient`.`fname`, \' \', `patient`.`lname` ) AS `pt_name`,
                    `lab_order`.`lab_items_code` AS `lab_items_code`,
                    `lab_order`.`lab_order_result` AS `lab_order_result`,
                    `lab_order`.`confirm` AS `confirm`,
                    `lab_items`.`lab_items_name` AS `lab_items_name` 
                FROM
                    (
                    (
                    (
                    ( `lab_order` JOIN `lab_order_service` ON ( ( `lab_order_service`.`lab_order_number` = `lab_order`.`lab_order_number` ) ) )
                    JOIN `vn_stat` ON ( ( `vn_stat`.`vn` = `lab_order_service`.`vn` ) ) 
                    )
                    JOIN `patient` ON ( ( `patient`.`hn` = `vn_stat`.`hn` ) ) 
                    )
                    JOIN `lab_items` ON ( ( `lab_items`.`lab_items_code` = `lab_order`.`lab_items_code` ) ) 
                    ) 
                WHERE
                    ( `vn_stat`.`vstdate` = :vstdate AND `lab_order`.`confirm` = :confirm)
                GROUP BY
                    `lab_order`.`lab_order_number`
                ORDER BY
                    `lab_order`.`lab_order_number`, `patient`.`fname`';
            $allModels = Yii::$app->db_his->createCommand($sql)->bindValues($params)->queryAll();
            $rows = (new \yii\db\Query())
                ->select(['q_ids', 'q_num','q_hn'])
                ->from('tb_quequ')
                ->all();
            $items = [];
            $i = 0;
            if(count($allModels) > 0){
                $qdata = ArrayHelper::map($rows, 'q_hn', 'q_num');
                foreach ($allModels as $key => $value) {
                    $qnum = ArrayHelper::getValue($qdata, $value['hn'], '');
                    if($qnum == ''){
                        continue;
                    }else{
                        $i++;
                    }
                    $items[] = ArrayHelper::merge($value, [
                        'qnum' => $qnum,
                        'map' => $qdata
                    ]);
                    if ($i == 5) {
                        $session->set('lab_order_number', $value['lab_order_number']);
                        break;
                    }
                }
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $items,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'lab_order_number'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'vn',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'pt_name',
                        'format' => 'raw',
                        'value' => function ($model,$key,$index,$column){
                            $str = $model['qnum'].' - '.$model['pt_name'];
                            return mb_substr($str,0,30).'...';
                        }
                    ],
                    [
                        'attribute' => 'lab_order_number',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'qnum',
                        'format' => 'raw',
                    ],
                ]
            ]);

            return ['data' => $columns->renderDataColumns(),'lab_order_number' => $session->get('lab_order_number')];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataDisplaylab2(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $lab_order_number = isset($_SESSION['lab_order_number']) ? $_SESSION['lab_order_number'] : null;
            $params = [':vstdate' => date('Y-m-d'),':confirm' => 'N',':lab_order_number' => $lab_order_number];
            $sql = 'SELECT
                    `lab_order`.`lab_order_number` AS lab_order_number,
                    `vn_stat`.`vn` AS `vn`,
                    `vn_stat`.`hn` AS `hn`,
                    `vn_stat`.`vstdate` AS `vstdate`,
                    concat( `patient`.`pname`, \' \', `patient`.`fname`, \' \', `patient`.`lname` ) AS `pt_name`,
                    `lab_order`.`lab_items_code` AS `lab_items_code`,
                    `lab_order`.`lab_order_result` AS `lab_order_result`,
                    `lab_order`.`confirm` AS `confirm`,
                    `lab_items`.`lab_items_name` AS `lab_items_name` 
                FROM
                    (
                    (
                    (
                    ( `lab_order` JOIN `lab_order_service` ON ( ( `lab_order_service`.`lab_order_number` = `lab_order`.`lab_order_number` ) ) )
                    JOIN `vn_stat` ON ( ( `vn_stat`.`vn` = `lab_order_service`.`vn` ) ) 
                    )
                    JOIN `patient` ON ( ( `patient`.`hn` = `vn_stat`.`hn` ) ) 
                    )
                    JOIN `lab_items` ON ( ( `lab_items`.`lab_items_code` = `lab_order`.`lab_items_code` ) ) 
                    ) 
                WHERE
                    ( `vn_stat`.`vstdate` = :vstdate AND `lab_order`.`confirm` = :confirm AND `lab_order`.`lab_order_number` > :lab_order_number)
                GROUP BY
                    `lab_order`.`lab_order_number`
                ORDER BY
                    `lab_order`.`lab_order_number`,`patient`.`fname`';
            $allModels = Yii::$app->db_his->createCommand($sql)->bindValues($params)->queryAll();
            $rows = (new \yii\db\Query())
                ->select(['q_ids', 'q_num','q_hn'])
                ->from('tb_quequ')
                ->all();
            $items = [];
            $i = 0;
            if(count($allModels) > 0){
                $qdata = ArrayHelper::map($rows, 'q_hn', 'q_num');
                foreach ($allModels as $key => $value) {
                    $qnum = ArrayHelper::getValue($qdata, $value['hn'], '');
                    if($qnum == ''){
                        continue;
                    }else{
                        $i++;
                    }
                    $items[] = ArrayHelper::merge($value, [
                        'qnum' => $qnum,
                        'map' => $qdata
                    ]);
                    if ($i == 5) {
                        break;
                    }
                }
            }

            $dataProvider = new ArrayDataProvider([
                'allModels' => $items,
                'pagination' => [
                    'pageSize' => false,
                ],
                'key' => 'lab_order_number'
            ]);
            $columns = Yii::createObject([
                'class' => ColumnData::className(),
                'dataProvider' => $dataProvider,
                'formatter' => Yii::$app->getFormatter(),
                'columns' => [
                    [
                        'attribute' => 'vn',
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'pt_name',
                        'format' => 'raw',
                        'value' => function ($model,$key,$index,$column){
                            $str = $model['qnum'].' - '.$model['pt_name'];
                            return mb_substr($str,0,30).'...';
                        }
                    ],
                    [
                        'attribute' => 'lab_order_number',
                        'format' => 'raw',
                    ],
                ]
            ]);

            return ['data' => $columns->renderDataColumns(),'lab_order_number' => $session->get('lab_order_number')];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataDisplay2(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $config = $request->post('config',[]);
            $query = $this->findDisplayData($config);

            $map = ArrayHelper::map($query,'serviceid','service_prefix');
            $caller_ids = ArrayHelper::getColumn($query,'caller_ids');
            $mapArr = [];
            foreach($map as $m){
                $rows = (new \yii\db\Query())
                        ->select(['tb_quequ.q_num'])
                        ->from('tb_caller')
                        ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
                        ->where(['tb_caller.caller_ids' => $caller_ids,'tb_caller.call_status' => 'calling'])
                        ->andWhere('tb_quequ.q_num LIKE :q_num')
                        ->addParams([':q_num'=> $m.'%'])
                        ->orderBy(['tb_caller.call_timestp' => SORT_ASC])
                        ->one();
                if($rows){
                    $mapArr[] = ['prefix' => $m,'qnum' => $rows['q_num']];
                }else{
                    $rows = (new \yii\db\Query())
                        ->select(['tb_quequ.q_num'])
                        ->from('tb_caller')
                        ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
                        ->where(['tb_caller.call_status' => 'callend','tb_caller.caller_ids' => $caller_ids])
                        ->andWhere('tb_quequ.q_num LIKE :q_num')
                        ->addParams([':q_num'=> $m.'%'])
                        ->orderBy(['tb_caller.call_timestp' => SORT_DESC])
                        ->one();
                    if($rows){
                        $mapArr[] = ['prefix' => $m,'qnum' => $rows['q_num']];
                    }
                }
            }

            return ['data' => $mapArr];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    protected function findDisplayData($config){
        $lastcalling = $this->lastCalling($config);
        $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_quequ.q_num',
                'tb_caller.call_timestp',
                'tb_counterservice.counterservice_callnumber',
                'tb_service.serviceid',
                'tb_service.service_name',
                'tb_service.service_prefix'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->innerJoin('tb_counterservice_type','tb_counterservice_type.counterservice_typeid = tb_counterservice.counterservice_type')
            ->leftJoin('tb_service','tb_service.serviceid = tb_quequ.serviceid')
            ->where([
            	'tb_caller.call_status' => ['calling','callend'],
                'tb_counterservice_type.counterservice_typeid' => $config['counterservice_id'],
                'tb_service.serviceid' => $config['service_id']
            ])
            //->limit($config['display_limit'])
            ->orderBy([
            	'tb_caller.call_timestp' => SORT_DESC
            ]);
            if($lastcalling){
                $query->andWhere('tb_caller.call_timestp <= :call_timestp', [':call_timestp' => $lastcalling['call_timestp']]);
            }
            return $query->all();
    }

    protected function lastCalling($config){
        $rows = (new \yii\db\Query())
                ->select([
                    'tb_caller.caller_ids',
                    'tb_quequ.q_num',
                    'tb_caller.call_timestp',
                    'tb_counterservice.counterservice_callnumber',
                    'tb_service.serviceid',
                    'tb_service.service_name',
                    'tb_service.service_prefix'
                ])
                ->from('tb_caller')
                ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
                ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
                ->innerJoin('tb_counterservice_type','tb_counterservice_type.counterservice_typeid = tb_counterservice.counterservice_type')
                ->leftJoin('tb_service','tb_service.serviceid = tb_quequ.serviceid')
                ->where([
                    'tb_caller.call_status' => ['calling'],
                    'tb_counterservice_type.counterservice_typeid' => $config['counterservice_id'],
                    'tb_service.serviceid' => $config['service_id']
                ])
                ->orderBy([
                    'tb_caller.call_timestp' => SORT_ASC
                ])
                ->one();
        return $rows;
        
    }

}

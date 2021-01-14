<?php

namespace frontend\modules\kiosk\controllers;

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
use frontend\modules\kiosk\models\TbCounterserviceType;
use frontend\modules\kiosk\models\TbDisplayConfig;

class DisplayController extends \yii\web\Controller
{
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
    	$counter = $this->findModelCounterserviceType($config['counterservice_type']);
        return $this->render('index',[
        	'config' => $config,
        	'counter' => $counter
        ]);
    }

    public function actionDisplayList(){
    	$display = TbDisplayConfig::find()->all();
    	return $this->render('display-list',[
    		'displays' => $display,
        ]);
    }

    public function actionDataDisplay(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $config = $request->post('config',[]);
            //$section = TbSection::findOne($request->post('secid'));
            $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_quequ.q_num',
                'tb_counterservice.sound_service_number',
                'tb_caller.call_timestp'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->innerJoin('tb_counterservice_type','tb_counterservice_type.tb_counterservice_typeid = tb_counterservice.counterservice_type')
            ->where([
            	'tb_caller.call_status' => ['calling','callend'],
            	'tb_counterservice_type.tb_counterservice_typeid' => $config['counterservice_type'],
            ])
            ->limit($config['display_limit'])
            ->orderBy([
            	'tb_caller.call_timestp' => SORT_DESC
            ])
            ->all();

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
                            return Html::tag('text',$model['sound_service_number'],['class' => trim($model['q_num'])]);
                        },
                        'format' => 'raw',
                    ],
                ]
            ]);

            return ['data' => $columns->renderDataColumns()];
        }else {
            throw new NotFoundHttpException('Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionDataDisplayHold(){
        $request = Yii::$app->request;

        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $config = $request->post('config',[]);

            //$section = TbSection::findOne($request->post('secid'));
            $query = (new \yii\db\Query())
            ->select([
            	'tb_caller.caller_ids',
                'GROUP_CONCAT(tb_quequ.q_num) AS q_num'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_caller.q_ids')
            ->innerJoin('tb_counterservice','tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->innerJoin('tb_counterservice_type','tb_counterservice_type.tb_counterservice_typeid = tb_counterservice.counterservice_type')
            ->where([
            	'tb_caller.call_status' => 'hold',
            	'tb_counterservice_type.tb_counterservice_typeid' => $config['counterservice_type'],
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

    protected function findModelCounterserviceType($id)
    {
        if (($model = TbCounterserviceType::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelDisplayConfig($id)
    {
        if (($model = TbDisplayConfig::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}

<?php

namespace frontend\modules\api\modules\v1\controllers;

use frontend\modules\app\models\TbService;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

/**
 * Service controller for the `v1` module
 */
class QueueController extends ActiveController
{
    public $modelClass = 'frontend\modules\app\models\TbService';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
        ];

        // re-add authentication filter
        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        return $actions;
    }


    public function actionWaitingList($serviceid = null)
    {
        $query = (new \yii\db\Query())
            ->select([
                'tb_qtrans.ids',
                'tb_qtrans.q_ids',
                'tb_qtrans.counter_service_id',
                'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%H:%i:%s\') as checkin_date',
                'tb_qtrans.service_status_id',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.q_vn',
                'tb_quequ.q_qn',
                'tb_quequ.pt_name',
                'tb_counterservice.counterservice_name',
                'tb_service_status.service_status_name',
                'tb_service.service_name',
                'tb_service.serviceid',
                'tb_service.service_prefix',
                'tb_quequ.quickly'
            ])
            ->from('tb_qtrans')
            ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->leftJoin('tb_counterservice', 'tb_counterservice.counterserviceid = tb_qtrans.counter_service_id')
            ->leftJoin('tb_service_status', 'tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->leftJoin('tb_service', 'tb_service.serviceid = tb_quequ.serviceid')
            ->where([
                'tb_qtrans.service_status_id' => [1, 11, 12, 13]
            ])
            ->andWhere('DATE(tb_quequ.q_timestp) = CURRENT_DATE');
        //->orderBy(['tb_quequ.quickly' => SORT_DESC, 'checkin_date' => SORT_ASC]);

        if (!empty($serviceid)) {
            $query->andWhere(['tb_quequ.serviceid' => $serviceid]);
        }

        return $query->all();

    }


    public function actionHoldList($serviceid = null,$counter_service_id = null)
    {
        $query = (new \yii\db\Query())
        ->select([
            'tb_caller.caller_ids',
            'tb_caller.q_ids',
            'tb_caller.qtran_ids',
            'DATE_FORMAT(DATE_ADD(tb_qtrans.checkin_date, INTERVAL 543 YEAR),\'%H:%i:%s\') as checkin_date',
            'tb_caller.servicegroupid',
            'tb_caller.counter_service_id',
            'tb_caller.call_timestp',
            'tb_quequ.q_num',
            'tb_quequ.q_hn',
            'tb_quequ.q_qn',
            'tb_quequ.pt_name',
            'tb_quequ.countdrug',
            'tb_quequ.qfinace',
            'tb_service_status.service_status_name',
            'tb_counterservice.counterservice_name',
            'tb_service.service_name',
            'tb_service.serviceid',
            'tb_service.service_prefix',
            'tb_quequ.quickly',
            'tb_qtrans.ids',
            'tb_qtrans.q_ids'
        ])
        ->from('tb_caller')
        ->innerJoin('tb_qtrans', 'tb_qtrans.ids = tb_caller.qtran_ids')
        ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
        ->innerJoin('tb_service_status', 'tb_service_status.service_status_id = tb_qtrans.service_status_id')
        ->innerJoin('tb_counterservice', 'tb_counterservice.counterserviceid = tb_caller.counter_service_id')
        ->leftJoin('tb_service', 'tb_service.serviceid = tb_quequ.serviceid')
        ->where([
            'tb_caller.call_status' => 'hold',
            'tb_quequ.q_status_id' => [3, 11, 12, 13]
        ])
        ->andWhere('DATE(tb_quequ.q_timestp) = CURRENT_DATE')
        ->orderBy(['tb_caller.call_timestp' => SORT_ASC]);
        

        if (!empty($serviceid)) {
            $query->andWhere(['tb_quequ.serviceid' => $serviceid]);
        }
        if (!empty($counter_service_id)) {
            $query->andWhere(['tb_caller.counter_service_id' => $counter_service_id]);
        }

        return $query->all();
    }

}

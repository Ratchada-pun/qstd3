<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use frontend\modules\app\models\ReportSearchModel;
use frontend\modules\app\models\TbQuequData;
use frontend\modules\app\models\TbQuequ;
use frontend\modules\app\models\TbService;
use yii\helpers\Json;
use yii\base\DynamicModel;

class ReportController extends \yii\web\Controller
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
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $searchModel = new ReportSearchModel();
        $get = $request->get('ReportSearchModel', []);
        $searchModel->startdate = isset($get['startdate']) ? $get['startdate'] : date('Y-m-d');
        $searchModel->enddate = isset($get['enddate']) ? $get['enddate'] : date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDuration()
    {
        $request = Yii::$app->request;
        $post = $request->post();
        $from_date = isset($post['from_date']) ? $post['from_date'] : '0000-00-00';
        $serviceid = isset($post['serviceid']) ? $post['serviceid'] : null;

        $series = [];
        $series2 = [];
        $categories = [];
        if ($serviceid) {
            $services = TbService::find()->where(['serviceid' => $serviceid])->all();
        } else {
            $services = TbService::find()->all();
        }

        $h = 5;
        $dataGrid = [];
        for ($x = 1; $x <= 12; $x++) {
            $date1 = new \DateTime($from_date . ' ' . $h . ':00:00');
            $date1->modify('+1 hour');
            $hour1 = $date1->format('H:i:s') . PHP_EOL;

            $date2 = new \DateTime($from_date . ' ' . $h . ':00:00');
            $date2->modify('+2 hour');
            $hour2 = $date2->format('H:i:s') . PHP_EOL;

            $h++;

            $start = $from_date . ' ' . $hour1;
            $end = $from_date . ' ' . $hour2;
            $count = TbQuequ::find()->where(['between', 'created_at', $start, $end])->count();
            $time = substr($hour1, 0, 5) . '-' . substr($hour2, 0, 5);
            $categories = ArrayHelper::merge($categories, [$time]);
            $series[] = [
                "name" => $time,
                "y" => intval($count),
                "drilldown" => $time
            ];

            $dataGrid = ArrayHelper::merge($dataGrid, [
                't_' . $h => intval($count),
                'time_' . $h => $time
            ]);

            $drilldown = [];
            foreach ($services as $service) {
                $count = TbQuequ::find()->where(['between', 'created_at', $start, $end])->andWhere(['serviceid' => $service['serviceid']])->count();
                $drilldown[] = [
                    $service['service_name'], intval($count)
                ];
            }
            $series2[] = [
                'name' => $time,
                'id' => $time,
                'data' => $drilldown
            ];

            unset($drilldown);
        }
        $total = ArrayHelper::getColumn($series, 'y');
        $dataProvider = new ArrayDataProvider([
            'allModels' => [$dataGrid],
            'pagination' => [
                'pageSize' => false,
            ],
        ]);
        //return Json::encode($dataGrid);
        return $this->render('duration', [
            'categories' => $categories,
            'series' => $series,
            'series2' => $series2,
            'total' => array_sum($total),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDurationSummary()
    {
        $request = Yii::$app->request;
        $post = $request->post();
        $from_date = isset($post['from_date']) ? $post['from_date'] : null;
        $to_date = isset($post['to_date']) ? $post['to_date'] : null;
        $serviceid = isset($post['serviceid']) ? $post['serviceid'] : null;


        $times = [];
        $series = [];
        $series_2 = [];
        $drilldown = [];
        $arrService = [];
        $categories = [];

        $services = TbService::find()->all();

        if ($serviceid) {
            $services = TbService::find()->where(['serviceid' => $serviceid])->all();
        } else {
            $services = TbService::find()->all();
        }

        if ($from_date != null && $to_date != null) {
            $period = new \DatePeriod(
                new \DateTime($from_date),
                new \DateInterval('P1D'),
                new \DateTime(date('Y-m-d', strtotime('+1 day', strtotime($to_date))))
            );

            foreach ($period as $key => $value) {
                $day = $value->format('Y-m-d');
                $categories = ArrayHelper::merge($categories, [$day]);
                $array = [];
                $arrcount = [];
                $series2 = [];
                $subdrilldown = [];
                $serviceTotal = [];

                $sub_time = '';
                $h = 5;
                for ($i = 1; $i <= 12; $i++) {
                    $date1 = new \DateTime($day . ' ' . $h . ':00:00');
                    $date1->modify('+1 hour');
                    $hour1 = $date1->format('H:i:s') . PHP_EOL;

                    $date2 = new \DateTime($day . ' ' . $h . ':00:00');
                    $date2->modify('+2 hour');
                    $hour2 = $date2->format('H:i:s') . PHP_EOL;

                    $h++;

                    $start = $day . ' ' . $hour1;
                    $end = $day . ' ' . $hour2;
                    $count = TbQuequ::find()->where(['between', 'created_at', $start, $end])->count();
                    $time = substr($hour1, 0, 5) . '-' . substr($hour2, 0, 5);
                    $array = ArrayHelper::merge($array, [
                        't_' . $h => intval($count),
                        'day' => $day,
                    ]);
                    $arrcount[] = intval($count);
                    $sub_time = 'sub_' . $day . $time;
                    $series2[] = ['name' => $time, 'y' => intval($count), 'drilldown' => $sub_time];

                    foreach ($services as $service) {
                        $count = TbQuequ::find()->where(['between', 'created_at', $start, $end])->andWhere(['serviceid' => $service['serviceid']])->count();
                        $subdrilldown[] = [
                            $service['service_name'], intval($count)
                        ];
                    }

                    $drilldown[] = ['id' => $sub_time, 'data' => $subdrilldown];
                    unset($subdrilldown);
                }
                $array2 = [];
                foreach ($services as $service) {
                    $y = 5;
                    $xxCount = [];

                    for ($x = 1; $x <= 12; $x++) {
                        $d1 = new \DateTime($day . ' ' . $y . ':00:00');
                        $d1->modify('+1 hour');
                        $h1 = $d1->format('H:i:s') . PHP_EOL;

                        $d2 = new \DateTime($day . ' ' . $y . ':00:00');
                        $d2->modify('+2 hour');
                        $h2 = $d2->format('H:i:s') . PHP_EOL;

                        $y++;

                        $start1 = $day . ' ' . $h1;
                        $end1 = $day . ' ' . $h2;
                        $count = TbQuequ::find()->where(['between', 'created_at', $start1, $end1])->andWhere(['serviceid' => $service['serviceid']])->count();
                        $t = substr($h1, 0, 5) . '-' . substr($h2, 0, 5);
                        $array2 = ArrayHelper::merge($array2, [
                            't_' . $y => intval($count),
                            'day' => $day,
                        ]);
                        $xxCount[] = intval($count);
                    }
                    $arrService[] = ArrayHelper::merge([
                        'service_name' => $service['service_name'],
                        'total' => array_sum($xxCount),
                        'sum_all' => TbQuequ::find()->where(['between', 'created_at', $day . ' 06:00:00', $day . ' 18:00:00'])->count()
                    ], $array2);
                }

                $series[] = [
                    "name" => $day,
                    "y" => array_sum($arrcount),
                    "drilldown" => $day
                ];
                $drilldown[] = [
                    'name' => $day,
                    'id' => $day,
                    'data' => $series2
                ];
                $times[] = $array;
            }
            foreach ($services as $service) {
                $arr = [];
                foreach ($categories as $day) {
                    $count = TbQuequ::find()->where(['between', 'created_at', $day . ' 00:00:00', $day . ' 23:59:59'])->andWhere(['serviceid' => $service['serviceid']])->count();
                    $arr = ArrayHelper::merge($arr, [intval($count)]);
                }
                $series_2[] = [
                    'name' => $service['service_name'],
                    'data' => $arr,
                ];
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $times,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        $dataProviderService = new ArrayDataProvider([
            'allModels' => $arrService,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        //ArrayHelper::multisort($arrService, ['time', 'service_name'], [SORT_ASC, SORT_DESC]);
        //return Json::encode($arrService);

        return $this->render('duration-summary', [
            'dataProvider' => $dataProvider,
            'series' => $series,
            'drilldown' => $drilldown,
            'categories'  => $categories,
            'series_2' => $series_2,
            'dataProviderService' => $dataProviderService,
        ]);
    }

    public function actionTimewaiting()
    {
        $request = Yii::$app->request;
        $items = [];
        $columns = [];

        if ($request->isPost) {
            $services = TbService::find()->all();
            $service_types = (new \yii\db\Query())
                ->select(['tb_service_type.*'])
                ->from('tb_service_type')
                ->all();

            $date = $request->post('begin_date');
            $startdate = $date . ' 00:00:00';
            $enddate = $date . ' 23:59:59';


            // $sql2 = '
            // SELECT
            //     tb_quequ.q_ids,
            //     tb_quequ.q_num,
            //     tb_quequ.q_hn,
            //     tb_quequ.pt_name,
            //     tb_quequ.q_timestp AS q_timestp,
            //     tb_caller.caller_ids,
            //     tb_caller.call_timestp,
            //     tb_servicegroup.servicegroup_name,
            //     tb_service.service_name,
            //     tb_caller.created_at AS call_created,
            //     tb_caller.updated_at AS call_updated,
            //     tb_counterservice.counterservice_name
            // FROM
            //     tb_quequ
            //     LEFT JOIN tb_qtrans ON tb_qtrans.q_ids = tb_quequ.q_ids
            //     LEFT JOIN tb_caller ON tb_caller.qtran_ids = tb_qtrans.ids
            //     LEFT JOIN tb_servicegroup ON tb_servicegroup.servicegroupid = tb_quequ.servicegroupid
            //     LEFT JOIN tb_service ON tb_service.service_groupid = tb_servicegroup.servicegroupid
            //     LEFT JOIN tb_counterservice ON tb_counterservice.counterserviceid = tb_caller.counter_service_id
            //     LEFT JOIN tb_counterservice_type ON tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid
            // WHERE
            //     tb_quequ.q_timestp BETWEEN :startdate AND :enddate AND
            //     tb_quequ.serviceid = :serviceid AND
            //     tb_caller.caller_ids <> (:caller_ids) AND tb_quequ.q_num = :q_num
            // GROUP BY
            //     tb_quequ.q_ids
            // ORDER BY
            //     tb_quequ.q_ids ASC
            // ';

            // $sql3 = '
            // SELECT
            //     tb_quequ.q_ids,
            //     tb_quequ.q_num,
            //     tb_quequ.q_hn,
            //     tb_quequ.pt_name,
            //     tb_quequ.q_timestp AS q_timestp,
            //     tb_caller.caller_ids,
            //     tb_caller.call_timestp,
            //     tb_servicegroup.servicegroup_name,
            //     tb_service.service_name,
            //     tb_caller.created_at AS call_created,
            //     tb_caller.updated_at AS call_updated,
            //     MINUTE(TIMEDIFF(tb_quequ.q_timestp, tb_caller.call_timestp)) AS t_wait,
            //     tb_counterservice.counterservice_name
            // FROM
            //     tb_quequ
            //     INNER JOIN tb_qtrans ON tb_qtrans.q_ids = tb_quequ.q_ids
            //     INNER JOIN tb_caller ON tb_caller.qtran_ids = tb_qtrans.ids
            //     INNER JOIN tb_servicegroup ON tb_servicegroup.servicegroupid = tb_quequ.servicegroupid
            //     INNER JOIN tb_service ON tb_service.service_groupid = tb_servicegroup.servicegroupid
            //     INNER JOIN tb_counterservice ON tb_counterservice.counterserviceid = tb_caller.counter_service_id
            //     INNER JOIN tb_counterservice_type ON tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid
            // WHERE
            //     tb_quequ.q_timestp BETWEEN :startdate AND :enddate AND
            //     tb_quequ.serviceid = :serviceid
            // GROUP BY
            //     tb_quequ.q_ids
            // ORDER BY
            //     tb_quequ.q_ids ASC';

            $sql = '
            SELECT
                tb_quequ.q_ids,
                tb_quequ.q_num,
                tb_quequ.q_hn,
                tb_quequ.pt_name,
                tb_quequ.q_timestp AS q_timestp,
                tb_caller.caller_ids,
                tb_caller.call_timestp,
                tb_servicegroup.servicegroup_name,
                tb_service.service_name,
                tb_caller.created_at AS call_created,
                tb_caller.updated_at AS call_updated,
                MINUTE(TIMEDIFF(tb_quequ.q_timestp, tb_caller.call_timestp)) AS t_wait,
                tb_counterservice.counterservice_name
            FROM
                tb_quequ
                LEFT JOIN tb_qtrans ON tb_qtrans.q_ids = tb_quequ.q_ids
                LEFT JOIN tb_caller ON tb_caller.qtran_ids = tb_qtrans.ids
                LEFT JOIN tb_servicegroup ON tb_servicegroup.servicegroupid = tb_quequ.servicegroupid
                LEFT JOIN tb_service ON tb_service.service_groupid = tb_servicegroup.servicegroupid
                LEFT JOIN tb_counterservice ON tb_counterservice.counterserviceid = tb_caller.counter_service_id
                LEFT JOIN tb_counterservice_type ON tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid
            WHERE
                tb_quequ.q_timestp BETWEEN :startdate AND :enddate AND
                tb_quequ.serviceid = :serviceid AND tb_service.service_type_id = :service_type_id';

                foreach ($service_types as $key => $service_type) {
                    $columns[] =  [
                        'attribute' => 'service_type_id_' . $service_type['service_type_id'],
                        'header' => $service_type['service_type_name'] . ' (นาที)',
                        'hAlign' => 'center',
                    ];
                }


            foreach ($services as $service) {
                $colums_type = [];
                foreach ($service_types as $key => $service_type) {
                    $params = [
                        ':startdate' => $startdate,
                        ':enddate' => $enddate,
                        ':serviceid' => $service['serviceid'],
                        ':service_type_id' => $service_type['service_type_id'],
                    ];
                    $query = Yii::$app->db->createCommand($sql)->bindValues($params)->queryAll();

                    $timeswait = ArrayHelper::getColumn($query, 't_wait');
                    $avg = count($timeswait) > 0 ? array_sum($timeswait) / count($timeswait) : 0;
                    $colums_type = ArrayHelper::merge($colums_type, [
                        'service_type_id_' . $service_type['service_type_id'] => $avg,
                    ]);
                }

                $items[] = ArrayHelper::merge([
                    'serviceid' => $service['serviceid'],
                    'service_name' => $service['service_name'],
                ], $colums_type);

                // $datatimes = [];
                // foreach ($query as $val) {
                //     $params2 = [':startdate' => $startdate, ':enddate' => $enddate, ':serviceid' => $data['serviceid'], ':caller_ids' => $val['caller_ids'], ':q_num' => $val['q_num']];
                //     $model = Yii::$app->db->createCommand($sql2)->bindValues($params2)->queryOne();
                //     if ($model) {
                //         $datatimes[] = $this->diffDate($val['call_updated'], $model['call_timestp'], '%I');
                //     }
                // }
                // $avg2 = count($datatimes) > 0 ? array_sum($datatimes) / count($datatimes) : 0;
                // $items[] = [
                //     'serviceid' => $data['serviceid'],
                //     'service_name' => $data['service_name'],
                //     'times_wait' => $timeswait,
                //     'avg' => $avg,
                //     'times_wait2' => $datatimes,
                //     'avg2' => $avg2
                // ];
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        return $this->render('timewaiting', [
            'dataProvider' => $dataProvider,
            'columns' => $columns
        ]);
    }

    protected function diffDate($datetime1, $datetime2, $format = '%H ชม., %I น., %S วินาที')
    {
        $d1 = new \DateTime($datetime1);
        $d2 = new \DateTime($datetime2);
        $interval = $d1->diff($d2);
        return $interval->format($format);
    }
}

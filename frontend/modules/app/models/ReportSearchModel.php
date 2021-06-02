<?php
namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;

class ReportSearchModel extends Model
{
    public $ids;
    public $q_ids;
    public $checkin_date;
    public $checkout_date;
    public $q_num;
    public $pt_name;
    public $q_hn;
    public $qtrans_created_at;
    public $caller_updated_at;
    public $caller_ids;
    public $call_timestp;
    public $t_hours;
    public $t_minutes;
    public $t_seconds;
    public $t_waiting_to_finished;
    public $service_status_name;
    public $counterservice_name;
    public $counterservice_type;
    public $t_hours2;
    public $t_minutes2;
    public $t_seconds2;
    public $t_waiting_to_finished2;
    public $counterservice_name2;
    public $counterservice_type2;
    public $caller_ids2;
    public $caller_updated_at2;
    public $t_total;
    public $startdate;
    public $enddate;
    public $service_name;
    public $servicegroup_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'pt_name','q_hn','service_name'
            ], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ids' => '',
            'q_ids' => '',
            'checkin_date' => 'เวลาเช็คอิน',
            'checkout_date' => '',
            'q_num' => 'หมายเลขคิว',
            'pt_name' => 'ชื่อ-นามสกุล',
            'q_hn' => 'HN',
            'qtrans_created_at' => '',
            'caller_updated_at' => 'เวลาสิ้นสุด',
            'caller_ids' => '',
            'call_timestp' => 'เวลาเรียก',
            't_hours' => '',
            't_minutes' => '',
            't_seconds' => '',
            't_waiting_to_finished' => 'เวลาที่ใช้บริการ',
            'service_status_name' => '',
            'counterservice_name' => 'ห้อง/ช่อง/โต๊ะ',
            'counterservice_type' => 'จุดบริการ',
            'call_timestp2' => 'Calling Time',
            't_hours2' => '',
            't_minutes2' => '',
            't_seconds2' => '',
            't_waiting_to_finished2' => 'เวลาที่ใช้บริการ',
            'counterservice_name2' => 'ห้อง/ช่อง/โต๊ะ',
            'counterservice_type2' => 'จุดบริการที่ 2',
            'caller_ids2' => '',
            'caller_updated_at2' => 'End Time',
            't_total'  => 'รวมเวลาที่ใช้บริการ',
            'service_name' => 'ชื่อบริการ',
            'servicegroup_name' => 'กลุ่มบริการ',
            'q_timestp' => 'เวลาเริ่มใช้บริการ'
        ];
    }

    public function search($params)
    {
        $startdate = $this->startdate.' 00:00:00';
        $enddate = $this->enddate.' 23:59:59';
        $params1 = [':startdate' => $startdate, ':enddate' => $enddate];
        $sql1 = 'SELECT
       tb_qtrans.ids,
        tb_qtrans.q_ids,
        tb_qtrans.checkin_date,
        tb_qtrans.checkout_date,
        tb_quequ.q_num,
        tb_quequ.pt_name,
        tb_quequ.q_hn,
        tb_quequ.q_timestp,
        tb_qtrans.created_at AS qtrans_created_at,
        tb_caller.updated_at AS caller_updated_at,
        tb_caller.caller_ids,
        tb_caller.call_timestp,
        MOD(HOUR(TIMEDIFF(tb_qtrans.created_at, tb_caller.updated_at)), 24) AS t_hours,
        MINUTE(TIMEDIFF(tb_qtrans.created_at, tb_caller.updated_at)) AS t_minutes,
        SECOND(TIMEDIFF(tb_qtrans.created_at, tb_caller.updated_at)) AS t_seconds,
        CONCAT(
        MOD(HOUR(TIMEDIFF(tb_qtrans.created_at, tb_caller.updated_at)), 24), \' ชม. \',
        MINUTE(TIMEDIFF(tb_qtrans.created_at, tb_caller.updated_at)), \' น. \',
        SECOND(TIMEDIFF(tb_qtrans.created_at, tb_caller.updated_at)), \' วินาที\') AS t_waiting_to_finished,
        tb_service_status.service_status_name,
        tb_counterservice.counterservice_name,
        tb_counterservice_type.counterservice_type,
        tb_service.serviceid,
        tb_service.service_name,
        tb_servicegroup.servicegroup_name
        FROM
        tb_qtrans
        LEFT JOIN tb_quequ ON tb_quequ.q_ids = tb_qtrans.q_ids
        LEFT JOIN tb_caller ON tb_caller.qtran_ids = tb_qtrans.ids
        LEFT JOIN tb_service_status ON tb_service_status.service_status_id = tb_qtrans.service_status_id
        LEFT JOIN tb_counterservice ON tb_counterservice.counterserviceid = tb_caller.counter_service_id
        LEFT JOIN tb_counterservice_type ON tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid
        LEFT JOIN tb_service ON tb_service.serviceid = tb_quequ.serviceid
        LEFT JOIN tb_servicegroup ON tb_service.service_groupid = tb_servicegroup.servicegroupid
        WHERE
        (tb_qtrans.created_at BETWEEN :startdate AND :enddate) AND tb_quequ.pt_name <> \'\'
        ORDER BY
        tb_quequ.q_ids ASC';
        $query1 = Yii::$app->db->createCommand($sql1)->bindValues($params1)->queryAll();

        $records = [];
        foreach($query1 as $data){
            if(empty($data['pt_name'])){
                /* $arr = [
                    'call_timestp2' => '0000-00-00 00:00:00',
                    't_hours2' => '00',
                    't_minutes2' => '00',
                    't_seconds2' => '00',
                    't_waiting_to_finished2' => '00 ชม., 00 น., 00 วินาที',
                    'counterservice_name2' => '',
                    'counterservice_type2' => '',
                    'caller_ids2' => null,
                    'caller_updated_at2' => '0000-00-00 00:00:00',
                    't_total' => $data['t_waiting_to_finished'],
                ];
                $records[] = ArrayHelper::merge($data,$arr); */
            }else{
                $sql = 'SELECT
                tb_caller.caller_ids,
                tb_caller.q_ids,
                tb_caller.qtran_ids,
                tb_caller.servicegroupid,
                tb_caller.counter_service_id,
                tb_caller.call_timestp,
                tb_caller.created_by,
                tb_caller.created_at,
                tb_caller.updated_by,
                tb_caller.updated_at,
                tb_caller.call_status,
                tb_counterservice.counterservice_name,
                tb_counterservice_type.counterservice_type,
                tb_quequ.q_num,
                tb_service.serviceid,
                tb_service.service_name,
                tb_servicegroup.servicegroup_name
                FROM
                tb_caller
                LEFT JOIN tb_counterservice ON tb_counterservice.counterserviceid = tb_caller.counter_service_id
                LEFT JOIN tb_counterservice_type ON tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid
                LEFT JOIN tb_quequ ON tb_quequ.q_ids = tb_caller.q_ids
                LEFT JOIN tb_service ON tb_service.serviceid = tb_quequ.serviceid
                LEFT JOIN tb_servicegroup ON tb_service.service_groupid = tb_servicegroup.servicegroupid
                WHERE
                tb_caller.q_ids = :q_ids AND
                tb_caller.qtran_ids = :qtran_ids AND
                tb_caller.caller_ids <> :caller_ids
                ';
                $params = [':q_ids' => $data['q_ids'], ':qtran_ids' => $data['ids'],':caller_ids' => $data['caller_ids']];
                $model = Yii::$app->db->createCommand($sql)->bindValues($params)->queryOne();

                if($model){
                    // $arr = [
                    //     'call_timestp2' => $model['call_timestp'],
                    //     't_hours2' => $this->diffDate($data['caller_updated_at'],$model['updated_at'],'%H'),
                    //     't_minutes2' => $this->diffDate($data['caller_updated_at'],$model['updated_at'],'%I'),
                    //     't_seconds2' => $this->diffDate($data['caller_updated_at'],$model['updated_at'],'%S'),
                    //     't_waiting_to_finished2' => $this->diffDate($data['caller_updated_at'],$model['updated_at']),
                    //     'counterservice_name2' => $model['counterservice_name'],
                    //     'counterservice_type2' => $model['counterservice_type'],
                    //     'caller_ids2' => $model['caller_ids'],
                    //     'caller_updated_at2' => $model['updated_at'],
                    //     't_total' => $this->diffDate($data['qtrans_created_at'],$model['updated_at']),
                    // ];
                    $data['t_total'] = $this->diffDate($data['qtrans_created_at'],$model['updated_at']);
                    $records[] = $data;
                    $data['t_hours'] = $this->diffDate($data['caller_updated_at'],$model['updated_at'],'%H');
                    $data['t_minutes'] =  $this->diffDate($data['caller_updated_at'],$model['updated_at'],'%I');
                    $data['t_seconds'] =  $this->diffDate($data['caller_updated_at'],$model['updated_at'],'%S');
                    $data['t_waiting_to_finished'] = $this->diffDate($data['caller_updated_at'],$model['updated_at']);
                    $data['counterservice_name'] = $model['counterservice_name'];
                    $data['counterservice_type'] = $model['counterservice_type'];
                    $data['caller_ids'] = $model['caller_ids'];
                    $data['caller_updated_at'] = $model['updated_at'];
                    $data['call_timestp'] = $model['call_timestp'];
                    $data['service_name'] = $model['service_name'];
                    $records[] = $data;
                }else{
                    $data['t_total'] = $data['t_waiting_to_finished'];
                    $records[] = $data;
                    // $arr = [
                    //     'call_timestp2' => '0000-00-00 00:00:00',
                    //     't_hours2' => '00',
                    //     't_minutes2' => '00',
                    //     't_seconds2' => '00',
                    //     't_waiting_to_finished2' => '00 ชม., 00 น., 00 วินาที',
                    //     'counterservice_name2' => '',
                    //     'counterservice_type2' => '',
                    //     'caller_ids2' => null,
                    //     'caller_updated_at2' => '0000-00-00 00:00:00',
                    //     't_total' => $data['t_waiting_to_finished'],
                    // ];
                    // $records[] = ArrayHelper::merge($data,$arr);
                }
            }
        }
        if (isset($_GET['ReportSearchModel'])) {
            $get = Yii::$app->request->get('ReportSearchModel',[]);
            if(isset($get['pt_name'])){
                $records = $this->filterArray($records,strtolower(trim($get['pt_name'])),'pt_name');
                $this->pt_name = $get['pt_name'];
            }
            if(isset($get['q_hn'])){
                $records = $this->filterArray($records,strtolower(trim($get['q_hn'])),'q_hn');
                $this->q_hn = $get['q_hn'];
            }
            if(isset($get['service_name'])){
                $records = $this->filterArray($records,strtolower(trim($get['service_name'])),'service_name');
                $this->service_name = $get['service_name'];
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $records,
            'pagination' => [
                'pageSize' => false,
            ],
            'sort' => [
                'attributes' => ['ids'],
            ],
            'key' => 'ids'
        ]);
        return $dataProvider;
    }

    protected function diffDate($datetime1,$datetime2,$format = '%H ชม., %I น., %S วินาที'){
        $d1 = new \DateTime($datetime1);
        $d2 = new \DateTime($datetime2);
        $interval = $d1->diff($d2);
        return $interval->format($format);
    }

    protected function filterArray($records,$value,$attr){
        if(!empty($value)){
            $records = array_filter($records, function ($role) use ($value,$attr) {
                return (empty($value) || strpos((strtolower(is_object($role) ? $role->{$attr} : $role[$attr])), $value) !== false);
            });
        }
        return $records;
    }

}
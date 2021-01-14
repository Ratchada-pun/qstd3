<?php
namespace frontend\modules\app\components;

use Yii;
use yii\base\BaseObject;

class HisQuery extends BaseObject
{
	public $hn;

	public $vstdate;

	public $search_by;

	public function init()
    {
        parent::init();
        $this->vstdate = !empty($this->vstdate) ? static::convertDate($this->vstdate, 'php:Y-m-d') : null;
    }

    public function getData(){
		$db = $this->db;
		if($this->search_by == 0){//HN
			$command = 'SELECT
					`patient`.`hometel` AS `hometel`,
					`patient`.`hn` AS `hn`,
					`patient`.`pname` AS `pname`,
					`patient`.`fname` AS `fname`,
					`patient`.`lname` AS `lname`,
					`patient`.`cid` AS `cid`,
					`vn_stat`.`vn` AS `vn`,
					`vn_stat`.`dx_doctor` AS `dx_doctor`,
					`vn_stat`.`vstdate` AS `vstdate`,
					`doctor`.`name` AS `doctor_name`,
					concat( `patient`.`pname`, `patient`.`fname`, \' \', `patient`.`lname` ) AS `fullname`
				FROM
					(
					( `patient` JOIN `vn_stat` ON ( ( `vn_stat`.`hn` = `patient`.`hn` ) ) )
					LEFT JOIN `doctor` ON ( ( `doctor`.`code` = `vn_stat`.`dx_doctor` ) )
					)
				WHERE
					`vn_stat`.`vstdate` = :vstdate AND `patient`.`hn` LIKE :hn';
			$data = $db->createCommand($command)->bindValue(':vstdate', $this->vstdate)->bindValue(':hn', '%'.$this->hn)->queryOne();
		}else{//CID
			$command = 'SELECT
					`patient`.`hometel` AS `hometel`,
					`patient`.`hn` AS `hn`,
					`patient`.`pname` AS `pname`,
					`patient`.`fname` AS `fname`,
					`patient`.`lname` AS `lname`,
					`patient`.`cid` AS `cid`,
					`vn_stat`.`vn` AS `vn`,
					`vn_stat`.`dx_doctor` AS `dx_doctor`,
					`vn_stat`.`vstdate` AS `vstdate`,
					`doctor`.`name` AS `doctor_name`,
					concat( `patient`.`pname`, `patient`.`fname`, \' \', `patient`.`lname` ) AS `fullname`
				FROM
					(
					( `patient` JOIN `vn_stat` ON ( ( `vn_stat`.`hn` = `patient`.`hn` ) ) )
					LEFT JOIN `doctor` ON ( ( `doctor`.`code` = `vn_stat`.`dx_doctor` ) )
					)
				WHERE
					`vn_stat`.`vstdate` = :vstdate AND `patient`.`cid` = :cid';
			$data = $db->createCommand($command)->bindValue(':vstdate', $this->vstdate)->bindValue(':cid', $this->hn)->queryOne();
		}
		return $data ? $data : false;
    }

    protected function getDb(){
    	return \Yii::$app->db_his;
    }

    public static function convertDate($date = null){
        $result = '';
        if(!empty($date)){
            $arr = explode("/", $date);
            $y = $arr[2];
            $m = $arr[1];
            $d = $arr[0];
            $result = "$y-$m-$d";
        }
        return $result;
    }
}
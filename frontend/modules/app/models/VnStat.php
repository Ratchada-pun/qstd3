<?php

namespace frontend\modules\app\models;

use Yii;

/**
 * This is the model class for table "vn_stat".
 *
 * @property string $vn
 * @property string $hn
 * @property string $pdx
 * @property int $gr504
 * @property int $lastvisit
 * @property string $accident_code
 * @property string $dx_doctor
 * @property string $dx0
 * @property string $dx1
 * @property string $dx2
 * @property string $dx3
 * @property string $dx4
 * @property string $dx5
 * @property string $sex
 * @property int $age_y
 * @property int $age_m
 * @property int $age_d
 * @property string $aid
 * @property string $moopart
 * @property int $count_in_month
 * @property int $count_in_year
 * @property string $pttype
 * @property double $income
 * @property double $paid_money
 * @property double $remain_money
 * @property double $uc_money
 * @property double $item_money
 * @property int $dba
 * @property string $spclty
 * @property string $vstdate
 * @property string $op0
 * @property string $op1
 * @property string $op2
 * @property string $op3
 * @property string $op4
 * @property string $op5
 * @property string $rcp_no
 * @property int $print_count
 * @property string $print_done
 * @property string $pttype_in_region
 * @property string $pttype_in_chwpart
 * @property string $pcode
 * @property string $hcode
 * @property double $inc01
 * @property double $inc02
 * @property double $inc03
 * @property double $inc04
 * @property double $inc05
 * @property double $inc06
 * @property double $inc07
 * @property double $inc08
 * @property double $inc09
 * @property double $inc10
 * @property double $inc11
 * @property double $inc12
 * @property double $inc13
 * @property double $inc14
 * @property double $inc15
 * @property double $inc16
 * @property string $hospmain
 * @property string $hospsub
 * @property string $pttypeno
 * @property string $pttype_expire
 * @property string $cid
 * @property string $main_pdx
 * @property double $inc17
 * @property double $inc_drug
 * @property double $inc_nondrug
 * @property int $pt_subtype
 * @property string $rcpno_list
 * @property string $ym
 * @property string $node_id
 * @property string $ill_visit
 * @property int $count_in_day
 * @property string $pttype_begin
 * @property int $lastvisit_hour
 * @property double $rcpt_money
 * @property double $discount_money
 * @property string $old_diagnosis
 * @property string $debt_id_list
 * @property string $vn_guid
 * @property string $lastvisit_vn
 * @property string $hos_guid
 * @property string $rx_license_no
 */
class VnStat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vn_stat';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_his');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vn'], 'required'],
            [['gr504', 'lastvisit', 'age_y', 'age_m', 'age_d', 'count_in_month', 'count_in_year', 'lastvisit_hour'], 'integer'],
            [['income', 'paid_money', 'remain_money', 'uc_money', 'item_money', 'inc01', 'inc02', 'inc03', 'inc04', 'inc05', 'inc06', 'inc07', 'inc08', 'inc09', 'inc10', 'inc11', 'inc12', 'inc13', 'inc14', 'inc15', 'inc16', 'inc17', 'inc_drug', 'inc_nondrug', 'rcpt_money', 'discount_money'], 'number'],
            [['vstdate', 'pttype_expire', 'pttype_begin'], 'safe'],
            [['vn', 'cid', 'lastvisit_vn'], 'string', 'max' => 13],
            [['hn'], 'string', 'max' => 9],
            [['pdx', 'accident_code', 'dx0', 'dx1', 'dx2', 'dx3', 'dx4', 'dx5', 'aid', 'op0', 'op1', 'op2', 'op3', 'op4', 'op5'], 'string', 'max' => 6],
            [['dx_doctor', 'ym'], 'string', 'max' => 7],
            [['sex', 'print_done', 'pttype_in_region', 'pttype_in_chwpart', 'node_id', 'ill_visit', 'old_diagnosis'], 'string', 'max' => 1],
            [['moopart', 'hcode', 'hospmain', 'hospsub'], 'string', 'max' => 5],
            [['pttype', 'spclty', 'pcode'], 'string', 'max' => 2],
            [['dba', 'print_count', 'pt_subtype', 'count_in_day'], 'string', 'max' => 4],
            [['rcp_no'], 'string', 'max' => 10],
            [['pttypeno'], 'string', 'max' => 20],
            [['main_pdx'], 'string', 'max' => 3],
            [['rcpno_list'], 'string', 'max' => 25],
            [['debt_id_list'], 'string', 'max' => 50],
            [['vn_guid', 'hos_guid'], 'string', 'max' => 38],
            [['rx_license_no'], 'string', 'max' => 15],
            [['vn'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vn' => 'Vn',
            'hn' => 'Hn',
            'pdx' => 'Pdx',
            'gr504' => 'Gr504',
            'lastvisit' => 'Lastvisit',
            'accident_code' => 'Accident Code',
            'dx_doctor' => 'Dx Doctor',
            'dx0' => 'Dx0',
            'dx1' => 'Dx1',
            'dx2' => 'Dx2',
            'dx3' => 'Dx3',
            'dx4' => 'Dx4',
            'dx5' => 'Dx5',
            'sex' => 'Sex',
            'age_y' => 'Age Y',
            'age_m' => 'Age M',
            'age_d' => 'Age D',
            'aid' => 'Aid',
            'moopart' => 'Moopart',
            'count_in_month' => 'Count In Month',
            'count_in_year' => 'Count In Year',
            'pttype' => 'Pttype',
            'income' => 'Income',
            'paid_money' => 'Paid Money',
            'remain_money' => 'Remain Money',
            'uc_money' => 'Uc Money',
            'item_money' => 'Item Money',
            'dba' => 'Dba',
            'spclty' => 'Spclty',
            'vstdate' => 'Vstdate',
            'op0' => 'Op0',
            'op1' => 'Op1',
            'op2' => 'Op2',
            'op3' => 'Op3',
            'op4' => 'Op4',
            'op5' => 'Op5',
            'rcp_no' => 'Rcp No',
            'print_count' => 'Print Count',
            'print_done' => 'Print Done',
            'pttype_in_region' => 'Pttype In Region',
            'pttype_in_chwpart' => 'Pttype In Chwpart',
            'pcode' => 'Pcode',
            'hcode' => 'Hcode',
            'inc01' => 'Inc01',
            'inc02' => 'Inc02',
            'inc03' => 'Inc03',
            'inc04' => 'Inc04',
            'inc05' => 'Inc05',
            'inc06' => 'Inc06',
            'inc07' => 'Inc07',
            'inc08' => 'Inc08',
            'inc09' => 'Inc09',
            'inc10' => 'Inc10',
            'inc11' => 'Inc11',
            'inc12' => 'Inc12',
            'inc13' => 'Inc13',
            'inc14' => 'Inc14',
            'inc15' => 'Inc15',
            'inc16' => 'Inc16',
            'hospmain' => 'Hospmain',
            'hospsub' => 'Hospsub',
            'pttypeno' => 'Pttypeno',
            'pttype_expire' => 'Pttype Expire',
            'cid' => 'Cid',
            'main_pdx' => 'Main Pdx',
            'inc17' => 'Inc17',
            'inc_drug' => 'Inc Drug',
            'inc_nondrug' => 'Inc Nondrug',
            'pt_subtype' => 'Pt Subtype',
            'rcpno_list' => 'Rcpno List',
            'ym' => 'Ym',
            'node_id' => 'Node ID',
            'ill_visit' => 'Ill Visit',
            'count_in_day' => 'Count In Day',
            'pttype_begin' => 'Pttype Begin',
            'lastvisit_hour' => 'Lastvisit Hour',
            'rcpt_money' => 'Rcpt Money',
            'discount_money' => 'Discount Money',
            'old_diagnosis' => 'Old Diagnosis',
            'debt_id_list' => 'Debt Id List',
            'vn_guid' => 'Vn Guid',
            'lastvisit_vn' => 'Lastvisit Vn',
            'hos_guid' => 'Hos Guid',
            'rx_license_no' => 'Rx License No',
        ];
    }

    /**
     * @inheritdoc
     * @return VnStatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VnStatQuery(get_called_class());
    }
}

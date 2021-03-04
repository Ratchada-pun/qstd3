<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\modules\app\models\QueuesInterface;
use kartik\widgets\DepDrop;
use kartik\form\ActiveForm; // or kartik\widgets\ActiveForm
$this->registerCss($this->render('style.css'));
// 
?>
<div class="hpanel hyellow">

    <div class="panel-body">
        <h4>หมายเลขคิว : <?= $model['q_num'] ?>(<?= $model['pt_visit_type_id'] === '2'  ? 'นัดตรวจ' : 'Walk in' ?>)</h4>
        <h6><b>HN : </b><?= $model['q_hn'] ?><b> VN :</b> </b><?= $model['VN'] ?> </h6>
        <h6> <b>ห้องตรวจ : </b><?= $model['counterservice_name'] ?> </h6>
        <h6> <b>แพทย์ : </b><?= $model['doctor_name'] ?> </h6>
        <h6><b>เวลาพบแพทย์ : </b><?= $model['checkin_date'] ?> </h6>
        <h6><b>ชื่อผู้ป่วย : </b><?= $model['pt_name'] ?> </h6>
        <h6><b>HN : </b><?= $model['q_hn'] ?> </h6>
        <h6><b>VN : </b><?= $model['VN'] ?> </h6>
        <h6><b>LAB : </b>
            <?php
            $lab = QueuesInterface::find()->where(['VN' => $model['VN']])->one();
            if ($lab['lab'] === 'ผลออกครบ') {
                echo \kartik\helpers\Html::badge($lab['lab'], ['class' => 'badge', 'style' => 'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;']);
            } else if ($lab['lab'] === 'รอผล') {
                echo \kartik\helpers\Html::badge($lab['lab'], ['class' => 'badge', 'style' => 'background-color:orange;color:#ffffff;text-align:center;font-size:16px;']);
            } else {
                echo $lab['lab'];
            }
            ?>
        </h6>
        <h6><b>X-RAY : </b>
            <?php
            $xray = QueuesInterface::find()->where(['VN' => $model['VN']])->one();
            if ($xray['xray'] === 'ผลออกครบ') {
                echo \kartik\helpers\Html::badge($xray['xray'], ['class' => 'badge', 'style' => 'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;']);
            } else if ($xray['xray'] === 'รอผล') {
                echo \kartik\helpers\Html::badge($xray['xray'], ['class' => 'badge', 'style' => 'background-color:orange;color:#ffffff;text-align:center;font-size:16px;']);
            } else {
                echo $xray['xray'];
            }
            ?>
        </h6>
        <h6><b>SP : </b>
            <?php
            $SP = QueuesInterface::find()->where(['VN' => $model['VN']])->one();
            if ($SP['SP'] === 'ผลออกครบ') {
                echo \kartik\helpers\Html::badge($SP['SP'], ['class' => 'badge', 'style' => 'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;']);
            } else if ($SP['SP'] === 'รอผล') {
                echo \kartik\helpers\Html::badge($SP['SP'], ['class' => 'badge', 'style' => 'background-color:orange;color:#ffffff;text-align:center;font-size:16px;']);
            } else {
                echo $SP['SP'];
            }
            ?>
        </h6>
    </div>
    <div class="panel-footer text-center">
        <div class="row">
            <div class="col-md-12">
                <button href="#" class="btn btn-info btn_callhold" id="<?= $model['q_ids'] ?>" class="col-md-3">เรียกคิว</button>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs($this->render('script-mobile.js'));
?>
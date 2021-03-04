<?php
// _list_item.php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\modules\app\models\QueuesInterface;
use frontend\modules\app\models\TbDoctorStatus;
use kartik\widgets\DepDrop;
use kartik\form\ActiveForm; // or kartik\widgets\ActiveForm
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

$this->registerCss($this->render('style.css'));

//
?>

<div class="hpanel hyellow">
    <div class="panel-heading hbuilt text-center" data-key="<?= $model[
        'ids'
    ] ?>" <?= $model['q_status_id'] == 2
    ? "style=\"background-color:#66ff66\""
    : '' ?>>
        <h4>หมายเลขคิว : <?= $model['q_num'] ?>(<?= $model[
    'pt_visit_type_id'
] === '2'
    ? 'นัดตรวจ'
    : 'Walk in' ?>)</h4>

        <h5><b>HN : </b><?= $model['q_hn'] ?><b> VN :</b> </b><?= $model[
    'VN'
] ?> </h5>
        <?php Pjax::begin(['id' => 'alerttime']); ?>
        <?php
        $sec = 0;
        foreach (array_reverse(explode(':', date('H:i:s'))) as $k => $v) {
            $sec += pow(60, $k) * $v;
        }
        // echo intval($sec) . '|';
        // echo ($model['appoint_time']) . '|';
        // echo (\Yii::$app->keyStorage->get('alerttime')) . '|';
        if (
            intval($sec) >
            intval($model['appoint_time']) +
                intval(\Yii::$app->keyStorage->get('alerttime')) * 60
        ) {
            echo '<div style="color:red!important;" class="blink"><b>' .
                'รอนานเกิน ' .
                \Yii::$app->keyStorage->get('alerttime') .
                ' นาที' .
                '</b></div>'; //do something to delay the execution by $i seconds
        }
        ?>
        <?php Pjax::end(); ?>
    </div>
    <div class="panel-body">
        <h5> <b>ห้องตรวจ : </b><?= $model['counterservice_name'] ?> </h5>
        <div>
            <h5> <b>แพทย์ : </b><?= $model['doctor_name'] ?> </h5>
        </div>
        <div>
            <h5> <b>สถานะแพทย์ : </b></h5>
                <div class="col-md-12">
                    <div class="col-md-4">
                        <?= Select2::widget([
                            'name' => 'Status_T',
                            'value' => $model['ID'],
                            'data' => ArrayHelper::map(
                                $doctorStatus = TbDoctorStatus::find()
                                    ->orderBy('ID')
                                    ->asArray()
                                    ->all(),
                                'ID',
                                'Status_T'
                            ),

                            'options' => [
                                'placeholder' => 'เลือกสถานะแพทย์',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'pluginEvents' => [
                                'change' =>
                                    "function() {

                                    var status = $(this).val()
                                    $.ajax({
                                        method: 'POST',
                                        url: '" .
                                    Url::base(true) .
                                    "/app/mobiletest/save-doc-status',
                                        data:{
                                            Doctor_name:\"" .
                                    $model['doctor_name'] .
                                    "\",
                                            Status: $(this).val()
                                        },
                                        dataType: 'json',
                                        success: function (res) {
                                          console.log(res)
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.log(errorThrown)
                                        },
                                    })
                                }",
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-8"></div>
                </div>
        </div>
        <br>
        <br>
        <h5><b>เวลาพบแพทย์ : </b><?= $model['checkin_date'] ?> </h5>
        <h5><b>ชื่อผู้ป่วย : </b><?= $model['pt_name'] ?> </h5>
        <h5><b>LAB : </b>
            <?php
            $lab = QueuesInterface::find()
                ->where(['VN' => $model['VN']])
                ->one();
            if ($lab['lab'] === 'ผลออกครบ') {
                echo \kartik\helpers\Html::badge($lab['lab'], [
                    'class' => 'badge',
                    'style' =>
                        'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;',
                ]);
            } elseif ($lab['lab'] === 'รอผล') {
                echo \kartik\helpers\Html::badge($lab['lab'], [
                    'class' => 'badge',
                    'style' =>
                        'background-color:orange;color:#ffffff;text-align:center;font-size:16px;',
                ]);
            } else {
                echo $lab['lab'];
            }
            ?>
        </h5>
        <h5><b>X-RAY : </b>
            <?php
            $xray = QueuesInterface::find()
                ->where(['VN' => $model['VN']])
                ->one();
            if ($xray['xray'] === 'ผลออกครบ') {
                echo \kartik\helpers\Html::badge($xray['xray'], [
                    'class' => 'badge',
                    'style' =>
                        'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;',
                ]);
            } elseif ($xray['xray'] === 'รอผล') {
                echo \kartik\helpers\Html::badge($xray['xray'], [
                    'class' => 'badge',
                    'style' =>
                        'background-color:orange;color:#ffffff;text-align:center;font-size:16px;',
                ]);
            } else {
                echo $xray['xray'];
            }
            ?>
        </h5>
        <h5><b>SP : </b>
            <?php
            $SP = QueuesInterface::find()
                ->where(['VN' => $model['VN']])
                ->one();
            if ($SP['SP'] === 'ผลออกครบ') {
                echo \kartik\helpers\Html::badge($SP['SP'], [
                    'class' => 'badge',
                    'style' =>
                        'background-color:#6d953a;color:#ffffff;text-align:center;font-size:16px;',
                ]);
            } elseif ($SP['SP'] === 'รอผล') {
                echo \kartik\helpers\Html::badge($SP['SP'], [
                    'class' => 'badge',
                    'style' =>
                        'background-color:orange;color:#ffffff;text-align:center;font-size:16px;',
                ]);
            } else {
                echo $SP['SP'];
            }
            ?>
        </h5>
    </div>
    <div class="panel-footer text-center">
        <div class="row">
            <div class="col-md-12">
                <button href="#" class="btn btn-info btn_call" id="<?= $model[
                    'q_ids'
                ] ?>" class="col-md-3">เรียกคิว</button>
                <button href="#" class="btn btn-success btn_recall" id="<?= $model[
                    'q_ids'
                ] ?>'" class="col-md-3">เรียกซ้ำ</button>
                <button href="#" class="btn btn-warning btn_hold" id="<?= $model[
                    'q_ids'
                ] ?>'" class="col-md-3">&nbsp;&nbsp;พักคิว&nbsp;</button>
                <button href="#" class="btn btn-danger btn_end" id="<?= $model[
                    'q_ids'
                ] ?>'" class="col-md-3">เสร็จสิ้น</button>

            </div>
        </div>
    </div>
</div>

<?php $this->registerJs($this->render('script-mobile.js'));
?>

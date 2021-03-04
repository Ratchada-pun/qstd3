<?php
use yii\bootstrap\Tabs;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
use yii\icons\Icon;
use homer\widgets\Table;
use frontend\modules\app\models\TbCounterservice;
use frontend\modules\app\models\TbServiceProfile;
use yii\helpers\ArrayHelper;
#assets
use homer\assets\SocketIOAsset;
use homer\assets\jPlayerAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use homer\assets\ICheckAsset;
use homer\assets\HomerAdminAsset;

SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);
ICheckAsset::register($this);

$this->title = 'เรียกคิวห้องตรวจ';
$this->registerCss($this->render('style-exam.css'));
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var modelForm = ' . Json::encode($modelForm) . '; ', View::POS_HEAD);
$this->registerJs('var modelProfile = ' . Json::encode($modelProfile) . '; ', View::POS_HEAD);
$this->registerJs('var select2Data = ' . Json::encode(ArrayHelper::map(TbCounterservice::find()
    ->where(['counterservice_type' => $modelProfile['counterservice_typeid'],'counterservice_status' => 1])
    ->asArray()->orderBy(['service_order' => SORT_ASC])
    ->all(), 'counterserviceid', 'counterservice_name')
    ) . '; ', View::POS_HEAD);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">

        <div class="hpanel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 text-center text-tablet-mode" style="display: none;">
                        <p><span style="font-weight: bold;text-align: center;font-size: 18px;">ห้องตรวจ</span></p>
                    </div>
                </div>
                <!-- Begin From -->
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="hpanel">
                        <div class="col-md-3">
                            <div class="col-md-6" style="font-size:14px;">
                                คิวทั้งหมด:
                            </div>
                            <div class="col-md-6" style="font-size:14px;border: 1.5px solid #e2be7d;padding-left:10px;padding-bottom: 0px;padding-top: 0px;">
                                <center id="statusall">0</centerร>
                            </div>
                        </div>

                        <!-- <div class="col-md-3">
                            <div class="col-md-6" style="font-size:14px;">รอผลตรวจ:</div>
                            <div class="col-md-6" style="font-size:14px;border: 1.5px solid #e2be7d;padding-left:10px;padding-bottom: 0px;padding-top: 0px;">
                                <center>0</center>
                            </div>
                        </div> -->
                        <div class="col-md-3">
                            <div class="col-md-6" style="font-size:14px;">คิวพัก:</div>
                            <div class="col-md-6" style="font-size:14px;border: 1.5px solid #e2be7d;padding-left:10px;padding-bottom: 0px;padding-top: 0px;">
                                <center class="count-hold">0</center>
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="col-md-6" style="font-size:14px;">คิวรอ Consult:</div>
                            <div class="col-md-6" style="font-size:14px;border: 1.5px solid #e2be7d;padding-left:10px;padding-bottom: 0px;padding-top: 0px;">
                                <center>0</center>
                            </div>
                        </div> -->
                        <!-- <div>
                            <button id="test">test</button>
                        </div> -->

                    </div>
                    <div class="hpanel panel-form">
                        <div class="panel-heading">
                            <div class="panel-tools">
                                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                            </div>
                            <div class="checkbox" style="display: inline-block;margin-bottom: 0px;">
                                <label>
                                    <input type="checkbox" value="0" name="tablet-mode" id="fullscreen-toggler">
                                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                    <i class="pe-7s-expand1"></i> Fullscreen
                                </label>
                            </div>
                            <span class="panel-heading-text" style="font-size: 18px;">&nbsp;</span>
                        </div>
                        <div class="panel-body" style="border: 1.5px solid #e2be7d;padding-left:10px;padding-bottom: 0px;padding-top: 0px;">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'calling-form',
                                'type' => 'horizontal',
                                'options' => ['autocomplete' => 'off'],
                                'formConfig' => ['showLabels' => false],
                            ]) ?>
                            <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                <div class="col-md-2">
                                    <div style="color:#e2be7d;font-size:18px;padding-left:20px;border-bottom:2px solid #e2be7d;">
                                        ห้องตรวจ
                                    </div>
                                    <div style="font-size:50px">
                                        <center id="C_N">00</center>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="color:blue;font-size:18px;padding-left:20px;border-bottom:2px solid blue;">
                                        หมายเลข
                                    </div>
                                    <div style="font-size:50px">
                                        <center id="Q_N">00000</center>
                                    </div>
                                </div>
                                <div class="col-md-4" style="margin-top:20px">
                                    <center>
                                        <a href="#" class="btn btn-success btn-lg" id="btncall">เรียกคิว</a>
                                        <a href="#" class="btn btn-primary btn-lg" id="btnrecall">เรียกซ้ำ</a>
                                        <a href="#" class="btn btn-warning btn-lg" id="btnhold">พักคิว</a>
                                        <a href="#" class="btn btn-danger btn-lg" id="btnend">เสร็จสิ้น</a>
                                    </center>
                                </div>
                                <div class="col-md-3 service_profile" style="margin-top:20px">
                                    <?=
                                        $form->field($modelForm, 'service_profile')->widget(Select2::class, [
                                            'data' => ArrayHelper::map(TbServiceProfile::find()->where(['service_profile_status' => 1])->asArray()->all(), 'service_profile_id', 'service_name'),
                                            'options' => ['placeholder' => 'เลือกโปรไฟล์...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'size' => Select2::LARGE,
                                            'pluginEvents' => [
                                                "change" => "function() {
                                            if($(this).val() != '' && $(this).val() != null){
                                                location.replace(baseUrl + \"/app/calling/examination-room?profileid=\" + $(this).val());
                                            }else{
                                                location.replace(baseUrl + \"/app/calling/examination-room\");
                                            }
                                        }",
                                            ]
                                        ]);
                                    ?>
                                </div>


                                <!-- <div class="col-md-3 last-queue">
                                    <ul class="list-group">
                                        <li class="list-group-item" style="font-size:18px;">
                                            <span class="badge badge-primary" style="font-size:18px;" id="last-queue">-</span>
                                            คิวที่เรียกล่าสุด
                                        </li>
                                    </ul>
                                </div> -->

                                <!-- <div class="col-md-3">
                                    <?
                                    // = $form->field($modelForm, 'qnum')->textInput([
                                    //     'class' => 'input-lg',
                                    //     'placeholder' => 'คีย์หมายเลขคิวที่นี่เพื่อเรียก',
                                    //     'style' => 'background-color: #434a54;color: white;',
                                    //     'autofocus' => true
                                    // ])->hint(''); 
                                    ?>
                                </div> -->
                                <!-- <div class="col-md-3">
                                    <p>
                                        <?
                                        // = Html::a('CALL NEXT', false, ['class' => 'btn btn-lg btn-block btn-primary activity-callnext']); 
                                        ?>
                                    </p>
                                </div> -->
                            </div>
                            <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                <div class="col-md-12">
                                    <?= $form->field($modelForm, 'counter_service')->checkboxList($modelForm->getDataCounterserviceEx($modelProfile), [
                                        'inline' => true,
                                        'class' => 'i-checks'
                                    ]) ?>
                                </div>
                            </div>
                            <?php ActiveForm::end() ?>
                        </div><!-- End panel body -->
                    </div><!-- End hpanel -->
                </div>

                <div class="form-group call-next-tablet-mode" style="margin-bottom: 5px;display: none">
                    <div class="col-md-9" style="padding-bottom: 5px;">
                        <ul class="list-group">
                            <li class="list-group-item text-primary" style="font-size:18px;">
                                <span class="badge badge-primary last-queue" style="font-size:18px;" id="last-queue">-</span>
                                คิวที่เรียกล่าสุด
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <p>
                            <?= Html::a('CALL NEXT', false, ['class' => 'btn btn-lg btn-block btn-primary activity-callnext']); ?>
                        </p>
                    </div>
                </div>

                <!-- End Form -->

                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:20px">
                    <!-- Begin Panel -->
                    <div class="hpanel">
                        <?php
                        echo Tabs::widget([
                            'items' => [
                                [
                                    'label' => 'รอเรียก' . Html::tag('span', '0', ['id' => 'count-waiting', 'class' => 'badge count-waiting']),
                                    'active' => true,
                                    'options' => ['id' => 'tab-waiting'],
                                    'linkOptions' => ['style' => 'font-size: 14px;'],
                                    'headerOptions' => ['class' => 'tab-waiting']
                                ],
                                [
                                    'label' => 'กำลังเรียก ' . Html::tag('span', '0', ['id' => 'count-calling', 'class' => 'badge count-calling']),
                                    'options' => ['id' => 'tab-calling'],
                                    'linkOptions' => ['style' => 'font-size: 14px;'],
                                    'headerOptions' => ['class' => 'tab-calling']
                                ],
                                [
                                    'label' => 'พักคิว ' . Html::tag('span', '0', ['id' => 'count-hold', 'class' => 'badge count-hold']),
                                    'options' => ['id' => 'tab-hold'],
                                    'linkOptions' => ['style' => 'font-size: 14px;'],
                                    'headerOptions' => ['class' => 'tab-hold']
                                ],
                            ],
                            'options' => ['class' => 'nav nav-tabs', 'id' => 'tab-menu-default'],
                            'encodeLabels' => false,
                            'renderTabContent' => false,
                        ]);
                        ?>
                        <div class="tab-content">
                            <div id="tab-waiting" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 text-center text-tablet-mode" style="display: none">
                                            <p><span class="label label-primary" style="font-weight: bold;text-align: center;font-size: 1em;">คิวรอเรียก7</span></p>
                                        </div>
                                    </div>
                                    <?php
                                    echo Table::widget([
                                        'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-waiting'],
                                        //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                        'beforeHeader' => [
                                            [
                                                'columns' => [
                                                    ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'VN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ห้องตรวจ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'Lab', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'X-ray', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'SP', 'options' => ['style' => 'text-align: center;']],
                                                    // ['content' => 'ผล Lab','options' => ['style' => 'text-align: center;']],
                                                    ['pt_visit_type_id' => 'SP', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                                                ],
                                                'options' => ['style' => 'background-color:#32CD32;color:#ffffff;'],
                                            ]
                                        ],
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div id="tab-calling" class="tab-pane">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 text-center text-tablet-mode" style="display: none">
                                            <p><span class="label label-primary" style="font-weight: bold;text-align: center;font-size: 1em;">คิวกำลังเรียก</span></p>
                                        </div>
                                    </div>
                                    <?php
                                    echo Table::widget([
                                        'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-calling'],
                                        //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                        'beforeHeader' => [
                                            [
                                                'columns' => [
                                                    ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'VN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ห้องตรวจ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'Lab', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'X-ray', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'SP', 'options' => ['style' => 'text-align: center;']],
                                                    // ['content' => 'ผล Lab','options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                                                ],
                                                'options' => ['style' => 'background-color:#1E90FF;color:black;'],
                                            ]
                                        ],
                                    ]);
                                    ?>
                                </div><!-- End panel body -->
                            </div>
                            <div id="tab-hold" class="tab-pane">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 text-center text-tablet-mode" style="display: none">
                                            <p><span class="label label-primary" style="font-weight: bold;text-align: center;font-size: 1em;">พักคิว</span></p>
                                        </div>
                                    </div>
                                    <?php
                                    echo Table::widget([
                                        'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-hold'],
                                        //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                        'beforeHeader' => [
                                            [
                                                'columns' => [
                                                    ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'VN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ห้องตรวจ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'Lab', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'X-ray', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'SP', 'options' => ['style' => 'text-align: center;']],
                                                    // ['content' => 'ผล Lab','options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                                                ],
                                                'options' => ['style' => 'background-color:#FF8C00;color:#FFFFFF;'],
                                            ]
                                        ],
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div><!-- End panel body -->
                    </div><!-- End hpanel -->
                </div>

            </div>
        </div>
    </div>
</div>

<!-- jPlayer -->
<div id="jplayer_notify"></div>

<?php
echo $this->render('_datatables', ['modelForm' => $modelForm, 'modelProfile' => $modelProfile, 'action' => Yii::$app->controller->action->id]);
$this->registerJs($this->render('script-examination.js'));
?>
<?php

use frontend\modules\app\models\TbDoctor;
use frontend\modules\app\models\TbDoctorStatus;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
?>
<div class="hpanel panel-form">
    <!-- <div class="panel-heading">
        <div class="panel-tools">
            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
        </div>
        <span class="panel-heading-text" style="font-size: 18px;">&nbsp;</span>
    </div> -->
    <div class="panel-body">
        <div class="form-group" style="margin-bottom: 0px;">
            <div class="col-md-3 service_profile">
                <?= $form
                    ->field($modelForm, 'service_profile')
                    ->widget(Select2::classname(), [
                        'data' => $modelForm->dataProfile,
                        'options' => ['placeholder' => 'เลือกโปรไฟล์...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginEvents' => [
                            'change' => "function() {
                            if($(this).val() != '' && $(this).val() != null){
                                location.replace(baseUrl + \"/app/mobiletest/index?profileid=\" + $(this).val());
                            }else{
                                location.replace(baseUrl + \"/app/mobiletest/index\");
                            }
                        }",
                        ],
                    ]) ?>
            </div>

            <div class="col-md-3 counter_service">
                <?= $form
                    ->field($modelForm, 'counter_service')
                    ->widget(Select2::classname(), [
                        'data' => $modelForm->dataCounter,
                        'options' => ['placeholder' => 'เลือก...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginEvents' => [
                            'change' =>
                                "function() {
                            if($(this).val() != '' && $(this).val() != null){
                                location.replace(baseUrl + \"/app/mobiletest/index?profileid=\" + " .
                                Json::encode($modelForm['service_profile']) .
                                " + \"&counterid=\" + $(this).val());
                            }else{
                                location.replace(baseUrl + \"/app/mobiletest/index?profileid=\" + " .
                                Json::encode($modelForm['service_profile']) .
                                ");
                            }
                        }",
                        ],
                    ]) ?>
            </div>
            <div class="col-md-3">
             
            </div>
            <br>
            <div class="col-md-3">
                <div class="form-group" style="margin-left: 0;">
                    <center>
                        <button href="#" class="btn  btn-primary btn_holdlist" data-toggle="modal" data-target="#holdlist">รายการพักคิว</button>
                        <button href="#" class="btn  btn-primary btn_holdlist" data-toggle="modal" data-target="#endlist">รายการเสร็จสิ้น</button>
                    </center>
                </div>
            </div>
        </div>
    </div><!-- End panel body -->
</div><!-- End hpanel -->
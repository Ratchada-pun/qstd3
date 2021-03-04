<?php
use kartik\widgets\Select2;
use yii\helpers\Json;
use yii\helpers\Html;

?>
<div class="hpanel panel-form">
    <div class="panel-heading">
        <div class="panel-tools">
            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
        </div>
        <div class="checkbox" style="display: inline-block;margin-bottom: 0px;">
            <label>
                <input type="checkbox" value="0" name="tablet-mode" id="tablet-mode">
                <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                <i class="pe-7s-phone"></i> Tablet Mode
            </label>
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
    <div class="panel-body"
         style="border: 1.5px dashed lightgrey;padding-left: 10px;padding-bottom: 0px;padding-top: 0px;">
        <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
            <div class="col-md-3 service_profile">
                <?=
                $form->field($modelForm, 'service_profile')->widget(Select2::classname(), [
                    'data' => $modelForm->dataProfile,
                    'options' => ['placeholder' => 'เลือกโปรไฟล์...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::LARGE,
                    'pluginEvents' => [
                        "change" => "function() {
                            if($(this).val() != '' && $(this).val() != null){
                                location.replace(baseUrl + \"/app/calling/index?profileid=\" + $(this).val());
                            }else{
                                location.replace(baseUrl + \"/app/calling/index\");
                            }
                        }",
                    ]
                ]);
                ?>
            </div>

            <div class="col-md-3 counter_service">
                <?=
                $form->field($modelForm, 'counter_service')->widget(Select2::classname(), [
                    'data' => $modelForm->dataCounter,
                    'options' => ['placeholder' => 'เลือก...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::LARGE,
                    'pluginEvents' => [
                        "change" => "function() {
                            if($(this).val() != '' && $(this).val() != null){
                                location.replace(baseUrl + \"/app/calling/index?profileid=\" + " . Json::encode($modelForm['service_profile']) . " + \"&counterid=\" + $(this).val());
                            }else{
                                location.replace(baseUrl + \"/app/calling/index?profileid=\" + " . Json::encode($modelForm['service_profile']) . ");
                            }
                        }",
                    ]
                ]);
                ?>
            </div>

            <div class="col-md-3 last-queue">
                <ul class="list-group">
                    <li class="list-group-item" style="font-size:18px;">
                        <span class="badge badge-primary" style="font-size:18px;" id="last-queue">-</span>
                        คิวที่เรียกล่าสุด
                    </li>
                </ul>
            </div>
            <div class="col-md-3">
                <?= $form->field($modelForm, 'qnum')->textInput([
                    'class' => 'input-lg',
                    'placeholder' => 'คีย์หมายเลขคิวที่นี่เพื่อเรียก',
                    'style' => 'background-color: #434a54;color: white;',
                    'autofocus' => true
                ])->hint(''); ?>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 5px;">
            <!--            <div class="call-next animated fadeInRight">-->

            <div class="col-md-9" style="padding-bottom: 10px;">
                <?= $modelForm->serviceList; ?>
            </div>
            <div class="col-md-3">
                <p>
                    <?= Html::a('CALL NEXT', false, ['class' => 'btn btn-lg btn-block btn-primary activity-callnext']); ?>
                </p>
            </div>
            <!--            </div>-->
        </div>
    </div><!-- End panel body -->
</div><!-- End hpanel -->
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
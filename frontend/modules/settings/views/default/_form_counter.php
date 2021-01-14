<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use homer\widgets\dynamicform\DynamicFormWidget;
use yii\icons\Icon;
use frontend\modules\kiosk\models\TbCounterserviceType;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use frontend\modules\kiosk\models\TbSection;
use frontend\modules\kiosk\models\TbServiceMdName;

$this->registerCss(<<<CSS
.modal-dialog{
    width: 90%;
}
.modal-header {
    padding: 10px;
}
CSS
);
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-counter', 'type' => ActiveForm::TYPE_HORIZONTAL, 
    'formConfig' => ['showLabels' => false],
]);?>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'limit' => \Yii::$app->keyStorage->get('dynamic-limit', 20), // the maximum times, an element can be cloned (default 999)
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $models[0],
        'formId' => 'form-counter',
        'formFields' => [
            'counterservice_name',
            'counterservice_type',
            'sec_id',
            'sound_stationid',
            'sound_typeid',
            'counterservice_status'
        ],
    ]); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= Icon::show('edit').'จัดการประเภทเคาน์เตอร์'; ?>
            <?= Html::button(Icon::show('plus').'เพิ่มรายการ',['class' => 'pull-right add-item btn btn-success btn-xs']); ?>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body container-items"><!-- widgetContainer -->
            <?php foreach ($models as $index => $model): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <?= Html::tag('span','รายการที่ : '.($index + 1),['class' => 'panel-title']); ?>
                        <div style="float: right;">
                            <?= Html::button(Icon::show('minus'),['class' => 'remove-item btn btn-danger btn-xs']); ?>
                            <?= Html::button(Icon::show('plus'),['class' => 'add-item btn btn-success btn-xs']); ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <?php
                            if (! $model->isNewRecord) {
                                echo Html::activeHiddenInput($model, "[{$index}]counterserviceid");
                            }
                            echo Html::activeHiddenInput($model, "[{$index}]counterservice_callnumber");
                            echo Html::activeHiddenInput($model, "[{$index}]servicegroupid");
                        ?>
                        <div class="form-group">
                            <?= Html::activeLabel($model, "[{$index}]counterservice_name", ['label' => 'ชื่อบริการ','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]counterservice_name",['showLabels'=>false])->textInput([
                                    'placeholder' => 'ชื่อบริการ'
                                ]); ?>
                            </div>

                            <?= Html::activeLabel($model, "[{$index}]counterservice_type", ['class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?=
                                $form->field($model, "[{$index}]counterservice_type")->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(TbCounterserviceType::find()->asArray()->all(),'tb_counterservice_typeid','tb_counterservice_type'),
                                    'options' => ['placeholder' => 'เลือกประเภทเคาน์เตอร์...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= Html::activeLabel($model, "[{$index}]sec_id", ['label' => 'แผนก','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?=
                                $form->field($model, "[{$index}]sec_id")->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(TbSection::find()->asArray()->all(),'sec_id','sec_name'),
                                    'options' => ['placeholder' => 'เลือกแผนก...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]);
                                ?>
                            </div>

                            <?= Html::activeLabel($model, "[{$index}]sound_stationid", ['label' => 'Sound Station','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]sound_stationid",['showLabels'=>false])->textInput([
                                    'placeholder' => 'Sound Station'
                                ]); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= Html::activeLabel($model, "[{$index}]sound_typeid", ['label' => 'เสียงเรียก','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]sound_typeid")->widget(Select2::classname(), [
                                    'data' => [1 => 'เสียงผู้หญิง',2 => 'เสียงผู้ชาย'],
                                    'options' => ['placeholder' => 'เลือกแผนก...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]); ?>
                            </div>

                            <?= Html::activeLabel($model, "[{$index}]counterservice_status", ['label' => 'Status','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]counterservice_status",['showLabels'=>false])->textInput([
                                    'placeholder' => ''
                                ]); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= Html::activeLabel($model, "[{$index}]sound_path", ['label' => 'โฟร์เดอร์ไฟล์เสียง','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]sound_path",['showLabels'=>false])->textInput([
                                    'placeholder' => 'โฟร์เดอร์ไฟล์เสียง'
                                ]); ?>
                            </div>

                            <?= Html::activeLabel($model, "[{$index}]sound_service_number", ['label' => 'ลำดับที่ให้บริการ','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]sound_service_number",['showLabels'=>false])->textInput([
                                    'placeholder' => 'ลำดับที่ให้บริการ'
                                ]); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <?= Html::activeLabel($model, "[{$index}]sound_service_name", ['label' => 'ชื่อไฟล์เสียงบริการ','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]sound_service_name",['showLabels'=>false])->textInput([
                                    'placeholder' => 'ชื่อไฟล์เสียงบริการ'
                                ]); ?>
                            </div>

                            <?= Html::activeLabel($model, "[{$index}]userid", ['label' => 'แพทย์','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-4">
                                <?= $form->field($model, "[{$index}]userid")->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(TbServiceMdName::find()->asArray()->all(),'service_md_name_id','service_md_name'),
                                    'options' => ['placeholder' => 'เลือกแพทย์...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]); ?>
                            </div>
                        </div>

                    </div><!-- End Body Panel /-->
                </div><!-- End Panel /-->
            <?php endforeach; ?>
        </div><!-- End Body Panel /-->
    </div><!-- End Panel /-->
    <?php DynamicFormWidget::end(); ?>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: right;">
            <div class="form-group">
                <div class="col-sm-12">
                    <?= Html::button(Icon::show('close').'CLOSE',['class' => 'btn btn-default','data-dismiss' => 'modal']); ?>
                    <?= Html::submitButton(Icon::show('save').'SAVE',['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(index) {
        jQuery(this).html("รายการที่ : " + (index + 1))
    });
});

//Form Event
var table = $('#tb-counter').DataTable();
var \$form = $('#form-counter');
\$form.on('beforeSubmit', function() {
    var data = new FormData($(\$form)[0]);//\$form.serialize();
    var \$btn = $('button[type="submit"]').button('loading');//loading btn
    \$.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        async: false,
        processData: false,
        contentType: false,
        success: function (data) {
            if(data.status == '200'){
                $('#ajaxCrudModal').modal('hide');//hide modal
                table.ajax.reload();//reload table
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function(){ \$btn.button('reset'); }, 1500);//clear button loading
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
            }
            \$btn.button('reset');
        },
        error: function(jqXHR, errMsg) {
            swal('Oops...',errMsg,'error');
            \$btn.button('reset');
        }
    });
    return false; // prevent default submit
});
JS
);
?>
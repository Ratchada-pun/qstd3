<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use homer\widgets\dynamicform\DynamicFormWidget;
use yii\icons\Icon;
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-doctor', 'type' => ActiveForm::TYPE_HORIZONTAL, 
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
        'formId' => 'form-doctor',
        'formFields' => [
            'service_md_name_id',
            'service_md_name',
        ],
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?= Icon::show('edit').'จัดการประเภท'; ?>
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
                                echo Html::activeHiddenInput($model, "[{$index}]service_md_name_id");
                            }
                        ?>
                        <div class="form-group">
                            <?= Html::activeLabel($model, "[{$index}]service_md_name", ['label' => 'ชื่อแพทย์','class'=>'col-sm-2 control-label']) ?>
                            <div class="col-sm-8">
                                <?= $form->field($model, "[{$index}]service_md_name",['showLabels'=>false])->textInput([
                                    'placeholder' => 'ชื่อแพทย์'
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
var table = $('#tb-doctor').DataTable();
var \$form = $('#form-doctor');
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
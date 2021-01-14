<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use frontend\modules\kiosk\models\TbPtVisitType;
use frontend\modules\kiosk\models\TbSection;
use yii\helpers\ArrayHelper;
use yii\icons\Icon;

$visit = ArrayHelper::map(TbPtVisitType::find()->asArray()->all(),'pt_visit_type_id','pt_visit_type');
$section = ArrayHelper::map(TbSection::find()->asArray()->all(),'sec_id','sec_name');
?>
<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'id' => 'form-'.$model->formName(),
    'formConfig' => ['showLabels' => false],
]); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group">
            <?= Html::activeLabel($model, 'q_hn', ['label' => 'HN '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <?= $form->field($model, 'q_hn',['staticValue' => \kartik\helpers\Html::badge($rows['q_hn'],['class' => 'badge'])])->staticInput([]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($model, 'pt_name', ['label' => 'ชื่อ-นามสกุล '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <?= $form->field($model, 'pt_name',['staticValue' => $rows['pt_name']])->staticInput([]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'pt_visit_type_id', ['label' => 'ประเภท '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <?= $form->field($model, 'pt_visit_type_id',['staticValue' => ArrayHelper::getValue($visit,$rows['pt_visit_type_id'],'-')])->staticInput([]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($model, 'pt_appoint_sec_id', ['label' => 'แผนก/คลีนิค '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <?= $form->field($model, 'pt_appoint_sec_id',['staticValue' => ArrayHelper::getValue($section, $rows['pt_appoint_secid'],'-')])->staticInput([]); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?= Html::activeHiddenInput($model,'q_vn',['value' => $rows['q_vn']]); ?>
                <?= Html::activeHiddenInput($model,'q_hn',['value' => $rows['q_hn']]); ?>
                <?= Html::activeHiddenInput($model,'pt_id',['value' => $rows['pt_id']]); ?>
                <?= Html::activeHiddenInput($model,'pt_name',['value' => $rows['pt_name']]); ?>
                <?= Html::activeHiddenInput($model,'pt_visit_type_id',['value' => $rows['pt_visit_type_id']]); ?>
                <?= Html::activeHiddenInput($model,'pt_appoint_sec_id',['value' => $rows['pt_appoint_secid']]); ?>
                <?= Html::activeHiddenInput($model,'doctor_id',['value' => $rows['doctor_id']]); ?>
            </div>
        </div>

    </div>
</div>
<div class="form-group">
    <div class="col-lg-12" style="text-align: right;">
        <?= Html::button(Icon::show('close').'Close',['class' => 'btn btn-default btn-lg','data-dismiss' => 'modal']); ?>
        <?= Html::submitButton(Icon::show('check').'ยืนยัน', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
var table = $('#tb-qdata').DataTable();
var \$form = $('#form-TbQuequ');
\$form.on('beforeSubmit', function() {
    var \$btn = $('form#form-TbQuequ button[type="submit"]').button('loading');
    var data = new FormData($(\$form)[0]);//\$form.serialize();
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        async: false,
        processData: false,
        contentType: false,
        success: function (data) {
            if(data.status === 200){
                \$btn.button('reset');
                $(\$form).trigger("reset");
                $("#search-form").trigger("reset");
                $("#modal-his").modal('hide');
                $("#searchform-hn").focus();
                table.ajax.reload();
                window.open(data.url,'_blank');
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
                \$btn.button('reset');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal({
                type: 'error',
                title: errorThrown,
                showConfirmButton: false,
                timer: 1500
            });
            \$btn.button('reset');
        }
    });
    return false; // prevent default submit
});
JS
);
?>
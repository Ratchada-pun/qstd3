<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use frontend\modules\kiosk\models\TbPtVisitType;
use frontend\modules\kiosk\models\TbSection;
use yii\helpers\ArrayHelper;
use yii\icons\Icon;
use frontend\modules\kiosk\models\TbCounterservice;
use homer\assets\ICheckAsset;

ICheckAsset::register($this);

$this->registerCss(<<<CSS
.modal-header {
    padding: 10px;
}
.form-control-static {
    font-size: 15px;
}
.radio label, .checkbox label {
    padding-left: 0px;
}
.checkbox label:after, 
.radio label:after {
    content: '';
    display: table;
    clear: both;
}

.checkbox .cr,
.radio .cr {
    position: relative;
    display: inline-block;
    border: 1px solid #a9a9a9;
    border-radius: .25em;
    width: 1.3em;
    height: 1.3em;
    float: left;
    margin-right: .5em;
}

.radio .cr {
    border-radius: 50%;
}

.checkbox .cr .cr-icon,
.radio .cr .cr-icon {
    position: absolute;
    font-size: .8em;
    line-height: 0;
    top: 50%;
    left: 20%;
}

.radio .cr .cr-icon {
    margin-left: 0.04em;
}

.checkbox label input[type="checkbox"],
.radio label input[type="radio"] {
    display: none;
}

.checkbox label input[type="checkbox"] + .cr > .cr-icon,
.radio label input[type="radio"] + .cr > .cr-icon {
    transform: scale(3) rotateZ(-20deg);
    opacity: 0;
    transition: all .3s ease-in;
}

.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
.radio label input[type="radio"]:checked + .cr > .cr-icon {
    transform: scale(1) rotateZ(0deg);
    opacity: 1;
}

.checkbox label input[type="checkbox"]:disabled + .cr,
.radio label input[type="radio"]:disabled + .cr {
    opacity: .5;
}
CSS
);
$visit = ArrayHelper::map(TbPtVisitType::find()->asArray()->all(),'pt_visit_type_id','pt_visit_type');
?>
<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'id' => 'form-'.$model->formName(),
    'formConfig' => ['showLabels' => false],
]); ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group">
            <?= Html::activeLabel($modelQ, 'q_hn', ['label' => 'HN '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-6 col-md-4">
                <?= $form->field($modelQ, 'q_hn')->staticInput([]); ?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::activeLabel($modelQ, 'pt_name', ['label' => 'ชื่อ-นามสกุล '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <?= $form->field($modelQ, 'pt_name')->staticInput([]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($modelQ, 'pt_visit_type_id', ['label' => 'ประเภท '.'<i class="fa fa-angle-double-right"></i>','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <?= $form->field($modelQ, 'pt_visit_type_id',['staticValue' => ArrayHelper::getValue($visit,$modelQ['pt_visit_type_id'],'-')])->staticInput([]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::activeLabel($modelQtran, 'counter_service_id', ['label' => 'ห้องตรวจ','class'=>'col-xs-12 col-sm-3 col-md-3 control-label']) ?>
            <div class="col-xs-12 col-sm-8 col-md-8">
                <?= $form->field($modelQtran, 'counter_service_id')->radioList(ArrayHelper::map((new \yii\db\Query())
                    ->select([
                        'CONCAT(tb_counterservice.counterservice_name, \' \', tb_service_md_name.service_md_name) AS counterservice_name', 
                        'tb_counterservice.counterserviceid'
                    ])
                    ->from('tb_counterservice')
                    ->innerJoin('tb_service_md_name','tb_service_md_name.service_md_name_id = tb_counterservice.userid')
                    ->where(['counterservice_type' => 2])
                    ->all(),'counterserviceid','counterservice_name'),
                    [
                        'class' => 'radio',
                        'item' => function($index, $label, $name, $checked, $value) {

                            $return = '<label class="modal-radio" style="font-size: 2em">';
                            $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                            $return .= '<span class="cr"><i class="cr-icon fa fa-circle"></i></span>' . ucwords($label);
                            $return .= '</label>';

                            return $return;
                        }
                    ]
            ); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <?= Html::activeHiddenInput($modelQ,'q_vn'); ?>
                <?= Html::activeHiddenInput($modelQ,'q_hn'); ?>
                <?= Html::activeHiddenInput($modelQ,'pt_id'); ?>
                <?= Html::activeHiddenInput($modelQ,'pt_name'); ?>
                <?= Html::activeHiddenInput($modelQ,'pt_visit_type_id'); ?>
                <?= Html::activeHiddenInput($modelQ,'pt_appoint_sec_id'); ?>
                <?= Html::activeHiddenInput($modelQ,'doctor_id'); ?>
                <?= Html::activeHiddenInput($model,'caller_ids'); ?>
            </div>
        </div>

    </div>
</div>
<div class="form-group">
    <div class="col-lg-12" style="text-align: right;">
        <?= Html::button(Icon::show('close').'Close',['class' => 'btn btn-default btn-lg','data-dismiss' => 'modal']); ?>
        <?= Html::submitButton(Icon::show('check').'Confirm', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
// Initialize iCheck plugin
$('.i-checks').iCheck({
    checkboxClass: 'icheckbox_square-green',
    radioClass: 'iradio_square-green'
});
var table = $('#tb-calling').DataTable();
var \$form = $('#form-TbCaller');
\$form.on('beforeSubmit', function() {
    var \$btn = $('form#form-TbCaller button[type="submit"]').button('loading');
    var data = new FormData($(\$form)[0]);//\$form.serialize();
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        async: false,
        processData: false,
        contentType: false,
        success: function (res) {
            if(res.status === 200){
                swal({
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
                table.ajax.reload();
                $("#ajaxCrudModal").modal('hide');
                socket.emit('endq-screening-room', res);//sending data
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
            }
            \$btn.button('reset');
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
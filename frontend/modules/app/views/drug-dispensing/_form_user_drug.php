<?php

use kartik\form\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\icons\Icon;


$this->registerCss('
.modal-header {
    padding: 15px 30px;
    background: #f7f9fa;
}
.modal-title {
    font-size: 20px;
    font-weight: 300;
}
.modal-title {
    font-size: 22px;
    font-weight: 300;
}
.modal-title {
    font-size: 20px;
    font-weight: 300;
}
');
?>

<?php $form = ActiveForm::begin(['id' => 'form-user_drug', 'type' => ActiveForm::TYPE_HORIZONTAL,]); ?>
<div class="form-group row">
    <?= Html::activeLabel($model, 'hn', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-md-4">
        <?php echo  $form->field($model, 'hn')->textInput([
            'autocomplete' => 'off'
        ])->label(false) ?>
    </div>
    <div class="col-md-2">
        <p>
            <?= Html::button('ค้นหา', ['class' => 'btn btn-md btn-block btn-success', 'type' => 'button', 'onclick' => 'searchPatient();']); ?>
        </p>
    </div>
</div>
<div class="form-group row">
    <?= Html::activeLabel($model, 'pt_name', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-md-8">
        <?php echo  $form->field($model, 'pt_name')->textInput([
            'readonly' => true
        ])->label(false) ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12 text-right">
        <?= Html::submitButton('บันทึก',['class' => 'btn btn-success']) ?>
        <?= Html::button('ปิด', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>



<?php
$this->registerJs(<<<JS
function searchPatient() {
    $.ajax({
        url: '/app/drug-dispensing/get-patientinfo',
        type: 'GET',
        data: {hn: $('#personal-hn').val()},
        success: function (data) {
            if(data != null) {
                data = JSON.parse(data)
                $('#personal-pt_name').val(data.pt_name)
            } else {
                // alert
            }
        },
        error: function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
}

var \$form = $('#form-user_drug');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    if(!$('#personal-pt_name').val()){
        searchPatient();
        return false
    }
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            var table = $('#get_personal_drug').DataTable();
            table.ajax.reload();
            $('#ajaxCrudModal').modal('hide');
        },
        error: function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
     return false; // prevent default submit
});
JS
);
?>


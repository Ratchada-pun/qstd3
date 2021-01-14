<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\helpers\Html;
use yii\helpers\Url;
use yii\icons\Icon;
use frontend\modules\app\models\TbServicegroup;
use frontend\modules\app\models\TbService;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
?>
<?php
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'id' => 'form-'.$model->formName()]);
?>
<div class="form-group">
    <?= Html::activeLabel($model, 'servicegroupid', ['label' => 'ชื่อกลุ่มบริการ','class'=>'col-sm-2 control-label']) ?>
    <div class="col-sm-6">
        <?= $form->field($model, 'servicegroupid', ['showLabels'=>false])->widget(Select2::classname(), [
            'data'=>ArrayHelper::map(TbServicegroup::find()->asArray()->all(),'servicegroupid','servicegroup_name'),
            'pluginOptions'=>['allowClear'=>true],
            'options' => ['placeholder'=>'Select state...'],
            'theme' => Select2::THEME_BOOTSTRAP,
        ]); ?>
    </div>
</div>
<div class="form-group">
    <?= Html::activeLabel($model, 'serviceid', ['label' => 'ชื่อบริการ','class'=>'col-sm-2 control-label']) ?>
    <div class="col-sm-6">
        <?= $form->field($model, 'serviceid', ['showLabels'=>false])->widget(DepDrop::classname(), [
            'data'=>ArrayHelper::map(TbService::find()->where(['service_groupid' => $model['servicegroupid']])->asArray()->all(),'serviceid','service_name'),
            'pluginOptions'=>[
                'depends'=>['tbquequ-servicegroupid'],
                'placeholder'=>'Select...',
                'url'=>Url::to(['/app/kiosk/child-servicegroup'])
            ],
            'type'=>DepDrop::TYPE_SELECT2,
            'select2Options'=>['pluginOptions'=>['allowClear'=>true],'theme' => Select2::THEME_BOOTSTRAP],
        ]); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-8" style="text-align: right;">
        <?= Html::button(Icon::show('close').'CLOSE',['class' => 'btn btn-default','data-dismiss' => 'modal']); ?>
        <?= Html::submitButton(Icon::show('save').'SAVE',['class' => 'btn btn-primary']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
//Form Event
var table = $('#tb-qdata').DataTable();
var tablepatients = $('#tb-patients').DataTable();
var \$form = $('#form-TbQuequ');
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
                tablepatients.ajax.reload();
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    text: data.model.q_num,
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
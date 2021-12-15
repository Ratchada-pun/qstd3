<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\checkbox\CheckboxX;
use yii\icons\Icon;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TbNewsTicker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-news-ticker-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-news-ticker', 'type' => ActiveForm::TYPE_HORIZONTAL, 'formConfig' => ['showLabels' => false],
    ]); ?>



    <div class="form-group">
        <?= Html::activeLabel($model, 'news_ticker_detail', ['label' => 'ข้อความ', 'class' => 'col-sm-2 control-label']) ?>
        <div class="col-sm-10">
            <?= $form->field($model, 'news_ticker_detail', ['showLabels' => false])->textarea([
                'placeholder' => 'ข้อความประชาสัมพันธ์'
            ]); ?>
        </div>

    </div>

    <div class="form-group">
        <?= Html::activeLabel($model, 'news_ticker_status', ['label' => 'สถานะการใช้งาน', 'class' => 'col-sm-2 control-label']) ?>
        <div class="col-sm-4">
            <?= $form->field($model, 'news_ticker_status', ['showLabels' => false])->RadioList(
                [
                    1 => 'เปิด',
                    0 => 'ปิด'
                ],
                [
                    'inline' => true,
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $return = '<div class="radio"><label style="font-size: 1em">';
                        $return .= Html::radio($name, $checked, ['value' => $value]);
                        $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                        $return .= '</label></div>';
                        return $return;
                    }
                ]
            );
            ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12" style="text-align: right;">
            <?= Html::button(Icon::show('close') . 'CLOSE', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
            <?= Html::submitButton(Icon::show('save') . 'SAVE', ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(<<<JS
var table = $('#tb-news-ticker').DataTable();
var \$form = $('#form-news-ticker');
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
                setTimeout(function(){ 
                    \$btn.button('reset');
                }, 1000);//clear button loading
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
                \$btn.button('reset');
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
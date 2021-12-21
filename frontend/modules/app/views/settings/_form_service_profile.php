<?php

use frontend\modules\app\models\TbCounterservice;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\icons\Icon;
use kartik\widgets\Select2;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbCounterserviceType;
use yii\helpers\ArrayHelper;
use kartik\widgets\ColorInput;
use kartik\depdrop\DepDrop;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Url;

$this->registerCss('
.modal-dialog{
	width: 90%;
}
.modal-header{
	padding: 10px;
}
');

if ($model->isNewRecord) {
    $model->service_profile_status = 1;
}
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-service-profile', 'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['showLabels' => false],
]); ?>
<div class="form-group">
    <?= Html::activeLabel($model, 'service_name', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'service_name', ['showLabels' => false])->textInput([]); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'counterservice_typeid', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'counterservice_typeid', ['showLabels' => false])->widget(Select2::classname(), [
            'data' => ArrayHelper::map(TbCounterserviceType::find()->asArray()->all(), 'counterservice_typeid', 'counterservice_type'),
            'options' => ['placeholder' => 'Select a state ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'theme' => Select2::THEME_BOOTSTRAP,
        ]); ?>
    </div>
    <?= Html::activeLabel($model, 'counterserviceid', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'counterserviceid', ['showLabels' => false])->widget(DepDrop::classname(), [
            'data' => ArrayHelper::map(TbCounterservice::find()->where(['counterservice_type' => $model['counterservice_typeid']])->asArray()->all(), 'counterserviceid', 'counterservice_name'),
            'options' => ['placeholder' => 'Select ...'],
            'pluginOptions' => [
                'depends' => ['tbserviceprofile-counterservice_typeid'],
                'placeholder' => 'Select...',
                'url' => Url::to(['/app/settings/sub-counter-type'])
            ],
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true],
                'theme' => Select2::THEME_BOOTSTRAP,
            ],
            'type' => DepDrop::TYPE_SELECT2,
        ]); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'items', ['label' => 'Priority', 'class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'items')->widget(MultipleInput::className(), [
            'id' => 'multiple-input',
            'min' => 0,
            'max' => 20,
            'addButtonOptions' => [
                'class' => 'btn btn-primary',
                'label' => 'เพิ่ม'
            ],
            'iconMap' => [
                'glyphicons' => [
                    'drag-handle'   => 'glyphicon glyphicon-menu-hamburger',
                    'remove'        => 'fa fa-minus',
                    'add'           => 'fa fa-plus',
                    'clone'         => 'glyphicon glyphicon-duplicate',
                ],
            ],
            'columns' => [
                [
                    'name'  => 'profile_priority_id',
                    'type'  => 'hiddenInput',
                    'title' => 'id',
                ],
                [
                    'name'  => 'profile_priority_seq',
                    'title' => 'ลำดับ',
                    'type' => 'textInput',
                    'options' => [
                        'type' => 'number',
                        'min' => '1',
                        'class' => 'form-control input-order'
                    ]
                ],
                [
                    'headerOptions' => [
                        'style' => 'width:80%'
                    ],
                    'name'  => 'service_id',
                    'title' => 'ชื่อบริการ',
                    'type'  => Select2::className(),
                    'value' => function ($data) {
                        return $data['service_id'];
                    },
                    'options' => [
                        'data' => ArrayHelper::map(TbService::find()->asArray()->all(), 'serviceid', 'service_name'),
                        'options' => ['placeholder' => '--- เลือก ---'],
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'bsVersion' => '4.x'
                    ]
                ],
            ]
        ])->label(false); ?>
    </div>
</div>

<?php /*

<div class="form-group">
    <?= Html::activeLabel($model, 'service_id', ['label' => 'กลุ่มบริการย่อย', 'class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'service_id', ['showLabels' => false])->checkBoxList(ArrayHelper::map(TbService::find()->where(['service_status' => 1])->asArray()->all(), 'serviceid', 'service_name'), [
            'inline' => false,
            'item' => function ($index, $label, $name, $checked, $value) {

                $return = '<div class="checkbox"><label style="font-size: 1em">';
                $return .= Html::checkbox($name, $checked, ['value' => $value]);
                $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                $return .= '</label></div>';

                return $return;
            }
        ]); ?>
    </div>
</div>
*/ ?>
<?php /*
    <div class="form-group">
        <?= Html::activeLabel($model, 'counter_service_ids', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-sm-10">
            <?= $form->field($model, 'counter_service_ids', ['showLabels' => false])->checkBoxList(ArrayHelper::map((new \yii\db\Query())
                ->select(['CONCAT(tb_counterservice_type.counterservice_type, \' \', tb_counterservice.counterservice_name) as counterservice_name', 'tb_counterservice.counterserviceid'])
                ->from('tb_counterservice')
                ->innerJoin('tb_counterservice_type', 'tb_counterservice.counterservice_type = tb_counterservice_type.counterservice_typeid')
                ->where(['tb_counterservice.counterservice_status' => 1])
                ->all(), 'counterserviceid', 'counterservice_name'), [
                'inline' => false,
                'item' => function ($index, $label, $name, $checked, $value) {

                    $return = '<div class="checkbox"><label style="font-size: 1em">';
                    $return .= Html::checkbox($name, $checked, ['value' => $value]);
                    $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                    $return .= '</label></div>';

                    return $return;
                }
            ]); ?>
        </div>
    </div>

  
    <div class="form-group">
        <?= Html::activeLabel($model, 'service_status_id', ['label' => 'สถานะคิว', 'class' => 'col-sm-2 control-label']) ?>
        <div class="col-sm-4">
            <?= $form->field($model, 'service_status_id', ['showLabels' => false])->widget(Select2::classname(), [
                'data' => ArrayHelper::map((new \yii\db\Query())
                    ->select([
                            'tb_service_status.service_status_id',
                            'tb_service_status.service_status_name'
                        ])
                    ->from('tb_service_status')
                    ->all(),'service_status_id','service_status_name'), 
                'options' => ['placeholder' => 'เลือก สถานะคิว...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]) ?>
        </div>
    </div>
    */ ?>

<div class="form-group">
    <?= Html::activeLabel($model, 'service_profile_status', ['label' => 'สถานะ', 'class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'service_profile_status', ['showLabels' => false])->widget(Select2::classname(), [
            'data' => [0 => 'Disabled', 1 => 'Enabled'],
            'options' => ['placeholder' => 'สถานะ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'theme' => Select2::THEME_BOOTSTRAP,
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12" style="text-align: right;">
        <?= Html::button(Icon::show('close') . 'CLOSE', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
        <?= Html::submitButton(Icon::show('save') . 'SAVE', ['class' => 'btn btn-primary']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(
    <<<JS
var table = $('#tb-service-profile').DataTable();
var \$form = $('#form-service-profile');
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
                socket.emit('setting',{model:'service_profile'})
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
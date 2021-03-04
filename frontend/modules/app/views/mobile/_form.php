<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\mobile\TbQuequ */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-quequ-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'q_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'q_timestp')->textInput() ?>

    <?= $form->field($model, 'q_arrive_time')->textInput() ?>

    <?= $form->field($model, 'q_appoint_time')->textInput() ?>

    <?= $form->field($model, 'pt_id')->textInput() ?>

    <?= $form->field($model, 'q_vn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'q_hn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pt_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pt_visit_type_id')->textInput() ?>

    <?= $form->field($model, 'pt_appoint_sec_id')->textInput() ?>

    <?= $form->field($model, 'serviceid')->textInput() ?>

    <?= $form->field($model, 'servicegroupid')->textInput() ?>

    <?= $form->field($model, 'quickly')->textInput() ?>

    <?= $form->field($model, 'q_status_id')->textInput() ?>

    <?= $form->field($model, 'doctor_id')->textInput() ?>

    <?= $form->field($model, 'counterserviceid')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

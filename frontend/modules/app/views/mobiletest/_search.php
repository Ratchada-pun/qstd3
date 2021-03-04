<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\mobile\TbQuequSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-quequ-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'q_ids') ?>

    <?= $form->field($model, 'q_num') ?>

    <?= $form->field($model, 'q_timestp') ?>

    <?= $form->field($model, 'q_arrive_time') ?>

    <?= $form->field($model, 'q_appoint_time') ?>

    <?php // echo $form->field($model, 'pt_id') ?>

    <?php // echo $form->field($model, 'q_vn') ?>

    <?php // echo $form->field($model, 'q_hn') ?>

    <?php // echo $form->field($model, 'pt_name') ?>

    <?php // echo $form->field($model, 'pt_visit_type_id') ?>

    <?php // echo $form->field($model, 'pt_appoint_sec_id') ?>

    <?php // echo $form->field($model, 'serviceid') ?>

    <?php // echo $form->field($model, 'servicegroupid') ?>

    <?php // echo $form->field($model, 'quickly') ?>

    <?php // echo $form->field($model, 'q_status_id') ?>

    <?php // echo $form->field($model, 'doctor_id') ?>

    <?php // echo $form->field($model, 'counterserviceid') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

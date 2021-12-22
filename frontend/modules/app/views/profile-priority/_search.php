<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TbProfilePrioritySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-profile-priority-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'profile_priority_id') ?>

    <?= $form->field($model, 'profile_priority_seq') ?>

    <?= $form->field($model, 'service_profile_id') ?>

    <?= $form->field($model, 'service_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

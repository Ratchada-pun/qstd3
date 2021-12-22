<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TbProfilePriority */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-profile-priority-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'profile_priority_seq')->textInput() ?>

    <?= $form->field($model, 'service_profile_id')->textInput() ?>

    <?= $form->field($model, 'service_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

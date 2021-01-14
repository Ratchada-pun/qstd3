<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\kiosk\models\TbPtVisitType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tb-pt-visit-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pt_visit_type')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

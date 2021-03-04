<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TableResourceSchedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="table-resource-schedule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ID')->textInput() ?>

    <?= $form->field($model, 'Date')->textInput() ?>

    <?= $form->field($model, 'STime')->textInput() ?>

    <?= $form->field($model, 'ETime')->textInput() ?>

    <?= $form->field($model, 'DRCode')->textInput() ?>

    <?= $form->field($model, 'DRName')->textInput() ?>

    <?= $form->field($model, 'Dayyy')->textInput() ?>

    <?= $form->field($model, 'Loccode')->textInput() ?>

    <?= $form->field($model, 'UpdateDate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateTime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ResourceText')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\Register */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="register-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'VN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HN')->textInput() ?>

    <?= $form->field($model, 'FullName')->textInput() ?>

    <?= $form->field($model, 'TEL')->textInput() ?>

    <?= $form->field($model, 'CareProvNo')->textInput() ?>

    <?= $form->field($model, 'CareProv')->textInput() ?>

    <?= $form->field($model, 'ServiceID')->textInput() ?>

    <?= $form->field($model, 'Time')->textInput() ?>

    <?= $form->field($model, 'AppTime')->textInput() ?>

    <?= $form->field($model, 'loccode')->textInput() ?>

    <?= $form->field($model, 'locdesc')->textInput() ?>

    <?= $form->field($model, 'UpdateDate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateTime')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

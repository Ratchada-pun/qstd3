<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\QueuesInterface */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="queues-interface-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'HN')->textInput() ?>

    <?= $form->field($model, 'VN')->textInput() ?>

    <?= $form->field($model, 'Fullname')->textInput() ?>

    <?= $form->field($model, 'doctor')->textInput() ?>

    <?= $form->field($model, 'lab')->textInput() ?>

    <?= $form->field($model, 'SP')->textInput() ?>

    <?= $form->field($model, 'PrintTime')->textInput() ?>

    <?= $form->field($model, 'ArrivedTime')->textInput() ?>

    <?= $form->field($model, 'PrintBillTime')->textInput() ?>

    <?= $form->field($model, 'Time1')->textInput() ?>

    <?= $form->field($model, 'Time2')->textInput() ?>

    <?= $form->field($model, 'UpdateDate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'UpdateTime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ArrivedTimeC')->textInput() ?>

    <?= $form->field($model, 'WTime')->textInput() ?>

    <?= $form->field($model, 'AppTime')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

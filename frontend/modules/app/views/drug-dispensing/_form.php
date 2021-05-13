<?php

use frontend\modules\app\models\TbDispensingStatus;
use homer\widgets\Table;
use homer\widgets\Datatables;
use kartik\form\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>

<?php $form = ActiveForm::begin([ 'id' => 'form', 'type' => ActiveForm::TYPE_HORIZONTAL, 'formConfig' => ['showLabels' => false],]); ?>

<div class="form-group">
        <?= Html::activeLabel($searchModel, 'pharmacy_drug_name', ['label' => 'ชื่อร้านขายยา', 'class' => 'col-sm-2 control-label']) ?>
        <div class="col-sm-4">
            <?= $form->field($searchModel, 'pharmacy_drug_name', ['showLabels' => false])->widget(Select2::classname(), [
                'data' => ArrayHelper::map((new \yii\db\Query())
                    ->select([
                            'tb_drug_dispensing.dispensing_id',
                            'tb_drug_dispensing.pharmacy_drug_id',
                            'tb_drug_dispensing.pharmacy_drug_name'
                        ])
                    ->from('tb_drug_dispensing')
                    ->all(),'pharmacy_drug_id','pharmacy_drug_name'), 
                'options' => ['placeholder' => 'เลือกร้านขายยา...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'theme' => Select2::THEME_BOOTSTRAP,
            ]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php /*
<div class="tb-drug-dispensing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pharmacy_drug_id')->textInput() ?>

    <?= $form->field($model, 'pharmacy_drug_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deptname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rx_operator_id')->textInput() ?>

    <?= $form->field($model, 'HN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pt_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doctor_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dispensing_date')->textInput() ?>

    <?= $form->field($model, 'dispensing_status_id')->textInput() ?>

    <?= $form->field($model, 'dispensing_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
*/?>
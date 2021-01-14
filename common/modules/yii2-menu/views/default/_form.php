<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use homer\menu\models\Menu;
use homer\menu\models\MenuCategory;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\web\View;
use homer\menu\assets\AppAsset;
use yii\helpers\ArrayHelper;

$asset = AppAsset::register($this);
$format = <<< SCRIPT
function format(state) {
    if (!state.id) return state.text; // optgroup
    return '<i class="fa fa-'+state.text+'"></i> ' + state.text;
}
SCRIPT;
$escape = new JsExpression("function(m) { return m; }");
$this->registerJs($format, View::POS_HEAD);

$icons = Yii::$app->db->createCommand('SELECT * FROM icons')->queryAll();
?>



<?php $form = ActiveForm::begin(['id' => $model->formName()]); ?>

<div class="row">   
    <div class="col-sm-3">
        <?= $form->field($model, 'icon')->widget(Select2::classname(),[
            'data' => ArrayHelper::map($icons, 'classname','classname'),
            'options' => ['placeholder' => 'Select icon...'],
            'pluginOptions' => [
                'templateResult' => new JsExpression('format'),
                'templateSelection' => new JsExpression('format'),
                'escapeMarkup' => $escape,
                'allowClear' => true
            ],
        ]) ?>
    </div>   
    <div class="col-sm-9">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>
</div>

<div class="row">   
    <div class="col-sm-2">  
        <?= $form->field($model, 'target')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-6">  
        <?= $form->field($model, 'router')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-4">
        <?= $form->field($model, 'parameter')->textInput(['maxlength' => true]) ?>
    </div> 
</div>

<div class="row"> 
    <div class="col-sm-12">  
    <?= $form->field($model, 'route')->widget(Select2::classname(),[
            'data' => ArrayHelper::merge($model->getAppRoutes(), $model->getRoute()),
            'options' => [
                'placeholder' => 'Select...',
                'multiple' => true,
                'value' => $model->getRoute()
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'tags' => true,
            ],
        ]) ?>
    </div>   
</div>   

<div class="row">   
    <div class="col-sm-6">
        <?= $form->field($model, 'menu_category_id')->widget(Select2::ClassName(), [
            'data' => MenuCategory::getList(),
            'options' => [
                'placeholder' => 'Select Category...',
                'value' => $model->isNewRecord ? 1 : $model['menu_category_id'],
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>   
    <div class="col-sm-6">
        <?= $form->field($model, 'parent_id')->widget(Select2::ClassName(), [
            'data' => Menu::getList(),
            'options' => [
                'placeholder' => 'Select Parent...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div> 
</div> 


<div class="row">   
    <div class="col-sm-3">
        <?= $form->field($model, 'status')->widget(Select2::ClassName(), [
            'data' => Menu::getItemStatus(),
            'options' => [
                'placeholder' => 'Select status...',
                'value' => $model->isNewRecord ? 1 : $model['status'],
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>   

    <div class="col-sm-3">  
        <?=
        $form->field($model, 'items')->widget(Select2::ClassName(), [
            'data' => Menu::getAuth(),
            'options' => [
                'placeholder' => 'Select ...',
                'multiple' => true
            ],
            'pluginOptions' => [
                //'allowClear' => true,
                'tags' => true,
                //'tokenSeparators' => [',', ' '],
                'maximumInputLength' => 10
            ],
        ])
        ?>
    </div>   
    <div class="col-sm-3">  
        <?= $form->field($model, 'protocol')->textInput(['maxlength' => true]) ?>
    </div>   
    <div class="col-sm-3">  
        <?= $form->field($model, 'home')->widget(Select2::ClassName(), [
            'data' => [0 => '0', 1 => '1'],
            'options' => [
                'placeholder' => 'Select home...',
                'value' => $model->isNewRecord ? 1 : $model['home'],
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
    </div>   
</div>   



<div class="row">   
    <div class="col-sm-3">

        <?php /* = $form->field($model, 'sort')->dropDownList(Menu::getSortBy($model->menu_category_id, $model->parent_id), ['prompt' => Yii::t('app', 'เลือก')]) */ ?>
        <?= $form->field($model, 'sort')->textInput() ?>
    </div>   
    <div class="col-sm-3">  

        <?php /* = $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

          <?= $form->field($model, 'assoc')->textInput(['maxlength' => true]) */ ?>

        <?= $form->field($model, 'params')->textInput() ?> 

    </div>   
</div>  
<?php if(!Yii::$app->request->isAjax) {?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('menu', 'Create') : Yii::t('menu', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>




<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = 'รายงานระยะเวลารอคอย';
?>
<?php
echo $this->render('_tabs');
?>
<div class="tab-content">
    <div id="tab-4" class="tab-pane active">
        <div class="panel-body" style="background: #fff;">
            <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); ?>
            <div class="form-group">
                <?= Html::label('เลือกวันที่', '', ['class' => 'col-sm-2 control-label']) ?>
                <div class="col-sm-3">
                    <?= DatePicker::widget([
                        'name' => 'begin_date',
                        'value' => isset($_POST['begin_date']) ? $_POST['begin_date'] : date('Y-m-d'),
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-4">
                    <?= Html::a('Reset', ['/app/report/timewaiting'], ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

            <br>
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'caption' => 'ตารางแสดงระยะเวลารอคอยเฉลี่ยแยกตามประเภทบริการ ' . (isset($_POST['begin_date']) ? Yii::$app->formatter->asDate($_POST['begin_date'], 'php:d/m/Y') : ''),
                'toolbar' => [
                    '{export}',
                ],
                'panel' => [
                    'heading' => false,
                    'type' => 'success',
                    'before' => '',
                    'after' => '',
                    'footer' => false
                ],
                'columns' => ArrayHelper::merge([
                    [
                        'class' => '\kartik\grid\SerialColumn'
                    ],
                    [
                        'attribute' => 'service_name',
                        'header' => 'ชื่อบริการ',
                    ],
                ], $columns),
                'responsive' => true,
                'hover' => true
            ]);
            ?>
        </div>
    </div>
</div>
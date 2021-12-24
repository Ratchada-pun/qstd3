<?php

use frontend\modules\app\models\TbService;
use kartik\widgets\DatePicker;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use homer\widgets\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\select2\Select2;

$this->title = 'รายงาน';
?>
<?php
echo $this->render('_tabs');
?>

<div class="tab-content">
    <div id="tab-5" class="tab-pane active">
        <div class="panel-body" style="background: #fff;">
            <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]); ?>
            <div class="form-group">
                <?= Html::label('เลือกวันที่', '', ['class' => 'col-sm-2 control-label']) ?>
                <div class="col-sm-6">
                    <?php
                    $value = isset($_POST['from_date']) ? $_POST['from_date'] : date('d/m/Y');
                    $value2 = isset($_POST['to_date']) ? $_POST['to_date'] : date('d/m/Y');
                    echo DatePicker::widget([
                        'name' => 'from_date',
                        'value' => $value,
                        'type' => DatePicker::TYPE_RANGE,
                        'name2' => 'to_date',
                        'value2' => $value2,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd/mm/yyyy'
                        ],
                        'options' => [
                            'autocomplete' => 'off', 'readonly' => true
                        ],
                        'options2' => [
                            'autocomplete' => 'off', 'readonly' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-4" style="text-align: left;">
                        <?= Html::a('Reset', ['duration-summary'], ['class' => 'btn btn-danger']) ?>
                        <?= Html::submitButton('<i class="glyphicon glyphicon-import"></i> แสดงข้อมูล', ['class' => 'btn btn-primary']); ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <br>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'showFooter' => false,
                'showPageSummary' => true,
                'responsive' => true,
                'toolbar' => [
                    '{export}',
                ],
                'panel' => [
                    'heading'=>false,
                    'type'=>'success',
                    'before'=>'',
                    'after'=>'',
                    'footer'=>false
                ],
                'exportConfig' => [
                    GridView::EXCEL => [],
                    GridView::PDF => [],
                ],
                'hover' => true,
                'caption' => 'รายงานผู้จำนวนผู้มารับบริการทุกประเภทบริการ ' . (isset($_POST['from_date']) ? $_POST['from_date'] : '') . '-' . (isset($_POST['to_date']) ? $_POST['to_date'] : ''),
                'columns' => [
                    [
                        'class' => '\kartik\grid\SerialColumn'
                    ],
                    [
                        'attribute' => 'service_name',
                        'header' => 'ประเภทบริการ',
                        'pageSummary' => 'รวม',
                    ],
                    [
                        'attribute' => 'summary_queue',
                        'header' => 'จำนวนผู้มารับบริการ',
                        'hAlign' => 'center',
                        'pageSummary' => true,
                    ],
                    [
                        'header' => 'เวลารอเฉลี่ย(นาที)',
                        'hAlign' => 'center',
                        'attribute' => 'average_waiting',
                        'format' => ['decimal', 1],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'header' => 'เวลารอต่ำสุด(นาที)',
                        'hAlign' => 'center',
                        'attribute' => 'wating_time_min',
                        'format' => ['decimal', 0],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'header' => 'เวลารอสูงสุด(นาที)',
                        'hAlign' => 'center',
                        'attribute' => 'wating_time_max',
                        'format' => ['decimal', 0],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                    [
                        'header' => 'เวลาให้บริการเฉลี่ย(นาที)',
                        'hAlign' => 'center',
                        'attribute' => 'average_service',
                        'format' => ['decimal', 1],
                        'pageSummary' => true,
                        'pageSummaryFunc' => GridView::F_SUM,
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
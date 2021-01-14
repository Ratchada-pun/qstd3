<?php
use kartik\widgets\DatePicker;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use homer\widgets\highcharts\Highcharts;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;

$this->title = 'รายงาน';
?>
<?php
echo $this->render('_tabs');
?>
<div class="tab-content">
    <div id="tab-3" class="tab-pane active">
        <div class="panel-body" style="background: #fff;">
            <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
            <div class="form-group">
                <?= Html::label('เลือกวันที่', '',[ 'class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?php
                    $value = isset($_POST['from_date']) ? $_POST['from_date'] : date('Y-m-d');
                    $value2 = isset($_POST['to_date']) ? $_POST['to_date'] : date('Y-m-d');
                    echo DatePicker::widget([
                        'name' => 'from_date',
                        'value' => $value,
                        'type' => DatePicker::TYPE_RANGE,
                        'name2' => 'to_date',
                        'value2' => $value2,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ],
                        'options' => [
                            'autocomplete' =>'off','readonly' => true
                        ],
                        'options2' => [
                            'autocomplete' =>'off','readonly' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-sm-4">
                    <?= Html::a('Reset',['duration-summary'], ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-import"></i> แสดงข้อมูล', ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
            <?php ActiveForm::end();?>
            <hr>
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'แยกตามวันที่',
                        'active' => true,
                        'options' => ['id' => 'tab-duration-summary1'],
                    ],
                    [
                        'label' => 'แยกตามประเภทบริการ',
                        'options' => ['id' => 'tab-duration-summary2'],
                    ],
                ],
                'renderTabContent' => false,
                'encodeLabels' => false,
            ]);
            ?>
            <div class="tab-content">
                <div id="tab-duration-summary1" class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                echo Highcharts::widget([
                                    'options' => [
                                        "chart" => [
                                            "type" => 'column'
                                        ],
                                        'title' => ['text' => 'กราฟแสดงจำนวนผู้ที่มาใช้บริการตามช่วงเวลา '.Yii::$app->formatter->asDate($value, 'php:d/m/Y').' - '.Yii::$app->formatter->asDate($value2, 'php:d/m/Y')],
                                        'subtitle' => [
                                            'text' => 'สามารถคลิกที่แท่งกราฟเพื่อดูรายละเอียดเพิ่มเติมได้'
                                        ],
                                        'xAxis' => [
                                            'type' => 'category'
                                        ],
                                        'yAxis' => [
                                            'min' => 0,
                                            'title' => [
                                                'text' => 'จำนวน'
                                            ]
                                        ],
                                        'tooltip' => [
                                            'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
                                            'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>จำนวน {point.y} คิว</b><br/>'
                                        ],
                                        'plotOptions' => [
                                            'series' => [
                                                'borderWidth' => 0,
                                                'dataLabels' => [
                                                    'enabled' => true,
                                                    'format' => '{point.y}'
                                                ]
                                            ]
                                        ],
                                        'series' => [
                                            [
                                                'name' => 'วันที่',
                                                "colorByPoint" => true,
                                                'data' => $series
                                            ]
                                        ],
                                        'legend' => [
                                            'enabled' => false
                                        ],
                                        "drilldown" => [
                                            "series" => $drilldown
                                        ],
                                    ],
                                    'scripts' => [
                                        'modules/exporting', // adds Exporting button/menu to chart
                                        'modules/drilldown'
                                        //'themes/grid'        // applies global 'grid' theme to all charts
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                            <?php
                            echo GridView::widget([
                                'dataProvider'=> $dataProvider,
                                'caption' => 'ตารางสรุปจำนวนผู้ที่มาใช้บริการตามช่วงเวลา (แยกตามวันที่) '.Yii::$app->formatter->asDate($value, 'php:d/m/Y').' - '.Yii::$app->formatter->asDate($value2, 'php:d/m/Y'),
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
                                'showPageSummary' => true,
                                'captionOptions' => ['style' => 'text-align: center;font-size:18px;border-bottom: 1px solid #ddd;'],
                                'columns' => [
                                    [
                                        'class' => '\kartik\grid\SerialColumn'
                                    ],
                                    [
                                        'attribute' => 'day',
                                        'header' => 'วันที่',
                                        'hAlign' => 'center',
                                        'noWrap' => true
                                    ],
                                    [
                                        'attribute' => 't_6',
                                        'header' => '06:00-07:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_7',
                                        'header' => '07:00-08:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_8',
                                        'header' => '08:00-09:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_9',
                                        'header' => '09:00-10:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_10',
                                        'header' => '10:00-11:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_11',
                                        'header' => '11:00-12:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'class' => '\kartik\grid\FormulaColumn',
                                        'header' => 'ช่วงเช้า<br>06:00-12:00',
                                        'hAlign' => 'center',
                                        'value' => function ($model, $key, $index, $widget) {
                                            $p = compact('model', 'key', 'index');
                                            // Write your formula below
                                            return $widget->col(2, $p) + $widget->col(3, $p) + $widget->col(4, $p) + $widget->col(5, $p) + $widget->col(6, $p)+ $widget->col(7, $p);
                                        },
                                        'contentOptions' => ['style' => 'background-color:#ddd;'],
                                        'headerOptions' => ['style' => 'background-color:#ddd;'],
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_12',
                                        'header' => '12:00-13:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_13',
                                        'header' => '13:00-14:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_14',
                                        'header' => '14:00-15:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_15',
                                        'header' => '15:00-16:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_16',
                                        'header' => '16:00-17:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_17',
                                        'header' => '17:00-18:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'class' => '\kartik\grid\FormulaColumn',
                                        'header' => 'ช่วงบ่าย<br>12:00-18:00',
                                        'hAlign' => 'center',
                                        'value' => function ($model, $key, $index, $widget) {
                                            $p = compact('model', 'key', 'index');
                                            // Write your formula below
                                            return $widget->col(9, $p) + $widget->col(10, $p) + $widget->col(11, $p) + $widget->col(12, $p) + $widget->col(13, $p) + $widget->col(14, $p);
                                        },
                                        'contentOptions' => ['style' => 'background-color:#ddd;'],
                                        'headerOptions' => ['style' => 'background-color:#ddd;'],
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'class' => '\kartik\grid\FormulaColumn',
                                        'header' => 'รวม',
                                        'hAlign' => 'center',
                                        'value' => function ($model, $key, $index, $widget) {
                                            $p = compact('model', 'key', 'index');
                                            return $widget->col(2, $p) + $widget->col(3, $p) + $widget->col(4, $p) + $widget->col(5, $p) + $widget->col(6, $p)+ $widget->col(7, $p) + 
                                            $widget->col(9, $p) + $widget->col(10, $p) + $widget->col(11, $p) + $widget->col(12, $p) + $widget->col(13, $p) + $widget->col(14, $p);
                                        },
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                ],
                                'responsive'=>true,
                                'hover'=>true
                            ]);
                            ?>
                            </div>
                        </div><!-- End Row -->
                        <hr>
                    </div>
                </div><!-- Endtab -->
                <div id="tab-duration-summary2" class="tab-pane">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                echo Highcharts::widget([
                                    'options' => [
                                        "chart" => [
                                            "type" => 'column'
                                        ],
                                        'title' => ['text' => 'กราฟแสดงจำนวนผู้ที่มาใช้บริการตามจุดบริการ (แยกตามประเภทบริการ) '.Yii::$app->formatter->asDate($value, 'php:d/m/Y').' - '.Yii::$app->formatter->asDate($value2, 'php:d/m/Y')],
                                        'subtitle' => [
                                            'text' => ''
                                        ],
                                        'xAxis' => [
                                            'categories' => $categories,
                                            'crosshair' => true
                                        ],
                                        'yAxis' => [
                                            'min' => 0,
                                            'title' => [
                                                'text' => 'จำนวน'
                                            ]
                                        ],
                                        'tooltip' => [
                                            'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
                                            'pointFormat' => '<tr><td style="color:{series.color};padding:0">{series.name}: </td><td style="padding:0"><b>{point.y} คิว</b></td></tr>',
                                            'footerFormat' => '</table>',
                                            'shared' => true,
                                            'useHTML' => true
                                        ],
                                        'plotOptions' => [
                                            'column' => [
                                                'pointPadding' => 0.2,
                                                'borderWidth' => 0,
                                            ]
                                        ],
                                        'series' => $series_2,
                                    ],
                                    'scripts' => [
                                        'modules/exporting', // adds Exporting button/menu to chart
                                        'modules/drilldown'
                                        //'themes/grid'        // applies global 'grid' theme to all charts
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                            <?php
                            echo GridView::widget([
                                'dataProvider'=> $dataProviderService,
                                'caption' => 'ตารางสรุปจำนวนผู้ที่มาใช้บริการตามประเภทบริการ '.Yii::$app->formatter->asDate($value, 'php:d/m/Y').' - '.Yii::$app->formatter->asDate($value2, 'php:d/m/Y'),
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
                                'beforeHeader' => [
                                    [
                                        'columns' => [
                                            ['content' => '#','options' => ['rowspan' => 2,'style' => 'vertical-align: middle;text-align:center;']],
                                            ['content' => 'ชื่อบริการ','options' => ['rowspan' => 2,'style' => 'vertical-align: middle;text-align:center;']],
                                            ['content' => 'ช่วงเวลา','options' => ['colspan' => 14,'style' => 'text-align: center;']],
                                            ['content' => 'รวม','options' => ['rowspan' => 2,'style' => 'vertical-align: middle;text-align:center;']],
                                            ['content' => 'คิดเป็น(%)','options' => ['rowspan' => 2,'style' => 'vertical-align: middle;text-align:center;']],
                                        ]
                                    ]
                                ],
                                'showPageSummary' => true,
                                'captionOptions' => ['style' => 'text-align: center;font-size:18px;border-bottom: 1px solid #ddd;'],
                                'columns' => [
                                    [
                                        'class' => '\kartik\grid\SerialColumn',
                                        'headerOptions' => ['class' => 'kv-grid-hide'],
                                    ],
                                    [
                                        'attribute' => 'day',
                                        'header' => 'วันที่',
                                        'headerOptions' => ['class' => 'kv-grid-hide'],
                                        'format' => ['date','php:d/m/Y'],
                                        'group' => true,
                                        'groupedRow'=>true,                    // move grouped column to a single grouped row
                                        'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                                            return [
                                                'content'=>[             // content to show in each summary cell
                                                    1=>'Summary',
                                                    3=>GridView::F_SUM,
                                                    4=>GridView::F_SUM,
                                                    5=>GridView::F_SUM,
                                                    6=>GridView::F_SUM,
                                                    7=>GridView::F_SUM,
                                                    8=>GridView::F_SUM,
                                                    9=>GridView::F_SUM,
                                                    10=>GridView::F_SUM,
                                                    11=>GridView::F_SUM,
                                                    12=>GridView::F_SUM,
                                                    13=>GridView::F_SUM,
                                                    14=>GridView::F_SUM,
                                                    15=>GridView::F_SUM,
                                                    16=>GridView::F_SUM,
                                                    17=>GridView::F_SUM,
                                                ],
                                                'contentFormats'=>[      // content reformatting for each summary cell
                                                    3=>['format'=>'number', 'decimals'=>0],
                                                    4=>['format'=>'number', 'decimals'=>0],
                                                    5=>['format'=>'number', 'decimals'=>0],
                                                    6=>['format'=>'number', 'decimals'=>0],
                                                    7=>['format'=>'number', 'decimals'=>0],
                                                    8=>['format'=>'number', 'decimals'=>0],
                                                    9=>['format'=>'number', 'decimals'=>0],
                                                    10=>['format'=>'number', 'decimals'=>0],
                                                    11=>['format'=>'number', 'decimals'=>0],
                                                    12=>['format'=>'number', 'decimals'=>0],
                                                    13=>['format'=>'number', 'decimals'=>0],
                                                    14=>['format'=>'number', 'decimals'=>0],
                                                    15=>['format'=>'number', 'decimals'=>0],
                                                    16=>['format'=>'number', 'decimals'=>0],
                                                    17=>['format'=>'number', 'decimals'=>0],
                                                ],
                                                'contentOptions'=>[      // content html attributes for each summary cell
                                                    1=>['style'=>'font-variant:small-caps'],
                                                    3=>['style'=>'text-align:center'],
                                                    4=>['style'=>'text-align:center'],
                                                    5=>['style'=>'text-align:center'],
                                                    6=>['style'=>'text-align:center'],
                                                    7=>['style'=>'text-align:center'],
                                                    8=>['style'=>'text-align:center'],
                                                    9=>['style'=>'text-align:center'],
                                                    10=>['style'=>'text-align:center'],
                                                    11=>['style'=>'text-align:center'],
                                                    12=>['style'=>'text-align:center'],
                                                    13=>['style'=>'text-align:center'],
                                                    14=>['style'=>'text-align:center'],
                                                    15=>['style'=>'text-align:center'],
                                                    16=>['style'=>'text-align:center'],
                                                    17=>['style'=>'text-align:center'],                                        
                                                ],
                                                // html attributes for group summary row
                                                'options'=>['class'=>'danger','style'=>'font-weight:bold;']
                                            ];
                                        }
                                    ],
                                    [
                                        'attribute' => 'service_name',
                                        'header' => 'ชื่อบริการ',
                                        'headerOptions' => ['class' => 'kv-grid-hide'],
                                        'noWrap' => true,
                                    ],
                                    [
                                        'attribute' => 't_6',
                                        'header' => '06:00-07:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_7',
                                        'header' => '07:00-08:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_8',
                                        'header' => '08:00-09:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_9',
                                        'header' => '09:00-10:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_10',
                                        'header' => '10:00-11:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_11',
                                        'header' => '11:00-12:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'class' => '\kartik\grid\FormulaColumn',
                                        'header' => 'ช่วงเช้า<br>06:00-12:00',
                                        'hAlign' => 'center',
                                        'value' => function ($model, $key, $index, $widget) {
                                            $p = compact('model', 'key', 'index');
                                            // Write your formula below
                                            return $widget->col(3, $p) + $widget->col(4, $p) + $widget->col(5, $p) + $widget->col(6, $p) + $widget->col(7, $p) + $widget->col(8, $p);
                                        },
                                        'contentOptions' => ['style' => 'background-color:#ddd;'],
                                        'headerOptions' => ['style' => 'background-color:#ddd;'],
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_12',
                                        'header' => '12:00-13:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_13',
                                        'header' => '13:00-14:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_14',
                                        'header' => '14:00-15:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_15',
                                        'header' => '15:00-16:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_16',
                                        'header' => '16:00-17:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 't_17',
                                        'header' => '17:00-18:00',
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'class' => '\kartik\grid\FormulaColumn',
                                        'header' => 'ช่วงบ่าย<br>12:00-18:00',
                                        'hAlign' => 'center',
                                        'value' => function ($model, $key, $index, $widget) {
                                            $p = compact('model', 'key', 'index');
                                            // Write your formula below
                                            return $widget->col(10, $p) + $widget->col(11, $p) + $widget->col(12, $p) + $widget->col(13, $p) + $widget->col(14, $p) + $widget->col(15, $p);
                                        },
                                        'contentOptions' => ['style' => 'background-color:#ddd;'],
                                        'headerOptions' => ['style' => 'background-color:#ddd;'],
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'class' => '\kartik\grid\FormulaColumn',
                                        'header' => 'รวม',
                                        'headerOptions' => ['class' => 'kv-grid-hide'],
                                        'hAlign' => 'center',
                                        'value' => function ($model, $key, $index, $widget) {
                                            $p = compact('model', 'key', 'index');
                                            return $widget->col(3, $p) + $widget->col(4, $p) + $widget->col(5, $p) + $widget->col(6, $p) + $widget->col(7, $p) + $widget->col(8, $p) + 
                                            $widget->col(10, $p) + $widget->col(11, $p) + $widget->col(12, $p) + $widget->col(13, $p) + $widget->col(14, $p) + $widget->col(15, $p);
                                        },
                                        'noWrap' => true,
                                        'pageSummary' => true,
                                        'pageSummaryFunc' => GridView::F_SUM,
                                    ],
                                    [
                                        'attribute' => 'sum_all',
                                        'header' => 'คิดเป็น(%)',
                                        'headerOptions' => ['class' => 'kv-grid-hide'],
                                        'hAlign' => 'center',
                                        'noWrap' => true,
                                        'value' => function ($model, $key, $index) {
                                            return $model['total'] > 0 ? ($model['total']*100)/$model['sum_all'] : 0;
                                        },
                                        'format' => ['decimal',2]
                                    ],
                                ],
                                'responsive'=>true,
                                'hover'=>true
                            ]);
                            ?>
                            </div>
                        </div>
                    </div>
                </div><!-- End tab -->
            </div>
        </div>
    </div>
</div>
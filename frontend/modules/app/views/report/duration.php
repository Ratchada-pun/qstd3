<?php
use kartik\widgets\DatePicker;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use homer\widgets\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\grid\GridView;

$this->title = 'รายงาน';
?>
<?php
echo $this->render('_tabs');
?>
<div class="tab-content">
    <div id="tab-2" class="tab-pane active">
        <div class="panel-body" style="background: #fff;">
            <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
            <div class="form-group">
                <?= Html::label('เลือกวันที่', '',[ 'class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?php
                    $value = isset($_POST['from_date']) ? $_POST['from_date'] : date('Y-m-d');
                    echo DatePicker::widget([
                        'name' => 'from_date', 
                        'value' => $value,
                        'options' => ['placeholder' => 'Select date ...','autocomplete' =>'off','readonly' => true],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'todayHighlight' => true,
                            'autoclose'=>true,
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-4">
                    <?= Html::a('Reset',['duration'], ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-import"></i> แสดงข้อมูล', ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
            <?php ActiveForm::end();?>
            <hr>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    echo Highcharts::widget([
                        'options' => [
                            "chart" => [
                                "type" => 'column'
                            ],
                            'title' => ['text' => 'กราฟแสดงจำนวนผู้ที่มาใช้บริการตามช่วงเวลา ('.Yii::$app->formatter->asDate($value, 'php:d/m/Y').')'],
                            // 'xAxis' => [
                            //     'categories' => $categories,
                            //     'crosshair' => true
                            // ],
                            'subtitle' => [
                                'text' => 'จำนวนคิวทั้งหมด '.$total
                            ],
                            'xAxis' => [
                                'type' => 'category'
                            ],
                            'yAxis' => [
                                'min' => 0,
                                'title' => [
                                    'text' => 'จำนวน (ทั้งหมด '.$total.' คิว)'
                                ]
                            ],
                            'tooltip' => [
                                'headerFormat' => '<span style="font-size:11px">{series.name}</span><br>',
                                'pointFormat' => '<span style="color:{point.color}">{point.name}</span>: <b>จำนวน {point.y} คิว</b><br/>'
                                /* 'headerFormat' => '<span style="font-size:10px">{point.key}</span><table>',
                                'pointFormat' => new JsExpression(' \'<tr><td style="color:{series.color};padding:0">{series.name}: </td>\' + \'<td style="padding:0"><b>{point.y} คิว</b></td></tr>\' '),
                                'footerFormat' => '</table>',
                                'shared' => true,
                                'useHTML' => true */
                            ],
                            'plotOptions' => [
                                /* 'column' => [
                                    'pointPadding' => 0.2,
                                    'borderWidth' => 0,
                                    'dataLabels' => [
                                        'enabled' => true
                                    ]
                                ] */
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
                                    'name' => 'ช่วงเวลา',
                                    "colorByPoint" => true,
                                    'data' => $series
                                ]
                            ],
                            'legend' => [
                                'enabled' => false
                            ],
                            "drilldown" => [
                                "series" => $series2
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
                    'caption' => 'ตารางสรุปจำนวนผู้ที่มาใช้บริการตามช่วงเวลา ('.Yii::$app->formatter->asDate($value, 'php:d/m/Y').')',
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
                    'columns' => [
                        [
                            'class' => '\kartik\grid\SerialColumn'
                        ],
                        [
                            'header' => 'วันที่',
                            'hAlign' => 'center',
                            'value' => function($model, $key, $index) use ($value){
                                return $value;
                            },
                            'noWrap' => true
                        ],
                        [
                            'attribute' => 't_6',
                            'header' => '06:00-07:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_7',
                            'header' => '07:00-08:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_8',
                            'header' => '08:00-09:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_9',
                            'header' => '09:00-10:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_10',
                            'header' => '10:00-11:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_11',
                            'header' => '11:00-12:00',
                            'hAlign' => 'center',
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
                        ],
                        [
                            'attribute' => 't_12',
                            'header' => '12:00-13:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_13',
                            'header' => '13:00-14:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_14',
                            'header' => '14:00-15:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_15',
                            'header' => '15:00-16:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_16',
                            'header' => '16:00-17:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'attribute' => 't_17',
                            'header' => '17:00-18:00',
                            'hAlign' => 'center',
                        ],
                        [
                            'class' => '\kartik\grid\FormulaColumn',
                            'header' => 'ช่วงบ่าย<br>12:00-18:00',
                            'hAlign' => 'center',
                            'value' => function ($model, $key, $index, $widget) {
                                $p = compact('model', 'key', 'index');
                                // Write your formula below
                                return $widget->col(9, $p) + $widget->col(10, $p) + $widget->col(11, $p) + $widget->col(12, $p) + $widget->col(13, $p)+ $widget->col(14, $p);
                            },
                            'contentOptions' => ['style' => 'background-color:#ddd;'],
                            'headerOptions' => ['style' => 'background-color:#ddd;'],
                            'noWrap' => true
                        ],
                        [
                            'header' => 'รวม',
                            'hAlign' => 'center',
                            'value' => function($model, $key, $index) use ($total){
                                return $total;
                            },
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true
                ]);
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
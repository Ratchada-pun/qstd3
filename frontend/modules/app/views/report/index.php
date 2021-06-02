<?php
use kartik\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use frontend\modules\app\models\TbService;
use yii\helpers\ArrayHelper;

$this->title = 'รายงาน';
?>
<?php
echo $this->render('_tabs');
?>
<div class="tab-content">
    <div id="tab-1" class="tab-pane active">
        <div class="panel-body" style="background: #fff;">
            <?php
            $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'method' => 'get']);
            ?>
            <div class="form-group">
                <?= Html::activeLabel($searchModel, 'startdate', ['label' => 'เลือกวันที่', 'class'=>'col-sm-2 control-label']) ?> 
                <div class="col-sm-4">
                    <?php
                    echo DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'startdate',
                        'attribute2' => 'enddate',
                        'options' => ['placeholder' => 'Start date','autocomplete' =>'off','readonly' => true],
                        'options2' => ['placeholder' => 'End date','autocomplete' =>'off','readonly' => true],
                        'type' => DatePicker::TYPE_RANGE,
                        'form' => $form,
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
                        ]
                    ]); ?>
                </div>
            </div>
            <?php /*
            <div class="form-group">
                <?= Html::activeLabel($searchModel, 'pt_name', [ 'class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($searchModel, 'pt_name',['showLabels'=>false])->textInput(['placeholder'=>'']); ?>
                </div>
            </div>
            */?>
            <div class="form-group">
                <?= Html::activeLabel($searchModel, 'q_hn', [ 'class'=>'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($searchModel, 'q_hn',['showLabels'=>false])->textInput(['placeholder'=>'']); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6" style="text-align: right;">
                    <?= Html::a('Reset',['index'], ['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-import"></i> แสดงข้อมูล', ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
            <?php
            ActiveForm::end();
            ?>
        <hr>
        <?php
        echo GridView::widget([
            'id'=>'report-grid',
            'dataProvider'=> $dataProvider,
            'filterModel' => $searchModel,
            'responsive'=>true,
            'hover'=>true,
            'panel' => [
                'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> รายงาน</h3>',
                'type'=>'default',
                'before'=>'',
                'after'=>'',
                'footer'=>''
            ],
            'columns' => [
                [
                    'class' => '\kartik\grid\SerialColumn'
                ],
                [
                    'attribute' => 'q_hn',
                    'group' => true,
                ],
                [
                    'attribute' => 'pt_name',
                    'group' => true,
                ],
                [
                    'attribute' => 'q_timestp',
                    'group' => true,
                ],
                [
                    'attribute' => 'service_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filterWidgetOptions' => [
                        'data' => ArrayHelper::map(TbService::find()->asArray()->all(),'service_name','service_name'),
                        'options' => ['placeholder' => 'Select...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]
                    //'group' => true,
                ],
                [
                    'attribute' => 'counterservice_type',
                ],
                [
                    'attribute' => 'counterservice_name',
                ],
                [
                    'attribute' => 'call_timestp',
                    'format' => ['date','php:H:i:s'],
                    'hAlign' => 'center',
                ],
                [
                    'attribute' => 'caller_updated_at',
                    'format' => ['date','php:H:i:s'],
                    'hAlign' => 'center',
                ],
                [
                    'attribute' => 't_waiting_to_finished',
                    'noWrap' => true,
                    'value' => function($model, $key, $index){
                        return Html::badge($model['t_waiting_to_finished']);
                    },
                    'format' => 'raw',
                    'hAlign' => 'center',
                ],
                /* [
                    'attribute' => 'counterservice_type2',
                ],
                [
                    'attribute' => 'counterservice_name2',
                ],
                [
                    'attribute' => 'call_timestp2',
                    'format' => ['date','php:H:i:s'],
                ],
                [
                    'attribute' => 'caller_updated_at2',
                    'format' => ['date','php:H:i:s'],
                ],
                [
                    'attribute' => 't_waiting_to_finished2',
                    'noWrap' => true,
                    'value' => function($model, $key, $index){
                        return Html::badge($model['t_waiting_to_finished2']);
                    },
                    'format' => 'raw',
                    'hAlign' => 'center'
                ], */
                [
                    'attribute' => 't_total',
                    'noWrap' => true,
                    'value' => function($model, $key, $index){
                        return Html::badge($model['t_total'],['class' => 'badge-success']);
                    },
                    'format' => 'raw',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'group' => true,
                ],
                /* [
                    'class' => '\kartik\grid\ActionColumn',
                ] */
            ],
        ]);
        ?>
        </div>
    </div> <!-- End tab -->
</div>

<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\bootstrap\Tabs;
use homer\assets\SweetAlert2Asset;
use PHPUnit\Util\Log\JSON;

SweetAlert2Asset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\app\models\TbDrugDispensingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'รายการรับยาใกล้บ้าน';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="hpanel">

            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'รายการ',
                        'content' => $this->render('_columns_drug_dispensing',['searchModel' => $searchModel ]),
                        'active' => true,
                    ],
                    [
                        'label' => 'ประวัติ',
                        'content' => $this->render('_columns_history'),
                    ],
                ],
                'encodeLabels' => false,
            ]);
            ?>
        </div>
    </div>
</div>
<?php /*
<div class="tb-drug-dispensing-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Tb Drug Dispensings','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Tb Drug Dispensings listing',
                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Are you sure?',
                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>

*/ ?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    "size" => "modal-lg",
    "options" => ["tabindex" => false],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
])?>
<?php Modal::end(); ?>



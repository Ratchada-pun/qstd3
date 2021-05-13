<?php

use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;

$this->title = 'รายชื่อผู้รับบริการรับยาใกล้บ้าน';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="panel-body">
    <div class="table-responsive">

            <?php  /*echo $this->render('_form', ['searchModel' => $searchModel]); */ ?>
        <?php
        echo Table::widget([
            'tableOptions' => ['class' => 'table table-hover', 'width' => '100%', 'id' => 'get_personal_drug'],
            'beforeHeader' => [
                [
                    'columns' => [
                        ['content' => '#', 'options' => []],
                       // ['content' => 'เลขที่รายการ', 'options' => []],
                        ['content' => 'HN', 'options' => []],
                        ['content' => 'ชื่อผู้รับบริการ', 'options' => []],
                        ['content' => 'วันที่สร้างรายการ', 'options' => []],
                        ['content' => 'วันที่ปรับปรุงรายการ', 'options' => []],
                        ['content' => 'สถานะ', 'options' => []],
                        ['content' => 'Action', 'options' => []],
                    ]
                ]
            ],
        ]);
        ?>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'get_personal_drug',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => 'data-personal-drug',
            'method' => 'get'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'], [
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่มรายการ', ['drug-dispensing/create-user-drug'],['class' => 'btn btn-success','role' => 'modal-remote']),
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        "autoWidth" => false,
        "deferRender" => true,
        'initComplete' => new JsExpression('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        'columns' => [
            ["data" => null, "defaultContent" => "", "className" => "dt-center dt-head-nowrap", "title" => "#", "orderable" => false],
           // ["data" => "personal_drug_id", "className" => "dt-body-left dt-head-nowrap", "title" => "เลขที่รายการ"],
            ["data" => "hn", "className" => "dt-body-left dt-head-nowrap", "title" => "HN"],
            ["data" => "fullname", "className" => "dt-body-left dt-head-nowrap", "title" => "ชื่อผู้รับบริการ"],
            ["data" => "personal_drug_date_create", "className" => "dt-body-left dt-head-nowrap", "title" => "วันที่สร้างรายการ"],
            ["data" => "personal_drug_date_update", "className" => "dt-body-left dt-head-nowrap", "title" => "วันที่ปรับปรุงรายการ"],
            ["data" => "is_active", "className" => "dt-body-left dt-head-nowrap", "title" => "สถานะ"],
            ["data" => "actions", "className" => "dt-center dt-nowrap", "orderable" => false, "title" => "Action"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    "size" => "modal-lg",
    "options" => ["tabindex" => false],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
])?>
<?php Modal::end(); ?>

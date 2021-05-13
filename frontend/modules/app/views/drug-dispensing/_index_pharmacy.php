<?php

use homer\assets\SweetAlert2Asset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\bootstrap\Tabs;

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
                        'label' => 'รายชื่อผู้รับบริการรับยาใกล้บ้าน',
                        'content' => $this->render('_columns_personal_drug'),
                        'active' => true,
                    ],
                    [
                        'label' => 'จัดการร้านขายยา',
                        'content' => $this->render('_columns_pharmcy_drug'),
                    ],
                ],
                'encodeLabels' => false,
            ]);
            ?>
        </div>
    </div>
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    "size" => "modal-lg",
    "options" => ["tabindex" => false],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
])?>
<?php Modal::end(); ?>


<?php
$this->registerJs(<<<JS
yii.confirm = function (message, ok, cancel) {
    var url = $(this).attr('href')
    swal({
        title: $(this).data('confirm'),
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function (data) {
                        var table = $('#get_personal_drug').DataTable();
                        table.ajax.reload();
                        resolve()
                    },
                    error: function(jqXHR, errMsg) {
                        swal(
                            'Oops!',
                            errMsg,
                            'error'
                        )
                    }
                });
            })
        }
    }).then((result) => {
        if (result.value) {
            swal(
                '',
                'ปิดใช้งานสำเร็จ',
                'success'
            )
        }
    })
    return false;
}

// yii.confirm = function (message, ok, cancel) {
//     var url = $(this).attr('href')
//     swal({
//         title: 'ยืนยันลบข้อมูล?',
//         text: "",
//         type: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'ยืนยันลบข้อมูล',
//         cancelButtonText: 'ยกเลิก',
//         showLoaderOnConfirm: true,
//         preConfirm: function() {
//             return new Promise(function(resolve) {
//                 $.ajax({
//                     url: url,
//                     type: 'POST',
//                     success: function (data) {
//                         var table = $('#get_personal_drug').DataTable();
//                         table.ajax.reload();
//                         var table2 = $('#get_pharmacy_drug').DataTable();
//                         table2.ajax.reload();
//                         resolve()
//                     },
//                     error: function(jqXHR, errMsg) {
//                         swal(
//                             'Oops!',
//                             errMsg,
//                             'error'
//                         )
//                     }
//                 });
//             })
//         }
//     }).then((result) => {
//         if (result.value) {
//             swal(
//                 'Deleted!',
//                 'ลบข้อมูลสำเร็จ',
//                 'success'
//             )
//         }
//     })
//     return false;
// }

JS
);
?>
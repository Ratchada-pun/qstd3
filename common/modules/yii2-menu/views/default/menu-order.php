<?php
use homer\widgets\nestable\Nestable;
use yii\helpers\Html;
use homer\widgets\panelwidget\PanelWidget;
use yii\widgets\Pjax;
use homer\widgets\Modal;
use yii\bootstrap\Tabs;
use homer\assets\BootboxAsset;
use homer\assets\DatatablesAsset;
use homer\assets\ToastrAsset;
use yii\icons\Icon;

DatatablesAsset::register($this);
BootboxAsset::register($this);
ToastrAsset::register($this);

$this->title = "Mange Menu";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4">
    <?php Pjax::begin(['id' => 'index-pjax']); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Sort Menu</h3>
            </div>
        <div class="panel-body">
        <?php
        echo Nestable::widget([
            'type' => Nestable::TYPE_WITH_HANDLE,
            //'query' => common\modules\user\models\Profile::find(),
            'modelOptions' => [
                'name' => 'name'
            ],
            'pluginEvents' => [
                'change' => 'function(e) {
                    var items = $(this).nestable(\'serialize\');
                    $.ajax({
                        type: "POST",
                        url: \'/menu/default/save-menusort\',
                        data: {items: items},
                        success: function(data, textStatus ,jqXHR ){
                            bootbox.alert("Change Saved!");
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            bootbox.alert(errorThrown);
                        },
                    });
                }',
                'dropCallback' => 'function(e) {
                    console.log(this);
                }'
            ],
            'clientOptions' => [
                'maxDepth' => 3,
            ],
            'items' => $items,
            'options' => ['class' => 'dd','id' => 'nestable2'],
            'handleLabel' => true,
            'collapseAll' => true 
        ]);
        ?>
            </div>
        </div>
        <?php Pjax::end(); ?>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Menu</h3>
            </div>
            <div class="panel-body">
                <table id="menu-table" class="table table-hover" cellspacing="0" width="100%">
                    <thead class="bordered-darkorange">
                        <tr>
                            <th>ชื่อเมนู</th>
                            <th>หมวดเมนู</th>
                            <th>ภายใต้เมนู</th>
                            <th>เรียง</th>
                            <th>สถานะ</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    'options' => ['class' => 'modal modal-danger','tabindex' => false,],
    'size' => 'modal-lg',
]);

Modal::end();
#Register JS
$btn = Html::a(Icon::show('plus').' Add Menu', ['create'], ['class' => 'btn btn-success','role' => 'modal-remote']);
$this->registerJs(<<<JS
var table = $('#menu-table').DataTable( {
    "dom": "<'row'<'col-sm-3'l><'col-sm-9'f>>" +"<'row'<'col-sm-12'tr>>" +"<'row'<'col-sm-5'i><'col-sm-7'p>>",
    "ajax": '/menu/default/dt-data',
    "order": [[ 3, 'asc' ]],
    "columns": [
        { "data": "title" },
        { "data": "cat_title" },
        { "data": "parent" },
        { "data": "sort" },
        { "data": "status" },
        { "data": "actions", "className" : "dt-nowrap dt-center" }
    ],
    "language": {
        "search": "Search: _INPUT_ " + '$btn'
    }
} );

dt = {
    delete: function(key){
        bootbox.confirm({
            message: "คุณมั่นใจว่าต้องการลบข้อมูลนี้?",
            callback: function (result) {
                if(result){
                    $.ajax({
                        type: "POST",
                        url: '/menu/default/delete-menu',
                        data: {id: key},
                        success: function(data, textStatus ,jqXHR ){
                            $.pjax.reload({container:'#index-pjax'});
                            bootbox.alert(data);
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            bootbox.alert(errorThrown);
                        },
                    });
                }
            }
        });
    }
};

$("#index-pjax").on("pjax:success", function() {
    table.ajax.reload();
});
JS
);
?>
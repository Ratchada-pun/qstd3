<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 27/9/2561
 * Time: 21:11
 */

use homer\widgets\Table;
use kartik\widgets\DatePicker;

?>
<div class="panel-body">
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-4 col-md-offset-4">
            <?php
            echo 'Visit Date';
            echo DatePicker::widget([
                'id' =>'dp-vstdate',
                'name' => 'dp_1',
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'value' => date('d/m/Y'),
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd/mm/yyyy',
                ],
                'pluginEvents' => [
                    "changeDate" => "function(e) {
                        var table = $('#tb-patients').DataTable();
                        table.ajax.url( '/app/calling/data-patients-list?vstdate='+$('#dp-vstdate').val() ).load();
                        table.button( 1 ).processing( true );
                    }",
                ],
                'readonly' => true,
            ]);
            ?>
        </div>
    </div>
    <?php
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed','width' => '100%','id' => 'tb-patients'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#','options' => ['style' => 'text-align: center;']],
                    ['content' => 'HN','options' => ['style' => 'text-align: center;']],
                    ['content' => 'VN','options' => ['style' => 'text-align: center;']],
                    ['content' => 'CID','options' => ['style' => 'text-align: center;']],
                    ['content' => 'ชื่อ-นามสกุล','options' => ['style' => 'text-align: center;']],
                    ['content' => 'Visit Date','options' => ['style' => 'text-align: center;']],
                    ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
                ]
            ]
        ],
    ]);
    ?>
</div>
<?php
$this->registerJs(<<<JS
$(document).ready(function() {
    var t = $('#tb-patients').DataTable( {
        "ajax": {
            "url" : "/app/calling/data-patients-list",
            "complete" : function(jqXHR, textStatus) {
                t.button( 1 ).processing( false );
            },
        },
        //"deferRender": true,
        "autoWidth": false,
        "pageLength": 50,
        "dom": "<'row'<'col-xs-6'f><'col-xs-6'lB>> <'row'<'col-xs-12'tr>> <'row'<'col-xs-5'i><'col-xs-7'p>>",
        "buttons": [
            'excel',
            {
                text: 'Reload',
                action: function ( e, dt, node, config ) {
                    this.processing( true );
                    dt.ajax.reload();
                }
            }
        ],
        "lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
        "language": {
            "sProcessing":   "กำลังดำเนินการ...",
            "sLengthMenu":   "_MENU_",
            "sZeroRecords":  "ไม่พบข้อมูล",
            "sInfo":         "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
            "sInfoEmpty":    "แสดง 0 ถึง 0 จาก 0 แถว",
            "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกแถว)",
            "sInfoPostFix":  "",
            "sSearch":       "ค้นหา: ",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "หน้าแรก",
                "sPrevious": "ก่อนหน้า",
                "sNext":     "ถัดไป",
                "sLast":     "หน้าสุดท้าย"
            }
        },
        "columns": [
            { "data": null,"defaultContent": "" ,"className": "dt-center dt-nowrap"},
            { "data": "hn", "className": "dt-center"},
            { "data": "vn", "className": "dt-center" },
            { "data": "cid", "className": "dt-center" },
            { "data": "fullname" },
            { "data": "vstdate" , "className": "dt-center"},
            { "data": "actions","className": "dt-center dt-nowrap" }
        ],
        "drawCallback": function( settings ) {
            var api = this.api();
            var count  = api.data().count();
            $("#count-patients").html(count);
        }
    } );
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
} );

Que = {
    handleClick: function() {
        $('#tb-patients-ist tbody').on( 'click', 'tr td a', function (event) {
            event.preventDefault();
            var table = $('#tb-patients').DataTable();
            var tr = $(this).closest("tr"), serviceid = $(this).attr("data-key"), groupid = $(this).attr("data-group");
            if(tr.hasClass("child") && typeof table.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = table.row( tr ).data();
            var txt = $(this).text();
            swal({
                title: 'ยืนยัน?',
                text: data.fullname,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+data.fullname+'</p>'+
                    '<p><i class="fa fa-angle-double-down"></i></p><p>'+txt+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ลงทะเบียน',
                cancelButtonText: 'ยกเลิก',
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve) {
                        $.ajax({
                            url: baseUrl+'/app/kiosk/register',
                            type: 'POST',
                            data: $.extend( data, {groupid:groupid,serviceid:serviceid} ),
                            dataType: 'JSON',
                            success: function (res) {
                                if(res.status == "200"){
                                    toastr.success(res.modelQ.pt_name, 'Printing #' + res.modelQ.q_num, {timeOut: 3000,positionClass: "toast-top-right",});
                                    window.open(res.url,"myPrint","width=800, height=600");
                                    table.ajax.reload();
                                    dt_tbqdata.ajax.reload();
                                    socket.emit('register', res);//sending data
                                    resolve();
                                }else{
                                    swal('Oops...','เกิดข้อผิดพลาด!','error');
                                }
                            },
                            error: function(jqXHR, errMsg) {
                                swal('Oops...',errMsg,'error');
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {
                    swal.close();
                }
            });
        });
    }
};
Que.handleClick();
JS
);
?>
<?php

use yii\helpers\Html;
use yii\icons\Icon;

?>
<div class="panel-body">
    <div class="row" id="hpanel-ticket">
		<?php foreach ($service as $key => $data) : ?>
            <div class="col-sm-1">

            </div>
            <div class="col-sm-4">
				<?php foreach ($data->servicesTicket as $i => $value) : ?>
                    <?php if($value['service_prefix'] <= 4) {?>
                    <p><?= Html::a($value['service_prefix'] . ' ' . $value['service_name'], ['/app/kiosk/register', 'groupid' => $data['servicegroupid'], 'serviceid' => $value['serviceid']], ['class' => 'btn btn-lg  btn-block btn-success activity-ticket', 'style' => 'text-align:left; font-size:25px;', 'title' => $value['service_name']]) ?></p>
                        <br>
                    <?php }?>
                <?php endforeach; ?>

            </div>
            <div class="col-sm-4">
				<?php foreach ($data->servicesTicket as $i => $value) : ?>
					<?php if($value['service_prefix'] >= 5) {?>
                        <p><?= Html::a($value['service_prefix'] . ' ' . $value['service_name'], ['/app/kiosk/register', 'groupid' => $data['servicegroupid'], 'serviceid' => $value['serviceid']], ['class' => 'btn btn-lg btn-block btn-success activity-ticket', 'style' => 'text-align:left;font-size:25px;', 'title' => $value['service_name']]) ?></p>
                        <br>
                    <?php }?>
				<?php endforeach; ?>
            </div>
            <div class="col-sm-1">

            </div>
		<?php endforeach; ?>
    </div>
</div>
<?php
$this->registerJs(<<<JS
	var elm = $('.hpanel-ticket').find('.panel-body');
	var Ticketheight = [];
	$.each(elm, function( index, value ) {
		Ticketheight.push($(this).height());
	});
	$("#hpanel-ticket .panel-body").css("height",(Math.max.apply(Math,Ticketheight)+40) );
	$('a.activity-ticket').on('click',function(e){
		e.preventDefault();
		var elm = this;
        var url = $(elm).attr('href');
		var title = $(elm).attr('title');
        swal({
		  	title: title,
		  	text: "พิมพ์บัตรคิว",
		  	type: 'question',
		  	showCancelButton: true,
		  	confirmButtonText: 'พิมพ์บัตรคิว',
		  	cancelButtonText: 'ยกเลิก',
		  	allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: function(value) {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        method: "GET",
                        url: url,
                        dataType: "json",
                        success: function(res, textStatus, jqXHR){
                            if(res.status == "200"){
                                window.open(res.url,"myPrint","width=800, height=600");
                                // dt_tbqdata.ajax.reload();
                                socket.emit('register', res);//sending data
                            }else{
                                swal('Oops...','เกิดข้อผิดพลาด!','error');
                            }
                            resolve();
                        },
                        error: function(jqXHR, textStatus, errorThrown){
                            swal({
                                type: 'error',
                                title: 'Oops...',
                                text: errorThrown,
                            });
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
JS
);
$this->registerCss(<<< CSS

a:link {
color:#ffffff;
  text-decoration: none;
}

a:visited {
color:#ffffff;
  text-decoration: none;
}

a:hover {
color:#ffffff;
  text-decoration: none;
}

a:active {
color:#ffffff;
  text-decoration: none;
}

h1 {
color:#ffffff !important;
}

/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */

/* Style the accordion panel. Note: hidden by default */
CSS
);
?>

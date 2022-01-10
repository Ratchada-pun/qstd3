<?php
use yii\helpers\Html;
use homer\assets\SocketIOAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;

$this->title = 'ออกบัตรคิว';

SocketIOAsset::register($this);
SweetAlert2Asset::register($this);
ToastrAsset::register($this);
?>
<br>
<style>
.btn-success {
    border: 10px solid #62cb31;
    border-radius: 15px;
}
.btn-lg, .btn-group-lg > .btn {
    padding: 0px;
}
/* Small devices (tablets, 768px and up) */
@media (min-width: 768px) {
    .btn-lg {
        font-size: 40px;
    }
    .row {
        padding-right: 15px;
        padding-left: 15px;
    }
    .content-kiosk {
        padding-right: 0px;
        padding-left: 0px;
    }
    #marquee {
        font-size: 40px;
    }
}

/* Medium devices (desktops, 992px and up) */
@media (min-width: 992px) {
    .btn-lg {
        font-size: 50px;
    }
    .row {
        padding-right: 15px;
        padding-left: 15px;
    }
    #marquee {
        font-size: 50px;
    }
}

/* Large devices (large desktops, 1200px and up) */
@media (min-width: 1200px) {
    .btn-lg {
        font-size: 90px;
    }
    .row {
        padding-right: 15px;
        padding-left: 15px;
    }
    #marquee {
        font-size: 60px;
    }
}
.center-ticket {
    position: absolute;
    top: 50%;
    left:50%;
    transform: translate(-50%,-50%);
    width: 100%;
}

.footer-kiosk {
    position: fixed;
    bottom: 0;
    width: 100%;
}
p {
  margin:0px !important;
}
@media print {
  p {
    margin:0px !important;
  }
}
</style>
<div class="row">
    <div class="col-md-8 col-md-offset-2 content-kiosk">
        <?php foreach($service as $data): ?>
            <?php foreach ($data->servicesTicket as $i => $value) : ?>
                <?php if($value['service_groupid'] == 1 && $value['show_on_kiosk'] == 1): ?>
                    <?= Html::a('<i class="fa fa-hand-pointer-o"></i>'.$value['btn_kiosk_name'],['/app/kiosk/register','groupid' => $data['servicegroupid'],'serviceid' => $value['serviceid']],[
                        'class' => 'btn btn-lg btn-block btn-success btn-outline activity-ticket',
                        'style' => 'text-align: center;',
                        'title' => $value['btn_kiosk_name'],
                        'data-loading-text' => 'กำลังออกหมายเลขคิว...'
                    ]); ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php
$this->registerJs(<<<JS
	$('a.activity-ticket').on('click',function(e){
		e.preventDefault();
        var btn = $(this).button('loading');
		var elm = this;
        var url = $(elm).attr('href');
		var title = $(elm).attr('title');
        $.ajax({
            method: "GET",
            url: url,
            dataType: "json",
            success: function(res, textStatus, jqXHR){
                btn.button('reset');
                if(res.status == "200"){
                    window.open(res.url,"myPrint","width=800, height=600");
                    socket.emit('register', res);//sending data
                }else{
                    swal('Oops...','เกิดข้อผิดพลาด!','error');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                btn.button('reset');
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: errorThrown,
                });
            }
        });
        /*
        swal({
		  	title: "ยืนยันการพิมพ์บัตรคิว?",
		  	text: title,
		  	type: 'warning',
		  	showCancelButton: true,
		  	confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
		}).then((result) => {
		  	if (result.value) {
		  		$.ajax({
				  	method: "GET",
				  	url: url,
				  	dataType: "json",
				  	success: function(res, textStatus, jqXHR){
				  		if(res.status == "200"){
			        		window.open(res.url,"myPrint","width=800, height=600");
			        		socket.emit('register', res);//sending data
			        	}else{
			        		swal('Oops...','เกิดข้อผิดพลาด!','error');
			        	}
				  	},
				  	error: function(jqXHR, textStatus, errorThrown){
				  		swal({
						  	type: 'error',
						  	title: 'Oops...',
						  	text: errorThrown,
						});
				  	}
				});
		  	}
		});*/
	});
JS
);
?>

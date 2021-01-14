<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\icons\Icon;
use kartik\widgets\DatePicker;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Url;
use frontend\modules\app\models\TbCidStation;
use yii\helpers\ArrayHelper;

$this->registerCss(<<<CSS
.modal-dialog{
    width: 90%;
}
CSS
);
$this->registerJs('var serviceData = ' . Json::encode($serviceData) . ';', View::POS_HEAD);
$this->registerJs('var modelService = ' . Json::encode($modelService) . ';', View::POS_HEAD);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . ';', View::POS_HEAD);
?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'id' => $model->formName(), 'options' => ['autocomplete' => 'off']]); ?>

            <div class="form-group">
                <div class="col-sm-12" style="text-align: center;">
                    <button type="button" class="btn btn-success btn-lg" disabled>
                        <i class="fa fa-tags"></i> <?= $modelService['service_name'] ?>
                    </button>
                </div>
            </div>
            <br>
            <?php
            $model->search_by = 0;
            ?>
            <div class="form-group">
                <?= Html::activeLabel($model, 'search_by', ['label' => 'ค้นหาด้วย', 'class' => 'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'search_by', ['showLabels' => false])->radioList([0 => 'HN', 1 => 'เลขบัตรประชาชน'], ['inline' => true]); ?>
                </div>

                <?= Html::activeLabel($model, 'cid_station', ['label' => 'จุดอ่านบัตร', 'class' => 'col-sm-1 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'cid_station', ['showLabels' => false])->radioList(ArrayHelper::map(TbCidStation::find()->where(['status' => 1])->asArray()->all(), 'id', 'name'), ['inline' => true, 'itemOptions' => ['class' => 'i-checks']]); ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::activeLabel($model, 'q_hn', ['label' => '', 'class' => 'col-sm-2 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'q_hn', ['showLabels' => false])->textInput([
                        'placeholder' => 'HN',
                        'class' => 'input-lg',
                        'autofocus' => true,
                        'required' => true,
                        'style' => 'background-color: #34495e;color: #FFFFFF;',
                    ]); ?>
                </div>

                <?= Html::activeLabel($model, 'vstdate', ['label' => 'Visit Date', 'class' => 'col-sm-1 control-label']) ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'vstdate', ['showLabels' => false])->widget(DatePicker::classname(), [
                        'options' => [
                            'placeholder' => 'VisitDate...',
                            'value' => Yii::$app->formatter->asDate('now', 'php:d/m/Y'),
                            'style' => 'background-color: #34495e;color: #FFFFFF;',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd/mm/yyyy',
                        ],
                        'readonly' => true,
                        'size' => 'lg',
                    ]); ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-11" style="text-align: right;">
                    <?= Html::button(Icon::show('close') . 'CLOSE', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
                    <?= Html::resetButton(Icon::show('refresh').' Reset',['class' => 'btn btn-danger']) ?>
                    <?= Html::submitButton(Icon::show('search') . 'SEARCH', ['class' => 'btn btn-success activity-search', 'data-loading-text' => 'Searching...']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php
$this->registerJs(<<<JS
var \$form = $('#TbQuequ');
var input = $("#tbquequ-q_hn");
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('#TbQuequ button.activity-search').button('loading');
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function (res) {
        	if(res === false){
        		swal({
				  type: 'warning',
				  title: 'ไม่พบข้อมูล!',
				  text: $('#tbquequ-q_hn').val(),
				  showConfirmButton: false,
				  timer: 5000
				});
        	}else if(res === "registed"){
        		swal({
				  type: 'warning',
				  title: 'ลงทะเบียนแล้ว!',
				  showConfirmButton: false,
				  timer: 5000
				});
        	}else{
        		QueueForm.register(res);
        	}
			\$btn.button('reset');
            //\$form.trigger("reset");
        },
        error: function(jqXHR, errMsg) {
            swal('Oops...',errMsg,'error');
            \$btn.button('reset');
        }
    });
    return false; // prevent default submit
});

$('#tbquequ-search_by input[type="radio"]').on('click',function(){
	if($(this).val() == 0){
		input.attr("placeholder","HN");
	}else{
		input.attr("placeholder","เลขบัตรประชาชน");
		input.val(null);
	}
	input.focus();
});

$('#tbquequ-cid_station input[type="radio"]').on('click',function(){
	var data = \$form.serialize();
	$.ajax({
        url: '/app/calling/set-cid-station',
        type: 'POST',
        data: data,
        dataType: 'JSON',
        success: function (res) {
        	console.log(res);
        },
        error: function(jqXHR, errMsg) {
            swal('Oops...',errMsg,'error');
        }
    });
});

QueueForm = {
	register: function(data){
		var table = $('#tb-qdata').DataTable();
		var tablepatients = $('#tb-patients').DataTable();
		swal({
		  	title: 'ยืนยัน?',
		  	text: data.fullname,
		  	html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i> '+data.fullname+'</p>'+
                    '<p><i class="fa fa-angle-double-down"></i></p><p>'+modelService.service_name+'</p>',
		  	type: 'question',
		  	showCancelButton: true,
		  	confirmButtonText: 'พิมพ์บัตรคิว',
		  	cancelButtonText: 'ยกเลิก',
		  	allowOutsideClick: false,
            showLoaderOnConfirm: true,
		  	preConfirm: function() {
				return new Promise(function(resolve) {
					$.ajax({
						url: '/app/kiosk/register',
						type: 'POST',
						data: $.extend( data, serviceData ),
						dataType: 'JSON',
						success: function (res) {
							if(res.status == "200"){
								input.val(null);
								input.focus();
								toastr.success(res.modelQ.pt_name, 'Printing #' + res.modelQ.q_num, {timeOut: 3000,positionClass: "toast-top-right",});
								window.open(res.url,"myPrint","width=800, height=600");
								table.ajax.reload();
								tablepatients.ajax.reload();
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
		  	}else{
				input.val(null);
				input.focus();
			}
		});
	}
};

socket.on('read-card', function(res){
	var dataArray = $(\$form).serializeArray(),
			dataObj = {};
	$(dataArray).each(function (i, field) {
		dataObj[field.name] = field.value;
	});
	if(dataObj['TbQuequ[cid_station]'] == res['EZ1503378440057007100[station_id]']){
		var cid = JSON.stringify(res['EZ1503378440057007100[pt_cid]']);
		$('#tbquequ-q_hn').val(cid.replace(/[^0-9]/g,''));
		$.ajax({
			url: \$form.attr('action'),
			type: 'POST',
			data: \$form.serialize(),
			dataType: 'JSON',
			success: function (res) {
				if(res === false){
					swal({
						type: 'warning',
						title: 'ไม่พบข้อมูล!',
						text: $('#tbquequ-q_hn').val(),
						showConfirmButton: false,
						timer: 5000
					});
				}else if(res === "registed"){
					swal({
						type: 'warning',
						title: 'ลงทะเบียนแล้ว!',
						showConfirmButton: false,
						timer: 5000
					});
					input.val(null);
				}else{
					QueueForm.register(res);
				}
			},
			error: function(jqXHR, errMsg) {
				swal('Oops...',errMsg,'error');
			}
		});
	}
	
	/* $('#pt_cid').html(res['EZ1503378440057007100[pt_cid]']);
	$('#fullname_th').html(res['EZ1503378440057007100[fullname_th]']);
	$('#fullname_en').html(res['EZ1503378440057007100[fullname_en]']);
	$('#bdate').html(res['EZ1503378440057007100[bdate]']);
	$('#address').html(res['EZ1503378440057007100[address]']); */
});
setTimeout(function(){ $('#tbquequ-q_hn').focus(); }, 500);
JS
);
?>
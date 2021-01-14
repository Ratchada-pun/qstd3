Queue = {
    setDataSession: function(){
        var $form = $('#calling-form');
        var data = $form.serialize();
        $.ajax({
            method: "POST",
            url: "/app/calling/set-counter-session",
            data: data,
            dataType: "json",
            beforeSend: function(jqXHR, settings){
                swal({
                    title: 'Loading...',
                    text: '',
                    onOpen: () => {
                        swal.showLoading()
                    }
                }).then((result) => {
                });
            },
            success: function(res){
                swal.close();
                location.reload();
            },
            error:function(jqXHR, textStatus, errorThrown){
                Queue.ajaxAlertError(errorThrown);
            }
        });
    },
    handleEventClick: function(){
        var self = this;
        //เรียกคิวรอ
        $('#tb-waiting tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
            var dt_tbwaiting = jQuery('#tb-waiting').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbwaiting.row( tr ).data();
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                input: 'select',
                type: 'warning',
                inputOptions: select2Data,
                inputPlaceholder: 'เลือกห้องตรวจ',
                inputValue: data.counter_service_id || '',
                inputClass: 'form-control m-b',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value !== '') {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + "/app/calling/call-examination-room",
                                dataType: "json",
                                data: {
                                    data:data,//Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile,
                                    value:value //select value
                                },
                                success: function(res){
                                    if(res.status == 200){
                                        self.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                        self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                        self.toastrSuccess('CALL ' + data.qnumber);
                                        socket.emit('call-examination-room', res);//sending data
                                        resolve();
                                    }else{
                                        self.ajaxAlertWarning();
                                    }
                                },
                                error:function(jqXHR, textStatus, errorThrown){
                                    self.ajaxAlertError(errorThrown);
                                }
                            });
                        } else {
                            resolve('คุณไม่ได้เลือกห้องตรวจ!')
                        }
                    });
                }
            });
            jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th"});
            $('select.swal2-select, span.select2').addClass('input-lg');
            $('#swal2-content').css('padding-bottom','15px');
        });

        //เรียกคิวซ้ำ
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-recall', function (event) {
            event.preventDefault();
            var dt_tbcalling = jQuery('#tb-calling').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {//Confirm
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/recall-examination-room",
                        dataType: "json",
                        data: {
                            data:data,//Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile
                        },
                        success: function(res){
                            if(res.status == 200){
                                //dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียก
                                self.toastrSuccess('RECALL ' + data.qnumber);
                                socket.emit('call-examination-room', res);//sending data
                            }else{
                                self.ajaxAlertWarning();
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            self.ajaxAlertError(errorThrown);
                        }
                    });
                }
            });
        });

        //พักคิว
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-hold', function (event) {
            event.preventDefault();
            var dt_tbcalling = jQuery('#tb-calling').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ยืนยันพักคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'พักคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {//Confirm
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/hold-examination-room",
                        dataType: "json",
                        data: {
                            data:data,//Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile
                        },
                        success: function(res){
                            if(res.status == 200){//success
                                self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                self.toastrSuccess('HOLD ' + data.qnumber);
                                socket.emit('hold-examination-room', res);//sending data
                            }else{//error
                                self.ajaxAlertWarning();
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            self.ajaxAlertError(errorThrown);
                        }
                    });
                }
            });
        });

        //เรียกคิว hold
        $('#tb-hold tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
            var dt_tbhold = jQuery('#tb-hold').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbhold.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbhold.row( tr ).data();
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {//Confirm
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/callhold-examination-room",
                        dataType: "json",
                        data: {
                            data:data,//Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile
                        },
                        success: function(res){
                            if(res.status == 200){//success
                                self.reloadTbCalling();//โหลดข้อมูลกำลังเรียก
                                self.reloadTbHold();//โหลดข้อมูลพักคิว
                                self.toastrSuccess('CALL ' + data.qnumber);
                                socket.emit('call-examination-room', res);//sending data
                            }else{//error
                                self.ajaxAlertWarning();
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            self.ajaxAlertError(errorThrown);
                        }
                    });
                }
            });
        });

        //End คิว hold
        $('#tb-hold tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
            var dt_tbhold = jQuery('#tb-hold').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbhold.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbhold.row( tr ).data();
            swal({
                title: 'ยืนยัน End คิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {//Confirm
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/endhold-examination-room",
                        dataType: "json",
                        data: {
                            data:data,//Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile
                        },
                        success: function(res){
                            if(res.status == 200){//success
                                self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                self.toastrSuccess('END ' + data.qnumber);
                                socket.emit('endq-examination-room', res);//sending data
                            }else{//error
                                self.ajaxAlertWarning();
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            self.ajaxAlertError(errorThrown);
                        }
                    });
                }
            });
        });

        //End คิว
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
            var dt_tbcalling = jQuery('#tb-calling').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ยืนยัน End คิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.value) {//Confirm
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/end-examination-room",
                        dataType: "json",
                        data: {
                            data:data,//Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile
                        },
                        success: function(res){
                            if(res.status == 200){//success
                                self.reloadTbCalling();//โหลดข้อมูล
                                self.toastrSuccess('END ' + data.qnumber);
                                socket.emit('endq-examination-room', res);//sending data
                            }else{//error
                                self.ajaxAlertWarning();
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            self.ajaxAlertError(errorThrown);
                        }
                    });
                }
            });
        });
    },
    init: function(){
        var self = this;
        self.handleEventClick();
    },
    reloadTbWaiting: function(){
        var dt_tbwaiting = jQuery('#tb-waiting').DataTable();
        dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอ
    },
    reloadTbCalling: function(){
        var dt_tbcalling = jQuery('#tb-calling').DataTable();
        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียก
    },
    reloadTbHold: function(){
        var dt_tbhold = jQuery('#tb-hold').DataTable();
        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
    },
    toastrSuccess: function(msg){
        if(localStorage.getItem('disablealert-pagecalling') == 'on'){
            toastr.success(msg, 'Success!', {timeOut: 3000,positionClass: "toast-top-right"});
        }
    },
    toastrWarning: function(title = 'Warning!',msg = ''){
        if(localStorage.getItem('disablealert-pagecalling') == 'on'){
            toastr.warning(msg, title, {timeOut: 5000,positionClass: "toast-top-right"});
        }
    },
    ajaxAlertError: function(msg){
        swal({
            type: 'error',
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    },
    ajaxAlertWarning: function(){
        swal({
            type: 'error',
            title: 'เกิดข้อผิดพลาด!!',
            showConfirmButton: false,
            timer: 1500
        });
    },
    checkCounter: function(){
        if(modelForm.service_profile == null){
            var title = 'กรุณาเลือกโปรไฟล์';
            swal({
                type: 'warning',
                title: title,
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }else{
            return true;
        }
    }
};

//socket event
$(function() {
    socket
    .on('endq-screening-room', (res) => {
        var services = (modelProfile.service_id).split(',');
        if(jQuery.inArray((res.modelQ.serviceid).toString(), services) != -1) {//ถ้าคิวมีใน service profile
            if(localStorage.getItem('playsound-pagecalling') == 'on'){
                var player = $("#jplayer_notify").jPlayer({
                    ready: function () {
                        $(this).jPlayer("setMedia", {
                            mp3: "/media/alert.mp3",
                        }).jPlayer("play");
                    },
                    supplied: "mp3",
                    ended: function() { // The $.jPlayer.event.ended event
                        $(this).jPlayer("stop"); // Repeat the media
                    },
                });
                $('#jplayer_notify').jPlayer("play");
            }
            Queue.reloadTbWaiting();//โหลดข้อมูลรอเรียก
            Queue.toastrWarning('ผู้ป่วยลงทะเบียนใหม่!',res.modelQ.pt_name);
        }
    })
    .on('call-examination-room', (res) => {//เรียกคิวรอ
        if(res.eventOn === 'tb-waiting' && res.state === 'call'){//เรียกคิวจาก table คิวรอพบแพทย์
            Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            swal.close();
        }else if(res.eventOn === 'tb-hold' && res.state === 'call-hold'){//เรียกคิวจาก table พักคิว
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
        }
    })
    .on('hold-examination-room', (res) => {//Hold คิวห้องตรวจ /kiosk/calling/examination-room
        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
        Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
    })
    .on('endq-examination-room', (res) => {//จบ Process q /kiosk/calling/examination-room
        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
    }).on('display', (res) => {
        setTimeout(function(){
            var dt_tbcalling = jQuery('#tab-calling').DataTable();
            dt_tbcalling.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                if(data.qnumber == res.title){
                    $('#tb-calling').find('tr.success').removeClass("success");
                    $("#last-queue").html(data.qnumber);
                    dt_tbcalling.$("tr#"+res.artist.data.DT_RowId).addClass("success");
                    Queue.toastrWarning('', '<i class="pe-7s-speaker"></i> กำลังเรียกคิว #'+data.qnumber);
                }
            });
        }, 500);
    });
});

//CallNext
$('a.activity-callnext').on('click',function(e){
    e.preventDefault();
    var dt_tbwaiting = jQuery('#tb-waiting').DataTable();
    var data = dt_tbwaiting.rows(0).data();
    if(data.length > 0){
        swal({
            title: 'ยืนยันเรียกคิว '+data[0].qnumber+' ?',
            html: '<p><i class="fa fa-user"></i> '+data[0].pt_name+'</p>',
            input: 'select',
            type: 'warning',
            inputOptions: select2Data,
            inputPlaceholder: 'เลือกห้องตรวจ',
            inputValue: data[0].counter_service_id || '',
            inputClass: 'form-control m-b',
            showCancelButton: true,
            confirmButtonText: 'เรียกคิว',
            cancelButtonText: 'ยกเลิก',
            allowOutsideClick: false,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value !== '') {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/call-examination-room",
                            dataType: "json",
                            data: {
                                data:data[0],//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                                value:value //select value
                            },
                            success: function(res){
                                if(res.status == 200){
                                    Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                    Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    Queue.toastrSuccess('CALL ' + data[0].qnumber);
                                    socket.emit('call-examination-room', res);//sending data
                                    resolve();
                                }else{
                                    Queue.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                Queue.ajaxAlertError(errorThrown);
                            }
                        });
                    } else {
                        resolve('คุณไม่ได้เลือกห้องตรวจ!')
                    }
                });
            }
        });
        jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th"});
        $('select.swal2-select, span.select2').addClass('input-lg');
        $('#swal2-content').css('padding-bottom','15px');
    }else{
        swal({
          type: 'warning',
          title: 'ไม่พบหมายเลขคิว',
          showConfirmButton: false,
          timer: 1500
        });
    }
});

var $form = $('#calling-form');
$form.on('beforeSubmit', function() {
    var dataObj = {};
    var qcall;
    var dt_tbwaiting = jQuery('#tb-waiting').DataTable();
    var dt_tbcalling = jQuery('#tb-calling').DataTable();
    var dt_tbhold = jQuery('#tb-hold').DataTable();

    $form.serializeArray().map(function(field){
        dataObj[field.name] = field.value;
    });

    if(dataObj['CallingForm[qnum]'] != null && dataObj['CallingForm[qnum]'] != ''){
        //ข้อมูลกำลังเรียก
        dt_tbcalling.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.qnumber === dataObj['CallingForm[qnum]']){
                qcall = {data:data,tbkey:'tbcalling'};
            }
        });
        //ข้อมูลคิวรอพบแพทย์
        dt_tbwaiting.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.qnumber === dataObj['CallingForm[qnum]']){
                qcall = {data:data,tbkey:'tbwaiting'};
            }
        });
        //ข้อมูลพักคิว
        dt_tbhold.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.qnumber === dataObj['CallingForm[qnum]']){
                qcall = {data:data,tbkey:'tbhold'};
            }
        });

        if(qcall === undefined){
            toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-right"});
        }else{
            if(qcall.tbkey === 'tbcalling'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/recall-examination-room",
                            dataType: "json",
                            data: {
                                data:qcall.data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){
                                    Queue.toastrSuccess('RECALL ' + qcall.data.qnumber);
                                    socket.emit('call-examination-room', res);//sending data
                                }else{
                                    Queue.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                Queue.ajaxAlertError(errorThrown);
                            }
                        });
                    }
                });
            }else if(qcall.tbkey === 'tbhold'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/callhold-examination-room",
                            dataType: "json",
                            data: {
                                data: qcall.data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    Queue.toastrSuccess('CALL ' + qcall.data.qnumber);
                                    socket.emit('call-examination-room', res);//sending data
                                }else{//error
                                    Queue.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                Queue.ajaxAlertError(errorThrown);
                            }
                        });
                    }
                });
            }else if(qcall.tbkey === 'tbwaiting'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+qcall.data.pt_name+'</p>',
                    input: 'select',
                    type: 'warning',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: qcall.data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value !== '') {
                                $.ajax({
                                    method: "POST",
                                    url: baseUrl + "/app/calling/call-examination-room",
                                    dataType: "json",
                                    data: {
                                        data: qcall.data,//Data in column Datatable
                                        modelForm: modelForm, //Data Model CallingForm
                                        modelProfile: modelProfile,
                                        value:value //select value
                                    },
                                    success: function(res){
                                        if(res.status == 200){
                                            Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                            Queue.toastrSuccess('CALL ' + qcall.data.qnumber);
                                            socket.emit('call-examination-room', res);//sending data
                                            resolve();
                                        }else{
                                            Queue.ajaxAlertWarning();
                                        }
                                    },
                                    error:function(jqXHR, textStatus, errorThrown){
                                        Queue.ajaxAlertError(errorThrown);
                                    }
                                });
                            } else {
                                resolve('คุณไม่ได้เลือกห้องตรวจ!')
                            }
                        });
                    }
                });
                jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th"});
                $('select.swal2-select, span.select2').addClass('input-lg');
                $('#swal2-content').css('padding-bottom','15px');
            }
        }
    }else{
        toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-right"});
    }
    $('input#callingform-qnum').val(null);//clear data
    return false;
});

Queue.init();
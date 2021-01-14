Queue = {
    handleEventClick: function(){
        var self = this;
        //เรียกคิว
        $('#tb-waiting tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
            var dt_tbwaiting = jQuery('#tb-waiting').DataTable();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbwaiting.row( tr ).data();
            var countername = $('#callingform-counter_service').select2('data')[0]['text'] || '';
            if(self.checkCounter()){
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    text: data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i> '+data.pt_name+'</p><p>'+countername+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/call-screening-room",
                            dataType: "json",
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){
                                    self.reloadTbWaiting();//โหลดข้อมูลรอเรียก
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียก
                                    self.toastrSuccess('CALL ' + data.qnumber);
                                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                                    socket.emit('call-screening-room', res);//sending data
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
            }
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
            var countername = $('#callingform-counter_service').select2('data')[0]['text'] || '';
            if(self.checkCounter()){
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    text: data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i> '+data.pt_name+'</p><p>'+countername+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/recall-screening-room",
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
                                    socket.emit('call-screening-room', res);//sending data
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
            }
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
            if(self.checkCounter()){
                swal({
                    title: 'ยืนยันพักคิว '+data.qnumber+' ?',
                    text: data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'พักคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/hold-screening-room",
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
                                    socket.emit('hold-screening-room', res);//sending data
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
            }
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
            var countername = $('#callingform-counter_service').select2('data')[0]['text'] || '';
            if(self.checkCounter()){
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    text: data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i> '+data.pt_name+'</p><p>'+countername+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/callhold-screening-room",
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
                                    socket.emit('call-screening-room', res);//sending data
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
            }
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
            if(self.checkCounter()){
                swal({
                    title: 'ยืนยัน END คิว '+data.qnumber+' ?',
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
                            url: baseUrl + "/app/calling/endhold-screening-room",
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
                                    self.toastrSuccess('End ' + data.qnumber);
                                    socket.emit('endq-screening-room', res);//sending data
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
            }
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
            if(self.checkCounter()){
                swal({
                    title: 'ยืนยัน END คิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'warning',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value !== '') {
                                $.ajax({
                                    method: "POST",
                                    url: baseUrl + "/app/calling/end-screening-room",
                                    dataType: "json",
                                    data: {
                                        data:data,//Data in column Datatable
                                        modelForm: modelForm, //Data Model CallingForm
                                        modelProfile: modelProfile,
                                        value:value //select value
                                    },
                                    success: function(res){
                                        if(res.status == "200"){
                                            self.reloadTbCalling();//โหลดข้อมูลกำลังเรียก
                                            self.toastrSuccess('End ' + data.qnumber);
                                            socket.emit('endq-screening-room', res);//sending data
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
                                resolve('คุณไม่ได้เลือกรายการ!')
                            }
                        });
                    }
                });
                jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th",sorter: function(data) {
                    return data.sort(function(a, b) {
                        return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                    });
                }});
                $('select.swal2-select, span.select2').addClass('input-lg');
                $('#swal2-content').css('padding-bottom','15px');
            }
        });
    },
    init: function(){
        var self = this;
        self.handleEventClick();
    },
    reloadTbWaiting: function(){
        var dt_tbwaiting = jQuery('#tab-watting').DataTable();
        dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอ
    },
    reloadTbCalling: function(){
        var dt_tbcalling = jQuery('#tab-calling').DataTable();
        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียก
    },
    reloadTbHold: function(){
        var dt_tbhold = jQuery('#tab-hold').DataTable();
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
        if(modelForm.counter_service == null || modelForm.service_profile == null){
            var title = modelForm.service_profile == null ? 'กรุณาเลือกโปรไฟล์' : 'กรุณาเลือกช่องบริการ';
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

//Socket Events
$(function() {
    socket.on('register', (res) => {//ออกบัตรคิว
        var services = (modelProfile.service_id).split(',');
        if(jQuery.inArray(res.modelQ.serviceid, services) != -1) {//ถ้าคิวมีใน service profile
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
    }).on('call-screening-room', (res) => {
        if(res.eventOn === 'tb-waiting' && res.state === 'call'){
            Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอ
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียก
            swal.close();
        }else if(res.eventOn === 'tb-hold' && res.state === 'call-hold'){
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
        }
    }).on('hold-screening-room', (res) => {
        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
        Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
    }).on('endq-screening-room', (res) => {
        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
        Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
    }).on('display', (res) => {
        setTimeout(function(){
            var dt_tbcalling = jQuery('#tab-calling').DataTable();
            dt_tbcalling.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                if(data.qnumber == res.title){
                    $('#tb-calling').find('tr.success').removeClass("success");
                    dt_tbcalling.$('tr.success').removeClass("success");
                    $("#last-queue").html(data.qnumber);
                    dt_tbcalling.$("tr#"+res.artist.data.DT_RowId).addClass("success");
                    Queue.toastrWarning('', '<i class="pe-7s-speaker"></i> กำลังเรียกคิว #'+data.qnumber);
                }
            });
        }, 500);
    });
    //
    $( window ).resize(function() {
        if($(window).width() > 992){
            $('.call-next').draggable();
        }
    });
    if($(window).width() > 992){
        $('.call-next').draggable();
    }
    //
    $('#callingform-qnum').keyup(function(){
        this.value = this.value.toUpperCase();
    });
});

//CallNext
$('a.activity-callnext').on('click',function(e){
    e.preventDefault();
    var dt_tbwaiting = jQuery('#tab-waiting').DataTable();
    var data = dt_tbwaiting.rows(0).data();
    var countername = $('#callingform-counter_service').select2('data')[0]['text'] || '';
    if(data.length > 0){
        if(Queue.checkCounter()){
            swal({
                title: 'CALL NEXT ' +data[0].qnumber+ ' ?',
                text: data[0].qnumber,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +'<p><i class="fa fa-user"></i> '+data[0].pt_name+'</p>'+ '<p>'+countername+'</p>',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/call-screening-room",
                        dataType: "json",
                        data: {
                            data: data[0], //Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile
                        },
                        success: function(res){
                            if(res.status == 200){
                                Queue.reloadTbWaiting();//โหลดข้อมูลรอเรียก
                                Queue.reloadTbCalling()//โหลดข้อมูลกำลังเรียก
                                Queue.toastrSuccess('CALL ' + data[0].qnumber);
                                //$("html, body").animate({ scrollTop: 0 }, "slow");
                                socket.emit('call-screening-room', res);//sending data
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
        }
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
    var countername = $('#callingform-counter_service').select2('data')[0]['text'] || '';
    var dt_tbwaiting = jQuery('#tab-waiting').DataTable();
    var dt_tbcalling = jQuery('#tab-calling').DataTable();
    var dt_tbhold = jQuery('#tab-hold').DataTable();

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
        //ข้อมูลรอเรียก
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
            toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-center"});
        }else{
            if(qcall.tbkey === 'tbcalling'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +'<p><i class="fa fa-user"></i> '+qcall.data.pt_name+'</p>'+ '<p>'+countername+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/recall-screening-room",
                            dataType: "json",
                            data: {
                                data:qcall.data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function(res){
                                if(res.status == 200){
                                    Queue.toastrSuccess('RECALL ' + qcall.data.qnumber);
                                    socket.emit('call-screening-room', res);//sending data
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
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +'<p><i class="fa fa-user"></i> '+qcall.data.pt_name+'</p>'+ '<p>'+countername+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/callhold-screening-room",
                            dataType: "json",
                            data: {
                                data: qcall.data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    Queue.toastrSuccess('CALL ' + qcall.data.qnumber);
                                    socket.emit('call-screening-room', res);//sending data
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
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +'<p><i class="fa fa-user"></i> '+qcall.data.pt_name+'</p>'+ '<p>'+countername+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/call-screening-room",
                            dataType: "json",
                            data: {
                                data: qcall.data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function(res){
                                if(res.status == 200){
                                    Queue.reloadTbWaiting();//โหลดข้อมูลรอเรียกคัดกรองใหม่
                                    Queue.reloadTbCalling();//โหลดข้อมูลกำลัวเรียกใหม่
                                    Queue.toastrSuccess('CALL ' + qcall.data.qnumber);
                                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                                    socket.emit('call-screening-room', res);//sending data
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
            }
        }
    }else{
        toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-center"});
    }
    $('input#callingform-qnum').val(null);//clear data
    return false;
});

Queue.init();

$('#tb-calling tbody,#tb-waiting tbody,#tb-hold tbody').on( 'click', 'tr td a', function () {
    var tr = $(this).closest("tr") , selected = 'active',id = $(this).closest("table").attr('id');
    if ( !$(tr).hasClass(selected) ) {
        if(id == 'tb-waiting'){
            $('#tb-waiting').find('tr.'+selected).removeClass(selected);
        }
        if(id == 'tb-calling'){
            $('#tb-calling').find('tr.'+selected).removeClass(selected);
        }
        if(id == 'tb-hold'){
            $('#tb-hold').find('tr.'+selected).removeClass(selected);
        }
        $(tr).addClass(selected);
    }
} );

//hidden menu
$('body').addClass('hide-sidebar');
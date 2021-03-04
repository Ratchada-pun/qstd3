$(function() {
    //hidden menu
    $('body').addClass('hide-sidebar');
    //$('input[type="search"]').removeClass('input-sm').addClass('input-lg');

    $('input[type="search"]').focus(function(){
        //animate
        $(this).animate({
            width: '250px',
        }, 400 )
    }); 

    $('input[type="search"]').blur(function(){
        $(this).animate({
            width: '160px'
        }, 500 );
    });

    $('#callingform-qnum').keyup(function(){
        this.value = this.value.toUpperCase();
    });

    // Toastr options
    toastr.options = {
        "debug": false,
        "newestOnTop": false,
        "positionClass": "toast-top-center",
        "closeButton": true,
        "toastClass": "animated fadeInDown",
    };

    // Initialize iCheck plugin
    var input = $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_square-green'
    });

    //Checkbox Event
    $(input).on('ifChecked', function(event){
        var key = $(this).val();
        Queue.setDataSession();
    });

    $(input).on('ifUnchecked', function(event){
        var key = $(this).val();
        Queue.setDataSession();
    });
});

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
    reloadStatus: function(){
        var _counter= $.ajax({
                                method: 'POST',
                                async: false,
                                url: baseUrl + '/app/calling/find-status',
                                dataType: 'json',
                                success: function (data) {
                                    $('#statusall').empty();
                                    $('#statusall').append(data[0].qnumber);
                                } 
                            });
    },
    handleEventClick: function(){
        var self = this;
        //เรียกคิวรอ
        $('#btncall').on('click',function(even){
          
            var counterid = [];
            var btn;
           
            $("label.checkbox-inline").find("input:checked").each(function (i, ob) { 
                counterid.push($(ob).val());
            });
            var _counter= $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/calling/find-counter',
                dataType: 'json',
                data: {
                    counterid:counterid
                },
                success: function (data) {
                    data;
                } 
            });
            var counter = _counter['responseJSON'];
            // alert(JSON.stringify(counter));
            var btn = [];
            var btnvalue = [];
            for(var i =0;i < counter.length; i ++){
               btn[i]= counter[i].counterservice_name;
               btnvalue[i]=counter[i].counterserviceid;
            }
            swal({
                    title: 'กรุณาคลิกเลือกห้องตรวจ',
                    html:
                        '<hr />' +
                        '<div>' +
                        '<button id="btn1" class="btn btn-info btn-lg">' +  btn[0] + '</button>&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<button id="btn2" class="btn btn-success btn-lg" >' +  btn[1] + '</button>'+
                        '</div>' +
                        '<br>' + 
                        '<div>' +
                        '<button class="btn btn-warning btn-lg" id="btn3">' +  btn[2] + '</button>&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<button class="btn btn-danger btn-lg" id="btn4">' +  btn[3] + '</button>'+
                        '</div>' +
                        '<br>' + 
                        '<div>' +
                        '<button class="btn btn-info btn-lg" id="btn5">' +  btn[4] + '</button>&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<button class="btn btn-success btn-lg" id="btn6">' +  btn[5] + '</button>'+
                        '</div>' +
                        '<br>' + 
                        '<div>' +
                        '<button class="btn btn-warning btn-lg" id="btn7">' +  btn[6] + '</button>&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<button class="btn btn-danger btn-lg" id="btn8">' +  btn[7] + '</button>'+
                        '</div>' +
                        '<br>' + 
                        '<div>' +
                        '<button class="btn btn-info btn-lg" id="btn9">' +  btn[8] + '</button>' +
                        '</div>'
                        ,
                    showCancelButton: true,
                    cancelButtonText: 'ยกเลิก',
                    showConfirmButton: false,
            })
            btn[0] === undefined ? $('#btn1').hide() : $('#btn1').show();
            btn[0] === undefined ? $('#btn1').hide() : $("#btn1").attr('value', btnvalue[0]);
            btn[1] === undefined ? $('#btn2').hide() : $('#btn2').show();
            btn[1] === undefined ? $('#btn2').hide() : $("#btn2").attr('value', btnvalue[1]);
            btn[2] === undefined ? $('#btn3').hide() : $('#btn3').show() ;
            btn[2] === undefined ? $('#btn3').hide() : $("#btn3").attr('value', btnvalue[2]);
            btn[3] === undefined ? $('#btn4').hide() : $('#btn4').show() ;
            btn[3] === undefined ? $('#btn4').hide() : $("#btn4").attr('value', btnvalue[3]);
            btn[4] === undefined ? $('#btn5').hide() : $('#btn5').show();
            btn[4] === undefined ? $('#btn5').hide() : $("#btn5").attr('value', btnvalue[4]);
            btn[5] === undefined ? $('#btn6').hide() : $('#btn6').show();
            btn[5] === undefined ? $('#btn6').hide() : $("#btn6").attr('value', btnvalue[5]);
            btn[6] === undefined ? $('#btn7').hide() : $('#btn7').show();
            btn[6] === undefined ? $('#btn7').hide() : $("#btn7").attr('value', btnvalue[6]);
            btn[7] === undefined ? $('#btn8').hide() : $('#btn8').show();
            btn[7] === undefined ? $('#btn8').hide() : $("#btn8").attr('value', btnvalue[7]);
            btn[8] === undefined ? $('#btn9').hide() : $('#btn9').show();
            btn[8] === undefined ? $('#btn9').hide() : $("#btn9").attr('value', btnvalue[8]);

            $('#btn1').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn1').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('01');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn2').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn2').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('02');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn3').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn3').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('03');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn4').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn4').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('04');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn5').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn5').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('05');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn6').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn6').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('06');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn7').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn7').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('07');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn8').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn8').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('08');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })

            $('#btn9').on('click',function(even){
                event.preventDefault();
                var tr = $(this).closest("tr");
                if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                    tr = $(this).closest("tr").prev();
                }
                var key = tr.data("key");
                var _data = $.ajax({
                    method: 'POST',
                    async: false,
                    url: baseUrl + '/app/calling/find-ids',
                    dataType: 'json',
                    data: {
                        counterid:$('#btn9').val()
                    },
                    success: function (data) {
                        data
                    } 
                });
                var data = _data['responseJSON'][0];
                swal({
                    title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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
                                        $('#C_N').empty();
                                        $('#C_N').append('09');
                                        $('#Q_N').empty();
									    $('#Q_N').append(data.qnumber);
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
            })
        });
      

        $('#btnrecall').on('click',function(even){
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            // var data = dt_tbcalling.row( tr ).data();
            var _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/calling/find-ids-recall',
                dataType: 'json',
                data: {
                    q_num:$('#Q_N').text()
                },
                success: function (data) {
                   data
                } 
            });
            var data = _data['responseJSON'][0];
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    resolve();
                                }else{
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        })

        $('#btnhold').on('click',function(even){
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            // var data = dt_tbcalling.row( tr ).data();
           
            var _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/calling/find-ids-recall',
                dataType: 'json',
                data: {
                    q_num:$('#Q_N').text()
                },
                success: function (data) {
                   data
                } 
            });
            var data = _data['responseJSON'][0];
            swal({
                title: 'ยืนยันพักคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'พักคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    $('#C_N').empty();
                                    $('#Q_N').empty();
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    self.toastrSuccess('HOLD ' + data.qnumber);
                                    socket.emit('hold-examination-room', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        })

        $('#btnend').on('click',function(even){
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            // var data = dt_tbcalling.row( tr ).data();
            var _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/calling/find-ids-recall',
                dataType: 'json',
                data: {
                    q_num:$('#Q_N').text()
                },
                success: function (data) {
                   data
                } 
            });
            var data = _data['responseJSON'][0];
            
            swal({
                title: 'ยืนยัน End คิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    $('#C_N').empty();
                                    $('#Q_N').empty();
                                    self.reloadTbCalling();//โหลดข้อมูล
                                    self.toastrSuccess('END ' + data.qnumber);
                                    socket.emit('endq-examination-room', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        })

        $('#tb-waiting tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
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
                type: 'question',
                inputOptions: select2Data,
                inputPlaceholder: 'เลือกห้องตรวจ',
                inputValue: data.counter_service_id || '',
                inputClass: 'form-control m-b',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                    return 'คุณไม่ได้เลือกห้องตรวจ!'
                    }
                },
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        var _counter= $.ajax({
                            method: 'POST',
                            async: false,
                            url: baseUrl + '/app/calling/find-counter',
                            dataType: 'json',
                            data: {
                                counterid:data.counter_service_id
                            },
                            success: function (data) {
                                data;
                            } 
                        });
                        var counter = _counter['responseJSON'];
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
                                    $('#C_N').empty();
                                    $('#C_N').append(counter[0].counterservice_callnumber);
                                    $('#Q_N').empty();
                                    $('#Q_N').append(data.qnumber);
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
        });

        //เรียกคิวซ้ำ
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-recall', function (event) {
            event.preventDefault();
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
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    resolve();
                                }else{
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        //พักคิว
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-hold', function (event) {
            event.preventDefault();
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
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'พักคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        $('#tb-calling tbody').on( 'click', 'tr td a.btn-transfer', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ส่งคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                input: 'select',
                type: 'question',
                inputOptions: select2Data,
                inputPlaceholder: 'เลือกห้องตรวจ',
                inputValue: data.counter_service_id || '',
                inputClass: 'form-control m-b',
                showCancelButton: true,
                confirmButtonText: 'ส่งคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                    return 'คุณไม่ได้เลือกห้องตรวจ!'
                    }
                },
                preConfirm: function(value) {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/transfer-examination-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                                value:value //select value
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    // self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    self.toastrSuccess('Transfer ' + data.qnumber);
                                    socket.emit('transfer-examination-room', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
            jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th",sorter: function(data) {
                return data.sort(function(a, b) {
                    return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                });
            }});
            $('select.swal2-select, span.select2').addClass('input-lg');
            $('#swal2-content').css('padding-bottom','15px');
        });

        //เรียกคิว hold
        $('#tb-hold tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
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
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        //End คิว hold
        $('#tb-hold tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
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
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        $('#tb-hold tbody').on( 'click', 'tr td a.btn-transfer', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbhold.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbhold.row( tr ).data();
            swal({
                title: 'ส่งคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                input: 'select',
                type: 'question',
                inputOptions: select2Data,
                inputPlaceholder: 'เลือกห้องตรวจ',
                inputValue: data.counter_service_id || '',
                inputClass: 'form-control m-b',
                showCancelButton: true,
                confirmButtonText: 'ส่งคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                    return 'คุณไม่ได้เลือกห้องตรวจ!'
                    }
                },
                preConfirm: function(value) {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/transfer-examination-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                                value:value //select value
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    self.toastrSuccess('Transfer ' + data.qnumber);
                                    socket.emit('transfer-examination-room', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
            jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th",sorter: function(data) {
                return data.sort(function(a, b) {
                    return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                });
            }});
            $('select.swal2-select, span.select2').addClass('input-lg');
            $('#swal2-content').css('padding-bottom','15px');
        });

        //End คิว
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
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
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
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
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });
        
        $('#tb-waiting tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbwaiting.row( tr ).data();
            swal({
                title: 'ยืนยัน End คิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>',
                input: 'select',
                type: 'question',
                inputOptions: select2Data,
                inputPlaceholder: 'เลือกห้องตรวจ',
                inputValue: data.counter_service_id || '',
                inputClass: 'form-control m-b',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'คุณไม่ได้เลือกห้องตรวจ!'
                    }
                },
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/end-wait-examination-room",
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
                                    self.toastrSuccess('END ' + data.qnumber);
                                    socket.emit('endq-examination-room', res);//sending data
                                    resolve();
                                }else{
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
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
        });
    },
    init: function(){
        var self = this;
        self.handleEventClick();
        Queue.reloadStatus();
    },
    reloadTbWaiting: function(){
        dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอ
        Queue.reloadStatus();
    },
    reloadTbCalling: function(){
        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียก
        Queue.reloadStatus();
    },
    reloadTbHold: function(){
        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
        Queue.reloadStatus();
    },
    toastrSuccess: function(msg){
        if(localStorage.getItem('disablealert-pagecalling') == 'on'){
            toastr.success(msg, 'Success!', {
                "timeOut": 3000,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "closeButton": true,
            });
        }
    },
    toastrWarning: function(title = 'Warning!',msg = ''){
        if(localStorage.getItem('disablealert-pagecalling') == 'on'){
            toastr.success(msg, title, {
                "timeOut": 5000,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "closeButton": true,
            });
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
            var counters = modelForm.counter_service;
            if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
                Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            }
            swal.close();
        }else if(res.eventOn === 'tb-hold' && res.state === 'call-hold'){//เรียกคิวจาก table พักคิว
            var counters = modelForm.counter_service;
            if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
                Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
            }
        }
    })
    .on('hold-examination-room', (res) => {//Hold คิวห้องตรวจ /kiosk/calling/examination-room
        var counters = modelForm.counter_service;
        if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
        }
    })
    .on('transfer-examination-room', (res) => {
        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
        Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
    })
    .on('endq-examination-room', (res) => {//จบ Process q /kiosk/calling/examination-room
        var counters = modelForm.counter_service;
        if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
        }
    }).on('display', (res) => {
        setTimeout(function(){
            dt_tbcalling.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                if(data.qnumber == res.title){
                    $('#tb-calling').find('tr.success').removeClass("success");
                    $("#last-queue,.last-queue").html(data.qnumber);
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
    var data = dt_tbwaiting.rows(0).data();
    if(data.length > 0){
        swal({
            title: 'ยืนยันเรียกคิว '+data[0].qnumber+' ?',
            html: '<p><i class="fa fa-user"></i> '+data[0].pt_name+'</p>',
            input: 'select',
            type: 'question',
            inputOptions: select2Data,
            inputPlaceholder: 'เลือกห้องตรวจ',
            inputValue: data[0].counter_service_id || '',
            inputClass: 'form-control m-b',
            showCancelButton: true,
            confirmButtonText: 'เรียกคิว',
            cancelButtonText: 'ยกเลิก',
            allowOutsideClick: false,
            inputValidator: (value) => {
                if (!value) {
                    return 'คุณไม่ได้เลือกห้องตรวจ!'
                }
            },
            preConfirm: (value) => {
                return new Promise((resolve) => {
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
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve, reject) {
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
                                        resolve();
                                    }else{
                                        Queue.ajaxAlertWarning();
                                    }
                                },
                                error:function(jqXHR, textStatus, errorThrown){
                                    Queue.ajaxAlertError(errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) {//Confirm
                        swal.close();
                    }
                });
            }else if(qcall.tbkey === 'tbhold'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve, reject) {
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
                                        resolve();
                                    }else{//error
                                        Queue.ajaxAlertWarning();
                                    }
                                },
                                error:function(jqXHR, textStatus, errorThrown){
                                    Queue.ajaxAlertError(errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) {//Confirm
                        swal.close();
                    }
                });
            }else if(qcall.tbkey === 'tbwaiting'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+qcall.data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: qcall.data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                    if (!value) {
                        return 'คุณไม่ได้เลือกห้องตรวจ!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
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

$('#fullscreen-toggler')
    .on('click', function (e) {
        setFullScreen();
    });

function setFullScreen(){
    var element = document.documentElement;
    if (!$('body')
        .hasClass("full-screen")) {

        $('body')
            .addClass("full-screen");
        $('#fullscreen-toggler').addClass("active");
        localStorage.setItem('medical-fullscreen','true');
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }

    } else {

        $('body')
            .removeClass("full-screen");
        $('#fullscreen-toggler')
            .removeClass("active");
        localStorage.setItem('medical-fullscreen','false');
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }

    }
}

function setTabletmode(){
    var hpanel = $('div.panel-form');
    var icon = $('div.panel-form').find('i:first');
    var body = hpanel.find('div.panel-body');
    var footer = hpanel.find('div.panel-footer');

    if(localStorage.getItem('examination-tablet-mode') == 'true'){

        body.slideToggle(300);
        footer.slideToggle(200);
        // Toggle icon from up to down
        icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        hpanel.toggleClass('').toggleClass('panel-collapse');
        setTimeout(function () {
            hpanel.resize();
            hpanel.find('[id^=map-]').resize();
        }, 50);
        var profilename = $('#callingform-service_profile').select2('data')[0]['text'] || '';
        var counternames = [];
        $('#callingform-counter_service input[type="checkbox"]').each(function( index, value ) {
            var el = $(this);
            if (el.is(':checked')){
                counternames.push(el.closest('label').text());
            } 
        });
        $('div.panel-form .panel-heading-text').html(' | ' + profilename + ': ' + counternames.join(" , "));
        $('#tablet-mode').prop("checked", true);
        $('#tab-menu-default,#tab-menu-default1,.small-header').css("display","none");
        $('.footer-tabs,.call-next-tablet-mode,.text-tablet-mode').css("display","");
        $('#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body').css("border-top","1px solid #e4e5e7");
    }else{
        if(hpanel.hasClass('panel-collapse')){
            body.slideToggle(300);
            footer.slideToggle(200);
            // Toggle icon from up to down
            icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
            hpanel.toggleClass('').toggleClass('panel-collapse');
            setTimeout(function () {
                hpanel.resize();
                hpanel.find('[id^=map-]').resize();
            }, 50);
        }
        $('div.panel-form .panel-heading-text').html('&nbsp;');
        $('.footer-tabs,.call-next-tablet-mode,.text-tablet-mode').css("display","none");
        $('#tab-menu-default,#tab-menu-default1,.small-header').css("display","");
        $('#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body').css("border-top","0");
    }
}

$(document).ready(function() {
    $('#tablet-mode').on('click',function(){
        if($(this).is(':checked')){
            localStorage.setItem('examination-tablet-mode','true');
        }else{
            localStorage.setItem('examination-tablet-mode','false');
        }
        setTabletmode();
    });
} );
var host = 'http://' + window.location.hostname + ':3001';
const socket = io(host)
socket.on('connect',()=>{
    socket.on('message_exam',(msg)=> {
        Queue.reloadTbWaiting();
    })
})

socket.on('connect',()=>{
    socket.on('message_drug',(msg)=> {
        Queue.reloadTbWaiting();
    })
})

setTabletmode();
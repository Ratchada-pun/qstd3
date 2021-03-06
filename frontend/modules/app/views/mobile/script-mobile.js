//Calling
$(document).ready(function () {
    setInterval(function () {
        Queue.reloadalertime()
    }, 1000)
})
Queue = {
    setDataSession: function () {
        var $form = $('#calling-form')
        var data = $form.serialize()
        $.ajax({
            method: 'POST',
            url: '/app/mobile/set-counter-session',
            data: data,
            dataType: 'json',
            beforeSend: function (jqXHR, settings) {
                swal({
                    title: 'Loading...',
                    text: '',
                    onOpen: () => {
                        swal.showLoading()
                    },
                }).then((result) => {})
            },
            success: function (res) {
                swal.close()
                location.reload()
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Queue.ajaxAlertError(errorThrown)
            },
        })
    },
    reloadStatus: function () {
        var _counter = $.ajax({
            method: 'POST',
            async: false,
            url: baseUrl + '/app/mobile/find-status',
            dataType: 'json',
            success: function (data) {
                $('#statusall').empty()
                $('#statusall').append(data[0].qnumber)
            },
        })
    },
    handleEventClick: function () {
        var self = this
        $('.btn_call').on('click', function (even) {
            var callvalue = even.currentTarget.attributes.id.value
            // console.log(callvalue)

            _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/mobile/find-ids',
                dataType: 'json',
                data: {
                    counterid: callvalue,
                },
                success: function (data) {
                    data
                },
            })
            data = _data['responseJSON'][0]
            // console.log(data)
            console.log(data)
            swal({
                title: '?????????????????????????????????????????? ' + data.qnumber + ' ?',
                html: '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: '????????????????????????',
                cancelButtonText: '??????????????????',
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        var url
                        var status
                        if (data['q_status_id'] == '1') {
                            status = '??????????????? '
                            url = baseUrl + '/app/mobile/call-examination-room'
                        } else {
                            status = '???????????????????????? '
                            url =
                                baseUrl + '/app/mobile/recall-examination-room'
                        }
                        $.ajax({
                            method: 'POST',
                            url: url,
                            dataType: 'json',
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                                value: data['counter_service_id'],
                            },
                            success: function (res) {
                                // console.log(res)
                                if (res.status == 200) {
                                    // self.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                    // self.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                                    self.toastrSuccess(status + data.qnumber)
                                    // $('#c1_recall,#c1_hold,#c1_jobstart').removeAttr('disabled')
                                    // $('#'+ callvalue).attr('disabled', true)
                                    socket.emit('call-examination-room', res)

                                    self.reloadList() //sending data
                                    resolve()
                                } else {
                                    // self.ajaxAlertWarning()
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                self.ajaxAlertError(errorThrown)
                            },
                        })
                    })
                },
            })
        })

        $('.btn_recall').on('click', function (even) {
            var callvalue = even.currentTarget.attributes.id.value

            _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/mobile/find-ids',
                dataType: 'json',
                data: {
                    counterid: callvalue,
                },
                success: function (data) {
                    data
                },
            })
            data = _data['responseJSON'][0]
            try {
            } catch (error) {}
            swal({
                title: '??????????????????????????????????????????????????? ' + data.qnumber + ' ?',
                html: '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: '?????????????????????????????????',
                cancelButtonText: '??????????????????',
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: 'POST',
                            url:
                                baseUrl + '/app/mobile/recall-examination-room',
                            dataType: 'json',
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function (res) {
                                if (res.status == 200) {
                                    // self.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                    // self.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                                    self.toastrSuccess(
                                        '???????????????????????? ' + data.qnumber
                                    )
                                    // $('#c1_recall,#c1_hold,#c1_jobstart').removeAttr('disabled')
                                    //   $(`#${callvalue}`).attr('disabled', true)
                                    socket.emit('call-examination-room', res) //sending data
                                    resolve()
                                } else {
                                    // self.ajaxAlertWarning()
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal({
                                    title: '??????????????????????????????????????????????????????????????????',
                                    confirmButtonText: '????????????',
                                })
                            },
                        })
                    })
                },
            })
        })

        $('.btn_hold').on('click', function (even) {
            var callvalue = even.currentTarget.attributes.id.value

            _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/mobile/find-ids',
                dataType: 'json',
                data: {
                    counterid: callvalue,
                },
                success: function (data) {
                    data
                },
            })
            data = _data['responseJSON'][0]

            swal({
                title: '?????????????????????????????????????????????' + data.qnumber + ' ?',
                html: '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: '??????????????????',
                cancelButtonText: '??????????????????',
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: 'POST',
                            url: baseUrl + '/app/mobile/hold-examination-room',
                            dataType: 'json',
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function (res) {
                                if (res.status == 200) {
                                    self.toastrSuccess('?????????????????? ' + data.qnumber)

                                    // self.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                    // $('#c1_recall,#c1_hold,#c1_jobstart').removeAttr('disabled')
                                    //   $(`#${callvalue}`).attr('disabled', true)
                                    socket.emit('hold-examination-room', res)
                                    self.reloadList()
                                    resolve()
                                } else {
                                    // self.ajaxAlertWarning()
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal({
                                    title: '??????????????????????????????????????????????????????????????????',
                                    confirmButtonText: '????????????',
                                })
                            },
                        })
                    })
                },
            })
        })

        $('.btn_end').on('click', function (even) {
            var callvalue = even.currentTarget.attributes.id.value

            _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/mobile/find-ids',
                dataType: 'json',
                data: {
                    counterid: callvalue,
                },
                success: function (data) {
                    data
                },
            })
            data = _data['responseJSON'][0]
            if ((callvalue = callvalue + "'")) {
                swal({
                    title: '??????????????????????????????????????????????????????????????????',
                    showCancelButton: true,
                    confirmButtonText: '???????????????????????????',
                    cancelButtonText: '??????????????????',
                })
            }
            swal({
                title: '??????????????????????????????????????????????????????' + data.qnumber + ' ?',
                html: '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: '???????????????????????????',
                cancelButtonText: '??????????????????',
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: 'POST',
                            url: baseUrl + '/app/mobile/end-examination-room',
                            dataType: 'json',
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function (res) {
                                if (res.status == 200) {
                                    self.toastrSuccess(
                                        '???????????????????????? ' + data.qnumber
                                    )
                                    self.reloadList()
                                    self.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                    // $('#c1_recall,#c1_hold,#c1_jobstart').removeAttr('disabled')
                                    //   $(`#${callvalue}`).attr('disabled', true)
                                    socket.emit('endq-examination-room', res)
                                    resolve()
                                } else {
                                    // self.ajaxAlertWarning()
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal({
                                    title: '??????????????????????????????????????????????????????????????????',
                                    confirmButtonText: '????????????',
                                })
                            },
                        })
                    })
                },
            })
        })

        $('.btn_callhold').on('click', function (even) {
            var callvalue = even.currentTarget.attributes.id.value

            _data = $.ajax({
                method: 'POST',
                async: false,
                url: baseUrl + '/app/mobile/find-ids',
                dataType: 'json',
                data: {
                    counterid: callvalue,
                },
                success: function (data) {
                    data
                },
            })
            data = _data['responseJSON'][0]
            if ((callvalue = callvalue + "'")) {
                swal({
                    title: '??????????????????????????????????????????????????????????????????',
                    showCancelButton: true,
                    confirmButtonText: '???????????????????????????',
                    cancelButtonText: '??????????????????',
                })
            }
            swal({
                title: '???????????????????????????????????????????????????' + data.qnumber + ' ?',
                html: '<p><i class="fa fa-user"></i> ' + data.pt_name + '</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: '??????????????????',
                cancelButtonText: '??????????????????',
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: 'POST',
                            url:
                                baseUrl +
                                '/app/mobile/callhold-examination-room',
                            dataType: 'json',
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function (res) {
                                if (res.status == 200) {
                                    self.toastrSuccess('?????????????????? ' + data.qnumber)
                                    self.reloadList()
                                    // self.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                    // $('#c1_recall,#c1_hold,#c1_jobstart').removeAttr('disabled')
                                    //   $(`#${callvalue}`).attr('disabled', true)
                                    socket.emit('call-examination-room', res)
                                    resolve()
                                } else {
                                    // self.ajaxAlertWarning()
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal({
                                    title: '??????????????????????????????????????????????????????????????????',
                                    confirmButtonText: '????????????',
                                })
                            },
                        })
                    })
                },
            })
        })
    },

    init: function () {
        var self = this
        self.handleEventClick()
        Queue.reloadStatus()
    },
    reloadTbWaiting: function () {
        $.pjax.reload({ container: '#QueueList', async: false }) //?????????????????????????????????????????????
        Queue.reloadStatus()
    },
    reloadTbCalling: function () {
        $.pjax.reload({ container: '#QueueList', async: false }) //????????????????????????????????????????????????????????????
        Queue.reloadStatus()
    },
    reloadTbHold: function () {
        dt_tbhold.ajax.reload() //????????????????????????????????????????????????????????????
        Queue.reloadStatus()
    },
    reloadalertime: function () {
        $.pjax.reload({ container: '#alerttime', async: false })
    },
    reloadList: function () {
        $.pjax.reload({ container: '#QueueList', async: false })
        $.pjax.reload({ container: '#holdlist_pjax', async: false })
        $.pjax.reload({ container: '#endlist_pjax', async: false }) //???????????????????????????????????????????????????????????????????????????
        Queue.reloadStatus()
    },
    toastrSuccess: function (msg) {
        if (localStorage.getItem('disablealert-pagecalling') == 'on') {
            toastr.success(msg, 'Success!', {
                timeOut: 3000,
                positionClass: 'toast-top-right',
                progressBar: true,
                closeButton: true,
            })
        }
    },
    toastrWarning: function (title = 'Warning!', msg = '') {
        if (localStorage.getItem('disablealert-pagecalling') == 'on') {
            toastr.success(msg, title, {
                timeOut: 5000,
                positionClass: 'toast-top-right',
                progressBar: true,
                closeButton: true,
            })
        }
    },
    ajaxAlertError: function (msg) {
        swal({
            type: 'error',
            title: msg,
            showConfirmButton: false,
            timer: 1500,
        })
    },
    ajaxAlertWarning: function () {
        swal({
            type: 'error',
            title: '??????????????????????????????????????????!!',
            showConfirmButton: false,
            timer: 1500,
        })
    },
    checkCounter: function () {
        if (modelForm.service_profile == null) {
            var title = '???????????????????????????????????????????????????'
            swal({
                type: 'warning',
                title: title,
                showConfirmButton: false,
                timer: 1500,
            })
            return false
        } else {
            return true
        }
    },
}

//socket event
$(function () {
    socket
        .on('endq-screening-room', (res) => {
            var services = modelProfile.service_id.split(',')
            if (
                jQuery.inArray(res.modelQ.serviceid.toString(), services) != -1
            ) {
                //?????????????????????????????? service profile
                if (localStorage.getItem('playsound-pagecalling') == 'on') {
                    var player = $('#jplayer_notify').jPlayer({
                        ready: function () {
                            $(this)
                                .jPlayer('setMedia', {
                                    mp3: '/media/alert.mp3',
                                })
                                .jPlayer('play')
                        },
                        supplied: 'mp3',
                        ended: function () {
                            // The $.jPlayer.event.ended event
                            $(this).jPlayer('stop') // Repeat the media
                        },
                    })
                    $('#jplayer_notify').jPlayer('play')
                }
                Queue.reloadTbWaiting() //???????????????????????????????????????????????????
                Queue.toastrWarning('????????????????????????????????????????????????????????????!', res.modelQ.pt_name)
            }
        })
        .on('call-examination-room', (res) => {
            //??????????????????????????????
            if (res.eventOn === 'tb-waiting' && res.state === 'call') {
                //????????????????????????????????? table ????????????????????????????????????
                Queue.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                var counters = modelForm.counter_service
                if (
                    jQuery.inArray(
                        res.counter.counterserviceid.toString(),
                        counters
                    ) != -1
                ) {
                    Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                }
                swal.close()
            } else if (res.eventOn === 'tb-hold' && res.state === 'call-hold') {
                //????????????????????????????????? table ??????????????????
                var counters = modelForm.counter_service
                if (
                    jQuery.inArray(
                        res.counter.counterserviceid.toString(),
                        counters
                    ) != -1
                ) {
                    Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                    Queue.reloadTbHold() //????????????????????????????????????????????????????????????
                }
            }
        })
        .on('hold-examination-room', (res) => {
            //Hold ????????????????????????????????? /kiosk/calling/examination-room
            var counters = modelForm.counter_service
            if (
                jQuery.inArray(
                    res.counter.counterserviceid.toString(),
                    counters
                ) != -1
            ) {
                Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                Queue.reloadTbHold() //????????????????????????????????????????????????????????????
            }
        })
        .on('transfer-examination-room', (res) => {
            Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
            Queue.reloadTbHold() //????????????????????????????????????????????????????????????
        })
        .on('endq-examination-room', (res) => {
            //?????? Process q /kiosk/calling/examination-room
            var counters = modelForm.counter_service
            if (
                jQuery.inArray(
                    res.counter.counterserviceid.toString(),
                    counters
                ) != -1
            ) {
                Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
            }
        })
        .on('message_exam', (res) => {
            $.pjax.reload({ container: '#QueueList', async: false }) //???
        })
})

//CallNext
$('a.activity-callnext').on('click', function (e) {
    e.preventDefault()
    var data = dt_tbwaiting.rows(0).data()
    if (data.length > 0) {
        swal({
            title: '?????????????????????????????????????????? ' + data[0].qnumber + ' ?',
            html: '<p><i class="fa fa-user"></i> ' + data[0].pt_name + '</p>',
            input: 'select',
            type: 'question',
            inputOptions: select2Data,
            inputPlaceholder: '???????????????????????????????????????',
            inputValue: data[0].counter_service_id || '',
            inputClass: 'form-control m-b',
            showCancelButton: true,
            confirmButtonText: '????????????????????????',
            cancelButtonText: '??????????????????',
            allowOutsideClick: false,
            inputValidator: (value) => {
                if (!value) {
                    return '??????????????????????????????????????????????????????????????????!'
                }
            },
            preConfirm: (value) => {
                return new Promise((resolve) => {
                    $.ajax({
                        method: 'POST',
                        url: baseUrl + '/app/mobile/call-examination-room',
                        dataType: 'json',
                        data: {
                            data: data[0], //Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile,
                            value: value, //select value
                        },
                        success: function (res) {
                            if (res.status == 200) {
                                Queue.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                                Queue.toastrSuccess('CALL ' + data[0].qnumber)
                                socket.emit('call-examination-room', res) //sending data
                                resolve()
                            } else {
                                Queue.ajaxAlertWarning()
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            Queue.ajaxAlertError(errorThrown)
                        },
                    })
                })
            },
        })
        jQuery('.swal2-select').select2({
            allowClear: true,
            theme: 'bootstrap',
            width: '100%',
            placeholder: '???????????????????????????????????????...',
            language: 'th',
        })
        $('select.swal2-select, span.select2').addClass('input-lg')
        $('#swal2-content').css('padding-bottom', '15px')
    } else {
        swal({
            type: 'warning',
            title: '?????????????????????????????????????????????',
            showConfirmButton: false,
            timer: 1500,
        })
    }
})

var $form = $('#calling-form')
$form.on('beforeSubmit', function () {
    var dataObj = {}
    var qcall

    $form.serializeArray().map(function (field) {
        dataObj[field.name] = field.value
    })

    if (
        dataObj['CallingForm[qnum]'] != null &&
        dataObj['CallingForm[qnum]'] != ''
    ) {
        //????????????????????????????????????????????????
        dt_tbcalling.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var data = this.data()
            if (data.qnumber === dataObj['CallingForm[qnum]']) {
                qcall = { data: data, tbkey: 'tbcalling' }
            }
        })
        //??????????????????????????????????????????????????????
        dt_tbwaiting.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var data = this.data()
            if (data.qnumber === dataObj['CallingForm[qnum]']) {
                qcall = { data: data, tbkey: 'tbwaiting' }
            }
        })
        //????????????????????????????????????
        dt_tbhold.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var data = this.data()
            if (data.qnumber === dataObj['CallingForm[qnum]']) {
                qcall = { data: data, tbkey: 'tbhold' }
            }
        })

        if (qcall === undefined) {
            toastr.error(dataObj['CallingForm[qnum]'], {
                timeOut: 3000,
                positionClass: 'toast-top-right',
            })
        } else {
            if (qcall.tbkey === 'tbcalling') {
                swal({
                    title: '?????????????????????????????????????????? ' + qcall.data.qnumber + ' ?',
                    text: qcall.data.pt_name,
                    html:
                        '<small class="text-danger" style="font-size: 13px;">?????? Enter ????????????????????????????????? / ?????? Esc ?????????????????????????????????</small>' +
                        '<p><i class="fa fa-user"></i>' +
                        qcall.data.pt_name +
                        '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: '????????????????????????',
                    cancelButtonText: '??????????????????',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: 'POST',
                                url:
                                    baseUrl +
                                    '/app/mobile/recall-examination-room',
                                dataType: 'json',
                                data: {
                                    data: qcall.data, //Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile,
                                },
                                success: function (res) {
                                    if (res.status == 200) {
                                        Queue.toastrSuccess(
                                            'RECALL ' + qcall.data.qnumber
                                        )
                                        socket.emit(
                                            'call-examination-room',
                                            res
                                        ) //sending data
                                        resolve()
                                    } else {
                                        Queue.ajaxAlertWarning()
                                    }
                                },
                                error: function (
                                    jqXHR,
                                    textStatus,
                                    errorThrown
                                ) {
                                    Queue.ajaxAlertError(errorThrown)
                                },
                            })
                        })
                    },
                }).then((result) => {
                    if (result.value) {
                        //Confirm
                        swal.close()
                    }
                })
            } else if (qcall.tbkey === 'tbhold') {
                swal({
                    title: '?????????????????????????????????????????? ' + qcall.data.qnumber + ' ?',
                    text: qcall.data.pt_name,
                    html:
                        '<small class="text-danger" style="font-size: 13px;">?????? Enter ????????????????????????????????? / ?????? Esc ?????????????????????????????????</small>' +
                        '<p><i class="fa fa-user"></i>' +
                        qcall.data.pt_name +
                        '</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: '????????????????????????',
                    cancelButtonText: '??????????????????',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function () {
                        return new Promise(function (resolve, reject) {
                            $.ajax({
                                method: 'POST',
                                url:
                                    baseUrl +
                                    '/app/mobile/callhold-examination-room',
                                dataType: 'json',
                                data: {
                                    data: qcall.data, //Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile,
                                },
                                success: function (res) {
                                    if (res.status == 200) {
                                        //success
                                        Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                                        Queue.reloadTbHold() //????????????????????????????????????????????????????????????
                                        Queue.toastrSuccess(
                                            'CALL ' + qcall.data.qnumber
                                        )
                                        socket.emit(
                                            'call-examination-room',
                                            res
                                        ) //sending data
                                        resolve()
                                    } else {
                                        //error
                                        Queue.ajaxAlertWarning()
                                    }
                                },
                                error: function (
                                    jqXHR,
                                    textStatus,
                                    errorThrown
                                ) {
                                    Queue.ajaxAlertError(errorThrown)
                                },
                            })
                        })
                    },
                }).then((result) => {
                    if (result.value) {
                        //Confirm
                        swal.close()
                    }
                })
            } else if (qcall.tbkey === 'tbwaiting') {
                swal({
                    title: '?????????????????????????????????????????? ' + qcall.data.qnumber + ' ?',
                    html:
                        '<p><i class="fa fa-user"></i> ' +
                        qcall.data.pt_name +
                        '</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: '???????????????????????????????????????',
                    inputValue: qcall.data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: '????????????????????????',
                    cancelButtonText: '??????????????????',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return '??????????????????????????????????????????????????????????????????!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
                            $.ajax({
                                method: 'POST',
                                url:
                                    baseUrl +
                                    '/app/mobile/call-examination-room',
                                dataType: 'json',
                                data: {
                                    data: qcall.data, //Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile,
                                    value: value, //select value
                                },
                                success: function (res) {
                                    if (res.status == 200) {
                                        Queue.reloadTbWaiting() //??????????????????????????????????????????????????????????????????????????????
                                        Queue.reloadTbCalling() //????????????????????????????????????????????????????????????????????????
                                        Queue.toastrSuccess(
                                            'CALL ' + qcall.data.qnumber
                                        )
                                        socket.emit(
                                            'call-examination-room',
                                            res
                                        ) //sending data
                                        resolve()
                                    } else {
                                        Queue.ajaxAlertWarning()
                                    }
                                },
                                error: function (
                                    jqXHR,
                                    textStatus,
                                    errorThrown
                                ) {
                                    Queue.ajaxAlertError(errorThrown)
                                },
                            })
                        })
                    },
                })
                jQuery('.swal2-select').select2({
                    allowClear: true,
                    theme: 'bootstrap',
                    width: '100%',
                    placeholder: '???????????????????????????????????????...',
                    language: 'th',
                })
                $('select.swal2-select, span.select2').addClass('input-lg')
                $('#swal2-content').css('padding-bottom', '15px')
            }
        }
    } else {
        // toastr.error(dataObj['CallingForm[qnum]'], {
        //     timeOut: 3000,
        //     positionClass: 'toast-top-right',
        // })
    }
    $('input#callingform-qnum').val(null) //clear data
    return false
})

Queue.init()

$('#fullscreen-toggler').on('click', function (e) {
    setFullScreen()
})

function setFullScreen() {
    var element = document.documentElement
    if (!$('body').hasClass('full-screen')) {
        $('body').addClass('full-screen')
        $('#fullscreen-toggler').addClass('active')
        localStorage.setItem('medical-fullscreen', 'true')
        if (element.requestFullscreen) {
            element.requestFullscreen()
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen()
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen()
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen()
        }
    } else {
        $('body').removeClass('full-screen')
        $('#fullscreen-toggler').removeClass('active')
        localStorage.setItem('medical-fullscreen', 'false')
        if (document.exitFullscreen) {
            document.exitFullscreen()
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen()
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen()
        }
    }
}

function setTabletmode() {
    var hpanel = $('div.panel-form')
    var icon = $('div.panel-form').find('i:first')
    var body = hpanel.find('div.panel-body')
    var footer = hpanel.find('div.panel-footer')

    if (localStorage.getItem('examination-tablet-mode') == 'true') {
        body.slideToggle(300)
        footer.slideToggle(200)
        // Toggle icon from up to down
        icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down')
        hpanel.toggleClass('').toggleClass('panel-collapse')
        setTimeout(function () {
            hpanel.resize()
            hpanel.find('[id^=map-]').resize()
        }, 50)
        var profilename =
            $('#callingform-service_profile').select2('data')[0]['text'] || ''
        var counternames = []
        $('#callingform-counter_service input[type="checkbox"]').each(function (
            index,
            value
        ) {
            var el = $(this)
            if (el.is(':checked')) {
                counternames.push(el.closest('label').text())
            }
        })
        $('div.panel-form .panel-heading-text').html(
            ' | ' + profilename + ': ' + counternames.join(' , ')
        )
        $('#tablet-mode').prop('checked', true)
        $('#tab-menu-default,#tab-menu-default1,.small-header').css(
            'display',
            'none'
        )
        $('.footer-tabs,.call-next-tablet-mode,.text-tablet-mode').css(
            'display',
            ''
        )
        $(
            '#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body'
        ).css('border-top', '1px solid #e4e5e7')
    } else {
        if (hpanel.hasClass('panel-collapse')) {
            body.slideToggle(300)
            footer.slideToggle(200)
            // Toggle icon from up to down
            icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down')
            hpanel.toggleClass('').toggleClass('panel-collapse')
            setTimeout(function () {
                hpanel.resize()
                hpanel.find('[id^=map-]').resize()
            }, 50)
        }
        $('div.panel-form .panel-heading-text').html('&nbsp;')
        $('.footer-tabs,.call-next-tablet-mode,.text-tablet-mode').css(
            'display',
            'none'
        )
        $('#tab-menu-default,#tab-menu-default1,.small-header').css(
            'display',
            ''
        )
        $(
            '#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body'
        ).css('border-top', '0')
    }
}

$(document).ready(function () {
    $('#tablet-mode').on('click', function () {
        if ($(this).is(':checked')) {
            localStorage.setItem('examination-tablet-mode', 'true')
        } else {
            localStorage.setItem('examination-tablet-mode', 'false')
        }
        setTabletmode()
    })
})
// var host = 'http://' + window.location.hostname + ':3001';
// const socket = io(host)
// socket.on('connect',()=>{
//     socket.on('message_exam',(msg)=> {
//         Queue.reloadTbWaiting();
//     })
// })

setTabletmode()

//Recall

//hold
// $('.btn_hold').on('click', function (even) {
//     var holdvalue = $('.btn,.btn-info,.btn_hold').attr('id')
//     console.log(holdvalue)
// })
// //Finish
// $('.btn_end').on('click', function (even) {
//     var endvalue = $('.btn,.btn-info,.btn_end').attr('id')
//     console.log(endvalue)
// })

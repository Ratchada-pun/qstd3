//$('input[type="search"]').removeClass('input-sm').addClass('input-lg');
$('input[type="search"]').focus(function() {
  //animate
  $(this).animate(
    {
      width: "250px",
    },
    400
  );
});

$('input[type="search"]').blur(function() {
  $(this).animate(
    {
      width: "160px",
    },
    500
  );
});
// Toastr options
toastr.options = {
  debug: false,
  newestOnTop: false,
  positionClass: "toast-top-center",
  closeButton: true,
  toastClass: "animated fadeInDown",
};

Queue = {
  handleEventClick: function() {
    var self = this;
    //เรียกคิว
    $("#tb-waiting tbody").on("click", "tr td a.btn-calling", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbwaiting.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbwaiting.row(tr).data();
      var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
      if (self.checkCounter()) {
        swal({
          title: "ยืนยันเรียกคิว " + data.qnumber + " ?",
          text: data.pt_name,
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            "<p>" +
            countername +
            "</p>",
          type: "question",
          input: "radio",
          // inputOptions: {
          //     0 : "ต้องการ END ทันที",
          //     1 : "ไม่ต้องการ END ทันที"
          // },
          // inputValue: 1,
          showCancelButton: true,
          confirmButtonText: "เรียกคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function(value) {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    $("li.tab-watting, #tab-watting").removeClass("active");
                    $("li.tab-calling, #tab-calling").addClass("active");
                    self.reloadTbWaiting(); //โหลดข้อมูลรอเรียก
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                    self.toastrSuccess("CALL " + data.qnumber);
                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                    socket.emit("call", res); //sending data
                    if (value == 0) {
                      setTimeout(function() {
                        var tr = $("#tb-calling tr#" + res.model.caller_ids),
                          url = "/app/calling/end-medical";
                        var key = tr.data("key");
                        var data = dt_tbcalling.row(tr).data();
                        $.ajax({
                          method: "POST",
                          url: baseUrl + url,
                          dataType: "json",
                          data: {
                            data: data, //Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile,
                          },
                          success: function(res) {
                            if (res.status == "200") {
                              self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                              self.toastrSuccess("END " + data.qnumber);
                              socket.emit("finish", res); //sending data
                              resolve();
                            } else {
                              self.ajaxAlertWarning();
                            }
                          },
                          error: function(jqXHR, textStatus, errorThrown) {
                            self.ajaxAlertError(errorThrown);
                          },
                        });
                      }, 500);
                    } else {
                      resolve();
                    }
                  } else {
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
          }
        });
      }
    });
    //End คิว hold
    $("#tb-waiting tbody").on("click", "tr td a.btn-end", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbwaiting.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbwaiting.row(tr).data();
      if (self.checkCounter()) {
        swal({
          title: "ยืนยัน END คิว " + data.qnumber + " ?",
          text: data.pt_name,
          html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>',
          type: "question",
          showCancelButton: true,
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    //success
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
                    self.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
                    self.toastrSuccess("END " + data.qnumber);
                    socket.emit("finish", res); //sending data
                    resolve();
                  } else {
                    //error
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });
    //เรียกคิวซ้ำ
    $("#tb-calling tbody").on("click", "tr td a.btn-recall", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbcalling.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbcalling.row(tr).data();
      var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
      if (self.checkCounter()) {
        swal({
          title: "ยืนยันเรียกคิว " + data.qnumber + " ?",
          text: data.pt_name,
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            "<p>" +
            countername +
            "</p>",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "เรียกคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    //Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียก
                    self.toastrSuccess("RECALL " + data.qnumber);
                    socket.emit("call", res); //sending data
                    resolve();
                  } else {
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });
    //พักคิว
    $("#tb-calling tbody").on("click", "tr td a.btn-hold", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbcalling.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbcalling.row(tr).data();
      if (self.checkCounter()) {
        swal({
          title: "ยืนยันพักคิว " + data.qnumber + " ?",
          text: data.pt_name,
          html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>',
          type: "question",
          showCancelButton: true,
          confirmButtonText: "พักคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    //success
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
                    self.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
                    self.toastrSuccess("HOLD " + data.qnumber);
                    socket.emit("hold", res); //sending data
                    resolve();
                  } else {
                    //error
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });

    //End คิว
    $("#tb-calling tbody").on("click", "tr td a.btn-end", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbcalling.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbcalling.row(tr).data();
      if (self.checkCounter()) {
        swal({
          title: "ยืนยัน END คิว " + data.qnumber + " ?",
          text: data.pt_name,
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            '<p><i class="fa fa-user"></i>' +
            data.pt_name +
            "</p>",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == "200") {
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                    self.toastrSuccess("END " + data.qnumber);
                    socket.emit("finish", res); //sending data
                    resolve();
                  } else {
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });

    //
    $("#tb-calling tbody").on("click", "tr td a.btn-waiting", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbcalling.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbcalling.row(tr).data();
      if (self.checkCounter()) {
        swal({
          title: "ยืนยันส่งห้องแพทย์ คิว " + data.qnumber + " ?",
          text: data.pt_name,
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            '<p><i class="fa fa-user"></i>' +
            data.pt_name +
            "</p>",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == "200") {
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                    self.toastrSuccess("END " + data.qnumber);
                    socket.emit("finish", res); //sending data
                    resolve();
                  } else {
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });

    //เรียกคิว hold
    $("#tb-hold tbody").on("click", "tr td a.btn-calling", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbhold.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbhold.row(tr).data();
      var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
      if (self.checkCounter()) {
        swal({
          title: "ยืนยันเรียกคิว " + data.qnumber + " ?",
          text: data.pt_name,
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            "<p>" +
            countername +
            "</p>",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "เรียกคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    //success
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                    self.reloadTbHold(); //โหลดข้อมูลพักคิว
                    self.toastrSuccess("CALL " + data.qnumber);
                    socket.emit("call", res); //sending data
                    resolve();
                  } else {
                    //error
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });

    //End คิว hold
    $("#tb-hold tbody").on("click", "tr td a.btn-end", function(event) {
      event.preventDefault();
      var tr = $(this).closest("tr"),
        url = $(this).attr("href");
      if (tr.hasClass("child") && typeof dt_tbhold.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = dt_tbhold.row(tr).data();
      if (self.checkCounter()) {
        swal({
          title: "ยืนยัน END คิว " + data.qnumber + " ?",
          text: data.pt_name,
          html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>',
          type: "question",
          showCancelButton: true,
          confirmButtonText: "ยืนยัน",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + url,
                dataType: "json",
                data: {
                  data: data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    //success
                    self.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
                    self.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
                    self.toastrSuccess("END " + data.qnumber);
                    socket.emit("finish", res); //sending data
                    resolve();
                  } else {
                    //error
                    self.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  self.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    });

    $("#tb-patients-ist tbody").on("click", "tr td a", function(event) {
      event.preventDefault();
      var table = $("#tb-patients-ist").DataTable();
      var tr = $(this).closest("tr"),
        serviceid = $(this).attr("data-key"),
        groupid = $(this).attr("data-group");
      if (tr.hasClass("child") && typeof table.row(tr).data() === "undefined") {
        tr = $(this)
          .closest("tr")
          .prev();
      }
      var key = tr.data("key");
      var data = table.row(tr).data();
      var txt = $(this).text();
      swal({
        title: "ยืนยัน?",
        text: data.fullname,
        html:
          '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
          '<p><i class="fa fa-user"></i>' +
          data.fullname +
          "</p>" +
          '<p><i class="fa fa-angle-double-down"></i></p><p>' +
          txt +
          "</p>",
        type: "question",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        showLoaderOnConfirm: true,
        preConfirm: function() {
          return new Promise(function(resolve) {
            $.ajax({
              url: baseUrl + "/app/kiosk/register",
              type: "POST",
              data: $.extend(data, { groupid: groupid, serviceid: serviceid }),
              dataType: "JSON",
              success: function(res) {
                if (res.status == "200") {
                  toastr.success(res.modelQ.pt_name, "Printing #" + res.modelQ.q_num, {
                    timeOut: 3000,
                    positionClass: "toast-top-right",
                  });
                  window.open(res.url, "myPrint", "width=800, height=600");
                  table.ajax.reload();
                  dt_tbqdata.ajax.reload();
                  socket.emit("register", res); //sending data
                  resolve();
                } else {
                  swal("Oops...", "เกิดข้อผิดพลาด!", "error");
                }
              },
              error: function(jqXHR, errMsg) {
                swal("Oops...", errMsg, "error");
              },
            });
          });
        },
      }).then((result) => {
        if (result.value) {
          swal.close();
        }
      });
    });
  },
  init: function() {
    var self = this;
    self.handleEventClick();
  },
  reloadTbWaiting: function() {
    dt_tbwaiting.ajax.reload(); //โหลดข้อมูลคิวรอ
  },
  reloadTbCalling: function() {
    dt_tbcalling.ajax.reload(); //โหลดข้อมูลกำลังเรียก
  },
  reloadTbHold: function() {
    dt_tbhold.ajax.reload(); //โหลดข้อมูลพักคิวใหม่
  },
  toastrSuccess: function(msg = "") {
    if (localStorage.getItem("disablealert-pagecalling") == "on") {
      toastr.success(msg, "Success!", {
        timeOut: 3000,
        positionClass: "toast-top-right",
        progressBar: true,
        closeButton: true,
      });
    }
  },
  toastrWarning: function(title = "Warning!", msg = "") {
    if (localStorage.getItem("disablealert-pagecalling") == "on") {
      toastr.success(msg, title, {
        timeOut: 5000,
        positionClass: "toast-top-right",
        progressBar: true,
        closeButton: true,
      });
    }
  },
  ajaxAlertError: function(msg) {
    swal({
      type: "error",
      title: msg,
      showConfirmButton: false,
      timer: 1500,
    });
  },
  ajaxAlertWarning: function() {
    swal({
      type: "error",
      title: "เกิดข้อผิดพลาด!!",
      showConfirmButton: false,
      timer: 1500,
    });
  },
  checkCounter: function() {
    if (modelForm.counter_service == null || modelForm.service_profile == null) {
      var title = modelForm.service_profile == null ? "กรุณาเลือกโปรไฟล์" : "กรุณาเลือกช่องบริการ";
      swal({
        type: "warning",
        title: title,
        showConfirmButton: false,
        timer: 1500,
      });
      return false;
    } else {
      return true;
    }
  },
};

//Socket Events
$(function() {
  socket
    .on("register", (res) => {
      //ออกบัตรคิว
      var services = modelProfile.service_id.split(",");
      dt_tbqdata.ajax.reload();
      if (jQuery.inArray(res.modelQ.serviceid, services) != -1) {
        //ถ้าคิวมีใน service profile
        if (localStorage.getItem("playsound-pagecalling") == "on") {
          var player = $("#jplayer_notify").jPlayer({
            ready: function() {
              $(this)
                .jPlayer("setMedia", {
                  mp3: "/media/alert.mp3",
                })
                .jPlayer("play");
            },
            supplied: "mp3",
            ended: function() {
              // The $.jPlayer.event.ended event
              $(this).jPlayer("stop"); // Repeat the media
            },
          });
          $("#jplayer_notify").jPlayer("play");
        }

        Queue.reloadTbWaiting(); //โหลดข้อมูลรอเรียก
        toastr.warning(res.modelQ.q_num, "คิวใหม่!", {
          timeOut: 7000,
          positionClass: "toast-top-right",
          progressBar: true,
          closeButton: true,
        });
      }
    })
    .on("call", (res) => {
      var t1 = $("#tb-waiting").DataTable();
      var t2 = $("#tb-calling").DataTable();
      var t3 = $("#tb-hold").DataTable();

      t1.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.q_ids) === parseInt(_.get(res, "modelQueue.q_ids")) ||
          parseInt(data.q_ids) === parseInt(_.get(res, "modelQ.q_ids"))
        ) {
          Queue.reloadTbWaiting(); //โหลดข้อมูลคิวรอ
          dt_tbqdata.ajax.reload();
        }
      });
      t2.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.caller_ids) === parseInt(_.get(res, "modelCaller.caller_ids")) ||
          parseInt(data.caller_ids) === parseInt(_.get(res, "model.caller_ids"))
        ) {
          Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
          dt_tbqdata.ajax.reload();
        }
      });
      t3.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.caller_ids) === parseInt(_.get(res, "modelCaller.caller_ids")) ||
          parseInt(data.caller_ids) === parseInt(_.get(res, "model.caller_ids"))
        ) {
          Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
          Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
          dt_tbqdata.ajax.reload();
        }
      });
      // if (res.eventOn === "tb-waiting" && res.state === "call") {
      //   Queue.reloadTbWaiting(); //โหลดข้อมูลคิวรอ
      //   if (
      //     res.modelProfile.service_profile_id == modelProfile.service_profile_id &&
      //     modelForm.counter_service == res.counter.counterserviceid.toString()
      //   ) {
      //     Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
      //   }
      //   swal.close();
      // } else if (res.eventOn === "tb-hold" && res.state === "call-hold") {
      //   if (
      //     res.modelProfile.service_profile_id == modelProfile.service_profile_id &&
      //     modelForm.counter_service == res.counter.counterserviceid.toString()
      //   ) {
      //     Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
      //     Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
      //   }
      // }
    })
    .on("hold", (res) => {
      var t2 = $("#tb-calling").DataTable();
      var t3 = $("#tb-hold").DataTable();
      t2.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.caller_ids) === parseInt(_.get(res, "modelCaller.caller_ids")) ||
          parseInt(data.caller_ids) === parseInt(_.get(res, "model.caller_ids"))
        ) {
          Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
          Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
          dt_tbqdata.ajax.reload();
        }
      });
      t3.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.caller_ids) === parseInt(_.get(res, "modelCaller.caller_ids")) ||
          parseInt(data.caller_ids) === parseInt(_.get(res, "model.caller_ids"))
        ) {
          Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
          Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
          dt_tbqdata.ajax.reload();
        }
      });
      // if (
      //   res.modelProfile.service_profile_id == modelProfile.service_profile_id &&
      //   modelForm.counter_service == res.counter.counterserviceid.toString()
      // ) {
      //   Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
      //   Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
      // }
    })
    .on("finish", (res) => {
      var t2 = $("#tb-calling").DataTable();
      var t3 = $("#tb-hold").DataTable();
      t2.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.caller_ids) === parseInt(_.get(res, "modelCaller.caller_ids")) ||
          parseInt(data.caller_ids) === parseInt(_.get(res, "model.caller_ids"))
        ) {
          Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
          Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
          dt_tbqdata.ajax.reload();
        }
      });
      t3.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        if (
          parseInt(data.caller_ids) === parseInt(_.get(res, "modelCaller.caller_ids")) ||
          parseInt(data.caller_ids) === parseInt(_.get(res, "model.caller_ids"))
        ) {
          Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
          Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
          dt_tbqdata.ajax.reload();
        }
      });
      // if (
      //   res.modelProfile.service_profile_id == modelProfile.service_profile_id &&
      //   modelForm.counter_service == res.counter.counterserviceid.toString()
      // ) {
      //   Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
      //   Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
      // }
    })
    .on("display", (res) => {
      setTimeout(function() {
        dt_tbcalling.rows().every(function(rowIdx, tableLoop, rowLoop) {
          var data = this.data();
          if (data.qnumber == res.title) {
            $("#tb-calling")
              .find("tr.success")
              .removeClass("success");
            $("#last-queue").html(data.qnumber);
            dt_tbcalling.$("tr#" + res.artist.data.DT_RowId).addClass("success");
            Queue.toastrWarning("", '<i class="pe-7s-speaker"></i> กำลังเรียกคิว #' + data.qnumber);
          }
        });
      }, 500);
    });
  //set draggable
  /* $( window ).resize(function() {
        if($(window).width() > 992){
            $('.call-next').draggable();
        }
    });
    if($(window).width() > 992){
        $('.call-next').draggable();
    } */
  //แปลงเป็นตัวอักษรตัวใหญ่
  $("#callingform-qnum").keyup(function() {
    this.value = this.value.toUpperCase();
  });
});

var $form = $("#calling-form");
$form.on("beforeSubmit", function() {
  var dataObj = {};
  var qcall;
  var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";

  $form.serializeArray().map(function(field) {
    dataObj[field.name] = field.value;
  });

  if (dataObj["CallingForm[qnum]"] != null && dataObj["CallingForm[qnum]"] != "") {
    //ข้อมูลกำลังเรียก
    dt_tbcalling.rows().every(function(rowIdx, tableLoop, rowLoop) {
      var data = this.data();
      if (data.qnumber === dataObj["CallingForm[qnum]"]) {
        qcall = { data: data, tbkey: "tbcalling" };
      }
    });
    //ข้อมูลรอเรียก
    dt_tbwaiting.rows().every(function(rowIdx, tableLoop, rowLoop) {
      var data = this.data();
      if (data.qnumber === dataObj["CallingForm[qnum]"]) {
        qcall = { data: data, tbkey: "tbwaiting" };
      }
    });
    //ข้อมูลพักคิว
    dt_tbhold.rows().every(function(rowIdx, tableLoop, rowLoop) {
      var data = this.data();
      if (data.qnumber === dataObj["CallingForm[qnum]"]) {
        qcall = { data: data, tbkey: "tbhold" };
      }
    });

    if (qcall === undefined) {
      toastr.error(dataObj["CallingForm[qnum]"], "ไม่พบข้อมูล!", { timeOut: 3000, positionClass: "toast-top-center" });
    } else {
      if (qcall.tbkey === "tbcalling") {
        swal({
          title: "ยืนยันเรียกคิว " + qcall.data.qnumber + " ?",
          text: "",
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            "<p>" +
            countername +
            "</p>",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "เรียกคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + "/app/calling/recall-screening-room",
                dataType: "json",
                data: {
                  data: qcall.data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    Queue.toastrSuccess("RECALL " + qcall.data.qnumber);
                    socket.emit("call", res); //sending data
                    resolve();
                  } else {
                    Queue.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  Queue.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      } else if (qcall.tbkey === "tbhold") {
        swal({
          title: "ยืนยันเรียกคิว " + qcall.data.qnumber + " ?",
          text: "",
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            "<p>" +
            countername +
            "</p>",
          type: "question",
          showCancelButton: true,
          confirmButtonText: "เรียกคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + "/app/calling/callhold-screening-room",
                dataType: "json",
                data: {
                  data: qcall.data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    //success
                    Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียกใหม่
                    Queue.reloadTbHold(); //โหลดข้อมูลพักคิวใหม่
                    Queue.toastrSuccess("CALL " + qcall.data.qnumber);
                    socket.emit("call", res); //sending data
                    resolve();
                  } else {
                    //error
                    Queue.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  Queue.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      } else if (qcall.tbkey === "tbwaiting") {
        swal({
          title: "ยืนยันเรียกคิว " + qcall.data.qnumber + " ?",
          text: "",
          html:
            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
            "<p>" +
            countername +
            "</p>",
          type: "question",
          input: "radio",
          inputOptions: {
            0: "ต้องการ END ทันที",
            1: "ไม่ต้องการ END ทันที",
          },
          inputValue: 1,
          showCancelButton: true,
          confirmButtonText: "เรียกคิว",
          cancelButtonText: "ยกเลิก",
          allowOutsideClick: false,
          showLoaderOnConfirm: true,
          preConfirm: function(value) {
            return new Promise(function(resolve, reject) {
              $.ajax({
                method: "POST",
                url: baseUrl + "/app/calling/call-screening-room",
                dataType: "json",
                data: {
                  data: qcall.data, //Data in column Datatable
                  modelForm: modelForm, //Data Model CallingForm
                  modelProfile: modelProfile,
                },
                success: function(res) {
                  if (res.status == 200) {
                    Queue.reloadTbWaiting(); //โหลดข้อมูลรอเรียกคัดกรองใหม่
                    Queue.reloadTbCalling(); //โหลดข้อมูลกำลัวเรียกใหม่
                    Queue.toastrSuccess("CALL " + qcall.data.qnumber);
                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                    socket.emit("call", res); //sending data
                    if (value == 0) {
                      setTimeout(function() {
                        var tr = $("#tb-calling tr#" + res.model.caller_ids),
                          url = "/app/calling/end-medical";
                        var key = tr.data("key");
                        var data = dt_tbcalling.row(tr).data();
                        $.ajax({
                          method: "POST",
                          url: baseUrl + url,
                          dataType: "json",
                          data: {
                            data: data, //Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile,
                          },
                          success: function(res) {
                            if (res.status == "200") {
                              Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                              Queue.toastrSuccess("END " + data.qnumber);
                              socket.emit("finish", res); //sending data
                              resolve();
                            } else {
                              Queue.ajaxAlertWarning();
                            }
                          },
                          error: function(jqXHR, textStatus, errorThrown) {
                            Queue.ajaxAlertError(errorThrown);
                          },
                        });
                      }, 500);
                    } else {
                      resolve();
                    }
                  } else {
                    Queue.ajaxAlertWarning();
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  Queue.ajaxAlertError(errorThrown);
                },
              });
            });
          },
        }).then((result) => {
          if (result.value) {
            //Confirm
            swal.close();
          }
        });
      }
    }
  } else {
    toastr.error(dataObj["CallingForm[qnum]"], "ไม่พบข้อมูล!", { timeOut: 3000, positionClass: "toast-top-center" });
  }
  $("input#callingform-qnum").val(null); //clear data
  return false;
});

$("a.activity-callnext").on("click", function(e) {
  e.preventDefault();
  var data = dt_tbwaiting.rows(0).data(),
    url = $(this).attr("data-url");
  var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
  if (data.length > 0) {
    if (Queue.checkCounter()) {
      swal({
        title: "CALL NEXT " + data[0].qnumber + " ?",
        text: data[0].qnumber,
        html:
          '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
          "<p>" +
          countername +
          "</p>",
        type: "question",
        input: "radio",
        inputOptions: {
          0: "ต้องการ END ทันที",
          1: "ไม่ต้องการ END ทันที",
        },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: "เรียกคิว",
        cancelButtonText: "ยกเลิก",
        showLoaderOnConfirm: true,
        preConfirm: function(value) {
          return new Promise(function(resolve, reject) {
            $.ajax({
              method: "POST",
              url: baseUrl + url,
              dataType: "json",
              data: {
                data: data[0], //Data in column Datatable
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
              },
              success: function(res) {
                if (res.status == 200) {
                  $("li.tab-watting, #tab-watting").removeClass("active");
                  $("li.tab-calling, #tab-calling").addClass("active");
                  Queue.reloadTbWaiting(); //โหลดข้อมูลรอเรียก
                  Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                  Queue.toastrSuccess("CALL " + data[0].qnumber);
                  //$("html, body").animate({ scrollTop: 0 }, "slow");
                  socket.emit("call", res); //sending data
                  if (value == 0) {
                    setTimeout(function() {
                      var tr = $("#tb-calling tr#" + res.model.caller_ids),
                        url = "/app/calling/end-medical";
                      var key = tr.data("key");
                      var data = dt_tbcalling.row(tr).data();
                      $.ajax({
                        method: "POST",
                        url: baseUrl + url,
                        dataType: "json",
                        data: {
                          data: data, //Data in column Datatable
                          modelForm: modelForm, //Data Model CallingForm
                          modelProfile: modelProfile,
                        },
                        success: function(res) {
                          if (res.status == "200") {
                            Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                            Queue.toastrSuccess("END " + data.qnumber);
                            socket.emit("finish", res); //sending data
                            resolve();
                          } else {
                            Queue.ajaxAlertWarning();
                          }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                          Queue.ajaxAlertError(errorThrown);
                        },
                      });
                    }, 500);
                  } else {
                    resolve();
                  }
                } else {
                  Queue.ajaxAlertWarning();
                }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                Queue.ajaxAlertError(errorThrown);
              },
            });
          });
        },
      }).then((result) => {
        if (result.value) {
          swal.close();
        }
      });
    }
  } else {
    swal({
      type: "warning",
      title: "ไม่พบหมายเลขคิว",
      showConfirmButton: false,
      timer: 1500,
    });
  }
});

//hidden menu
$("body").addClass("hide-sidebar");

$("#fullscreen-toggler").on("click", function(e) {
  setFullScreen();
});

function setFullScreen() {
  var element = document.documentElement;
  if (!$("body").hasClass("full-screen")) {
    $("body").addClass("full-screen");
    $("#fullscreen-toggler").addClass("active");
    localStorage.setItem("medical-fullscreen", "true");
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
    $("body").removeClass("full-screen");
    $("#fullscreen-toggler").removeClass("active");
    localStorage.setItem("medical-fullscreen", "false");
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
    }
  }
}

function setTabletmode() {
  var hpanel = $("div.panel-form");
  var icon = $("div.panel-form").find("i:first");
  var body = hpanel.find("div.panel-body");
  var footer = hpanel.find("div.panel-footer");

  if (localStorage.getItem("medical-tablet-mode") == "true") {
    body.slideToggle(300);
    footer.slideToggle(200);
    // Toggle icon from up to down
    icon.toggleClass("fa-chevron-up").toggleClass("fa-chevron-down");
    hpanel.toggleClass("").toggleClass("panel-collapse");
    setTimeout(function() {
      hpanel.resize();
      hpanel.find("[id^=map-]").resize();
    }, 50);
    var profilename = $("#callingform-service_profile").select2("data")[0]["text"] || "";
    var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
    $("div.panel-form .panel-heading-text").html(" | " + profilename + ": " + countername);
    $("#tablet-mode").prop("checked", true);
    $("#tab-menu-default,.small-header").css("display", "none");
    $(".footer-tabs,.call-next-tablet-mode,.text-tablet-mode").css("display", "");
    $("#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body").css("border-top", "1px solid #e4e5e7");
    dt_tbwaiting.column(1).visible(true);
  } else {
    if (hpanel.hasClass("panel-collapse")) {
      body.slideToggle(300);
      footer.slideToggle(200);
      // Toggle icon from up to down
      icon.toggleClass("fa-chevron-up").toggleClass("fa-chevron-down");
      hpanel.toggleClass("").toggleClass("panel-collapse");
      setTimeout(function() {
        hpanel.resize();
        hpanel.find("[id^=map-]").resize();
      }, 50);
    }
    $("div.panel-form .panel-heading-text").html("&nbsp;");
    $(".footer-tabs,.call-next-tablet-mode,.text-tablet-mode").css("display", "none");
    $("#tab-menu-default,.small-header").css("display", "");
    $("#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body").css("border-top", "0");
    dt_tbwaiting.column(1).visible(false);
  }
}

$(document).ready(function() {
  $("#tablet-mode").on("click", function() {
    if ($(this).is(":checked")) {
      localStorage.setItem("medical-tablet-mode", "true");
    } else {
      localStorage.setItem("medical-tablet-mode", "false");
    }
    setTabletmode();
  });
});

setTabletmode();

$("#tb-waiting tbody").on("change", 'input[name="selection[]"]', function() {
  var tr = $(this).closest("tr");
  var value = $(this).val();
  if (this.checked) {
    $(tr).addClass("success");
  } else {
    $(tr).removeClass("success");
  }
  // If checkbox is not checked
  if (this.checked) {
    keySelected.push(value);
  } else {
    if (jQuery.inArray(value, keySelected) !== -1) {
      $.each(keySelected, function(index, data) {
        if (value == data) {
          keySelected.splice(index, 1);
        }
      });
    }
  }
  if (keySelected.length > 0) {
    $("button.on-call-selected").prop("disabled", false);
  } else {
    $("button.on-call-selected").prop("disabled", true);
  }
  $(".count-selected").html("(" + keySelected.length + ")");
});

$("button.on-call-selected").on("click", function(e) {
  e.preventDefault();
  var url = $(this).data("url");
  var selectedData = [];
  var queNumber = [];
  $.each(keySelected, function(index, value) {
    var data = dt_tbwaiting.row("#" + value).data();
    if (data != undefined) {
      selectedData.push(data);
      queNumber.push(data.qnumber);
    }
  });
  if (selectedData.length > 0) {
    if (Queue.checkCounter()) {
      var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
      swal({
        title: "ยืนยันเรียกคิว?",
        text: "",
        html:
          '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small><br><p>' +
          queNumber.join(", ") +
          "</p><p>" +
          countername +
          "</p>",
        type: "question",
        input: "radio",
        inputOptions: {
          0: "ต้องการ END ทันที",
          1: "ไม่ต้องการ END ทันที",
        },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: "เรียกคิว",
        cancelButtonText: "ยกเลิก",
        allowOutsideClick: false,
        showLoaderOnConfirm: true,
        preConfirm: function(value) {
          return new Promise(function(resolve, reject) {
            var timer = 1000;
            $.ajax({
              method: "POST",
              url: baseUrl + url,
              dataType: "json",
              data: {
                selectedData: selectedData, //Data in column Datatable
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
                autoend: value,
              },
              success: function(res) {
                $("li.tab-watting, #tab-watting").removeClass("active");
                $("li.tab-calling, #tab-calling").addClass("active");
                $(".count-selected").html("(0)");
                $("button.on-call-selected").prop("disabled", true);
                Queue.reloadTbWaiting(); //โหลดข้อมูลรอเรียก
                Queue.reloadTbCalling(); //โหลดข้อมูลกำลังเรียก
                $.each(res.call_result, function(index, data) {
                  console.log(data);
                  setTimeout(function() {
                    Queue.toastrSuccess("CALL " + data.data.qnumber);
                    socket.emit("call", data); //sending data
                  }, timer);
                  timer = timer + 1500;
                });
                if (value == 0) {
                  var timer2 = 1000;
                  $.each(res.end_result, function(index, data) {
                    console.log(data);
                    setTimeout(function() {
                      Queue.toastrSuccess("END " + data.data.qnumber);
                      socket.emit("finish", data); //sending data
                    }, timer2);
                    timer2 = timer2 + 1500;
                  });
                }
                keySelected = [];
                resolve();
              },
              error: function(jqXHR, textStatus, errorThrown) {
                Queue.ajaxAlertError(errorThrown);
              },
            });
          });
        },
      }).then((result) => {
        if (result.value) {
          //Confirm
        }
      });
    }
  }
});

Queue.init();

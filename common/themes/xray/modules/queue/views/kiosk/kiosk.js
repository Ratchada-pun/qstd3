const http = axios.create({
  baseURL: baseURL,
  // timeout: 1000,
  headers: { "x-csrf-token": window.yii.getCsrfToken() },
});

// Add a request interceptor
http.interceptors.request.use(
  function(config) {
    return config;
  },
  function(error) {
    return Promise.reject(error);
  }
);

// Add a response interceptor
http.interceptors.response.use(
  function(response) {
    return _.get(response, "data", response);
  },
  function(error) {
    return Promise.reject(_.get(error, "response.data", error));
  }
);

const socket = io(window.socketBaseURL, {
  transports: ["websocket", "polling"],
  forceNew: true,
  path: window.socketPath,
});

socket
  .on("connect", () => {
    // console.log(socket.id);
    app.getClientIP();
  })
  .on("disconnect", (reason) => {
    // console.log(reason);
    if (reason === "io server disconnect") {
      // the disconnection was initiated by the server, you need to reconnect manually
      socket.connect();
    }
    // socket.close();
  })
  .on("setting", (res) => {
    window.location.reload();
  });

const SMARTCARD_EVENTS = {
  PCSC_INITIAL: "PCSC_INITIAL",
  PCSC_CLOSE: "PCSC_CLOSE",

  DEVICE_WAITING: "DEVICE_WAITING",
  DEVICE_CONNECTED: "DEVICE_CONNECTED",
  DEVICE_ERROR: "DEVICE_ERROR",
  DEVICE_DISCONNECTED: "DEVICE_DISCONNECTED",

  CARD_INSERTED: "CARD_INSERTED",
  CARD_REMOVED: "CARD_REMOVED",

  READING_INIT: "READING_INIT",
  READING_START: "READING_START",
  READING_PROGRESS: "READING_PROGRESS",
  READING_COMPLETE: "READING_COMPLETE",
  READING_FAIL: "READING_FAIL",
};

$('#btn-th').on('click',function(){
  app.$i18n.locale = 'th';
})
$('#btn-en').on('click',function(){
  app.$i18n.locale = 'en';
})

// Ready translated locale messages

// Create VueI18n instance with options
const i18n = new VueI18n({
  locale: window.locale || "th-TH", // set locale
  messages, // set locale messages
});
var app = new Vue({
  el: "#app",
  data: {
    message: "Hello Vue!",
    action: "",
    loading: false,
    loading2: false,
    loadingMsg: "เสียบบัตรประชาชน",
    crsfToken: "",
    clientIP: "0.0.0.0",
    search: "",
    patient: null,
    right: null,
    httpConfig: {
      baseURL: window.nodeBaseURL,
    },

    service_id: null,
    services: [],
  },
  computed: {
    patientName: function() {
      if (this.patient) {
        return _.get(this.patient, "fullname", "ชื่อ-นามสกุล");
      } else if (this.right) {
        const title = _.get(this.right, "title_name", "");
        const fname = _.get(this.right, "fname", "");
        const lname = _.get(this.right, "lname", "");
        return `${title}${fname} ${lname}`;
      }
      return "ชื่อ-นามสกุล";
    },
    hn: function() {
      return _.get(this.patient, "hn", "-");
    },
    cid: function() {
      if (this.patient) {
        return _.get(this.patient, "citizenId", "");
      } else if (this.right) {
        return _.get(this.right, "person_id", "");
      }
      return "";
    },
    cidFormat: function() {
      if (this.patient) {
        return String(_.get(this.patient, "citizenId", "")).substr(0, 9) + "XXXX";
      } else if (this.right) {
        return String(_.get(this.right, "person_id", "")).substr(0, 9) + "XXXX";
      }
      return "-";
    },
    rightName: function() {
      return _.get(this.right, "maininscl_name", "-");
    },
    avatar: function() {
      return _.get(this.patient, "photo", window.patientPicture);
    },
    age: function() {
      if (this.patient) {
        const [year, month, day] = String(this.patient.birthday).split("-");
        const a = moment();
        const b = moment(`${parseInt(year)}-${month}-${day}`, "YYYY-MM-DD");

        const years = a.diff(b, "year");
        b.add(years, "years");
        return years;
      } else if (this.right) {
        const y = parseInt(this.right.birthdate.substr(0, 4)) - 543;
        // const m = this.right.birthdate.substr(4, 2);
        // const d = this.right.birthdate.substr(6);
        // const a = moment();
        // const b = moment(`${parseInt(y)}-${m}-${d}`, "YYYY-MM-DD");

        // const years = a.diff(b, "year");
        // b.add(years, "years");
        return parseInt(moment().format("YYYY")) - y;
      }
      return 0;
    },
    disabledStyle: function() {
      if (!this.service_id) {
        return {
          cursor: "not-allowed",
          pointerEvents: "none",
        };
      }
      return {};
    },
    opacity: function() {
      if (!this.service_id) {
        return 0.65;
      }
      return null;
    },
  },
  beforeMount() {
    this.crsfToken = window.yii.getCsrfToken();
    // console.log(this.crsfToken);
  },
  mounted() {
    this.$nextTick(function() {
      this.fetchDataServices();
    });

    // if (jQuery("#w0").data("bootstrapSwitch")) {
    //   jQuery("#w0").bootstrapSwitch("destroy");
    // }
    // jQuery("#w0").bootstrapSwitch({
    //   size: "large",
    //   onColor: "success",
    //   offColor: "danger",
    //   onText: "ภาษาไทย",
    //   offText: "English",
    //   animate: true,
    //   indeterminate: false,
    //   disabled: false,
    //   readonly: false,
    // });
  },
  watch: {
    action: function(newVal, oldVal) {
      if (newVal === "hn-or-idcard") {
        // ป้อน HN หรือเลขบัตร ปชช
        setTimeout(() => {
          $("#input-hn-or-idcard").focus();
        }, 100);
      }
    },
    search: function(newVal, oldVal) {
      if (String(newVal).length === 13) {
        this.onConfirmSearch();
      }
    },
  },
  methods: {
    getClientIP: async function() {
      try {
        const ip = await http.get("/api2/v1/kiosk/client-ip");
        this.clientIP = ip;
        // socket.emit("join-room", { ip: ip, crsfToken: this.crsfToken });
        this.onSmartCardHandler();
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.message,
        });
      }
    },

    // รายชื่องานบริการ
    fetchDataServices: async function() {
      try {
        const services = await http.get("/api2/v1/kiosk/services");
        this.services = services;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.message,
        });
      }
    },

    onSmartCardHandler: function() {
      const _this = this;
      // socket.on("user joined", (data) => {
      //   console.log("user joined", socket.id);
      //   console.log("user joined", data);
      // });
      socket.on(SMARTCARD_EVENTS.DEVICE_CONNECTED, (data) => {
        if (_.get(data, "ipAddress") === _this.clientIP) {
          _this.setRight(null);
          _this.setProfile(null);
          _this.setLoadingMessage("อุปกรณ์กำลังเชื่อมต่อ...");
          setTimeout(() => {
            _this.setLoadingMessage("เสียบบัตรประชาชน");
          }, 1500);
        }
      });
      socket.on(SMARTCARD_EVENTS.CARD_INSERTED, (data) => {
        if (_.get(data, "ipAddress") === _this.clientIP) {
          // console.log("CARD_INSERTED", "เสียบบัตร");
          // console.log("CARD_INSERTED", data);
          _this.setRight(null);
          _this.setProfile(null);
          _this.setLoadingMessage("เสียบบัตร");
          setTimeout(() => {
            _this.setLoadingMessage("กรุณารอสักครู่...");
          }, 1000);
        }
      });
      socket.on(SMARTCARD_EVENTS.CARD_REMOVED, (data) => {
        if (_.get(data, "ipAddress") === _this.clientIP) {
          // console.log("CARD_REMOVED", "ถอดบัตร");
          // console.log("CARD_REMOVED", data);
          _this.setRight(null);
          _this.setProfile(null);
          _this.setLoading(false);
          _this.setLoadingMessage("เสียบบัตรประชาชน");
        }
      });
      socket.on(SMARTCARD_EVENTS.READING_START, (data) => {
        if (_.get(data, "ipAddress") === _this.clientIP) {
          // console.log("READING_START", "เริ่มอ่านบัตร");
          // console.log("READING_START", data);
          _this.setRight(null);
          _this.setProfile(null);
          _this.setLoading(true);
          _this.setLoadingMessage("กำลังอ่านข้อมูลบัตร");
        }
      });
      // socket.on(SMARTCARD_EVENTS.READING_PROGRESS, (data) => {
      //   if (_.get(data, "ipAddress") === _this.clientIP) {
      //     console.log("READING_PROGRESS", data);
      //   }
      // });
      socket.on(SMARTCARD_EVENTS.READING_COMPLETE, (data) => {
        if (_.get(data, "ipAddress") === _this.clientIP) {
          // console.log("READING_COMPLETE", data);
          _this.decryptData(_.get(data, "data.encrypted"));
        }
      });
      socket.on(SMARTCARD_EVENTS.READING_FAIL, (data) => {
        if (_.get(data, "ipAddress") === _this.clientIP) {
          // console.log("READING_FAIL", data);
          _this.setRight(null);
          _this.setProfile(null);
          _this.setLoading(false);
          _this.setLoadingMessage("เสียบบัตรประชาชน");
        }
      });
    },

    setLoading: function(loading = false) {
      this.loading = loading;
    },
    setLoadingMessage: function(message = "") {
      this.loadingMsg = message;
    },

    init: function() {
      this.getClientIP();
    },

    onSelectAction: function(action) {
      this.action = action;
    },
    onSelectLanguage: function(locale) {
      this.$i18n.locale = locale;
      this.action = "select-language";
    },
    onCancelAction: function() {
      this.action = "";
      this.search = "";
      this.loading = false;
      this.loading2 = false;
      this.loadingMsg = "เสียบบัตรประชาชน";
      this.service_id = null;
      this.setRight(null);
      this.setProfile(null);
    },

    decryptData: async function(encrypted) {
      const _this = this;
      if (this.loading2) return;
      try {
        _this.loading2 = true;
        const body = { encrypted: encrypted };
        const profile = await http.post("/api/queue/decrypt-data", body, _this.httpConfig);
        // get right
        await _this.fetchPatientRight(profile.citizenId);
        _this.setProfile(profile);
        _this.setLoading(false);
        _this.setLoadingMessage("เสียบบัตรประชาชน");
        if (_this.right) {
          // UCS = สิทธิหลักประกันสุขภาพแห่งชาติ
          // WEL = สิทธิหลักประกันสุขภาพแห่งชาติ (ยกเว้นการร่วมจ่ายค่าบริการ 30 บาท)
          // hmain 15049 = รพ.สิรินธร
          if (_this.right.hmain === "15049" && (_this.right.maininscl === "WEL" || _this.right.maininscl === "UCS")) { //เป็นสิทธิบัตรทองโรงพยาบาลสิรินท
            //  เป็นสิทธิ ผู้สูงอายุ ๖๐-๗๙
            if (_this.age >= 60 && _this.age <= 79) {
              _this.service_id = "39";
            } else if (_this.age >= 80) {
              // สิทธิผู้สูงอายุ ๘๐ ขึ้นไป
              _this.service_id = "42";
            } else {
              _this.service_id = "38";
            }
            Swal.close();
            _this.onCreateQueue();
          } else if (
            _this.right.hmain !== "15049" &&
            (_this.right.maininscl === "WEL" || _this.right.maininscl === "UCS")
          ) {
            //_this.service_id = "40";
            //  _this.onCreateQueue(autoConfirm);
            Swal.fire({
              title: _this.$t("คุณมี ใบส่งตัว/ใบ Refer มาพร้อมการรักษาวันนี้หรือไม่?"),
              text: "",
              icon: "question",
              width: "60%",
              showCancelButton: true,
              reverseButtons: true,
              allowOutsideClick: false,
              confirmButtonColor: "#3085d6",
              cancelButtonColor: "#d33",
              confirmButtonText: _this.$t("มี"),
              cancelButtonText: _this.$t("ไม่มี"),
              didOpen: () => {
                $(Swal.getTitle()).css("fontSize", "5rem");
                $(Swal.getTitle()).css("padding", "0 1em 0");
                $(Swal.getConfirmButton()).css("fontSize", "3rem");
                $(Swal.getConfirmButton()).css("width", "200px");
                $(Swal.getCancelButton()).css("fontSize", "3rem");
                $(Swal.getCancelButton()).css("width", "200px");
                $(Swal.getIcon()).css("fontSize", "2rem");
              },
            }).then((result) => {
              if (result.isConfirmed) {
                Swal.fire({
                  icon: "warning",
                  title: _this.$t("กรุณาติดต่อห้องเบอร์ 1"),
                  confirmButtonText: "ปิด",
                  width: "60%",
                  timer: 3000,
                  timerProgressBar: true,
                  showConfirmButton: false,
                  didOpen: () => {
                    $(Swal.getTitle()).css("fontSize", "5rem");
                    $(Swal.getTitle()).css("padding", "0 1em 0");
                    $(Swal.getConfirmButton()).css("fontSize", "3rem");
                    $(Swal.getConfirmButton()).css("width", "200px");
                    $(Swal.getIcon()).css("fontSize", "2rem");
                  },
                  willClose: () => {
                    _this.onCancelAction();
                  },
                });
              } else {
                _this.service_id = "40";
                _this.onCreateQueue();
              }
            });
          } else if (_this.right.hmain === "15049" && _this.right.maininscl === "SSS") {
            //สิทธิประกันสังคมโรงพยาบาลสิรินธร
            Swal.fire({
              icon: "warning",
              title: _this.$t("กรุณาติดต่อห้องเบอร์ 1"),
              confirmButtonText: "ปิด",
              width: "60%",
              timer: 3000,
              timerProgressBar: true,
              showConfirmButton: false,
              didOpen: () => {
                $(Swal.getTitle()).css("fontSize", "5rem");
                $(Swal.getTitle()).css("padding", "0 1em 0");
                $(Swal.getConfirmButton()).css("fontSize", "3rem");
                $(Swal.getConfirmButton()).css("width", "200px");
                $(Swal.getIcon()).css("fontSize", "2rem");
              },
              willClose: () => {
                _this.onCancelAction();
              },
            });
          } else {
            //สิทธิอื่นๆ ชำระเงินเอง / รัฐวิสาหกิจ / ประกันสุขภาพโรงพยาบาลอื่นๆ (ยกเว้น สิทธิหลักประกันสุขภาพแห่งชาติและสิทธิประกันสังคมโรงพยาบาลสิรินธร)
            Swal.close();
            _this.service_id = "40";
            _this.onCreateQueue();
          }
        } else {
          Swal.fire({
            icon: "warning",
            title: _this.$t("ไม่พบข้อมูลสิทธิการรักษา"),
            confirmButtonText: "ปิด",
            width: "60%",
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            didOpen: () => {
              $(Swal.getTitle()).css("fontSize", "5rem");
              $(Swal.getTitle()).css("padding", "0 1em 0");
              $(Swal.getConfirmButton()).css("fontSize", "3rem");
              $(Swal.getConfirmButton()).css("width", "200px");
              $(Swal.getIcon()).css("fontSize", "2rem");
            },
            willClose: () => {
              _this.onCancelAction();
            },
          });
        }
        _this.loading2 = false;
      } catch (error) {
        _this.loading2 = false;
        _this.setLoading(false);
        _this.setLoadingMessage("เสียบบัตรประชาชน");
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.message,
        });
      }
    },

    fetchPatientRight: async function(cid) {
      const _this = this;
      try {
        const right = await http.get(`/api/queue/patient-right/${cid}`, {
          baseURL: window.nodeBaseURLLocal,
        });
        this.setRight(_.get(right, "data"));
        return right;
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: error.message,
        });
      }
    },

    onClickNumber(number) {
      const search = this.search;
      if (search.length < 13) {
        this.search = search + number.toString();
        setTimeout(() => {
          this.$refs.search.focus();
        }, 0);
      }
    },
    onClearSearch() {
      this.search = "";
      setTimeout(() => {
        this.$refs.search.focus();
      }, 0);
    },
    onDeleteNumber() {
      if (this.search) {
        this.search = this.search.substr(0, this.search.length - 1);
        setTimeout(() => {
          this.$refs.search.focus();
        }, 0);
      }
    },

    setProfile: function(profile) {
      this.patient = profile;
    },
    setRight: function(right) {
      this.right = right;
    },

    onConfirmSearch: async function() {
      const _this = this;
      if (!_this.search) {
        Swal.fire({
          icon: "warning",
          title: "",
          text: _this.$t("กรุณาป้อนหมายเลขบัตรประจำตัวประชาชนที่ต้องการทำรายการ"),
        });
      } else {
        try {
          Swal.fire({
            title: "Please wait...",
            text: _this.$t("กำลังตรวจสอบข้อมูล"),
            timerProgressBar: true,
            allowOutsideClick: false,
            // timer: 3000,
            didOpen: () => {
              Swal.showLoading();
            },
          });
          await _this.fetchPatientRight(_this.search);
          if (_this.right) {
            // UCS = สิทธิหลักประกันสุขภาพแห่งชาติ
            // WEL = สิทธิหลักประกันสุขภาพแห่งชาติ (ยกเว้นการร่วมจ่ายค่าบริการ 30 บาท)
            // hmain 15049 = รพ.สิรินธร
            if (_this.right.hmain === "15049" && (_this.right.maininscl === "WEL" || _this.right.maininscl === "UCS")) {
              //  เป็นสิทธิ ผู้สูงอายุ ๖๐-๗๙
              if (_this.age >= 60 && _this.age <= 79) {
                _this.service_id = "39";
              } else if (_this.age >= 80) {
                // สิทธิผู้สูงอายุ ๘๐ ขึ้นไป
                _this.service_id = "42";
              } else {
                _this.service_id = "38";
              }
              Swal.close();
              _this.onCreateQueue();
            } else if (
              _this.right.hmain !== "15049" &&
              (_this.right.maininscl === "WEL" || _this.right.maininscl === "UCS")
            ) {
              //_this.service_id = "40";
              //  _this.onCreateQueue(autoConfirm);
              Swal.fire({
                title: _this.$t("คุณมี ใบส่งตัว/ใบ Refer มาพร้อมการรักษาวันนี้หรือไม่?"),
                text: "",
                icon: "question",
                width: "60%",
                showCancelButton: true,
                reverseButtons: true,
                allowOutsideClick: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: _this.$t("มี"),
                cancelButtonText: _this.$t("ไม่มี"),
                didOpen: () => {
                  $(Swal.getTitle()).css("fontSize", "5rem");
                  $(Swal.getTitle()).css("padding", "0 1em 0");
                  $(Swal.getConfirmButton()).css("fontSize", "3rem");
                  $(Swal.getConfirmButton()).css("width", "200px");
                  $(Swal.getCancelButton()).css("fontSize", "3rem");
                  $(Swal.getCancelButton()).css("width", "200px");
                  $(Swal.getIcon()).css("fontSize", "2rem");
                },
              }).then((result) => {
                if (result.isConfirmed) {
                  Swal.fire({
                    icon: "warning",
                    title: _this.$t("กรุณาติดต่อห้องเบอร์ 1"),
                    confirmButtonText: "ปิด",
                    width: "60%",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    didOpen: () => {
                      $(Swal.getTitle()).css("fontSize", "5rem");
                      $(Swal.getTitle()).css("padding", "0 1em 0");
                      $(Swal.getConfirmButton()).css("fontSize", "3rem");
                      $(Swal.getConfirmButton()).css("width", "200px");
                      $(Swal.getIcon()).css("fontSize", "2rem");
                    },
                    willClose: () => {
                      _this.onCancelAction();
                    },
                  });
                } else {
                  _this.service_id = "40";
                  _this.onCreateQueue();
                }
              });
            } else if (_this.right.hmain === "15049" && _this.right.maininscl === "SSS") {
              //สิทธิประกันสังคมโรงพยาบาลสิรินธร
              Swal.fire({
                icon: "warning",
                title: _this.$t("กรุณาติดต่อห้องเบอร์ 1"),
                confirmButtonText: "ปิด",
                width: "60%",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                didOpen: () => {
                  $(Swal.getTitle()).css("fontSize", "5rem");
                  $(Swal.getTitle()).css("padding", "0 1em 0");
                  $(Swal.getConfirmButton()).css("fontSize", "3rem");
                  $(Swal.getConfirmButton()).css("width", "200px");
                  $(Swal.getIcon()).css("fontSize", "2rem");
                },
                willClose: () => {
                  _this.onCancelAction();
                },
              });
            } else {
              //สิทธิอื่นๆ ชำระเงินเอง / รัฐวิสาหกิจ / ประกันสุขภาพโรงพยาบาลอื่นๆ (ยกเว้น สิทธิหลักประกันสุขภาพแห่งชาติและสิทธิประกันสังคมโรงพยาบาลสิรินธร)
              Swal.close();
              _this.service_id = "40";
              _this.onCreateQueue();
            }
          } else {
            Swal.fire({
              icon: "warning",
              title: _this.$t("ไม่พบข้อมูลสิทธิการรักษา"),
              confirmButtonText: "ปิด",
              width: "60%",
              timer: 3000,
              timerProgressBar: true,
              showConfirmButton: false,
              didOpen: () => {
                $(Swal.getTitle()).css("fontSize", "5rem");
                $(Swal.getTitle()).css("padding", "0 1em 0");
                $(Swal.getConfirmButton()).css("fontSize", "3rem");
                $(Swal.getConfirmButton()).css("width", "200px");
                $(Swal.getIcon()).css("fontSize", "2rem");
              },
              willClose: () => {
                _this.onCancelAction();
              },
            });
          }
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.message,
          });
        }
      }
    },

    onSelectService: function(serviceId) {
      this.service_id = serviceId;
      this.onCreateQueue();
    },

    onCreateQueue: async function(autoConfirm = false) {
      const _this = this;
      if (!_this.service_id) {
        Swal.fire({
          icon: "warning",
          title: _this.$t("กรุณาเลือกบริการ"),
          text: "",
        });
        return;
      } else {
        try {
          Swal.fire({
            title: _this.$t("กรุณารอสักครู่..."),
            text: _this.$t("ระบบกำลังทำรายการ"),
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
              Swal.clickConfirm();
            },
          });
          const body = {
            service_id: _this.service_id,
            cid: _this.cid,
            patient_name: _this.patientName,
            age: String(_this.age),
            maininscl_name: _this.rightName,
            picture: _.get(_this.patient, "photo"),
            right: _this.right,
            locale: _this.$i18n.locale,
          };
          const created = await http.post(`/api/queue/create-queue`, body, _this.httpConfig);
          socket.emit("register", created);
          Swal.fire({
            icon: "success",
            title: _this.$t("กรุณารอรับบัตรคิว"),
            text: "",
            timer: 3000,
            showConfirmButton: false,
          });
          window.open(`/queue/kiosk/print-ticket?id=${created.modelQueue.q_ids}`, "myPrint", "width=800, height=600");
          _this.onCancelAction();
        } catch (error) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: error.message,
          });
        }
        // Swal.fire({
        //   title: "ยืนยันออกบัตรคิว?",
        //   text: "",
        //   icon: "warning",
        //   showCancelButton: true,
        //   confirmButtonColor: "#3085d6",
        //   cancelButtonColor: "#d33",
        //   confirmButtonText: 'ตกลง / OK <i class="far fa-check-circle"></i>',
        //   cancelButtonText: '<i class="fas fa-times"></i> ยกเลิก / Cancel',
        //   showLoaderOnConfirm: true,
        //   reverseButtons: true,
        //   allowOutsideClick: false,
        //   width: "60%",
        //   didOpen: () => {
        //     $(Swal.getTitle()).css("fontSize", "4rem");
        //     $(Swal.getConfirmButton()).css("fontSize", "2rem");
        //     $(Swal.getCancelButton()).css("fontSize", "2rem");
        //     Swal.clickConfirm();
        //   },
        //   preConfirm: function() {
        //     return new Promise(async function(resolve) {});
        //   },
        // }).then((result) => {
        //   if (result.isConfirmed) {
        //   } else {
        //     _this.onCancelAction();
        //   }
        // });
      }
    },

    getPatientname: function() {
      if (this.action === "scan-idcard" && this.patient) {
        return _.get(this.patient, "fullname", "");
      } else if (this.action === "hn-or-idcard" && this.right) {
        const title = _.get(this.right, "title_name", "");
        const fname = _.get(this.right, "fname", "");
        const lname = _.get(this.right, "lname", "");
        return `${title}${fname} ${lname}`;
      }
      return "";
    },
    getRight: function(field, defaultValue = "") {
      return _.get(this.right, field, defaultValue);
    },
  },
  i18n,
});
// app.init();

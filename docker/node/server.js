require("dotenv").config();
var app = require("express")();
var cors = require('cors')
var server = require("http").Server(app);
var io = require("socket.io")(server, {
  allowEIO3: true,
  cors: {
    origin: "*",
    methods: ["GET", "POST"]
  }
});
var port = process.env.PORT || 3000;
var bodyParser = require("body-parser");
const ioclient = require("socket.io-client");
const socketclient = ioclient("http://q.chainathospital.org", { path: "/node/socket.io" });

socketclient
  .on("connect", () => {
    console.log("connected"); // true
  })
  .on("error", (error) => {
    console.log(error); // "G5p5..."
  });

// require the module

var multiparty = require("multiparty");
app.use(cors())
app.use(bodyParser.urlencoded({ extended: false }));

app.use(bodyParser.json());

app.get("/", function(req, res) {
  res.sendFile(__dirname + "/index.html");
});
app.use(function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  res.header("Access-Control-Allow-Methods", "GET,PUT,POST,DELETE");
  res.header("Access-Control-Allow-Headers", "Content-Type");

  req.io = socketclient;
  next();
});

var indexRouter = require("./routes/index");
var callingRouter = require("./routes/calling");
var dispensingRouter = require("./routes/dispensing");

app.use("/api", indexRouter);
app.use("/api/calling", callingRouter);
app.use("/api/dispensing", dispensingRouter);

//connection
io.on("connection", function(socket) {
  //ลงทะเบียนผู้ป่วย
  socket.on("register", function(res) {
    socket.broadcast.emit("register", res);
  });

  //เรียกคิว
  socket.on("call-screening-room", function(res) {
    socket.broadcast.emit("call-screening-room", res);
  });

  //Hold คิว
  socket.on("hold-screening-room", function(res) {
    socket.broadcast.emit("hold-screening-room", res);
  });

  //End คิว
  socket.on("endq-screening-room", function(res) {
    socket.broadcast.emit("endq-screening-room", res);
  });

  //เรียกคิว
  socket.on("call-examination-room", function(res) {
    socket.broadcast.emit("call-examination-room", res);
  });

  //Hold คิว
  socket.on("hold-examination-room", function(res) {
    socket.broadcast.emit("hold-examination-room", res);
  });

  //End คิว
  socket.on("endq-examination-room", function(res) {
    socket.broadcast.emit("endq-examination-room", res);
  });

  //เรียกคิว
  socket.on("call-medicine-room", function(res) {
    socket.broadcast.emit("call-medicine-room", res);
  });

  //Hold คิว
  socket.on("hold-medicine-room", function(res) {
    socket.broadcast.emit("hold-medicine-room", res);
  });

  //End คิว
  socket.on("endq-medicine-room", function(res) {
    socket.broadcast.emit("endq-medicine-room", res);
  });

  socket.on("transfer-examination-room", function(res) {
    socket.broadcast.emit("transfer-examination-room", res);
  });
  //สร้างรายการรับยาใกล้บ้าน
  socket.on("create-drug-dispensing", function(res) {
    io.emit("create-drug-dispensing", res);
  });

  //Display
  socket.on("display", function(res) {
    socket.broadcast.emit("display", res);
  });

  socket.on("call", function(res) {
    socket.broadcast.emit("call", res);
  });
  socket.on("recall", function(res) {
    socket.broadcast.emit("recall", res);
  });
  socket.on("hold", function(res) {
    socket.broadcast.emit("hold", res);
  });
  socket.on("finish", function(res) {
    socket.broadcast.emit("finish", res);
  });

  app.post("/api/save-profile", function(req, res) {
    if (!req.body) return res.sendStatus(400);

    var form = new multiparty.Form();

    form.on("error", function(err) {
      console.log("Error parsing form: " + err.stack);
    });

    form.parse(req, function(err, fields, files) {
      //console.log('fields: %@', fields);
      socket.broadcast.emit("read-card", fields);
    });

    req.on("end", () => {
      res.send("ok");
    });
  });

  socket.on("disconnect", function() {
    io.emit("disconnected");
  });
});

server.listen(port, function() {
  console.log("listening on *:" + port);
});

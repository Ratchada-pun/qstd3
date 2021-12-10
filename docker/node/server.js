require("dotenv").config();

const devMode = process.env.NODE_ENV === "development";
const express = require("express");
const app = express();
const cors = require("cors");
const http = require("http");
const httpServer = http.createServer(app);
const options = {
  allowEIO3: true,
  cors: {
    origin: "*",
    methods: ["GET", "POST", "DELETE", "PUT"],
  },
  transports: ["polling", "websocket"],
};
const io = require("socket.io")(httpServer, options);
const NodeRSA = require("node-rsa");
const key = new NodeRSA({ b: 512 });
const bodyParser = require("body-parser");
const morgan = require("morgan");
const soap = require("soap");
const _ = require("lodash");
const axios = require("axios");
const multiparty = require("multiparty");
const httpConfig = {
  baseURL: "https://qstd3.andamandev.com",
};
const port = process.env.PORT || 3000;
const ioclient = require("socket.io-client");
//const socketclient = ioclient("http://localhost:3000", { path: "/socket.io" });
const socketclient = ioclient("http://qstd3node:3003", { path: "/node/socket.io" });
const createError = require("http-errors");
const httpAssert = require("http-assert");
// error
const throwError = (...args) => {
  throw createError(...args);
};

const knex = require('knex')({
  client: 'mysql',
  connection: {
    host : process.env.DB_HOST,
    user : process.env.DB_USERNAME,
    password : process.env.DB_PASSWORD,
    database : process.env.DB_DATABASE,
    port: process.env.DB_PORT
  }
});

const publicKey = `-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBANTfxYalbw3kXUS/i4BZ6qPq9JXB0/zI
5KW8iIQ9ucqvizyTSB5gYHMlfEuP/NL78+hnaEvrGeJ8JBeqLQxrpqUCAwEAAQA==
-----END PUBLIC KEY-----`;

key.importKey(publicKey, "pkcs8-public-pem");

const privatePem = `-----BEGIN RSA PRIVATE KEY-----
  MIIBOgIBAAJBANTfxYalbw3kXUS/i4BZ6qPq9JXB0/zI5KW8iIQ9ucqvizyTSB5g
  YHMlfEuP/NL78+hnaEvrGeJ8JBeqLQxrpqUCAwEAAQJAJ7IWxnYBEIkeL1y8qdGa
  pLiCpY6AdmoL4TAYEPjltXrRnauiV860puRSRq00h4oWXRUXearAea02fnGbrZ+F
  gQIhAOu8bPjr5Ntl1OfpaejBllSNwpiWTcYGt0caw784Aj7hAiEA5yw/3Xa1KGNz
  nxESV2twA7XRpfyp9LoHblRFLBdrNEUCIQCryP3oT47Qyt5hudiyAxCXwU5Df5Rh
  cFdy+3AWEqygQQIgA8Kaf1Ww+Kk1dj7m13kt50GL2XFUqmBkQo0oWuE+oykCICkc
  6D4kj1aCh1DqX5myd+T1t8wCgwV89p+KiXg14YpF
  -----END RSA PRIVATE KEY-----`;

key.importKey(privatePem, "pkcs1-pem");

socketclient
  .on("connect", () => {
    console.log("connected"); // true
  })
  .on("error", (error) => {
    console.log(error); // "G5p5..."
  });

// require the module


app.use(cors());
app.use(bodyParser.urlencoded({ extended: false }));

app.use(bodyParser.json());

app.get("/", function(req, res) {
  res.sendFile(__dirname + "/index.html");
});

app.use(function (req, res, next) {
  res.header("Access-Control-Allow-Origin", "*");
  // res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization')
  res.header(
    "Access-Control-Allow-Headers",
    "Authorization, Accept, Origin, DNT, X-CustomHeader, Keep-Alive, User-Agent, X-Requested-With, If-Modified-Since, Cache-Control, Content-Type, Content-Range, Range"
  );
  if (req.method === "OPTIONS") {
    res.header("Access-Control-Allow-Methods", "PUT, POST, PATCH, DELETE, GET"); //to give access to all the methods provided
    return res.status(200).json({});
  }
  req.io = socketclient;
  req.assert = httpAssert;
  req.throw = throwError;
  next();
});

app.use(function (req, res, next) {
  res.success = (data = "", statusCode = 200) => {
    res.status(statusCode || 200).send({
      statusCode: statusCode,
      success: true,
      message: "ok",
      data: data,
    });
  };

  res.error = (err) => {
    if (devMode) {
      console.log(err.stack);
    }
    let statusCode = err.status || 500;
    res.status(statusCode);
    res.send({
      statusCode: statusCode,
      success: false,
      name: String(err.name).replace("Error", ""),
      message: err.message,
    });
  };

  next();
});

app.use(morgan("combined"));

app.post("/api/queue/decrypt-data", (req, res) => {
  const decryptedString = key.decrypt(req.body.encrypted, "utf8");
  const decrypedData = JSON.parse(decryptedString);
  res.send(decrypedData);
});

app.get("/api/queue/patient-right/:cid", async (req, res) => {
  // try {
  //   req.assert(req.params.cid, 400, "invalid cid.");
  //   const response = await axios.get(`/api2/v1/kiosk/pt-right?cid=${String(req.params.cid).replace(/ /g, "")}`, httpConfig);
  //   res.send(response.data);
  // } catch (error) {
  //   res.status(_.get(error, 'response.status', 500)).send(error.response.data);
  // }
  try {
    // const UserServiceModel = new UserService();
    const token = await knex.select('*').from('tb_token_nhso').orderBy('crearedat', 'desc').first()
    req.assert(token, 400, "invalid token");
    const args = {
      user_person_id: token.user_person_id,
      smctoken: token.smctoken,
      person_id: String(req.params.cid).replace(/ /g, ""),
    };

    const result = await new Promise((resolve) => {
      resolve(
        soap
          .createClientAsync("http://ucws.nhso.go.th/ucwstokenp1/UCWSTokenP1?WSDL")
          .then((client) => {
            return client.searchCurrentByPIDAsync(args);
          })
          .then((result) => {
            return result[0].return;
          })
      );
    });
    req.assert(result, 404, "ไม่พบข้อมูลสิทธิการรักษา");
    if (_.get(result, "ws_status") === "NHSO-00003") {
      req.throw(400, _.get(result, "ws_status_desc", "Token expire."));
    } else if (_.isEmpty(result.fname)) {
      req.throw(400, "Not found in NHSO.");
    } else if (_.isEmpty(result.maininscl) || _.isEmpty(result.maininscl_name)) {
      req.throw(400, "ไม่พบข้อมูลสิทธิการรักษา");
    }
    // redis.set(req.params.cid + "_right", JSON.stringify(result), "EX", 60 * 60 * 24);
    res.success(result);
  } catch (error) {
    res.error(error);
  }
});

app.post("/api/queue/create-queue", async (req, res) => {
  try {
    const response = await axios.post("/api2/v1/kiosk/create-queue", req.body, httpConfig);
    req.io.emit("register", response.data);
    res.send(response.data);
  } catch (error) {
    res.status(_.get(error, 'response.status', 500)).send(error.response.data);
  }
});

var indexRouter = require("./routes/index");
var callingRouter = require("./routes/calling");
var dispensingRouter = require("./routes/dispensing");
var kioskRouter = require("./routes/kiosk");
// var messageQueue = require("./jobs")(admin);

app.use("/api", indexRouter);
app.use("/api/calling", callingRouter);
app.use("/api/dispensing", dispensingRouter);
app.use("/api/kiosk", kioskRouter);

app.post("/api/send-message", async function(req, res) {
  try {
    await admin.messaging().send(req.body.message);

    res.status(200).send({ message: "Successfully sent message." });
  } catch (error) {
    res.status(500).send(error);
  }
});

app.post("/api/add-message", async function(req, res) {
  try {
    await messageQueue.add(req.body.message);
    res.status(200).send({ message: "Successfully sent message." });
  } catch (error) {
    res.status(500).send(error);
  }
});

const getClientIp = (socket) => {
  return socket.handshake.headers["x-forwarded-for"]
    ? socket.handshake.headers["x-forwarded-for"].split(/\s*,\s*/)[0]
    : socket.request.connection.remoteAddress;
};

const EVENTS = {
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
  socket.on("setting", function(res) {
    socket.broadcast.emit("setting", res);
  });

  socket.on("get ip", (clientId) => {
    socket.emit("ip", { ip: getClientIp(socket), clientId: clientId });
  });

  socket.on(EVENTS.DEVICE_CONNECTED, (data) => {
    socket.broadcast.emit(EVENTS.DEVICE_CONNECTED, data);
  });

  socket.on(EVENTS.DEVICE_DISCONNECTED, (data) => {
    socket.broadcast.emit(EVENTS.DEVICE_DISCONNECTED, data);
  });

  socket.on(EVENTS.CARD_INSERTED, (data) => {
    socket.broadcast.emit(EVENTS.CARD_INSERTED, data);
  });

  socket.on(EVENTS.CARD_REMOVED, (data) => {
    socket.broadcast.emit(EVENTS.CARD_REMOVED, data);
  });

  socket.on(EVENTS.READING_START, (data) => {
    socket.broadcast.emit(EVENTS.READING_START, data);
  });

  socket.on(EVENTS.READING_PROGRESS, (data) => {
    socket.broadcast.emit(EVENTS.READING_PROGRESS, data);
  });

  socket.on(EVENTS.READING_COMPLETE, (data) => {
    socket.broadcast.emit(EVENTS.READING_COMPLETE, data);
  });

  socket.on(EVENTS.READING_FAIL, (data) => {
    socket.broadcast.emit(EVENTS.READING_FAIL, data);
  });

  socket.on("join-room", (config) => {
    const roomId = getClientIp(socket);

    socket.join(roomId);
    socket.broadcast.to(roomId).emit("user-connected", config);

    socket.on("message", (message) => {
      io.to(roomId).emit("createMessage", message, config);
    });

    socket.on(EVENTS.DEVICE_CONNECTED, (data) => {
      io.to(roomId).emit(EVENTS.DEVICE_CONNECTED, config);
    });

    socket.on(EVENTS.DEVICE_DISCONNECTED, (data) => {
      io.to(roomId).emit(EVENTS.DEVICE_DISCONNECTED, config);
    });

    socket.on(EVENTS.CARD_INSERTED, (data) => {
      io.to(roomId).emit(EVENTS.CARD_INSERTED, config);
    });

    socket.on(EVENTS.CARD_REMOVED, (data) => {
      io.to(roomId).emit(EVENTS.CARD_REMOVED, config);
    });

    socket.on(EVENTS.READING_START, (data) => {
      io.to(roomId).emit(EVENTS.READING_START, config);
    });

    socket.on(EVENTS.READING_COMPLETE, (data) => {
      io.to(roomId).emit(EVENTS.READING_COMPLETE, data, config);
    });

    socket.on(EVENTS.READING_FAIL, (data) => {
      io.to(roomId).emit(EVENTS.READING_FAIL, config);
    });

    socket.on("disconnect", () => {
      socket.broadcast.to(roomId).emit("user-disconnected", config);
    });
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

httpServer.listen(port, function() {
  console.log("listening on *:" + port);
});

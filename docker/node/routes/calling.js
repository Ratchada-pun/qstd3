var express = require("express");
var qs = require("qs");
var router = express.Router();
const axios = require("axios");
axios.defaults.baseURL = "http://q.chainathospital.org";

router.get("/calling-queue", async function(req, res) {
  try {
    const params = `?${qs.stringify(req.query)}`;
    const response = await axios.get("/app/calling/calling-queue" + params);
    req.io.emit("call-screening-room", response.data);
    req.io.emit("call-examination-room", response.data);
    res.send(response.data);
  } catch (error) {
    res.status(error.response.status || 500).send(error.response.data);
  }
});

router.get("/hold-queue", async function(req, res) {
  try {
    const params = `?${qs.stringify(req.query)}`;
    const response = await axios.get("/app/calling/hold-queue" + params);
    req.io.emit("hold-screening-room", response.data);
    req.io.emit("hold-examination-room", response.data);
    res.send(response.data);
  } catch (error) {
    res.status(error.response.status || 500).send(error.response.data);
  }
});

router.get("/end-queue", async function(req, res) {
  try {
    const params = `?${qs.stringify(req.query)}`;
    const response = await axios.get("/app/calling/end-queue" + params);
    req.io.emit("endq-screening-room", response.data);
    req.io.emit("endq-examination-room", response.data);
    res.send(response.data);
  } catch (error) {
    res.status(error.response.status || 500).send(error.response.data);
  }
});

router.get("/send-to-doctor", async function(req, res) {
  try {
    const params = `?${qs.stringify(req.query)}`;
    const response = await axios.get("/app/calling/send-to-doctor" + params);
    req.io.emit("endq-screening-room", response.data);
    req.io.emit("endq-examination-room", response.data);
    res.send(response.data);
  } catch (error) {
    res.status(error.response.status || 500).send(error.response.data);
  }
});

module.exports = router;

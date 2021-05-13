var express = require('express');
var router = express.Router();
const axios = require('axios');
axios.defaults.baseURL = 'http://q.chainathospital.org';

router.post('/calling-queue', async function(req, res) {
    try {
        const response = await axios.post('/app/calling/calling-queue', req.body)
        req.io.emit('call-screening-room', response.data);
        req.io.emit('call-examination-room', response.data);
        res.send(response.data);
    } catch (error) {
        res.status(error.response.status || 500).send(error.response.data);
    }
});

router.post('/hold-queue', async function(req, res) {
    try {
        const response = await axios.post('/app/calling/hold-queue', req.body)
        req.io.emit('hold-screening-room', response.data);
        req.io.emit('hold-examination-room', response.data);
        res.send(response.data);
    } catch (error) {
        res.status(error.response.status || 500).send(error.response.data);
    }
});

router.post('/end-queue', async function(req, res) {
    try {
        const response = await axios.post('/app/calling/end-queue', req.body)
        req.io.emit('endq-screening-room', response.data);
        req.io.emit('endq-examination-room', response.data);
        res.send(response.data);
    } catch (error) {
        res.status(error.response.status || 500).send(error.response.data);
    }
});

module.exports = router;
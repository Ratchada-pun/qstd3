var express = require('express');
var router = express.Router();
const axios = require('axios');
//axios.defaults.baseURL = 'http://q.chainathospital.org';
axios.defaults.baseURL = 'http://queue-chainat.local';

router.post('/create', async function(req, res) {
    try {
        const response = await axios.post('/app/drug-dispensing/create-drug-dispensing', req.body)
        req.io.emit('create-drug-dispensing', response.data);
        res.send(response.data);
    } catch (error) {
        res.status(error.response.status || 500).send(error.response.data);
    }
});
module.exports = router;
var express = require('express');
var router = express.Router();
const Servicegroup = require('../models/get_servicegroup_list')
const Service = require('../models/get_service_list')
const Servicecounter = require('../models/get_counterservice_list')
const Drugconfig = require('../models/get_drug_config')
var assert = require('http-assert')

/* GET home page. */
router.get('/', function(req, res, next) {
    res.render('index', { title: 'Express' });
});

/* GET servicegroup. */
router.get('/get_servicegroup_list', async function(req, res, next) {
    try {
        const items = await Servicegroup.getItems()

        res.json(items)

    } catch (error) {

        res.json(error)


    }

});
/* GET servicegroup by id. */
router.get('/get_servicegroup_list/:id', async function(req, res, next) {
    try {
        const items = await Servicegroup.getItembyid(req.params.id)
        assert(items, 404, 'Not Found')

        res.json(items)

    } catch (error) {


        res.json(error)


    }

});

/* GET service. */
router.get('/get_service_list', async function(req, res, next) {
    try {

        const items = await Service.getItems()
        res.json(items)
    } catch (error) {
        res.json(error)

    }

});
/* GET service by id. */
router.get('/get_service_list/:id', async function(req, res, next) {
    try {
        const items = await Service.getItembyid(req.params.id)
        assert(items, 404, 'Not Found')
        res.json(items)
    } catch (error) {


        res.json(error)

    }

});
/* GET servicecounter. */
router.get('/get_counterservice_list', async function(req, res, next) {
    try {

        const items = await Servicecounter.getItems()
        res.json(items)
    } catch (error) {
        res.json(error)

    }

});


/* GET servicecounter by id. */
router.get('/get_counterservice_list/:id', async function(req, res, next) {
    try {
        const items = await Servicecounter.getItembyid(req.params.id)
        assert(items, 404, 'Not Found')
        res.json(items)
    } catch (error) {


        res.json(error)

    }

});
/* GET get_drug_config. */
router.get('/get_drug_config', async function(req, res, next) {
    try {
        const items = await Drugconfig.getItems()

        res.json(items)

    } catch (error) {

        res.json(error)


    }

});

module.exports = router;
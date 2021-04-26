const db = require('../db')
class Service {
    static getItems() {
        return db.select('*').from('tb_counterservice')

    }
    static getItembyid(id) {
        return db.select('*').from('tb_counterservice').where('servicegroupid', id)
    }



}
module.exports = Service;
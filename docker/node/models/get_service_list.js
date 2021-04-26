const db = require('../db')
class Service {
    static getItems() {
        return db.select('*').from('tb_service')

    }
    static getItembyid(id) {
        return db.select('*').from('tb_service').where('service_groupid', id)
    }


}
module.exports = Service;
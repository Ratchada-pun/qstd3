const db = require('../db')
class Servicegroup {
    static getItems() {
        return db.select('*').from('tb_servicegroup')
    }
    static getItembyid(id) {
        return db.select('*').from('tb_servicegroup').where('servicegroupid', id).first()

    }
}
module.exports = Servicegroup;
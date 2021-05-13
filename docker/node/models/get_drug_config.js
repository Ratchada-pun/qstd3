const db = require('../db')
class Drugconfig {
    static getItems() {
        return db.select('*').from('tb_drug_config')

    }

}
module.exports = Drugconfig;
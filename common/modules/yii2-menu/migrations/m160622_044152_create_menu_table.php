<?php

use yii\db\Migration;

/**
 * Handles the creation for table `menu_table`.
 */
class m160622_044152_create_menu_table extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('menu', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%menu}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'menu_category_id' => 'INT(11) NOT NULL',
                    'parent_id' => 'INT(11) NULL',
                    'title' => 'VARCHAR(200) NOT NULL',
                    'router' => 'VARCHAR(250) NOT NULL',
                    'parameter' => 'VARCHAR(250) NULL',
                    'icon' => 'VARCHAR(30) NULL',
                    'status' => 'ENUM(\'2\',\'1\',\'0\') NULL DEFAULT \'0\'',
                    'item_name' => 'VARCHAR(64) NULL',
                    'target' => 'VARCHAR(30) NULL',
                    'protocol' => 'VARCHAR(20) NULL',
                    'home' => 'ENUM(\'1\',\'0\') NULL DEFAULT \'0\'',
                    'sort' => 'INT(3) NULL',
                    'language' => 'VARCHAR(7) NULL DEFAULT \'*\'',
                    'params' => 'MEDIUMTEXT NULL',
                    'assoc' => 'VARCHAR(12) NULL',
                    'created_at' => 'INT(11) NULL',
                    'created_by' => 'INT(11) NULL',
                    'name' => 'VARCHAR(128) NULL',
                    'parent' => 'INT(11) NULL',
                    'route' => 'VARCHAR(256) NULL',
                    'order' => 'INT(11) NULL',
                    'data' => 'TEXT NULL',
                        ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('menu_auth', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%menu_auth}}', [
                    'menu_id' => 'INT(11) NOT NULL',
                    0 => 'PRIMARY KEY (`menu_id`)',
                    'item_name' => 'VARCHAR(64) NOT NULL',
                    1 => 'KEY (`item_name`)',
                        ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('menu_category', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%menu_category}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'title' => 'VARCHAR(50) NOT NULL',
                    'discription' => 'VARCHAR(255) NULL',
                    'status' => 'ENUM(\'1\',\'0\') NULL',
                        ], $tableOptions_mysql);
            }
        }


        $this->createIndex('idx_menu_category_id_5207_00', 'menu', 'menu_category_id', 0);
        $this->createIndex('idx_parent_id_5207_01', 'menu', 'parent_id', 0);
        $this->createIndex('idx_id_5487_02', 'menu_category', 'id', 0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_menu_category_5187_00', '{{%menu}}', 'menu_category_id', '{{%menu_category}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_menu_5327_01', '{{%menu_auth}}', 'menu_id', '{{%menu}}', 'id', 'CASCADE', 'CASCADE');
        $this->execute('SET foreign_key_checks = 1;');
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `menu`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `menu_auth`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `menu_category`');
        $this->execute('SET foreign_key_checks = 1;');
    }

}

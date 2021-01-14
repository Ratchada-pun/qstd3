<?php

use yii\db\Schema;

class m180426_020101_menu_auth extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('menu_auth', [
            'menu_id' => $this->integer(11)->notNull(),
            'item_name' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[menu_id]])',
            'FOREIGN KEY ([[menu_id]]) REFERENCES menu ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('menu_auth');
    }
}

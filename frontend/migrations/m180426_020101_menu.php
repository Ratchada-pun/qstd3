<?php

use yii\db\Schema;

class m180426_020101_menu extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'menu_category_id' => $this->integer(11)->notNull(),
            'parent_id' => $this->integer(11),
            'title' => $this->string(200)->notNull(),
            'router' => $this->string(250)->notNull(),
            'parameter' => $this->string(250),
            'icon' => $this->string(30),
            'status' => $this->string()->defaultValue('0'),
            'item_name' => $this->string(64),
            'target' => $this->string(30),
            'protocol' => $this->string(20),
            'home' => $this->string()->defaultValue('0'),
            'sort' => $this->integer(3),
            'language' => $this->string(7)->defaultValue('*'),
            'params' => $this->text(),
            'assoc' => $this->string(12),
            'created_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'name' => $this->string(128),
            'parent' => $this->integer(11),
            'route' => $this->text(),
            'order' => $this->integer(11),
            'data' => $this->text(),
            'auth_items' => $this->text(),
            'FOREIGN KEY ([[menu_category_id]]) REFERENCES menu_category ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('menu');
    }
}

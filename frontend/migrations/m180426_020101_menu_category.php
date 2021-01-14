<?php

use yii\db\Schema;

class m180426_020101_menu_category extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('menu_category', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->notNull(),
            'discription' => $this->string(255),
            'status' => $this->string(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('menu_category');
    }
}

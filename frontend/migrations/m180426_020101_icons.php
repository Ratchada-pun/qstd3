<?php

use yii\db\Schema;

class m180426_020101_icons extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('icons', [
            'id' => $this->primaryKey(),
            'classname' => $this->string(255)->notNull(),
            'type' => $this->string(255),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('icons');
    }
}

<?php

use yii\db\Schema;

class m180426_020101_file_storage_item extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('file_storage_item', [
            'id' => $this->primaryKey(),
            'component' => $this->string(255)->notNull(),
            'base_url' => $this->string(1024)->notNull(),
            'path' => $this->string(1024)->notNull(),
            'type' => $this->string(255),
            'size' => $this->integer(11),
            'name' => $this->string(255),
            'upload_ip' => $this->string(15),
            'created_at' => $this->integer(11)->notNull(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('file_storage_item');
    }
}

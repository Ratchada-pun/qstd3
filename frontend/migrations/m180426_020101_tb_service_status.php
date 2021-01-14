<?php

use yii\db\Schema;

class m180426_020101_tb_service_status extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_service_status', [
            'service_status_id' => $this->primaryKey(),
            'service_status_name' => $this->string(255)->notNull(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_service_status');
    }
}

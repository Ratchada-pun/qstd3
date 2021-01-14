<?php

use yii\db\Schema;

class m180426_020101_tb_service_profile extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_service_profile', [
            'service_profile_id' => $this->primaryKey(),
            'service_name' => $this->string(100)->notNull(),
            'counterservice_typeid' => $this->integer(11)->notNull(),
            'service_id' => $this->string(100)->notNull(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_service_profile');
    }
}

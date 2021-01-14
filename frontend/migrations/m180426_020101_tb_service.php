<?php

use yii\db\Schema;

class m180426_020101_tb_service extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_service', [
            'serviceid' => $this->primaryKey(),
            'service_name' => $this->string(100),
            'service_groupid' => $this->integer(11),
            'service_route' => $this->string(11),
            'prn_profileid' => $this->integer(11),
            'prn_copyqty' => $this->integer(2),
            'service_prefix' => $this->string(2),
            'service_numdigit' => $this->integer(2),
            'service_status' => $this->string(10),
            'service_md_name_id' => $this->integer(2),
            'FOREIGN KEY ([[service_groupid]]) REFERENCES tb_servicegroup ([[servicegroupid]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_service');
    }
}

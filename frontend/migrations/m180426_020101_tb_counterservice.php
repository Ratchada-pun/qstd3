<?php

use yii\db\Schema;

class m180426_020101_tb_counterservice extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_counterservice', [
            'counterserviceid' => $this->primaryKey(),
            'counterservice_name' => $this->string(100),
            'counterservice_callnumber' => $this->integer(2),
            'counterservice_type' => $this->integer(11),
            'servicegroupid' => $this->integer(11),
            'userid' => $this->integer(20),
            'serviceid' => $this->string(20),
            'sound_stationid' => $this->integer(11),
            'sound_id' => $this->integer(11),
            'counterservice_status' => $this->string(10),
            'FOREIGN KEY ([[counterservice_type]]) REFERENCES tb_counterservice_type ([[counterservice_typeid]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_counterservice');
    }
}

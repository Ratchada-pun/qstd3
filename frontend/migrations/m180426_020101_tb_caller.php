<?php

use yii\db\Schema;

class m180426_020101_tb_caller extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_caller', [
            'caller_ids' => $this->primaryKey(),
            'q_ids' => $this->integer(11),
            'qtran_ids' => $this->integer(11),
            'servicegroupid' => $this->integer(11),
            'counter_service_id' => $this->integer(11),
            'call_timestp' => $this->datetime(),
            'created_by' => $this->integer(11),
            'created_at' => $this->datetime(),
            'updated_by' => $this->integer(11),
            'updated_at' => $this->datetime(),
            'call_status' => $this->string(10),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_caller');
    }
}

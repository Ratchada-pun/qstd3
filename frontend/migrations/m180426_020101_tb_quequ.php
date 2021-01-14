<?php

use yii\db\Schema;

class m180426_020101_tb_quequ extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_quequ', [
            'q_ids' => $this->primaryKey(),
            'q_num' => $this->string(20),
            'q_timestp' => $this->datetime(),
            'pt_id' => $this->integer(11),
            'q_vn' => $this->string(20),
            'q_hn' => $this->string(20),
            'pt_name' => $this->string(200),
            'pt_visit_type_id' => $this->integer(11),
            'pt_appoint_sec_id' => $this->integer(11),
            'serviceid' => $this->integer(11),
            'servicegroupid' => $this->integer(11),
            'q_status_id' => $this->integer(11),
            'doctor_id' => $this->string(50),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_quequ');
    }
}

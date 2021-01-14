<?php

use yii\db\Schema;

class m180426_020101_tb_qtrans extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_qtrans', [
            'ids' => $this->primaryKey(),
            'q_ids' => $this->integer(11),
            'servicegroupid' => $this->integer(11),
            'counter_service_id' => $this->integer(11),
            'doctor_id' => $this->integer(11),
            'checkin_date' => $this->datetime(),
            'checkout_date' => $this->datetime(),
            'service_status_id' => $this->integer(11),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_qtrans');
    }
}

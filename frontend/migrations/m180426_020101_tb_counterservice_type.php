<?php

use yii\db\Schema;

class m180426_020101_tb_counterservice_type extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_counterservice_type', [
            'counterservice_typeid' => $this->primaryKey(),
            'counterservice_type' => $this->string(50),
            'sound_id' => $this->integer(11),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_counterservice_type');
    }
}

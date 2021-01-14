<?php

use yii\db\Schema;

class m180426_020101_tb_servicegroup extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_servicegroup', [
            'servicegroupid' => $this->primaryKey(),
            'servicegroup_name' => $this->string(100),
            'servicegroup_order' => $this->integer(11),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_servicegroup');
    }
}

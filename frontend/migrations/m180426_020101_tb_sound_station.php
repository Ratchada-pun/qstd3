<?php

use yii\db\Schema;

class m180426_020101_tb_sound_station extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_sound_station', [
            'sound_station_id' => $this->primaryKey(),
            'sound_station_name' => $this->string(255)->notNull(),
            'counterserviceid' => $this->string(255)->notNull(),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_sound_station');
    }
}

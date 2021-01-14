<?php

use yii\db\Schema;

class m180426_020101_tb_sound extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_sound', [
            'sound_id' => $this->primaryKey(),
            'sound_name' => $this->string(255)->notNull(),
            'sound_path_name' => $this->string(255)->notNull(),
            'sound_th' => $this->string(255),
            'sound_type' => $this->integer(11),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_sound');
    }
}

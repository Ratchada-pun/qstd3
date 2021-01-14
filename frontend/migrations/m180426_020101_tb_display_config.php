<?php

use yii\db\Schema;

class m180426_020101_tb_display_config extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_display_config', [
            'display_ids' => $this->primaryKey(),
            'display_name' => $this->string(255)->notNull(),
            'counterservice_id' => $this->string(100)->notNull(),
            'title_left' => $this->string(255)->notNull(),
            'title_right' => $this->string(255)->notNull(),
            'table_title_left' => $this->string(255)->notNull(),
            'table_title_right' => $this->string(255)->notNull(),
            'display_limit' => $this->integer(11)->notNull(),
            'hold_label' => $this->string(255)->notNull(),
            'header_color' => $this->string(100),
            'column_color' => $this->string(100),
            'background_color' => $this->string(100),
            'font_color' => $this->string(100),
            'border_color' => $this->string(100),
            'title_color' => $this->string(100),
            'display_status' => $this->integer(11),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_display_config');
    }
}

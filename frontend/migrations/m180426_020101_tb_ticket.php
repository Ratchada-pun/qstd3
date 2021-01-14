<?php

use yii\db\Schema;

class m180426_020101_tb_ticket extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        
        $this->createTable('tb_ticket', [
            'ids' => $this->primaryKey(),
            'hos_name_th' => $this->string(255)->notNull(),
            'hos_name_en' => $this->string(255),
            'template' => $this->text(),
            'default_template' => $this->text(),
            'logo_path' => $this->string(255),
            'logo_base_url' => $this->string(255),
            'barcode_type' => $this->string(255)->notNull(),
            'status' => $this->integer(255),
            ], $tableOptions);
                
    }

    public function down()
    {
        $this->dropTable('tb_ticket');
    }
}

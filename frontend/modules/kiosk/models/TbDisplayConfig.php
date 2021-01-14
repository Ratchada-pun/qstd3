<?php

namespace frontend\modules\kiosk\models;

use Yii;

/**
 * This is the model class for table "tb_display_config".
 *
 * @property int $display_ids
 * @property string $display_name
 * @property int $counterservice_type
 * @property string $title_left
 * @property string $title_right
 * @property string $table_title_left
 * @property string $table_title_right
 * @property int $display_limit
 * @property string $hold_label
 * @property string $header_color
 * @property string $column_color
 * @property string $background_color
 * @property string $font_color
 * @property string $border_color
 * @property string $title_color
 */
class TbDisplayConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_display_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name', 'counterservice_type', 'title_left', 'title_right', 'table_title_left', 'table_title_right', 'display_limit', 'hold_label'], 'required'],
            [['counterservice_type', 'display_limit'], 'integer'],
            [['display_name', 'title_left', 'title_right', 'table_title_left', 'table_title_right', 'hold_label'], 'string', 'max' => 255],
            [['header_color', 'column_color', 'background_color', 'font_color', 'border_color', 'title_color'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'display_ids' => 'Display Ids',
            'display_name' => 'Display Name',
            'counterservice_type' => 'Counterservice Type',
            'title_left' => 'Title Left',
            'title_right' => 'Title Right',
            'table_title_left' => 'Table Title Left',
            'table_title_right' => 'Table Title Right',
            'display_limit' => 'Display Limit',
            'hold_label' => 'Hold Label',
            'header_color' => 'Header Color',
            'column_color' => 'Column Color',
            'background_color' => 'Background Color',
            'font_color' => 'Font Color',
            'border_color' => 'Border Color',
            'title_color' => 'Title Color',
        ];
    }
}

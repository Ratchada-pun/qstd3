<?php

namespace frontend\modules\app\models;

use Yii;
use homer\behaviors\CoreMultiValueBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
/**
 * This is the model class for table "tb_display_config".
 *
 * @property int $display_ids
 * @property string $display_name
 * @property string $counterservice_id
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
 * @property int $display_status สถานะ
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

    public function behaviors()
    {
        return [
            [
                'class' => CoreMultiValueBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['service_id','counterservice_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['service_id','counterservice_id'],
                ],
                'value' => function ($event) {
                    return is_array($event->sender[$event->data]) ? implode(",", $event->sender[$event->data]) : '';
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'required'],
            [['display_limit', 'display_status','lab_display','pt_name','pt_pic','show_last_q','show_advertise','show_last_call'], 'integer'],
            [['display_name', 'title_left', 'title_right', 'title_latest', 'table_title_left', 'table_title_right', 'title_latest_right', 'hold_label', 'text_marquee', 'title_left_color', 'title_right_color', 'title_latest_color', 'table_title_left_color', 'table_title_right_color', 'title_latest_right_color', 'font_cell_display_color', 'cell_hold_bg_color', 'header_latest_color', 'cell_latest_color', 'font_cell_latest_color', 'border_cell_latest_color', 'hold_bg_color', 'hold_font_color', 'hold_border_color', 'font_marquee_color'], 'string', 'max' => 255],
            [['header_color', 'column_color', 'background_color', 'font_color', 'border_color', 'title_color'], 'string', 'max' => 100],
            [['service_id','sound_station_id'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'display_ids' => Yii::t('app', 'Display Ids'),
            'display_name' => Yii::t('app', 'Display Name'),
            'counterservice_id' => Yii::t('app', 'Counterservice ID'),
            'service_id' => Yii::t('app', 'Service ID'),
            'title_left' => Yii::t('app', 'Title Left'),
            'title_right' => Yii::t('app', 'Title Right'),
            'title_latest' => Yii::t('app', 'Title Latest'),
            'table_title_left' => Yii::t('app', 'Table Title Left'),
            'table_title_right' => Yii::t('app', 'Table Title Right'),
            'title_latest_right' => Yii::t('app', 'Title Latest Right'),
            'display_limit' => Yii::t('app', 'Display Limit'),
            'hold_label' => Yii::t('app', 'Hold Label'),
            'header_color' => Yii::t('app', 'Header Color'),
            'column_color' => Yii::t('app', 'Column Color'),
            'background_color' => Yii::t('app', 'Background Color'),
            'font_color' => Yii::t('app', 'Font Color'),
            'border_color' => Yii::t('app', 'Border Color'),
            'title_color' => Yii::t('app', 'Title Color'),
            'display_status' => Yii::t('app', 'สถานะ'),
            'text_marquee' => Yii::t('app', 'ข้อความวิ่ง'),
            'title_left_color' => Yii::t('app', 'Title Left Color'),
            'title_right_color' => Yii::t('app', 'Title Right Color'),
            'title_latest_color' => Yii::t('app', 'Title Latest Color'),
            'table_title_left_color' => Yii::t('app', 'Table Title Left Color'),
            'table_title_right_color' => Yii::t('app', 'Table Title Right Color'),
            'title_latest_right_color' => Yii::t('app', 'Title Latest Right Color'),
            'font_cell_display_color' => Yii::t('app', 'Font Cell Display Color'),
            'cell_hold_bg_color' => Yii::t('app', 'Cell Hold Bg Color'),
            'header_latest_color' => Yii::t('app', 'Header Latest Color'),
            'cell_latest_color' => Yii::t('app', 'Cell Latest Color'),
            'font_cell_latest_color' => Yii::t('app', 'Font Cell Latest Color'),
            'border_cell_latest_color' => Yii::t('app', 'Border Cell Latest Color'),
            'hold_bg_color' => Yii::t('app', 'Hold Bg Color'),
            'hold_font_color' => Yii::t('app', 'Hold Font Color'),
            'hold_border_color' => Yii::t('app', 'Hold Border Color'),
            'font_marquee_color' => Yii::t('app', 'Font Marquee Color'),
            'lab_display' => 'แสดงผล Lab เท่านั้น',
            'sound_station_id' => 'รหัสเครื่องเสียง',
            'pt_name' => 'ชื่อผู้ป่วย',
            'pt_pic' => 'ภาพ',
            'show_last_q' => 'แสดงคิวล่าสุด',
            'show_advertise' => 'แสดงข้อความประชาสัมพันธ์',
            'show_last_call' => 'แสดงคิวที่เรียกผ่านไปแล้ว',
        ];
    }

    /**
     * @inheritdoc
     * @return TbDisplayConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbDisplayConfigQuery(get_called_class());
    }

    public function getCounterList(){
        $li = [];
        if(!empty($this->counterservice_id)){
            $counters = explode(",",$this->counterservice_id);
            $model = TbCounterserviceType::find()->where(['counterservice_typeid' => $counters])->all();
            foreach ($model as $key => $value) {
                $li[] = Html::tag('li',$value['counterservice_type']);
            }
        }
        return count($li) > 0 ? Html::tag('ol',implode("\n", $li)) : '';
    }

    public function getServiceList(){
        $li = [];
        if(!empty($this->service_id)){
            $serviceids = explode(",",$this->service_id);
            $model = TbService::find()->where(['serviceid' => $serviceids])->all();
            foreach ($model as $key => $value) {
                $li[] = Html::tag('li',$value['service_name']);
            }
        }
        return count($li) > 0 ? Html::tag('ol',implode("\n", $li)) : '';
    }
}

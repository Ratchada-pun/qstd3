<?php

namespace frontend\modules\app\models;

use Yii;
use homer\behaviors\CoreMultiValueBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\icons\Icon;
use kartik\helpers\Html as kartik;
/**
 * This is the model class for table "tb_sound_station".
 *
 * @property int $sound_station_id
 * @property string $sound_station_name ชื่อ
 * @property string $counterserviceid ช่องบริการ
 */
class TbSoundStation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_sound_station';
    }

    public function behaviors()
    {
        return [
            [
                'class' => CoreMultiValueBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'counterserviceid',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'counterserviceid',
                ],
                'value' => function ($event) {
                    return implode(",", $event->sender[$event->data]);
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
            [['sound_station_name', 'counterserviceid','sound_station_status'], 'required'],
            [['sound_station_name'], 'string', 'max' => 255],
            [['counterserviceid'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sound_station_id' => 'Sound Station ID',
            'sound_station_name' => 'ชื่อ',
            'counterserviceid' => 'ช่องบริการ',
            'sound_station_status' => 'สถานะ'
        ];
    }

    /**
     * @inheritdoc
     * @return TbSoundStationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbSoundStationQuery(get_called_class());
    }

    public function getCounterList(){
        $li = [];
        if(!empty($this->counterserviceid)){
            $counters = explode(",",$this->counterserviceid);
            $model = TbCounterservice::find()->where(['counterserviceid' => $counters])->all();
            foreach ($model as $key => $value) {
                $li[] = Html::tag('li',$value['counterservice_name']);
            }
        }
        return count($li) > 0 ? Html::tag('ol',implode("\n", $li)) : '';
    }

    public function getCounterPlayList(){
        $badge = [];
        if(!empty($this->counterserviceid)){
            $counters = explode(",",$this->counterserviceid);
            $model = TbCounterservice::find()->where(['counterserviceid' => $counters])->all();
            foreach ($model as $key => $value) {
                $badge[] = Kartik::badge(Icon::show('check').$value['counterservice_name'],['class' => 'badge badge-success']);
            }
        }
        return implode("\n", $badge);
    }
}

<?php

namespace frontend\modules\app\models;

use Yii;
use homer\behaviors\CoreMultiValueBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
/**
 * This is the model class for table "tb_kiosk".
 *
 * @property int $kiosk_id
 * @property string $kiosk_name ชื่อ
 * @property string $service_ids กลุ่มบริการ
 * @property int $status สถานะ
 */
class TbKiosk extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_kiosk';
    }

    public function behaviors()
    {
        return [
            [
                'class' => CoreMultiValueBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['service_ids'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['service_ids'],
                ],
                'value' => function ($event) {
                    return is_array($event->sender[$event->data]) ? implode(",", $event->sender[$event->data]) : '';
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kiosk_name', 'service_ids', 'status'], 'required'],
            [['service_ids','font_size'], 'safe'],
            [['status'], 'integer'],
            [['kiosk_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kiosk_id' => Yii::t('app', 'Kiosk ID'),
            'kiosk_name' => Yii::t('app', 'ชื่อ'),
            'service_ids' => Yii::t('app', 'กลุ่มบริการ'),
            'status' => Yii::t('app', 'สถานะ'),
            'font_size' => Yii::t('app', 'ขนาดตัวอักษรปุ่มกด'),
        ];
    }

    public function getServiceList($service_ids){
        $li = [];
        if(!empty($service_ids)){
            $serviceids = explode(",",$service_ids);
            foreach ($serviceids as $serviceid) {
                $model = TbService::findOne($serviceid);
                if($model){
                    $li[] = Html::tag('li',$model['btn_kiosk_name'].' ('.$model['service_name'].')');
                }
            }
        }
        return count($li) > 0 ? Html::tag('ol',implode("\n", $li)) : '';
    }
}

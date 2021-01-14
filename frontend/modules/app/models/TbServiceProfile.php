<?php

namespace frontend\modules\app\models;

use Yii;
use homer\behaviors\CoreMultiValueBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "tb_service_profile".
 *
 * @property int $service_profile_id
 * @property string $service_name
 * @property int $service_groupid
 * @property string $service_id
 */
class TbServiceProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_service_profile';
    }

    public function behaviors()
    {
        return [
            [
                'class' => CoreMultiValueBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['service_id', 'counter_service_ids'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['service_id', 'counter_service_ids'],
                ],
                'value' => function ($event) {
                    return empty($event->sender[$event->data]) ? '' : implode(",", $event->sender[$event->data]);
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
            [['service_name', 'counterservice_typeid', 'service_id','service_profile_status'], 'required'],
            [['counterservice_typeid'], 'integer'],
            [['service_name'], 'string', 'max' => 100],
            [['service_id', 'counter_service_ids'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_profile_id' => 'Service Profile ID',
            'service_name' => 'Service Name',
            'counterservice_typeid' => 'Counter',
            'service_id' => 'Service ID',
            'service_profile_status' => 'สถานะ',
            'counter_service_ids' => 'ห้องตรวจที่ต้องการส่งคิวจากซักประวัติไป',
        ];
    }

    /**
     * @inheritdoc
     * @return TbServiceProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TbServiceProfileQuery(get_called_class());
    }

    public function getServieceList(){
        $li = [];
        if(!empty($this->service_id)){
            $counters = explode(",",$this->service_id);
            $model = TbService::find()->where(['serviceid' => $counters,'service_status' => 1])->all();
            foreach ($model as $key => $value) {
                $li[] = Html::tag('li',$value['service_name']);
            }
        }
        return count($li) > 0 ? Html::tag('ol',implode("\n", $li)) : '';
    }

    public function getTbCounterserviceType()
    {
        return $this->hasOne(TbCounterserviceType::className(), ['counterservice_typeid' => 'counterservice_typeid']);
    }

    public function getPrefixs(){
        $prefix = [];
        if(!empty($this->service_id)){
            $services = explode(",",$this->service_id);
            $model = TbService::find()->where(['serviceid' => $services,'service_status' => 1])->all();
            $prefix = ArrayHelper::map($model,'service_prefix','service_prefix');
        }
        return $prefix;
    }
}

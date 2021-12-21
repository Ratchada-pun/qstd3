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
    public $items;
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
            [['service_name', 'counterservice_typeid', 'service_profile_status', 'counterserviceid'], 'required'],
            [['counterservice_typeid', 'service_profile_status', 'service_status_id', 'counterserviceid'], 'integer'],
            [['counter_service_ids'], 'string'],
            [['items'], 'safe'],
            [['service_name', 'service_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'service_profile_id' => 'Service Profile ID',
            'service_name' => 'ชื่อโปรไฟล์',
            'counterservice_typeid' => 'ประเภทช่องบริการ',
            'service_id' => 'งานบริการ',
            'service_profile_status' => 'สถานะ',
            'counter_service_ids' => 'Counter Service Ids',
            'service_status_id' => 'สถานะการให้บริการ',
            'counterserviceid' => 'ช่องบริการ',
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

    public function getServieceList()
    {
        $li = [];
        if (!empty($this->service_id)) {
            $counters = explode(",", $this->service_id);
            $model = TbService::find()->where(['serviceid' => $counters, 'service_status' => 1])->all();
            foreach ($model as $key => $value) {
                $li[] = Html::tag('li', $value['service_name']);
            }
        }
        return count($li) > 0 ? Html::tag('ol', implode("\n", $li)) : '';
    }

    public function getTbCounterserviceType()
    {
        return $this->hasOne(TbCounterserviceType::className(), ['counterservice_typeid' => 'counterservice_typeid']);
    }
    public function getTbServiceStatus()
    {
        return $this->hasOne(TbServiceStatus::className(), ['service_status_id' => 'service_status_id']);
    }

    public function getPrefixs()
    {
        $prefix = [];
        if (!empty($this->service_id)) {
            $services = explode(",", $this->service_id);
            $model = TbService::find()->where(['serviceid' => $services, 'service_status' => 1])->all();
            $prefix = ArrayHelper::map($model, 'service_prefix', 'service_prefix');
        }
        return $prefix;
    }

    public function getProfilePrioritys()
    {
        return $this->hasMany(TbProfilePriority::className(), ['service_profile_id' => 'service_profile_id']);
    }
}

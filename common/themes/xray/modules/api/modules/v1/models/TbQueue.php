<?php

namespace xray\modules\api\modules\v1\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tb_queqe".
 *
 * @property int $q_ids running
 * @property string $queue_no หมายเลขคิว
 * @property string $queue_time เวลาที่ออกคิว
 * @property string $queue_date วันที่ออกคิว
 * @property string|null $cid รหัสประชาชนผู้ป่วย
 * @property string|null $pt_name ชื่อผู้ป่วย
 * @property int|null $age อายุ
 * @property int $servicegroupid กลุ่มบริการ
 * @property int $serviceid ประเภทบริการ
 * @property int $q_status_id สถานะคิว
 * @property int|null $tslotid รหัสช่วงเวลา
 * @property string|null $maininscl_name สิทธิการรักษา
 * @property string|null $created_at วันที่บันทึก
 * @property string|null $updated_at วันที่แก้ไข
 */
class TbQueue extends \yii\db\ActiveRecord
{
  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'tb_quequ';
  }

  public function behaviors()
  {
    return [
      [
        'class' => TimestampBehavior::className(),
        'attributes' => [
          ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at','q_timestp'],
          ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
        ],
        'value' => Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s')
      ],
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['q_num', 'servicegroupid', 'serviceid', 'q_status_id'], 'required'],
      [['q_timestp', 'created_at', 'updated_at'], 'safe'],
      [['age', 'servicegroupid', 'serviceid', 'q_status_id', 'tslotid'], 'integer'],
      [['q_num'], 'string', 'max' => 20],
      [['cid'], 'string', 'max' => 13],
      [['pt_name', 'maininscl_name'], 'string', 'max' => 255],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'q_ids' => 'running',
      'q_num' => 'หมายเลขคิว',
      'q_timestp' => 'เวลาที่ออกคิว',
      'cid' => 'รหัสประชาชนผู้ป่วย',
      'pt_name' => 'ชื่อผู้ป่วย',
      'age' => 'อายุ',
      'servicegroupid' => 'กลุ่มบริการ',
      'serviceid' => 'ประเภทบริการ',
      'q_status_id' => 'สถานะคิว',
      'tslotid' => 'รหัสช่วงเวลา',
      'maininscl_name' => 'สิทธิการรักษา',
      'created_at' => 'วันที่บันทึก',
      'updated_at' => 'วันที่แก้ไข',
    ];
  }

  /* public function generateQueueNumber($params)
  {
    $queue = ArrayHelper::map($this->find()->asArray()->all(), 'q_ids', 'queue_no');
    $qnums = [];
    $maxqnum = null;
    $qid = null;
    if (count($queue) > 0) {
      foreach ($queue as $key => $q) {
        $qnums[$key] = preg_replace('/[^0-9\.]/', '', $q);
      }
      $maxqnum = max($qnums);
      $qid = array_search($maxqnum, $qnums);
    }
    $component = \Yii::createObject([
      'class' => \xray\modules\api\components\AutoNumber::class,
      'prefix' => !empty($params['service_prefix']) ? $params['service_prefix'] : '0',
      'number' => ArrayHelper::getValue($queue, $qid, null),
      'digit' => !empty($params['service_numdigit']) ? $params['service_numdigit'] : 3,
    ]);
    return $component->generate();
  } */
  public function generateQueueNumber($params)
  {
    $maxid = $this->find()
      ->where([
        'serviceid' => $params['serviceid']
      ])
      ->andWhere('DATE(q_timestp) = CURRENT_DATE')
      ->max('q_ids');
    $number = null;
    if ($maxid) {
      $model = $this->findOne($maxid);
      $number = $model['q_num'];
    }
    $component = \Yii::createObject([
      'class'     => \xray\modules\api\components\AutoNumber::class,
      'prefix'    => $params['service_prefix'],
      'number'    => $number,
      'digit'     => $params['service_numdigit'],
    ]);
    return $component->generate();
  }
}

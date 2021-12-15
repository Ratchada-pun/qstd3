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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at', 'q_timestp'],
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
            [['q_arrive_time', 'q_appoint_time', 'pt_visit_type_id', 'appoint_id', 'servicegroupid', 'quickly', 'serviceid', 'created_from', 'q_status_id', 'counterserviceid', 'tslotid', 'countdrug', 'qfinace', 'paid_model'], 'integer'],
            [['u_id', 'token', 'hmain_op_name'], 'string'],
            [['q_num', 'q_vn', 'q_hn'], 'string', 'max' => 20],
            [['cid'], 'string', 'max' => 13],
            [['q_qn', 'rx_q'], 'string', 'max' => 10],
            [['pt_name'], 'string', 'max' => 200],
            [['doctor_id'], 'string', 'max' => 50],
            [['doctor_name'], 'string', 'max' => 250],
            [['pt_pic', 'pt_sound', 'maininscl_name', 'purchaseprovince_name', 'hsub_name', 'hmain_name'], 'string', 'max' => 255],
            [['age'], 'string', 'max' => 11],
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
            'q_arrive_time' => 'เวลามาถึง',
            'q_appoint_time' => 'เวลานัดหมาย',
            'cid' => 'รหัสประชาชนผู้ป่วย',
            'q_vn' => 'Visit number ของผู้ป่วย',
            'q_hn' => 'หมายเลข HN ผู้ป่วย',
            'q_qn' => 'QN',
            'rx_q' => 'Rx Q',
            'pt_name' => 'ชื่อผู้ป่วย',
            'pt_visit_type_id' => 'ประเภท walkin/ไม่ walkin',
            'appoint_id' => 'แผนกที่นัดหมาย',
            'servicegroupid' => 'กลุ่มบริการ',
            'quickly' => 'ความด่วนของคิว',
            'serviceid' => 'ประเภทบริการ',
            'created_from' => 'คิวสร้างจาก 1 kiosk 2 mobile',
            'q_status_id' => 'สถานะ',
            'doctor_id' => 'รหัสแพทย์',
            'doctor_name' => 'แพทย์ที่นัด',
            'counterserviceid' => 'เลขที่ช่องบริการ',
            'tslotid' => 'รหัสช่วงเวลา',
            'created_at' => 'วันที่บันทึก',
            'updated_at' => 'วันที่แก้ไข',
            'pt_pic' => 'ไฟล์ภาพ path file',
            'pt_sound' => 'ไฟล์เสียง path file',
            'maininscl_name' => 'สิทธิ์',
            'u_id' => 'รหัสผู้ใช้งาน mobile',
            'token' => 'รหัสแจ้งเตือนคิว',
            'age' => 'อายุ',
            'countdrug' => '0 = ไม่มียา , 1 = มียา',
            'qfinace' => '0 = ไม่ต้องจ่ายเงิน, 1 = จ่ายงิน',
            'purchaseprovince_name' => 'จังหวัดที่ลงทะเบียนรักษา',
            'hsub_name' => 'ชื่อหน่วยบริกการปฐมภูมิ ',
            'hmain_name' => 'ชื่อหน่วยบริกำรที่รับกำรส่งต่อ ',
            'paid_model' => 'รูปแบบกำรให้บริกำรเพื่อรับกำรจัดสรรเงิน(model)',
            'hmain_op_name' => 'ชื่อหน่วยบริการประจำ',
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

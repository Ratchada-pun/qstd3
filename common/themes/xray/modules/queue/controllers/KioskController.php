<?php

namespace xray\modules\queue\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\traits\ModelTrait;
use frontend\modules\app\models\TbNewsTicker;
use frontend\modules\app\models\TbTokenNhso;
use yii\web\HttpException;

/**
 * Kiosk controller for the `queue` module
 */
class KioskController extends Controller
{
    use ModelTrait;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $news_ticker = TbNewsTicker::findOne(['news_ticker_status' => 1]);
        $rows = (new \yii\db\Query())
            ->select(['i18n_source_message.*', 'i18n_message.language', 'i18n_message.translation'])
            ->from('i18n_message')
            ->leftJoin('i18n_source_message', 'i18n_source_message.id = i18n_message.id')
            ->where(['i18n_source_message.category' => 'app.frontend'])
            ->all();
        $groups = ArrayHelper::index($rows, null, 'language');
        $messages = [];
        foreach ($groups as $key => $items) {
            $messages[$key] = ArrayHelper::map($items, 'message', 'translation');
        }
        return $this->render('index', [
            'news_ticker' => $news_ticker,
            'messages' => $messages
        ]);
    }

    public function actionClientIp()
    {
        return $this->getUserIP();
    }

    private function getUserIP()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return current(array_values(array_filter(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    public function actionPrintTicket($id) //บัตรคิว

    {
        $model = $this->findModelQueue($id);
        $service = $this->findModelService($model['serviceid']);
        $ticket = $this->findModelTicket($service['prn_profileid']);
        $y = \Yii::$app->formatter->asDate('now', 'php:Y');

        $sql = 'SELECT
        count( `tb_quequ`.`q_ids` )
        FROM
          `tb_quequ`
        WHERE
          q_status_id = 1
          AND serviceid = :serviceid
          AND q_ids < :q_ids
          AND DATE( tb_quequ.q_timestp ) = CURRENT_DATE';
        $params = [':serviceid' => $model['serviceid'], ':q_ids' => $id];
        $count = Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryScalar();

        $attr = [];
        $keys = array_keys($model->attributeLabels());
        foreach ($keys as $value) {
            $attr['{' . $value . '}'] = $model->{$value};
        }

        $template = '';

        if ($model['locale'] == 'th') {
            $template = $ticket->template_th;
        }
        if ($model['locale'] == 'en') {
            $template = $ticket->template_en;
        }
        // บัตรคิวฟอร์มเล็ก 
        if (empty($model['cid'])) {
            if ($model['locale'] == 'th') {
                $template = $ticket->template_th_small;
            }
            if ($model['locale'] == 'en') {
                $template = $ticket->template_en_small;
            }
        }

        $template = strtr($template, ArrayHelper::merge([
            '{hos_name_th}' => $ticket->hos_name_th,
            '{pt_name}' => $model->pt_name,
            '{q_num}' => $model->q_num,
            '{service_name}' => $service['service_name'],
            '{time}' => \Yii::$app->formatter->asDate('now', 'php:d M ' . substr($y, 2)) . ' ' . \Yii::$app->formatter->asDate('now', 'php:H:i'),
            '{user_print}' => Yii::$app->user->isGuest ? 'Kiosk' : Yii::$app->user->identity->profile->name,
            '{qwaiting}' => $count,
            '/img/logo/logo.jpg' => $ticket->logo_path ? $ticket->logo_base_url . '/' . $ticket->logo_path : '/img/logo/logo.jpg',
        ], $attr));

        return $this->renderAjax('print-ticket', [
            'model' => $model,
            'ticket' => $ticket,
            'template' => $template,
            'service' => $service,
        ]);
    }
}

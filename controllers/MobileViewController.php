<?php
namespace frontend\controllers;

use Yii;
use frontend\modules\app\models\TbQuequ;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbQtrans;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MobileViewController extends \yii\web\Controller
{
    private $QID = null;

    public function actionIndex($id)
    {
    	$model = TbQuequ::findOne($id);
        $modelTrans = TbQtrans::findOne(['q_ids' => $id]);
        $this->QID = $id;
    	if(!$model || !$modelTrans){
            return $this->renderAjax('no-data');
			//throw new NotFoundHttpException('The requested page does not exist.');
    	}
        $countData = $this->count;
        return $this->renderAjax('index-v2',[
        	'model' => $model,
            'modelTrans' => $modelTrans,
            'modelCaller' => $countData['modelCaller'],
            'countData' => $countData,
        ]);
    }

    protected function findModelQ($id)
    {
        if (($model = TbQuequ::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCountQueue(){
        $request = Yii::$app->request;
        if($request->isAjax){
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $this->QID = $request->post('q_ids');
            $countData = $this->count;
            return $countData;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getCount(){
        $count = 0;
        $model = $this->findModelQ($this->QID);
        $modelTrans = TbQtrans::findOne(['q_ids' => $this->QID]);
        if(!$model || !$modelTrans){
            return 0;
        }
        if($modelTrans['service_status_id'] == 1){//ยังไม่ถูกเรียกเลย
            $count = TbQtrans::find()
            ->where('checkin_date < :checkin_date', [':checkin_date' => $modelTrans['checkin_date']])
            ->andWhere(['service_status_id' => 1])
            ->andWhere('ids <> :ids', [':ids' => $modelTrans['ids']])
            ->andWhere('tb_quequ.serviceid <> 1')
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin('tb_service','tb_service.serviceid = tb_quequ.serviceid')
            ->count();
        }else if($modelTrans['service_status_id'] == 2 && empty($modelTrans['counter_service_id'])) {//ถูกเรียก
            $count = 0;
        }else if($modelTrans['service_status_id'] == 4 && !empty($modelTrans['counter_service_id'])) {//รอเรียกห้องตรวจ
            $count = TbQtrans::find()
            ->where('checkin_date < :checkin_date', [':checkin_date' => $modelTrans['checkin_date']])
            ->andWhere(['service_status_id' => 4])
            ->andWhere('ids <> :ids', [':ids' => $modelTrans['ids']])
            ->andWhere('tb_quequ.serviceid <> 1')
            ->andWhere(['not', ['counter_service_id' => null]])
            ->innerJoin('tb_quequ','tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin('tb_service','tb_service.serviceid = tb_quequ.serviceid')
            ->count();
        }else if($modelTrans['service_status_id'] == 2 && !empty($modelTrans['counter_service_id'])) {//ถูกเรียกเข้าห้องตรวจ
            $count = 0;
        }
        $modelCaller = TbCaller::findOne(['qtran_ids' => $modelTrans['ids']]);

        $countername = '-';
        if(isset($modelTrans->tbCounterservice)){
            $countername = $modelTrans->tbCounterservice->counterservice_name;
        }elseif(isset($modelCaller->tbCounterservice)){
            $countername = $modelCaller->tbCounterservice->counterservice_name;
        }
        return [
            'model' => $model,
            'modelTrans' => $modelTrans,
            'count' => $count,
            'modelCaller' => $modelCaller,
            'countername' => $countername
        ];
    }
}
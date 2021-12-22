<?php

namespace frontend\modules\app\controllers;

use frontend\modules\app\models\TbDisplayConfig;
use Yii;
use frontend\modules\app\models\TbProfilePriority;
use frontend\modules\app\models\TbProfilePrioritySearch;
use frontend\modules\app\models\TbServiceProfile;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ProfilePriorityController implements the CRUD actions for TbProfilePriority model.
 */
class ProfilePriorityController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TbProfilePriority models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbProfilePrioritySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TbProfilePriority model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TbProfilePriority model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbProfilePriority();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->profile_priority_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TbProfilePriority model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->profile_priority_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TbProfilePriority model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TbProfilePriority model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProfilePriority the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProfilePriority::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDisplayPriority()
    {
        $request = Yii::$app->request;
        $this->layout = '@homer/views/layouts/main-blank.php';

        $display = TbDisplayConfig::find()->one();
        $rows = (new \yii\db\Query())
            ->select(['COUNT( tb_profile_priority.service_id ) AS c'])
            ->from('tb_profile_priority')
            ->groupBy('tb_profile_priority.service_profile_id')
            ->all();
        $models = TbServiceProfile::find()->where(['service_profile_status' => 1])->all();

        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            // reset
            $modelsProfilePriority = [];
            if (isset($_POST['TbProfilePriority'][0][0])) {
                foreach ($_POST['TbProfilePriority'] as $index => $prioritys) {
                    foreach ($prioritys as $indexPriority => $priority) {
                        $data['TbProfilePriority'] = $priority;
                        $modelProfilePriority = TbProfilePriority::findOne($priority['profile_priority_id']);
                        $modelProfilePriority->load($data);
                        $modelsProfilePriority[$index][$indexPriority] = $modelProfilePriority;
                        $valid = $modelProfilePriority->validate();
                    }
                }
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $flag = false;
                foreach ($models as $index => $model) {


                    if (!($flag = $model->save(false))) {
                        break;
                    }

                    if (isset($modelsProfilePriority[$index]) && is_array($modelsProfilePriority[$index])) {
                        foreach ($modelsProfilePriority[$index] as $indexPriority => $modelPriority) {
                            $modelPriority->profile_priority_seq = $indexPriority + 1;
                            if (!($flag = $modelPriority->save(false))) {
                                break;
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return $models;
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('_display_priority', [
            'models' => $models,
            'cols' => max(ArrayHelper::getColumn($rows, 'c')),
            'display' => $display
        ]);
    }

    public function actionDataDisplay()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = (new \yii\db\Query())
            ->select([
                'tb_caller.caller_ids',
                'tb_caller.qtran_ids',
                'DATE_FORMAT( DATE_ADD( tb_qtrans.checkin_date, INTERVAL 543 YEAR ), \'%H:%i:%s\' ) AS checkin_date',
                'tb_caller.servicegroupid',
                'tb_caller.counter_service_id',
                'tb_caller.call_timestp',
                'tb_quequ.q_num',
                'tb_quequ.q_hn',
                'tb_quequ.pt_name',
                'tb_service_status.service_status_name',
                'tb_counterservice.counterservice_name',
                'tb_counterservice.counterservice_callnumber',
                'tb_service.service_name',
                'tb_service.serviceid',
                'tb_service.service_prefix',
                'tb_quequ.quickly',
                'tb_qtrans.ids'
            ])
            ->from('tb_caller')
            ->innerJoin('tb_qtrans', 'tb_qtrans.ids = tb_caller.qtran_ids')
            ->innerJoin('tb_quequ', 'tb_quequ.q_ids = tb_qtrans.q_ids')
            ->innerJoin('tb_service_status', 'tb_service_status.service_status_id = tb_qtrans.service_status_id')
            ->innerJoin('tb_counterservice', 'tb_counterservice.counterserviceid = tb_caller.counter_service_id')
            ->leftJoin('tb_service', 'tb_service.serviceid = tb_quequ.serviceid')
            ->leftJoin('tb_service_profile', 'tb_service_profile.service_profile_id = tb_caller.service_profile_id')
            ->leftJoin('tb_profile_priority', 'tb_service_profile.service_profile_id = tb_profile_priority.service_profile_id')
            ->where([
                'tb_caller.call_status' => ['calling', 'callend'],
                'tb_quequ.q_status_id' => [2, 11, 12, 13]
            ])
            ->andWhere('DATE(tb_quequ.q_timestp) = CURRENT_DATE')
            ->orderBy(['tb_caller.call_timestp' => SORT_DESC])
            ->groupBy('tb_caller.caller_ids')
            ->all();

        return ['data' => $query];
    }

    public function actionCountWaiting()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $models = TbServiceProfile::find()->where(['service_profile_status' => 1])->all();
        $result = [];
        foreach ($models as $model) {
            $result[] = [
                'count' => $model->countWaiting,
                'service_profile_id' => $model->service_profile_id
            ];
        }
        return $result;
    }
}

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\mobile\TbQuequ */

$this->title = $model->q_ids;
$this->params['breadcrumbs'][] = ['label' => 'Tb Quequs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tb-quequ-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->q_ids], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->q_ids], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'q_ids',
            'q_num',
            'q_timestp',
            'q_arrive_time:datetime',
            'q_appoint_time:datetime',
            'pt_id',
            'q_vn',
            'q_hn',
            'pt_name',
            'pt_visit_type_id',
            'pt_appoint_sec_id',
            'serviceid',
            'servicegroupid',
            'quickly',
            'q_status_id',
            'doctor_id',
            'counterserviceid',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>

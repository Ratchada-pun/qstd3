<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\mobile\TbQuequ */

$this->title = 'Update Tb Quequ: ' . $model->q_ids;
$this->params['breadcrumbs'][] = ['label' => 'Tb Quequs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->q_ids, 'url' => ['view', 'id' => $model->q_ids]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-quequ-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

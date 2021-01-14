<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\kiosk\models\TbPtVisitType */

$this->title = 'Update Tb Pt Visit Type: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Tb Pt Visit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pt_visit_type_id, 'url' => ['view', 'id' => $model->pt_visit_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-pt-visit-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

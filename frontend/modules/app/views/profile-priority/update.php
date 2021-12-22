<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TbProfilePriority */

$this->title = 'Update Tb Profile Priority: ' . $model->profile_priority_id;
$this->params['breadcrumbs'][] = ['label' => 'Tb Profile Priorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->profile_priority_id, 'url' => ['view', 'id' => $model->profile_priority_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tb-profile-priority-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

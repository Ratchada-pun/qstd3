<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TbProfilePriority */

$this->title = 'Create Tb Profile Priority';
$this->params['breadcrumbs'][] = ['label' => 'Tb Profile Priorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-profile-priority-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

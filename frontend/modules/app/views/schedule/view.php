<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TableResourceSchedule */
?>
<div class="table-resource-schedule-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'Date',
            'STime',
            'ETime',
            'DRCode',
            'DRName',
            'Dayyy',
            'Loccode',
            'UpdateDate',
            'UpdateTime',
            'ResourceText',
        ],
    ]) ?>

</div>

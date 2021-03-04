<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\QueuesInterface */
?>
<div class="queues-interface-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'HN',
            'VN',
            'Fullname',
            'doctor',
            'lab',
            'xray',
            'SP',
            'PrintTime',
            'ArrivedTime',
            'PrintBillTime',
            'Time1',
            'Time2',
            'UpdateDate',
            'UpdateTime',
            'ArrivedTimeC',
            'WTime',
            'AppTime',
        ],
    ]) ?>

</div>

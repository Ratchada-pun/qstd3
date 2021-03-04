<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\Register */
?>
<div class="register-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'VN',
            'HN',
            'FullName',
            'TEL',
            'CareProvNo',
            'CareProv',
            'ServiceID',
            'Time',
            'AppTime',
            'loccode',
            'locdesc',
            'UpdateDate',
            'UpdateTime',
        ],
    ]) ?>

</div>

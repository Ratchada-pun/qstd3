<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\app\models\TbDrugDispensing */
?>
<div class="tb-drug-dispensing-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dispensing_id',
            'pharmacy_drug_id',
            'pharmacy_drug_name',
            'deptname',
            'rx_operator_id',
            'HN',
            'pt_name',
            'doctor_name',
            'dispensing_date',
            'dispensing_status_id',
            'dispensing_by',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'note',
        ],
    ]) ?>

</div>

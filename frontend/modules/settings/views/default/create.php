<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\kiosk\models\TbPtVisitType */

$this->title = 'Create Tb Pt Visit Type';
$this->params['breadcrumbs'][] = ['label' => 'Tb Pt Visit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-pt-visit-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

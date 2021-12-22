<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\app\models\TbProfilePrioritySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tb Profile Priorities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tb-profile-priority-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Tb Profile Priority', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'profile_priority_id',
            'profile_priority_seq',
            'service_profile_id',
            'service_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

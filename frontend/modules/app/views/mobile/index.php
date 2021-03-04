<?php

use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use kartik\form\ActiveForm;
use homer\assets\SocketIOAsset;
use homer\assets\jPlayerAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use homer\assets\ICheckAsset;
use homer\assets\HomerAdminAsset;
use yii\helpers\ArrayHelper;
use yii\web\View;
use frontend\modules\app\models\TbCounterservice;

SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);
ICheckAsset::register($this);

$this->registerCss($this->render('style-exam.css'));
$this->registerJs(
    'var baseUrl = ' . Json::encode(Url::base(true)) . '; ',
    View::POS_HEAD
);
$this->registerJs(
    'var modelForm = ' . Json::encode($modelForm) . '; ',
    View::POS_HEAD
);
$this->registerJs(
    'var modelProfile = ' . Json::encode($modelProfile) . '; ',
    View::POS_HEAD
);
$this->registerJs(
    'var select2Data = ' .
        Json::encode(
            ArrayHelper::map(
                TbCounterservice::find()
                    ->where([
                        'counterservice_type' =>
                            $modelProfile['counterservice_typeid'],
                        'counterservice_status' => 1,
                    ])
                    ->asArray()
                    ->orderBy(['service_order' => SORT_ASC])
                    ->all(),
                'counterserviceid',
                'counterservice_name'
            )
        ) .
        '; ',
    View::POS_HEAD
);

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\app\models\mobile\TbQuequSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'โปรแกรมเรียกคิวห้องตรวจ';
$this->registerCss($this->render('style.css'));
$this->render('assets', [
    'modelProfile' => $modelProfile,
    'modelForm' => $modelForm,
]);

?>
<?php
$form = ActiveForm::begin([
    'id' => 'calling-form',
    'type' => 'horizontal',
    'options' => ['autocomplete' => 'off'],
    'formConfig' => ['showLabels' => false],
]);
echo $this->render('_form_index', [
    'modelForm' => $modelForm,
    'modelProfile' => $modelProfile,
    'form' => $form,
    'doctorStatus' => $doctorStatus,
    'second1' => $second1,
    'second2' => $second2
]);
ActiveForm::end();
?>

<div class="row">
    <div class="col-lg-12">
        <?php Pjax::begin(['id' => 'QueueList']); ?>
        <?= ListView::widget([
            'dataProvider' => $listDataProvider,
            'summary' => false,
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('_list_item', ['model' => $model]);
            },
        ]) ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<div class="modal fade" id="holdlist" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">รายการคิวพัก</h6>
            </div>
            <div class="modal-body">
                <?php Pjax::begin(['id' => 'holdlist_pjax']); ?>
                <?= ListView::widget([
                    'dataProvider' => $listHoldProvider,
                    'summary' => false,
                    'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_list_hold', ['model' => $model]);
                    },
                ]) ?>
                <?php Pjax::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="endlist" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">รายการคิวที่เสร็จสิ้น</h6>
            </div>
            <div class="modal-body">
                <?php Pjax::begin(['id' => 'endlist_pjax']); ?>
                <?= ListView::widget([
                    'dataProvider' => $listEndProvider,
                    'summary' => false, //     'class' => 'list-wrapper', // ],
                    // 'options' => [
                    //     'tag' => 'div',
                    //     'id' => 'list-wrapper',
                    'itemView' => function ($model, $key, $index, $widget) {
                        return $this->render('_list_end', ['model' => $model]); // or just do some echo // return $model->title . ' posted by ' . $model->author;
                    },
                ]) ?>
                <?php Pjax::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
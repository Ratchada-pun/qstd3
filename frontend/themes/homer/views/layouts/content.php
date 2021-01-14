<?php
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Html;
?>
<div class="normalheader transition animated fadeIn small-header">
    <div class="hpanel">
        <div class="panel-body">

            <div id="hbreadcrumb" class="pull-right">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'tag' => 'ol',
                    'options' => ['class' => 'hbreadcrumb breadcrumb']
                ]) ?>
                <!-- <ol class="hbreadcrumb breadcrumb">
                    <li><a href="index.html">Dashboard</a></li>
                    <li>
                        <span>Interface</span>
                    </li>
                    <li class="active">
                        <span>Panels design</span>
                    </li>
                </ol> -->
            </div>
            <h2 class="font-light m-b-xs">
                <?= $this->title; ?>
            </h2>
            <small></small>
        </div>
    </div>
</div>
<div class="content animate-panel">
    <?= Alert::widget() ?>
    <?= $content ?>
    <?= \homer\widgets\ScrollTop::widget([]); ?>
</div>
<!-- Right sidebar -->
<div id="right-sidebar" class="animated fadeInRight">

    <div class="p-m">
        <button id="sidebar-close" class="right-sidebar-toggle sidebar-button btn btn-default m-b-md"><i class="pe pe-7s-close"></i>
        </button>
        <div class="checkbox">
            <label>
                <?= Html::checkbox( 'disabled-alert', false, [
                    'data-toggle' => 'toggle',
                    'id' => 'input-disable-alert',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'ios',
                    'data-size' => 'small'
                ]) ?>
                แจ้งเตือนหน้าเรียกคิว
            </label>
        </div>
        <div class="checkbox">
            <label>
                <?= Html::checkbox( 'sound-alert', false, [
                    'data-toggle' => 'toggle',
                    'id' => 'input-sound-alert',
                    'data-onstyle' => 'success',
                    'data-offstyle' => 'danger',
                    'data-style' => 'ios',
                    'data-size' => 'small'
                ]) ?>
                เสียงเตือนหน้าเรียกคิว
            </label>
        </div>
    </div>
</div>
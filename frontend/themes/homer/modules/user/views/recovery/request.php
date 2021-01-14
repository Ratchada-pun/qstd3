<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Recover your password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="text-center m-b-md">
            <?= Html::img(Yii::getAlias('@web').'/img/logo/logo.jpg',['class' => 'img-responsive center-block','width' => '200px']); ?>
            <h3 style="margin-top: 0px;margin-bottom: 0px;"><?= Html::encode($this->title) ?></h3>
            <small></small>
        </div>
        <div class="hpanel">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'password-recovery-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <div class="form-group">
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                </div>

                <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-success btn-block']) ?>
                <?= Html::a('Back to Login',['/user/login'],['class' => 'btn btn-default btn-block']); ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 text-center">
        <strong><?= \Yii::$app->keyStorage->get('app-name', Yii::$app->name); ?></strong> - Responsive WebApp <br/> 
    </div>
</div>

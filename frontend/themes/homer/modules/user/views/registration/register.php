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
 * @var dektrium\user\models\User $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign up');
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
                    'id' => 'registration-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <div class="form-group">
                    <?= $form->field($model, 'name') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'email') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'username') ?>
                </div>

                <div class="form-group">
                    <?php if ($module->enableGeneratingPassword == false): ?>
                        <?= $form->field($model, 'password')->passwordInput() ?>
                    <?php endif ?>
                </div>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>
                <?= Html::a(Yii::t('user', 'Already registered? Sign in!'),['/user/login'],['class' => 'btn btn-default btn-block']); ?>

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

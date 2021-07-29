<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use dektrium\user\models\LoginForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<div class="row">
    <div class="col-md-12">
        <div class="text-center m-b-md">
            <?= Html::img(\Yii::$app->keyStorage->get('logo-url', '/img/logo/logoKM4.png'),['class' => 'img-responsive center-block','width' => \Yii::$app->keyStorage->get('logo-login-width', '400px')]); ?>
            <h3 style="margin-top: 0px;margin-bottom: 0px;"><?= Yii::t('user','PLEASE LOGIN TO APP') ?></h3>
            <small></small>
        </div>
        <div class="hpanel">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                    'validateOnType' => false,
                    'validateOnChange' => false,
                ]) ?>
                <div class="form-group">
                    <?php if ($module->debug): ?>
                        <?= $form->field($model, 'login', [
                            'inputOptions' => [
                                'autofocus' => 'autofocus',
                                'class' => 'form-control',
                                'tabindex' => '1']])->dropDownList(LoginForm::loginList());
                        ?>

                    <?php else: ?>

                        <?= $form->field($model, 'login',
                            ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]
                        )->label(Yii::t('user','Username or Email'));
                        ?>

                    <?php endif ?>
                </div>
                <div class="form-group">
                    <?php if ($module->debug): ?>
                        <div class="alert alert-warning">
                            <?= Yii::t('user', 'Password is not necessary because the module is in DEBUG mode.'); ?>
                        </div>
                    <?php else: ?>
                        <?= $form->field(
                            $model,
                            'password',
                            ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])
                            ->passwordInput()
                            ->label(
                                Yii::t('user', 'Password')
                                . ($module->enablePasswordRecovery ?
                                    ' (' . Html::a(
                                        Yii::t('user', 'Forgot password?'),
                                        ['/user/recovery/request'],
                                        ['tabindex' => '5']
                                    )
                                    . ')' : '')
                            ) ?>
                    <?php endif ?>
                </div>
                <div class="checkbox">
                    <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '3']) ?>
                </div>
                <?= Html::submitButton(
                    Yii::t('user', 'Sign in'),
                    ['class' => 'btn btn-success btn-block', 'tabindex' => '4']
                ) ?>
                <?php if ($module->enableConfirmation): ?>
                    <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend'],['class' => 'btn btn-default btn-block']) ?>
                <?php endif ?>
                <?php if ($module->enableRegistration): ?>
                    <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register'],['class' => 'btn btn-default btn-block']) ?>
                <?php endif ?>
                <br>
                <?= Connect::widget([
                    'baseAuthUrl' => ['/user/security/auth'],
                ]) ?>
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
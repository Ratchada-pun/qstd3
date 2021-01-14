<?php

use yii\helpers\Html;
use yii\helpers\BaseStringHelper;

/* @var $this \yii\web\View */
/* @var $content string */

$controller = $this->context;
//$menus = $controller->module->menus;
//$route = $controller->route;
?>
<?php $this->beginContent('@app/views/layouts/main.php') ?>

<div class="row">
    <div class="col-md-3">

        <?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('menu', 'สร้างเมนู'), ['/menu/default/create'], ['class' => 'btn btn-success btn-block margin-bottom']) ?>


        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">
                    จัดการเมนู
                </h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">

                <?php
                $menu = [];
                $menu[] = [
                    'label' => 'เมนูทั้งหมด',
                    'encode' => false,
                    'url' => ['default/index'],
                    //$visible,
                ];


                //$nav = new firdows\menu\models\Navigate();
                //$nav->menu(1);
                echo yii\bootstrap\Nav::widget([
                    'options' => ['class' => 'nav nav-pills nav-stacked'],
                    //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                    'items' => $menu,
                ])
                ?>                 

            </div>
            <!-- /.box-body -->
        </div>
        
        
        <?= Html::a('<i class="fa fa-plus-circle"></i> ' . Yii::t('menu', 'สร้างประเภทเมนู'), ['/menu/category/create'], ['class' => 'btn btn-success btn-block margin-bottom']) ?>


        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">
                    จัดการประเภทเมนู
                </h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">

                <?php
                $menu = [];
                $menu[] = [
                    'label' => 'ประเภททั้งหมด',
                    'encode' => false,
                    'url' => ['category/index'],
                    //$visible,
                ];


                //$nav = new common\models\Navigate();
                echo yii\bootstrap\Nav::widget([
                    'options' => ['class' => 'nav nav-pills nav-stacked'],
                    //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                    'items' => $menu,
                ])
                ?>                 

            </div>
            <!-- /.box-body -->
        </div>


    </div>
    <!-- /.col -->


    <div class="col-md-9">
        <?= $content ?>
        <!-- /. box -->
    </div>
    <!-- /.col -->


</div>


<?php $this->endContent(); ?>

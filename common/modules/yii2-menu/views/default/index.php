<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use homer\menu\models\Menu;
use homer\menu\models\MenuCategory;
use yii\icons\Icon;
use homer\widgets\Modal;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel homer\menu\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('menu', 'ระบบจัดการเมนู');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tabbable">
    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'เมนู',
                'active' => true,
                'options' => ['id' => 'tab-menu'],
            ],
            [
                'label' => 'ประเภทเมนู',
                'options' => ['id' => 'tab-menucat'],
            ],
        ],
        'renderTabContent' => false,
        'encodeLabels' => false
    ]);
    ?>

<div class="tab-content">
    <div id="tab-menu" class="tab-pane active">
        <?php Pjax::begin(['id' => 'index-pjax']); ?>
        <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'panel' => [
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> '.Html::encode($this->title).'</h3>',
                    'type'=>'success',
                    'before'=>Html::a(Icon::show('plus').' Add Menu', ['create'], ['class' => 'btn btn-success','role' => 'modal-remote']).' '.Html::a(Icon::show('plus').' Add Category', ['/menu/category/create'], ['class' => 'btn btn-success','role' => 'modal-remote']),
                    'after'=>Html::a(Icon::show('refresh'), ['index'], ['class' => 'btn btn-info']),
                    'footer'=>false
                ],
                'condensed' => true,
                'hover' => true,
                'pjax' => true,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'name',
                        'format'=>'html',
                        'value' => function($model) {
                            return Html::a($model->iconShow.' '.$model->title,['/menu/default/view','id'=>$model->id]);
                        }
                    ],
                    [
                        'attribute' => 'menu_category_id',
                        'filter' => MenuCategory::getList(),
                        'value' => function($model) {
                            return $model->menu_category_id?$model->menuCategory->title:null;
                        }
                    ],
                    [
                        'attribute' => 'route',
                        'filter' => Menu::getRouterDistinct(),                 
                    ],
                    [
                        'attribute' => 'parent_id',
                        'filter' => Menu::getParentDistinct(),
                        'value' => function($model) {
                            return $model->parentTitle;
                        }
                    ],
                    // 'parameter',
                    // 'icon',
                    
                    [
                        'attribute' => 'status',
                        'filter' => Menu::getItemStatus(),
                        'value' => 'statusLabel',
                    ],
                    //'item_name',                      
                    [
                        'attribute' => 'items',
                        'filter' => Menu::getItemsListDistinct(),
                        'value' => 'itemsList',
                        'headerOptions' => ['width' => '200']
                    ],
                    'sort',
                    // 'language',
                    // 'assoc',
                    // 'created_at',
                    // 'created_by',
                    [
                        'class' => '\kartik\grid\ActionColumn',
                        'updateOptions' => [
                            'role' => 'modal-remote'
                        ],
                    ],
                ],
            ]);
            ?>
            <?php Pjax::end(); ?>
    </div>
    <div id="tab-menucat" class="tab-pane">
        <?= GridView::widget([
            'dataProvider' => $dataProviderCat,
            'filterModel' => $searchModelCat,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'title',
                'discription',
                'status',
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'updateOptions' => [
                        'role' => 'modal-remote'
                    ],
                    'urlCreator' => function ( $action,  $model,  $key,  $index) {
                        if($action == 'view'){
                            return Url::to(['/menu/category/view','id' => $key]);
                        }
                        if($action == 'update'){
                            return Url::to(['/menu/category/update','id' => $key]);
                        }
                        if($action == 'delete'){
                            return Url::to(['/menu/category/delete','id' => $key]);
                        }
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
</div>
<?php
Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    'options' => ['class' => 'modal modal-success','tabindex' => false,],
    'size' => 'modal-lg',
]);
Modal::end();
?>
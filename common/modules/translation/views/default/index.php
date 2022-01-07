<?php

use common\modules\translation\models\Source;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var $this               yii\web\View
 * @var $searchModel        common\modules\translation\models\search\SourceSearch
 * @var $dataProvider       yii\data\ActiveDataProvider
 * @var $model              \common\base\MultiModel
 * @var $languages          array
 */

$this->title = Yii::t('app', 'Translation');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card card-outline card-success collapsed-card">
    <div class="card-header">
        <h3 class="card-title"><?php echo Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Source Message']) ?></h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php echo $this->render('_form', [
            'model' => $model,
            'languages' => $languages,
        ]) ?>
    </div>
    <!-- /.card-body -->
</div>

<?php

$translationColumns = [];
foreach ($languages as $language => $name) {
    $translationColumns[] = [
        'attribute' => $language,
        'header' => $name,
        'value' => $language . '.translation',
    ];
}


echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => [
        'class' => 'grid-view table-responsive',
    ],
    'columns' => ArrayHelper::merge([
        [
            'attribute' => 'id',
            'options' => ['style' => 'width: 5%'],
        ],
        [
            'attribute' => 'category',
            'options' => ['style' => 'width: 10%'],
            'filter' => ArrayHelper::map(Source::find()->select('category')->distinct()->all(), 'category', 'category'),
        ],
        'message:ntext',
        [
            'class' => '\kartik\grid\ActionColumn',
            'options' => ['style' => 'width: 5%'],
            'template' => '{update} {delete}',
        ],
    ], $translationColumns),
]); ?>
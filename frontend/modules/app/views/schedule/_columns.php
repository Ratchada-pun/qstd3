<?php

use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ID',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Date',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'STime',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ETime',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'DRCode',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'DRName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Dayyy',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Loccode',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'UpdateDate',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'UpdateTime',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ResourceText',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
    ],

];

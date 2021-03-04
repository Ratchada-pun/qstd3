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
        'attribute' => 'VN',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'HN',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'FullName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'TEL',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'CareProvNo',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'CareProv',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ServiceID',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Time',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'AppTime',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'loccode',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'locdesc',
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

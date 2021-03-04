<?php

use yii\helpers\Url;

return [
    // [
    //     'class' => 'kartik\grid\CheckboxColumn',
    //     'width' => '20px',
    // ],
    // [
    //     'class' => 'kartik\grid\SerialColumn',
    //     'width' => '30px',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'ID',
        'contentOptions' => ['style' => 'text-align: center;width:10px;']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'HN',
        'contentOptions' => ['style' => 'text-align: center;width:80px;']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'VN',
        'contentOptions' => ['style' => 'text-align: center;width:100px;']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'Fullname',
        'contentOptions' => ['style' => 'text-align: center;width:200px;']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'doctor',
        'contentOptions' => ['style' => 'text-align: center;width:200px;']
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'lab',
        'contentOptions' => function ($model) {
            if ($model->lab === 'ผลออกครบ') {
                return ['style' => 'background-color:#6d953a;color:#ffffff;text-align:center;'];
            } else if ($model->lab === 'รอผล') {
                return ['style' => 'background-color:orange;color:#ffffff;text-align:center;'];
            } else {
                return ['style' => 'text-align: center;color:red;'];
            }
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'xray',
        'contentOptions' => function ($model) {
            if ($model->xray === 'ผลออกครบ') {
                return ['style' => 'background-color:#6d953a;color:#ffffff;text-align:center;'];
            } else if ($model->xray === 'รอผล') {
                return ['style' => 'background-color:orange;color:#ffffff;text-align:center;'];
            } else {
                return ['style' => 'text-align: center;color:red;'];
            }
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'SP',
        'contentOptions' => function ($model) {
            if ($model->SP === 'ผลออกครบ') {
                return ['style' => 'background-color:#6d953a;color:#ffffff;text-align:center;'];
            } else if ($model->SP === 'รอผล') {
                return ['style' => 'background-color:orange;color:#ffffff;text-align:center;'];
            } else {
                return ['style' => 'text-align: center;color:red;'];
            }
        },
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'PrintTime',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'ArrivedTime',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'PrintBillTime',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'Time1',
    // ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'Time2',
    // ],
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
        'attribute' => 'ArrivedTimeC',
        'contentOptions' => ['style' => 'text-align: center;']
    ],
    // [
    //     'class'=>'\kartik\grid\DataColumn',
    //     'attribute'=>'WTime',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'AppTime',
        'contentOptions' => ['style' => 'text-align: center;']
    ],
    // [
    //     'class' => 'kartik\grid\ActionColumn',
    //     'dropdown' => false,
    //     'vAlign'=>'middle',
    //     'urlCreator' => function($action, $model, $key, $index) { 
    //             return Url::to([$action,'id'=>$key]);
    //     },
    //     'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
    //     'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
    //     'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
    //                       'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
    //                       'data-request-method'=>'post',
    //                       'data-toggle'=>'tooltip',
    //                       'data-confirm-title'=>'Are you sure?',
    //                       'data-confirm-message'=>'Are you sure want to delete this item'], 
    // ],

];

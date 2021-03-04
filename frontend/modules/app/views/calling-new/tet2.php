  echo Table::widget([
                                        'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-calling'],
                                        //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                        'beforeHeader' => [
                                            [
                                                'columns' => [
                                                    ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'VN', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ห้องตรวจ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'Lab', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'X-ray', 'options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'SP', 'options' => ['style' => 'text-align: center;']],
                                                    // ['content' => 'ผล Lab','options' => ['style' => 'text-align: center;']],
                                                    ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                                                ],
                                                'options' => ['style' => 'background-color:#1E90FF;color:black;'],
                                            ]
                                        ],
                                    ]);
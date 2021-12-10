<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use homer\widgets\Panel;
use homer\assets\SocketIOAsset;

SocketIOAsset::register($this);

$this->title = 'Settings';
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="hpanel">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'จอแสดงผล',
                        'content' => $this->render('_content_display'),
                        'active' => true,
                    ],
                    [
                        'label' => 'ประเภท',
                        'content' => $this->render('_content_visit_type'),
                    ],
                    [
                        'label' => 'แพทย์',
                        'content' => $this->render('_content_doctor'),
                    ],
                    [
                        'label' => 'สถานะคิว',
                        'content' => $this->render('_content_service_status'),
                    ],
                    [
                        'label' => 'ประเภทเคาน์เตอร์/บริการ',
                        'content' => $this->render('_content_counter_type'),
                    ],
                    [
                        'label' => 'แผนก/คลีนิค',
                        'content' => $this->render('_content_section'),
                    ],
                    [
                        'label' => 'เคาน์เตอร์/บริการ',
                        'content' => $this->render('_content_counter'),
                    ],
                    [
                        'label' => 'ไฟล์เสียง',
                        'content' => $this->render('_content_sound_source'),
                    ],
                    [
                        'label' => 'ข้อมูลบัตรคิว',
                        'content' => $this->render('_content_ticket'),
                    ],
                ],
                'encodeLabels' => false,
            ]);
            ?>
        </div>
    </div>
</div>
<?php
echo $this->render('modal');

$this->registerJs(<<<JS
$('body').addClass('hide-sidebar');
JS
);
?>
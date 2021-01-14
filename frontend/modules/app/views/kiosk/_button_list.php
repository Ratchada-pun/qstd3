<?php
use frontend\modules\app\models\TbService;
use yii\helpers\Html;

$this->title = 'Kiosk';
?>
<div class="row">
<?php foreach($kiosks as $kiosk): ?>
    <div class="col-md-4" style="">
        <div class="hpanel">
            <div class="panel-body">
                <div class="text-center">
                    <h2 class="m-b-xs text-success"><?= $kiosk['kiosk_name'] ?></h2>
                    <div class="m">
                        <i class="pe-7s-monitor fa-5x"></i>
                    </div>
                    <ul>
                        <?php
                        if(!empty($kiosk['service_ids'])){
                            $serviceids = explode(",",$kiosk['service_ids']);
                            foreach ($serviceids as $serviceid) {
                                $model = TbService::findOne($serviceid);
                                if($model){
                                    echo Html::tag('li',$model['btn_kiosk_name'].' ('.$model['service_name'].')',['style' => 'text-align:left']);
                                }
                            }
                        }
                        ?>
                    </ul>
                    <?= Html::a('OPEN KIOSK',['/app/kiosk/kiosk-ticket','id' => $kiosk['kiosk_id']],['class' => 'btn btn-success btn-lg']); ?>            
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php
use yii\helpers\Html;

$this->title = 'Display';
?>
<br>
<div class="container">
	<div class="row">
	<?php foreach ($displays as $key => $display) { ?>
		
	    <div class="col-md-6" style="">
	        <div class="hpanel">
	            <div class="panel-body">
	                <div class="text-center">
	                    <h2 class="m-b-xs text-success"><?= $display['display_name']; ?></h2>
	                    <div class="m">
	                        <i class="pe-7s-monitor fa-5x"></i>
	                    </div>
	                    <?= Html::a('OPEN DISPLAY',['/kiosk/display/index','id' => $display['display_ids']],['class' => 'btn btn-success btn-lg']); ?>             
	                </div>
	            </div>
	        </div>
	    </div>
		
	<?php } ?>
	</div>
</div>

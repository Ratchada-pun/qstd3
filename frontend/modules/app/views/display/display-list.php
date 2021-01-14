<?php
use yii\helpers\Html;

$this->title = 'Display';
?>
<br>
<div class="container">
	<div class="row">
		<div class="col-md-12" style="text-align: center;">
			<h3>จอแสดงผล</h3>
		</div>
	</div>
	<div class="row">
	<?php foreach ($displays as $key => $display) { ?>

	<?php if($display['lab_display'] == 1) : ?>
		<div class="col-md-6" style="">
	        <div class="hpanel">
	            <div class="panel-body">
	                <div class="text-center">
	                    <h2 class="m-b-xs text-success"><?= $display['display_name']; ?></h2>
	                    <div class="m">
	                        <i class="pe-7s-monitor fa-5x"></i>
	                    </div>
	                    <?= Html::a('OPEN DISPLAY',['/app/display/lab','id' => $display['display_ids']],['class' => 'btn btn-success btn-lg','target' => '_blank','data-pjax' => 0]); ?>             
	                </div>
	            </div>
	        </div>
	    </div>
	<?php else: ?>
		<div class="col-md-6" style="">
	        <div class="hpanel">
	            <div class="panel-body">
	                <div class="text-center">
	                    <h2 class="m-b-xs text-success"><?= $display['display_name']; ?></h2>
	                    <div class="m">
	                        <i class="pe-7s-monitor fa-5x"></i>
	                    </div>
	                    <?= Html::a('OPEN DISPLAY',['/app/display/index','id' => $display['display_ids']],['class' => 'btn btn-success btn-lg','target' => '_blank','data-pjax' => 0]); ?>             
	                </div>
	            </div>
	        </div>
	    </div>
	<?php endif; ?>
	    
	<?php } ?>
	</div>
</div>
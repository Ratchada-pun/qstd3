<?php
use yii\helpers\Html;
use yii\bootstrap\Tabs;
use homer\assets\SweetAlert2Asset;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;

SweetAlert2Asset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);

$this->title = 'ออกบัตรคิว';
?>
<table id="Table_01" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<img src="/img/kiosktheme/theme_01.jpg"  alt=""></td>
	</tr>
	<tr>
        <td>
<div class="row">
    <div >
        <div class="hpanel">
            <?php
            echo $this->render('_content_ticket',['service' => $service]);
            // echo Tabs::widget([
            //     'items' => [
            //         [
            //             'label' => 'ออกบัตรคิว',
            //             'content' => $this->render('_content_ticket',['service' => $service]),
            //             'active' => true,
            //         ],
            //         [
            //             'label' => 'รายการคิว '.Html::tag('span','0',['id' => 'count-qdata','class' => 'badge']),
            //             'content' => $this->render('_content_qlist'),
            //         ],
            //     ],
            //     'encodeLabels' => false,
            // ]);
            ?>
        </div>
    </div>
</div>
</td>
	</tr>
            </table>
<!--            <div style="position: fixed;bottom: 0px; width:100%">-->
<!--                <marquee bgcolor="106cb5" >-->
<!--                    <font color="#ffffff">-->
<!--                      <h1>ยินดีต้อนรับสู่8</h1>-->
<!--                    </font>-->
<!--                </marquee>-->
<!--            </div>-->

<?php
echo $this->render('modal');
$this->registerJs(<<<JS
socket
.on('register', (res) => {
    dt_tbqdata.ajax.reload();
});
JS
);
?>
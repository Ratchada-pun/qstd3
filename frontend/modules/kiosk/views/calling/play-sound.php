<?php
use yii\jui\JuiAsset;
use homer\assets\jPlayerAsset;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;
use homer\assets\SweetAlert2Asset;
use frontend\modules\kiosk\models\TbSection;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;

JuiAsset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);
SweetAlert2Asset::register($this);
$bundle = jPlayerAsset::register($this);
$bundle->js[] = 'vendor/jPlayer/dist/add-on/jquery.jplayer.inspector.js';
$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
$this->registerJs('var model = '.Json::encode($model).'; ',View::POS_HEAD);

$this->title  = 'โปรแกรมเสียงเรียกคิว';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.jp-gui {
	position:relative;
	padding:20px;
	width:auto;
}
.jp-gui.jp-no-volume {
	width:432px;
}
.jp-gui ul {
	margin:0;
	padding:0;
}
.jp-gui ul li {
	position:relative;
	float:left;
	list-style:none;
	margin:2px;
	padding:4px 0;
	cursor:pointer;
}
.jp-gui ul li a {
	margin:0 4px;
}
.jp-gui li.jp-repeat,
.jp-gui li.jp-repeat-off {
	margin-left:344px;
}
.jp-gui li.jp-mute,
.jp-gui li.jp-unmute {
	margin-left:20px;
}
.jp-gui li.jp-volume-max {
	margin-left:120px;
}
li.jp-pause,
li.jp-repeat-off,
li.jp-unmute,
.jp-no-solution {
	display:none;
}
.jp-progress-slider {
	position:absolute;
	top:28px;
	left:100px;
	width:300px;
}
.jp-progress-slider .ui-slider-handle {
	cursor:pointer;
}
.jp-volume-slider {
	position:absolute;
	top:31px;
	left:508px;
	width:100px;
	height:.4em;
}
.jp-volume-slider .ui-slider-handle {
	height:.8em;
	width:.8em;
	cursor:pointer;
}
.jp-gui.jp-no-volume .jp-volume-slider {
	display:none;
}
.jp-current-time,
.jp-duration {
	position:absolute;
	top:42px;
	font-size:0.8em;
	cursor:default;
}
.jp-current-time {
	left:100px;
}
.jp-duration {
	left:360px;
}
.jp-gui.jp-no-volume .jp-duration {
	right:70px;
}
.jp-clearboth {
	clear:both;
}
.jp-jplayer {
    width: auto !important;
    height: auto !important;
}
.jp-title {
    left: 140px;
    position: absolute;
    top: 42px;
    font-size: 0.8em;
    cursor: default;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="hpanel">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                        'id' => 'calling-form',
                        'type' => 'horizontal',
                        'options' => ['autocomplete' => 'off'],
                        'formConfig' => ['showLabels' => false],
                    ]);
                ?>
                <div class="form-group" style="margin-bottom: 0px;margin-top: 0px;">
                    <div class="col-md-4">
                        <?=
                        $form->field($model, 'section')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(TbSection::find()->asArray()->all(),'sec_id','sec_name'),
                            'options' => ['placeholder' => 'เลือกแผนก...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::LARGE,
                            'pluginEvents' => [
                                "change" => "function() {
                                    if($(this).val() != '' && $(this).val() != null){
                                        location.replace(baseUrl + \"/kiosk/calling/play-sound?secid=\" + $(this).val());
                                    }else{
                                        location.replace(baseUrl + \"/kiosk/calling/play-sound?secid=null\");
                                    }
                                }",
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
                <section>
                    <div id="jquery_jplayer_N" class="jp-jplayer"></div>

                    <div id="jp_container">
                        <div class="jp-gui ui-widget ui-widget-content ui-corner-all">
                            <ul>
                                <li class="jp-play ui-state-default ui-corner-all"><a href="javascript:;" class="jp-play ui-icon ui-icon-play" tabindex="1" title="play">play</a></li>
                                <li class="jp-pause ui-state-default ui-corner-all"><a href="javascript:;" class="jp-pause ui-icon ui-icon-pause" tabindex="1" title="pause">pause</a></li>
                                <li class="jp-stop ui-state-default ui-corner-all"><a href="javascript:;" class="jp-stop ui-icon ui-icon-stop" tabindex="1" title="stop">stop</a></li>
                                <li class="jp-repeat ui-state-default ui-corner-all"><a href="javascript:;" class="jp-repeat ui-icon ui-icon-refresh" tabindex="1" title="repeat">repeat</a></li>
                                <li class="jp-repeat-off ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-repeat-off ui-icon ui-icon-refresh" tabindex="1" title="repeat off">repeat off</a></li>
                                <li class="jp-mute ui-state-default ui-corner-all"><a href="javascript:;" class="jp-mute ui-icon ui-icon-volume-off" tabindex="1" title="mute">mute</a></li>
                                <li class="jp-unmute ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-unmute ui-icon ui-icon-volume-off" tabindex="1" title="unmute">unmute</a></li>
                                <li class="jp-volume-max ui-state-default ui-corner-all"><a href="javascript:;" class="jp-volume-max ui-icon ui-icon-volume-on" tabindex="1" title="max volume">max volume</a></li>
                            </ul>
                            <div class="jp-progress-slider"></div>
                            <div class="jp-volume-slider"></div>
                            <div class="jp-current-time"></div>
                            <div class="jp-title"></div>
                            <div class="jp-duration"></div>
                            <div class="jp-clearboth"></div>
                        </div>
                        <!-- <div class="jp-playlist">
                            <ul>
                                <li></li>
                            </ul>
                        </div> -->
                    </div>
                    <div id="jplayer_inspector"></div>
                </section>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
var jPlayerid = "#jquery_jplayer_N";
var jp_container = "#jp_container";
var i = 0;
var myPlayer = $(jPlayerid),
    myPlayerData,
    fixFlash_mp4, // Flag: The m4a and m4v Flash player gives some old currentTime values when changed.
    fixFlash_mp4_id, // Timeout ID used with fixFlash_mp4
    ignore_timeupdate, // Flag used with fixFlash_mp4
    myControl = {
        progress: $(jp_container + " .jp-progress-slider"),
        volume: $(jp_container + " .jp-volume-slider")
    };
var myPlaylist = new jPlayerPlaylist({
        jPlayer: jPlayerid,
        cssSelectorAncestor: jp_container
    }, [
    
    ], {
    playlistOptions: {
        autoPlay: true,
        enableRemoveControls: true,
    },
    ready: function (event) {
        // Hide the volume slider on mobile browsers. ie., They have no effect.
        if(event.jPlayer.status.noVolume) {
            // Add a class and then CSS rules deal with it.
            $(".jp-gui").addClass("jp-no-volume");
        }
        // Determine if Flash is being used and the mp4 media type is supplied. BTW, Supplying both mp3 and mp4 is pointless.
        fixFlash_mp4 = event.jPlayer.flash.used && /m4a|m4v/.test(event.jPlayer.options.supplied);
    },
    timeupdate: function(event) {
        if(!ignore_timeupdate) {
            myControl.progress.slider("value", event.jPlayer.status.currentPercentAbsolute);
        }
    },
    volumechange: function(event) {
        if(event.jPlayer.options.muted) {
            myControl.volume.slider("value", 0);
        } else {
            myControl.volume.slider("value", event.jPlayer.options.volume);
        }
    },
    playing: function (event) {
        var current = myPlaylist.current;
        var data = myPlaylist.playlist[current];
        if(data.wav.indexOf("please.wav") >= 0){
            socket.emit('display', data);//sending data
            qFunc.updateStatus(data.artist.model.caller_ids);//update tb_caller status = callend
            toastr.success(' ' + data.title, 'Calling!', {timeOut: 5000,positionClass: "toast-top-right"});
        }
        if((current + 1) === myPlaylist.playlist.length){
            myPlaylist.remove();//reset q
        }
    },
    loadstart: function (event) {
        //console.log(myPlaylist.playlist);
    },
    ended: function (event) {

    },
    error: function (event) {
        console.log(event);
    },
    loop: false,
    swfPath: "/vendor/jPlayer/dist/jplayer",
    supplied: "m4a, oga, mp3, wav, mp4, rtmp, flv, ogg, webmv, ogv, m4v",
    cssSelectorAncestor: jp_container,
    wmode: "window",
    keyEnabled: true,
    volume: 1,
    audioFullScreen: true,
    preload: 'auto'
});

myPlayerData = $(jPlayerid).data("jPlayer");

myControl.progress.slider({
    animate: "fast",
    max: 100,
    range: "min",
    step: 0.1,
    value : 0,
    slide: function(event, ui) {
        var sp = myPlayerData.status.seekPercent;
        if(sp > 0) {
            // Apply a fix to mp4 formats when the Flash is used.
            if(fixFlash_mp4) {
                ignore_timeupdate = true;
                clearTimeout(fixFlash_mp4_id);
                fixFlash_mp4_id = setTimeout(function() {
                    ignore_timeupdate = false;
                },1000);
            }
            // Move the play-head to the value and factor in the seek percent.
            $(jPlayerid).jPlayer("playHead", ui.value * (100 / sp));
        } else {
            // Create a timeout to reset this slider to zero.
            setTimeout(function() {
                myControl.progress.slider("value", 0);
            }, 0);
        }
    }
});

// Create the volume slider control
myControl.volume.slider({
    animate: "fast",
    max: 1,
    range: "min",
    step: 0.01,
    value : myPlaylist.options.volume,
    slide: function(event, ui) {
        $(jPlayerid).jPlayer("option", "muted", false);
        $(jPlayerid).jPlayer("option", "volume", ui.value);
    }
});

$("#jplayer_inspector").jPlayerInspector({jPlayer:$(jPlayerid)});

//Socket Event
socket
.on('call-screening-room', (res) => {//เรียกคิวคัดกรอง /kiosk/calling/screening-room
    if(parseInt(model.section) === res.section.sec_id){//เช็คแผนกที่เรียกกับแผนกโปรแกรมเสียง
        $.each(res.sound, function( index, sound ) {
            myPlaylist.add({
                title: res.data.qnumber,
                artist: res,
                wav: sound
            });
        });
        $(jPlayerid).jPlayer("play");
    }
})
.on('call-examination-room', (res) => {//เรียกคิวห้องตรวจ /kiosk/calling/examination-room
    if(parseInt(model.section) === res.section.sec_id){//เช็คแผนกที่เรียกกับแผนกโปรแกรมเสียง
        $.each(res.sound, function( index, sound ) {
            myPlaylist.add({
                title: res.data.qnumber,
                artist: res,
                wav: sound
            });
        });
        $(jPlayerid).jPlayer("play");
    }
})
.on('call-blooddrill-room', (res) => {//เรียกคิวห้องเจาะเลือด /kiosk/calling/examination-room
    if(parseInt(model.section) === res.section.sec_id){//เช็คแผนกที่เรียกกับแผนกโปรแกรมเสียง
        $.each(res.sound, function( index, sound ) {
            myPlaylist.add({
                title: res.data.qnumber,
                artist: res,
                wav: sound
            });
        });
        $(jPlayerid).jPlayer("play");
    }
});

qFunc = {
    updateStatus: function(ids){
        $.ajax({
            method: "GET",
            url: "/kiosk/calling/update-status",
            data: {ids:ids},
            dataType: "json",
            success: function(res){
                if(res.status == 200){
                    toastr.success('Update Status Completed!', 'Success!', {timeOut: 5000,positionClass: "toast-top-right",});
                }else{
                    swal({
                        type: 'error',
                        title: 'เกิดข้อผิดพลาด!!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error:function(jqXHR, textStatus, errorThrown){
                swal({
                    type: 'error',
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    },
    autoloadMedia: function(){
        $.ajax({
            method: "GET",
            url: "/kiosk/calling/autoload-media",
            data: {section: model.section},
            dataType: "json",
            success: function(res){
                if(res.status == 200 && res.rows.length){
                    $.each(res.rows, function( index, data ) {
                        if(data.model.service_sec_id === model.section){
                            $.each(data.sound, function( i, sound ) {
                                myPlaylist.add({
                                    title: data.model.q_num,
                                    artist: data,
                                    wav: sound
                                });
                            });
                        }
                    });
                    $(jPlayerid).jPlayer("play");
                }
            },
            error:function(jqXHR, textStatus, errorThrown){
                swal({
                    type: 'error',
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    },
    init: function(){
        var self = this;
        self.autoloadMedia();
    }
};
qFunc.init();

JS
);
?>
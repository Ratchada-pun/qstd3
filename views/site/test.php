<?php
use homer\widgets\Panel;
use homer\widgets\Nestable;
use yii\bootstrap\BootstrapAsset;
/* @var $this yii\web\View */

$this->title = \Yii::$app->keyStorage->get('app-name', Yii::$app->name);
?>
<?php /*
<div class="row">
    <div class="col-lg-4 col-xs-6">
        <?php
        Panel::begin([
            "header" => "Expandable",
            "expandable" => true,
            "removable" => true,
            "footer"=>'footer content'
        ])
        ?>
            <p>The body of the box</p>
        <?php Panel::end() ?>
    </div>

    <div class="col-lg-4 col-xs-6">
        <?php
        Panel::begin([
            "header" => "Expandable",
            "expandable" => true,
            "removable" => true,
            "footer"=>'footer content',
            "headingOptions" => [
                'class' => 'panel-heading hbuilt'
            ]
        ])
        ?>
            <p>The body of the box</p>
        <?php Panel::end() ?>
    </div>

    <div class="col-lg-4 col-xs-6">
        <?php
        Panel::begin([
            "header" => "Expandable",
            "expandable" => true,
            "removable" => true,
            "footer"=>'footer content',
            "headingOptions" => [
                'class' => 'panel-heading hbuilt'
            ],
            'options' => ['class' => 'hblue']
        ])
        ?>
            <p>The body of the box</p>
        <?php Panel::end() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        echo Nestable::widget([
            'type' => Nestable::TYPE_WITH_HANDLE,
            'modelOptions' => [
                'name' => 'name'
            ],
            'pluginEvents' => [
                'change' => 'function(e) {}',
            ],
            'pluginOptions' => [
                'maxDepth' => 7,
            ],
            'items' => [
                ['content' => 'Item # 1', 'id' => 1,'icon' => 'fa fa-users'],
                ['content' => 'Item # 2', 'id' => 2],
                ['content' => 'Item # 3', 'id' => 3],
                ['content' => 'Item # 4 with children', 'id' => 4, 'children' => [
                    ['content' => 'Item # 4.1', 'id' => 5],
                    ['content' => 'Item # 4.2', 'id' => 6],
                    ['content' => 'Item # 4.3', 'id' => 7],
                ]],
            ],
        ]);        
        ?>
    </div>
</div>
*/
?>
<div id="jquery_jplayer_1"></div>
<button type="button" class="btn btn-info" id="add">Add</button>
<button type="button" class="btn btn-info" id="play">Play</button>
<button type="button" class="btn btn-info" id="pause">Pause</button>
<button type="button" class="btn btn-info" id="previous">Previous</button>
<button type="button" class="btn btn-info" id="destroy">Destroy</button>
<button type="button" class="btn btn-info" id="stop">Stop</button>
<?php
$this->registerJsFile(
    '@web/vendor/jPlayer/dist/jplayer/jquery.jplayer.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile(
    '@web/vendor/jPlayer/dist/add-on/jplayer.playlist.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJs(<<<JS
// var myPlaylist = $("#jquery_jplayer_1").jPlayer({
//     swfPath: "/js",
//     supplied: "wav",
//     playlistOptions: {
//         autoPlay: true,
//         enableRemoveControls: true
//     },
// });
var media = [
    {
        wav: "/media/Prompt2/Prompt2_Number.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_A.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_0.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_0.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_1.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_Service2.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_10.wav",
    },
    {
        wav: "/media/Prompt2/Prompt2_Sir.wav",
    },
];
var i = 1;
var myPlaylist = new jPlayerPlaylist({
    jPlayer: "#jquery_jplayer_1",
}, media, {
    playlistOptions: {
        autoPlay: true,
        enableRemoveControls: true
    },
    supplied: "wav, mp3",
    keyEnabled: true,
    volume: 0.8,
    loop: false,
    defaultPlaybackRate: 1,
    loadstart: function (event) {
        console.log('loadstart');
    },
    ended: function (event) {
        console.log(myPlaylist.playlist[myPlaylist.current-1]);
        if((myPlaylist.current + 1) === myPlaylist.playlist.length){
            myPlaylist.remove();
        }
    },
    error: function (event) {
        //console.log('error');
    },
    repeat: function(event) {
    },
});
$('#add').on('click',function(){
    if($("#jquery_jplayer_1").jPlayer("getData","diag.isPlaying") == true){
        console.log(this);
    }else{
        console.log('stop');
    }
    $.each(["Prompt2_1.wav", "Prompt2_2.wav", "Prompt2_3.wav" ], function( index, value ) {
        myPlaylist.add({
            wav: "/media/Prompt2/" + value
        });
    });
});
$('#pause').on('click',function(){
    myPlaylist.pause();
});
$('#play').on('click',function(){
    myPlaylist.play(0);
});
$('#previous').on('click',function(){
    myPlaylist.previous();
});
$('#destroy').on('click',function(){
    $("#jquery_jplayer_1").jPlayer("destroy");
});
$('#stop').on('click',function(){
    $("#jquery_jplayer_1").jPlayer("stop");
});
$("#repeat-on").click( function() {
  $("#jquery_jplayer_1").bind($.jPlayer.event.ended + ".jp-repeat", function(event) { // Using ".jp-repeat" namespace so we can easily remove this event
    $(this).jPlayer("play"); // Add a repeat behaviour so media replays when it ends. (Loops)
  });
  return false;
});
$('#jquery_jplayer_1').bind($.jPlayer.event.play, function(event) {
    console.log($.jPlayer.event.play);
    if (event.jPlayer.status.currentTime>0 && event.status.paused===false) {
        console.log(this);
    }
});

JS
);
?>
<?php
use homer\assets\ToastrAsset;
ToastrAsset::register($this);

use homer\assets\SocketIOAsset;

SocketIOAsset::register($this);
$this->title = 'ตั้งค่า';

?>
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
  .checkbox label:after, 
    .radio label:after {
        content: '';
        display: table;
        clear: both;
    }

    .checkbox .cr,
    .radio .cr {
        position: relative;
        display: inline-block;
        border: 1px solid #a9a9a9;
        border-radius: .25em;
        width: 1.3em;
        height: 1.3em;
        float: left;
        margin-right: .5em;
    }

    .radio .cr {
        border-radius: 50%;
    }

    .checkbox .cr .cr-icon,
    .radio .cr .cr-icon {
        position: absolute;
        font-size: .8em;
        line-height: 0;
        top: 50%;
        left: 20%;
    }

    .radio .cr .cr-icon {
        margin-left: 0.04em;
    }

    .checkbox label input[type="checkbox"],
    .radio label input[type="radio"] {
        display: none;
    }

    .checkbox label input[type="checkbox"] + .cr > .cr-icon,
    .radio label input[type="radio"] + .cr > .cr-icon {
        transform: scale(3) rotateZ(-20deg);
        opacity: 0;
        transition: all .3s ease-in;
    }

    .checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
    .radio label input[type="radio"]:checked + .cr > .cr-icon {
        transform: scale(1) rotateZ(0deg);
        opacity: 1;
    }

    .checkbox label input[type="checkbox"]:disabled + .cr,
    .radio label input[type="radio"]:disabled + .cr {
        opacity: .5;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="hpanel">
            <?= $this->render('_tabs'); ?>
        </div>
    </div>
</div>
<?php
echo $this->render('modal');
$this->registerJs(<<<JS
window.socket = socket 
$('body').addClass('hide-sidebar');
Events = {
    toggle: function(elm,state,action){
        var status;
        if(state){
            status = 1;
        }else{
            status = 0;
        }
        $.ajax({
            method: "POST",
            url: "/app/settings/" + action,
            data: {
                status: status,
                key: $(elm).data('key')
            },
            dataType: "json",
            success: function(res){
                //api.ajax.reload();
                toastr.success('', 'Success!', {timeOut: 3000,positionClass: "toast-top-right"});
            },
            error: function(jqXHR, textStatus, errorThrown){
                swal('Oops...',errorThrown,'error');
            }
        });
    }
};
JS
);

?>
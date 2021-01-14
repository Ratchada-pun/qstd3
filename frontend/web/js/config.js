$(function() {
    //แจ้งเตือนหน้าเรียกคิว
    var disablealert = localStorage.getItem('disablealert-pagecalling');
    if(disablealert == 'on'){
        $('#input-disable-alert').bootstrapToggle('on');
    }else if(disablealert == 'off'){
        $('#input-disable-alert').bootstrapToggle('off');
    }else if(disablealert == null){
        $('#input-disable-alert').bootstrapToggle('on');
        localStorage.setItem('disablealert-pagecalling','on');
    }
    $('#input-disable-alert').on('change', function(event){
        if($(this).is(':checked')){
            localStorage.setItem('disablealert-pagecalling','on');
        }else{
            localStorage.setItem('disablealert-pagecalling','off');
        }
    });
    //เสียงเตือนหน้าเรียกคิว
    var playsound = localStorage.getItem('playsound-pagecalling');
    if(playsound == 'on'){
        $('#input-sound-alert').bootstrapToggle('on');
    }else if(playsound == 'off'){
        $('#input-sound-alert').bootstrapToggle('off');
    }else if(playsound == null){
        $('#input-sound-alert').bootstrapToggle('on');
        localStorage.setItem('playsound-pagecalling','on');
    }
    $('#input-sound-alert').on('change', function(event){
        if($(this).is(':checked')){
            localStorage.setItem('playsound-pagecalling','on');
        }else{
            localStorage.setItem('playsound-pagecalling','off');
        }
    });
});
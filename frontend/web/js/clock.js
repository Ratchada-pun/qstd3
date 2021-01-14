$(document).ready(function(){
    if($(".clock")[0]){
        var a = new Date;
        a.setDate(a.getDate()),
        setInterval(function(){
            var a=(new Date).getSeconds();
            $(".time__sec").html((a<10?"0":"")+a)
        },1e3),
        setInterval(function(){
            var a=(new Date).getMinutes();
            $(".time__min").html((a<10?"0":"")+a)
        },1e3),
        setInterval(function(){
            var a=(new Date).getHours();
            $(".time__hours").html((a<10?"0":"")+a)
        },1e3)
    }
});
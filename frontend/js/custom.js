var audioNotification = new Audio('frontend/audio/definite.ogg');
var audioError = new Audio('frontend/audio/system-fault.ogg');
function notifikasi(text, notiftype) {
    if (notiftype === undefined) notiftype = 'info';
    $.notify(
        {
            // options
            message: text,
        },
        {
            // settings
            allow_dismiss: true,
            newest_on_top: false,
            type: notiftype,
            timer: 3000,
            z_index: 2031
        }
    );
    if (notiftype == "danger") {
        audioError.play();
    } else {
        audioNotification.play();
    }
}

function loadPage(path){
    if(path===undefined){
        console.log("ERROR: loadPage require path");
    }else if(path==""){
        console.log("ERROR: loadPage path cannot be empty");
    }else{
        $.ajax({
            url:path,
            type:'GET',
            beforeSend:function(){
                loadingVeil(true);
            },
            success:function(result){
                $('#page-content').html(result);
            },
            error:function(err){
                notifikasi("AJAX:Gagal memuat halaman","danger");
            },
            complete:function(){
                loadingVeil(false);
            }
        });
    }
}

function loadingVeil(open){
    if(open===undefined)open=true;
    if(open){
        $('#loading-veil').fadeIn();
    }else{
        $('#loading-veil').fadeOut();
    }
}
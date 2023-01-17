var alert = function (message='', title= '', time=null){
    $("#modal-for-all-message #title").html(title);
    $("#modal-for-all-message #message").html(message);
    $("#modal-for-all-message").css('display','block');
    $("body").addClass('overflow-hidden');
    $('#modal-for-all-message').show()
    if(time != null){
        setTimeout( function() {
                $('#modal-message-general-class').text('')
                $('.btn-close').click();
            },
        time);
    }
}

var alert2 = function (message='', keyword= '', image='', link=''){
    $("#success_alert_modal #keyword").html(keyword);
    $("#success_alert_modal #message").html(message);
    $("#success_alert_modal #image").attr('src', image);
    // $("#success_alert_modal #link").attr('href', '/cart');
    $("#success_alert_modal #link").val(link);
    $("#success_alert_modal").css('display','block');
    $("body").addClass('overflow-hidden');
    $('#success_alert_modal').show()
}
export default{
    alert,alert2
}

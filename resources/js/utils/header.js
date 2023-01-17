// $(document).on('mouseover' ,'.profile-flex' , function(){
//     $(".pop-over").removeClass('active');
//     $(".profile-dd").toggleClass('active');
//     $(".overlay-primary").toggleClass('active');
//     $("body").addClass('overflow-hidden');
// });

$(document).on('click' ,'.profile-flex' , function(){
    $(".pop-over").removeClass('active');
    $(".profile-dd").toggleClass('active');
    $(".overlay-primary").toggleClass('active');
    $("body").addClass('overflow-hidden');
});

// $(document).on('mouseover' ,'.wrapper-notif' , function(){
//     $(".pop-over").removeClass('active');
//     $(".notif-dd").toggleClass('active');
//     $(".overlay-primary").addClass('active');
//     $("body").addClass('overflow-hidden');
// });

$(document).on('click' ,'.wrapper-notif' , function(){
    $(".pop-over").removeClass('active');
    $(".notif-dd").toggleClass('active');
    $(".overlay-primary").toggleClass('active');
    $("body").addClass('overflow-hidden');
});

$(document).on('click' ,'.select-box' , function(){
    $(".options-container").toggleClass('active');
    $(".select-box").toggleClass('active');
});

$(document).on('mouseleave','.notif', '.profile-flex', function () {
    $(".pop-over").removeClass('active');
    $("body").removeClass('overflow-hidden');
    $(".autocomplete").removeClass('active');
});

$(document).on('click' ,'.overlay-primary' , function(e){
    e.preventDefault();
    $(".pop-over").removeClass('active');
    $("body").removeClass('overflow-hidden');
    $(".autocomplete").removeClass('active');
    $(".hd-slider-filter").removeClass('show');
    $(".courier-expand").removeClass('show');
    $(".payment-method").removeClass('show');
});

$(document).on('click' ,'.close-modal, .cont-shopping'  , function(){
    $(this).closest('.modal').fadeOut();
    $("body").removeClass('overflow-hidden');
});

$(document).on('click' ,'.hotdeal-searching' , function(){      
    $(".autocomplete").addClass('active'); 
    $(".overlay-primary").addClass('active');
    $("body").addClass('overflow-hidden');
});

$(document).on('click' ,'.searching-desktop' , function(){      
    $(".autocomplete").addClass('active'); 
    $(".overlay-primary").addClass('active');
    $("body").addClass('overflow-hidden');
});

$(document).on('click', '.link', function () {
    $(".pop-over").removeClass('active');
    $("body").removeClass('overflow-hidden');
    $('#modal_search').removeClass('active');
});

$(document).on('click', '.var-arrow', function () {
    $(".var-cont").toggleClass('active');
});

$(document).on('click' ,'.toggle-filter' , function(){
    $(".small-box-filter").toggleClass('active');
    $(".overlay-transparent").toggleClass('active');
});

$(document).on('click' ,'.overlay-transparent' , function(){
    $(".small-box-filter").removeClass('active');
    $(".overlay-transparent").removeClass('active');
});

$(document).on('click' ,'.small-box-filter > li' , function(){
    $(".small-box-filter").removeClass('active');
    $(".overlay-transparent").removeClass('active');
});

$(document).on('click' ,'.trigger' , function(){
    $(".slider-filter").toggleClass('show');
    $(".slider-filter").addClass('overflow-scroll');
    $("body").addClass('overflow-hidden');
});

$(document).on('click' ,'.btn-hd-filter' , function(){
    $(".hd-slider-filter").addClass('show');
    $(".overlay-primary").addClass('active');
    $("body").addClass('overflow-hidden');
});

$(document).on('click' ,'.close-div', function(){
    $('.hd-slider-filter').removeClass('show');
    $(".overlay-primary").removeClass('active');
    $("body").removeClass('overflow-hidden');

    let data_id = $(this).attr('data-id')
    var windowsize = window.innerWidth;
    if (windowsize <= 540) {
        $(".toggle-expand-" + data_id).removeClass('show');

        let check = $(".payment-method").hasClass('show');
        if(!check){
            $(".payment-method").removeClass('show');
            $(".overlay-primary").removeClass('active');
            $("body").removeClass('overflow-hidden');
        } else {
            $(".payment-method").addClass('show');
            $(".overlay-primary").addClass('active');
            $("body").addClass('overflow-hidden');
        }
    }
});

$(document).on('click' ,'.menu-dropdown', function(){
    $('.sub-menu-mobile').toggleClass("active" );
    $('.arrow').toggleClass("open");
});

$(document).on('click' ,'.select-courier', function(){
    let data_id = $(this).attr('data-id')
    var windowsize = window.innerWidth;
    if (windowsize <= 540) {
        let check = $(".toggle-expand-" + data_id).hasClass('show');
        if(!check){
            $(".toggle-expand-" + data_id).addClass('show');
            $(".overlay-primary").addClass('active');
            $("body").addClass('overflow-hidden');
        } else {
            $(".toggle-expand-" + data_id).removeClass('show');
            $(".overlay-primary").removeClass('active');
            $("body").removeClass('overflow-hidden');
        }
    } else {
        $(".toggle-expand-" + data_id).toggleClass('show');
        $(".dropdown.courier-" + data_id).toggleClass('rotate');
    }
});

$(document).on('click' ,'.select-pay', function(){
    var windowsize = window.innerWidth;
    if (windowsize <= 540) {
        let check = $(".payment-method").hasClass('show');
        if(!check){
            $(".payment-method").addClass('show');
            $(".overlay-primary").addClass('active');
            $("body").addClass('overflow-hidden');
        } else {
            $(".payment-method").removeClass('show');
            $(".overlay-primary").removeClass('active');
            $("body").removeClass('overflow-hidden');
        }
    }  else {
        $(".payment-method").toggleClass('show');
        $(".dropdown.pay").toggleClass('rotate');
    }
});

$(document).on('click', '.hotdeal-searching', function () {
    var windowsize = window.innerWidth;
    if (windowsize <= 540) {
        $('#modal_search').addClass('active');
        $("#searching-mobile").focus();
    } else {
        return false;
    }
});

$(document).on('click', '.icon-link', function(e){
	e.preventDefault();
	if($(this).hasClass("open"))
	{   
        $(this).removeClass("open");
        $(this).parent().children("ul").stop(true,true).slideUp("normal");
	} else {
        $(".icon-link").removeClass("open");
        $(this).addClass("open");
        $(".sub").filter(":visible").slideUp("normal");
        $(this).parent().children("ul").stop(true,true).slideDown("normal");
	}
});

$(document).on('click', '.header', function(e){
    if (e.target !== this)
        return;
    $(".overlay-primary").click();
})

window.innerWidth

window.onscroll = function() {
    // scrollFunction();
    scrollFunctionBtn();
    scrolltoTopBtn();
};

function scrollFunction() {
    if (window.innerWidth <= 540){
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
            $('header').addClass('shadow');
        } else {
            $('header').removeClass('shadow');
        }
    }
}

function scrollFunctionBtn() {
    if (window.innerWidth <= 540){
        if (($(document).height() - $(window).height()) - $(window).scrollTop() <= 1000) {
            $('#floating_btn').css("display", "none");
        }else{
            $('#floating_btn').css("display", "flex");

        }
    }
}

function scrolltoTopBtn() {
    if (window.innerWidth <= 540){
        if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 100) {
            $('#scrollToTop').addClass('active');
        } else {
            $('#scrollToTop').removeClass('active');
        }
    }
}

$(document).on('click', '.hotdeal-searching', function () {
    var windowsize = window.innerWidth;
    if (windowsize <= 540) {
        $('#modal_search').addClass('active');
        $("#searching-mobile").focus();
    } else {
        return false;
    }
});

$(document).on('click', '.profile-flex', function () {
    var windowsize = window.innerWidth;

    if (windowsize <= 540) {
        $('.side-menu-mobile').toggleClass('active');
        $(".overlay-primary-mobile").toggleClass('active');
        $('.profile-dd').removeClass('active'); 
        $(".overlay-primary").removeClass('active');
    }
    return false;
});

$(document).on('click' ,'#close_side_menu , .overlay-primary-mobile', function(){
    $('.pop-over').removeClass('active');
    $("body").removeClass('overflow-hidden');
    
});

$(document).on('click', '.show-password', function () {
    var _this = $(this).parent('div').find(".pin-input").attr('type');
    if(_this == 'text'){
        $(this).parent('div').find(".pin-input").attr('type','password');
    }else{
        $(this).parent('div').find(".pin-input").attr('type','text');
    }
});

$(document).on('click', '.menu li:has(ul)', function() {
    e.preventDefault();

    if($(this).hasClass('activado')) {
        $(this).removeClass('activado');
        $(this).children('ul').fadeIn();
    } else {
        $('.menu li ul').fadeOut();
        $('.menu li').removeClass('activado');
        $(this).addClass('activado');
        $(this).children('ul').fadeOut();
    }

    $('menu li ul li a', 'click', function(){
        window.location.href = $(this).attr('href');
    })

});

$(document).on('click', '.search-popular.btn-link', function (){
    $(".overlay-primary").click();
})

$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});

$(document).on('keyup', '.password', function(){
    var password = $(this).val();
    if(password.length > 0 ){
        $(".showhide").show();
    }else{
        $(".showhide").hide();
    }
});

$(document).on('keyup', '.password-confirm', function(){
    var password = $(this).val();
    if(password.length > 0 ){
        $(".showhide-confirm").show();
    }else{
        $(".showhide-confirm").hide();
    }
});

$(document).on('keyup', '.password-new', function(){
    var password = $(this).val();
    if(password.length > 0 ){
        $(".showhide-new").show();
    }else{
        $(".showhide-new").hide();
    }
});

// $(document).on('keyup', '.search', function(){
//     var search = $(this).val();
//     if(search.length > 0 ){
//         $(".delsearch").show();
//     }else{
//         $(".delsearch").hide();
//     }
// });

$(document).on('keyup', '.input-custom', function(){
    var password = $(this).val();
    if(password.length > 0 ){
        $(".btn-check-voucher").addClass('active');
    }else{
        $(".btn-check-voucher").removeClass('active');
    }
});

$(document).on('click', '#hide_button', function(){
    $('.floating-btn-chat').addClass("transform-css" );
});
// $(document).on('focusin', '.input-custom', function(){
//     var windowsize = window.innerWidth;
//     if (windowsize <= 540) {
//         $('.container-cookie').hide();
//         $('.floating-btn-chat').hide();
//         $('.bottom-menu').hide();
//     }
//     return false;
// });

// $(document).on('focusout', '.input-custom', function(){
//     var windowsize = window.innerWidth;
//     if (windowsize <= 540) {
//         $('.container-cookie').show();
//         $('.floating-btn-chat').show();
//         $('.bottom-menu').show();
//     }
//     return false;
// });


// var inptxt = $('#in_out_input');
// $(inptxt).on('focusin', 
//    function(){
//     $('.container-cookie').addClass('active');
//    }).on('focusout', function(){
//     $('.container-cookie').removeClass('active');
//   });

$(document).ready(function(){
    $(".input-custom").focusin(function (){
        var windowsize = window.innerWidth;
        if (windowsize <= 540) {
            $('.container-cookie').hide();
            $('.floating-btn-chat').hide();
            $('.bottom-menu').hide();
        }
    })
})

$(document).ready(function(){
    $(".input-custom").focusout(function (){
        var windowsize = window.innerWidth;
        if (windowsize <= 540) {
            $('.container-cookie').show();
            $('.floating-btn-chat').show();
            $('.bottom-menu').show();
        }
    })
})

// $('.summernote').summernote({
//     toolbar: [
//         ['style', ['bold', 'italic', 'underline']],
//         ['fontsize', ['fontsize']],
//         ['color', ['color']],
//         ['para', ['ul', 'ol', 'paragraph']]
//     ],
//      height:150
// });

// $('.note-editable').css('font-size','18px');
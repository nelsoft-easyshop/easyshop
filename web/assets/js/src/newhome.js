(function ($) {

    $(".btn-add-cart").on("click", function(){
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var $button = $(this);
        var productId = $button.data('productid');
        var slug = $button.data('slug');
         
        $.ajax({
            type: "POST",
            url: "cart/doAddItem", 
            dataType: "json",
            data: "express=true&"+csrfname+"="+csrftoken+"&productId="+productId,
            success: function(result) {
                if(!result.isLoggedIn){
                    window.location.replace("/login");
                }
                else if(result.isSuccessful){
                    window.location.replace("/cart");
                }
                else{
                    window.location.replace("/item/"+slug);
                }
            }, 
        });
    });
    

    var mainwindowsize = $(window).width();

    $(window).on('load',function() {
        mainwindowsize = $(window).width();
        if (mainwindowsize < 565) {
            $('.bxslider').bxSlider({
                minslides : 1,
                maxSlides: 1,
                moveSlides: 1,
                speed: 1000,
                pause: 6000,
                prevText : '',
                nextText : '',
                touchEnabled : true,
                infiniteLoop: true
            });
        }

        else {
            $('.bxslider').bxSlider({
                minslides : 1,
                maxSlides: 2,
                moveSlides: 1,
                speed: 1000,
                auto: true,
                pause: 6000,
                prevText : '',
                nextText : '',
                slideMargin : 10,
                touchEnabled : true,
                infiniteLoop: true
            });
        }
    });
    
    

    // $('#content .bx-wrapper').removeAttr("style");
    // $('#content .bx-wrapper ul li').removeAttr("style");

        
    $(window).on('load resize', function() {
        var windowWidth = $(window).width(),
        bxSliderWidth = $('#bxslider').width(),
        bxSliderHeight = $('#bxslider').height(),
        shadowWidth = (windowWidth - bxSliderWidth) / 2 ;

        $('.left-side-shadow, .right-side-shadow').css({'width': shadowWidth, 'height': bxSliderHeight});

    });


}(jQuery));
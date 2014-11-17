

(function ($) {

    $(document).ready(function(){
        
        var leftpanel = $(".feed-left-panel");
        var midpanel = $(".feed-middle-panel");
        var rightpanel = $(".feed-right-panel");
        var pos = leftpanel.offset().top;  

            
        $(window).scroll(function() {
            var windowpos = $(window).scrollTop();

            if( (windowpos + $(window).height() + 255 >= $(document).height()) &&
                ($(document).height() > ($(window).height() + 151 )  )){
                //rightpanel.switchClass( "", "feed-pos-ab-bttm", 100, "swing" );
                //leftpanel.switchClass( "", "feed-pos-ab-bttm", 100, "swing" );        
                leftpanel.addClass('feed-left-panel-fix feed-pos-ab-bttm');
                midpanel.addClass('feed-middle-panel-fix');
                rightpanel.addClass('feed-right-panel-fix feed-pos-ab-bttm');
            }

            else if(windowpos >= pos){
                leftpanel.addClass('feed-left-panel-fix').removeClass('feed-pos-ab-bttm');
                midpanel.addClass('feed-middle-panel-fix');
                rightpanel.addClass('feed-right-panel-fix').removeClass('feed-pos-ab-bttm');
            }

            else{
               leftpanel.removeClass('feed-left-panel-fix feed-pos-ab-bttm');
               midpanel.removeClass('feed-middle-panel-fix feed-pos-ab-bttm');
               rightpanel.removeClass('feed-right-panel-fix feed-pos-ab-bttm');

            }
        });
        
        $(window).trigger(scroll);
        
    });
    
    
    
    
})(jQuery);




(function($){

    $('#feed-categories').on('mouseenter',function(){
        $('.feed-catlist-collapseable').show();
    });
    
    $('.feed-cat').on('mouseleave', function(){
        $('.feed-catlist-collapseable').hide();
    });
 
})(jQuery);

/* Click function for feed menu */
(function($){
    $('.feed-menu').on('click', function(e){
        var divId = $(this).children('a').attr('href');
        var staticFeaturedProduct = $('div.product.feature.media');
        
        $('.feed-menu').removeClass('active');
        $(this).addClass('active');
        
        $('.feed-prod-cont').hide();
        $(divId).show();
        
        
        if( divId === "#m_follow_seller" ){
            staticFeaturedProduct.hide();
        }else{
            staticFeaturedProduct.show();
        }
        
        e.preventDefault();
    });
})(jQuery);

/* Load More */
(function($){
    $('.feed_load_more').on('click',function(){
        var thisbtn = $(this);
        var form = $(this).siblings('form.load_more_form');
        var parentDiv = $(this).closest('div.load_more_div');
        var pageField = form.children('input[name="feed_page"]');
        var pageNum = parseInt(pageField.val());
        
        thisbtn.attr('disabled',true);
        thisbtn.val("Loading...");
        $.post("/home/getMoreFeeds", $(form).serializeArray(), function(data){
            try{
                var obj = jQuery.parseJSON(data);
            }
            catch(e){
                alert('Failed to retrieve product list.');
                return false;
            }
            
            if( typeof obj.error != "undefined" ){
                thisbtn.replaceWith('<span>' + obj.error + '</span>');
                return false;
            }
            else if(obj.view==""){
                thisbtn.replaceWith('<span>End of list reached.</span>');
                return false;
            }
            
            if( typeof obj.fpID != "undefined" ){
                form.find('[name="ids"]').val(obj.fpID);
            }
            
            parentDiv.before(obj.view);
            thisbtn.attr('disabled',false);
            thisbtn.val("Load More");

            var y = $(window).scrollTop();  
            $(window).scrollTop(y+1);
            
        });
        
        pageField.val(pageNum+1);
    });
})(jQuery);


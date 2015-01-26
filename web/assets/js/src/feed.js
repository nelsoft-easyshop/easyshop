

(function ($) {

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

var $window = $(window);
$window.on('load', function() {
    var windowsHeight = $(window).height();
    var rightpanelHeight = $("#feed-right-panel").height();
    var rightpanelTop = (windowsHeight - rightpanelHeight);

    var leftpanelHeight = $("#feed-left-panel").height();
    var leftpanelTop = (windowsHeight - leftpanelHeight);

    if (rightpanelHeight > windowsHeight) {
        $.stickysidebarscroll("#feed-right-panel",{offset: {top: rightpanelTop, bottom: 193}});
    }
    else {
        $.stickysidebarscroll("#feed-right-panel",{offset: {top: 0, bottom: 193}});
    }

    if (leftpanelHeight > windowsHeight) {
        $.stickysidebarscroll("#feed-left-panel",{offset: {top: leftpanelTop, bottom: 193}});
    }
    else {
        $.stickysidebarscroll("#feed-left-panel",{offset: {top: 0, bottom: 180}});
    }
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


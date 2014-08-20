/* DROP DOWN FOR CATEGORIES */
$(function(){

    $('#feed-categories').on('mouseenter',function(){
        $('#feed-catlist').show();
    });
    
    $('.feed-cat').on('mouseleave', function(){
        $('#feed-catlist').hide();
    });

})

/* Click function for feed menu */
$(function(){	
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
});

/* Load More */
$(function(){
    $('.feed_load_more').on('click',function(){
        var thisbtn = $(this);
        var form = $(this).siblings('form.load_more_form');
        var parentDiv = $(this).closest('div.load_more_div');
        var pageField = form.children('input[name="feed_page"]');
        var pageNum = parseInt(pageField.val());
        
        thisbtn.attr('disabled',true);
        thisbtn.val("Loading...");
        $.post(config.base_url+"home/getMoreFeeds", $(form).serializeArray(), function(data){
            try{
                var obj = jQuery.parseJSON(data);
            }
            catch(e){
                alert('Failed to retrieve product list.');
                return false;
            }
            
            if(obj.view==""){
                thisbtn.replaceWith('<span>End of list reached.</span>');
                return false;
            }
            
            if( typeof obj.fpID != "undefined" ){
                form.find('[name="ids"]').val(obj.fpID);
            }
            
            parentDiv.before(obj.view);
            thisbtn.attr('disabled',false);
            thisbtn.val("Load More");
        });
        
        pageField.val(pageNum+1);
    });
})
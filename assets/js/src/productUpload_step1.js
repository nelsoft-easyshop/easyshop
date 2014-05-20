 $(document).ready(function() { 

    var globalParent;
    var globalLevel; 
    var scrollEvent = {
        bool: false,
        scrollLeft: 0,
        reset : function (){
            this.bool = 0;
            this.scrollLeft = 0;
        }
    };
    
    var focuslevel = 3;
    var maxscroll = 0;
    var cLevel = 0;
    var draftCount = $('.draftCount').val();
    
    $('.div_draft').hide();

    $(document).on('click','.draft_name',function () {
        var pid = $(this).data('pid');
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "p_id").val(pid);
        $('#draft_form').append($(input));
        $('#draft_form').submit();
    }); 

    $(document).on('click','.draft_remove',function () {
        var pid = $(this).data('pid');
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var action = "sell/draft/remove"; 

        if(confirm("Are you sure you want to permanently removed this draft entry?")){
            var request = $.ajax({
                async: false,
                type: "POST",
                url: config.base_url + action,
                data: "p_id=" + pid + "&"+csrfname+"=" + csrftoken,
                dataType: "json",
                cache: false,
                success: function(d) { 
                    if(d.e == 1){
                        $('.simplemodal-container  .draftitem'+pid).remove();
                        draftCount -= 1; 
                        $('.draft-cnt').html(draftCount);
                        if(draftCount <= 0){
                            $('.div_draft').html('<h3>Draft Item(s) </h3><div class="draft_items_container"><br/><strong>There are no items in your draft list.</strong><br/></div>');  
                        }
                    }else{
                        alert(d.m);
                    }
                }
            });
        }
    });
    
    $(document).on('click','.show_draft_link',function () {
        $('.div_draft').modal({
            escClose: false,
            containerCss:{
                maxWidth: 900,
                minWidth: 605,
                maxHeight: 600,
            },
            persist: true
        });
        $('#simplemodal-container').addClass('draft_container');
    });

    $('.jcarousel').bind("scroll", ScrollOnLoad);
        setTimeout(UnbindScroll, 150); 
        function ScrollOnLoad() {
            UnbindScroll();
            $('.jcarousel').scrollLeft(0);
    }

    function UnbindScroll() {
        $('.jcarousel').unbind("scroll", ScrollOnLoad);
    }

    $(document).on('click','.product-list li a',function () { 
        $(this).addClass('active').parent().siblings().children('a').removeClass('active');
    });

    $(document).on('click','.navList li a',function () { 
        $(this).addClass('active').parent().siblings().children('a').removeClass('active');
    });

    $(document).on('keyup','#box',function () {
        var valThis = $(this).val().toLowerCase();
        $('.navList > li').each(function() {
            var text = $(this).text().toLowerCase();
            if(text.indexOf(valThis) != -1){
              $(this).show();
            }else{
                 $(this).hide();
            }
        });
    });

        $(document).on('keyup','.sub-box',function () {
        var valThis = $(this).val().toLowerCase();
        var cnt = $(this).data('cnt');
        $('.navList'+cnt+' > li').each(function() {
            var text = $(this).text().toLowerCase();
            if(text.indexOf(valThis) != -1){
              $(this).show();
            }else{
                 $(this).hide();
            }
        });
    });


    
     
    /*
     * requesting the child category from selected first level parent category
     */
    $(document).on('click','.select',function () { 
        $(".add_category_submit").empty();
        var D = eval('(' + $(this).attr('data') + ')');
        var action = 'productUpload/getChild';
        var catId = D.cat_id;
        var level =  D.level;
        var name = D.name;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');

        $('.jcarousel-control-prev , .jcarousel-control-next').addClass('inactive'); 
        maxscroll = 0;
        focuslevel = 3; 
        cLevel = 0;
        $(".jcarousel").animate({scrollLeft: 0}, 0);
        $(".product_sub_category .product_sub_items0").nextAll().remove();
        $(".product_sub_category .product_sub_items0").remove();
        if($('#storeValue .parent'+catId).length == 0) {
            $.ajax({
                onLoading:jQuery(".sub_cat_loading_container").html('<img src="'+config.base_url+'assets/images/orange_loader.gif" />').show(),
                async: false,
                type: "POST",
                url: config.base_url + action,
                data: "cat_id=" + catId + "&level=" + level + "&name=" + name + "&"+csrfname+"=" + csrftoken,
                dataType: "json",
                cache: false,
                success: function(d) {
                    $(".product_sub_category").append(d);
                    $("#storeValue").append(d);
                    jQuery(".sub_cat_loading_container").fadeOut(600);
                }
            });
        }else{
             
                $(".add_category_submit").empty();
                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+name+'">');
           
           var clone = $('#storeValue .parent'+catId).clone();
           $('.product_sub_category').append(clone);
       }
   });
        
       
    /*
     * requesting the child category from non-main selected category
     */
    $(document).on('click','.child',function () { 
        var D = eval('(' + $(this).attr('data') + ')');
        var nlevel = parseInt(D.level) + 1;
        var action = 'productUpload/getChild';
        var catId = D.cat_id;
        var name = D.name;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $(".add_category_submit").empty();
        $(".product_sub_category .product_sub_items" + D.level).nextAll().remove(); 
        $(".product_sub_category .product_sub_items" + nlevel).nextAll().remove(); 
        if($('#storeValue .parent'+catId).length == 0){
            $.ajax({
                async: false,
                type: "POST",
                url: config.base_url +  action,
                data: "cat_id=" + catId + "&level=" + nlevel + "&name=" + name + "&"+csrfname+"=" + csrftoken,
                dataType: "json",
                cache: false,
                onLoading:$(".sub_cat_loading_container").html('<img src="'+config.base_url+'assets/images/orange_loader.gif" />').show(),
                success: function(d) {
                    $(".product_sub_category").append(d);
                    $("#storeValue").append(d);
                    $(".sub_cat_loading_container").fadeOut(600);
                }
            });
        }else{ 
            var finalValue = $('#storeValue .parent'+catId).data('final');
            var finalValue = true;
            var clone = $('#storeValue .parent'+catId).clone();
            $('.product_sub_category').append(clone);
            if(finalValue == true){
                $(".add_category_submit").empty();
                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+name+'">');
            }
        } 
        if(nlevel > 2){
            if(nlevel == focuslevel){
                $('.jcarousel-control-prev').removeClass('inactive');
                var leftPos;
                if(scrollEvent.bool){
                    leftPos = scrollEvent.scrollLeft;
                    scrollEvent.scrollLeft += 200;
                }
                else{
                    leftPos = $('.jcarousel').scrollLeft();
                    scrollEvent.scrollLeft = 0;
                }
                $(".jcarousel").animate({scrollLeft: leftPos + 200}, 400);
                maxscroll = leftPos;
                focuslevel = focuslevel + 1;
            }   
            cLevel = nlevel;
        }   
    });

    $(document).on('click','.jcarousel-control-prev',function () {
        var leftPos = $('.jcarousel').scrollLeft();
        $(".jcarousel").animate({scrollLeft: leftPos - 200}, 400);
        if(focuslevel > 3){
            focuslevel = focuslevel - 1;
             $('.jcarousel-control-next').removeClass('inactive');
        }

        if(focuslevel == 3){
            $('.jcarousel-control-prev').addClass('inactive');
        }
    });

    $(document).on('click','.jcarousel-control-next',function () {
        $('.jcarousel-control-prev').removeClass('inactive');
        var leftPos = $('.jcarousel').scrollLeft();
        if(leftPos <= maxscroll){
        $(".jcarousel").animate({scrollLeft: leftPos + 200}, 400);
            focuslevel = focuslevel + 1;
        }
        if(leftPos == maxscroll){
             $(this).addClass('inactive');
        }
    });

    $(document).on('click','.othercategory_main a', function(){
        $(".select ").removeClass('active');
        $('.othercategory_main').empty();
        $(".product_sub_category .product_sub_items0").nextAll().remove();
        $(".product_sub_category .product_sub_items0").remove();
        $('.othercategory_main').append('<input type="text" id="otherNameCategory_main" style="background:none;padding:5px" placeholder="Add a category"  class="otherNameCategory_main" data-parentname="" data-level="" data-final="" autocomplete="off" name="othernamecategory" />');
        $('.othercategory_main .otherNameCategory_main').focus();
        $(".add_category_submit").empty();
    });
    
    $(document).on('blur change','#otherNameCategory_main',function () {
        var otherName = $(this).val();
        var finalValue = true;
        globalParent = 1;
        $(".add_category_submit").empty();
        if(otherName.length == 0){ 
            $('.otherNameCategory_main_li').empty();
          $('.otherNameCategory_main_li').append('<a href="javascript:void(0)" class="select2" data-level="0" data-parent="1" data-parentname="" data-final="true" style="color:#0191C8 !important;"><b class="add_cat span_bg"></b><b>Add a Category</b></a>');
           
        }else{  
            $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+escapeHtml(otherName.replace(/'/g, "\\'"))+'">');    
        }
    });

    $(document).on('click','.othercategory a',function () {
        var selfAttrParent = $(this).data('parent');
        var selfLevel = $(this).data('level'); 
        var finalValue = $(this).data('final');
        var parentName = $(this).data('parentname');
        globalParent = selfAttrParent;
        globalLevel = selfLevel;
        $(".product_sub_items"+selfLevel+" .select2 ").removeClass('active');
        $('.othercategory'+selfLevel).empty();
        $(".product_sub_category .product_sub_items" + selfLevel).nextAll().remove(); 
        $('.product_sub_items'+selfLevel+' .othercategory'+selfLevel).append('<input type="text" style="background:none;padding:5px" placeholder="Add a category" id="otherNameCategory" class="otherNameCategoryClass'+selfLevel+'" data-parentname="'+parentName+'" data-level="'+selfLevel+'" data-final="'+finalValue+'" autocomplete="off" name="othernamecategory" />');
        $('.product_sub_items'+selfLevel+' .otherNameCategoryClass'+selfLevel).focus();
        $(".add_category_submit").empty();
    });

    $(document).on('blur change','#otherNameCategory',function () {
        var otherName = $(this).val();
        var finalValue = $(this).data('final');
        var parentName = $(this).data('parentname');
        $(".add_category_submit").empty();
        if(otherName.length == 0){ 
            $('.product_sub_items'+globalLevel+' .othercategory').empty();
            $('.product_sub_items'+globalLevel+' .othercategory').append('<a href="javascript:void(0)" class="select2" data-level="'+globalLevel+'" data-parent="'+globalParent+'"><b class="add_cat span_bg"></b><b>Add a Category</b></a>');
            if(finalValue == true){ 
              $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+parentName+'">');    
            }
        }else{  
            $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+escapeHtml(otherName.replace(/'/g, "\\'"))+'">');    
        }
    });

    $(document).on('focus','#otherNameCategory',function () {
        var level = $(this).data('level');
        $(".product_sub_category .product_sub_items" + level).nextAll().remove();    
    });
    
    /*
     *   Event action when any of the category search results is clicked.
     *   Each ancestor of the selected category of is sequentially clicked.
     *   Also enables scrollEvent.bool before running and disables it afterwards.
     */
    
    $('#cat_search_drop_content').on('click', 'li.cat_result', function(){
        var parent_ids = eval('('+$(this).attr('data-parent')+')');
        $('li .select').each(function(){
            var D = eval('(' + $(this).attr('data') + ')');
            if( parseInt(D.cat_id) === parent_ids[0]){
                $(this).click();
                scrollToElement(this, '.main_product_category');
                return false;
            }
        });
        scrollEvent.bool = true;
        var cnt = 0;
        $.each(parent_ids, function(){
            var id  = parent_ids.shift();
            $('li.'+ id+' .select2.child').each(function(){
                var D = eval('(' + $(this).attr('data') + ')');
                if( parseInt(D.cat_id) === parent_ids[0]){
                    $(this).click(); 
                    scrollToElement(this, '.product_sub_items' +cnt);
                    cnt++;
                    return false;
                }
            });
        });
        scrollEvent.reset();
    });
        
    var currentRequest = null;
    $( "#cat_sch" ).keyup(function() {
        var searchQuery = $(this).val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        if(searchQuery != ""){  
            currentRequest = jQuery.ajax({
                type: "GET",
                url: config.base_url + 'product/searchCategory', 
                data: "data="+searchQuery+"&"+csrfname+"="+csrftoken, 
                onLoading:jQuery(".cat_sch_loading").html('<img src="'+config.base_url+'assets/images/orange_loader_small.gif" />').show(),
                beforeSend : function(){       
                    $("#cat_search_drop_content").empty();
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    var html = '<ul>';
                    var data_content, data_id, cnt;
                    var delim_img = ' <img src = "'+config.base_url+'assets/images/img_bullet2.jpg"/> ';
                    if((obj.length)>0){
                        jQuery.each(obj,function(){
                            data_content = '';
                            data_id = '[';
                            count = 0;
                            var length = $(this)[0].parent.length;
                            //Iterate through the parent categories
                            jQuery.each($(this)[0].parent,function(){
                                count++;
                                if(count !== length){
                                    data_content += $(this)[0].name + delim_img;
                                    data_id += $(this)[0].id_cat+",";
                                }
                                else{
                                    data_content += '<b>' + $(this)[0].name + '</b>';
                                    data_id +=  $(this)[0].id_cat + "]";
                                }
                            });
                            html += '<li class="cat_result" data-parent="'+data_id+'"><a href="javascript:void(0)">'+data_content+'</a></li>' ;                             
                        });
                    }
                    else{
                        html += '<li> No results found </li>' 
                    }
                    html += '</ul>';
                    $("#cat_search_drop_content").html(html);
                    jQuery(".cat_sch_loading").hide();
                }
            });
        }
    });
    
});
    
function scrollToElement(selector, container, time) {
    var xtime = typeof(time) != 'undefined' ? time : 100;
    var container = typeof(container) != 'undefined' ? container : 'html, body';
    var verticalOffset = $(container).offset().top;
    var element = $(selector);
    var offset = element.offset();
    var offsetTop = offset.top - verticalOffset;
    $(container).animate({
        scrollTop: offsetTop
    }, xtime);
}
    
    
$(document).ready(function() { 
    /* 
     *  Edit Step 1: highlight product category tree
     */
    var obj = JSON.parse($('#edit_cat_tree').val());
    if(obj.length > 0){
        $('li .select').each(function(){
            var D = eval('(' + $(this).attr('data') + ')');
            if( parseInt(D.cat_id,10) === parseInt(obj[0].id_cat,10)){
                $(this).click();
                scrollToElement(this, '.main_product_category');
                return false;
            }
        });
        var cnt = 0;
        $.each(obj, function(){
            var new_obj  = obj.shift();
            $('li.'+ new_obj.id_cat  +' .select2.child').each(function(){
                var D = eval('(' + $(this).attr('data') + ')');
                if(typeof obj[0] !== 'undefined'){
                    if( parseInt(D.cat_id) === parseInt(obj[0].id_cat,10)){
                        $(this).click(); 
                        scrollToElement(this, '.product_sub_items' +cnt);
                        cnt++;
                        return false;
                    }
                }
            });
        });
    }
});


$(document).ready(function() { 

    $('#cat_sch').focus(function() {
    $('#cat_search_drop_content').show();
    $(document).bind('focusin.cat_sch_drop_content click.cat_sch_drop_content',function(e) {
        if ($(e.target).closest('#cat_search_drop_content, #cat_sch').length) return;
        $('#cat_search_drop_content').hide();
        });
     });
    $('#cat_search_drop_content').hide();
    
});
(function ($) {
    
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    var srchdropcontent= $('#cat_search_drop_content');
    var draftCount = $('.draftCount').val();
    var currentRequest = null;
    $('.div_draft').hide();

    saveValue('#container_level01');
    editCategory($('#edit_cat_tree').val(),$('.other_cat_name').val());

    /*
     *   Event action when any of the category search results is clicked.
     *   Re-factored on May 20, 2014: no longer requires asynchronous = false     
     */
    $( "#cat_sch" ).keyup(function() {
        var searchQuery = $(this).val().trim();
        if(searchQuery != ""){ 
            currentRequest = jQuery.ajax({
                type: "GET",
                url: '/product_search/searchCategory', 
                data: "data="+searchQuery+"&"+csrfname+"="+csrftoken, 
                onLoading:jQuery(".cat_sch_loading").html('<img src="/assets/images/orange_loader_small.gif" />').show(),
                beforeSend : function(){       
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                },
                success: function(response) {
                    $("#cat_search_drop_content").empty();
                    var obj = jQuery.parseJSON(response);
                    var html = '<ul>';
                    var data_content, data_id, cnt;
                    var delim_img = ' <img src = "/assets/images/img_bullet2.jpg"/> ';
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
                    $("#cat_search_drop_content").fadeIn(150);
                    jQuery(".cat_sch_loading").fadeOut('fast');
                }
            });
        }
        else{
            $("#cat_search_drop_content").fadeOut('fast');
            $("#cat_search_drop_content").empty();
        }
    });

    $(document).on('click','.draft_remove',function () {
        var pid = $(this).data('pid'); 
        var action = "sell/draft/remove"; 

        if(confirm("Are you sure you want to permanently removed this draft entry?")){
            var request = $.ajax({
                type: "POST",
                url: '/' + action,
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
    
    $('#cat_sch').focusin(function() {
        if($('#cat_sch').val().length > 0){
            srchdropcontent.fadeIn(150);
        }
    });

    $('#cat_sch').focusout(function() {
        srchdropcontent.fadeOut('fast');  
    });

    srchdropcontent.on('click', 'li.cat_result', function(){
        var parent_ids = eval('('+$(this).attr('data-parent')+')');
        var newArray = [];
         
        $.each(parent_ids, function(index, val){ 
            newArray.push({
                id_cat: val
            });
        });

        $('#first_text').empty().append('<a href="javascript:void(0)" class="selected_category_link" data-level="01">Main Category</a>').nextAll().remove();;
        editCategory(JSON.stringify(newArray),'');
    });

    function saveValue(selector)
    {   
        if($('#storeValue > '+selector).length == 0){   
           $('#storeValue').append($(selector).clone());
        }
    }

    function editCategory(ids,customCategory)
    {
        var obj = JSON.parse(ids);
        var other_cat_name = escapeHtml(customCategory); 
        if(obj.length > 0){
 
            var parent_ids = new Array();

            $.each(obj, function(index, val){ 
                parent_ids.push(val.id_cat)
            });
            $('#first_text').empty().append('<a href="javascript:void(0)" class="selected_category_link" data-level="01">Main Category</a>');
            saveValue('#container_level01');
            $.ajax({
                type: "GET",
                url: '/' +  'productUpload/getAllChildren',
                data: "cat_array="+ JSON.stringify(parent_ids) + "&"+csrfname+"=" + csrftoken,
                dataType: "json",
                cache: false,
                success: function(d) {
                    var total = d.length;  
                    $.each(d, function(index, value){
                        $('#cr_div_container').empty();
                        $('#cr_div_container').append(value.html);
                        saveValue('#container_level'+value.level+''+value.cat_id);
                        $('#cl_div_container').append('<div class="border-rad-tl-bl-3 pd-13-12 cat_arrw"><a class="selected_category_link" data-name="'+value.name+'" data-catid="'+value.cat_id+'" data-level="'+value.level+''+value.cat_id+'" href="javascript:void(0)">'+value.name+'</a></div>');
                        
                        if (index === total - 1) {

                            if(other_cat_name != '' &&   typeof(other_cat_name) != 'undefined'){
                                $('#cr_div_container').empty(); 
                                $('#cl_div_container').append('<div><a class="selected_category_link" data-catid="'+value.cat_id+'" data-name="'+other_cat_name+'"   href="javascript:void(0)">'+other_cat_name+'</a></div>');
                                $(".add_category_submit").empty();
                                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+value.cat_id+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+other_cat_name+'">');    
                                $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="'+other_cat_name+'" />');
                            }
                            else{
                                $(".add_category_submit").empty();
                                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+value.cat_id+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+value.name+'">');    
                                $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="" />');
                            }
                        }
                    });
                    $('#cl_div_container div:last-child').addClass('selected_category border-rad-tl-bl-3 pd-13-12 cat_arrw').siblings().removeClass('selected_category');
                }
            });
        }
        else{
            if(other_cat_name != '' &&   typeof(other_cat_name) != 'undefined'){
                $('#first_text').empty().append('<a href="javascript:void(0)" class="selected_category_link" data-level="01">Main Category</a>').removeClass('selected_category');
                saveValue('#container_level01');
                $('#cr_div_container').empty(); 
                $('#cl_div_container').append('<div class="border-rad-tl-bl-3 pd-13-12 cat_arrw selected_category"><a class="selected_category_link" data-catid="1" data-name="'+other_cat_name+'" href="javascript:void(0)">'+other_cat_name+'</a></div>');
                $(".add_category_submit").empty();
                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="1" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+other_cat_name+'">');    
                $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="'+other_cat_name+'" />');
            }
        }
    }

    $(document).on('click','.custom_category_link',function () { 
        var selector = $(this);
        selector.replaceWith('<input class="customCategory" data-catid="'+selector.data('catid')+'" data-level="'+selector.data('level')+'" type="text">');
        $(".customCategory").focus();
    });

    $(document).on('change focusout','.customCategory',function () {
        var selector = $(this); 
        var catId = selector.data('catid');
        var level = selector.data('level');
        var value = escapeHtml(selector.val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' ').trim());
        if(value == "" || selector == "undefined"){
            $(this).replaceWith('<a class="custom_category_link pd-13-12 display-ib" data-level="'+level+'" data-catid="'+catId+'" >Add Category <span class="span_bg icon-add border-rad-90"></span></a>');
        }
        else{
            $(".add_category_submit").empty();
            $('#cr_div_container').empty(); 
            $('#first_text').empty().append('<a href="javascript:void(0)" class="selected_category_link" data-level="01">Main Category</a>');
            $('#cl_div_container').append('<div class="border-rad-tl-bl-3 pd-13-12 cat_sel1"><a class="selected_category_link" data-catid="'+catId+'" data-name="'+value+'"   href="javascript:void(0)">'+value+'</a></div>').find('.cat_sel1').addClass('selected_category cat_arrw').removeClass('cat_sel1').siblings().removeClass('selected_category');
            $(this).replaceWith('<a class="custom_category_link">'+value+'</a>');  
            $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+value+'">');    
            $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="'+value+'" />');           
        }
    });

    $(document).on('click','.custom_link',function(){
        var selector = $(this);
        catId = selector.data('catid');
        value = selector.data('name');
        level = selector.data('level');
        $('#first_text').empty().append('<a href="javascript:void(0)" class="selected_category_link" data-level="01">Main Category</a>');
 

        $(".add_category_submit").empty();
        $('#cr_div_container').empty(); 
        $('#cl_div_container').append('<div_draft><a class="selected_category_link" data-catid="'+catId+'" data-name="'+value+'"   href="javascript:void(0)">'+value+'</a></div>');
        $(this).replaceWith('<a class="custom_category_link">'+value+'</a>');  
        $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+value+'">');    
        $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="'+value+'" />');    
    });

    $(document).on('click','.draft_name',function () {
        var pid = $(this).data('pid');
        var input = $("<input>")
        .attr("type", "hidden")
        .attr("name", "p_id").val(pid);
        $('#draft_form').append($(input));
        $('#draft_form').submit();
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

    $(document).on('click','.category_link',function () {
        selector = $(this);
        var catId = selector.data('catid'); 
        var parentId = selector.data('parentid'); 
        var currentLevel = selector.data('level');
        var name = selector.data('name');
        var action = 'productUpload/getChild';
        var identify = currentLevel+''+parentId; 
        var newlevel = currentLevel + 1;
        var newIdentify = newlevel+''+catId; 
        saveValue('#container_level'+identify);
        $('#cr_div_container').empty();
        $('#first_text').empty().append('<a href="javascript:void(0)" class="selected_category_link" data-level="01">Main Category</a>');
        $('#cl_div_container').append('<div class="border-rad-tl-bl-3 pd-13-12 cat_sel1"><a class="selected_category_link" data-name="'+name+'" data-catid="'+catId+'" data-level="'+newIdentify+'" href="javascript:void(0)">'+name+'</a></div>').find('.cat_sel1').addClass('selected_category cat_arrw').removeClass('cat_sel1').siblings().removeClass('selected_category , pd-13-12');
        if($('#storeValue > #container_level'+newIdentify).length == 0){
            $.ajax({
                type: "POST",
                url: '/' + action,
                data: "cat_id=" + catId + "&level=" + newlevel + "&"+csrfname+"=" + csrftoken,
                dataType: "json",
                cache: false,
                success: function(d) {
                    $('#cr_div_container').append(d.html);
                    saveValue('#container_level'+newIdentify);
                }
            });
        }
        else{
            $('#cr_div_container').empty().append($('#storeValue > #container_level'+ newIdentify).clone());
        }

        $(".add_category_submit").empty();
        $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+name+'">');    
        $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="" />');
    });

    $(document).on('click','.selected_category_link',function(){
        selector = $(this); 
        var catId = selector.data('catid'); 
        var name = selector.data('name'); 
        var level = selector.data('level');
        $('#cr_div_container').empty().append($('#storeValue > #container_level'+ level).clone());
        selector.parent().nextAll().remove();

        $(".add_category_submit").empty();

        if ($(this).attr('data-catid')) {
            $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+name+'">');    
            $(".add_category_submit").append('<input type="hidden" name="othernamecategory" value="" />');
        }
        $(selector).closest('div').addClass('selected_category pd-13-12');
    });
})(jQuery);




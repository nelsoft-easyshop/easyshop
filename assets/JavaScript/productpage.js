
$(document).ready(function() {

    $(".product_quantity").numeric({negative : false});
    
    $(".product_quantity").keyup(function(){
        var obj = $('.quantity')[0].innerHTML;
        obj = (obj === '')?'0':obj;
        if(parseInt($(this).val(),10) >   parseInt(obj,10)){
            $(".product_quantity").val(parseInt(obj,10));
        }
        else if((parseInt($(this).val(),10) === 0)&&(parseInt(obj) !== 0)) {
            $(".product_quantity").val(1);
        }
    });
});

$(function(){
  $('.jqzoom').jqzoom({
            zoomType: 'standard',
            lens:true,
            preloadImages: false,
            alwaysOn:false
        });
});


$(function(){
    $('.nav_title').mouseover(function(e) {
       $("nav").show();
        });
        $('.nav_title').mouseout(function(e) {
       $("nav").hide();
        });
    $("nav").mouseenter(function() {
      $(this).show();
    }).mouseleave(function() {
      $(this).hide();
    });
});
 
$(function() {
    $('#tabs').tabs();
});

$(function(){
  // modal Load dialog on click
  $('.write_review').click(function (e) {
    $('#write_review_content').modal();
    $('#write_review_content').parent().removeAttr('style');
    $('#star').raty();
    $("#review_form").validate({
        rules: {
            subject: {
                required: true
                },
            comment: {
                required: true
                }
         },
         messages: {
            subject: {
                required: '* Required'
                },
            comment: {
                required: '* Required'
                }
         },
         errorElement: "span",
         errorPlacement: function(error, element) {
                error.addClass('red');
                error.appendTo(element.parent());
         },
         submitHandler: function(form) {
           $('#load_submitreview').css('display','inline');
           $.post(config.base_url+'product/submit_review', $(form).serializeArray(),
               function(data){
                        $('#review_container').css('display','none');
                        $('#load_submitreview').css('display','none');
                        $('#review_success_container').css('display','inline-block');
           });
        
        }
         
    });
    return false;
  });
  
});



$(function(){
    $('.reviews_content').on('click','.reply_btn', function(event){
        $(this).siblings('.reply_area').fadeIn(300);
        $(this).fadeOut();
    });
    
    $('.reviews_content').on('click','.reply_cancel', function(event){
        $(this).closest('div.reply_area').fadeOut();
        $(this).siblings('.reply_field').val('');
        $(this).closest('div.reply_area').siblings('.reply_btn').fadeIn(300);
    });
    
    $('.reviews_content').on('click','.reply_save', function(event){
        var form = $(this).parent('form');
        var replyfield = $(this).siblings('.reply_field');
        var loadingimg = $(this).siblings('#savereply_loadingimg');
        if($.trim(replyfield.val()).length < 1)
            replyfield.effect('pulsate',{times:5},500);
        else{
            $(this).hide();
            loadingimg.show();
            $.post(config.base_url+'product/submit_reply',form.serialize(),function(data){
                $(this).show();
                loadingimg.hide();
                if(data == 1){
                    location.reload(true);
                }
                else
                    alert('Sorry, your reply was not posted.');
            });
        }
        
    });
    
    $('.reviews_content').on('click','.show_replies',function(){
        $(this).fadeOut();
        $(this).siblings('.reply_content, .hide_replies').fadeIn(300);
    });
    
    $('.reviews_content').on('click','.hide_replies',function(){
        $(this).fadeOut();
        $(this).siblings('.reply_content').fadeOut(300);
        $(this).siblings('.show_replies').fadeIn(300);
    });
});


$(function(){
    //Loads the defaults quantity
    var qty = JSON.parse($('#p_qty').val());
    //wrapped in each for the meantime, just in case default quantity is not the
    //only content of $('#p_qty').val()   
    $.each(qty, function(index, value){
        if((value.product_attribute_ids.length == 1)&&(parseInt(value.product_attribute_ids[0].id)==0)&&(parseInt(value.product_attribute_ids[0].is_other)==0)){
            $('.quantity').data('qty',value.quantity);
            $('.quantity')[0].innerHTML = value.quantity;
            $('.quantity').data('default','true');
            //if there are no attributes to choose from: enable buy button
            if($('.product_option').find('ul.options')[0] === undefined){
                 $('.orange_btn3').removeClass("disabled").addClass("enabled");
            }
            return false;
        }
    });      
});



function attrClick(target, $this){
        //If clicked attribute is disabled, exit immediately
        if(target.hasClass('disable'))
            return false;
            
        //** Highlight selected attribute
        //It is important that the evaluation for isActiveBool happens before the active class is removed
        var isActiveBool = (target.attr('class') === 'active')?true:false; 
         //".option a" and the first if condition is added in order to account for product options with images
        if($this.prop('tagName').toLowerCase() != 'ul' ){                          
            $this.parent().find('.active').removeClass("active");
        }
        else{
            $this.find('.active').removeClass("active");
        }
        
        if(!isActiveBool){
            target.addClass("active");
        }
     
        var isOptionAvailable = false;
        //** calculate price
        var sel_id = new Array();
        
        var price = parseFloat($('.current_price').attr('data-baseprice'));
        $('.product_option').find('ul.options').each(function(){	
            var actv = $(this).find("li.active");
            if(actv[0] === undefined){
                price += 0;
            }
            else{
                sel_id.push([actv.attr('data-attrid'),actv.attr('data-type')])
                price += parseFloat(actv.attr('data-price'));
                //Added this if condition so that hidden active attributes are ignored for attribute display enabling
                if(actv.attr('data-hidden') !== 'true')
                    isOptionAvailable = true;
            }
        });
 
        $('.current_price')[0].innerHTML = price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        /*
         *  If only the default quantity is set, skip attribute checking and quantity recalculation
         *  Enable buy button if an attribute is selected in all the rows.
         */
         
        if($('.quantity').data('default') === 'true'){
            var defaultBuyEnable = true;
            $('.product_option .options').each(function(){
                if($(this).find('.active')[0] === undefined){
                    defaultBuyEnable = false;
                }
            });
            if(defaultBuyEnable){
                $('.orange_btn3').removeClass("disabled").addClass("enabled");
            }
            else{
                $('.orange_btn3').removeClass("enabled").addClass("disabled");
            }
            return false;
        }
        
        //**Calculate quantity          
        var qty = JSON.parse($('#p_qty').val());
        $.each(qty, function(index, value){
            var value_arr = new Array();
            $.each(value.product_attribute_ids, function(y,x){
                value_arr.push([x.id, x.is_other]);
            });
            if(value_arr.sort().join(',') === sel_id.sort().join(',')){
               $('.quantity')[0].innerHTML = value.quantity;
               $('.orange_btn3').removeClass("disabled").addClass("enabled"); //REMOVED TO DISABLE BUY NOW BUTTON ACTIVATION
               return false;
            }
            else{
               $('.orange_btn3').removeClass("enabled").addClass("disabled");
               $('.quantity')[0].innerHTML = $('.quantity').data('qty');
            }
        });
        
        //** Determine shipment location
        var shipment = JSON.parse($('#p_shipment').val());
        var shipmentList= document.getElementById("shipment_locations");
        shipmentList.innerHTML = "";
        
        $.each(shipment, function(index, value){ 
            var value_arr = new Array();
            $.each(value.product_attribute_ids, function(y,x){
                value_arr.push([x.id, x.is_other]);
            });
           if(value_arr.sort().join(',') === sel_id.sort().join(',')){
               var item = document.createElement("li");
               item.innerHTML = value.location;
               shipmentList.appendChild(item);
            }
        });


        //**trigger keyup event of product_quantity textbox
        $(".product_quantity").trigger( "keyup" );
        
        //** disable attributes with no quantity option
        var show_ids = new Array();

        /*  The algorithm determines the attributes to be displayed using the following steps:
         *  1. If the combination contains all of the selected attributes, enable the other attributes in the combination
         *  2. Get combinations wherein each attribute to be displayed is included:
         *     If an attribute in the combination is in the same row as a currently active attribute    
         *     AND the selected attributes (minus the first element) PLUS the attribute in question are present together 
         *     in the combination: enable the attribute. The relevance of the removing the first element of the selected
         *     attributes array is to give the algorithm a row to consider as the "free-est" row (the row wherein the user
         *     can move about the most without violating the combinations). This can be any row, but it has been arbitrarily 
         *     decided to consider the row of the top most selected attribute as the "free-est" row.
         *  3. If an attribute is not included in the attributes to be displayed AND is in the same row as the currently 
         *     selected attribute, do not disable it even if it does not pass conditions 1 & 2. Note that this step does 
         *     not enable such attributes but simply prevents them from being disabled. They simply maintain their state.
         */
        
        /*
         *  @Step 1: Get ids of attributes to display based on available combination and selected attributes: 
         *  This step is actually sufficient to meet the purpose of this feature. However, using this alone 
         *  restricts the user's freedom to change attributes.
         */
        $.each(qty, function(index, value){
            var value_arr = new Array();
            $.each(value.product_attribute_ids, function(y,x){
                value_arr.push([x.id, x.is_other]);
            });
            if(containsAll(sel_id, value_arr)){
                $.each(value_arr, function(r,s){
                    //if attr_id is not yet in show_ids, push it in
                    if(!inArray(s, show_ids)){
                        show_ids.push(s);
                    }
                });
            }
        });
		
        /*
         * @Step 2: Get ids of PROBABLE attributes based on available combinations, selected attributes, and
         * currently enabled attributes.
         */

        $.each(show_ids, function(idx, id){
            $.each(qty, function(index, value){
                var value_arr = new Array();
                $.each(value.product_attribute_ids, function(y,x){
                    value_arr.push([x.id, x.is_other]);
                }); 
                if((containsAll([id], value_arr)))  {        
                    $.each(value_arr, function(r,s){
                        if(($('.product_option li[data-attrid='+s[0]+']').siblings('.active')[0] !== undefined)){
                            //Remove element from the "free-est" row
                            var arr = [].concat(sel_id);
                            arr.shift();  
                            var n_arr = [s].concat(arr);
                            if((containsAll(n_arr, value_arr))){
                                //if attr_id is not yet in show_ids, push it in
                                if(!inArray(s, show_ids))
                                {
                                    show_ids.push(s);
                                }
                            }
                        }
                    });
                }
            })
        }); 
       
  
        //Disabled/enable attributes accordingly (if no option is selected, just enable everything)
        $('.product_option li').each(function(){
            var t_arr = [$(this).attr('data-attrid'), $(this).attr('data-type')];
            if((!inArray(t_arr, show_ids))&&(isOptionAvailable))
            {
               //@Step 3: added this if condition in order to keep same row attributes from being disabled
               if(($(this).closest('ul')[0]!==target.closest('ul')[0]))
               {
                    $(this).addClass('disable');
                    //$(this).removeClass('active'); //useful in the event of a bug
                    if($(this).parent().prop('tagName').toLowerCase() === 'a'){
                       $(this).parent().data('enable', 'false');
                    }
                }
            }
            else{
                $(this).removeClass('disable');
                if($(this).parent().prop('tagName').toLowerCase() === 'a'){
                    //data-enable added in order to disabled jqzoom swapping, see jqzoomer.js
                    $(this).parent().data('enable', 'true');
                }
            }
        });
        if(!isActiveBool){
            target.removeClass('disable');
        }
        return isActiveBool;
}

$(function(){
    $('.options, .options a').on('click', function(event){
        var $this = $(this);
		if(attrClick($(event.target), $this)){
            var active = new Array();
            $('.product_option li').each(function(){
                if($(this).hasClass('active')){
                    active.push([$(this).attr('data-attrid'), $(this).attr('data-type')]);
                }
                $(this).removeClass('disable');
                $(this).removeClass('active');
            });
            $.each(active, function(p, q){
                var target = $('.product_option li[data-attrid='+q[0]+'][data-type='+q[1]+']');
                attrClick(target,$this);
            });
        }
    });
   
});




$(function(){
    $('li[data-hidden="true"]').each(function(){
        $(this).trigger( "click" );
    });
});

 
$(function(){
    jQuery(document).on('click', '#send.enabled', function(){
            var i_id = $(".id-class").attr("id");
            var i_name =  $("#pname").text().trim();
            var i_qty =  $(".product_quantity").val();
            var i_price =  $(".current_price").text().trim();
            var i_opt = {};
            var length = parseInt($('.product_option').length) - 1;
            var csrftoken = $('#buynow_csrf').val();
            var max_qty = $(".quantity").html();
            $(".options").each(function() {
                var parent = $(this).parent();
                var id = $(this).attr("name");
                var attr= parent.find(".active").attr("id");
                i_opt[id] =attr;
                
            });
            $.ajax({
                async:false,
                url: config.base_url + "cart/add_item",
                type:"POST",
                dataType:"JSON",
                data:{id:i_id,qty:i_qty,price:i_price,opt:i_opt,name:i_name,length:length,es_csrf_token:csrftoken,max_qty:max_qty},

                success:function(data){
                    if(data == "386f25bdf171542e69262bf316a8981d0ca571b8" ){
                        alert("Please select an attribute.");
                    }else if(data == "d3d34a1c4cb94f516ae916e4b8b4be80d50c8f7a"){
                        window.location.replace(config.base_url + "cart");
                    }
                }

            });

    });

});

$(function(){
    $('.reviews_content').on('click','#see_more_reviews', function(event){
        $('#more_review_loading_img').show();
        $('#see_more_reviews').hide();
		var csrftoken = $('#reviews_csrf').val();
        $.post(config.base_url+'product/get_more_reviews',{last_id:$('#lastreview').prop('value'), es_csrf_token : csrftoken}, 
            function(data){
                $('#more_review_loading_img').hide();
                $('#see_more_reviews').show();
                
                var obj = jQuery.parseJSON(data);
                //console.log(obj['reviews'].length);
                if(obj['reviews'].length > 0){
                    $.each(obj['reviews'], function(index,value){
                    var i;
                    var on_star_html = "";
                    var off_star_html = "";
                    
                    //GENERATE STAR RATINGS
                    for(i=value.rating;i>0;i--){
                        on_star_html += "<img src='"+config.base_url+"assets/images/star-on.png' alt='*' title=''>";
                    }
                    for(i=5-value.rating;i>0;i--){
                        off_star_html += "<img src='"+config.base_url+"assets/images/star-off.png' alt='*' title=''>";
                    }
                    
                    //CREATE NEW LEFT AND RIGHT DIV
                    var leftdiv = $("<div class='review_left_content'><h3>"+value.title+
                    "</h3><p>"+value.reviewer+" | "+value.datesubmitted+"</p><p>Rating: "+on_star_html+off_star_html+"</p></div>").insertBefore($('div.review_last').prev('div.clear'));
                    
                    //var rightdiv = $("<div class='right_left_content'><p class='review_comment_content'>"+value.review+"</p><div class='reply_content_shown'></div></div>").insertAfter(leftdiv);
                    var rightdiv = $("<div class='right_left_content'><p class='review_comment_content'>"+value.review+"</p></div>").insertAfter(leftdiv);

                    //APPEND replies if any
                    if(value.reply_count > 0){
                        rightdiv.children('p.review_comment_content').after('<div class="reply_content_shown"></div>');
                        if(value.reply_count > 3){
                            rightdiv.children('div.reply_content_shown').after('<div class="reply_content"></div>');
                            rightdiv.children('div.reply_content').after("<p class='show_replies'>Show replies</p><p class='hide_replies'>Hide replies</p>");
                        }
                            
                        var reply_counter = 0;
                        $.each(value.replies, function(index,value){
                            if(reply_counter < 3)
                                rightdiv.children('div.reply_content_shown').append("<p><strong>"+value.reviewer+"</strong> on: "+value.datesubmitted+'<br>"'+value.review+'"</p>');
                            else
                                rightdiv.children('div.reply_content').append("<p><strong>"+value.reviewer+"</strong> on: "+value.datesubmitted+'<br>"'+value.review+'"</p>');
                            reply_counter++;
                        });
                    }
                    
                    //APPEND REPLY BUTTON IF ALLOWED
                    if((value.is_reviewer === 1 || obj['is_seller'] === 'yes') && obj['is_loggedin'] === 'yes'){
                        var $html1 = $('div.reply_area').clone(true);
                        $html1.find('input[name="p_reviewid"]').val(value.id_review);
                        rightdiv.append("<span class='reply_btn'>Reply</span><div class='reply_area'>" + $html1.html() + "</div>");
                    }

                    $('#lastreview').prop('value', value.id_review);
                    
                    });
                }
                else{
                    $('.reviews_content').off('click','#see_more_reviews');
                    $('#see_more_reviews').html('<strong>End of reviews.</strong>');
                }
                    
            }
        );
        return false;
    });
    
});

/*
 * This function checks if all elements in array needles exists in
 * array haystack. This does not compare if two arrays are equal.
 */

function containsAll(needles, haystack){ 
    for(var i = 0, len = needles.length; i<len; i++){
        if(!inArray(needles[i], haystack)){
            return false;
        }
    }
    return true;
}

function inArray(needle, haystack){
    for(j = 0, len = haystack.length; j<len; j++){
        if(arraysEqual(needle, haystack[j])){
            return true;
        }
    }
    return false;
}

function arraysEqual(a, b) {
    if (a === b) 
        return true;
    if (a == null || b == null) 
        return false;
    if (a.length != b.length) 
        return false;
    for (var i = 0; i < a.length; ++i) {
        if (a[i] !== b[i]) 
            return false;
    }
    return true;
}

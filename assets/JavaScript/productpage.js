
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
                required: true,
                },
            comment: {
                required: true,
                },
         },
         messages: {
            subject: {
                required: '* Required',
                },
            comment: {
                required: '* Required',
                },
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
    $.each(qty, function(index, value){
        if((value.product_attribute_ids.length == 1)&&(parseInt(value.product_attribute_ids[0])==0)){
            $('.quantity').data('qty',value.quantity);
            $('.quantity')[0].innerHTML = value.quantity;
        }
    });       
});

$(function(){
    $('.options, .options a').on('click', function(event){
    
        if($(event.target).hasClass('disable'))
            return false;
    
        //** Highlight selected attribute
        //It is important that the evaluation for isActiveBool happens before the active class is removed
        var isActiveBool = ($(event.target).attr('class') === 'active')?true:false; 
         //".option a" and the first if condition is added in order to account for product options with images
        if($(this).prop('tagName').toLowerCase() != 'ul' ){                          
            $(this).parent().find('.active').removeClass("active");
        }
        else{
            $(this).find('.active').removeClass("active");
        }
        if(!isActiveBool){
            $(event.target).addClass("active");
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
                sel_id.push(actv.attr('data-attrid'))
                price += parseFloat(actv.attr('data-price'));
                isOptionAvailable = true;
            }
        });
        $('.current_price')[0].innerHTML = price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
        //**Calculate quantity          
        var qty = JSON.parse($('#p_qty').val());

        $.each(qty, function(index, value){        
            if(value.product_attribute_ids.sort().join(',') === sel_id.sort().join(',')){
               $('.quantity')[0].innerHTML = value.quantity;
               //$('.orange_btn3').removeClass("disabled").addClass("enabled"); REMOVED TO DISABLE BUY NOW BUTTON ACTIVATION
               return false;
            }
            else{
               $('.orange_btn3').removeClass("enabled").addClass("disabled");
               //$('.quantity')[0].innerHTML = '';
               $('.quantity')[0].innerHTML = $('.quantity').data('qty');
            }
        });
        
        //**trigger keyup event of product_quantity textbox
        $(".product_quantity").trigger( "keyup" );
        
        //** disable attributes with no quantity option
        var show_ids = new Array();
        
        //get ids of attributes to display
        $.each(qty, function(index, value){
            if(containsAll(sel_id, value.product_attribute_ids)){  
                $.each(value.product_attribute_ids, function(r,s){
                    if($.inArray(s, show_ids) == -1){
                        show_ids.push(s);
                    }
                });
            }
        });
  
        //Disabled/enable attributes accordingly (if no option is selected, just display everything)
        
        $('.product_option').find('li').each(function(){
            if(($.inArray($(this).attr('data-attrid'), show_ids) === -1)&&(isOptionAvailable)){
                //added this if condition in order to keep same row attributes enabled
                if(($(this).closest('ul')[0]!==$(event.target).closest('ul')[0])){
                    $(this).addClass('disable');
                    if($(this).parent().prop('tagName').toLowerCase() === 'a'){
                       $(this).parent().data('enable', 'false');
                    }
                }
            }
            else{
                $(this).removeClass('disable');
                if($(this).parent().prop('tagName').toLowerCase() === 'a'){
                    $(this).parent().data('enable', 'true');
                }
            }
        });
        if(!isActiveBool){
            $(event.target).removeClass('disable');
        }

        
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
                data:{id:i_id,qty:i_qty,price:i_price,opt:i_opt,name:i_name,length:length},

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
        $.post(config.base_url+'product/get_more_reviews',{last_id:$('#lastreview').prop('value')}, 
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

function containsAll(needles, haystack){ 
    for(var i = 0 , len = needles.length; i < len; i++){
        if($.inArray(needles[i], haystack) == -1) return false;
    }
    return true;
}

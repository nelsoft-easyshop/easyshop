(function($) {
    $(function() {
        $('.jcarousel').jcarousel();

        $('.jcarousel-control-prev')
                .on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        })
                .on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
                .jcarouselControl({
            target: '-=1'
        });

        $('.jcarousel-control-next')
                .on('jcarouselcontrol:active', function() {
            $(this).removeClass('inactive');
        })
                .on('jcarouselcontrol:inactive', function() {
            $(this).addClass('inactive');
        })
                .jcarouselControl({
            target: '+=1'
        });

        $('.jcarousel-pagination')
                .on('jcarouselpagination:active', 'a', function() {
            $(this).addClass('active');
        })
                .on('jcarouselpagination:inactive', 'a', function() {
            $(this).removeClass('active');
        })
                .jcarouselPagination();
    });
    
    
    
    $(function(){    
        var actionConstants = {
            message: 0
        }
        
        var isLoggedIn = ($(".es-data[name='is-logged-in']").val() == 'true');
        var action = $.cookie('es-productpage-action');
        $.removeCookie('es-productpage-action');

        var uid = parseInt($('#user-id').val(), 10);
        var seller_id = parseInt($('#seller-id').val(), 10);
        if (uid ==  seller_id || uid == 0 ) {
            $(".vendor-msg-modal").remove();
            $("#modal-background").remove();
            $("#modal-container").remove();
        }

        $("#modal-background, #modal-close").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").hide();
            $("#msg-message").val("");
        });
        
        $(".modal_msg_launcher").click(function() {      
            if(!isLoggedIn){
                $.cookie('es-productpage-action', actionConstants.message);
                window.location.replace("/login");
            }
            else{
                $("#modal-container, #modal-background").toggleClass("active");
                $("#modal-container").show('fade',400);
            }
        });
        
        $("#modal_send_btn").on("click",function(){
            var recipientUsername = $('#seller-username').val();
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var msg = $("#msg-message").val();
            if (msg == "") {
                alert("Say something..");
                return false;
            }
            var msg = $("#msg-message").val();
                $.ajax({
                    async : true,
                    type : "POST",
                    dataType : "json",
                    url : "/messages/send_msg",
                    data : {recipient:recipientUsername,msg:msg,csrfname:csrftoken},
                    success : function(data) {
                        $("#modal-container, #modal-background").toggleClass("active");
                        $("#modal-container").hide();
                        $("#msg-message").val("");
                        alert("Your message has been sent.");
                    }
                });
        });
        
        if(action == actionConstants.message && isLoggedIn){
            $(".modal_msg_launcher").click();
        }
        
        
  

    });
    
    $(document).on('click','.prod_cat_drop',function() {
        $(".category_nav").toggleClass("category_nav_plus");
        $(".prod_cat_drop").toggleClass("active_prod_cat_drop_arrow");
        
        $(document).bind('focusin.prod_cat_drop click.prod_cat_drop',function(e) {
                if ($(e.target).closest('.prod_cat_drop, .category_nav').length) return;
                $('.category_nav').removeClass('category_nav_plus');
                $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');
        });
    });
 
    $(function(){  
        $('.category_nav').removeClass('category_nav_plus');
        $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');
    });
    
 
    $(function(){  
        
        $('.mid_slide1').bxSlider({
            mode: 'horizontal',
            auto:true,
            autoControls: true,
            pause: 3500,
            controls:false,
            slideWidth: 510
        });

        $('.mid_slide2').bxSlider({
            slideWidth: 160,
            minSlides: 2,
            maxSlides: 3,
            moveSlides: 1,
            slideMargin: 0,
            infiniteLoop:true,
            autoControls: false,
            pager:false
        });

        $('.countdown_slides').bxSlider({
            slideWidth: 220,
            minSlides: 3,
            maxSlides: 4,
            moveSlides: 2,
            slideMargin: 0,
            infiniteLoop:true,
            autoControls: false,
            pager:false
        });

        $('.slider3').bxSlider({
            slideWidth: 452,
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            slideMargin: 0,
            infiniteLoop:true,
            autoControls: false,
            pager:false
        });

        $('.bx-wrapper').addClass('slide_arrows');

        //middle content top slides
        $('.mid_slide1').parent('.bx-viewport').addClass('mid_top_slides');

        //middle content countdown slides
        $('.countdown_slides').parent('.bx-viewport').parent('.bx-wrapper').addClass('countdown_slides_wrapper');

        //middle content bottom slides
        $('.mid_slide2').parent('.bx-viewport').parent('.bx-wrapper').addClass('mid_bottom_slides');
        $('.mid_slide2').parent('.bx-viewport').addClass('inner_mid_bottom_slides');

        //electronics slides
        $('.slider3').parent('.bx-viewport').addClass('electronic_slides');

        //side navigation menu slides
        $('.slides_prod').parent('.bx-viewport').addClass('side_menu_slides');
        $('.side_menu_slides').parent('.bx-wrapper').addClass('side_menu_nav_slides');
        $('.side_menu_nav_slides').children('.bx-controls').addClass('side_menu_nav_arrow');


    });

    $(document).ready(function() {

        $(".product_quantity").numeric({negative : false});
        
        $(".product_quantity").keyup(function(){
            var obj = $('.quantity').html();
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
                        error.addClass('review-error');
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
        $('.media-body').on('click','.reply_btn_m', function(event){
            $(this).siblings('.reply_area_m').fadeIn(300);
            $(this).fadeOut();
        });

        $('.reviews_content').on('click','.reply_cancel', function(event){
            $(this).closest('div.reply_area').fadeOut();
            $(this).siblings('.reply_field').val('');
            $(this).closest('div.reply_area').siblings('.reply_btn').fadeIn(300);
        });
        
        $('.media-body').on('click','.reply_cancel_m', function(event){
            $(this).closest('div.reply_area_m').fadeOut();
            $(this).siblings('.reply_field_m').val('');
            $(this).closest('div.reply_area_m').siblings('.reply_btn_m').fadeIn(300);
        });

        $('.reviews_content').on('click','.reply_save', function(event){
            var form = $(this).parent('form');
            var replyfield = $(this).siblings('.reply_field');
            var loadingimg = $(this).siblings('#savereply_loadingimg');
            var replydiv = $(this).closest('div.reply_area');
            var thisbtn = $(this);
            var cancelbtn = $('.reply_cancel');
            if($.trim(replyfield.val()).length < 1)
                replyfield.effect('pulsate',{times:5},500);
            else{
                thisbtn.hide();
                cancelbtn.hide();
                loadingimg.show();
                $.post(config.base_url+'product/submit_reply',form.serialize(),function(data){
                    replydiv.fadeOut();
                    thisbtn.show();
                    loadingimg.hide();
                    if(data == 1){
                        location.reload(true);
                    }
                    else
                        alert('Sorry, your reply was not posted.');
                });
            }
            
        });

        $('.media-body').on('click','.reply_save_m', function(event){
            var form = $(this).parent('form');
            var replyfield = $(this).siblings('.reply_field_m');
            var loadingimg = $(this).siblings('#savereply_loadingimg_m');
            var replydiv = $(this).closest('div.reply_area_m');
            var thisbtn = $(this);
            var cancelbtn = $('.reply_cancel_m');
            if($.trim(replyfield.val()).length < 1)
                replyfield.effect('pulsate',{times:5},500);
            else{
                thisbtn.hide();
                cancelbtn.hide();
                loadingimg.show();
                $.post(config.base_url+'product/submit_reply',form.serialize(),function(data){
                    replydiv.fadeOut();
                    thisbtn.show();
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
        //For mobile
        $('.media-body').on('click','.show_replies_m',function(){
            $(this).fadeOut();
            $(this).siblings('.reply_content_m, .hide_replies_m').fadeIn(300);
        });

        $('.reviews_content').on('click','.hide_replies',function(){
            $(this).fadeOut();
            $(this).siblings('.reply_content').fadeOut(300);
            $(this).siblings('.show_replies').fadeIn(300);
        });
        
        //For Mobile
        $('.media-body').on('click','.hide_replies_m',function(){
            $(this).fadeOut();
            $(this).siblings('.reply_content_m').fadeOut(300);
            $(this).siblings('.show_replies_m').fadeIn(300);
        });
    });





    var sel_id_ordered = new Array();
            
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
                        
            var data_attr = [target.attr('data-attrid'),target.attr('data-type')];
            if(!isActiveBool){
                target.addClass("active");
                target.parent().find('li').each(function(){
                    var t_idx = inArray([$(this).attr('data-attrid'),$(this).attr('data-type')], sel_id_ordered);
                    if(t_idx > -1){
                        sel_id_ordered.splice(t_idx, 1);
                    }
                });
                if(inArray(data_attr, sel_id_ordered) === -1){
                    sel_id_ordered.push(data_attr);
                }
            }
            else{
                var idx = inArray(data_attr, sel_id_ordered);
                if(idx > -1){
                    sel_id_ordered.splice(idx, 1);
                }
            }
            /*  Previously: if(!isActiveBool) target.addClass("active");
            *  Added rest of code to get selected attributes in the same
            *  order that they are entered. 
            */
                
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
                    sel_id.push([actv.attr('data-attrid'),actv.attr('data-type')]);
                    price += parseFloat(actv.attr('data-price'));
                    //Added this if condition so that hidden active attributes are ignored for attribute display enabling
                    if(actv.attr('data-hidden') !== 'true')
                        isOptionAvailable = true;
                }
            });
            
            var freeRowIndex = inArray(sel_id_ordered[0], sel_id);
            freeRowIndex = (freeRowIndex<=-1) ? 0 : freeRowIndex;
            
            
            if(price == 0.01){
                $('.current_price').html('FREE');
                $('.currency').hide();
            }
            else{
                $('.currency').show();
                $('.current_price').html(price.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
            }
            
            /*
            *  If only the default quantity is set, skip attribute checking and quantity recalculation
            *  Enable buy button if an attribute is selected in all the rows.
            */
            
            var allAttributeSelected = true;
            $('.product_option .options').each(function(){
                if($(this).find('.active')[0] === undefined){
                    allAttributeSelected = false;
                }
            }); 
            
            if($('.quantity').data('default') === 'true'){
                var availability = parseInt($('.quantity').data('qty'),10);
                if((allAttributeSelected) && (availability > 0)){
                    $('.fm1.orange_btn3').removeClass("disabled").addClass("enabled");
                }
                else{
                    $('.fm1.orange_btn3').removeClass("enabled").addClass("disabled");
                }
                return false;
            }
            
            //**Calculate quantity          
            var qty = JSON.parse($('#p_qty').val());
            var qty_match_found = false;
    
            $.each(qty, function(index, value){
                var value_arr = new Array();
                $.each(value.product_attribute_ids, function(y,x){
                    value_arr.push([x.id, x.is_other]);
                });
                if(value_arr.sort().join(',') === sel_id.sort().join(',')){
                    $('#p_itemid').val(index);                    
                    $('.quantity').html(value.quantity);
                    if(parseInt(value.quantity,10) > 0){
                        $('.fm1.orange_btn3').removeClass("disabled").addClass("enabled"); //REMOVED TO DISABLE BUY NOW BUTTON ACTIVATION
                    }
                    qty_match_found = true;
                    return false;
                }
                else{
                $('.fm1.orange_btn3').removeClass("enabled").addClass("disabled");
                $('.quantity').html( $('.quantity').data('qty') );
                }
            });
            
            if((allAttributeSelected)&&(!qty_match_found)){
                $('.fm1.orange_btn3').removeClass("enabled").addClass("disabled");
                $('.quantity').html(0);
            }
        
            //** Determine shipment location

            var sel_visible_length = sel_id.length - $('.product_option[style*="display:none"]').length;
            
            
            var shipment = JSON.parse($('#p_shipment').val());        
            var option = document.createElement("option");
            $('#shipment_locations').find('option').not('.default').each(function(){
                $(this).prop('disabled', true);
                $(this).data('price', 0);
            });
            $.each(shipment, function(index, value){    
                var value_arr = new Array();
                $.each(value.product_attribute_ids, function(y,x){
                    value_arr.push([x.id, x.is_other]);
                });            
                if((sel_visible_length === 0)||(value_arr.sort().join(',') === sel_id.sort().join(','))){
                    var option =  $('#locationID_' + value.location_id);
                    option.data('price',value.price);
                    option.prop('disabled', false);
                    $.each(option.nextAll(), function(){
                        if($(this).data('type') === option.data('type')){
                        return false;
                        }
                        $(this).prop('disabled', false);
                        $(this).data('price',value.price);
                    }); 
                }    
            });
            
            if($('#shipment_locations :selected').is(':disabled')){
                $('#shipment_locations :nth-child(1)').prop('selected', true);
                $('.shipping_fee').html("<span class='loc_invalid'>Select location*</span>");
            }
            
            //**trigger keyup event of product_quantity textbox
            $(".product_quantity").trigger( "keyup" );
            
            //** disable attributes with no quantity option
            var show_ids = new Array();

            /*  The algorithm determines the attributes to be displayed using the following steps:
            *  1. If the combination contains all of the selected attributes, enable the other attributes in the combination
            *  2. Get combinations wherein each attribute to be displayed is included:
            *     If an attribute in the combination is in the same row as a currently active attribute    
            *     AND the selected attributes (minus the first clicked element) PLUS the attribute in question are present together 
            *     in the combination: enable the attribute. The relevance of the removing the first element of the selected
            *     attributes array is to give the algorithm a row to consider as the "free-est" row (the row wherein the user
            *     can move about the most without violating the combinations).
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
                        if(inArray(s, show_ids) === -1){
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
                    if((containsAll([id], value_arr))){        
                        $.each(value_arr, function(r,s){
                            if(($('.product_option li[data-attrid='+s[0]+']').siblings('.active')[0] !== undefined)){
                                //Remove element from the "free-est" row
                                var arr = [].concat(sel_id);
                                arr.splice(freeRowIndex, 1);
                                var n_arr = [s].concat(arr);
                                if((containsAll(n_arr, value_arr))){
                                    //if attr_id is not yet in show_ids, push it in
                                    if(inArray(s, show_ids)===-1)
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
                if((inArray(t_arr, show_ids)===-1)&&(isOptionAvailable))
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
                        //data-enable added in order to disable jqzoom swapping, see jqzoomer.js
                        $(this).parent().data('enable', 'true');
                    }
                }
            });
            
            $('#shipment_locations').change();
            
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
        //VERY IMPORTANT ASSUMPTION: the default shipment exists with the default quantity

        //Loads the defaults quantity
        var qty = JSON.parse($('#p_qty').val());
        var shipment = JSON.parse($('#p_shipment').val());

        var total_qty = 0;
        
        var firstAvailableCombination = false;

        $.each(qty, function(index, value){ 
        
            if((parseInt(value.quantity,10) !== 0)&&(firstAvailableCombination === false)){
                firstAvailableCombination = value;
            }
        
            total_qty = total_qty + parseInt(value.quantity,10);
            if((value.product_attribute_ids.length == 1)&&(parseInt(value.product_attribute_ids[0].id)==0)&&(parseInt(value.product_attribute_ids[0].is_other)==0)){
                $('.quantity').data('default','true');
                $('.product_quantity').val(1);
                $('#p_itemid').val(index);
                //if there are no attributes to choose from: enable buy button
                if(($('.product_option').find('ul.options')[0] === undefined) && (parseInt(value.quantity,10) > 0))  {
                    $('.fm1.orange_btn3').removeClass("disabled").addClass("enabled");
                }else {
            //Else click a default value
            $('.product_option:visible').find('ul.options').each(function(){
                $(this).find('li:first').trigger('click');
            });
            }
                return false;
            }
        });  
    
        $('.quantity').data('qty',total_qty);
        $('.quantity').html(total_qty);

              
        if(firstAvailableCombination){
            $.each(firstAvailableCombination.product_attribute_ids, function(idx, value){
                $('.product_option:visible .options [data-type = '+value.is_other+'][data-attrid = '+value.id+']').trigger( "click" );
            });
        }
        
        //Loads the default shipment locations
        $.each(shipment, function(index, value){
            
            if(value.location_id == 1 && value.location_type == 0){
                var firstOption = $('.shiploc option:not(.default)').first();
                firstOption.data('price',value.price);
                firstOption.prop('disabled', false);
                $.each(firstOption.nextAll(), function(){
                    $(this).prop('disabled', false);
                    $(this).data('price',value.price);
                });
                return false;
            }

            var option =  $('#locationID_' + value.location_id);
            option.data('price',value.price);
            option.prop('disabled', false);
            $.each(option.nextAll(), function(){
                if($(this).data('type') === option.data('type')){
                return false;
                }
                $(this).prop('disabled', false);
                $(this).data('price',value.price);
            }); 
        });
        

    });



    $(function(){
        $('li[data-hidden="true"]').each(function(){
            $(this).trigger( "click" );
        });

    });

    
    $(function(){
        
        $(document).on('click', '#send_registration', function() {
            $('#send_registration').html('Please wait');
            var i_id = $(".id-class").attr("id");
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var isLoggedIn = ($(".es-data[name='is-logged-in']").val() == 'true');
            var msg = 'Kindly login to qualify for this promo.';
            $.ajax({
                url : '/promo/BuyAtZero/buyAtZeroRegistration',
                type : 'post',
                dataType : 'JSON',
                data : {
                    csrfname:csrftoken,
                    id:i_id
                },
                success : function(data){
                    $('#send_registration').html('Buy Now');
                    if(data == "not-logged-in"){
                        msg = 'Kindly login to qualify for this promo.';
                        setTimeout(function() {
                            window.location = "/login";
                        }, 1000);
                    }
                    else if(data){
                        msg = "Congratulations! You now have the chance to win this  " + 
                            escapeHtml($('#pname').html())  + " item! The lucky winner will be " +
                            "announced on October 31, 2014. Stay tuned for more EasyShop.ph " +
                            "promotions. ";
                    }
                    else{
                        msg = "You are already currently subscribed for this promo. " +
                            "Stay tuned to find out whether you are one of the lucky winners.";
                    }
                    
                    alert("<div style='font-size: 13px; font-weight: lighter;'>" + msg + "</div>")
                }
            });
        })
        
        $(document).on('click', '#send.enabled', function(){
            
                var productId = $(".id-class").attr("id");
                var quantity = $(".product_quantity").val();
                var csrftoken = $("meta[name='csrf-token']").attr('content');
                var csrfname = $("meta[name='csrf-name']").attr('content');
                var optionsObject = {};
                $(".options").each(function() {
                    var $activeOption = $(this).find(".active");
                    var attrKey = $(this).attr("name");
                    var attrValue = $activeOption.attr("id");
                    var attrPrice = $activeOption.data("price");
                    optionsObject[attrKey] = attrValue + '~' + attrPrice;
                });

                $.ajax({
                    url: "/cart/doAddItem",
                    type:"POST",
                    dataType:"JSON",
                    data:{productId:productId,quantity:quantity,options:optionsObject,csrfname:csrftoken},
                    success:function(data){

                        if(!data.isLoggedIn){
                            window.location.replace("/login");
                        }
                        
                        if(data.isSuccessful){
                            window.location.replace("/cart");
                        }
                        else{
                            alert("We cannot process your request at this time. Please try again in a few moment");
                        }
                    }

                });

        });

    });

    $(function(){
        $('.reviews_content').on('click','#see_more_reviews', function(event){
            $('#more_review_loading_img').show();
            $('#see_more_reviews').hide();
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            $.post(config.base_url+'product/get_more_reviews',{last_id:$('#lastreview').prop('value'), csrfname : csrftoken}, 
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
                        $('#see_more_reviews').html('No more reviews to see.');
                    }
                }
            );
            return false;
        });
        
        $("body").on('change','#shipment_locations', function(){
            var selected = $('#shipment_locations :selected');
            if(selected.val() == 0){
                $('.shipping_fee').html("<span class='loc_invalid'>Select location*</span>");
            }
            else{
                var fee = parseFloat(selected.data('price'));
                if(fee > 0){
                    $('.shipping_fee').html("<span class='shipping_fee_php'>PHP <span><span class='shipping_fee_price'>"+fee.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"</span>");
                }
                else{
                    //$('.shipping_fee').html("<span class='free_shipping_logo'></span>");
                    $('.shipping_fee').html("<span class='free_shipping_logo'><span class='shipping_fee_php' style='font-weight:bold; font-size:10px; color:#F18200;'>Free shipping</span></span>");
                }
                
            
                
            }
        });
        
    });


})(jQuery);

/**
 * This function checks if all elements in needles exists in
 * the haystack. This does not compare if two arrays are equal.
 * 
 * @param object[] needles
 * @param object[][] haystack
 * @return boolean
 */
function containsAll(needles, haystack)
{ 
    for(var i = 0, len = needles.length; i<len; i++){
        if(inArray(needles[i], haystack) === -1){
            return false;
        }
    }
    return true;
}

/**
 * Returns the index of an element in the array
 * 
 * @param object[] needle
 * @param object[][] haystack
 * @return integer
 */
function inArray(needle, haystack)
{
    for(j = 0, len = haystack.length; j<len; j++){
        if(arraysEqual(needle, haystack[j])){
            return j;
        }
    }
    return -1;
}


/**
 * Checks if two arrays are equal (same values and same order)
 * 
 * @param string[] a
 * @param string[] b
 * @return integer
 * 
 */
function arraysEqual(a, b) 
{
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



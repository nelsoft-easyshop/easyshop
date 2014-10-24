/*******************	HTML Decoder	********************************/
function htmlDecode(value) {
    if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}

(function(){

    $.scrollUp({
        scrollName: 'scrollUp', // Element ID
        scrollDistance: 300, // Distance from top/bottom before showing element (px)
        scrollFrom: 'top', // 'top' or 'bottom'
        scrollSpeed: 300, // Speed back to top (ms)
        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
        animation: 'fade', // Fade, slide, none
        animationInSpeed: 100, // Animation in speed (ms)
        animationOutSpeed: 100, // Animation out speed (ms)
        scrollText: '', // Text for element, can contain HTML
        scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
        scrollImg: false, // Set true to use image
        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647, // Z-Index for the overlay
    });
    
    $(document).ready(function(){
        var options = { direction: 'right' };
        // Set the duration (default: 400 milliseconds)
        var duration = 300;
        var fadein_duration = 900;
        
        $('#following-lnk').click(function(){
            $(".user-tab").fadeOut(duration);
            $('#following-tab').show('slide', options, duration);
            $(".view_all_feedbacks").fadeIn(fadein_duration);
        });
        
         $('#follower-lnk').click(function(){
            $(".user-tab").fadeOut(duration);
            $('#follower-tab').show('slide', options, duration);
            $(".view_all_feedbacks").fadeIn(fadein_duration);
        });
         
        $(".view_all_feedbacks").click(function() {
            $(".view_all_feedbacks,.user-tab").fadeOut();
            $("#dashboard-feedbacks").toggle('slide', options, duration);
        });
        
        $(".hide_all_feedbacks,.subscription_cont .close").click(function() {
            $(".view_all_feedbacks,.vendor_products_wrapper").fadeIn(fadein_duration);
            $("#dashboard-feedbacks").hide('slide', options, duration);
        });

        $('#dashboard-feedbacks').hide();

    });
    
    
    $(document).ready(function(){
        var tab = $('#tab-cmd').val();
        switch (tab) {
            case 'following':
                $('#following-lnk').click();
                break;
            case 'follower':
                $('#follower-lnk').click();
                break;
            case 'feedback':
                $(".view_all_feedbacks").click();
                break;
        }
    });

    $(document).ready(function(){
        $('.v_loadmore').on('click',function(){
            var cookie = $.cookie("grd");
            if(cookie != null){
                $.removeCookie("grd");
            }
            $.cookie("grd", "grid", {path: "/", secure: false});    
        });
    });
    
})(jQuery);




(function(){

    $(document).ready(function(){
        var userid = parseInt($('user-id').val(),10);
        var sellerid = parseInt($('seller-id').val(),10);
        
        if (userid == sellerid || userid == 0  ) {
            $(".vendor-msg-modal").remove();
            $("#modal-background").remove();
            $("#modal-container").remove();
        }
            
        $("#modal-background, #modal-close").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").hide();
            $("#msg-message").val("");
        });
        $("#modal-launcher").click(function() {
            $("#modal-container, #modal-background").toggleClass("active");
            $("#modal-container").show();
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
                alert("Message Sent");
                    }
                });
        });
    });
    
    $('.vendor_edit_con').siblings('.vendor_desc_dis_con').children('p').css({
        "border":"1px solid #cecece",
        "padding":"5px",
        "overflow-y":"scroll"
    });
    
})(jQuery);



/**	Populate product item dislay **/
$(document).ready(function(){

    /**
     *   Fix for the stupid behaviour of jpagination with chrome when pressing the back button.
     */
    $('.sch_box').val('');
    $('input.items').each(function(k,v){
        $(this).val($(this).data('value'));
    });
});

var memconf = {
	csrftoken: $("meta[name='csrf-token']").attr('content'),
	csrfname: $("meta[name='csrf-name']").attr('content'),
	ajaxStat: null,
	active: {
		schVal: '',
		sortVal: 1,
		sortOrder: 1,
		deleteStatus: 0,
	},
	deleted: {
		schVal: '',
		sortVal: 1,
		sortOrder: 1,
		deleteStatus: 1,
	},
	itemPerPage: 10,
	mid: parseInt($('#mid').val()),
	bannerWidth: 980,
	bannerHeight: 270,
	form: null
};

/*******************************************************************************************/
/************************	NEW VENDOR FUNCTIONS	****************************************/
/*******************************************************************************************/

/***********	BANNER EDIT 	****************/
$(function(){
	$('.img_edit').on('click',function(){
		memconf.form = $(this).siblings('form');
		$(this).siblings('form').children('input.img_file_input').click();
	});
	
	$('input.img_file_input').on('change',function(){
		var oldIE;
        var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
        
		if($('html').is('.ie6, .ie7, .ie8, .ie9')){
			oldIE = true;
		}

		if(oldIE || isSafari){
			memconf.form.submit();
		} 
        else{
			imageprev(this);
		}
	});
	
});

function imageprev(input) {

	var jcrop_api, width, height;
	
    if (input.files && input.files[0] && input.files[0].type.match(/(gif|png|jpeg|jpg)/g) && input.files[0].size <= 5000000) {
		var reader = new FileReader();

		reader.onload = function(e){
			var image = new Image();
			image.src = e.target.result;
			image.onload = function(){
				width = this.width;
				height = this.height;
				$('#user_image_prev').attr('src', this.src);
				if(width >10 && height > 10 && width <= 5000 && height <= 5000)
					deploy_imageprev();
				else if(width > 5000 || height > 5000)
					alert('Failed to upload image. Max image dimensions: 5000px x 5000px');
				else
					$('#div_user_image_prev span:first').html('Preview');
			}
		}
		reader.readAsDataURL(input.files[0]);
    }
	else
		alert('You can only upload gif|png|jpeg|jpg files at a max size of 5MB! ');
	
	
	function deploy_imageprev(){
		$('#div_user_image_prev').modal({
				escClose: false,
				containerCss:{
					maxWidth: 600,
					minWidth: 505,
					maxHeight: 600
				},
				onShow: function(){
					$('#div_user_image_prev button').on('click', function(){
						memconf.form.submit();
						$.modal.close();
					});
					if(memconf.form.data('tag') == 'banner'){
						jcrop_api = $.Jcrop($('#user_image_prev'),{
							aspectRatio: memconf.bannerWidth/memconf.bannerHeight,
							allowSelect: false,
							setSelect:[0,0,width*0.5,height*0.5],
							boxWidth: 500,
							boxHeight: 500,
							minSize: [width*0.3,height*0.3],
							trueSize: [width,height],
							onChange: showCoords,
							onSelect: showCoords,
							onRelease: resetCoords
						});
					}else if(memconf.form.data('tag') == 'avatar'){
						jcrop_api = $.Jcrop($('#user_image_prev'),{
							aspectRatio: width/height,
							boxWidth: 500,
							boxHeight: 500,
							minSize: [width*0.1,height*0.1],
							trueSize: [width,height],
							onChange: showCoords,
							onSelect: showCoords,
							onRelease: resetCoords
						});
					}
					this.setPosition();
				},
				onClose: function(){
					$('#user_image_prev').attr('src', '');
					resetCoords();
					jcrop_api.destroy();
					$('#div_user_image_prev span').after('<img src="" id="user_image_prev">');
					$.modal.close();
				}
			});
	}
}

function showCoords(c){
	memconf.form.children('input.image_x').val(c.x);
	memconf.form.children('input.image_y').val(c.y);
	memconf.form.children('input.image_w').val(c.w);
	memconf.form.children('input.image_h').val(c.h);
}

function resetCoords(){
	memconf.form.children('input.image_x').val(0);
	memconf.form.children('input.image_y').val(0);
	memconf.form.children('input.image_w').val(0);
	memconf.form.children('input.image_h').val(0);
}

/**********	VENDOR SUBSCRIPTION	**************/
(function(){

    $('.subscription_btn').on('click',function(){
        var form = $(this).closest('form');
        var $this = $(this);
        var sibling = $(this).siblings('.subscription_btn');
        
        var logInStatus = $('input[name="is-logged-in"]').val().toString();
        var vendorLink = form.find('input[name="userlink"]').val();
        
        if( logInStatus === 'true' ){
            $.post(config.base_url+'memberpage/vendorSubscription', $(form).serializeArray(), function(data){
                try{
                    var obj = jQuery.parseJSON(data);
                }
                catch(e){
                    alert('There was an error while processing your request. Please try again later.');
                    return false;
                }
                
                if(obj.result === 'success'){
                    $this.hide();
                    sibling.show();
                }
                else{
                    alert(obj.error);
                }
            });    
        }
        else{
            $.removeCookie('es_vendor_subscribe', {path: '/'});
            $.cookie('es_vendor_subscribe', vendorLink, {path: '/'});
            window.location.href = config.base_url + 'login';
        }
        
        return false;
    });

    $(document).ready(function(){
        var vendorLink = $.cookie('es_vendor_subscribe');
        var logInStatus = $('input[name="is-logged-in"]').val().toString();
        var subscribeStatus = $('#subscribe_status').val();
        var vendorName = $('#vendor_name').val();

        if( typeof vendorLink !== "undefined" && logInStatus === "true" && subscribeStatus === "unfollowed"){
            $('#follow_btn').trigger('click');
            alert("You are now following " + vendorName + "'s store!");
            
            $.removeCookie('es_vendor_subscribe', {path: '/'});
        }
    });

})(jQuery);

/*****************	STORE DESCRIPTION	******************************/
$(function(){
	$('#store_desc_echo').on('mouseover', function(){
		$(this).children('span').show();
	}).on('mouseleave', function(){
		$(this).children('span').hide();
	});
	
	$('#store_desc_edit').on('click',function(){
		$(this).parent('div').hide();
		$(this).parent('div').siblings('div').show();
	});

	$('#store_desc_submit').on('click',function(){
		var form = $(this).parent('form');
		var textarea = $(this).siblings('textarea');
		var divEchoData = $(this).closest('div').siblings('div');
		var divEditData = $(this).closest('div');
		var thisbtn = $(this);
		
		
		
		$.post(config.base_url+'memberpage/vendorStoreDesc', $(form).serializeArray(), function(data){
			thisbtn.attr('disabled', false);
			thisbtn.val('Save');
			
			try{
				var obj = jQuery.parseJSON(data);
			}
			catch(e){
				alert('There was an error while processing your request. Please try again later.');
				return false;
			}
			
			if(obj.result==='success'){
				var desc = $.trim(textarea.val());
				if( desc.length > 0 ){
					divEchoData.show();
					divEditData.hide();
					divEchoData.children('p').text(htmlDecode(desc));
				}
			}else{
				alert(obj.error);
			}
			
		});
		thisbtn.val('Saving...');
		thisbtn.attr('disabled', true);
		return false;
	});
});

/**
 *  Store Name Functions
 */
(function(){
    $(function(){

        $('#store_name_edit').on('click',function(){
            $('#user_store_echo').hide();
            $('#user_store_edit').show();
        });

        $('#store_name_cancel').on('click',function(){
            var textarea = $(this).siblings('input[name="store_name"]');
            var origName = textarea.attr('data-origname');

            $('#user_store_echo').show();
            $('#user_store_edit').hide();
            textarea.val(origName);
        });

        // Trigger store name submit on "enter" keypress
        $('#user_store_edit input[name="store_name"]').on('keypress',function(e){
            var code = e.keyCode || e.which;
            if(code===13){
                $('#store_name_submit').trigger('click');
                return false;
            }
        });

        $('#store_name_submit').on('click',function(){
            var form = $(this).closest('form');
            var thisbtn = $(this);
            var btnSet = $('#store_name_cancel, #store_name_submit');

            var storeNameTextBox = form.find('input[name="store_name"]');

            var editStoreNameField = $('#user_store_edit');
            var echoStoreNameField = $('#user_store_echo');
            var echoUserName = $('#username_echo');
            var echoStoreName = echoStoreNameField.find('h2');
            
            thisbtn.val('Saving...');
            btnSet.attr('disabled', true);

            $.post(config.base_url+'memberpage/vendorStoreName', $(form).serializeArray(), function(data){
                thisbtn.val('Save');
                btnSet.attr('disabled', false);
                
                try{
                    var obj = jQuery.parseJSON(data);
                }
                catch(e){
                    alert('There was an error while processing your request. Please try again later.');
                    return false;
                }

                if(obj.result === true){
                    var hasStoreName = obj.username !== obj.storename &&
                        obj.storename.length > 0 ? true:false;

                    if(hasStoreName){
                        echoUserName.show();
                        var newStoreName = htmlDecode(obj.storename);
                        var textboxVal = htmlDecode(obj.storename);    
                    }
                    else{
                        echoUserName.hide();
                        var newStoreName = obj.username;
                        var textboxVal = obj.username;
                    }

                    editStoreNameField.hide();
                    echoStoreNameField.show();
                    echoStoreName.text(newStoreName);
                    storeNameTextBox.val(textboxVal);
                    storeNameTextBox.attr('data-origname', textboxVal);
                }
                else{
                    alert(obj.error);
                }
            });
        });

    })
})(jQuery);


(function ($) {

    /********************   PAGING FUNCTIONS    ************************************************/
    $(document).ready(function(){
        $('#op_buyer .paging:not(:first)').hide();
        $('#op_seller .paging:not(:first)').hide();
        $('#yp_buyer .paging:not(:first)').hide();
        $('#yp_seller .paging:not(:first)').hide();
    
        $('#pagination-opbuyer').jqPagination({
            paged: function(page) {
                $('#op_buyer .paging').hide();
                $($('#op_buyer .paging')[page-1]).show();
            }
        });
        $('#pagination-opseller').jqPagination({
            paged: function(page) {
                $('#op_seller .paging').hide();
                $($('#op_seller .paging')[page-1]).show();
            }
        });
        $('#pagination-ypbuyer').jqPagination({
            paged: function(page) {
                $('#yp_buyer .paging').hide();
                $($('#yp_buyer .paging')[page-1]).show();
            }
        });
        $('#pagination-ypseller').jqPagination({
            paged: function(page) {
                $('#yp_seller .paging').hide();
                $($('#yp_seller .paging')[page-1]).show();
            }
        });
    
    });

    function triggerTab(x){
        $('.idTabs a[href="#'+x+'"]').trigger('click');
    }

    //create a stick nav
    var menuOffset = $('.vendor-sticky-nav')[0].offsetTop; // replace #menu with the id or class of the target navigation
    $(document).bind('ready scroll', function() {
        var docScroll = $(document).scrollTop();
        if (docScroll >= 455) 
            {
                if (!$('.vendor-sticky-nav').hasClass('sticky-nav-fixed')) {
                    $('.vendor-sticky-nav').addClass('sticky-nav-fixed').css({
                        top: '-155px'
                    }).stop().animate({
                        top: 0
                    }, 500);
                    
                }

                $('.vendor-content-wrapper').addClass('fixed-vendor-content');

            } 
        else 
            {
                $('.vendor-sticky-nav').removeClass('sticky-nav-fixed').removeAttr('style');
                $('.vendor-content-wrapper').removeClass('fixed-vendor-content');
            }

    });

    var $edit_profile_photo = $(".edit-profile-photo");
    var $edit_profile_photo_menu = $(".edit-profile-photo-menu");
    var $header_cart_container = $(".header-cart-container");
    var $header_cart_item_list = $(".header-cart-item-list");
    var $sticky_cart = $(".sticky-cart");
    var $stick_cart_item = $(".sticky-header-cart-item-list");

    $(document).mouseup(function (e) {

        if (!$edit_profile_photo_menu.is(e.target) // if the target of the click isn't the container...
            && $edit_profile_photo_menu.has(e.target).length === 0) // ... nor a descendant of the container
        {
           $edit_profile_photo_menu.hide(1);
        }

    });

    $edit_profile_photo.click(function() {
        $edit_profile_photo_menu.show();
    });

    $(document).mouseup(function (e) {

        if (!$header_cart_item_list.is(e.target) // if the target of the click isn't the container...
            && $header_cart_item_list.has(e.target).length === 0) // ... nor a descendant of the container
        {
           $header_cart_item_list.hide(1);
        }

    });

    $header_cart_container.click(function() {
        $header_cart_item_list.show();
    });

    $(document).mouseup(function (e) {

        if (!$stick_cart_item.is(e.target) // if the target of the click isn't the container...
            && $stick_cart_item.has(e.target).length === 0) // ... nor a descendant of the container
        {
           $stick_cart_item.hide(1);
        }

    });

    $sticky_cart.click(function() {
        $stick_cart_item.show();
    });
    

    $(document.body).on('click','.icon-grid',function() {
        
        var view = $("div.view").attr("class");
        
        if(view == "view row row-items list")
        {
            $('div.view').removeClass("view row row-items list").addClass("view row row-items grid");
            $('div.col-md-12').removeClass("col-md-12 thumb").addClass("col-xs-3 thumb");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list active-view").addClass("lv fa fa-th-list fa-2x icon-view icon-list");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid").addClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view");
        }
            
    });

    $(document).on('click','.icon-list',function() {    
            
        var view = $("div.view").attr("class");

        if(view == "view row row-items grid")
        {   
            $('div.view').removeClass("view row row-items grid").addClass("view row row-items list");
            $('div.col-lg-3').removeClass("col-xs-3").addClass("col-md-12 thumb");
            $('span.gv').removeClass("gv fa fa-th-large fa-2x icon-view icon-grid active-view").addClass("gv fa fa-th-large fa-2x icon-view icon-grid");
            $('span.lv').removeClass("lv fa fa-th-list fa-2x icon-view icon-list").addClass("lv fa fa-th-list fa-2x icon-view icon-list active-view");
        };
    });
    
    
    
    var $window = $(window),
    $stickyLeft = $('#the-sticky-div'),
    leftTop = $stickyLeft.offset().top;

    $window.scroll(function() {
        $stickyLeft.toggleClass('sticky', $window.scrollTop() > leftTop);
    });

    /***** create wishlist modal *****/

    $(document).ready(function(){

    	$('.wishlist_create').click(function (e) {
    		$("#create_wishlist").modal({position: ["25%","35%"]});
    		$('#create_wishlist').parent().removeAttr('style');
    	});

    });

})(jQuery);

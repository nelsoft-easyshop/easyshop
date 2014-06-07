$(document).ready(function() {

	$(":checkbox[name^='_subcat']").change(function(){
		var val = $(this).val();
		$(":checkbox[name^='_subcat']").not(this).prop("checked", false);			
		$('#_cat option[value=' + val + ']').attr("selected", "selected");
		$("#advsrch").submit();
	});

	$(".adv_leftpanel, #_sop, #_con, #_loc").change(function(){			
		$("#advsrch").submit();
	});

	// Product View Toggle
	
	var curCookie = $.cookie("grd");
		
	if(curCookie == "list" || curCookie == null){
		$("#list").attr("class", "list list-active");
		$("#grid").attr("class", "grid");
		$(".product").attr("class", "product-list");
	}else{
		$("#grid").attr("class", "grid grid-active");
		$("#list").attr("class", "list");
		$(".product-list").attr("class", "product");
	}
	
	$('#list').click(function() {
		$.removeCookie("grd");
		$.cookie("grd", "list", {path: "/", secure: false});
		var cookieValue = $.cookie("grd");

		$('.product').animate({opacity: 0}, function() {
			$('#grid').removeClass('grid-active');
			$('#list').addClass('list-active');
			$('.product').attr('class', 'product-list');
			$('.product-list').stop().animate({opacity: 1}, "fast");
		});
		
	});

	$('#grid').click(function() {
		$.removeCookie("grd");
		$.cookie("grd", "grid", {path: "/", secure: false});
		var cookieValue = $.cookie("grd");

		$('.product-list').animate({opacity: 0}, function() {
			$('#list').removeClass('list-active');
			$('#grid').addClass('grid-active');
			$('.product-list').attr('class', 'product');
			$('.product').stop().animate({opacity: 1}, "fast");
		});			
	});
	
	// Product View Toggle end			
	
	$("#_price1,#_price2").change(function(){
		$(this).removeClass("err");
		var val = parseFloat($(this).val());
		if (isNaN(val)){
			$(this).val('');
		}else{
			$(this).val(val.toFixed(2)); 
		}			
	});
	
	$("#btn_srch").click(function() {
		
			if($("#_cat").val() == 1){
				$(":checkbox").not(this).prop("checked", false);	
			}
			
			// Price - Start //////////////////////////////////////	
			var price1 = parseInt($("#_price1").val());
			var price2 = parseInt($("#_price2").val());
			var msg = "Invalid price range";
			var fprice1;
			var fprice2;
			
			if (isNaN(price1)){
				fprice1 = "";
			}else{
				fprice1 = price1.toFixed(2); 
			}					
			
			if (isNaN(price2)){
				fprice2 = "";
			}else{
				fprice2 = price2.toFixed(2); 
			}			
												
			if(price1 > price2){
				alert(msg);
				$("#_price2").addClass("err").focus();
				return false;
			}else if(isNaN(price1) == true && price2 > 0){
				alert(msg);
				$("#_price1").addClass("err").focus();
				return false;			
			}else if(isNaN(price2) == true && price1 > 0){
				alert(msg);
				$("#_price2").addClass("err").focus();
				return false;			
			}
			// Price - End //////////////////////////////////////					
	});

	// START OF INFINITE SCROLLING FUNCTION

    var base_url = config.base_url;
    var offset = 1;
    var request_ajax = true;
    var ajax_is_on = false;
    var objHeight = $(window).height() - 50;
    var last_scroll_top = 0;
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content'); 
    
    $(window).scroll(function(event) {
    
        var st = $(this).scrollTop();
        
        if(st > last_scroll_top){
            if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {					
                if (request_ajax === true && ajax_is_on === false) {
                    ajax_is_on = true;
                    
                    var cat = $("#_cat").val();
                    var condition = JSON.parse($('.condition').val());
                    
                    $.ajax({
                        url: base_url + 'advsrch/more',
                        data:{page_number:offset,id_cat:cat,parameters:condition,csrfname:csrftoken},
                        type: 'post',
                        async: true,
                        dataType: 'JSON',
                        onLoading:jQuery(".loading_products").html("<img src='"+ base_url +"assets/images/orange_loader.gif' />").show(),						
                        success: function(d){
                            if(d == "0"){
                                ajax_is_on = true;
                            }else{
                                $($.parseHTML(d.trim())).appendTo($('#product_content'));
                                ajax_is_on = false;
                                offset += 1;
                            }
                            jQuery(".loading_products").fadeOut(); 
                            //$(".loading_products").hide();
                        } // end of function(d)
                    }); // end of .ajax
                } // end of request ajax
            } // end of $(window).scrollTop
        } // end of st > last_scroll_top
        
        last_scroll_top = st;
    });  // end of window .scroll

	// END OF INFINITE SCROLLING FUNCTION

}); // end of document ready


$(function(){
	$("h3[id^=fld_]").click(function(){
		var getchild = "#c" + $(this).attr('id');
		var geticon = "#i" + $(this).attr('id');
		if($(getchild).is(":visible")){
			$(getchild).hide();
			$(geticon).removeClass("span_bg advsrch_toggle");
			$(geticon).addClass("span_bg advsrch");
		}else{
			$(getchild).show();
			$(geticon).removeClass("span_bg advsrch");
			$(geticon).addClass("span_bg advsrch_toggle");
		}
	});
});
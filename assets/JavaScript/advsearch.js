function removeParam(key, sourceURL) {
	var rtn = sourceURL.split("?")[0],
	param,
	params_arr = [],
	queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
	if (queryString !== "") {
		params_arr = queryString.split("&");
		for (var i = params_arr.length - 1; i >= 0; i -= 1) {
			param = params_arr[i].split("=")[0];
			if (param === key) {
				params_arr.splice(i, 1);
			}
		}
		rtn = rtn + "?" + params_arr.join("&");
	}

	return rtn;
}

$(document).ready(function() {

	$(":checkbox[name^='_subcat']").change(function(){
		var val = $(this).val();
		$(":checkbox[name^='_subcat']").not(this).prop("checked", false);			
		$('#_cat option[value=' + val + ']').attr("selected", "selected");
		$("#advsrch").submit();
	});

	$(".adv_leftpanel").change(function(){			
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
	
	$("#_sop").change(function(){
		var url = $(this).data("url");
		var srt = $(this).val();
		url = removeParam("_sop", url);
		document.location.href=url+"&_sop="+srt;
	});
	
	$("#_con").change(function(){
		var url = $(this).data("url");
		var srt = $(this).val();
		url = removeParam("_con", url);
		document.location.href=url+"&_con="+srt;
	});
	
	$("#_brnd").click(function(){
		var url = $(this).data("url");
		var srt = $(this).val();
		url = removeParam("_brnd", url);
		document.location.href=url+"&_brnd="+srt;
	});
	
	$("#_cat").change(function(){
		$(this).removeClass("err");
	});				
	
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

			// Price - Start //////////////////////////////////////	
			var price1 = parseInt($("#_price1").val());
			var price2 = parseInt($("#_price2").val());
			var url = $("#_price").data("url");
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
			}else{
				url = removeParam("_price", url);
				url = removeParam("_price1", url);
				url = removeParam("_price2", url);				
			}
			// Price - End //////////////////////////////////////					
	});
	
	$(".cbx").click(function() { // for IE
		window.location = "<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>";
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
						url: base_url + 'advsrch/scroll_product',
						data:{page_number:offset,id_cat:cat,parameters:condition,csrfname:csrftoken},
						type: 'post',
						async: false,
						dataType: 'json',
						success: function(d){
							if(d == "0"){
								ajax_is_on = true;
							}else{
								$($.parseHTML(d.trim())).appendTo($('#product_content'));
								ajax_is_on = false;
								offset += 1;
							}
						} // end of function(d)
					}); // end of .ajax
				} // end of request ajax
			} // end of $(window).scrollTop
		} // end of st > last_scroll_top
		
		last_scroll_top = st;
	});  // end of window .scroll

	// END OF INFINITE SCROLLING FUNCTION

}); // end of document ready


////////////////////////////////////////////////////

$(function(){
	$(".more_attr").hide();	
//	$(".more_attr").click(function() {
//		$(this).parent().children().show();
//		$(this).hide();
//		$(this).siblings('.less_attr').show;
//	});
//	
//	$(".less_attr").click(function() {
//		$('.adv_leftpanel').children('h3:gt(2)').nextAll().hide();
//		$('.adv_leftpanel').children('h3:gt(2)').hide();
//		$(this).siblings('.more_attr').show();
//		$(this).hide();
//	});
//});
//
//$(document).ready(function(){
//	if ($('.adv_leftpanel').length === $('.adv_leftpanel:contains("input")').length) {
//		$('.adv_leftpanel').children('h3:gt(2)').nextAll().hide();
//		$('.adv_leftpanel').children('h3:gt(2)').hide();
//		$('.adv_leftpanel').children('.more_attr').show();
//	}else{
//		$('.more_attr').hide();
//	}
});
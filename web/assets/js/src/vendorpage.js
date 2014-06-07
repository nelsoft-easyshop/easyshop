$(document).ready(function(){

	/* 
     *   Fix for the stupid behaviour of jpagination with chrome when pressing the back button.
     *   See next two lines of code.
     */
    $('.sch_box').val('');
	$('input.items').each(function(k,v){
		$(this).val($(this).data('value'));
	});
	
	var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
	var lastid = parseInt($('#last_dashboard_item_id').val());
	var mid = parseInt($('#mid').val());

	$.post(config.base_url+'memberpage/getMoreUserItems/vendor',{csrfname:csrftoken, lastid:lastid, mid:mid}, function(data){
		try{
			var obj = jQuery.parseJSON(data);
		}
		catch(e){
			alert('Failed to retrieve user product list.');
			window.location.reload(true);
			return false;
		}
		
		// Update display of active products
		var activeItems = $('#active_items');
		var activeCount = parseInt(activeItems.find('div.post_items_content').length);
		var activeRaw = $.parseHTML(obj.active); // contains TextNodes
		var newdiv = "<div class='paging' style='display:none;'></div>";
		
		if(activeRaw){
			var activeContent = $.map(activeRaw, function(val,key){if(val.nodeType == 1){return val;}});
			if(activeContent.length > 0){
				if(activeCount == 0){
					activeItems.find('p:first').remove();
					activeItems.append($(newdiv).css('display','block'));
				}
				$.each(activeContent, function(k,v){
					$(v).attr('data-order', activeCount);
					activeItems.find('div.paging:last').append(v);
					activeCount++;
					if(activeCount%10 == 0){
						activeItems.append(newdiv);
					}
				});
				$('#pagination_active').jqPagination('option', 'max_page', Math.ceil( (activeCount===0 ? 10:activeCount) /10) );
			}
		}		
	});
});


/*************** Personal Profile Dashboard circular progress bar **************/
$(function($) {
	$(".items").knob({
		change : function (value) {
			//console.log("change : " + value);
		},
		release : function (value) {
			//console.log(this.$.attr('value'));
			//console.log("release : " + value);
		},
		cancel : function () {
			//console.log("cancel : ", this);
		},
		draw : function () {

			// "tron" case
			if(this.$.data('skin') == 'tron') {

				var a = this.angle(this.cv)  // Angle
					, sa = this.startAngle          // Previous start angle
					, sat = this.startAngle         // Start angle
					, ea                            // Previous end angle
					, eat = sat + a                 // End angle
					, r = 1;

				this.g.lineWidth = this.lineWidth;

				this.o.cursor
					&& (sat = eat - 0.3)
					&& (eat = eat + 0.3);

				if (this.o.displayPrevious) {
					ea = this.startAngle + this.angle(this.v);
					this.o.cursor
						&& (sa = ea - 0.3)
						&& (ea = ea + 0.3);
					this.g.beginPath();
					this.g.strokeStyle = this.pColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
					this.g.stroke();
				}

				this.g.beginPath();
				this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
				this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
				this.g.stroke();

				this.g.lineWidth = 2;
				this.g.beginPath();
				this.g.strokeStyle = this.o.fgColor;
				this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
				this.g.stroke();

				return false;
			}
		}
	});
});

$(document).ready(function(){

	$(".show_prod_desc").click(function(){
		$(this).siblings('.item_prod_desc_content').addClass('show_desc');
		$(this).fadeOut();
	});

	$(".show_more_options").click(function(){
		$(this).siblings('.attr_hide').slideToggle();
		$(this).toggleClass("active");
	});
 
});

/********************	PAGING FUNCTIONS	************************************************/

$(document).ready(function(){
	$('#active_items .paging:not(:first)').hide();
	$('#deleted_items .paging:not(:first)').hide();
	
	$('#bought .paging:not(:first)').hide();
	$('#sold .paging:not(:first)').hide();
	
	$('#op_buyer .paging:not(:first)').hide();
	$('#op_seller .paging:not(:first)').hide();
	$('#yp_buyer .paging:not(:first)').hide();
	$('#yp_seller .paging:not(:first)').hide();
	
	setDefaultActivePagination();
	
	$('#pagination_deleted').jqPagination({
		paged: function(page) {
		    $('#deleted_items .paging').hide();
			$($('#deleted_items .paging')[page - 1]).show();
		}
	});
	
	$('#pagination-bought').jqPagination({
		paged: function(page) {
			$('#bought .paging').hide();
			$($('#bought .paging')[page-1]).show();
		}
	});
	
	$('#pagination-sold').jqPagination({
		paged: function(page) {
			$('#bought .paging').hide();
			$($('#bought .paging')[page-1]).show();
		}
	});
	
	
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

function setDefaultActivePagination() {
	$('#pagination_active').jqPagination({
		paged: function(page) {
		    $('#active_items .paging').hide();
			$($('#active_items .paging')[page - 1]).show();
		}
	});
	$('#pagination_active').jqPagination('option','current_page', 1);
}

function setFilterResultActivePagination(resultCounter){
	$('#pagination_active').jqPagination('destroy');
	$('#pagination_active').jqPagination({
		max_page: Math.ceil((resultCounter===0 ? 10:resultCounter) / 10),
		paged: function(page) {
			$('#active_items div.filter_result').hide();
			$($('#active_items div.filter_result')[page-1]).show();
		}
	});
	$('#pagination_active').jqPagination('option', 'current_page', 1);
	$('#active_items div.filter_result:first').show();
}

/***** create wishlist modal *****/

$(document).ready(function(){
	 $('.wishlist_create').click(function (e) {
		$("#create_wishlist").modal({position: ["25%","35%"]});
		$('#create_wishlist').parent().removeAttr('style');
		});

});


/******************	DASHBOARD - ACTIVE TAB Search Box	********************/
$(function(){
	var schResult = [];
	var schValue = '';
	
	$('#active_schbtn').on('click', function(){
		// Remove filter result and re-append new one
		var divActiveItems = $('#active_items');
		divActiveItems.children('div.filter_result').remove();
		divActiveItems.append('<div class="filter_result" style="display:none;"></div>');
		var filterDiv = divActiveItems.children('div.filter_result:last');
		
		var resultCounter = 0;
		var schValue = $('#schbox_active').val().toLowerCase().replace(/\s/g,'');
		$('#active_sort').val('date');
		$('#active_sortorder').removeClass('rotate_arrow');
		
		if(schValue !== ''){
			var divPaging = divActiveItems.children('div.paging');
			divPaging.hide();
			
			//cycle through each Product Title
			divPaging.children('div.post_items_content').each(function(){
				var prodTitle = $(this).find('div.post_item_content_right').find('.post_item_product_title a').text();
				prodTitle = prodTitle.toLowerCase().replace(/\s/g,'');
				
				// Search for search string in product title
				if(prodTitle.indexOf(schValue) != -1){
					if(resultCounter % 10 === 0 && resultCounter !== 0){
						$('#active_items').append('<div class="filter_result" style="display:none;"></div>');
						filterDiv = $('#active_items div.filter_result:last');
					}
					filterDiv.append($(this).clone());
					resultCounter++;
				}
			});
			setFilterResultActivePagination(resultCounter);
			
			if( !$('#active_sort').hasClass('hasSearch') ) {
				$('#active_sort').addClass('hasSearch');
			}
		}
		else if(schValue === ''){
			divActiveItems.children('div.filter_result').remove();
			divActiveItems.children('div.paging:first').show();
			$('#pagination_active').jqPagination('destroy');
			setDefaultActivePagination();
			$('#active_sort').removeClass('hasSearch');
		}
		
	});
	
	// Trigger Search on 'Enter' key press
	$('#schbox_active').on('keydown', function(e){
		var code = e.keyCode || e.which;
		if(code===13){
			$('#active_schbtn').trigger('click');
		}
	});
	
});


/*******************	ACTIVE SORT	**************************/
$(function(){
	
	function sortNameDesc(a,b){
		return $(a).find('.product_title_container').find('a').text().toLowerCase() < $(b).find('.product_title_container').find('a').text().toLowerCase() ? 1 : -1;
	}
	
	function sortPriceDesc(a,b){
		var pricea = parseFloat($(a).find('.price_container').attr('data-prodprice'));
		var priceb = parseFloat($(b).find('.price_container').attr('data-prodprice'));
		return priceb-pricea;
	}
	
	function sortDateDesc(a,b){
		var datea = $(a).attr('data-order');
		var dateb = $(b).attr('data-order');
		return datea-dateb;
	}
	
	$('#active_sort').on('change', function(){
		var selectedOption = $(this).find('option:selected');
		var sortVals = [];
		var resultCounter = 0;
		
		if( $(this).hasClass('hasSearch') ){
			var parentDiv = $('#active_items div.filter_result').find('div.post_items_content');
			var contDiv = $('#active_items div.filter_result');
		}
		else{
			var parentDiv = $('#active_items div.paging').find('div.post_items_content');
			var contDiv = $('#active_items div.paging');
		}
		
		switch(selectedOption.val()){
			case 'date':
				sortVals = parentDiv.sort(sortDateDesc);
				break;
			case 'name':
				sortVals = parentDiv.sort(sortNameDesc);
				break;
			case 'price':
				sortVals = parentDiv.sort(sortPriceDesc);
				break;
			default:
				break;
		}
		
		if( $('#active_sortorder').hasClass('rotate_arrow') ){
			sortVals = $(sortVals.get().reverse());
		}
		
		// Re-order results
		var resultCounter = divPosition = 0;
		contDiv.children().remove();
		$.each(sortVals, function(k,v){
			if(resultCounter === 10){
				resultCounter = 0;
				divPosition++;
			}
			contDiv.eq(divPosition).append($(v));
			resultCounter++;
		});
		
	});
	
	$('#active_sortorder').on('click', function(){
		if( $('#active_items div.filter_result').length !==0 ){
			var divPostItems = $('#active_items div.filter_result div.post_items_content');
			var divCont = $('#active_items div.filter_result');
		}
		else{
			var divPostItems = $('#active_items div.paging div.post_items_content');
			var divCont = $('#active_items div.paging');
		}
		var resultCounter = divPosition = 0;
		$(divPostItems.get().reverse()).each(function(){
			if(resultCounter === 10){
				resultCounter = 0;
				divPosition++;
			}
			divCont.eq(divPosition).append($(this).clone());
			$(this).remove();
			resultCounter++;
		});
	});
	
});

/******* rotate sort arrow when click *****/
$(".arrow_sort").on("click", function () {
    $(this).toggleClass("rotate_arrow");
});

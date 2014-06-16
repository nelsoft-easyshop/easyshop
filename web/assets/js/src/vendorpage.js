/**	Populate product item display **/
$(document).ready(function(){
		
    /* 
     *   Fix for the stupid behaviour of jpagination with chrome when pressing the back button.
     *   See next two lines of code.
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
	mid: parseInt($('#mid').val())
};

function ItemListAjax(ItemDiv,start,pageindex,count=false){
	var loadingDiv = ItemDiv.children('div.page_load');
	var key = ItemDiv.data('key');
	var thisdiv = ItemDiv.children('div.paging[data-page="'+pageindex+'"]');
	var c = count ? 'count' : '';
	
	memconf.ajaxStat = jQuery.ajax({
		type: "GET",
		url: config.base_url+'memberpage/getMoreUserItems/vendor',
		data: "s="+memconf[key].deleteStatus+"&p="+start+"&"+memconf.csrfname+"="+memconf.csrftoken+"&nf="+memconf[key].schVal+
			"&of="+memconf[key].sortVal+"&osf="+memconf[key].sortOrder+"&c="+c+"&mid="+memconf.mid,
		beforeSend: function(){
			if(memconf.ajaxStat != null){
				memconf.ajaxStat.abort();
			}
			loadingDiv.show();
		},
		success: function(data){
			memconf.ajaxStat = null;
			loadingDiv.hide();
			try{
				var obj = jQuery.parseJSON(data);
			}
			catch(e){
				alert('Failed to retrieve user product list.');
				return false;
			}
			
			if(count){
				var pagingDivBtn = ItemDiv.children('div.pagination');
				pagingDivBtn.jqPagination('option', 'current_page', 1);
				if(obj.count === 0){
					thisdiv.html('<h2>Search returned no results.</h2>');
					pagingDivBtn.jqPagination('option', 'max_page', 1);
				}else{
					pagingDivBtn.jqPagination('option', 'max_page', Math.ceil(obj.count/memconf.itemPerPage));
				}
				memconf.ajaxStat.abort(); //abort all ajax triggered by updating pagination page
				loadingDiv.hide();
				thisdiv.show();
			}
			
			var htmlData = $.parseHTML(obj.html); // contains TextNodes
			if(htmlData){
				var activeContent = $.map(htmlData, function(val,key){if(val.nodeType == 1){return val;}});
				if(activeContent.length > 0){
					$.each(activeContent, function(k,v){
						$(v).find('form').append('<input type="hidden" name="'+memconf.csrfname+'" value="'+memconf.csrftoken+'">');
						thisdiv.append(v);
					});
				}
				thisdiv.show();
			}
		}
	});//close ajax
}

/*********	ACTIVE and DELETED PRODUCTS AJAX PAGING	************/
$(document).ready(function(){
	$('#active_items .paging:not(:first)').hide();
	$('#deleted_items .paging:not(:first)').hide();
	
	defaultPaging($('#pagination_active'));
	defaultPaging($('#pagination_deleted'));
});

function defaultPaging(pagingDivBtn){
	var ItemDiv = pagingDivBtn.closest('div.dashboard_table');
	$(pagingDivBtn).jqPagination({
		paged: function(page){
		    var start = (page-1) * memconf.itemPerPage;
			var pageindex = page-1;
			
			ItemDiv.children('div.paging').hide();
			
			if( ItemDiv.find('div[data-page="'+pageindex+'"] div.post_items_content').length == 0 ){
				if( ItemDiv.children('div[data-page="'+pageindex+'"]').length == 0 ){
					ItemDiv.append("<div class='paging' data-page='"+pageindex+"' style='display:none;'></div>");
				}
				ItemListAjax(ItemDiv,start,pageindex);
				
			}else{
				ItemDiv.children(' .paging[data-page="'+pageindex+'"]').show();
			}
			
		}
	});
}

/******************* Search Functions ***********************/
$(document).ready(function(){
	$('span.sch_btn').on('click',function(){
		var ItemDiv = $(this).closest('div.dashboard_table');
		var key = ItemDiv.data('key');
		var pagingDivBtn = ItemDiv.children('div.pagination');
		
		var schVal = $.trim($(this).siblings('input.sch_box').val());
		memconf[key].schVal = schVal;
		
		ItemDiv.children('div.paging:not(:first)').remove();
		ItemDiv.find('div.post_items_content').remove();
		ItemDiv.children('div.paging:first').show();
		ItemDiv.find('div.paging:first h2').remove();
		if(schVal.length > 0){
			ItemListAjax(ItemDiv,0,0,true); // true = update maxpage of pagination
		}else{
			pagingDivBtn.jqPagination('option','max_page',pagingDivBtn.children('input').data('origmaxpage'));
			ItemListAjax(ItemDiv,0,0);
		}
	});
	
	// Trigger Search on 'Enter' key press
	$('.sch_box').on('keydown', function(e){
		var code = e.keyCode || e.which;
		if(code===13){
			$(this).siblings('.sch_btn').trigger('click');
			return false;
		}
	});
});


/******************* Sort Functions ***********************/
$(document).ready(function(){
	$('select.sort_select').on('change',function(){
		var ItemDiv = $(this).closest('div.dashboard_table');
		var key = ItemDiv.data('key');
		var pagingDivBtn = ItemDiv.children('div.pagination');
		
		memconf[key].sortVal = $(this).val();
		
		ItemDiv.children('div.paging:not(:first)').remove();
		ItemDiv.find('div.post_items_content').remove();
		ItemDiv.children('div.paging:first').show();
		pagingDivBtn.jqPagination('option','current_page', 1);
	});
	
	$('.arrow_sort').on('click', function(){
		var ItemDiv = $(this).closest('div.dashboard_table');
		var key = ItemDiv.data('key');
		var pagingDivBtn = ItemDiv.children('div.pagination');
		
		if( ! $(this).hasClass('rotate_arrow') ){
			memconf[key].sortOrder = 1;
		}else{
			memconf[key].sortOrder = 2;
		}
		
		ItemDiv.children('div.paging:not(:first)').remove();
		ItemDiv.find('div.post_items_content').remove();
		ItemDiv.children('div.paging:first').show();
		pagingDivBtn.jqPagination('option','current_page', 1);
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

	$('div.dashboard_table').on('click', '.show_prod_desc', function(){
		$(this).siblings('.item_prod_desc_content').addClass('show_desc');
		$(this).fadeOut();
	});

	$('div.dashboard_table').on('click', '.show_more_options', function(){
		$(this).siblings('.attr_hide').slideToggle();
		$(this).toggleClass("active");
	});
 
});

/********************	PAGING FUNCTIONS	************************************************/

$(document).ready(function(){
	
	$('#bought .paging:not(:first)').hide();
	$('#sold .paging:not(:first)').hide();
	
	$('#op_buyer .paging:not(:first)').hide();
	$('#op_seller .paging:not(:first)').hide();
	$('#yp_buyer .paging:not(:first)').hide();
	$('#yp_seller .paging:not(:first)').hide();
	
	
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



/******* rotate sort arrow when click *****/
$(".arrow_sort").on("click", function () {
    $(this).toggleClass("rotate_arrow");
});

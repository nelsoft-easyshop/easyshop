/*******************	HTML Decoder	********************************/
function htmlDecode(value) {
	if (value) {
        return $('<div />').html(value).text();
    } else {
        return '';
    }
}

/**	Populate product item dislay **/
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
		if ($('html').is('.ie6, .ie7, .ie8, .ie9')) {
			oldIE = true;
		}

		if (oldIE) {
			console.log(memconf.form);
			memconf.form.submit();
			console.log('old');
		} else {
			imageprev(this);
			console.log('new');
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
$(function(){

	$('.subscription_btn').on('click',function(){
		var form = $(this).closest('form');
		var $this = $(this);
		var sibling = $(this).siblings('.subscription_btn');
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
		return false;
	});
	
});

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


function ItemListAjax(ItemDiv,start,pageindex,count_i){

	var count = typeof(count_i) !== 'undefined' ? count_i : false;

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


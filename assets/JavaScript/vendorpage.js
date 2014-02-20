
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
	
	$('#pagination_active').jqPagination({
		paged: function(page) {
		    $('#active_items .paging').hide();
			$($('#active_items .paging')[page - 1]).show();
		}
	});
	
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


/***** create wishlist modal *****/

$(document).ready(function(){
	 $('.wishlist_create').click(function (e) {
		$("#create_wishlist").modal({position: ["25%","35%"]});
		$('#create_wishlist').parent().removeAttr('style');
		});

	 });
		

/*
$(document).ready(function(){
	$("#view_map").click(function(){       
	var streetno = $("#streetno").val();
	var streetname = $("#streetname").val();
	var barangay = $("#barangay").val();
	var citytown = $("#citytown").val();
	var country = $("#country").val();
	var address = streetno + " " + streetname + " Street " + ", " + barangay + " " + citytown + ", " + country;
	$.ajax({
		async:true,
		url:config.base_url+"memberpage/toCoordinates",
		type:"POST",
		dataType:"JSON",
		data:{address:address},
		success:function(data){
			if(data['lat']==false || data['lng']==false){
				alert("Cannot retrieve map,Address is invalid");
			}else{
				var myLatlng =  new google.maps.LatLng(data['lat'],data['lng']);
				$("#map").show();
				google.maps.event.addDomListener(window, 'load', initialize(myLatlng));
			}
		}

		});
	});

	function initialize(myLatlng) {
	var mapOptions = {
	  center:myLatlng,
	  zoom: 15
	};
	var map = new google.maps.Map(document.getElementById("map-canvas"),
		mapOptions);
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title:"You! :)"
		});
	}
});

$(document).ready(function(){
    
	$('#close').click(function () {
		$(this).parent('#map').fadeOut();
		$(this).parent('#map').siblings('#map-canvas').fadeOut();
		$(this).parent('#map').siblings('.view_map_btn').find('#view_map').fadeIn();
	});

	$('#view_map').click(function () {
		$(this).fadeOut();
		$(this).parent('div').siblings('#map-canvas').addClass('map_canvas');
		$(this).parent('div').siblings('#map-canvas').fadeIn();
	});

});

*/

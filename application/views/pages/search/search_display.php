<?php
$myurl = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']);

#checking multidimensional array 
function in_array_r($needle, $haystack, $strict = false) {
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
			return true;
		}
	}
	return false;
}# checking end	

?>
<link rel="stylesheet" href="<?=base_url()?>assets/css/product_search_category.css?ver=1.0" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/grid_list_style.css?ver=1.0" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?=base_url()?>assets/css/style_new.css?ver=1.0" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.bxslider.css?ver=1.0" type="text/css" media="screen"/>
<style type="text/css">
.err{
	-webkit-box-shadow: 0px 0px 2px 2px #FF0000;
	-moz-box-shadow: 0px 0px 2px 2px #FF0000;
	box-shadow: 0px 0px 2px 2px #FF0000;	
}
</style>
<div class="wrapper" id="main_search_container">
  <!-- Search ------------------>  
  <div class="left_attribute">
	<h3>Price</h3>
 	<input type="text" id="_price1" maxlength=10 size=6 value="<?php echo $this->input->get('_price1');?>">to<input type="text" id="_price2" maxlength=10 size=6 value="<?php echo $this->input->get('_price2');?>"><input class="price" data-url="<?php echo $myurl . '&price=';?>" type="button" value=">>"/>
	
	<h3>Item Condtion</h3>&nbsp;
	<?php $con = $this->input->get('_con'); ?>
	<select title="Sort Item Condition" data-url="<?php echo $myurl . '&_con=';?>" id="_con">
		<option value="">- All -</option>          
		<?php foreach($this->lang->line('product_condition') as $x): ?>
			<option value="<?php echo $x;?>" <?php if($con == $x){?>selected="selected"<?php } ?>><?php echo $x; ?></option>
		<?php endforeach; ?>
	</select>
	<?php
	
	$url = $myurl;

	if (isset($arrayofparams)) {

    	foreach ($arrayofparams as $row => $value) {
			
			echo "<h3 class='title'>". $value['name'] ."<br></h3> "; 

			$attr_group = $value['name'];
			foreach ($value[0] as $row => $attr_values) {			
				
				$check = "";
				$get_group = $this->input->get($attr_group);
				
				if (isset($get_group)) {
					
					list($file, $parameters) = explode('?', $url);
					parse_str($parameters, $output);

					if (in_array_r($attr_values, $output)) {
						
						$rain_link = "";
						$arr_link = "";	
						$pln_lin = "";					
						foreach($output as $name => $values){	
							if(is_array($values)){
								foreach($values as $row => $values_o){
									if(isset($values_o)){
										if($attr_values != $values_o){
											$arr_link = $arr_link . "&". str_replace(' ','',$name) ."[". $row ."]=". $values_o;	
										}
									}
								}
							}else{
								$pln_lin = $pln_lin . "&" . $name . "=" . $values;
							}														
						}
						
						$rain_link = $arr_link . $pln_lin;
						$result = $file . '?' . $rain_link; // uncheck
						$link = $result;
						$check = ' checked="checked"';
						
					}else{
						unset($output[$attr_values]);
						if(isset($get_group)){
							$arr_group[] = $get_group;
						}

						$result = $file . '?' . http_build_query($output) . '&' . str_replace(' ','',$attr_group) . '['. $row .']=' . urlencode($attr_values);
						$link = $result;
					}											

				}
				
				echo "<a href='". $link ."' style='display: block;' class='cbx'>";
				echo "<input type='checkbox' ". $check .">" . $attr_values;
				echo "<br></a>";
				
			} // for each attr_values
		} // for each arrayofparams
	} // isset($arrayofparams)
	
    ?> 
  	<h3 class="title">&nbsp;</h3>
	<p class="more_attr">More</p>
	<p class="less_attr">Less</p>  
  </div>
  
  <!-- Products ------------------>
  
  <div class="right_product">
    <p class="search_result"><!-- Showing 1 - 48 of 13,152 Results --></p>
    Sort by:
	<?php $sop = $this->input->get('_sop'); ?>
    <select data-url="<?php echo $myurl . '&_sop=';?>" id="_sop">
		<option value="popular" <?php if($sop == "popular"){?>selected="selected"<?php } ?>>Popular</option>
		<option value="hot" <?php if($sop == "hot"){?>selected="selected"<?php } ?>>Hot</option>		
		<option value="new" <?php if($sop == "new"){?>selected="selected"<?php } ?>>New</option>
		<option value="con" <?php if($sop == "con"){?>selected="selected"<?php } ?>>Item Condition</option>
    </select>
    <!-- Buttons start -->
    <div id="list" class="list list-active"></div>
    <div id="grid" class="grid"></div>
    <!-- Buttons end -->
    <div class="clear"></div>
    <div id="product_content">
      <?php
        if (isset($items)) {
            for ($i = 0; $i < sizeof($items); $i++) {
                $pic = explode('/', $items[$i]['product_image_path']);
      ?>
				<div class="product-list"> <a href="<?= base_url() ?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo urlencode($items[$i]['product_name']); ?>.html"><img alt="<?php echo $items[$i]['product_name']; ?>" src="<?php echo base_url() . $pic[0] . '/' . $pic[1] . '/' . $pic[2] . '/' . $pic[3] . '/' . 'categoryview' . '/' . $pic[4]; ?>"></a>
					<h3 style="-o-text-overflow: ellipsis; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; width:225px; ">
						<a href="<?= base_url() ?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo urlencode($items[$i]['product_name']); ?>.php"><?php echo $items[$i]['product_name']; ?></a>
					</h3>
					<div class="price-cnt">
					  <div class="price"> <span>&#8369;</span> <?php echo number_format($items[$i]['product_price'], 2); ?> </div>
					</div>
					<div class="product_info_bottom">
					  <div>Condition: <strong><?php echo $items[$i]['product_condition']; ?></strong></div>
					  <!-- <div>Sold: <strong>32</strong></div> --> 
					</div>
					<p><?php echo $items[$i]['product_brief']; ?></p>
				</div>
      <?php
			} // end of loop
		} // end of isset
      ?>
    </div>
  </div>
  <!-- Products ------------------>
</div>
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.easing.min.js" type="text/javascript"></script> 
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.scrollUp.min.js" type="text/javascript"></script> 
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.bxslider.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
	$.scrollUp({
		scrollName: 'scrollUp', // Element ID
		scrollDistance: 300, // Distance from top/bottom before showing element (px)
		scrollFrom: 'top', // 'top' or 'bottom'
		scrollSpeed: 300, // Speed back to top (ms)
		easingType: 'linear', // Scroll to top easing (see http://easings.net/)
		animation: 'fade', // Fade, slide, none
		animationInSpeed: 200, // Animation in speed (ms)
		animationOutSpeed: 200, // Animation out speed (ms)
		scrollText: 'Scroll to top', // Text for element, can contain HTML
		scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
		scrollImg: false, // Set true to use image
		activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
		zIndex: 2147483647 // Z-Index for the overlay
	});
});
</script>
<script type="text/javascript">
	$(document).ready(function() {

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
		
		// Price - Start //////////////////////////////////////
		$("#_price1,#_price2").change(function(){
			$(this).removeClass("err");
			var val = parseFloat($(this).val());
			if (isNaN(val)){
				$(this).val('');
			}else{
				$(this).val(val.toFixed(2)); 
			}			
		});
		
		$(".price").click(function() {			
			var price1 = parseInt($("#_price1").val());
			var price2 = parseInt($("#_price2").val());
			var url = $(this).data("url");
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
			}else if(price1 == "" && price2 > 0){
				alert(msg);
				$("#_price1").addClass("err").focus();
				return false;			
			}else if(price1 > 0 && price2 == ""){
				alert(msg);
				$("#_price2").addClass("err").focus();
				return false;			
			}else{
				url = removeParam("price", url);
				url = removeParam("price1", url);
				url = removeParam("price2", url);
								
				document.location.href=url+"&_price1="+fprice1+"&_price2="+fprice2;
			}
			// Price - Start //////////////////////////////////////				
			
		});
		
		$(".cbx").click(function() { // for IE
            window.location = "<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']); ?>";
        });
						   
		// START OF INFINITE SCROLLING FUNCTION

        var base_url = '<?php echo base_url(); ?>';
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
						
						<?php 
							$condition = $this->input->get();
						?>
						
						$.ajax({
							url: base_url + 'advance_search/load_other_product',
							data:{page_number:offset,id_cat:'<?php echo $this->input->get('_cat');?>',parameters:<?php echo json_encode($condition)?>, csrfname : csrftoken},
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
		//console.log(rtn);
		return rtn;
	}
</script>
<script type="text/javascript">
$(function(){
	$(".more_attr").click(function() {
		$(this).parent().children().show();
		$(this).hide();
		$(this).siblings('.less_attr').show;
	});
	
	$(".less_attr").click(function() {
		$('.left_attribute').children('h3:gt(2)').nextAll().hide();
		$('.left_attribute').children('h3:gt(2)').hide();
		$(this).siblings('.more_attr').show();
		$(this).hide();
	});
});

$(document).ready(function(){
	if ($('.left_attribute').length === $('.left_attribute:contains("a")').length) {
		$('.left_attribute').children('h3:gt(2)').nextAll().hide();
		$('.left_attribute').children('h3:gt(2)').hide();
		$('.left_attribute').children('.more_attr').show();
	}else{
		$('.more_attr').hide();
	}
});
</script>
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
<link rel="stylesheet" href="<?=base_url()?>assets/css/product_search_category.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/grid_list_style.css" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?=base_url()?>assets/css/style_new.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.bxslider.css" type="text/css" media="screen"/>
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
  	<?php 
	$category = $this->input->get('_cat');
	?>
	<h3>Categories</h3>
		<?php
		
		$getlnk = $this->input->get();
		unset($getlnk['_subcat']);
//		unset($getlnk['_cat']);
		$dsplnk = "";
		$xxx = "";		
		$chk = "";

		if($getlnk){
			$dsplnk = http_build_query($getlnk);
		}else{
			$chk = "1";
		}
		
		if($ctrl_subcat){
			
			foreach ($ctrl_subcat as $row) {
				
				$getsubcat = $this->input->get("_subcat");
				$check = "";
				if($getsubcat == $row['id_cat']){
					$check = ' checked="checked"';
					$link = current_url() . "?" . $dsplnk;
				}else{
					if($chk == "1"){
						$xxx = "&_cat=" . $row['id_cat'];
					}
					$link = current_url() . "?" . $dsplnk . $xxx . "&_subcat=" . $row['id_cat'];				
				}
				echo "<a href='". $link ."' style='display: block;' class='cbx'>";
				echo "<input type='checkbox' ". $check .">" . $row['name'];
				echo "<br></a>";	
			}		
		}
		?>


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
	<p class="more_attr">More</p>
	<p class="less_attr">Less</p>
  </div>
  
  <!-- Products ------------------>
  
  <div class="right_product">
  	<?php
		$attr = array('id'=>'advsrch', 'autocomplete'=>'off', 'method'=>'get');
		echo form_open('',$attr);
	?>

  	<div class="inputRow">	
		Keyword:
		<?php
		$is = $this->input->get('_is');
		?>		
		<input type="text" name="_is" id="_is" value="<?php echo html_escape($is);?>" size="50" maxlength="300" placeholder="Enter keywords or item number" />
		<select name="_cat" id="_cat" title="Select item category">
			<option value="">- All -</option>
			<?php
				$getcat = $this->input->get('_cat');
				$fincat = "";
				if($getcat){
					$fincat = $getcat;					
				}else{
					$fincat = $this->input->get('_subcat');
				}
				foreach ($firstlevel as $row) { # generate all parent category.
			?>
				<option value="<?php echo $row['id_cat']; ?>" <?php if($row['id_cat'] == $fincat){ ?>selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
			<?php } ?>
		</select>
		<input type="submit" value="SEARCH" name="btn_srch" id="btn_srch"/>
						
	</div>

	<div class="inputRow">
		Location:
		<?php $gloc = $this->input->get('_loc'); ?>
		<select title="Select item location" name="_loc" id="_loc" class="advsrchLocation">
			<option value="">- All -</option>
				<?php foreach($shiploc['area'] as $island=>$loc):?>
					<option value="<?php echo $shiploc['islandkey'][$island];?>" <?php if($gloc == $shiploc['islandkey'][$island]){?>selected="selected"<?php } ?>><?php echo $island;?></option>
						<?php foreach($loc as $region=>$subloc):?>
							<option value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" <?php if($gloc == $shiploc['regionkey'][$region]){?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
								<?php foreach($subloc as $id_cityprov=>$cityprov):?>
									<option value="<?php echo $id_cityprov;?>" style="margin-left:30px;" <?php if($gloc == $id_cityprov){?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
								<?php endforeach;?>
						<?php endforeach;?>
				<?php endforeach;?>
        </select>		
		Condition:	
		<?php $con = $this->input->get('_con'); ?>
		<select title="Select item condition" name="_con" id="_con">
			<option value="">- All -</option>          
			<?php foreach($this->lang->line('product_condition') as $x): ?>
				<option value="<?php echo $x;?>" <?php if($con == $x){?>selected="selected"<?php } ?>><?php echo $x; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="inputRow">
		Price:
		<?php
		$price1 = $this->input->get('_price1');
		$price2 = $this->input->get('_price2');
		?>
		<input class="advsrchPrice1" type="text" name="_price1" id="_price1" value="<?php echo html_escape($price1);?>" maxlength="10" size="6" placeholder="Min" title="Minimum price"> to <input class="advsrchPrice2" type="text" name="_price2" id="_price2" value="<?php echo html_escape($price2);?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
		<input type="hidden" name="_price" id="_price"data-url="<?php echo $myurl;?>"/>
	    <p class="search_result"><!-- Showing 1 - 48 of 13,152 Results --></p>
	    Sort by:
		<?php $sop = $this->input->get('_sop'); ?>
	    <select name="_sop" id="_sop" title="Sort item">
			<option value="popular" <?php if($sop == "popular"){?>selected="selected"<?php } ?>>Popular</option>
			<option value="hot" <?php if($sop == "hot"){?>selected="selected"<?php } ?>>Hot</option>		
			<option value="new" <?php if($sop == "new"){?>selected="selected"<?php } ?>>New</option>
			<option value="con" <?php if($sop == "con"){?>selected="selected"<?php } ?>>Item Condition</option>
	    </select>
	</div>
	<?php echo form_close();?>
    <!-- Buttons start -->
    <div id="list" class="list list-active" title="List"></div>
    <div id="grid" class="grid" title="Grid"></div>
    <!-- Buttons end -->
    <div class="clear"></div>
    <div id="product_content">
      <?php

        if(!empty($items)) {
            for ($i = 0; $i < sizeof($items); $i++) {
                $pic = explode('/', $items[$i]['product_image_path']);
      ?>
				<div class="product-list"> 
					<a href="<?= base_url() ?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo es_url_clean(urlencode($items[$i]['product_name'])); ?>.html">
						<img alt="<?php echo html_escape($items[$i]['product_name']); ?>" src="<?php echo base_url() . $pic[0] . '/' . $pic[1] . '/' . $pic[2] . '/' . $pic[3] . '/' . 'categoryview' . '/' . $pic[4]; ?>">
					</a>
					<h3 style="-o-text-overflow: ellipsis; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; width:225px; ">
						<a href="<?= base_url() ?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo es_url_clean(urlencode($items[$i]['product_name'])); ?>.php"><?php echo html_escape($items[$i]['product_name']); ?></a>
					</h3>
					<div class="price-cnt">
					  <div class="price"> <span>&#8369;</span> <?php echo number_format($items[$i]['product_price'], 2); ?> </div>
					</div>
					<div class="product_info_bottom">
					  <div>Condition: <strong><?php echo $items[$i]['product_condition']; ?></strong></div>
					  <!-- <div>Sold: <strong>32</strong></div> --> 
					</div>
					<p><?php echo html_escape($items[$i]['product_brief']); ?></p>
				</div>
      <?php
			} // end of loop
		}else{
			echo "<br><br><h3 align='center'>No resuls found!</h3>";
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
							url: base_url + 'advsrch/scroll_product',
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
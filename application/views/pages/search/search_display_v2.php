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
<link rel="stylesheet" href="<?=base_url()?>assets/css/style_new.css?ver=1.0" type="text/css" media="screen"/>
<style type="text/css">
.err{
	-webkit-box-shadow: 0px 0px 2px 2px #FF0000;
	-moz-box-shadow: 0px 0px 2px 2px #FF0000;
	box-shadow: 0px 0px 2px 2px #FF0000;	
}
</style>
<?php
	$attr = array('id'=>'advsrch', 'autocomplete'=>'off', 'method'=>'get');
	echo form_open('',$attr);
?>
<div class="wrapper" id="main_search_container">
  <!-- left pane start ------------------>  
  <div class="left_attribute">
	<h3>Categories</h3>
		<?php
		
		if($ctrl_subcat){		
			foreach ($ctrl_subcat as $row => $value) {
				$check = "";
				if ($getsubcat == $value['id_cat']) {
					$check = ' checked="checked"';
				}			
				
				echo "<input type='checkbox' class='adv_catpanel' ". $check ." name='_subcat' value='". $value['id_cat'] ."'>" . $value['name'];
				echo "<br>";
			}		
		}
		?>

	<?php

	if (isset($arrayofparams)) {

		list($file, $parameters) = explode('?', $myurl);
		parse_str($parameters, $output);

    	foreach ($arrayofparams as $row => $value) {
			
			if(!empty($value[0])){
				$genid = "fld_". $row;
				$i = 0;
				$geninputs = "";
				$attr_group = $value['name'];
				$get_group = $this->input->get($attr_group);
				foreach ($value[0] as $row => $attr_values) {
					$check = "";
					if (isset($get_group)) {
						if (in_array_r($attr_values, $output)) {
							$check = ' checked="checked"';
							$i = $i + 1;
						}
					}			
					$geninputs = $geninputs . "<input type='checkbox' class='adv_leftpanel' ". $check ." name='". $attr_group ."[". $row ."]' value='". $attr_values ."'>" . $attr_values . "<br>";
				} // for each attr_values
				
				$style = " style='display:none' ";
				$class = " class='span_bg advsrch' ";
				if($i > 0){
					$style = " ";
					$class = " class='span_bg advsrch_toggle' ";
				}
				
				echo "<h3 class='title' id='". $genid  ."' style='cursor:pointer;'><span id='i". $genid ."' ". $class ."></span> ". $value['name'] ."</h3>";			
				echo "<div id='c". $genid ."' ". $style .">" . $geninputs . "</div>";
			} // check value[0]
		} // for each arrayofparams
	} // isset($arrayofparams)
	
    ?> 
  </div>
  <!-- left pane end ------------------>

  <div class="right_product">
  	<div class="advsearch">
		<div class="inputRow">
			<span class="adv_is">	
				Keyword:	
				<input style="" type="text" name="_is" id="_is" value="<?php echo html_escape($getis);?>" size="50" maxlength="300" placeholder="Enter keywords or item number" />
			</span>
			<span class="adv_is">
				<select name="_cat" id="_cat" title="Select item category">
					<option value="1">- All -</option>
					<?php
						foreach ($firstlevel as $row) : # generate all parent category.
					?>
						<option value="<?php echo $row['id_cat']; ?>" <?php if($row['id_cat'] == $getcat){ ?>selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</span>
			<input type="submit" value="SEARCH" id="btn_srch"/>			
		</div>
		<div class="inputRow">
			<span class="adv_us">
				Seller:	
				<input type="text" name="_us" id="_us" value="<?php echo html_escape($getus);?>" size="40" maxlength="30" placeholder="Find items offered by a particular seller" />
			</span>
			<span class="adv_us">
			Location:
				<select title="Select item location" name="_loc" id="_loc" class="advsrchLocation">
					<option value="">- All -</option>
						<?php foreach($shiploc['area'] as $island=>$loc):?>
							<option value="<?php echo $shiploc['islandkey'][$island];?>" <?php if($getloc == $shiploc['islandkey'][$island]){?>selected="selected"<?php } ?>><?php echo $island;?></option>
								<?php foreach($loc as $region=>$subloc):?>
									<option value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" <?php if($getloc == $shiploc['regionkey'][$region]){?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
										<?php foreach($subloc as $id_cityprov=>$cityprov):?>
											<option value="<?php echo $id_cityprov;?>" style="margin-left:30px;" <?php if($getloc == $id_cityprov){?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
										<?php endforeach;?>
								<?php endforeach;?>
						<?php endforeach;?>
				</select>
			</span>
		</div>
		<div class="inputRow">
			<span class="adv_us">
				Condition:	
				<select title="Select item condition" name="_con" id="_con">
					<option value="">- All -</option>          
					<?php foreach($this->lang->line('product_condition') as $con): ?>
						<option value="<?php echo $con;?>" <?php if($getcon == $con){?>selected="selected"<?php } ?>><?php echo $con; ?></option>
					<?php endforeach; ?>
				</select>
			</span>
			<span class="adv_us">
				Price:
				<input type="text" name="_price1" id="_price1" value="<?php echo html_escape($getprice1);?>" maxlength="10" size="6" placeholder="Min" title="Minimum price"> to <input type="text" name="_price2" id="_price2" value="<?php echo html_escape($getprice2);?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
			</span>
			<span class="adv_us">
				Sort by:
				<select name="_sop" id="_sop" title="Sort item">
					<option value="popular" <?php if($getsop == "popular"){?>selected="selected"<?php } ?>>Popular</option>
					<option value="hot" <?php if($getsop == "hot"){?>selected="selected"<?php } ?>>Hot</option>		
					<option value="new" <?php if($getsop == "new"){?>selected="selected"<?php } ?>>New</option>
					<option value="con" <?php if($getsop == "con"){?>selected="selected"<?php } ?>>Item Condition</option>
				</select>
			</span>
		</div>
		<p class="search_result"><!-- Showing 1 - 48 of 13,152 Results --></p>
	</div>
    <!-- Buttons start -->
    <div id="list" class="list list-active" title="List"></div>
    <div id="grid" class="grid" title="Grid"></div>
    <!-- Buttons end -->
    <div class="clear"></div>
	<!-- Products start ------------------>
    <div id="product_content">
      <?php
        if(!empty($items)) {
            for ($i = 0; $i < sizeof($items); $i++) {
                $pic = explode('/', $items[$i]['product_image_path']);
      ?>
				<div class="product-list"> 
					<a href="<?php echo base_url() . "item/" . $items[$i]['slug']; ?>">
						<span class="prod_img_wrapper">
							<span class="prod_img_container">
								<img alt="<?php echo html_escape($items[$i]['product_name']); ?>" src="<?php echo base_url() . $pic[0] . "/" . $pic[1] . "/" . $pic[2] . "/" . $pic[3] . "/" . "categoryview" . "/" . $pic[4]; ?>">
							</span>
						</span>	
					</a>
					<h3>
						<a href="<?php echo base_url() . "item/" . $items[$i]['slug']; ?>">
							<?php echo html_escape($items[$i]['product_name']); ?>
						</a>
					</h3>
					<div class="price-cnt">
					  	<div class="price"> 
					  		<span>&#8369;</span> <?php echo number_format($items[$i]['product_price'], 2);?>
						</div>
					</div>
					<div class="product_info_bottom">
					  	<div>Condition: <strong><?php echo $items[$i]['product_condition']; ?></strong></div>
					</div>
					<p><?php echo html_escape($items[$i]['product_brief']); ?></p>
				</div>
      <?php
			} // end of loop
		}else{
			echo "<br><br><h3 align='center'>No results found.</h3>";
		} // end of isset
      ?>
    </div>
  </div>
</div>
<input class='condition' type='hidden' value='<?php echo json_encode($condition); ?>'/>
<?php echo form_close();?>
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.easing.min.js" type="text/javascript"></script> 
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.scrollUp.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/JavaScript/advsearch.js?ver=1.0" type="text/javascript"></script>
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
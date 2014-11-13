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
<link rel="stylesheet" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/product_advance_search.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/simple-sidebar.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
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
<div class="wrapper display-when-desktop" id="main_search_container">
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
				
				echo "<input type='checkbox' class='adv_catpanel' ". $check ." name='_subcat' value='". $value['id_cat'] ."'>" . html_escape($value['name']);
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
					$geninputs = $geninputs . "<input type='checkbox' class='adv_leftpanel' ". $check ." name='". $attr_group ."[". $row ."]' value='". html_escape($attr_values) ."'>" . html_escape($attr_values) . "<br>";
				} // for each attr_values
				
				$style = " style='display:none' ";
				$class = " class='span_bg advsrch' ";
				if($i > 0){
					$style = " ";
					$class = " class='span_bg advsrch_toggle' ";
				}
				
				echo "<div class=\"clear\"></div><h3 class='title h3-cat-title' id='". $genid  ."' style='cursor:pointer;'><span id='i". $genid ."' ". $class ."></span> ". html_escape($value['name']) ."</h3>";			
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
				<label>Keyword:	</label>
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
				<label>Seller:</label>	
				<input type="text" name="_us" id="_us" value="<?php echo html_escape($getus);?>" size="40" maxlength="30" placeholder="Search for a seller's item" />
			</span>
			<span class="adv_us">
			<label>Location:</label>
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
				<label>Condition:</label>	
				<select title="Select item condition" name="_con" id="_con" class="prod_condition">
					<option value="">- All -</option>          
					<?php foreach($this->lang->line('product_condition') as $con): ?>
						<option value="<?php echo $con;?>" <?php if($getcon == $con){?>selected="selected"<?php } ?>><?php echo $con; ?></option>
					<?php endforeach; ?>
				</select>
			</span>
			<span class="adv_us">
				<label>Price:</label>
				<input type="text" name="_price1" id="_price1" value="<?php echo html_escape($getprice1);?>" maxlength="10" size="6" placeholder="Min" title="Minimum price"> to <input type="text" name="_price2" id="_price2" value="<?php echo html_escape($getprice2);?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
			</span>
			<span class="adv_us">
				<label>Sort by:</label>
				<select name="_sop" id="_sop" title="Sort item">
					<option value="popular" <?php if($getsop == "popular"){?>selected="selected"<?php } ?>>Popular</option>
					<option value="hot" <?php if($getsop == "hot"){?>selected="selected"<?php } ?>>Hot</option>		
					<option value="new" <?php if($getsop == "new"){?>selected="selected"<?php } ?>>New</option>
					<option value="con" <?php if($getsop == "con"){?>selected="selected"<?php } ?>>Item Condition</option>
				</select>
			</span>
		</div>
	</div>

    <?php if(isset($cntr)): ?>
        <div class="adv_ctr"><strong style="font-size:14px"><?php echo ($cntr>0)?number_format($cntr):'No';?></strong> result<?php echo  ($cntr>1 || $cntr === 0)?'s':'';?> found</div>
    <?php endif ?>
    
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
      ?>
				<div class="product-list"> 
					<a href="<?php echo "/item/" . $items[$i]['slug']; ?>">
						<span class="prod_img_wrapper">
							<?php if((intval($items[$i]['is_promote']) == 1) && isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>					  
							  <span class="cd_slide_discount">
								  <span><?php echo number_format($items[$i]['percentage'],0,'.',',');?>%<br>OFF</span>
							  </span>
							<?php endif; ?>
                            
							<span class="prod_img_container">
								<img alt="<?php echo html_escape($items[$i]['product_name']); ?>" src="<?php echo getAssetsDomain() ?><?php echo $items[$i]['path']. "categoryview/" .$items[$i]['file']; ?>">
							</span>
						</span>	
					</a>
					<h3>
						<a href="<?php echo "/item/" . $items[$i]['slug']; ?>">
							<?php echo html_escape($items[$i]['product_name']); ?>
						</a>
					</h3>
					<div class="price-cnt">
					  
					  	<div class="price"> 
					  		<span>&#8369;</span> <?php echo number_format($items[$i]['price'], 2);?>
						</div>
		
						<?php if(isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
						
						    <div>
						      <span class="original_price">
							      &#8369; <?PHP echo number_format($items[$i]['original_price'],2,'.',','); ?>
						      </span>	
						      <span style="height: 20px;">
							|&nbsp; <strong> <?PHP echo number_format($items[$i]['percentage'],0,'.',',');?>%OFF</strong>
						      </span>
						    </div>
						<?php endif; ?>
                        
                        
					</div>
					<div class="product_info_bottom">

						<div>Condition: <strong><?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($items[$i]['product_condition']),15) : html_escape($items[$i]['product_condition']);?></strong></div>
						<?PHP if($items[$i]['is_free_shipping']): ?>
						  <span style="float:right;"><span class="span_bg img_free_shipping"></span>
						<?PHP endif; ?>	

					</div>
					<p><?php echo html_escape($items[$i]['product_brief']); ?></p>
				</div>
      <?php
			} // end of loop
		}else{
			echo "<br><br><h3 align='center'>".($default?'Begin searching by applying search filters.':'No results found.')."</h3>";
		} // end of isset
      ?>
    </div>
	<!-- Products end ------------------>
	<div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
  </div>
</div>

<div class="display-when-mobile-1024">
	<div class="container container-responsive container-search">
		<div class="row">
			<div class="col-md-12">
		
				<div class="panel-group panel-search" id="accordion">
					<div class="panel panel-default no-border ">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" class="a-accordion-header" href="#collapseOne">
									Advanced Search <i class="glyphicon <?php if(!empty($items)) { echo "glyphicon-chevron-down"; }else{ echo "glyphicon-chevron-up"; }?> pull-right"></i>
								</a>
							</h4>
							<script>
									$("#accordion").on('click','.a-accordion-header',function() {
										
										var attr = $("i.glyphicon").attr("class");

										if(attr == "glyphicon glyphicon-chevron-down pull-right")
										{
											$('.glyphicon').removeClass("glyphicon glyphicon-chevron-down pull-right").addClass("glyphicon glyphicon-chevron-up pull-right");
										}else if(attr == "glyphicon glyphicon-chevron-up pull-right"){
											$('.glyphicon').removeClass("glyphicon glyphicon-chevron-up pull-right").addClass("glyphicon glyphicon-chevron-down pull-right");
										
										}
									});
						  </script>
						</div>
						<form method="post" action="/">
						<div id="collapseOne" class="panel-collapse collapse <?php if(!empty($items)) { echo ""; }else{ echo "in"; }?>">
							<div class="panel-body">
								<table width="100%">
									<tr>
										<td class="td-search-label">Keyword: </td>
										<td class="td-search-input"><input style="" type="text" name="_is" id="_is" class="form-control input-sm no-border" value="<?php echo html_escape($getis);?>" size="50" maxlength="300" placeholder="Enter keywords or item number" /></td>
									</tr>
									<tr>
										<td class="td-search-label">Category: </td>
										<td class="td-search-input">
											<select name="_cat" id="_cat" class="form-control input-sm no-border" title="Select item category">
												<option value="1">- All -</option>
												<?php
													foreach ($firstlevel as $row) : # generate all parent category.
												?>
													<option value="<?php echo $row['id_cat']; ?>" <?php if($row['id_cat'] == $getcat){ ?>selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="td-search-label">Seller: </td>
										<td class="td-search-input"><input type="text" name="_us" id="_us" class="form-control no-border input-sm" value="<?php echo html_escape($getus);?>" size="40" maxlength="30" placeholder="Search for a seller's item" /></td>
									</tr>
									<tr>
										<td class="td-search-label">Location: </td>
										<td class="td-search-input">
											<select title="Select item location" name="_loc" id="_loc" class="advsrchLocation form-control input-sm no-border">
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
										</td>
									</tr>
									<tr>
										<td class="td-search-label">Condition: </td>
										<td class="td-search-input">
											<select title="Select item condition" name="_con" id="_con" class="prod_condition form-control input-sm no-border">
												<option value="">- All -</option>          
												<?php foreach($this->lang->line('product_condition') as $con): ?>
													<option value="<?php echo $con;?>" <?php if($getcon == $con){?>selected="selected"<?php } ?>><?php echo $con; ?></option>
												<?php endforeach; ?>
											</select>
										</td>
									</tr>
									<tr>
										<td class="td-search-label">Price: </td>
										<td class="td-search-input">
											<input type="text" name="_price1" id="_price1" value="<?php echo html_escape($getprice1);?>" maxlength="10" size="6" placeholder="Min" title="Minimum price"> to <input type="text" name="_price2" id="_price2" value="<?php echo html_escape($getprice2);?>" maxlength="10" size="6" placeholder="Max" title="Maximum price">
										</td>
									</tr>
									<tr>
										<td class="td-search-label">Sort by: </td>
										<td class="td-search-input">
											<select name="_sop" id="_sop" class="form-control input-sm no-border" title="Sort item">
												<option value="popular" <?php if($getsop == "popular"){?>selected="selected"<?php } ?>>Popular</option>
												<option value="hot" <?php if($getsop == "hot"){?>selected="selected"<?php } ?>>Hot</option>		
												<option value="new" <?php if($getsop == "new"){?>selected="selected"<?php } ?>>New</option>
												<option value="con" <?php if($getsop == "con"){?>selected="selected"<?php } ?>>Item Condition</option>
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="2" class="td-search-button">
											<input type="submit" value="SEARCH" id="btn_srch" class="btn btn-lg btn-block" name="search_btn"/>		
											
											<center><a data-toggle="modal" data-target="#refine" class="a-refine">Refine Search</a></center>
											
										</td>
									</tr>
								</table>
							</div>
						</div>
						</form>
					</div>
				</div>
				
				 <?php if(isset($cntr)): ?>
					<p class="p-results"><strong style="font-size:14px;"><?php echo ($cntr>0)?number_format($cntr):'No';?></strong> result<?php echo  ($cntr>1 || $cntr === 0)?'s':'';?> found</p>
					
				<?php endif ?>
				<!-------------------->
				<?php
        if(!empty($items)) {
            for ($i = 0; $i < sizeof($items); $i++) {
      ?>
				<div class="panel panel-default no-border panel-items">
					<table width="100%" class="">
						<tr>
							<td width="90px" class="v-align-top">
								<span class="prod_img_container">
									<img alt="<?php echo html_escape($items[$i]['product_name']); ?>" src="<?php echo getAssetsDomain() ?><?php echo $items[$i]['path']. "small/" .$items[$i]['file']; ?>">
								</span>
							</td>
							<td class="v-align-top">
								<p class="p-item-name">
									<a class="a-item-name" href="/item/<?php echo $items[$i]['slug']; ?>">
										<?php 
											
											$item_name_m = html_escape($items[$i]['product_name']); 
											if(strlen($item_name_m)>35){
											
											echo substr_replace( $item_name_m, "...", 35);
											
											}else{
												echo $item_name_m;
											}
										?>
									</a>
								</p>
								<p class="p-item-price">
									PHP <?php echo number_format($items[$i]['price'], 2,'.',','); ?>
								</p>
								<?php if(isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
									<p class="p-item-discount">
									  <span class="span-item-original-price">
										  PHP <?PHP echo number_format($items[$i]['original_price'],2,'.',','); ?>
									  </span>	
									  <span>
									|&nbsp; <strong> <?PHP echo number_format($items[$i]['percentage'],0,'.',',');?>%OFF</strong>
									  </span>
									</p>
								<?php endif; ?>
									
								<p class="p-item-condition">Condition: <strong><?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($items[$i]['product_condition']),15) : html_escape($items[$i]['product_condition']);?></strong></p>
							</td>
							<td width="30px" class=" v-align-top">
								<?PHP if($items[$i]['is_free_shipping']): ?>
								  <span style="float:right;"><span class="span_bg img_free_shipping"></span>
								<?PHP endif; ?>	
							</td>
						</tr>
					</table>
				</div>
			  <?php
			} // end of loop
		}else{
			echo "<hr/><h3 align='center' class=\"h3_begin\">".($default?'Begin searching by applying search filters.':'No results found.')."</h3>";
		} // end of isset
      ?>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="refine" tabindex="-1" role="dialog" aria-labelledby="SubCategories" aria-hidden="true">
	<div class="modal-dialog no-border border-0">
		<div class="modal-content no-border">
			<div class="modal-header bg-orange">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="color-white">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title color-white" id="SubCategories">Refine Search</h4>
			</div>
			<div class="modal-body no-border">
				<p class="h3-cat-title">Categories</p>
				<?php
				
				if($ctrl_subcat){		
					foreach ($ctrl_subcat as $row => $value) {
						$check = "";
						if ($getsubcat == $value['id_cat']) {
							$check = ' checked="checked"';
						}			
						
						echo "<input type='checkbox' class='adv_catpanel adv_catpanel_m' ". $check ." name='_subcat' value='". $value['id_cat'] ."'><text class=\"cbx-label\">" . html_escape($value['name'])."</text>";
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
								$geninputs = $geninputs . "<input type='checkbox' class='adv_leftpanel adv_catpanel_m' ". $check ." name='". $attr_group ."[". $row ."]' value='". html_escape($attr_values) ."'>" . html_escape($attr_values) . "<br>";
							} // for each attr_values
							
							$style = " style='display:none' ";
							$class = " class='span_bg advsrch' ";
							if($i > 0){
								$style = " ";
								$class = " class='span_bg advsrch_toggle' ";
							}
							
							echo "<div class=\"clear\"></div><h3 class='title h3-cat-title' id='". $genid  ."' style='cursor:pointer;'><span id='i". $genid ."' ". $class ."></span> ". html_escape($value['name']) ."</h3>";			
							echo "<div id='c". $genid ."' ". $style .">" . $geninputs . "</div>";
						} // check value[0]
					} // for each arrayofparams
				} // isset($arrayofparams)
				
				?>
			</div>
			
		</div>
	</div>
</div>
<input class='condition' type='hidden' value='<?php echo json_encode($condition); ?>'/>
<?php echo form_close();?>
<script src="/assets/js/src/bootstrap.js" type="text/javascript"></script> 
<script src="/assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script> 
<script src="/assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
<script src="/assets/js/src/advsearch.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>
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
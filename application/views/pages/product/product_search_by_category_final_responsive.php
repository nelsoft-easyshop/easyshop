<meta name="viewport" content="width=device-width, maximum-scale=1.0"">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category_responsive.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>

<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 

<link rel="canonical" href="<?php echo base_url()?>category/<?php echo $category_slug?>"/>

<?php


session_start();
$_SESSION['start'] = "0";
?>
<section style="color-gray display-when-desktop">
	<div class="container container-responsive">	
		<div class="row">
			<div class="col-md-12">
				<section class="top_margin product-page-section display-when-desktop">
					<div class="wrapper">
						<div class="prod_categories">
							<div class="nav_title">Categories <img src="/assets/images/img_arrow_down.png" class="drop-arrow"></div>
							<?php echo $category_navigation; ?>
							<script>
								$(".drop-arrow").hover(function(){
								  $("nav").css("display","inline");
								  },function(){
								  $("nav").css("display","none");
								});
							</script>
						</div> 
						<div class="prod_cat_nav">
							<div class="category_nav product_content">
								<ul>
								<?php foreach($main_categories as $category): ?>
									<li class = <?php echo ((isset($breadcrumbs[0]['id_cat']) &&  $category['id_cat'] === $breadcrumbs[0]['id_cat'])?"active":"");?>> <a href="<?=base_url()?>category/<?php echo $category['slug']?>"> <?php echo html_escape($category['name']);?> </a> </li>
								<?php endforeach;?>
								</ul>
								<span class="span_bg prod_cat_drop"></span>
							</div>
						</div>
						<div class="clear"></div>
						<div class="bread_crumbs">
							<ul>
								<li class=""><a href="<?= base_url() ?>home">Home</a></li>
								<?php foreach ($breadcrumbs as $crumbs): ?>
								<li>
									<a href="<?= base_url() ?>category/<?php echo $crumbs['slug'] ?>">
										<?php echo html_escape($crumbs['name']); ?>
									</a>
								</li> 
								<?php endforeach; ?>
							</ul> 
						</div>
					</div>
					<br/>
				</section>
			</div>
		</div>
		<div class="row display-when-desktop">
			<div class="col-md-2 row-main">
				<div class="left_attribute">
					<h3>Price</h3>
					<?php
					if(!isset($_GET['price']))
						$pricelink = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']) . '&price=';
					else
						$pricelink = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']) ;
					?>
					<input type="text" id="price1" maxlength=10 size=6>to<input type="text" id="price2" maxlength=10 size=6> <input class="price" data-url="<?php echo $pricelink ?>" type="button" value=">>"/>
					<?php
					if (isset($attributes)) {
						foreach ($attributes as $keyparam => $value) {
								
								$decodeparam = urldecode($keyparam);
								$parameter = strtolower(str_replace(' ', '_', $decodeparam));

								echo '<h3 class="title">'.html_escape($decodeparam).' <br></h3>';
								
							foreach ($value as $key2 => $attr_value) {
								$attr_value = ucfirst(strtolower($attr_value));
								# start if   
								if(count($_GET) <= 0){$finalurl = $_SERVER['REQUEST_URI'].'?';}
								else{$finalurl = $_SERVER['REQUEST_URI'].'&';}
								# end if


								$url = $finalurl;
								list($file, $parameters) = explode('?', $url);
								parse_str($parameters, $output);
								$checked = "";
								if(isset($_GET[$parameter])){
								   $oldvalue = $output[$parameter];
								   unset($output[$parameter]);
									if(strpos($_GET[$parameter], '-|-') !== false) {

										$var = explode('-|-',$_GET[$parameter]);
										$newvalue = "";
			 
											if (in_array($attr_value, $var)) {
												$checked = "checked";
												$key = array_search($attr_value, $var);
												unset($var[$key]); 
												$newvalue = implode("-|-", $var); 
												$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$newvalue;
											}else{
												$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$oldvalue.'-|-'.$attr_value;
											}

									} else {

										if($_GET[$parameter] == $attr_value){
											$checked = "checked";
											$link = $file . '?' . http_build_query($output); 
										}else{
											$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$oldvalue.'-|-'.$attr_value;
										}
									}
								 
								}else{
									$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$attr_value; 
								}

								echo '<a href="'.$link.'"><input type="checkbox" '.$checked.' class="cbx" data-value="'.$link.'" > 
										<label for="cbx">'.ucfirst(strtolower($attr_value)).'</label><br>
									</a>';
							}
						}
					}
					?> 
					<p class="more_attr">More Filters</p>
					<p class="less_attr">Less Filters</p>
				</div>

				
			</div>
			<div class="col-md-10 row-main" style="border: transparent #fff 1px; padding: 0px !important;">
				<?php if(count($subcategories) !== 0):?>
					<div class="filters">           
					  <h2 class="margin-0">Categories:</h2>    
					  <div class="jcarousel category_carousel cc2_wrapper">
						<div class="cc2">
							<?PHP foreach ($subcategories as $rows): ?>
							<div class="">
								<a class="cc2_title color-gray" href="<?=base_url()?>category/<?php echo $rows['slug'];?>">
									<span><?php echo html_escape($rows['name']);?></span></a>
									<?PHP if(count($rows['popular'])>0): ?>
									<span class="cat_carousel_img_con"><span class="cat_carousel_img"><img src="<?= base_url().$rows['popular'][0]['path'].'categoryview/'.$rows['popular'][0]['file']; ?>"></span></span><br />
									<div class="cc2_prod_name"><a href="<?PHP echo base_url()."item/".$rows['popular'][0]['slug']; ?>" title="<?PHP echo $rows['popular'][0]['product']; ?>"><span class="color-gray font-12"><?PHP echo html_escape($rows['popular'][0]['product']); ?></span></a></div>
									<span class="recommended_product_price">PHP <?php echo number_format($rows['popular'][0]['price'],2,'.',',');?></span>
									<?PHP endif; ?>
								</div>
								<?PHP endforeach;?>
							</div>  
						</div>
						<a href="#" class="jcarousel-control-prev inactive category_carousel2_prev text-decoration-none">&lsaquo;</a>
						<a href="#" class="jcarousel-control-next inactive category_carousel2_next text-decoration-none">&rsaquo;</a>            
					</div>
				<?php endif; ?>
				<div class="clear"></div>
				<p class="search_result margin-left-42"> </p>
					
				 Sort by: 
				<select data-url="<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']).'&sop='; ?>" id="sort_order" class="select-sort-by"> 
					<?php
					$sortarray = array('bestmatch' => 'best match', 'hot' => 'hot','new' => 'new','popular' => 'popular');
					$sop = isset($_GET['sop']) ? $this->input->get('sop') : 'best match';
					foreach ($sortarray as $key => $value) {
						$selected = ($sop == $value) ? 'selected' : '';
						echo '<option value="'.$key.'" '.$selected.'>'.ucfirst($value).'</option>';
					} ?>
				</select>
				<div class="pull-right div-view-buttons">
				<?php
				$typeOfViewActive = '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
				if(isset($_COOKIE['view']))
				{
					$cookieView = $_COOKIE['view'];
					$typeOfViewActive = ($cookieView == "list") ? '<div id="list" class="list list-active"></div><div id="grid" class="grid"></div>' : '<div id="list" class="list "></div><div id="grid" class="grid grid-active"></div>';
				}
				echo $typeOfViewActive;
				?> 
				</div>
				<div class="clear"></div>
				<div id="product_content" class="margin-left-42">     
					<?php
					if (isset($items)) {
						for ($i = 0; $i < sizeof($items); $i++) {

							$typeOfView = "product";
							if(isset($_COOKIE['view']))
							{
								$cookieView = $_COOKIE['view'];
								$typeOfView = ($cookieView == "list") ? "product-list" : "product";
							}
						 ?>
						<div class="<?php echo $typeOfView; ?>">
							<a href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>">
								<span class="prod_img_wrapper">
						<?php if((intval($items[$i]['is_promote']) === 1) && isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
									<span class="cd_slide_discount">
							<span><?php echo number_format($items[$i]['percentage'],0,'.',',');?>%<br>OFF</span>
						</span>
						<?php endif; ?>


									<span class="prod_img_container">
										<img alt="<?php echo html_escape($items[$i]['name']); ?>" src="<?php echo base_url() .$items[$i]['path'].'categoryview/'.$items[$i]['file']; ?>">
									</span>
								</span>
							</a>
							<h3>
								<a href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>">
									<?php echo html_escape($items[$i]['name']); ?>
								</a>
							</h3>
							<div class="price-cnt">
								<div class="price">
									PHP <?php echo number_format($items[$i]['price'], 2,'.',','); ?>
								</div>
								
								
								 <?php if(isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>
						
						<div class="div_discount">
						  <span class="original_price">
							  PHP <?PHP echo number_format($items[$i]['original_price'],2,'.',','); ?>
						  </span>	
						  <span style="height: 20px;">
						|&nbsp; <strong> <?PHP echo number_format($items[$i]['percentage'],0,'.',',');?>%OFF</strong>
						  </span>
						</div>
					<?php endif; ?>
								
							</div>
							<div class="product_info_bottom">
								<div>Condition: <strong><?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($items[$i]['condition']),15) : html_escape($items[$i]['condition']);?></strong></div>
					<?PHP if($items[$i]['is_free_shipping']): ?>
					  <span style="float:right;"><span class="span_bg img_free_shipping"></span>
					<?PHP endif; ?>	
							<br/>
							</div>
							<p>
								<?php echo html_escape($items[$i]['brief']); ?>
							</p>
						</div>
						<?php
					}
				}
				?>

				</div> 
				 <div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
			</div>
		</div>
	</div>
</section>
<div class="display-when-mobile-1024">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="panel-group " id="categories">
				  <div class="panel panel-default panel-category no-border border-0">
					<div class="panel-heading panel-category-heading no-border">
					  <h4 class="panel-title panel-title-category">
						Categories
						<a data-toggle="collapse" data-parent="#categories" href="#categories-body">
							<img class="pull-right" src="<?=base_url()?>assets/images/img_arrow_down.png">
						</a>
					  </h4>
					</div>
					<div id="categories-body" class="panel-collapse collapse">
					  <div class="panel-body-category">
							<ul class="list-unstyled">
								<li class="list-category">Category 1</li>
								<li class="list-category">Category 2</li>
							</ul>
					  </div>
					</div>
				  </div>
				</div>
				<div class="bread_crumbs_m">
					<ul style="margin-bottom: 10px;">
						<li class="li_home" ><a href="<?=base_url()?>home" style="color: #f18200;">Home</a></li>
						<?php foreach($breadcrumbs as $crumbs): ?>
						<li> <a href="<?=base_url()?>category/<?php echo $crumbs['slug']?>" style="color: #f18200;"> <?php echo html_escape($crumbs['name']);?> </a> </li>
						<?php endforeach;?>
						
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
					<div class="search_result_m">
					<p class="search_result "> </p>
					
					 <span class="span-sort">Sort by:</span> 
					<select data-url="<?php echo site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']).'&sop='; ?>" id="sort_order" class="select-sort-by"> 
						<?php
						$sortarray = array('bestmatch' => 'best match', 'hot' => 'hot','new' => 'new','popular' => 'popular');
						$sop = isset($_GET['sop']) ? $this->input->get('sop') : 'best match';
						foreach ($sortarray as $key => $value) {
							$selected = ($sop == $value) ? 'selected' : '';
							echo '<option value="'.$key.'" '.$selected.'>'.ucfirst($value).'</option>';
						} ?>
					</select>
					</div>
					<?php
					if (isset($items)) {
						for ($i = 0; $i < sizeof($items); $i++) {

							$typeOfView = "product";
							if(isset($_COOKIE['view']))
							{
								$cookieView = $_COOKIE['view'];
								$typeOfView = ($cookieView == "list") ? "product-list" : "product";
							}
						 ?>
						 <h3>
                       
						</h3>
						<div class="panel panel-default no-border panel-items">
							<table width="100%" class="">
								<tr>
									<td width="90px" class="v-align-top">
										<span class="prod_img_container">
											<img alt="<?php echo html_escape($items[$i]['name']); ?>" src="<?php echo base_url() .$items[$i]['path'].'small/'.$items[$i]['file']; ?>">
										</span>
									</td>
									<td class="v-align-top">
										<p class="p-item-name">
											<a class="a-item-name" href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>">
												<?php 
													
													$item_name_m = html_escape($items[$i]['name']); 
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
											
											<p class="p-item-condition">Condition: <strong><?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($items[$i]['condition']),15) : html_escape($items[$i]['condition']);?></strong></p>
											
										
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
						}
					}
					?>	 
					<div class="loading_products" style="display: inline-block;text-align: center;width: 100%;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="div-button-fixed navbar-fixed-bottom display-when-mobile-1024">
		<?php if(count($subcategories) !== 0):?>
		<table width="100%" style="margin-top: 20px;">
			<tr>
				<td width="50%" class="td-sub-cat button-bottom">
					<a href="#" data-toggle="modal" data-target="#subcategories" class="btn-sub-cat"><p width="100%" class="p-link-category"><i class="glyphicon glyphicon-th-list"></i> Sub Categories</p></a>
				</td>
				
				<td width="50%" class="td-filter button-bottom">
					<a href="#" data-toggle="modal" data-target="#filter" class="btn-sub-cat"><p width="100%" class="p-link-category"><i class="glyphicon glyphicon-filter"></i> Filter</p></a>
				</td>
			</tr>
		</table>
		<?php else:?>
		<table width="100%" style="margin-top: 20px;">
			<tr>
				<td width="100%" class="td-filter_solo button-bottom">
					<a href="#" data-toggle="modal" data-target="#filter" class="btn-sub-cat"><p width="100%" class="p-link-category"><i class="glyphicon glyphicon-filter"></i> Filter</p></a>
				</td>
			</tr>
		</table>	
		<?PHP endif;?>
	</div>
	
	
</div>		

<?php if(count($subcategories) !== 0):?>
<div class="modal fade" id="subcategories" tabindex="-1" role="dialog" aria-labelledby="SubCategories" aria-hidden="true">
	<div class="modal-dialog no-border border-0">
		<div class="modal-content no-border">
			<div class="modal-header bg-orange">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="color-white">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title color-white" id="SubCategories"><i class="glyphicon glyphicon-th-list"></i> Sub Categories</h4>
			</div>
			<div class="modal-body no-border no-padding">
				<ul class="list-unstyled ul-sub">
					 <?PHP foreach ($subcategories as $rows): ?>
						 <a href="<?=base_url()?>category/<?php echo $rows['slug'];?>">
						<li><?php echo html_escape($rows['name']);?></li>
						</a>
					 <?PHP endforeach;?>
				 </ul>
			</div>
			
		</div>
	</div>
</div>
 <?PHP endif;?>

<div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="Filter" aria-hidden="true">
	<div class="modal-dialog no-border border-0">
		<div class="modal-content no-border">
			<div class="modal-header bg-orange">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="color-white">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title color-white" id="Filter"><i class="glyphicon glyphicon-filter"></i> Filter</h4>
			</div>
			<div class="modal-body no-border">
					<h3 class="h3-filter">Price</h3>
					<?php
					if(!isset($_GET['price']))
						$pricelink = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']) . '&price=';
					else
						$pricelink = site_url(uri_string() . '?' . $_SERVER['QUERY_STRING']) ;
					?>
					<table class="tbl-price" width="100%">
						<tr>
							<td class="td-price-filter" width="40%">
								<input type="text" id="price1" style="width: 100%;" maxlength=10 size=6>
							</td>
							<td class="td-price-filter" width="5%" align="center">
								to
							</td>
							<td class="td-price-filter"  width="40%">
								<input type="text" id="price2" style="width: 100%;" maxlength=10 size=6>
							</td>
							<td class="td-price-filter">
								<input class="price price_m" data-url="<?php echo $pricelink ?>" type="button" value=">>"/>
							</td>
						</tr>
					</table>
					<?php
					if (isset($attributes)) {
						foreach ($attributes as $keyparam => $value) {
								
								$decodeparam = urldecode($keyparam);
								$parameter = strtolower(str_replace(' ', '_', $decodeparam));

								echo '<br/><h3 class="title h3-filter">'.html_escape($decodeparam).' <br></h3>';
								
							foreach ($value as $key2 => $attr_value) {
								$attr_value = ucfirst(strtolower($attr_value));
								# start if   
								if(count($_GET) <= 0){$finalurl = $_SERVER['REQUEST_URI'].'?';}
								else{$finalurl = $_SERVER['REQUEST_URI'].'&';}
								# end if


								$url = $finalurl;
								list($file, $parameters) = explode('?', $url);
								parse_str($parameters, $output);
								$checked = "";
								if(isset($_GET[$parameter])){
								   $oldvalue = $output[$parameter];
								   unset($output[$parameter]);
									if(strpos($_GET[$parameter], '-|-') !== false) {

										$var = explode('-|-',$_GET[$parameter]);
										$newvalue = "";
			 
											if (in_array($attr_value, $var)) {
												$checked = "checked";
												$key = array_search($attr_value, $var);
												unset($var[$key]); 
												$newvalue = implode("-|-", $var); 
												$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$newvalue;
											}else{
												$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$oldvalue.'-|-'.$attr_value;
											}

									} else {

										if($_GET[$parameter] == $attr_value){
											$checked = "checked";
											$link = $file . '?' . http_build_query($output); 
										}else{
											$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$oldvalue.'-|-'.$attr_value;
										}
									}
								 
								}else{
									$link = $file . '?' . http_build_query($output).'&'. $parameter.'='.$attr_value; 
								}

								echo '<div class="span-filter pull-left"><a href="'.$link.'"><input type="checkbox" '.$checked.' class="cbx" data-value="'.$link.'" > 
										<label for="cbx" class="cbx">'.ucfirst(strtolower($attr_value)).'</label>
									</a></div>';
							}
						}
					}
					?> 
					<br/>
					<br/>
					<p class="more_attr">More Filters</p>
					<p class="less_attr">Less Filters</p>
				
			</div>
			
		</div>
	</div>
</div>

<script src="<?= base_url() ?>assets/js/src/bootstrap.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
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
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/categorynavigation.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>

<?php
$price1= "";
$price2 = "";
if(isset($_GET['price'])){
    if(strpos($_GET['price'], 'to') !== false)
    {
        $price = explode('to',  $_GET['price']);
        $price1 = str_replace( ',', '', $price[0]);
        $price2 = str_replace( ',', '', $price[1]);          
       
    } else {
       $price1= "";
       $price2 = "";
   }
}
?>

<script type="text/javascript">
$(document).ready(function() {

    var today = new Date();
    var expiry = new Date(today.getTime() + 30 * 24 * 3600 * 1000); // plus 30 days 

    function createCookie(name, value, expires, path, domain) {
        var cookie = name + "=" + escape(value) + ";";
        if (expires) { 
            if(expires instanceof Date) { 
                if (isNaN(expires.getTime()))
                    expires = new Date();
            }
            else
                expires = new Date(new Date().getTime() + parseInt(expires) * 1000 * 60 * 60 * 24);
                cookie += "expires=" + expires.toGMTString() + ";";
        }
        if (path)
            cookie += "path=" + path + ";";
        if (domain)
            cookie += "domain=" + domain + ";";
        document.cookie = cookie;
    }

    function getCookie(name) {
        var regexp = new RegExp("(?:^" + name + "|;\s*"+ name + ")=(.*?)(?:;|$)", "g");
        var result = regexp.exec(document.cookie);
        return (result === null) ? null : result[1];
    }

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
 
    // START OF INFINITE SCROLLING FUNCTION
    var base_url = config.base_url;
    var offset = 1;
    var request_ajax = true;
    var ajax_is_on = false;
    var objHeight = $(window).height() - 50;
    var last_scroll_top = 0;
    
    <?php 
    $type = 0;
    if(isset($_COOKIE['view'])){
        $type = ($cookieView == "list") ? '1' : '0';
    }
    ?>

    var type = '<?php echo $type ?>';
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    $(window).scroll(function(event) {
        var st = $(this).scrollTop();
        if(st > last_scroll_top){
            if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {
                if (request_ajax === true && ajax_is_on === false) {
                    ajax_is_on = true; 
                    $.ajax({
                        url: base_url + 'category_more',
                        data:{page_number:offset,id_cat:'<?php echo $id_cat ?>',type:type, parameters:'<?php echo  json_encode($_GET); ?>', csrfname : csrftoken},
                        type: 'post',
                        dataType: 'JSON',
                        onLoading:jQuery(".loading_products").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                        success: function(d) {
                            if(d == "0"){
                                ajax_is_on = true;
                            }else{ 
                                if(d.substring(0,5)  == "<dob>"){
                                    $($.parseHTML(d.trim())).appendTo($('#product_content'));
                                    ajax_is_on = true;
                                }else{
                                    $($.parseHTML(d.trim())).appendTo($('#product_content'));
                                    ajax_is_on = false;
                                    offset += 1;   
                                }
                            }
                           jQuery(".loading_products").fadeOut();    
                        }
                    });
                }
            }
        }
        last_scroll_top = st;

    });
    // END OF INFINITE SCROLLING FUNCTION


    $(document).on('click','.smr_btn',function () {
        ajax_is_on = false;
        $('.phides').show();
        $(this).hide();
    });

    $(".cbx").click(function() {
        window.location = $(this).data('value');
    });

    $('#list').click(function() {
        type = 1;
        createCookie("view ", "list", 30); 
        $('.product').animate({opacity: 0}, function() {
            $('.grid').removeClass('grid-active');
            $('.list').addClass('list-active');
            $('.product').attr('class', 'product-list');
            $('.product-list').stop().animate({opacity: 1}, "fast");
        });
    });

    $('#grid').click(function() {
        type = 0;
        createCookie("view ", "grid", 30);  
        $('.product-list').animate({opacity: 0}, function() {
            $('.list').removeClass('list-active');
            $('.grid').addClass('grid-active');
            $('.product-list').attr('class', 'product');
            $('.product').stop().animate({opacity: 1}, "fast");
        });
    });

    $('.nav_title').mouseover(function(e) {
       // $("nav").show();
	   var d = getElementsByTagName("nav")
	   d.style.display = "inline";
    });
    $('.nav_title').mouseout(function(e) {
        $("nav").hide();
    });
    $("nav").mouseenter(function() {
        $(this).show();
    }).mouseleave(function() {
        $(this).hide();
    });
 
    $('#price1').val(<?php echo $price1 ?>);
    $('#price2').val(<?php echo $price2 ?>);

    $(document).on('change',"#price2,#price1",function () {
        var priceval = this.value.replace(new RegExp(",", "g"), '');
        var v = parseFloat(priceval);
        var tempval;
        if (isNaN(v)) {
            this.value = '';
        } else {
            tempval = Math.abs(v);
            this.value = tempval.toFixed(2);
        }
    });

    $('.price').click(function() {
        var price1 = $('#price1').val();
        var price2 = $('#price2').val();
        var url = $(this).data("url");

        if(price1 == "" && price2 == "")
        {
            url = removeParam("price", url);
        }else{
            url = removeParam("price", url);
            url = url +'&price='+ price1 +'to'+price2;
        }
        if(price1 == "" && price2 != ""){
            $("#price1").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
            $( "#price1" ).focus();
        }else if(price1 != "" && price2 == ""){
            $("#price2").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
            $( "#price2" ).focus();
        }else if(price1 > price2){
           $("#price1,#price2").css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
                "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
                "box-shadow": "0px 0px 2px 2px #FF0000"});
            $( "#price1" ).focus();
        }else{
            document.location.href=url;
        }     
    });
 
    $( "#price1 , #price2" ).keypress(function(evt) {
 
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
            return false;

        return true;
               $(this).css({"-webkit-box-shadow": "0px 0px 0px 0px #FFFFFF",
            "-moz-box-shadow": "0px 0px 0px 0px #FFFFFF",
            "box-shadow": "0px 0px 0px 0px #FFFFFF"});
         
    });

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

    $(document).on('change','#sort_order',function() {
        var url = $(this).data("url");
        var type = $(this).val();
        url = removeParam("sop", url);
        document.location.href=url+"&sop="+type;

    });

    $(document).on('click','.prod_cat_drop',function() {
        $(".category_nav").toggleClass("category_nav_plus");
        $(".prod_cat_drop").toggleClass("active_prod_cat_drop_arrow");
        $(document).bind('focusin.prod_cat_drop click.prod_cat_drop',function(e) {
            if ($(e.target).closest('.prod_cat_drop, .category_nav').length) return;
            $('.category_nav').removeClass('category_nav_plus');
            $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');
        });
    });

    $('.category_nav').removeClass('category_nav_plus');
    $('.prod_cat_drop').removeClass('active_prod_cat_drop_arrow');

});

</script>
 
<script type="text/javascript">
    $(document).ready(function () {
        if ($('.left_attribute').length === $('.left_attribute:contains("a")').length) {
            $('.left_attribute').children('h3:gt(2)').nextAll().hide();
            $('.left_attribute').children('h3:gt(2)').hide();
            $('.left_attribute').children('.more_attr').show();
        }
        else {
            $('.more_attr').hide();
        }
    });
</script>
 
<script type="text/javascript">
    (function($) {
        $(function() {
            $('.jcarousel').jcarousel();

            $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

            $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

            $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .jcarouselPagination();
        });
    })(jQuery);
</script>

<script type="text/javascript">
    var p = $('.cc2_prod_name span');
    var divh = $('.cc2_prod_name').height();
    while ($(p).outerHeight()>divh) {
        $(p).text(function (index, text) {
            return text.replace(/\W*\s(\S)*$/, '...');
        });
    }
</script>
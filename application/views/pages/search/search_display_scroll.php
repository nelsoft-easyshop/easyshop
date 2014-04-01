<?php

	$grd = get_cookie("grd");
	if($grd == "list" || !isset($grd) || strlen($grd) == 0){
		$class = "product-list";
	}else{
		$class = "product";
	}

	if(isset($items)){
		for ($i=0; $i < sizeof($items); $i++) { 
		$pic = explode('/', $items[$i]['product_image_path']);
?>
			<div class="<?php echo $class; ?>"> <a href="<?=base_url()?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo urlencode($items[$i]['product_name']); ?>.html"><img alt="<?php echo $items[$i]['product_name']; ?>" src="<?php echo base_url().$pic[0].'/'.$pic[1].'/'.$pic[2].'/'.$pic[3].'/'.'categoryview'.'/'.$pic[4];;?>"></a>
				<h3 style="-o-text-overflow: ellipsis; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; width: 225px;">
					<a href="<?=base_url()?>item/<?php echo $items[$i]['product_id']; ?>/<?php echo urlencode($items[$i]['product_name']); ?>.html"><?php echo $items[$i]['product_name']; ?></a>
				</h3>
				<div class="price-cnt">
					<div class="price">
						<span>&#8369;</span><?php echo number_format($items[$i]['product_price'],2); ?>
					</div>
				</div>
				<div class="product_info_bottom">
					<div>
						Condition: <strong><?php echo $items[$i]['product_condition']; ?></strong>
					</div>
				</div>
				<p><?php echo $items[$i]['product_brief']; ?></p>
			</div>
<?php
		} // end of loop
	} // end of isset
?>
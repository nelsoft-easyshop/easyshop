<?php foreach( $products as $prod ):?>
<div class="media table-bordered mrgin-bttm-8 product">
	<div class="col-md-10 col-sm-10 media-sub media-content">
		<a class="pull-left" target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">
			<img class="media-object" src="<?php echo base_url() . $prod['path'] . "categoryview/" . $prod['file']?>">
		</a>
		<div class="media-body">
			<div class="content">
				<h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>"><?php echo $prod['name']?></a></h5>
				<?php echo $prod['brief']?>
			</div>
			<div class="condition">Condition: <?php echo $prod['condition']?></div>
		</div>
	</div>
	<div class="col-md-2 col-sm-2 media-sub media-btn-panel">
		<p>Php</p>
		<p class="feed-price"><?php echo $prod['price']?></p>
		<div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">Buy Now</a></div>
	</div>
</div>
<?php endforeach;?>
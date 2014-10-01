<div class="category_list_container" id="container_level<?=$level.$cat_id;?>">
	<?php foreach ($node as $row): ?>
	<div class="border-rad-3">
		<a href="javascript:void(0)" data-parentid="<?=$row['parent_id']; ?>" data-catid="<?=$row['id_cat']; ?>" data-level="<?=$level?>" data-name="<?=html_escape($row['name']); ?>"  class="category_link display-ib">
			<?=html_escape($row['name']); ?>
		</a>
	</div>
	<?php endforeach; ?>
	<div class="border-rad-3 add-cat-con"> 
		<a class="custom_category_link display-ib" data-level="0" data-catid="<?=$cat_id; ?>">Add Category
			<span class="span_bg icon-add border-rad-90"></span>
		</a>
	</div>
</div>
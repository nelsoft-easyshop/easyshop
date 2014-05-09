<?php 
header('Content-Type: application/json'); ?>
<script>
$(document).ready(function() {
$('#box<?php echo $level; ?>').keyup(function() {  // this function is for searching item on the list box every category
var valThis = $(this).val().toLowerCase();
$('.navList<?php echo $level; ?>>li').each(function() {
var text = $(this).text().toLowerCase();
(!text.contains(valThis) == 0) ? $(this).show() : $(this).hide();
});
});
});
</script>
<?php
if (empty($node)) { # if no more available item on selected category the button procedd will show 
?>
<div class='product_sub_items<?php echo $level; ?> parent<?php echo $cat_id ?>' data-final="true">
<input type="text" class="box<?php echo $level; ?>" id="box<?php echo $level; ?>">
<ul class="product-list navList<?php echo $level; ?>" style="list-style-type:none">
<li  class="othercategory othercategory<?php echo $level; ?>"><a href="javascript:void(0)" class="select2" data-level="<?php echo $level; ?>" data-parent="<?php echo $cat_id; ?>" data-parentname="<?php echo addslashes($name)?>" data-final="true"><b class="add_cat span_bg"></b><b>Add a Category</b></a></li>
</ul>
</div>
<script type="text/javascript">
$(document).ready(function() {
$(".add_category_submit").empty();
$(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+<?php echo $cat_id ?>+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with <?php echo html_escape($name) ?>">');
});     
</script>
<?php
} else {  # if there is more available item on selected category the list will generate
?>
<div class='product_sub_items<?php echo $level; ?> parent<?php echo $cat_id; ?>' data-final="false">
<input type="text" class="box<?php echo $level; ?>" id="box<?php echo $level; ?>">
<ul class="product-list navList<?php echo $level; ?>" style="list-style-type:none">
<?php
foreach ($node as $row) { # generating all child category base on selected parent category from product_upload_step3_view
?>
<li  class="<?php echo $row['parent_id']; ?>"><a href="javascript:void(0)" data="{cat_id:'<?php echo $row['id_cat']; ?>',level:'<?php echo $level ?>',name:'<?php echo addslashes ($row['name']); ?>'}" class="child select2"><?php echo $row['name']; ?></a></li>
<?php }
?>
<li  class="othercategory othercategory<?php echo $level; ?> "><a href="javascript:void(0)" class="select2" data-level="<?php echo $level; ?>" data-parent="<?php echo $row['parent_id']; ?>" data-parentname="<?php echo addslashes($name)?>" data-final="false"><b class="add_cat span_bg"></b><b>Add a Category</b></a></li>
</ul>
</div>
<script type="text/javascript">
$(document).ready(function() {
$(".add_category_submit").empty();
$(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+<?php echo $cat_id ?>+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with <?php echo html_escape($name) ?>">');
});     
</script>
<?php
}
?>
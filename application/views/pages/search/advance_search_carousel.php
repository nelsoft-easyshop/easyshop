<?php header('Content-Type: application/json'); ?>
<div id="content1">
	<script type="text/javascript">
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
	<script type="text/javascript">
	(function($) {  // generating the carousel for better view
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
	
			$('.jcarousel').jcarousel('reload');
			
		});
	})(jQuery);
	</script>
	<?php if (!empty($node)) { ?>
	<div class='product_sub_items<?php echo $level; ?> parent<?php echo $cat_id; ?>' data-final="true">
		<?php if (!empty($node)) { ?>
			<input type="text" class="box<?php echo $level; ?>" id="box<?php echo $level; ?>">
		<?php } ?>
		<ul class="product-list navList<?php echo $level; ?>" style="list-style-type:none">
			<?php 
			foreach ($node as $row) { # generating all child category base on selected parent category from product_upload_step3_view
			?>
				<li  class="<?php echo $row['parent_id']; ?>">
					<a href="javascript:void(0)" data="{cat_id:'<?php echo $row['id_cat']; ?>',level:'<?php echo $level ?>',name:'<?php echo addslashes($row['name']); ?>'}" class="child select2">
						<?php echo $row['name']; ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
</div>
<div id="content2">
	<?php  if(!empty($attribute)){ ?>
		<style type="text/css">
			.as_style {
				margin-top: 20px;
				padding: 5px;
				width: 980px;
			}
			
			.as_style tr td {
				padding: 0 15px 15px 0;
			}
			
			.as_style tbody tr td span {
				display: inline-block;
				padding-bottom: 5px;
				vertical-align: middle;
				width: 190px;
			}
			
			.as_style input {
				margin-right: 5px;
				vertical-align: middle;
			}
		</style>
		<table class="as_style">
			<tr>
				<td colspan="3">
					<h3 style="color:#FF4400">Select item attributes</h3> 
					<hr />
				</td>
			</tr>			
			<?php 
			$array_name_inputs = "";
			$array_name_of_inputs = array();
			$input_type = "";
			$input_cat_name = "";
			$input_id_attr = "";
			
			for ($i=0 ; $i < sizeof($attribute); $i++)
			{
				$input_type = strtoupper($attribute[$i]['input_type']);
				$input_cat_name =str_replace(' ', '', ucwords(strtolower($attribute[$i]['cat_name']))); 
				$input_cat_name_with_space = ucwords(strtolower($attribute[$i]['cat_name'])); 
				$input_id_attr = $attribute[$i]['id_attr'];
				$itemattribute = $attribute[$i][0];
				
				?>
				<tr>
					<td nowrap="nowrap" width="8px">
						<?php echo  ucwords(strtolower($attribute[$i]['cat_name'])); ?>
					</td>
					<td colspan="2">
						<?php 
						if(isset($product_attributes_spe[$input_id_attr])){
							$cat_attr = $product_attributes_spe[$input_id_attr];
						}else{
							$cat_attr = array();   
						}
						#Removed case formatting for input type values
						switch ($input_type) {
							case 'SELECT':
								echo '<span><select name="'.$input_cat_name.'">';
								echo '<option value="">-</option>';
								foreach ($itemattribute as $list) {
									$selected = '';
									if(isset($cat_attr[0])){
										if($cat_attr[0]['value'] === $list['name']){
											$selected = 'selected';
											unset($cat_attr[0]);
										}
									}
									echo '<option value="'.$list['name'].'"'.$selected.'>'.$list['name'].'</option>';
								}
								echo '</select></span>';
								break;

							case 'RADIO':
								foreach ($itemattribute as $list) {
									$checked = '';
									if(isset($cat_attr[0])){
										if($cat_attr[0]['value'] === $list['name']){
											$checked = 'checked';
											unset($cat_attr[0]);
										}
									}
									echo "<span><input type='radio' value='".$list['name']."' name='".$input_cat_name."' ".$checked.">".$list['name']."</span>";              
								}
								break;
							
							case 'CHECKBOX':
							
								foreach ($itemattribute as $list) {
									$checked = '';
									$id_attribute_value = $list['id_attr_lookuplist_item'];
									foreach($cat_attr  as $key=>$prod_attr){ 
										if($prod_attr['value']===$list['name']){
											$checked = 'checked';
											unset($cat_attr[$key]);
											break;
										}
									}
									echo "<span><input type='checkbox' class='checkbox_itemattr' data-group='".$input_cat_name_with_space."'   data-attrid='". $id_attribute_value."' data-value='".$list['name']."' data-attr='".$input_cat_name.'_'.str_replace(' ', '', $list['name'])."' value='".$list['name']."' name='".$input_cat_name."[]' ".$checked.">".$list['name']."</span>";
								}
								break;
						}
						?>
					</td>
				</tr>
			<?php
				$array_name_inputs = $array_name_inputs .'|'. $input_cat_name .'/'. $input_id_attr;
			} // end of for loop
			?>
			<tr>
				<td colspan="3">
					<hr />
				</td>
			</tr>			
		</table> 
	<?php } ?>
</div>
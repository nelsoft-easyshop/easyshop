<link rel="stylesheet" href="<?=base_url()?>assets/css/sell_item.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.selectize-default.css" type="text/css" media="screen"/>
<script src="<?= base_url() ?>assets/JavaScript/js/jquery.selectize.js" type="text/javascript"></script>
<!--
<link rel="stylesheet" href="<?=base_url()?>assets/css/product_search_category.css" type="text/css" media="screen"/>
-->
<div class="wrapper" id="main_search_container">
<!-- For future updates	
	<div class="left_attribute">
		<h3>Find Items</h3>
		<a href="#">By seller</a> 
		<a href="#">By bidder</a> 
		<a href="#">By item number</a> 
		<br />
	</div>-->
	<div class="right_product">
		<div class="wrapper">
			<div class="seller_product_content">
				<div class="inner_seller_product_content">
								
					<form action="<?php echo base_url(); ?>advance_search/red" name="search_form" method="get">
						<div class="keyword_container">
							<label for="_is" class="keyword_label"><strong>Enter keywords:</strong></label>
							<input type="text" name="_is" id="_is" size="50" maxlength="300" value="" placeholder="Enter keywords or item number" />
						</div>
						<div class="cat_sch_container">
							<b>Search for category: &nbsp;</b>
							<input type="text" class="box" id="_cat_sch" autocomplete="off"><div class="cat_sch_loading"></div>
							<div id="cat_search_drop_content" class="cat_sch_drop_content"></div>
						</div>						
						
						<div class="add_product_category">
							<div class="main_product_category">
								<input type="text" class="box" id="_box">
								<ul class="navList" style="list-style-type:none">  
									<?php
									foreach ($firstlevel as $row) { # generate all parent category.
									?>
									<li class="<?php echo $row['parent_id']; ?>">
										<a href="javascript:void(0)" data="{cat_id:'<?php echo $row['id_cat']; ?>',level:'<?php echo '0' ?>',name:'<?php echo addslashes ($row['name']); ?>'}" class="select"><?php echo $row['name']; ?></a>
									</li>
									<?php } ?>
								</ul>
							</div>
							<div class="carousel_container">
								<div class="jcarousel">
									<div class="product_sub_category"></div>
									<div class="sub_cat_loading_container loading_img"></div>
									<div class="loading_category_list loading_img"></div>
								</div>
								<!-- Controls -->
								<a href="#" class="jcarousel-control-prev inactive">&lsaquo;</a>
								<a href="#" class="jcarousel-control-next inactive">&rsaquo;</a>
							</div>
						</div>						
						
						<div id="rainLink"></div>
						<div id="rainValue"></div>			
						
						<div class="adv_bottom_container">
						
							<div>
								<span class="adv_attr_title">Brand:</span>
								<select id="searchbox" name="BRAND[]" placeholder="Select product brand" class="form-control">
								</select>
							</div>
							<br />					
							<div>
								<span class="adv_attr_title">Item Condition:</span>
								<select name="_con" id="_con" size="1">
									<option value="">- All -</option>          
									<?php foreach($this->lang->line('product_condition') as $x): ?>
										<option value="<?php echo $x;?>"><?php echo $x; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<br />
							<div>
								<span class="adv_attr_title">Price:</span>
								<input type="text" id="_price1" name="_price1" maxlength=10 size=6> to 
								<input type="text" id="_price2" name="_price2" maxlength=10 size=6>
							</div>
							<br />
							<div>
								<span class="adv_attr_title">Sort by:</span>
								<select name="_sop" id="_sop">
									<option value="popular">Popular</option>
									<option value="hot">Hot</option>
									<option value="new">New</option>
									<option value="con">Item Condition</option>
								</select>
							</div>
						</div>									
						<div class="add_category_submit">
							<input class="proceed_form" id="proceed_form" type="submit" value="Select Category">
						</div>
						<input type="hidden" name="_cat" id="_cat" value="">			
					</form>				
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>

<style type="text/css">
.err{
	-webkit-box-shadow: 0px 0px 2px 2px #FF0000;
	-moz-box-shadow: 0px 0px 2px 2px #FF0000;
	box-shadow: 0px 0px 2px 2px #FF0000;	
}
</style>
<script>
$(document).ready(function(){
	var action = 'product_search/getBranch';
	
    $('#searchbox').selectize({
        valueField: 'name',
        labelField: 'name',
        searchField: ['name'],
        maxOptions: 10,
        options: [],
        create: false,
        render: {
            option: function(item, escape) {
                return '<div>'+ escape(item.name) +'</div>';
            }
        },
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
				url: '<?php echo base_url(); ?>' +  action,
                type: 'GET',
                dataType: 'json',
                data: {q: query},
                error: function(){callback();},
                success: function(response) {
					var obj = response;
					if(obj.length > 0){
						callback(response);
					};
                }
            });
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function() {	
		
		$("#_price1,#_price2").change(function(){
			$(this).removeClass("err");
			var val = parseFloat($(this).val());
			if (isNaN(val)){
				$(this).val('');
			}else{
				$(this).val(val.toFixed(2)); 
			}			
		});

		$("#proceed_form").click(function() {

			if ($("#_cat").val() == ""){
				alert("Please select a category");
				return false; //Prevent the default button action
			}
			
			var price1 = $("#_price1").val();
			var price2 = $("#_price2").val();
			var msg = "Invalid price range";
			
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
			}		
			
		});

		var globalParent;
		var globalLevel;

		$(document).on('click','.product-list li a',function () { 
			$(this).addClass('active').parent().siblings().children('a').removeClass('active');
		});

		$(document).on('click','.navList li a',function () { 
			$(this).addClass('active').parent().siblings().children('a').removeClass('active');
		});

        $("#_box").unbind("click").click(function() {  // this function is for searching item on the list box every category
            $('#_box').keyup(function() {
                var valThis = $(this).val().toLowerCase();
                $('.navList>li').each(function() {
                    var text = $(this).text().toLowerCase();
                    (!text.contains(valThis) == 0) ? $(this).show() : $(this).hide();
                });
            });
        });

		// requesting the child category from selected FIRST LEVEL parent category
        $(document).on('click','.select',function () {
 	
			$('#proceed_form').prop("disabled", false); 
            var D = eval('(' + $(this).attr('data') + ')');
            var action = 'product_search/getChild';
            var catId = D.cat_id;
            var level =  D.level;
            var name = D.name;
			var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');

            $(".product_sub_category .product_sub_items0").nextAll().remove();
            $(".product_sub_category .product_sub_items0").remove();
			$(".as_style").remove();

			$("#_cat").val("");
			$("#proceed_form").val("Select Category");
			$.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url(); ?>' + action,
				data: "cat_id=" + catId + "&level=" + level + "&name=" + name + "&"+csrfname+"=" + csrftoken,
				dataType: "json",
				cache: false,
				onLoading:jQuery(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
				success: function(d) {
					var $response=$(d);
					var val = $response.filter('#content1').html();
					var subval = $response.filter('#content2').html();
					
					$('.product_sub_category').append(val);
					$('#storeValue').append(val);
					
					$('#rainValue').append(subval);	
					$('#storeValue2').append(subval);			
					
					$("#_cat").val(catId);
					$("#proceed_form").val("Proceed with " + name);
					jQuery(".sub_cat_loading_container").hide();
				}
			});	
       	});

		// requesting the CHILD category from selected category
        $(document).on('click','.child',function () { 
            $('#proceed_form').prop("disabled", false);
			var D = eval('(' + $(this).attr('data') + ')');
            var nlevel = parseInt(D.level) + 1;
            var action = 'product_search/getChild';
			var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var catId = D.cat_id;
            var name = D.name;

			$("#_cat").val("");
			$("#proceed_form").val("Select Category");
            $(".product_sub_category .product_sub_items" + D.level).nextAll().remove(); 
            $(".product_sub_category .product_sub_items" + nlevel).nextAll().remove(); 
			$(".as_style").remove();
			$.ajax({
				async: false,
				type: "POST",
				url: '<?php echo base_url(); ?>' +  action,
				data: "cat_id=" + catId + "&level=" + nlevel + "&name=" + name + "&"+csrfname+"=" + csrftoken,
				dataType: "json",
				cache: false,
				onLoading:$(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
				success: function(d) {
					var $response=$(d);
					var val = $response.filter('#content1').html();
					var subval = $response.filter('#content2').html();
					
					$('.product_sub_category').append(val);
					$('#storeValue').append(val);
					
					$('#rainValue').append(subval);	
					$('#storeValue2').append(subval);	
					
					$(".sub_cat_loading_container").hide();
					$("#_cat").val(catId);
					$("#proceed_form").val("Proceed with " + name);			   
				}
			});
           $('.jcarousel').jcarousel('scroll', '+=1');
       });


        $(document).on('click','.othercategory a',function () {
            var selfAttrParent = $(this).data('parent');
            var selfLevel = $(this).data('level'); 
            var finalValue = $(this).data('final');
            var parentName = $(this).data('parentname');
            globalParent = selfAttrParent;
            globalLevel = selfLevel;
            $('.othercategory'+selfLevel).empty();
            $(".product_sub_category .product_sub_items" + selfLevel).nextAll().remove(); 
            $('.product_sub_items'+selfLevel+' .othercategory'+selfLevel).append('<input type="text" id="otherNameCategory" class="otherNameCategoryClass'+selfLevel+'" data-parentname="'+parentName+'" data-level="'+selfLevel+'" data-final="'+finalValue+'" autocomplete="off" name="othernamecategory" />');
            $('.product_sub_items'+selfLevel+' .otherNameCategoryClass'+selfLevel).focus();
            $(".add_category_submit").empty();
			$(".as_style").remove();
        });

        $(document).on('blur change','#otherNameCategory',function () {
            var otherName = $(this).val();
            var finalValue = $(this).data('final');
            var parentName = $(this).data('parentname');
            
			$("#_cat").val("");
			$("#proceed_form").val("Select Category");	
            if(otherName.length == 0){ 

                $('.product_sub_items'+globalLevel+' .othercategory').empty();
                // add a category
				$('.product_sub_items'+globalLevel+' .othercategory').append('<a href="javascript:void(0)" class="select2" data-level="'+globalLevel+'" data-parent="'+globalParent+'"><b class="add_cat span_bg"></b><b>Add a Category</b></a>');
				$("#_cat").val(globalParent);
				$("#proceed_form").val("Proceed with " + parentName);	

            }else{ 
				$("#_cat").val(globalParent);
				$("#proceed_form").val("Proceed with " + otherName.replace(/'/g, "\\'"));	 
            }
        });

        $(document).on('focus','#otherNameCategory',function () {
            var level = $(this).data('level');
            $(".product_sub_category .product_sub_items" + level).nextAll().remove();    
        });
});
</script> 
<div id="storeValue" style="display:none"></div>
<div id="storeValue2" style="display:none"></div>
<script>  
      
   $(document).ready(function() {
       $('#cat_search_drop_content').on('click', 'li.cat_result', function(){
            var parent_ids = eval('('+$(this).attr('data-parent')+')');
            $('li .select').each(function(){
                var D = eval('(' + $(this).attr('data') + ')');
                if( parseInt(D.cat_id) === parent_ids[0]){
                    $(this).click();
                    scrollToElement(this, '.main_product_category');
                    return false;
                }
            });
            var cnt = 0;
            $.each(parent_ids, function(){
                var id  = parent_ids.shift();
                $('li.'+ id+' .select2.child').each(function(){
                    var D = eval('(' + $(this).attr('data') + ')');
                    if( parseInt(D.cat_id) === parent_ids[0]){
                        $(this).click(); 
                        scrollToElement(this, '.product_sub_items' +cnt);
                        cnt++;
                        return false;
                    }
                });
            });
        });
        
        var currentRequest = null;
        $( "#_cat_sch" ).keyup(function() {
            var searchQuery = $(this).val();
			var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            if(searchQuery != ""){
                currentRequest = jQuery.ajax({
                    type: "GET",
                    url: '<?php echo base_url();?>product/searchCategory', 
                    data: "data="+searchQuery+"&"+csrfname+"="+csrftoken, 
                    onLoading:jQuery(".cat_sch_loading").html('<img src="<?= base_url() ?>assets/images/orange_loader_small.gif" />').show(),
                    beforeSend : function(){       
                        $("#cat_search_drop_content").empty();
                        if(currentRequest != null) {
                            currentRequest.abort();
                        }
                    },
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);
                        var html = '<ul>';
                        var data_content, data_id, cnt;
                        var delim_img = ' <img src = "<?=base_url()?>assets/images/img_bullet2.jpg"/> ';
						if((obj.length)>0){
                            jQuery.each(obj,function(){
                                data_content = '';
                                data_id = '[';
                                count = 0;
                                var length = $(this)[0].parent.length;
                                //Iterate through the parent categories
                                jQuery.each($(this)[0].parent,function(){
                                    count++;
                                    if(count !== length){
                                        data_content += $(this)[0].name + delim_img;
                                        data_id += $(this)[0].id_cat+",";
                                    }
                                    else{
                                        data_content += '<b>' + $(this)[0].name + '</b>';
                                        data_id +=  $(this)[0].id_cat + "]";
                                    }
                                });
                                html += '<li class="cat_result" data-parent="'+data_id+'"><a href="javascript:void(0)">'+data_content+'</a></li>' ;                             
                            });
                        }
                        else{
                            html += '<li> No results found </li>' 
                        }
                        html += '</ul>';
                        $("#cat_search_drop_content").html(html);
                        jQuery(".cat_sch_loading").hide();
                    }
                });
            }
        });
 
    }); // document.ready end
    
    function scrollToElement(selector, container, time) {
        var xtime = typeof(time) != 'undefined' ? time : 100;
        var container = typeof(container) != 'undefined' ? container : 'html, body';
        var verticalOffset = $(container).offset().top;
        var element = $(selector);
        var offset = element.offset();
        var offsetTop = offset.top - verticalOffset;
        $(container).animate({
            scrollTop: offsetTop
        }, xtime);
    }
</script>
<script>
 $(document).ready(function() { 

	$('#_cat_sch').focus(function() {
	$('#cat_search_drop_content').show();
	$(document).bind('focusin.cat_sch_drop_content click.cat_sch_drop_content',function(e) {
		if ($(e.target).closest('#cat_search_drop_content, #_cat_sch').length) return;
		$('#cat_search_drop_content').hide();
		});
	 });

	$('#cat_search_drop_content').hide();
});
</script>
<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css" rel="stylesheet" />
<div class="wrapper"> 

    <div class="clear"></div>

    <!-- <div class="tab_list">
        <p><a href="">Iam a Buyer</a></p> 
        <p class="active"><a href="">Iam a Seller</a></p>
    </div>
    <div class="clear"></div> -->
    <div class="seller_product_content">
        <!-- <div class="top_nav">
            <ul>
                <li>
                    
                    <a href="">
                        <img src="<?= base_url() ?>assets/images/img_signup.png" alt="signup"><br />
                        <span>Account Sign-in</span>
                    </a>
                   
                </li>
                <li>
                    
                    <a href="">
                        <img src="<?= base_url() ?>assets/images/img_shop.png" alt="shop"><br />
                        <span>Want to Shop</span>
                    </a>
                    
                </li>
                <li>
                   
                    <a href="">
                        <img src="<?= base_url() ?>assets/images/img_setup.png" alt="setup"><br />
                        <span>Shop exam and set up shop</span>
                    </a>
                   
                </li>
                <li>
                    
                    <a href="">
                        <img src="<?= base_url() ?>assets/images/img_publish.png" alt="publish"><br />
                        <span>Published Baby</span>
                    </a>
                   
                </li>
                <li>
                    
                    <a href="">
                        <img src="<?= base_url() ?>assets/images/img_delivery.png" alt="delivery"><br />
                        <span>Delivery Operation</span>
                    </a>
                    
                    
                </li>
                <li>
                    
                    <a href="">
                        <img src="<?= base_url() ?>assets/images/img_ratings.png" alt="ratings"><br />
                        <span>Ratings &amp; Withdrawals</span>
                    </a>
                   
                </li>
            </ul>
        </div> -->

        
        <!--<form action="<?php //echo base_url() . 'sell/step2'; ?>" method="POST">-->
        <?php echo form_open('sell/step2'); ?>

        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
			<input type="hidden" id="uploadstep1_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
            <div class="sell_steps sell_steps1">
                <ul>
                    <li><a href="#">Step 1 : Select Category</a></li>
                    <li><a href="#">Step 2 : Upload Item</a></li>                   
                    <li><a href="#">Step 3: Select Shipping Courier</a></li>
                    <li><a href="#">Step 4: Success</a></li>
                </ul>
            </div>

            <!-- <div class="search_box seller_search_box">
              <div>
                <input type="text">
                <button class="search_btn">SEARCH</button>
              </div>         

            </div>  -->
            <div class="clear"></div>
            <div class="cat_sch_container">
               <b>Search for category: &nbsp;</b><input type="text" class="box" id="cat_sch" autocomplete="off"><div class="cat_sch_loading"></div>
             <div id="cat_search_drop_content" class="cat_sch_drop_content"></div>


       </div>


       <div class="add_product_category">
        <div class="main_product_category">
            <input type="text" class="box" id="box">
            <ul class="navList" style="list-style-type:none">  
                <?php
                        foreach ($firstlevel as $row) { # generate all parent category.
                            ?>


                            <li class="<?php echo $row['parent_id']; ?>"><a href="javascript:void(0)" data="{cat_id:'<?php echo $row['id_cat']; ?>',level:'<?php echo '0' ?>',name:'<?php echo addslashes ($row['name']); ?>'}" class="select"><?php echo $row['name']; ?></a></li>

                            <?php } ?>
                        </ul>
                    </div>
                    <div class="carousel_container">
                        <div class="jcarousel">
                            <div class="product_sub_category">
                            </div>
                            <div class="sub_cat_loading_container loading_img">
                            </div>

                            <div class="loading_category_list loading_img"></div>
                        </div>

                        <!-- Controls -->
                        <a href="#" class="jcarousel-control-prev inactive">&lsaquo;</a>
                        <a href="#" class="jcarousel-control-next inactive">&rsaquo;</a>



                    </div>
                </div>

                <div class="clear"></div>
                
                <div class="add_category_submit"></div>
                
            </div>
            <?php echo form_close();?>
        </div>

        <div class="clear"></div>  


        <script>
        $(document).ready(function() {

            var globalParent;
            var globalLevel;

            $(document).on('click','.product-list li a',function () { 
                $(this).addClass('active').parent().siblings().children('a').removeClass('active');
            });

            $(document).on('click','.navList li a',function () { 
                $(this).addClass('active').parent().siblings().children('a').removeClass('active');
            });

        $("#box").unbind("click").click(function() {  // this function is for searching item on the list box every category
            $('#box').keyup(function() {
                var valThis = $(this).val().toLowerCase();
                $('.navList>li').each(function() {
                    var text = $(this).text().toLowerCase();
                    (!text.contains(valThis) == 0) ? $(this).show() : $(this).hide();
                });
            });
        });

        $(document).on('click','.select',function () { // requesting the child category from selected first level parent category
            $(".add_category_submit").empty();
            var D = eval('(' + $(this).attr('data') + ')');
            var action = 'productUpload/getChild';
            var catId = D.cat_id;
            var level =  D.level;
            var name = D.name;
			var csrftoken = $('#uploadstep1_csrf').val();

            $(".product_sub_category .product_sub_items0").nextAll().remove();
            $(".product_sub_category .product_sub_items0").remove();

            if($('#storeValue .parent'+catId).length == 0) {
                $.ajax({
                    async: false,
                    type: "POST",
                    url: '<?php echo base_url(); ?>' + action,
                    data: "cat_id=" + catId + "&level=" + level + "&name=" + name + "&es_csrf_token=" + csrftoken,
                    dataType: "json",
                    cache: false,
                    onLoading:jQuery(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                    success: function(d) {
                        $(".product_sub_category").append(d);
                        $("#storeValue").append(d);

                        jQuery(".sub_cat_loading_container").hide();
                    }
                });
            }else{
               var clone = $('#storeValue .parent'+catId).clone();
               $('.product_sub_category').append(clone);
           }
       });

        $(document).on('click','.child',function () { // requesting the child category from selected category
            var D = eval('(' + $(this).attr('data') + ')');
            var nlevel = parseInt(D.level) + 1;
            var action = 'productUpload/getChild';
            var catId = D.cat_id;
            var name = D.name;
			var csrftoken = $('#uploadstep1_csrf').val();
			
            $(".add_category_submit").empty();
            $(".product_sub_category .product_sub_items" + D.level).nextAll().remove(); 
            $(".product_sub_category .product_sub_items" + nlevel).nextAll().remove(); 
            if($('#storeValue .parent'+catId).length == 0) {
                $.ajax({
                    async: false,
                    type: "POST",
                    url: '<?php echo base_url(); ?>' +  action,
                    data: "cat_id=" + catId + "&level=" + nlevel + "&name=" + name + "&es_csrf_token=" + csrftoken,
                    dataType: "json",
                    cache: false,
                    onLoading:$(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                    success: function(d) {
                        $(".product_sub_category").append(d);
                        $("#storeValue").append(d);
                        $(".sub_cat_loading_container").hide();
                    }
                });
            }else{ 
                var finalValue = $('#storeValue .parent'+catId).data('final');
                var clone = $('#storeValue .parent'+catId).clone();
                $('.product_sub_category').append(clone);

                if(finalValue == true){
                    $(".add_category_submit").empty();
                    $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+catId+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+name+'">');
                }

         }

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
        });

        $(document).on('blur change','#otherNameCategory',function () {
            var otherName = $(this).val();
            var finalValue = $(this).data('final');
            var parentName = $(this).data('parentname');
            

 
            $(".add_category_submit").empty();
            if(otherName.length == 0){ 

                $('.product_sub_items'+globalLevel+' .othercategory').empty();
                $('.product_sub_items'+globalLevel+' .othercategory').append('<a href="javascript:void(0)" class="select2" data-level="'+globalLevel+'" data-parent="'+globalParent+'"><b class="add_cat span_bg"></b><b>Add a Category</b></a>');
                if(finalValue == true){ 
                  $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+parentName+'">');    
                }

            }else{  
                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+otherName.replace(/'/g, "\\'")+'">');    
 
           }
        });

        $(document).on('focus','#otherNameCategory',function () {
            var level = $(this).data('level');
            $(".product_sub_category .product_sub_items" + level).nextAll().remove();    
        });

});
</script> 
<div id="storeValue" style="display:none">



</div>


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
        $( "#cat_sch" ).keyup(function() {
            var searchQuery = $(this).val();
			var csrftoken = $('#uploadstep1_csrf').val();
            if(searchQuery != ""){
                currentRequest = jQuery.ajax({
                    type: "POST",
                    url: '<?php echo base_url();?>product/searchCategory', 
                    data: "data="+searchQuery+"&es_csrf_token="+csrftoken, 
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
                            html += '<li"> No results found </li>' 
                        }
                        html += '</ul>';
                        $("#cat_search_drop_content").html(html);
                        jQuery(".cat_sch_loading").hide();
                    }
                });
            }
        });
 
    });
    
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

            $('#cat_sch').focus(function() {
            $('#cat_search_drop_content').show();
            $(document).bind('focusin.cat_sch_drop_content click.cat_sch_drop_content',function(e) {
                if ($(e.target).closest('#cat_search_drop_content, #cat_sch').length) return;
                $('#cat_search_drop_content').hide();
                });
             });
 
            $('#cat_search_drop_content').hide();
        });

</script>
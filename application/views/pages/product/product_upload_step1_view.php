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
                <div class="sell_steps sell_steps1">
                    <ul>
                        <li><a href="#">Step 1 : Select Category</a></li>
                        <li><a href="#">Step 2 : Upload Item</a></li>
                        <li><a href="#">Step 3: Success</a></li>                    
                    <!-- <li><a href="#">Step 3: Select Shipping Courier</a></li>
                    <li><a href="#">Step 4: Success</a></li> -->
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
               Search Category: <input type="text" class="box" id="cat_sch"><div class="cat_sch_loading"></div>
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
            $.ajax({
                async: false,
                type: "POST",
                url: '<?php echo base_url(); ?>' + action,
                data: "cat_id=" + D.cat_id + "&level=" + D.level + "&name=" + D.name,
                dataType: "json",
                cache: false,
                onLoading:jQuery(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                beforeSend: function(jqxhr, settings) {
                    $(".product_sub_items0").nextAll().remove();
                    $(".product_sub_items0").remove();
                },
                success: function(d) {
                    $(d).appendTo($('.product_sub_category'));
                    jQuery(".sub_cat_loading_container").hide();
                }

            });
        });

        $(document).on('click','.othercategory a',function () {
            selfAttrParent = $(this).data('parent');
            selfLevel = $(this).data('level'); 
            globalParent = selfAttrParent;
            globalLevel = selfLevel;
            $('.othercategory'+selfLevel).empty();
            $('.othercategory'+selfLevel).append('<input type="text" id="otherNameCategory" class="otherNameCategoryClass'+selfLevel+'" autocomplete="off" name="othernamecategory" />');
            $('.otherNameCategoryClass'+selfLevel).focus();
            $(".add_category_submit").empty();
        });

        $(document).on('blur change','#otherNameCategory',function () {
            var otherName = $(this).val();

            if(otherName.length == 0){
                $('.othercategory').empty();
                $('.othercategory').append('<a href="javascript:void(0)" class="select2" data-level="'+globalLevel+'" data-parent="'+globalParent+'">Other</a>');
            }else{
               $(".add_category_submit").empty();
               $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+otherName+'">');    
           }

        });

 
        $(document).on('click','.child',function () { // requesting the child category from selected category
            var D = eval('(' + $(this).attr('data') + ')');
            var nlevel = parseInt(D.level) + 1;
            var action = 'productUpload/getChild';
            $(".add_category_submit").empty();
            $(".product_sub_items" + parseInt(D.level) + 1).remove();
            $(".product_sub_items" + nlevel).nextAll().remove();
            $.ajax({
                async: false,
                type: "POST",
                url: '<?php echo base_url(); ?>' +  action,
                data: "cat_id=" + D.cat_id + "&level=" + nlevel + "&name=" + D.name,
                dataType: "json",
                cache: false,
                onLoading:jQuery(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                beforeSend: function(jqxhr, settings) {
                    $(".product_sub_items" + nlevel).remove();
                    $(".product_sub_items" + parseInt(D.level) + 1).remove();
                    $(".product_sub_items" + nlevel).nextAll().remove();
                },
                success: function(d) {
                    $(".product_sub_category").append(d);
                    jQuery(".sub_cat_loading_container").hide();
                }
            });

            $('.jcarousel').jcarousel('scroll', '+=1');
        });
});
</script>
    </script>
    
    <script>

        $(document).ready(function() { 
            var currentRequest = null;
            $( "#cat_sch" ).keyup(function() {
                

                var searchQuery = $(this).val();
                if(searchQuery != ""){
                    currentRequest = jQuery.ajax({
                        type: "POST",
                        url: '<?php echo base_url();?>product/searchCategory', 
                        data: "data="+searchQuery, 
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
                            console.log(obj.length);
                            if((obj.length)>0){
                                jQuery.each(obj,function(){
                                    html += '<li>'+$(this)[0].name+'</li>' 
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
        });


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
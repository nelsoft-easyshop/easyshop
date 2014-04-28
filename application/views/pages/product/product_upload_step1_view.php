<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css" rel="stylesheet" />
 
<?php 
$attributesForm = array('id' => 'draft_form',
    'name'=>'draft_form');
echo form_open('sell/edit/step2', $attributesForm);
?>
<?php echo form_close(); ?>

  

<div class="wrapper"> 
    <input type="hidden" id="edit_cat_tree" value='<?php echo isset($cat_tree_edit)?$cat_tree_edit:json_encode(array()); ?>'/>
    <div class="clear"></div>

    <div class="seller_product_content">
      
        <?php if(isset($product_id_edit)){
                  echo form_open('sell/edit/step2');
                  echo '<input type="hidden" id="p_id" name="p_id" value="'.$product_id_edit.'">';
              }
              else{
                  echo form_open('sell/step2');
                  $x=(isset($step2_content))?$step2_content:json_encode(array());
                  echo "<input type='hidden' name='step1_content' id='step1_content' value='".$x."'/>";
              }
        ?>

        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
			<input type="hidden" id="uploadstep1_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
            <div class="sell_steps sell_steps1">
                <ul>
                    <li><a href="javascript:void(0)" id="step1_link"><span>Step 1:</span> Select Category</a></li>
                  <li><a href="javascript:void(0)">Step 2: Upload Item</a></li>                   
                  <li><a href="javascript:void(0)">Step 3: Select Shipping Courier</a></li>
                  <li><a href="javascript:void(0)">Step 4: Success</a></li>
                </ul>
            </div>
            
            <div class="clear"></div>
            <div class="cat_sch_container">
 
            <b>Search for category: &nbsp;</b><input type="text" class="box" id="cat_sch" autocomplete="off"><div class="cat_sch_loading"></div>
            <div id="cat_search_drop_content" class="cat_sch_drop_content"></div>
            <?php if(!isset($product_id_edit)): ?>
                <div class="draft_txt">
                   <a href="javascript:void(0);" class="show_draft_link blue">View your draft items.</a>
                  
                   <span class='draft-cnt'>(<?php echo count($draftItems) ?>)</span> Item(s)
                </div>
            <?php endif; ?>
       </div>
     <?php if(!isset($product_id_edit)): ?>
     <div class="div_draft simplemodal-container">
        <h3>Draft Item(s) </h3>
        <div class="draft_items_container">
          <?php
          if(count($draftItems) <= 0){
            echo 'No item in your Draft!';
          }
          ?>
            <?php 
             foreach ($draftItems as $draft) {
                  ?>
                <div class="div_item draftitem<?php echo $draft['id_product']; ?>" >
                    <div class="draft_title">
                        <a class="draft_name" href="javascript:void(0)" data-pid="<?php echo $draft['id_product'] ?>">
                        <?php
                        if($draft['name'] != ""){
                            echo  $draft['name'] ;
                        }else{
                            echo '(Untitled Draft)';
                        }
                        ?> 
                        </a>
                    </div>
                    <div class="draft_category"><?php echo $draft['crumbs']?></div>
                    <div class="draft_down"> <span class="span_bg draft_del"></span> <a style="color:#f00;font-size:11px;" class="draft_remove" data-pid="<?php echo $draft['id_product'] ?>" href="javascript:{}">Delete</a>  | <span style="font-style:italic;font-size:10px"><?php echo date("M-d", strtotime($draft['lastmodifieddate']))?></span></div>
                    <div class="clear"></div>
                </div>
                  <?php } ?>
        </div>
    </div>
      <?php endif; ?>
       <div class="add_product_category">
        <div class="main_product_category">
            <input type="text" class="box" id="box">
            <ul class="navList" style="list-style-type:none">  
                <?php
                        foreach ($firstlevel as $row) { # generate all parent category.
                            ?>


                            <li class="<?php echo $row['parent_id']; ?>"><a href="javascript:void(0)" data="{cat_id:'<?php echo $row['id_cat']; ?>',level:'<?php echo '0' ?>',name:'<?php echo addslashes ($row['name']); ?>'}" class="select"><?php echo $row['name']; ?></a></li>

                            <?php } ?>
                            <li  class="othercategory_main"><a href="javascript:void(0)" class="select2" data-level="0" data-parent="1" data-parentname="" data-final="true" style="color:#0191C8 !important;"><b class="add_cat span_bg"></b><b>Add a Category</b></a></li>

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
                        <a href="javascript:void(0)" class="jcarousel-control-prev inactive">&lsaquo;</a>
                        <a href="javascript:void(0)" class="jcarousel-control-next inactive">&rsaquo;</a>



                    </div>
                </div>

                <div class="clear"></div>
                
                <div class="add_category_submit"></div>
                
            </div>
            <?php echo form_close();?>
        </div>

        <div class="clear"></div>  


        <style type="text/css">
        /* Overlay */
        #simplemodal-overlay {
            background-color:#bcbcbc;
        }

        /* Container */
        #simplemodal-container {
            height: auto !important;
            width: auto !important; 
            background-color:#0000;
            padding: 5px;
        }
        </style>

        <script>
        $(document).ready(function() { 
            var draftCount = "<?php echo (isset($draftItems))?count($draftItems):'0';?>";

            $('.div_draft').hide();

            $(document).on('click','.draft_name',function () {
                var pid = $(this).data('pid');
           
                var input = $("<input>")
                .attr("type", "hidden")
                .attr("name", "p_id").val(pid);
                $('#draft_form').append($(input));
                $('#draft_form').submit();

            });     
            $(document).on('click','.draft_remove',function () {
                var pid = $(this).data('pid');
                var csrftoken = $('input[name="es_csrf_token"]').val();
                var action = "sell/draft/remove"; 
 
                if(confirm("Are you sure you want to permanently removed your draft item?")){
                    var request = $.ajax({
                        async: false,
                        type: "POST",
                        url: '<?php echo base_url(); ?>' + action,
                        data: "p_id=" + pid + "&es_csrf_token=" + csrftoken,
                        dataType: "json",
                        cache: false,
                        success: function(d) { 
                            if(d.e == 1){
                                $('.simplemodal-container > .draftitem'+pid).remove();
                                draftCount -= 1;
                                 $.modal.close();
                                $('.draft-cnt').html(draftCount);
                                if(draftCount <= 0){

                                $('.div_draft').html('No item in your Draft!');  
                                }
                            }else{
                                alert(d.m);
                            }
                        }
                    });
                }
            });
            $(document).on('click','.show_draft_link',function () {
               $('.div_draft').modal({
                    escClose: false,
                    containerCss:{
                        maxWidth: 900,
                        minWidth: 605,
                        maxHeight: 600,
                    },
                    persist: true
                });
               $('#simplemodal-container').addClass('draft_container');
            });

            $('.jcarousel').bind("scroll", ScrollOnLoad);
            setTimeout(UnbindScroll, 150); 
            function ScrollOnLoad() {
                UnbindScroll();
                $('.jcarousel').scrollLeft(0);
            }

            function UnbindScroll() {
                $('.jcarousel').unbind("scroll", ScrollOnLoad);
            }

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


            $('.jcarousel-control-prev , .jcarousel-control-next').addClass('inactive'); 
            maxscroll = 0;
            focuslevel = 3; 
            cLevel = 0;
            $(".jcarousel").animate({scrollLeft: 0}, 0);

            $(".product_sub_category .product_sub_items0").nextAll().remove();
            $(".product_sub_category .product_sub_items0").remove();

            if($('#storeValue .parent'+catId).length == 0) {
                $.ajax({
                    onLoading:jQuery(".sub_cat_loading_container").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                    async: false,
                    type: "POST",
                    url: '<?php echo base_url(); ?>' + action,
                    data: "cat_id=" + catId + "&level=" + level + "&name=" + name + "&es_csrf_token=" + csrftoken,
                    dataType: "json",
                    cache: false,
                    
                    success: function(d) {
                        $(".product_sub_category").append(d);
                        $("#storeValue").append(d);

                        jQuery(".sub_cat_loading_container").fadeOut(600);
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
                        $(".sub_cat_loading_container").fadeOut(600);
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

        
        if(nlevel > 2){  
            if(nlevel == focuslevel){
                $('.jcarousel-control-prev').removeClass('inactive');
                var leftPos = $('.jcarousel').scrollLeft();
                // $('.jcarousel').scrollLeft(leftPos + 200);
            $(".jcarousel").animate({scrollLeft: leftPos + 200}, 400);
                maxscroll = leftPos;
                focuslevel = focuslevel + 1;
            }   
            cLevel = nlevel;
        }
 
        });

        var focuslevel = 3;
        var maxscroll = 0;
        var cLevel = 0;

        $(document).on('click','.jcarousel-control-prev',function () {

            var leftPos = $('.jcarousel').scrollLeft();
            // $('.jcarousel').scrollLeft(leftPos - 200);
            $(".jcarousel").animate({scrollLeft: leftPos - 200}, 400);
            if(focuslevel > 3){
                focuslevel = focuslevel - 1;
                 $('.jcarousel-control-next').removeClass('inactive');
            }

            if(focuslevel == 3){
                $('.jcarousel-control-prev').addClass('inactive');
            }
        });

        $(document).on('click','.jcarousel-control-next',function () {
            $('.jcarousel-control-prev').removeClass('inactive');
            var leftPos = $('.jcarousel').scrollLeft();
            if(leftPos <= maxscroll){
                // $('.jcarousel').scrollLeft(leftPos + 200);
            $(".jcarousel").animate({scrollLeft: leftPos + 200}, 400);
                focuslevel = focuslevel + 1;
            }
            if(leftPos == maxscroll){
                 $(this).addClass('inactive');
            }

        });

        $(document).on('click','.othercategory_main a', function(){

             $(".select ").removeClass('active');
            $('.othercategory_main').empty();
            $(".product_sub_category .product_sub_items0").nextAll().remove();
            $(".product_sub_category .product_sub_items0").remove();
            $('.othercategory_main').append('<input type="text" id="otherNameCategory_main" class="otherNameCategory_main" data-parentname="" data-level="" data-final="" autocomplete="off" name="othernamecategory" />');
            $('.othercategory_main .otherNameCategory_main').focus();
            $(".add_category_submit").empty();
        });
        $(document).on('blur change','#otherNameCategory_main',function () {
            
            var otherName = $(this).val();
            var finalValue = true;
            
            globalParent = 1;
            $(".add_category_submit").empty();
            if(otherName.length == 0){ 

                $('.product_sub_items0 .othercategory').empty();
                if(finalValue == true){ 
                  $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+parentName+'">');    
                }

            }else{  
                $(".add_category_submit").append('<input type="hidden" name="hidden_attribute" value="'+globalParent+'" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed with '+otherName.replace(/'/g, "\\'")+'">');    
 
           }

        });

        $(document).on('click','.othercategory a',function () {
            var selfAttrParent = $(this).data('parent');
            var selfLevel = $(this).data('level'); 
            var finalValue = $(this).data('final');
            var parentName = $(this).data('parentname');
            globalParent = selfAttrParent;
            globalLevel = selfLevel;


             $(".product_sub_items"+selfLevel+" .select2 ").removeClass('active');

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
                    type: "GET",
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
    
    
    // Edit: Step 1, highlight product category tree
    $(document).ready(function() {
        var obj = JSON.parse($('#edit_cat_tree').val());
        if(obj.length > 0){
            $('li .select').each(function(){
                var D = eval('(' + $(this).attr('data') + ')');
                if( parseInt(D.cat_id,10) === parseInt(obj[0].id_cat,10)){
                    $(this).click();
                    scrollToElement(this, '.main_product_category');
                    return false;
                }
            });
            var cnt = 0;
            $.each(obj, function(){
                var new_obj  = obj.shift();
                $('li.'+ new_obj.id_cat  +' .select2.child').each(function(){
                    var D = eval('(' + $(this).attr('data') + ')');
                    if(typeof obj[0] !== 'undefined'){
                        if( parseInt(D.cat_id) === parseInt(obj[0].id_cat,10)){
                            $(this).click(); 
                            scrollToElement(this, '.product_sub_items' +cnt);
                            cnt++;
                            return false;
                        }
                    }
                });
            });
        }
    });

  
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

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.simplemodal.js'></script>
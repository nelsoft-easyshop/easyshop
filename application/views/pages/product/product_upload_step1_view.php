<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link type="text/css" href="/assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
    <link type="text/css" href="/assets/css/vendor/bower_components/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
    <link type="text/css" href="/assets/css/bootstrap-mods.css"  rel="stylesheet"  media="screen"/>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.upload-step1.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>

<style type="text/css">
  /* Overlay */
  #simplemodal-overlay {
      background-color:#bcbcbc;
  }

</style>

<?php 
    $attributesForm = array('id' => 'draft_form','name'=>'draft_form');
    echo form_open('sell/edit/step2', $attributesForm);
    echo form_close();
?>

<div class="container"> 
    <input type="hidden" id="edit_cat_tree" value='<?php echo isset($cat_tree_edit)? html_escape($cat_tree_edit):json_encode(array()); ?>'/>

    <div class="clear"></div>

    <div class="seller_product_content">      
        <?php 
            if(isset($product_id_edit)):
                echo form_open('sell/edit/step2');
                echo '<input type="hidden" id="p_id" name="p_id" value="'.$product_id_edit.'">';
            else:
                echo form_open('sell/step2');
                $x=(isset($step2_content))?$step2_content:json_encode(array());
                echo "<input type='hidden' name='step1_content' id='step1_content' value='".$x."'/>";
            endif;
        ?>
        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
            <div class="sell_steps sell_steps1">
                <ul> 
                    <li>
                        <a href="javascript:void(0)" class="steps_link">
                            <span class="span_bg left-arrow-shape ar_active"></span>
                            <span class="steps_txt_active"><span class="f18">Step 1 : </span> Select Category</span>
                            <span class="span_bg right-arrow-shape ar_r_active"></span>
                        </a>
                    </li>
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">Step 2 : Upload Item</span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>                   
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">Step 3 : Shipping Location</span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">Success</span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>
                </ul>
            </div>

            <div class="clear"></div>

            <div class="cat_sch_container text-center">
                <div class="form-group">
                    <div class="input-group col-xs-12 col-sm-6 col-md-6 mrgin-deflt">
                        <div class="input-group-addon"><label for="cat_sch"><span class="span_bg icon_srch"></span></label></div>
                        <input type="text" class="box form-control width-50p" id="cat_sch" autocomplete="off" placeholder="Search for category">
                    </div>
                </div>
            </div>
                <!-- <div class="cat_sch_loading"></div> -->
                <div id="cat_search_drop_content" class="cat_sch_drop_content"></div>

            <div class="clear"></div>
            <!-- NEW START HERE -->
            <div class="clear"></div>  
            <div class="add_product_category width-100p">
                <div class="border-all of-hidden">
                    <div id="category_left_display" class="width-30p">
                        <div id="cl_div_container">
                            <div id="first_text" class="border-rad-tl-bl-3 pd-13-12 selected_category">Select Category</div>
                        </div>
                    </div>
                    <div id="category_right_display" class="width-70p">
                        <div id="cr_div_container">
                            <div class="category_list_container" id="container_level01">
                                <?php foreach ($firstlevel as $row): ?>
                                    <div class="border-rad-3">
                                        <a href="javascript:void(0)" data-parentid="<?=$row['parent_id']; ?>" data-catid="<?=$row['id_cat']; ?>" data-level="0" data-name="<?=html_escape($row['name']); ?>"  class="category_link display-ib">
                                           <?=html_escape($row['name']); ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>

                                <div class="border-rad-3 add-cat-con bl">
                                    <a class="custom_category_link display-ib" data-level="0" data-catid="1">Add Category
                                        <span class="span_bg icon-add border-rad-90"></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>  
            <div class="add_category_submit"></div>  
            <!-- NEW ENDS HERE -->

        </div>
        <?php echo form_close();?>
    </div>
</div>
    <input type='hidden' class='draftCount' value="<?php echo (isset($draftItems))?count($draftItems):'0';?>"/>
    <input type='hidden' class='other_cat_name' value="<?php echo (isset($other_cat_name)?html_escape($other_cat_name):'') ?>"/>

    <div class="clear"></div>  
    <div id="storeValue" style="display:none"></div>

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type='text/javascript' src='/assets/js/src/productUpload_step1.js?ver=<?=ES_FILE_VERSION?>'></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.product_upload_step1_view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

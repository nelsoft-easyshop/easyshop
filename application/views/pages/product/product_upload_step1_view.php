<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
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

<div class="res_wrapper"> 
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
                        <span class="steps_txt_active">
                          <span class="f18">Step 1: </span> Select Category
                        </span>
                        <span class="span_bg right-arrow-shape ar_r_active"></span>
                      </a>
                    </li>
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">
                          Step 2: Upload Item
                        </span>
                         <span class="span_bg right-arrow-shape"></span>
                    </li>                   
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">
                          Step 3: Success
                        </span>
                         <span class="span_bg right-arrow-shape"></span>
                    </li>
                  </ul>
            </div>

            <div class="clear"></div>

            <div class="cat_sch_container">
                <b>Search for category: &nbsp;</b>
                <div class="display-ib">
                  <span class="span_bg icon-search icon-hide-show"></span><input type="text" class="box" id="cat_sch" autocomplete="off">
                </div>
                <div class="cat_sch_loading"></div>
                <div id="cat_search_drop_content" class="cat_sch_drop_content"></div>

                <?php if(!isset($product_id_edit)): ?>
                <div class="draft_txt">
                    <a href="javascript:void(0);" class="show_draft_link blue">View your draft items.</a>
                    <span class='draft-cnt'>(<?php echo count($draftItems) ?>)</span> Item(s)
                </div>
                <?php endif; ?>
            </div>

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
                                      <a href="javascript:void(0)" data-parentid="<?=$row['parent_id']; ?>" data-catid="<?=$row['id_cat']; ?>" data-level="0" data-name="<?=addslashes ($row['name']); ?>"  class="category_link display-ib pd-13-12">
                                         <?=$row['name']; ?>
                                      </a>
                                  </div>
                              <?php endforeach; ?>

                              <div class="border-rad-3 add-cat-con bl">
                                <a class="custom_category_link pd-13-12 display-ib" data-level="0" data-catid="1">Add Category
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
    <input type='hidden' class='draftCount' value="<?php echo (isset($draftItems))?count($draftItems):'0';?>"/>
    <input type='hidden' class='other_cat_name' value="<?php echo (isset($other_cat_name)?html_escape($other_cat_name):'') ?>"/>

    <div class="clear"></div>  
    <div id="storeValue" style="display:none"></div>


    <?php if(!isset($product_id_edit)): ?>
    <div class="div_draft simplemodal-container" style='display:none'>
        <h3>Draft Item(s) </h3>
        <div class="draft_items_container">
            <?=(count($draftItems)  <= 0) ? '<br/><strong>There are no items in your draft list.</strong><br/>' : '' ;?>
            <?php foreach ($draftItems as $draft): ?>
                <div class="div_item draftitem<?php echo $draft['id_product']; ?>" >
                    <div class="draft_title">
                        <a class="draft_name" href="javascript:void(0)" data-pid="<?php echo $draft['id_product'] ?>">
                        <?=($draft['name'] != "") ? html_escape($draft['name']) : '(Untitled Draft)'; ?>  
                        </a>
                    </div>
                    <div class="draft_category"><?=$draft['crumbs']?></div>
                    <div class="draft_down"> <span class="span_bg draft_del"></span> <a style="color:#f00;font-size:11px;" class="draft_remove" data-pid="<?php echo $draft['id_product'] ?>" href="javascript:{}">Delete</a>  | <span style="font-style:italic;font-size:10px"><?php echo date("M-d", strtotime($draft['lastmodifieddate']))?></span></div>
                    
                    <div class="clear"></div>
                
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
<script type='text/javascript' src='<?=base_url()?>assets/js/src/productUpload_step1.js?ver=<?=ES_FILE_VERSION?>'></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.simplemodal.js'></script>

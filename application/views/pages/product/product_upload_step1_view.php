<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css?ver=1.0" rel="stylesheet" />
 
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
            <div class="sell_steps sell_steps1">
                <ul>
                    <li><a href="javascript:void(0)" id="step1_link"><span>Step 1:</span> Select Category</a></li>
                  <li>Step 2: Upload Item</li>                   
                  <li>Step 3: Select Shipping Courier</li>
                  <li>Step 4: Success</li>
                </ul>
            </div>
            
            <div class="clear"></div>
            <div class="cat_sch_container">
 
            <b>Search for category: &nbsp;</b>
            <input type="text" class="box" id="cat_sch" autocomplete="off"><div class="cat_sch_loading"></div>
            <div id="cat_search_drop_content" class="cat_sch_drop_content"></div>
            <?php if(!isset($product_id_edit)): ?>
                <div class="draft_txt">
                   <a href="javascript:void(0);" class="show_draft_link blue">View your draft items.</a>
                  
                   <span class='draft-cnt'>(<?php echo count($draftItems) ?>)</span> Item(s)
                </div>
            <?php endif; ?>
       </div>
     <?php if(!isset($product_id_edit)): ?>
     <div class="div_draft simplemodal-container" style='display:none'>
        <h3>Draft Item(s) </h3>
        <div class="draft_items_container">
          <?php
          if(count($draftItems) <= 0){
           echo '<br/><strong>There are no items in your draft list.</strong><br/>';
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
                            echo  html_escape($draft['name']) ;
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
                            <li  class="othercategory_main otherNameCategory_main_li"><a href="javascript:void(0)" class="select2" data-level="0" data-parent="1" data-parentname="" data-final="true" style="color:#0191C8 !important;"><b class="add_cat span_bg"></b><b>Add a Category</b></a></li>

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
        <input type='hidden' class='draftCount' value="<?php echo (isset($draftItems))?count($draftItems):'0';?>"/>
        
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

<div id="storeValue" style="display:none">

</div>

<script type='text/javascript' src='<?=base_url()?>assets/js/src/productUpload_step1.js?ver=1.0'></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.simplemodal.js'></script>

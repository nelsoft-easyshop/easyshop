<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link type="text/css" href="<?=base_url()?>assets/css/product_upload_tutorial.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<!-- Start of simple slider-->
<!--<link rel="stylesheet" href="--><?//=base_url()?><!--assets/css/normalize.min.css" />-->
<link rel="stylesheet" href="<?=base_url()?>assets/css/ion.rangeSlider.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/css/ion.rangeSlider.skinFlat.css" />
<script src="<?=base_url()?>assets/js/src/vendor/ion.rangeSlider.min.js"></script>
<!--  END OF simple slider-->
<div class="res_wrapper">

  <div class="clear"></div>

  <div class="seller_product_content">

    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
      <div class="sell_steps sell_steps2">
        <ul> 
          <li class="steps_txt_hide">
            <a href="javascript:void(0)" class="steps_link">
              <span class="span_bg left-arrow-shape2"></span>
              <span class="steps_txt">
                Step 1: Select Category</span>
               <span class="span_bg right-arrow-shape"></span>
            </a>
          </li>
          <li><span class="span_bg left-arrow-shape ar_active"></span>
              <span class="steps_txt_active">
                <span class="f18">Step 2: </span> Upload Item
              </span>
              <span class="span_bg right-arrow-shape ar_r_active"></span></li>                   
          <li class="steps_txt_hide">
              <span class="span_bg left-arrow-shape2"></span>
              <span class="steps_txt">
                Step 3: Success
              </span>
               <span class="span_bg right-arrow-shape"></span>
          </li>
        </ul>
      </div>
      <input type="hidden" name="step1_content" id="step1_content" value='<?php echo isset($step1_content)?$step1_content:json_encode(array());?>'/>
      <?php if(isset($product_details)): ?>  
      <?php echo form_open('sell/step1', array('id'=>'edit_step1'));?>
      <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_details['id_product'];?>">
      <?php echo form_close();?>
    <?php else: ?>
    <?php echo form_open('sell/step1', array('id'=>'edit_step1'));?>
    <input type="hidden" name="c_id" id="c_id" value="<?php echo $id;?>">
    <input type="hidden" name="other_cat_name" id="other_cat_name" value="<?php echo $otherCategory;?>">
    <input type="hidden" name="step2_content" id="step2_content"/>
    <?php echo form_close();?>
  <?php endif; ?>

  <div class="clear"></div>

  <div class="upload_input_form form_input">
    <?php 
    $attr = array(
      'class' => 'form_product',
      'id' => 'form_product',
      'name' => 'form_product',
      'enctype' => 'multipart/form-data'
      );
    echo isset($is_edit)?form_open('sell/edit/processing2', $attr):form_open('sell/processing', $attr);
    ?>

    <input type="hidden" class="arrayNameOfFiles" name="arraynameoffiles"> 

  <div class="res_wrapper product_upload_step2">
      <div class="border-all fz-df mrgntop-10 mrgntop-10">
        <div class="upload_step2_title">
            <h3 class="pd-8-12">Categories where your listing will appear</h3>  
            <a class="tooltips" href="javascript:void(0)">
              <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
              <span class="1line_tooltip">Your item will be listed under this EasyShop category
              </span>
            </a> 
          </td>
        </div>
        <div class="pd-8-12">
          <div>
            <?php echo $parent_to_last; ?> <!-- will show the parent category until to the last category selected (bread crumbs) -->
            <br>
            <a href="javascript:void(0)" style="color:#0654BA;" class="step1_link">Change category</a>
          </div>
        </div>
      </div>
      <div class="border-all fz-df mrgntop-10">
        <div class="upload_step2_title pd-8-12">
            <h3>Describe your item</h3> <strong class="required">(*) required fields</strong> 
        </div>
        <div class="pd-8-12">
          <!-- Title of the product -->
          <div class="display-ib width-15p-10min pd-tb-8">
              Product Name: <font color="red">*</font>
          </div>
          <div class="display-ib width-100p-70max vrtcl-mid">
            <input class="width-30p-245min" type="text" maxlength="255" placeholder="Enter title" autocomplete="off" id="prod_title" maxlength="255" name="prod_title" value="<?php echo (isset($product_details['name']))?$product_details['name']:'';?>">
            <a  class="tooltips" href="javascript:void(0)">
              <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
              <span class="lines_tooltip">Give your listing a descriptive title. Include necessary information so that other users may easily find your listing.
              </span>
            </a> 
          </div>
        </div>
    
        <!-- Start Condition of the product -->
        <div class="pd-8-12">
          <div>
            <div class="display-ib width-15p-10min pd-tb-8">
              Condition: <font color="red">*</font>
            </div>
            <div class="display-ib width-100p-70max">
              <select name="prod_condition" id="prod_condition" class="width-251min">
                <option value="0">--Select Condition--</option>          
                <?php foreach($this->lang->line('product_condition') as $x): ?>
                <option value="<?php echo $x;?>" <?php if(isset($product_details['condition'])){echo ($product_details['condition'])===$x?'selected':'';}?>><?php echo $x; ?></option>
              <?php endforeach; ?>
            </select>
            <a class="tooltips" href="javascript:void(0)">
              <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
              <span class="lines_tooltip">Identify the condition of your item so potential buyers may know what they should be getting.</span>
            </a>
            </div>
          </div>
        </div>
      </div>
      <!-- end Condition of the product -->

      <!-- Upload Image Content -->
      <div class="border-all fz-df mrgntop-10">
        <div class="upload_step2_title pd-8-12"> 
          <h3>Add Photos</h3> <span class="required">You are required to have a minimum of 1 photo</span>
          <a class="tooltips" href="javascript:void(0)">
           <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
            <span class="twolines_tooltip">Upload images for your listing. We recommend that you keep the image resolution within <?php echo $img_max_dimension[0].'x'.$img_max_dimension[1]; ?> for best quality of your images
            </span>
          </a>
        </div>
 
        <div class="pd-8-12 display-ib">
          <div class="inputfiles"> 
            <span class="labelfiles">
              <span class="add_photo span_bg"></span>Browse Photo
            </span>
            <br />
            <span class="label_bttm_txt">You may select multiple images</span>
          </div> 
          <!-- this output will show all selected from input file field from above. this is multiple upload. -->
          <output id="list">

            <!-- IF EDIT FUNCTION -->
            <?php $main_img_cnt = 0;?>
            <?php if(isset($main_images)):?> 
            <?php foreach($main_images as $main_image): ?>      

            <div id="previewList<?php echo $main_img_cnt; ?>" class="edit_img upload_img_div <?php echo ($main_img_cnt===0)?'active_img':'';?>">
              <span class="upload_img_con">
                <img src="<?php echo base_url().$main_image['path'].'categoryview/'.$main_image['file'];?>" alt="<?php echo base_url().$main_image['path'].'categoryview/'.$main_image['file'];?> ">
              </span>
              <a href="javascript:void(0)" class="removepic" data-number="<?php echo $main_img_cnt; ?>"  data-imgid="<?php echo $main_image['id_product_image'];?>">x</a>
              <br>
              <a href="javascript:void(0)" class="makeprimary photoprimary<?php echo $main_img_cnt; ?>" data-number="<?php echo $main_img_cnt; ?>" data-imgid="<?php echo $main_image['id_product_image'];?>"><?php echo ($main_img_cnt===0)?'Your Primary':'Make Primary';?></a>
            </div>

            <?php $main_img_cnt++; ?>
            <?php endforeach; ?>
            <?php endif; ?>
          </output>
        <!-- end of output -->
        </div>
      </div>
      <!-- end of upload image -->

      <!--Start of Description -->
      <div class="border-all fz-df mrgntop-10">
        <div class="upload_step2_title pd-8-12">
          <h3>Add a description</h3> <strong class="required">(*) required fields</strong> 
        </div>
      
        <div class="pd-8-12">
          <div class="display-ib width-15p-10min vrtcl-top pd-tb-8">
              Product Details: <font color="red">*</font>
          </div>
          <!-- Main Description of the product --> 
          <div class="display-ib width-100p-84max">
            <textarea style="width: 100%;height:100%" name="prod_description" class="mceEditor"  id="prod_description" placeholder="Enter description..."><?php echo (isset($product_details['description']))?$product_details['description']:'';?></textarea>
          </div>
        </div>
        <!-- end of Description -->
        <!-- start of keywords -->
        <div class="pd-8-12">
          <div class="display-ib width-15p-10min pd-tb-8">Additional Keywords
            <br>
            <span class="f11">(Seperated by spaces.)</span>
          </div>
          <div class="display-ib width-100p-70max vrtcl-mid">
            <input class="width-30p-245min" type="text" autocomplete="off" maxlength="150" name="prod_keyword" id="prod_keyword" placeholder="Enter keyword for you item" value="<?php echo (isset($product_details['keywords']))?$product_details['keywords']:'';?>">
            <a class="tooltips" href="javascript:void(0)">
              <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
              <span>Provide meaningful keywords for your listing so that other users may search for it more easily.
              </span>
            </a>
            <br>
            <span class="countdown" style="color:gray;font-style:italic;font-size:12px"></span>
          </div>
        </div>
        <!-- end of keywords -->

        <div class="pd-8-12">
          <p class="view_more_product_details blue"><span class="span_bg vmd_img"></span>View more product details</p>
        </div>
        <div class="more_product_details_container">
        <div class="step4_2">

        <!-- Start hide of more product details -->
        <div class="pd-8-12">
        <!-- Add item specifics -->
        <div class="pd-8-12">
            <div class="display-ib pd-tb-8">
              <h3 class="display-in"> Add item specifics</h3>  
              <a id="tutSpec" class="tooltips" href="javascript:void(0)" style='text-decoration:underline'>
               <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">  
               What's this?
               <span>Click here to read more. Your progress will not be lost. </span>
             </a>
           </div>
        </div>

        <div class="pd-tb-8">
            <!-- Brief of the product -->
            <div class="display-ib width-15p-10min pd-tb-8">Brief description:</div>
            <div class="display-ib width-100p-70max">
                <input class="width-30p-245min" type="text" autocomplete="off" maxlength="255" placeholder="Enter brief description" id="prod_brief_desc" name="prod_brief_desc"  value="<?php echo (isset($product_details['brief']))?$product_details['brief']:'';?>">
                <a class="tooltips" href="javascript:void(0)">
                    <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
                    <span>Describe your item in a brief but precise way.</span>
                </a>
            </div>
        </div>
        <!-- start of brand -->
        <div class="pd-tb-8">
            <div class="display-ib width-15p-10min pd-tb-8">Brand:</div> 
            <div class="display-ib width-100p-70max vrtcl-mid">
                <input type = "hidden" id="prod_brand" name="prod_brand" value="<?php echo isset($product_details['brand_id'])?$product_details['brand_id']:0?>"/>
                <input class="width-30p-245min" type = "text" id="brand_sch" name="brand_sch" autocomplete="off" placeholder="Search for your brand" value="<?php echo isset($product_details['brandname'])?$product_details['brandname']:''?>"/>
                <div class="brand_sch_loading"></div>
                <div id="brand_search_drop_content" class="brand_sch_drop_content"></div>
            </div>
        </div>
        <!-- end of brand -->
        <!-- start of sku code -->
        <div class="pd-tb-8">
            <div class="display-ib width-15p-10min pd-tb-8">SKU Code: </div> <!-- SKU of the product -->
            <div class="display-ib width-100p-70max">
                <input class="width-30p-245min" type="text" autocomplete="off"  maxlength="45" placeholder="Enter SKU" id="prod_sku" name="prod_sku" value="<?php echo (isset($product_details['sku']))?$product_details['sku']:'';?>">
                <a class="tooltips" href="javascript:void(0)"><img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
                    <span class="2lines_tooltip">Stock Keeping Unit: you can assign any code in order to keep track of your items</span>
                </a>
            </div>
        </div>
        <!-- end of sku code -->
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

      <div class="pd-tb-8">
        <div class="display-ib width-15p-10min pd-tb-8"><?php echo  ucwords(strtolower($attribute[$i]['cat_name'])); ?></div>
        <div class="display-ib width-100p-70max">

          <?php 
            if(isset($product_attributes_spe[$input_id_attr]))
             $cat_attr = $product_attributes_spe[$input_id_attr];
            else
             $cat_attr = array();   
                                  #Removed case formatting for input type values
            switch ($input_type) {
            case 'SELECT':
            echo '<span><select class="width-251min" name="'.$input_type.'_'.$input_cat_name.'">';
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

            case 'TEXT':
            $value = (isset($cat_attr[0]))?$cat_attr[0]['value']:'';
            echo "<input type='text' class='width-22min' autocomplete='off' value='".$value."' name='".$input_type.'_'.$input_cat_name."' />";
            break;

            case 'TEXTAREA':
            $value = (isset($cat_attr[0]))?$cat_attr[0]['value']:'';
                                        // echo "<span><textarea style='height:141px' cols=98 rows='98' name='".$input_type.'_'.$input_cat_name."'>".$value."</textarea></span>";
            echo "<textarea name='".$input_type.'_'.$input_cat_name."' style='width: 100%;height:100%' class='mceEditor_attr' >".$value."</textarea></span>";
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
              echo "<span class='display-ib width-15p-10min pd-tb-8'><input type='radio' value='".$list['name']."' name='".$input_type.'_'.$input_cat_name."' ".$checked.">".$list['name']."</span>";              
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
              echo "<span class='display-ib width-15p-10min pd-tb-8'><input type='checkbox' class='checkbox_itemattr' data-group='".$input_cat_name_with_space."'   data-attrid='". $id_attribute_value."' data-value='".$list['name']."' data-attr='".$input_type.'_'.$input_cat_name.'_'.str_replace(' ', '', $list['name'])."' value='".$list['name']."' name='".$input_type.'_'.$input_cat_name."[]' ".$checked.">".$list['name']."</span>";
            }
            break;
            }
          ?>
        </div>
      </div>
      <?php
        $array_name_inputs = $array_name_inputs .'|'. $input_type.'_'.$input_cat_name.'/'.$input_id_attr;
        }
      ?>
      <div class="pd-tb-8">
          <div class="display-ib">
              <h3> Additional Information for your Item</h3> 
              <br>
              Use this to add your own details to your listing.
              <a id="tutOptional" class="tooltips" href="javascript:void(0)" style='text-decoration:underline'>
                  <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt=""> 
                  What's this?
                  <span>Click here to read more. Your progress will not be lost. </span>
              </a>
          </div>
      </div>


<!-- BEGIN ADDING PREVIOUS OPTIONAL ATTRIBUTES (EDIT FEATURE) -->
<?php 
$j = 1;
if(!isset($product_attributes_opt)){
  $product_attributes_opt = array();
}            

foreach($product_attributes_opt as $key=>$opt_attr): ?>

<div class="pd-tb-8 main<?php echo $j; ?>" >
  <div class="display-ib width-15p-10min"> <?php echo ($j===1)?'Others: (Optional)':''; ?></div>  
  <div class="display-ib option_image_td vrtcl-top">
    <input type="text" name="prod_other_name[]" data-cnt="<?php echo $j; ?>" class="width-30p-245min prod_<?php echo $j;?> other_name_class" autocomplete="off" placeholder="Enter name" value="<?php echo str_replace("'", '', $key);?>"> 
    <?php if($j === 1): ?>
      <a href="javascript:void(0)" class="lnkClearFirst">Clear This Group</a>
    <?php else: ?>
      <a class="removeOptionGroup" data-cnt="<?php echo $j?>" href="javascript:void(0)">Remove This Group</a>
    <?php endif; ?>
  </div>
</div>

<?php $k = 0;
foreach($opt_attr as $prod_attr): ?>
<div class='pd-tb-8 main<?php echo $j; ?> main<?php echo $j; ?>_2nd<?php echo ($k===0)?'':'_add';?>'>
  <div class="display-ib width-15p-10min hide_space"></div> 
  <?php if($k > 0):?>
  <div style="display:none">
    <span>
      <input class="width-30p-245min prod_<?php echo $j;?>" type="text" value ="<?php echo str_replace("'", '',$key);?>" data-cnt="<?php echo $j;?>" name="prod_other_name[]">
    </span>
  </div>
  <?php endif; ?>

  <div class="display-ib">
    <input type="text"  class="width-30p-245min other_name_value otherNameValue<?php echo $j?>" data-cnt="<?php echo $j;?>" name="prod_other[]" autocomplete="off" data-otherid="<?php echo $prod_attr['value_id'];?>" placeholder="Enter description" value="<?php echo $prod_attr['value']?>">
  </div>
  <div style="display:none">
    <input type="text" name="prod_other_id[]" value="<?php echo $prod_attr['value_id']; ?>">
  </div>

    <div class="display-ib">
      &#8369; <input class="width-30p-245min" type="text" name="prod_other_price[]" autocomplete="off" id="price_field"   onkeypress="return isNumberKey(event)"  placeholder="Enter additional price (0.00)" value="<?php echo number_format($prod_attr['price'],2,'.',',');?>">
    </div>

  <div class="display-ib option_image_td vrtcl-top">
    <div>
      <input class="option_image_input vrtcl-mid" type="file" name="prod_other_img[]" accept="image/*">
      <input type="hidden" name="prod_other_img_idx[]">                       
      <?php if((trim($prod_attr['img_path'])!=='')&&(trim($prod_attr['img_file'])!=='')):?>
      <span class='option_image_container'> 
        <span class='option_image_span'>
          <img src="<?php echo base_url().$prod_attr['img_path'].'thumbnail/'.$prod_attr['img_file']; ?>" class="option_image">
        </span>
      </span>
      
      <span class='removeOptionValue remove_option_short' data-cnt="<?php echo $j;?>"><img src="<?=base_url()?>assets/images/icon-remove.png" alt="Remove"></span>
      <?php else: ?>
        <a href="javascript:void(0)" class="removeOptionValue remove_option_long" data-cnt="<?php echo $j;?>"><img src="<?=base_url()?>assets/images/icon-remove.png" alt="Remove"></a>
      <?php endif;?>
    </div>
  </div>
</div>
<?php $k++; endforeach; ?>

<div id="main<?php echo $j;?>" class='<?php echo ($j===0)?'':'main'.$j.'_link'?> pd-tb-8'  >
  <div class="display-ib width-15p-10min pd-tb-8 hide_space"></div>
  <div class="display-ib">
    <a class="add_more_link_value" data-value="<?php echo $j;?>" href="javascript:void(0)">+Add more value</a>
  </div>
</div>     
<?php $j++; endforeach;?>

<!-- END PREVIOUS OPTIONAL ATTRIBUTES (EDIT FEATURE) -->


<?php if($j===1):?>
  <div class="row1 main1 pd-tb-8">
    <div class="display-ib width-15p-10min pd-tb-8 vrtcl-top"> Others: (Optional) </div> 
    <div class="others_content">
      <input  type="text" name="prod_other_name[]" data-cnt="<?php echo $j;?>" class="<?php echo 'prod_'.$j;?> other_name_class width-30p-245min" autocomplete="off" placeholder="Item property title"> 
      <a href="javascript:void(0)" class="lnkClearFirst">Clear This Group</a>
      <span class="f11 display-bl">For Example: Color, Brand and Year</span>
    
      <div class="main1 main1_2nd pd-tb-8 other_value pos-rel">
        <div class="display-ib">
          <input  type="text" name="prod_other[]"  class="other_name_value otherNameValue1 width-30p-245min"  autocomplete="off" data-cnt="<?php echo $j;?>" placeholder="Item property value ">
          <span class="f11 display-bl">For Example: Blue, SKK and 2013</span>
          <div style="display:none">
            <input type="text" name="prod_other_id[]" value="">
          </div>
        </div>
        <div class="display-ib vrtcl-top">
          <div class="<?php echo 'h_if_'.$j;?> hdv">
            &#8369; <input class="width-30p-245min price_text" type="text" name="prod_other_price[]"   onkeypress="return isNumberKey(event)" autocomplete="off" id="price_field" placeholder="Enter additional price (0.00)">
          </div>
        </div>
        <div class="display-ib option_image_td vrtcl-top">
          <div class="<?php echo 'h_if_'.$j;?> hdv">
            <input type="file" class='option_image_input vrtcl-mid' name="prod_other_img[]"  accept="image/*">
            <input type="hidden" name="prod_other_img_idx[]">
            <a href="javascript:void(0)" class="removeOptionValue remove_option_long" data-cnt ="<?php echo $j;?>"><img src="<?=base_url()?>assets/images/icon-remove.png" alt="Remove"></a>
          </div>
        </div> 
      </div>
    </div>
  </div>
   
  <div class="pd-tb-8" id="main1">
    <div class="display-ib width-15p-10min pd-tb-8 hide_space"></div>
    <div class="display-ib">
      <a class="add_more_link_value" data-value="<?php echo $j;?>" href="javascript:void(0)">+Add more value</a>
    </div>
  </div>
<?php endif; ?>

      </div>
        

      </div>
      <div class="pd-8-12">
          <a class="add_more_link" href="javascript:void(0)">+ Add More Optional</a>

          <?php if(isset($product_details)): ?>
            <input type="hidden" name="p_id" value="<?php echo $product_details['id_product']; ?>">
          <?php endif;?>
        </div>
      

  </div>
</div>
<!-- End hide of more product details -->

  <!-- Start of Price Content -->
  <div class="border-all fz-df mrgntop-10">
      <div class="upload_step2_title pd-8-12">
          <h3>Price and Quantity</h3> <strong class="required">(*) required fields</strong>
      </div> 
      
      <div class="pd-8-12">
        <div>
          <div class="display-ib width-15p-10min pd-tb-8">Base Price <font color="red"> *</font></div>
          <div class="display-ib width-100p-70max vrtcl-mid">
                &#8369; <input type="text" autocomplete="off" onkeypress="return isNumberKey(event)"  name="prod_price" id="prod_price" placeholder="Enter price (0.00)" value="<?php echo (isset($product_details['price']))?number_format($product_details['price'],2,'.',','):'';?>">

                <a class="tooltips" href="javascript:void(0)">
                  <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">
                  <span>Set the base price for your listing. You may set the shipment fee separately in the following step.
                  </span>
                </a>

                <br><a id="discnt_btn" class="blue">Add discount price</a>
                <div class="discounted_price_container">
                  <strong>Discounted Price:</strong> &#8369;
                  <span id="discounted_price_con">0.00</span>
                </div>
                <div id="dsc_frm">
                  <nobr class="discount_price_con_top">
                    <label id="lbl_discount"><strong>Discount Percentage</strong></label><input type="text" id="slider_val" placeholder="0%" onkeypress="return isNumberKey(event)" data-value='<?php echo isset( $product_details['discount'])?$product_details['discount']:0;?>' >

                  </nobr>     
                  <input type="text" id="range_1" name="discount" value=""/>           
                  <nobr class="discount_price_con">
                    <label id="lbl_realPrc"><strong>Discounted Price:</strong> &#8369;</label><input type="text" id="discountedP" class="blue" name="discountedP" value="" onkeypress="return isNumberKey(event)" />
                  </nobr> 
                </div>
          </div>
        </div>
        <div>
             <div class="display-ib width-15p-10min pd-tb-8">
                  <h3 class="orange">Quantity</h3>
            </div>
            <div class="quantity_table display-ib width-100p-84max vrtcl-mid mrgntop-10">

              <div class="quantity_table_row">               
                <div class="qty_title">
                  <span>Quantity:</span><br />
                  <input type="text" class="qtyTextClass"  onkeypress="return isNumberKey(event)"  id="qtyTextClass" name="quantity"> 
                  <a href="javascript:void(0)" data-value="1" class="quantity_attr_done orange_btn3">Add</a>

                  <a id="tutQty" class="tooltips qty_tooltip" href="javascript:void(0)" style='text-decoration:underline'>
                   <img src="<?= base_url() ?>assets/images/icon_qmark.png" alt="">  
                   What's this?
                   <span>Click here to read more about setting the quantity options of your listing. Your progress will not be lost. </span>
                  </a>                
                </div>
                <div class="quantity_attrs_content" id="quantity_attrs_content2"></div>
              </div>
              <div class="clear"></div>
              <div class="combinationContainer border-top mrgntop-10"></div>
              <div class="clear"></div>
        </div>
      </div>

  </div>






<tfoot>





<!-- end of Price Content -->

    

 </div>  
 <div class="clear"></div>
 <div class="quantity_table2">
 </div> 
 <?php echo form_close();?>

 <?php 
 $attributesForm = array('id' => 'hidden_form','name'=>'hidden_form');
 echo form_open('sell/newstep3', $attributesForm);
 ?>
 <input type="hidden" name="prod_h_id" id="prod_h_id"> 

 <?php if(isset($is_edit)): ?>
 <input type="hidden" name="is_edit" value="true">
<?php endif; ?>

<?php echo form_close(); ?>

</div>

<div class="clear"></div>
<div class="quantity_table2">
</div>
</td>
</tr>
<tr>
  <td colspan="4">
    <div class="add_category_submit">
      <div class="button_div"><input class="proceed_form" id="proceed_form" type="button" value="Proceed"></div>
      <input type="hidden" id="qty_details" value='<?php echo (isset($item_quantity))?json_encode($item_quantity):json_encode(array());  ;?>'></input>
    </form>
    <div class="loader_div" style="display:none"><img src='/assets/images/orange_loader.gif'></div>              
    <div class="percentage">

    </div>
  </div>  
</td>
</tr>
<tr>
  <td>


  </td> 
</tr>
</tfoot>
</div> 



<div id="div_tutOptional" style="display:none;width:990px; height: 530px;">
  <div class="paging">
    <div class="p_title">
      <h2>Add your own specifications</h2>
    </div>
    <div class="p_content">
      <p class="h_strong">You may add your own item specifications to your listing. This is helpful if you have item details that are not present in our provided list.</p>
      <div><img src="/assets/images/tutorial/prd_upload_step2/optionals/1.png"></div>
      <p class="">You can enter as many fields as you need by clicking on <strong>+Add more value</strong></p>
    </div>
  </div>
  
  <div class="paging">
    <div class="p_title">
      <h2>Add your own specification</h2>
    </div>
    <div class="p_content">
     <p class="h_strong">You may assign images and additional prices to each of your new details. Take advantage and customize your item listing. </p>
     <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/optionals/2.png"></div>
     <br/>
   </div>
 </div>

 <div class="paging">
  <div class="p_title">
    <h2>Add your own specification</h2>
  </div>
  <div class="p_content">
    <p class="h_strong">Once successful, other users will be able to see the image and price associated with each detail when they select it. </p>
    <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/optionals/3.png" alt="With Attribute Combinations.png"></div>
    <br/>
  </div>
</div>

<div class="paging">
  <div class="p_title">
    <h2>Add your own specification</h2>
  </div>
  <div class="p_content">
    <p class="h_strong">Once successful, other users will be able to see the image and price associated with each detail when they select it. </p>
    <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/optionals/4.png" alt="With Attribute Combinations.png"></div>
    <br/>
  </div>
</div>


<div class="pagination p_cent" id="paging_tutOptional">
  <a href="#" class="first" data-action="first">&laquo;</a>
  <a href="#" class="previous" data-action="previous">&lsaquo;</a>
  <input type="text" readonly="readonly" data-max-page="4" />
  <a href="#" class="next" data-action="next">&rsaquo;</a>
  <a href="#" class="last" data-action="last">&raquo;</a>
</div>
</div>

<div id="div_tutQty" style="display:none;width:990px; height: 530px;">    
  <div class="paging">
    <div class="p_title">
      <h2>Set Availability Options</h2>
    </div>
    <div class="p_content">
      <p class="h_strong">You may set the availability setting for different product detail combinations. To do this, make sure that you have filled-in the <strong>'View more product details' </strong> section with your desired details.</p>
      <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/quantity/1.png"></div>
    </div>
  </div>

  <div class="paging">
    <div class="p_title">
      <h2>Set Availability Options</h2>
    </div>
    <div class="p_content">
      <p class="h_strong">All the details you have filled-up should be available in the <strong> 'quantity' </strong> section. From here you may come up with different combinations by playing around with the select boxes.</p>
      <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/quantity/2.png"></div>
    </div>
  </div>

  <div class="paging">
    <div class="p_title">
      <h2>Set Availability Options</h2>
    </div>
    <div class="p_content">
      <p class="h_strong">Add an availability value by clicking the <strong>Add</strong> button. You may add as many as you need but do note that you can only set the availability of each combination once. If you need to make changes, you can change the quantity text field for that combination.</p>
      <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/quantity/3.png" alt="With Attribute Combinations.png"></div>
    </div>
  </div>

  <div class="pagination p_cent" id="paging_tutQty">
    <a href="#" class="first" data-action="first">&laquo;</a>
    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
    <input type="text" readonly="readonly" data-max-page="3" />
    <a href="#" class="next" data-action="next">&rsaquo;</a>
    <a href="#" class="last" data-action="last">&raquo;</a>
  </div>
</div>

<div id="div_tutSpec" style="display:none; width:990px; height:530px;">

  <div class="paging">
    <div class="p_title">
      <h2>Adding item specifications</h2>
    </div>
    <div class="p_content">
      <p class="h_strong">Fill up the available text fields or select from the provided checkboxes from the list of attributes that we have provided. </p>
      <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/specifics/1.png"></div> 
    </div>
  </div>

  <div class="paging">
    <div class="p_title">
      <h2>Adding item specifications</h2>
    </div>
    <div class="p_content">
      <p class="h_strong">The fields you filled-up will appear in the listing page of your item. Other users may even select from the available details when they are looking to purchase your item.</p>
      <div><img src="<?=base_url()?>assets/images/tutorial/prd_upload_step2/specifics/2.png"></div>
      <p class=""></p>
    </div>
  </div>

  
  <div class="pagination p_cent" id="paging_tutSpec">
    <a href="#" class="first" data-action="first">&laquo;</a>
    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
    <input type="text" readonly="readonly" data-max-page="2" />
    <a href="#" class="next" data-action="next">&rsaquo;</a>
    <a href="#" class="last" data-action="last">&raquo;</a>
  </div>

</div>
<?php 
$attr = array(
  'class' => 'form_files',
  'id' => 'form_files',
  'name' => 'form_files',
  'enctype' => 'multipart/form-data'
  );
echo form_open('productUpload/uploadimage', $attr);
?>
<input type="hidden" class="counter" name="counter" >
<input type="hidden" class="arrayNameOfFiles" name="arraynameoffiles">
<input type="hidden" class="filescnttxt" name="filescnttxt">
<input type="hidden" class="afstart" id="afstart" name="afstart">
<div id="inputList" class="inputList">


</div>
</form> 

<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.numeric.js'></script>
<script type="text/javascript">
$('.view_more_product_details').on('click', function() {
  $('.more_product_details_container,.prod-details-add-more-link').slideToggle();
  $('.view_more_product_details').toggleClass('active-product-details');
});
</script>

<script type="text/javascript" src="<?=base_url()?>assets/js/src/productUpload_step2.js?ver=<?=ES_FILE_VERSION?>"></script> 
<script src="<?php echo base_url() ?>assets/tinymce/plugins/jbimages/js/jquery.form.js"></script>
<script type='text/javascript' src="<?=base_url()?>assets/js/src/vendor/jquery.simplemodal.js"></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.jqpagination.min.js'></script>
<script src="<?php echo base_url(); ?>assets/tinymce/tinymce.min.js" type="text/javascript"></script>
    <script type="text/javascript">



jQuery(function($){
  var confirm_unload = true;
  window.onbeforeunload = function (e) {
    if(confirm_unload){
      e = e || window.event;
      var str = 'Some of your data may be lost.'
          // For IE and Firefox prior to version 4
          if (e) {
            e.returnValue = str;
          }
          // For others
          return str;
        }
      };


    //store onbeforeunload for later use
    $(window).data('beforeunload',window.onbeforeunload);  

    $(document).on('mouseover mouseout','a[href="javascript:void(0)"]', function(event) {
      if (event.type == 'mouseover') {
        window.onbeforeunload=null;
      } else {
        window.onbeforeunload=$(window).data('beforeunload');
      }
    });
    
    $(document).on('click', '#proceed_form', function(event){
     window.onbeforeunload=null;
   });
    

  });


$(document).ready(function(){

  $("#range_1").ionRangeSlider({
    min: 0,
    max: 100,
    type: 'single',
    step: 1,
    postfix: "%",
    prettify: true,
    hasGrid: true,
        onChange: function (obj) {        // callback is called after slider load and update
          var value = obj.fromNumber;
          $("#slider_val").val(value);
          get_discPrice();
        }
      });


    $("#slider_val").bind('change keyup',function(e){
        if(e.which > 13 || e.which < 13){
            return false;
        }
        var thisslider = $(this);
        var newval = (parseFloat($(this).val()) > 100) ? 99 : (parseFloat($(this).val()) == 0 || isNaN(parseFloat($(this).val())))? 0 : parseFloat($(this).val());
        get_discPrice();
        $("#range_1").ionRangeSlider("update", {
            from: newval,                       // change default FROM setting
            onChange: function (obj) {        // callback is called after slider load and update
                var value = obj.fromNumber;
                thisslider.val(value);
                get_discPrice();
            }
        });
    });

  $("#discountedP").bind('change keyup',function(e){
      if(e.which > 13 || e.which < 13){
          return false;
      }
      validateWhiteTextBox("#discountedP");
      var disc_price = parseFloat($(this).val());
      var base_price = parseFloat($("#prod_price").val().replace(/,/g,''));
      var sum = ((base_price - disc_price) / base_price) * 100;
      sum = sum.toFixed(4);
      if(disc_price > base_price){
          alert("Discount Price cannot be greater than base price.");
          $(this).val("0.00");
          validateRedTextBox("#discountedP");
          return false;
      }
      if(disc_price <= 0){
          alert("Discount Price cannot be equal or less than 0.");
          $(this).val("0.00");
          $( "span#discounted_price_con" ).text( "0.00" );
          validateRedTextBox("#discountedP");
          return false;
      }
      $("#range_1").ionRangeSlider("update", {
          from: sum
      });
      $("#slider_val").val(sum+"%");
      tempval = Math.abs(disc_price);
      disc_price = ReplaceNumberWithCommas(tempval.toFixed(2));
      $(this).val(disc_price);
      $( "span#discounted_price_con" ).text( disc_price );
  });

  $('#prod_price').on('change', function(){
    var prcnt = parseFloat($("#slider_val").val().replace("%",''));
    if( !isNaN(prcnt) ){
     get_discPrice();
   }
 });


  $("#discnt_btn").on("click",function(){
    $("#dsc_frm").toggle();      
  });    

  var slider_val = parseFloat($('#slider_val').data('value')); 
  if(slider_val !== 0 && !isNaN(slider_val)){
	   $('#slider_val').val(slider_val); 
	   $('#slider_val').trigger( "change" );
   }


});




$(document).ready(function(){

  if(window.FileReader) {   
    badIE = false;
    $('#inputList').append('<input type="file" id="files" class="files active" name="files[]" multiple accept="image/*" required = "required"  /><br/><br/>');
  } else { 
    badIE = true;
    $('#inputList').append('<input type="file" id="files" class="files active" name="files[]" accept="image/*" required = "required"  /><br/><br/>');
  }

  var tempId = '<?= $tempId; ?>';
  var memberId = '<?= $memid; ?>';
  var fulldate = '<?=date("YmdGis");?>';
  var af = new Array(); 
  var badIE = config.badIE;
  var canProceed = true; 
  var removeThisPictures = [];
  var pictureCount = 0;
  var primaryPicture = 0;
  var qtycnt = 1;
  var countSelected = 1;
  var countSelected = 1;
  var arrayVar = [];
  var combination = []; 
  var arrayCombination = []; 
  arraySelected = {};  

  var g_input_name;
  var g_id;
  var g_combinationSelected;
  var g_description;
  var g_noCombination;
  var g_otherCategory;
  var g_removeThisPictures;
  var g_primaryPicture;
  var g_editRemoveThisPictures;
  var g_editPrimaryPicture; 
  var g_quantitySolo;
  
  var imageCustom = new Array();

  var editRemoveThisPictures = new Array();
  var editPrimaryPicture = 0;
  
  var cnt_o = <?php echo json_encode($j); ?>;
  //For edit: cnt_o is greater than 1 whenever an optional attribute is already present
  if(cnt_o > 1){
    cnt_o--;
  }

  $(".hdv").css("display", "none");
  $( ".loader_div" ).hide();
  $('.quantity_attr_done').hide();
  $('.qty_tooltip').hide();
  var noCombination = true;

    // remove optional

    $(document).on('click',".lnkClearFirst",function (){

      var cnt = 1;
      var formatHeadValue = $.trim($('.prod_'+cnt).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
      var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase();});  

      $('.row'+cnt).each(function(){
        var selfValue = $(this).find('.other_value > .display-ib > .otherNameValue'+cnt).val();
        var value = selfValue+headValue; 
        var idHtmlId = headValue.replace(/ /g,'')+'Combination';
        $("#"+idHtmlId+" option[data-temp='"+value+"']").remove(); 
        if($('#'+idHtmlId).has('option').length <= 0){
          $('#div'+idHtmlId).remove();
        }
      });

      resetFirstOptional(1);
      // resetFirstSecondRowOptional(1);

    });

    $(document).on('click',".removeOptionValue",function (){

        $('.combinationContainer').empty();
        noCombination = true;
        arraySelected = {}; 

        // REMOVE VALUE TO POSSIBLE COMBINATION
        var cnt = $(this).data('cnt'); 
        var formatHeadValue = $.trim($('.prod_'+cnt).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
        var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        }); 
        var rowDiv = $(this).closest('.other_value');
        var selfValue = $.trim(rowDiv.find('.otherNameValue'+cnt).val()); 
        var value = selfValue+headValue; 
        var idHtmlId = headValue.replace(/ /g,'')+'Combination';

        $("#"+idHtmlId+" option[data-temp='"+value+"']").remove(); 

        if($('#'+idHtmlId).has('option').length <= 0){
            $('#div'+idHtmlId).remove();
        }
 
        if(rowDiv.hasClass('main'+cnt+'_2nd')){ 
            if ($(".main"+cnt+"_2nd_add").length > 0){
              if(rowDiv.hasClass('main'+cnt+'_2nd')){
                  $(".main"+cnt+"_2nd").remove(); 
              }
              else{
                  $(".main"+cnt+"_2nd").parent().remove(); 
              }
              $('.main'+cnt+'_2nd_add:first').removeClass("main"+cnt+"_2nd_add").addClass("main"+cnt+"_2nd");
            }
            else{
              resetFirstOptional(cnt);  
            }
        }else{
            rowDiv.parent().remove();
        }

    });


$(document).on('click',".removeOptionGroup",function (){

  var cnt = $(this).data("cnt");
  var formatHeadValue = $.trim($('.prod_'+cnt).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
  var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase();}); 

  $('.main'+cnt+'_2nd').each(function(){
    var selfValue = $(this).find('div > .otherNameValue'+cnt).val();
    var value = selfValue+headValue; 
    var idHtmlId = headValue.replace(/ /g,'')+'Combination';
    $("#"+idHtmlId+" option[data-temp='"+value+"']").remove(); 
    if($('#'+idHtmlId).has('option').length <= 0){
      $('#div'+idHtmlId).remove();
    }
  });
 
  $('.combinationContainer').empty();
  noCombination = true;
  arraySelected = {}; 
  $('.main'+cnt).parent().remove(); 
});


$(document).on('change',".option_image_input",function (){
  var $parent = $(this).parent();
  var cnt = $parent.find('.removeOptionValue').data('cnt');
  $parent.find('.removeOptionValue').remove();
  $parent.find('.clear').remove();
  if(badIE == false){
    var anyWindow = window.URL || window.webkitURL; 
    $parent.find('.option_image_container').remove();
    $parent.append("<span class='option_image_container'><span class='option_image_span'><img src='"+anyWindow.createObjectURL(this.files[0])+"' class='option_image'></span></span>");
  }
  $parent.append("<a class='removeOptionValue remove_option_short' data-cnt="+cnt+"><img alt='Remove' src='<?=base_url()?>assets/images/icon-remove.png'></a>");

  $parent.find('[name^=prod_other_img_idx]').val(1);

});

    // ES_UPLOADER BETA

    var filescntret;
    function startUpload(cnt,filescnt,arrayUpload,afstart){
      if(window.FileReader) {   
          badIE = false;
      } else { 
          badIE = true;
      }
      $('.counter').val(cnt); 
      $('.filescnttxt').val(filescnt); 
      $('#afstart').val(JSON.stringify(afstart));
      var response;   
      $('#form_files').ajaxForm({
        url: config.base_url+'productUpload/uploadimage',
        type: 'post', 
        dataType: "json",         
        uploadProgress : function(event, position, total, percentComplete) {
          canProceed = false;
          console.log(percentComplete);
        },
        success :function(d) {   
          filescntret = d.fcnt;
          $('.filescnt'+filescntret+' > .loadingfiles').remove();
          $('.filescnt'+filescntret+' > span').removeClass('loading_opacity');
          $('.filescnt'+filescnt+' > .makeprimary').show(); 
          $('.filescnt'+filescnt+' > .removepic').show(); 
          canProceed = true;

          if(d.err == '1'){
            alert(d.msg);
            $.each( arrayUpload, function( key, value ) {
             removeThisPictures.push(value); 
             $('#previewList'+value).remove();
           });
          } 
          if(badIE == true){
            $(".files").remove();
            $('#inputList').append('<input type="file"  id="files" class="files active" name="files[]" accept="image/*" required = "required"  /> ');
          }


        },
        error: function (request, status, error) {

          response = request.responseText;
          if (response.toLowerCase().indexOf("1001") >= 0){
            alert('Sorry, the images you are uploading are too large.');
          }else{
            alert('Sorry, we have encountered a problem.','Please try again after a few minutes.');
          }
          $.each( arrayUpload, function( key, value ) {
            removeThisPictures.push(value); 
            $('#previewList'+value).remove();
          });

          canProceed = true;
          if(badIE == true){
            $(".files").remove();
            $('#inputList').append('<input type="file"  id="files" class="files active" name="files[]"  accept="image/*" required = "required"  /> ');
          }


        }
      }); 

$('#form_files').submit();


}

var filescnt = 1;
$(document).on('change',".files.active",function (e){
  var arrayUpload = new Array();
  var afstart = new Array();
  
  if(window.FileReader) {   
   badIE = false;
 } else { 
   badIE = true;
 }
 

 if(badIE == false){
  var fileList = this.files;
  var anyWindow = window.URL || window.webkitURL;
  var errorValues = "";

  for(var i = 0; i < fileList.length; i++){
    var size = fileList[i].size
    var val = fileList[i].name;
    var extension = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
    var objectUrl = anyWindow.createObjectURL(fileList[i]);
    var primaryText = "Make Primary";
    var activeText = "";
    pictureInDiv = $("#list > div").length;
    if(pictureInDiv == 0){
      primaryText = "Your Primary";
      activeText = "active_img";
      primaryPicture = pictureCount;
    }

    if((extension == 'gif' || extension == 'jpg' || extension == 'png' || extension == 'jpeg') && size < 5242880){
      $('#list').append('<div id="previewList'+pictureCount+'" class="new_img upload_img_div '+activeText+' filescnt filescntactive filescnt'+filescnt+'"><span class="upload_img_con loading_opacity"><img src="'+objectUrl+'"></span><a href="javascript:void(0)" class="removepic" data-number="'+pictureCount+'">x</a><br><a href="javascript:void(0)" class="makeprimary photoprimary'+pictureCount+'" data-number="'+pictureCount+'">'+primaryText+'</a><div class="loadingfiles"></div></div>');
      $('.filescnt'+filescnt+' > .makeprimary').hide(); 
      $('.filescnt'+filescnt+' > .removepic').hide(); 

    }else{
      if(size < 5*1024*1024){
        errorValues += val + "\n(Invalid file type).\n<br>";
      }else{
       errorValues += val + "\n(The file size exceeds 5 MB).\n<br>";
     }
     removeThisPictures.push(pictureCount);
   }

   fname = tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension+'||'+extension;
   fnamestart = tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension;
   af.push(fname); 
   afstart.push(fnamestart); 

   window.URL.revokeObjectURL(fileList[i]);
   arrayUpload.push(pictureCount);
   pictureCount++; 

 }

 if(errorValues != ""){
  alert("Sorry, the following files cannot be uploaded:", errorValues)
}

$(".files").hide();  
$(".files.active").each(function(){
  $(this).removeClass('active');
});


startUpload(pictureCount,filescnt,arrayUpload,afstart);
filescnt++;
$('#inputList').append('<input type="file"  id="files" class="files active" name="files[]" multiple accept="image/*" required = "required"  /> ');
$(this).remove();

}else{

  var val = $(this).val();
  var primaryText = "Make Primary";
  var activeText = "";
  pictureInDiv = $("#list > div").length;
  if(pictureInDiv == 0){
    primaryText = "Your Primary";
    activeText = "active_img";   
    primaryPicture = pictureCount;
  }

  var id = "imgid" + pictureCount;
  imageCustom = document.getElementById('files').value;

  var filename = imageCustom.match(/[^\/\\]+$/);
  extension = val.substring(val.lastIndexOf('.') + 1).toLowerCase();
  switch(extension){
    case 'gif': case 'jpg': case 'png': case 'jpeg':

    $('#list').append('<div id="previewList'+pictureCount+'" class="new_img upload_img_div '+activeText+' filescnt filescntactive filescnt'+filescnt+'"><span class="upload_img_con"><img src="'+imageCustom+'" alt="'+filename+'" style="height:100px;"></span><a href="javascript:void(0)" class="removepic" data-number="'+pictureCount+'">x</a><br><a href="javascript:void(0)" class="makeprimary photoprimary'+pictureCount+'" data-number="'+pictureCount+'">'+primaryText+'</a><div class="loadingfiles"></div></div>');   
    $('.filescnt'+filescnt+' > .makeprimary').hide(); 
    $('.filescnt'+filescnt+' > .removepic').hide(); 
    break;
    default:
    removeThisPictures.push(pictureCount); 
    break;
  }

  fname = tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension+'||'+extension;
  fnamestart = tempId+'_'+memberId+'_'+fulldate+pictureCount+'.'+extension;
  af.push(fname); 
  afstart.push(fnamestart);

  arrayUpload.push(pictureCount);  
  pictureCount++;

  $(".files").hide();  
  $(".files.active").each(function(){
    $(this).removeClass('active');
  });

  startUpload(pictureCount,filescnt,arrayUpload,afstart);
  filescnt++;
            // $('#inputList').append('<input type="file"  id="files" class="files active" name="files[]" multiple accept="image/*" required = "required"  /> ');
            // $(this).remove();

          }


        });

$(document).on('click',".removepic",function (){

        /* 
         * Altered ON: 5/6/2014
         * Altered BY: SAM (for edit functionlity)
         * Changed ".photoprimary"+idNumber selector to $(this) + sibling/closest selectors
         * SEE REVISION 1529 for original code
         */
         
         var idNumber;
         var text = $(this).siblings('.makeprimary').first().text();

         if($(this).closest('.upload_img_div').hasClass('new_img')){
          idNumber = $(this).data('number');
          removeThisPictures.push(idNumber);
        }
        else if($(this).closest('.upload_img_div').hasClass('edit_img')){
          idNumber = $(this).data('imgid');
          editRemoveThisPictures.push(idNumber);
        } 

        $(this).closest('.upload_img_div').remove();
        if(text == "Your Primary"){
          var first_img_div = $("#list > div:first-child" );
          var primary_control_anchor = $("#list > div:first-child > .makeprimary");
          if(typeof primary_control_anchor[0] !== 'undefined'){
            if(first_img_div.hasClass('new_img')){
              primaryPicture = primary_control_anchor.data('number');
              editPrimaryPicture = -1;
            }
            else if(first_img_div.hasClass('edit_img')){
              editPrimaryPicture = primary_control_anchor.data('imgid');
              primaryPicture = 0;
            }
            
          }
          else{
            editPrimaryPicture = -1;
            primaryPicture = 0;
          }
          primary_control_anchor.text('Your Primary');     
          first_img_div.addClass("active_img"); 
        }


      });

$(document).on('click','.makeprimary',function(){
        /* 
         * Altered ON: 5/6/2014
         * Altered BY: SAM (for edit functionlity)
         * Changed ".photoprimary"+idNumber selector to $(this) + sibling/closest selectors
         * SEE SVN REVISION 1529 for original code
         */

         if($(this).closest('.upload_img_div').hasClass('new_img')){
          primaryPicture = $(this).data('number');
          editPrimaryPicture = -1;
        }
        else if($(this).closest('.upload_img_div').hasClass('edit_img')){
          editPrimaryPicture = $(this).data('imgid');
          primaryPicture = 0;
        }
        else{
          return false;
        }
        $(".makeprimary").text('Make Primary');
        $(".upload_img_div").removeClass("active_img");
        $(this).text('Your Primary');
        $(this).closest('.upload_img_div').addClass("active_img");
        
      });

    // ES_UPLOAER BETA END
    
    $(document).on('click',".removeSelected",function (){
      var row = $(this).data('row');
      removeSelected (row);
      var trueFalse = isEmpty(arraySelected);        
      if(trueFalse) {
        noCombination = true;
      } 
    });


    $('.quantity_table_row , .quantity_table2').on('click', '.quantity_attr_done', function () {  

      var qtyTextbox = $('.qtyTextClass');
      var qtyTextboxValue = parseInt(qtyTextbox.val());
      var dataCombination = {};     
      var combinationVal = [];
      var sortCombination = [];
      var arrayCombinationString = "";
      var thisValueCount = $(this).data('value');
      var htmlEach  = "";
      var alreadyExist  = false;
      var haveValue = false;
      if(isNaN(qtyTextboxValue) ||  qtyTextboxValue <= 0){ 
        qtyTextbox.val('1');
      }
      htmlEach += '<div class="input_qty"><input type="textbox" class="quantityText" value="'+qtyTextbox.val()+'" data-cnt="'+thisValueCount+'"></div><div class="mid_inner_con_list">';


      $('.quantity_attrs_content option:selected').each(function(){
        haveValue = true;
        noCombination = false;
        var eachValue = $(this).val();
        var eachValueString = $(this).text();
        var eachGroup = $(this).data('group');  
        var eachDataValue = $(this).data('value');  
        combinationVal.push(eachDataValue+':'+eachValue+':'+eachGroup);
        htmlEach += '<div class="mrginrght-5">'+ eachGroup +': ' + eachValueString +'</div>';
      });

      sortCombination = combinationVal.sort();

      for (var i = 0; i < sortCombination.length; i++) {
        arrayCombinationString += sortCombination[i] + '~-~';
      };

      for (var key in arraySelected) { 
        if (arraySelected.hasOwnProperty(key))
          if(arraySelected[key]['value'] === arrayCombinationString.slice(0, - 3)){
            alreadyExist = true;
            break;
          }
        }


        if(haveValue === true){
          if(alreadyExist === false){

            $('.combinationContainer').append('<div class="inner_quantity_list innerContainer'+thisValueCount+' width-100p"> '+ htmlEach +'</div> <a href="javascript:void(0)" class="removeSelected" data-row="'+thisValueCount+'"   style="color:Red">Remove</a></div>');
            dataCombination['quantity'] = qtyTextbox.val();
            dataCombination['value'] = arrayCombinationString.slice(0, - 3);
            arraySelected[thisValueCount] = dataCombination;
            thisValueCount++;
            $(this).data('value',thisValueCount);
          }else{
            alert('This combination has already been selected. Please select another combination.');
            return false;
          }  
        } 

      });



$(document).on('change','.other_name_class',function(){
  $('.combinationContainer').empty();
  noCombination = true;
  arraySelected = {};  
  var formatHeadValue = $.trim($(this).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
  var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) {
    return letter.toUpperCase();
  }); 
  var formatOldValue = $(this).data('oldValue');
  if (formatOldValue == undefined){
    formatOldValue ="";
  }
  var oldValue = formatOldValue;
  var cnt = $(this).data('cnt');
  var headCount = 0;
  var idHeadValue = escapes(headValue.replace(/ /g,''));
  var idOldValue = escapes(oldValue.replace(/ /g,''));
  if(!headValue <= 0){
    $('.other_name_class').each(function(){
      var thisValue = $.trim($(this).val());
      var thisValue = thisValue.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
      });  
      if(headValue == thisValue){
        headCount++;
      }
    });
  }  
  if(headCount > 1){
    alert('Value '+headValue+' already exist!');
    $('.prod_'+cnt).val(oldValue);
    return false;
  }
  $(this).data('oldValue', headValue);
  if(!$.trim( $('#'+idHeadValue+'Combination').html()).length <= 0){
    $('#'+idOldValue+'Combination option[data-value=1]').each(function(){
      var value = $(this).val();
      var dataValue = 1;
      var dataGroup = headValue;
      var dataTemp = value+headValue;
      var html = '<option value="'+value+'" data-temp="'+dataTemp+'" data-value="1" data-group="'+dataGroup+'">'+value+'</option>'
      $('#'+idHeadValue+'Combination').append(html);
      $(this).remove();
    });

    if($('#'+idOldValue+'Combination').has('option').length <= 0 ){
      $('#div'+idOldValue+'Combination').remove();
    }
  }else{
    if(headValue.length <= 0){
      $('#'+idOldValue+'Combination option[data-value=1]').remove();  
      if($('#'+idOldValue+'Combination').has('option').length <= 0){
        $('#div'+idOldValue+'Combination').remove();
      }
    }else{  
      $('#'+idOldValue+'Combination option[data-value=1]').each(function(){
        var value = $(this).val();
        var dataValue = 1;
        var dataGroup = headValue;
        var dataTemp = value+headValue;
        var html = '<option value="'+value+'" data-temp="'+dataTemp+'" data-value="1" data-group="'+dataGroup+'">'+value+'</option>'
        $('#'+idHeadValue+'Combination').append(html);
        $(this).remove();
      });

      if($('#'+idOldValue+'Combination').has('option').length <= 0){
        $('#div'+idOldValue+'Combination').remove();
      }

      if(!headValue.length <= 0){
        var haveValue = 0;
        $(".otherNameValue"+cnt).each(function(){
          var attrVal = $(this).val();
          if(attrVal.length > 0){
            haveValue++;
          }
        });
        if(haveValue > 0){
          $('.quantity_attrs_content').append('<div id="div'+idHeadValue+'Combination" style="position:relative">'+headValue+':<br> <select id="'+idHeadValue+'Combination" ></select><br></div>');   
          $(".otherNameValue"+cnt).each(function(){  
            var attrVal = $(this).val();
            var attrID = $(this).data('otherid');
            if(attrVal.length > 0){
              $('[id='+idHeadValue+'Combination]').append('<option value="'+attrVal+'" data-temp="'+attrVal+headValue+'" data-value="1" data-group="'+headValue+'" data-otherid="'+attrID+'">'+attrVal+'</option>');
            }
          });
        }
      }

    }
  }

  $('.prod_'+cnt).val(headValue);
  $(".otherNameValue"+cnt).each(function(){
    var value = $(this).val()+headValue;
    $(this).data('temp','"'+value+'"');
  });

  if( !$.trim( $('.quantity_attrs_content').html() ).length ) {
    $('.quantity_attr_done').hide();
    $('.qty_tooltip').hide();
    noCombination == true
  }else{
    $('.quantity_attr_done').show();
    $('.qty_tooltip').show();
  }
});



$(document).on('change','.other_name_value',function(){
  $('.combinationContainer').empty();
  noCombination = true;
  arraySelected = {};  

  var cnt = $(this).data('cnt');
  var formatHeadValue = $.trim($('.prod_'+cnt).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
  var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) {
    return letter.toUpperCase();
  }); 
  var temp = $(this).data('temp'); 
  var selfValue = escapeHtml($.trim($(this).val()));
  var value = selfValue+headValue;
  $(this).data('temp','"'+value+'"');
  var attrVal = selfValue;

  var idHtmlId = headValue.replace(/ /g,'')+'Combination';

  if(formatHeadValue.length >0){ 
    $('#'+idHtmlId+' option[data-temp='+temp+']').remove();
    if(!$('#'+idHtmlId).length){
      $('.quantity_attrs_content').append('<div id="div'+idHtmlId+'" style="position:relative">'+headValue+':<br> <select id="'+idHtmlId+'" ></select><br></div>');   
    }
    if(!attrVal.length <= 0){
              //added additional data attribute to option for edit option
              var attrID = $(this).data('otherid');
              $('#'+idHtmlId).append('<option value="'+attrVal+'" data-temp="'+value+'" data-value="1" data-group="'+headValue+'" data-otherid="'+attrID+'">'+attrVal+'</option>');
            } 

            if($('#'+idHtmlId).has('option').length <= 0){
              $('#div'+idHtmlId).remove();
            }

            if( !$.trim( $('.quantity_attrs_content').html() ).length ) {
              $('.quantity_attr_done').hide();
              $('.qty_tooltip').hide();
              noCombination == true
            }else{
              $('.quantity_attr_done').show();
              $('.qty_tooltip').show();
            }
          }
        }); 


$(".checkbox_itemattr").click(function(){
  addAttrQtySelection($(this));
});

$(".add_more_link").unbind("click").click(function(){
  cnt_o++;
  $('.step4_2').append('<div class="pd-8-12"><div class="pd-tb-8 main'+cnt_o+'"><div class="display-ib width-15p-10min hide_space"></div><div class="display-ib"><input type="text" data-cnt="'+cnt_o+'" autocomplete="off" name="prod_other_name[]" class="width-30p-245min pd-tb-8 prod_'+cnt_o+' other_name_class" placeholder="Item property title"><a href="javascript:void(0)" data-cnt="'+cnt_o+'" class="removeOptionGroup">Remove This Group</a></div></div><div class="row'+cnt_o+'"><div class="display-ib pd-tb-8 width-15p-10min hide_space"></div><div class="other_value pos-rel pd-tb-8 main'+cnt_o+' main'+cnt_o+'_2nd"><div class="display-ib pd-tb-8"><input type="text" autocomplete="off" data-cnt="'+cnt_o+'" class="width-30p-245min other_name_value otherNameValue'+cnt_o+'" name="prod_other[]" placeholder="Item property value"></div>   <div style="display:none"><input type="text" name="prod_other_id[]" value=""> </div>  <div class="display-ib"> <div class="h_if_'+cnt_o+' hdv"  style="display:none"> &#8369; <input type="text" class="width-30p-245min" name="prod_other_price[]"  class="price_text"   id="price_field"   onkeypress="return isNumberKey(event)"   autocomplete="off" placeholder="Enter additional price (0.00)"></div></div><div class="display-ib option_image_td"> <div class="h_if_'+cnt_o+' hdv" style="display:none"><input type="file" class="option_image_input vrtcl-mid" name="prod_other_img[]" accept="image/*" ><input type="hidden" name="prod_other_img_idx[]"><a data-cnt="'+cnt_o+'" class="removeOptionValue remove_option_long" href="javascript:void(0)"><img src="<?=base_url()?>assets/images/icon-remove.png" alt="Remove"></a></div></div></div></div><div id="main'+cnt_o+'" class="pd-tb-8 main'+cnt_o+'_link"><div class="display-ib"></div><div class="display-ib width-15p-10min hide_space"></div><div class="display-ib"><a class="add_more_link_value" data-value="'+cnt_o+'" href="javascript:void(0)">+Add more value</a></div></div></div>');
});

$('.upload_input_form').on('click', '.add_more_link_value', function() {
  var data =   $(this).data( "value" );   
  $(".h_if_"+data).css("display", "block");
  var attr = $('.prod_'+data).val();

  var  subClass = "main"+data+"_2nd_add";

  var newrow = $('<div class="row'+data+'"><div class="display-ib width-15p-10min hide_space"></div><div class="other_value pos-rel pd-tb-8 main'+data+' '+subClass+'"><div style="display:none"><span ><input type="text" value ="'+attr+'" data-cnt="'+data+'" class="width-30p-245min pd-tb-8 prod_'+data+'" name="prod_other_name[]"></span></div><div class="display-ib"><input type="text" autocomplete="off" data-cnt="'+data+'" class="width-30p-245min pd-tb-8 other_name_value otherNameValue'+data+'"  name="prod_other[]" placeholder="Item property value"></div>  <div style="display:none"><input type="text" name="prod_other_id[]" value=""> </div> <div class="display-ib pd-tb-8"> &#8369; <input type="text" name="prod_other_price[]"  id="price_field" class="width-30p-245min price_text"   onkeypress="return isNumberKey(event)"   autocomplete="off" placeholder="Enter additional price (0.00)"></div><div class="display-ib option_image_td"><input type="file" class="option_image_input vrtcl-mid" name="prod_other_img[]"  accept="image/*"><input type="hidden" name="prod_other_img_idx[]"><a data-cnt="'+data+'" class="removeOptionValue remove_option_long" href="javascript:void(0)"><img alt="Remove" src="<?=base_url()?>assets/images/icon-remove.png"></a></div></div></div>');
  $('#main'+data).before(newrow);
});

$(document).on('change',"#price_field,#prod_price, .price_text",function () {
      var priceval = this.value.replace(new RegExp(",", "g"), '');
      var v = parseFloat(priceval);
      var tempval;
      if (isNaN(v)) {
        this.value = '';
      } else {
        tempval = Math.abs(v);
        this.value = ReplaceNumberWithCommas(tempval.toFixed(2));
      }
      
    });


  $( "#prod_title,#prod_price,#prod_condition,#qtyTextClass" ).blur(function() {
      var value = $(this).val();
      var id = $(this).attr('id');

      if(value == "0" || value == ""){
        validateRedTextBox("#"+id);
      }else{
        validateWhiteTextBox("#"+id);
      }
  });

  $( "#brand_sch,#prod_title,#prod_brief_desc,#prod_price,#qtyTextClass" ).keypress(function() {
      var id = $(this).attr('id');
      validateWhiteTextBox("#"+id);
  });

$(document).on('change',"#prod_condition",function () {
  var id = $(this).attr('id');
  validateWhiteTextBox("#"+id);
});

function proceedStep3(url){
  $('#form_product').ajaxForm({ 
   url: url,
   dataType: "json",
   beforeSubmit : function(arr, $form, options){
    var percentVal = '0%';
    $('.percentage').html(percentVal);
    $( ".button_div" ).hide();
    $( ".loader_div" ).show();

    $('<input type="hidden">').attr({
      id: 'inputs',
      name: 'inputs',
      value: g_input_name
    }).appendTo('form');
    arr.push({name:'inputs', value:g_input_name});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'id',
                      name: 'id',
                      value: g_id
                    }).appendTo('form');
                    arr.push({name:'id', value:g_id});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'combination',
                      name: 'combination',
                      value: g_combinationSelected
                    }).appendTo('form');
                    arr.push({name:'combination', value:g_combinationSelected});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'desc',
                      name: 'desc',
                      value: g_description
                    }).appendTo('form');
                    arr.push({name:'desc', value:g_description});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'noCombination',
                      name: 'noCombination',
                      value: g_noCombination
                    }).appendTo('form');
                    arr.push({name:'noCombination', value:g_noCombination});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'otherCategory',
                      name: 'otherCategory',
                      value: g_otherCategory
                    }).appendTo('form');
                    arr.push({name:'otherCategory', value:g_otherCategory});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'removeThisPictures',
                      name: 'removeThisPictures',
                      value: g_removeThisPictures
                    }).appendTo('form');
                    arr.push({name:'removeThisPictures', value:g_removeThisPictures});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'primaryPicture',
                      name: 'primaryPicture',
                      value: g_primaryPicture
                    }).appendTo('form');
                    arr.push({name:'primaryPicture', value:g_primaryPicture});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'editRemoveThisPictures',
                      name: 'editRemoveThisPictures',
                      value: g_editRemoveThisPictures
                    }).appendTo('form');
                    arr.push({name:'editRemoveThisPictures', value:g_editRemoveThisPictures});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'editPrimaryPicture',
                      name: 'editPrimaryPicture',
                      value: g_editPrimaryPicture
                    }).appendTo('form');
                    arr.push({name:'editPrimaryPicture', value:g_editPrimaryPicture});
                    // -------------------------
                    
                    $('<input type="hidden">').attr({
                      id: 'quantitySolo',
                      name: 'quantitySolo',
                      value: g_quantitySolo
                    }).appendTo('form');
                    arr.push({name:'quantitySolo', value:g_quantitySolo});


                  },
                  uploadProgress : function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    $('.percentage').empty();
                    if(percentComplete >= 100){
                      percentVal = '100%'
                      $('.percentage').html(percentVal);
                    }else{
                      $('.percentage').html(percentVal);
                    }
                  },
                  success :function(d) { 
                    $('.percentage').html('100%');
                    if (d.e == 1) {
                      $('#prod_h_id').val(d.d); 
                      document.getElementById("hidden_form").submit();
                    } else {
                      $( ".button_div" ).show();
                      $( ".loader_div" ).hide();
                      $('.percentage').empty();
                      alert(d.d);
                    } 
                  },
                  error: function (request, status, error) {
                    $( ".button_div" ).show();
                    $( ".loader_div" ).hide();
                    $('.percentage').empty();
                    response = request.responseText;
                    if (response.toLowerCase().indexOf("1001") >= 0){
                      alert('Something Went Wrong. The images you are uploading in [OTHER ATTRIBUTES] are too large.');
                    }else{
                      alert('Something Went Wrong. Please try again.');
                    }
                  } 
                }); 
}

$(".proceed_form").unbind("click").click(function(){

  tinyMCE.triggerSave();
  var description = tinyMCE.get('prod_description').getContent();
  var id = "<?php echo $id; ?>"; 
  var input_name = "<?php echo (string)$array_name_inputs; ?>";
  var title = $("#prod_title");
  var brief = $("#prod_brief_desc"); 
  var combinationSelected = JSON.stringify(arraySelected);
  var otherCategory = escapeHtml("<?php echo isset( $otherCategory)? html_escape($otherCategory) : (isset( $product_details['otherCategory'])?html_escape($product_details['otherCategory']):'' ); ?>");

  g_input_name = input_name;
  g_id = id;
  g_combinationSelected = combinationSelected;
  g_description = description;
  g_noCombination = noCombination;
  g_otherCategory = otherCategory;
  g_removeThisPictures = JSON.stringify(removeThisPictures);
  g_primaryPicture = primaryPicture;
  g_editRemoveThisPictures = JSON.stringify(editRemoveThisPictures);
  g_editPrimaryPicture = editPrimaryPicture;  

  var csrftoken = $("meta[name='csrf-token']").attr('content');
  var csrfname = $("meta[name='csrf-name']").attr('content');
  var price = $("#prod_price");
  var other_price = $("#price_field");
  var sku = $("#prod_sku");  
  var brand = $("#prod_brand");
  var condition = $('#prod_condition');
  var conditionAvailable = <?php echo json_encode($this->lang->line('product_condition')); ?>;
  var found = false;
  var quantity = $('.qtyTextClass');
  var combinationQuantity = $('.quantityText');


  if(title.val().length == 0){
    $( "#prod_title" ).focus();
    validateRedTextBox("#prod_title");
  }

        // if(brief.val().length == 0){
        //     $( "#prod_brief_desc" ).focus();
        //     validateRedTextBox("#prod_brief_desc");
        // }

        if(price.val().length == 0){
          $( "#prod_price" ).focus();
          validateRedTextBox("#prod_price");
        }

        // if(brand.val() == "0"){
        // validateRedTextBox("#brand_sch");
        // }else{
        // validateWhiteTextBox("#brand_sch");
        // }

        if(quantity.val().length == 0){
          $( "#qtyTextClass" ).focus();
          validateRedTextBox("#qtyTextClass");
        }

        if(condition.val() == "0"){
          validateRedTextBox("#prod_condition");
        }else{
          validateWhiteTextBox("#prod_condition");
        }

        if(description.length == 0){
          validateRedTextBox("#mce_27");
        }

        if(combinationQuantity.val() <= 0){
          alert('0 Quantity is Invalid!');
          return false;
        }

        if(title.val().length == 0 || description.length == 0 || price.val().length == 0 ){
          alert("Fill (*) All Required Fields Properly!");
          return false;
        }else{

          var pricevalue = price.val().replace(new RegExp(",", "g"), '');
          if(pricevalue <= 0 || !$.isNumeric(pricevalue)){
            alert("Invalid Price. Price should be numeric or cannot be less than equal 0!");
            validateRedTextBox("#prod_price");
            return false;
          }else{
           found = false;
           for (var key in conditionAvailable) {
            if (conditionAvailable.hasOwnProperty(key))
              if(conditionAvailable[key]=== condition.val()){
               found = true;
               break;
             }
           }

           if(found === false){
             validateRedTextBox("#prod_condition");
             $( "#prod_condition" ).focus(); 
             alert('Condition selected not available. Please select other.');
             return false;
           } 

           if(noCombination == true){

            if(quantity.val().length == 0 || quantity.val() <= 0){
              $( ".qtyTextClass" ).focus();
              validateRedTextBox(".qtyTextClass"); 
              alert("Invalid Quantity!");
              return false;
            }

            g_quantitySolo = quantity.val();
          }
          if(canProceed == false){
            alert('Please wait while your pictures are being uploaded.');
            return false;
          }else{
            var action = $('#form_product').attr('action');
            $('.arrayNameOfFiles').val(JSON.stringify(af)); 

            proceedStep3(action);
            confirm_unload = false;
            $('#form_product').submit();
          }


        }
      }
    });

    /*
     * Product Edit javascript
     * Sam Gavinio
     */


     var itemPrice = parseFloat($('#prod_price').data('price'));
     if((itemPrice>0)&&(!isNaN(itemPrice))){
      $('#prod_price').val(itemPrice);
      $('#prod_price').change();
    }

    //Add previously checked attributes to the quantity select elements
    $('.checkbox_itemattr').each(function(){
      if($(this).is(":checked")){
        addAttrQtySelection($(this));
      }
    });

    //Add previously entered optional attributes to the quantity select elements
    $('.other_name_class').each(function(){
      $(this).change();
    });
    $('.other_name_value').each(function(){
      $(this).change();
    });

    //Submits previously configured quantity attribute combinations 
    var qty_obj =  JSON.parse($('#qty_details').val());
    
    var html_item_selection = $('#quantity_attrs_content2').find('option');
    var prev_combination_count = 1;
    $.each(qty_obj,function(){
      var $this = this;
      $('.qtyTextClass').val($this.quantity);
      $.each(html_item_selection,function(){
        $(this).attr("selected",false);
            //Category specific attributes
            if($(this).data('value') === 0){
              if($.inArray($(this).val(), $this.attr_lookuplist_item_id) !== -1){
                $(this).attr("selected",true);
              }
            }
            //Optional product attributes
            //formerly:  if($(this).attr('data-otherid')!==undefined)
            else if($(this).data('value') === 1){
              var value_arr = new Array();
              $.each($this.product_attribute_ids, function(y,x){
                value_arr.push([x.id, x.is_other]);
              });
              var idx = inArray([$(this).attr('data-otherid'),"1"], value_arr);
              if(idx>-1){
                if(parseInt($this.attr_lookuplist_item_id[idx]) === 0){
                  $(this).attr("selected",true);
                }     
              }    
            }                
          });
      addAttrQtyCombination(prev_combination_count++);
    });


    //Function that adds obj to the quantity select html elements
    function addAttrQtySelection(obj){
      var attrIdVal = obj.data( "attrid" );
      var attrVal = obj.data('value');
      var attrGroup = obj.data('group'); 
      var idHtmlId = attrGroup.replace(' ','')+'Combination';
      var attrValNoSpace = attrVal.replace(' ','');

      $('.combinationContainer').empty();
      noCombination = true;
      arraySelected = {};  

      if (obj.is(":checked")) {
        if(!$('#'+idHtmlId).length){
          $('.quantity_attrs_content').append('<div id="div'+idHtmlId+'" style="position:relative">'+attrGroup+':<br> <select id="'+idHtmlId+'" ></select><br></div>');   
        }
        $('#'+idHtmlId).append('<option value="'+attrIdVal+'" data-value="0" data-group="'+attrGroup+'">'+attrVal+'</option>');
      }else{
        $('#'+idHtmlId +' option[value="'+attrIdVal+'"]').remove();
        if( !$.trim( $('#'+idHtmlId).html() ).length ) {
          $('#div'+idHtmlId).remove();
        }
      } 

      if( !$.trim( $('.quantity_attrs_content').html() ).length ) {
        $('.quantity_attr_done').hide();
        $('.qty_tooltip').hide();
        noCombination == true
      }else{
        $('.quantity_attr_done').show();
        $('.qty_tooltip').show();
      }
    }


    function addAttrQtyCombination(count){
      var qtyTextbox = $('.qtyTextClass');
      var qtyTextboxValue = parseInt(qtyTextbox.val());
      var dataCombination = {};     
      var combinationVal = [];
      var sortCombination = [];
      var arrayCombinationString = "";
      var thisValueCount = count;
      var htmlEach  = "";
      var alreadyExist  = false;
      var haveValue = false;
      htmlEach += '<div class="input_qty"><input type="textbox" class="quantityText" value="'+qtyTextbox.val()+'" data-cnt="'+thisValueCount+'"></div><div class="mid_inner_con_list">';

      $('.quantity_attrs_content option').each(function(){
        if($(this).attr('selected')){
          haveValue = true;
          noCombination = false;
          var eachValue = $(this).val();
          var eachValueString = $(this).text();
          var eachGroup = $(this).data('group');  
          var eachDataValue = $(this).data('value');  
          combinationVal.push(eachDataValue+':'+eachValue+':'+eachGroup);
          htmlEach += '<div>'+ eachGroup +': ' + eachValueString +'</div>';
        }
      });
      if(isNaN(qtyTextboxValue) ||  qtyTextboxValue <= 0){ 
        qtyTextbox.val('1');
      }
      sortCombination = combinationVal.sort();

      for (var i = 0; i < sortCombination.length; i++) {
        arrayCombinationString += sortCombination[i] + '~-~';
      };

      for (var key in arraySelected) { 
        if (arraySelected.hasOwnProperty(key))
          if(arraySelected[key]['value'] === arrayCombinationString.slice(0, - 3)){
            alreadyExist = true;
            break;
          }
        }

        if(haveValue === true){
          if(alreadyExist === false){      
            $('.combinationContainer').append('<div class="inner_quantity_list innerContainer'+thisValueCount+'"> '+ htmlEach +'</div> <a href="javascript:void(0)" class="removeSelected" data-row="'+thisValueCount+'"   style="color:Red">Remove</a></div>');
            dataCombination['quantity'] = qtyTextbox.val();
            dataCombination['value'] = arrayCombinationString.slice(0, - 3);
            arraySelected[thisValueCount] = dataCombination;
            thisValueCount++;
            $('.quantity_attr_done.orange_btn3').data('value', thisValueCount);
          }else{
            alert('Combination Already Selected!');
            return false;
          }
        }
      }




      $('.step1_link').on('click', function(){
        if(currentRequest != null) {
          $('#prod_brand').val(1)
          $('#prod_brand').trigger( "change" );
        }
        confirm_unload = false;
        $('#edit_step1').submit();
      });


      var prev_content = JSON.parse($('#step1_content').val());
      if(typeof prev_content.prod_title !== "undefined"){
        $('#prod_title').val(prev_content.prod_title);
      }
      if(typeof prev_content.brand_sch !== "undefined"){
        $('#brand_sch').val(prev_content.brand_sch);
      }
      if(typeof prev_content.prod_brand !== "undefined"){
        $('#prod_brand').val(prev_content.prod_brand);
      }
      if(typeof prev_content.prod_brief_desc !== "undefined"){
        $('#prod_brief_desc').val(prev_content.prod_brief_desc);
      }
      if(typeof prev_content.prod_description !== "undefined"){
        $('#prod_description').val(prev_content.prod_description);
      }
      if(typeof prev_content.prod_condition !== "undefined"){
        $('#prod_condition').val(prev_content.prod_condition);
      }
      if(typeof prev_content.prod_keyword !== "undefined"){
        $('#prod_keyword').val(prev_content.prod_keyword);
      }
      if(typeof prev_content.prod_price !== "undefined"){
        var priceval = prev_content.prod_price.replace(new RegExp(",", "g"), '');
        priceval = parseFloat(priceval);
        if(!isNaN(priceval) && !(priceval <= 0 ) ){
	    $('#prod_price').val( ReplaceNumberWithCommas(priceval.toFixed(2)));
        }
 
        
      }
      if(typeof prev_content.prod_sku !== "undefined"){
        $('#prod_sku').val(prev_content.prod_sku);
      }

      if(typeof prev_content.prod_discount_percentage !== "undefined"){
       var slider_temp_val = parseFloat(prev_content.prod_discount_percentage);
       if(isNaN(slider_temp_val)){
          slider_temp_val = 0;
       }
       if( parseFloat($('#prod_price').val(),10) > 0){
	  $('#slider_val').val(slider_temp_val); 
	  $('#slider_val').trigger( "change" );
       }

    }
    
    $("#dsc_frm").hide();

    var temp_content = new Object();
    temp_content.prod_title = $('#prod_title').val();
    temp_content.brand_sch = $('#brand_sch').val();
    temp_content.prod_brand = $('#prod_brand').val();
    temp_content.prod_condition = $('#prod_condition').val();
    temp_content.prod_brief_desc = $('#prod_brief_desc').val();
    temp_content.prod_description = $('#prod_description').val();
    temp_content.prod_keyword = $('#prod_keyword').val();
    temp_content.prod_price = $('#prod_price').val();
    temp_content.prod_sku = $('#prod_sku').val();
    temp_content.prod_discount_percentage = $('#slider_val').val();
    
    $('#step2_content').val(JSON.stringify(temp_content));
    

    $('#prod_title').change(function(){
      temp_content.prod_title = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#brand_sch').change(function(){
      temp_content.brand_sch = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#prod_brand').change(function(){
      temp_content.prod_brand = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#prod_condition').change(function(){
      temp_content.prod_condition = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#prod_brief_desc').change(function(){
      temp_content.prod_brief_desc = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#prod_description').change(function(){
      temp_content.prod_description = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#prod_keyword').change(function(){
      temp_content.prod_keyword = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    $('#prod_price').change(function(){
      temp_content.prod_price = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });

    $('#prod_sku').change(function(){
      temp_content.prod_sku = $(this).val();
      $('#step2_content').val(JSON.stringify(temp_content));
    });
    
    if(($('#brand_sch').val() !== '')&&(parseInt($('#prod_brand').val(),10) !== 0)){
      var img_temp = (parseInt($('#prod_brand').val(),10) !== 1)?'<img src="<?= base_url() ?>assets/images/check_icon.png" />':'<img src="<?= base_url() ?>assets/images/img_new_txt.png" />';
      jQuery(".brand_sch_loading").html(img_temp).show().css('display','inline-block');
    }

    $('#brand_search_drop_content').on('click', 'li.brand_result', function(){
      $this = $(this);     
      $('#prod_brand').val($this.data('brandid'));
      $("#brand_sch").val($this.text());
      $('#prod_brand').trigger( "change" );
      $("#brand_sch").trigger( "change" );
      jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/check_icon.png" />').show().css('display','inline-block');

      $('#brand_search_drop_content').hide();
    });

    var currentRequest = null;
    $( "#brand_sch" ).keyup(function() {
      $('#prod_brand').val(0)
      $('#prod_brand').trigger( "change" );
      jQuery(".brand_sch_loading").hide();
      var searchQuery = $(this).val();
      var csrftoken = $("meta[name='csrf-token']").attr('content');
      var csrfname = $("meta[name='csrf-name']").attr('content');
      if(searchQuery != ""){
        currentRequest = jQuery.ajax({
          type: "GET",
          url: '<?php echo base_url();?>product_search/searchBrand', 
          onLoading:jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/orange_loader_small.gif" />').show().css('display','inline-block'),
          data: "data="+searchQuery+"&"+csrfname+"="+csrftoken, 
          beforeSend : function(){       
            if(currentRequest != null) {
              currentRequest.abort();
            }
            $('.brand_sch_drop_content').show();
          },
          success: function(response) {
            currentRequest = null;
            var obj = jQuery.parseJSON(response);
            var html = '<ul>';
            if((obj.length)>0){
              jQuery.each(obj,function(){
                html += '<li class="brand_result" data-brandid="'+(this.id_brand) +'">'+(this.name)+'</li>' ;                             
              });
              html +=  '<li class="add_brand blue">Use your own brand name</li>';
              jQuery(".brand_sch_loading").hide();
            }
            else{
             addNewBrand();
           }

           html += '</ul>';
           $("#brand_search_drop_content").html(html);

           if(!$("#brand_sch").is(":focus")){
            var available = false;
            $('#brand_search_drop_content li.brand_result a').each(function(){
              if($(this).text().toLowerCase() ===  $('#brand_sch').val().toLowerCase()){
                $(this).click();
                available = true;
                return false;
              }
            });
            if(!available){
              addNewBrand();
            }
          }
        }
      });
}
});

$(document).on("click",".add_brand", function(){    
  if(currentRequest != null) {
    currentRequest.abort();
  }
  addNewBrand();
  $('#brand_search_drop_content').hide();
});

$('#brand_sch').focusout(function(){
 var available = false;
 $('#brand_search_drop_content li.brand_result').each(function(){
  if($(this).text().toLowerCase() ===  $('#brand_sch').val().toLowerCase()){
    $(this).click();
    available = true;
    return false;
  }
  if(!available){
    addNewBrand();
  }
});
});   

$(document).mouseup(function (e)
{
  var container = $("#dsc_frm");
        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
          temp_content.prod_discount_percentage = $('#slider_val').val();
          $('#step2_content').val(JSON.stringify(temp_content));
          container.hide(); 
        }
      });

});

function get_discPrice() {
  var prcnt = $("#slider_val").val().replace("%",'');
  var act_price = $("#prod_price").val().replace(/,/g,'');
  if (prcnt >= 100) {
    prcnt = 99;
  }
  if (act_price == 0 || act_price == null ) {      
    validateRedTextBox("#prod_price");
    act_price = 0;
  }

  $("#slider_val").val("");
  $("#slider_val").val(prcnt+"%");
  discounted = act_price * (prcnt/100);
  var v = parseFloat(act_price - discounted);
  tempval = Math.abs(v);
  disc_price = ReplaceNumberWithCommas(tempval.toFixed(2));
  $("#discountedP").val(disc_price);
  $( "span#discounted_price_con" ).text( disc_price );
}


function removeSelected(row){
  delete arraySelected[row];
  $('.innerContainer'+row).remove();
}

function isEmpty(myObject) {
  for(var key in myObject) {
    if (myObject.hasOwnProperty(key)) {
      return false;
    }
  }
  return true;
}

function escapes(string){
  if(string != undefined){
    return string.replace(/([;&,\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, '\\$1');
  }else{
    return "";
  }
}

function resetFirstOptional(cnt){ 
  $('.combinationContainer').empty();
  noCombination = true;
  arraySelected = {}; 
  var link = '<a href="javascript:void(0)" class="lnkClearFirst">Clear This Group</a>';
  var title = 'Others: (Optional)';
  if(cnt > 1){ 
    link = '<a class="removeOptionGroup" data-cnt='+cnt+' href="javascript:void(0)">Remove This Group</a>';
    title = '';
  }
  $('.main'+cnt).empty();  
  console.log('also here');      
  $('.pd-8-12 > .main'+cnt).append('<div class="display-ib width-15p-10min pd-tb-8 vrtcl-top">'+title+'</div> \
    <div class="others_content">\
      <input type="text" placeholder="Item property title" autocomplete="off" class="prod_'+cnt+' other_name_class width-30p-245min" data-cnt="'+cnt+'" name="prod_other_name[]"> \
      '+link+'\
      <span class="f11 display-bl">For Example: Color, Brand and Year</span>\
      <div class="main'+cnt+' main'+cnt+'_2nd pd-tb-8 other_value pos-rel">\
        <div class="display-ib">\
          <input type="text" placeholder="Item property value " data-cnt="'+cnt+'" autocomplete="off" class="other_name_value otherNameValue1 width-30p-245min" name="prod_other[]">\
          <span class="f11 display-bl">For Example: Blue, SKK and 2013</span>\
          <div style="display:none">\
            <input type="text" value="" name="prod_other_id[]">\
          </div>\
        </div>\
        <div class="display-ib vrtcl-top">\
          <div class="h_if_'+cnt+' hdv" style="display: none;">\
             &#8369; <input type="text" placeholder="Enter additional price (0.00)" id="price_field" autocomplete="off" onkeypress="return isNumberKey(event)" name="prod_other_price[]" class="width-30p-245min price_text">\
          </div>\
        </div>\
        <div class="display-ib option_image_td vrtcl-top">\
          <div class="h_if_'+cnt+' hdv" style="display: none;">\
            <input type="file" accept="image/*" name="prod_other_img[]" class="option_image_input vrtcl-mid">\
            <input type="hidden" name="prod_other_img_idx[]">\
            <a data-cnt="'+cnt+'" class="removeOptionValue remove_option_long" href="javascript:void(0)"><img alt="Remove" src="<?= base_url() ?>assets/images/icon-remove.png"></a>\
          </div>\
        </div> \
      </div>\
    </div>');
    $('.pd-8-12 > .main'+cnt).nextAll('.row'+cnt).remove();
}


function resetFirstSecondRowOptional(cnt){
  $('.combinationContainer').empty();
  noCombination = true;
  arraySelected = {}; 
  $('.main'+cnt+'_2nd_add').remove();
  $('.main'+cnt+'_2nd').empty();
  $('.main'+cnt+'_2nd').append('<div class="display-ib ">&nbsp;</div> \
    <div class="display-ib">\
    <input type="text" placeholder="Item property value" data-cnt="'+cnt+'" autocomplete="off" class="other_name_value otherNameValue'+cnt+'" name="prod_other[]">\
    </div>\
    <div style="display:none">\
    <input type="text" name="prod_other_id[]" value="">\
    </div>\
    <div class="display-ib">\
    <div class="h_if_'+cnt+' hdv" style="display: none;">\
    &#8369; <input type="text"   onkeypress="return isNumberKey(event)"  placeholder="Enter additional price (0.00)" id="price_field" autocomplete="off" name="prod_other_price[]">\
    </div>\
    </div>\
    <div class="display-ib vrtcl-top option_image_td">\
    <div class="h_if_'+cnt+' hdv" style="display: none;">\
    <input type="file" class="option_image_input vrtcl-mid" name="prod_other_img[]" accept="image/*">\
    <input type="hidden" name="prod_other_img_idx[]">\
    <a data-cnt="'+cnt+'" class="removeOptionValue remove_option_long" href="javascript:void(0)"><img src="<?= base_url() ?>assets/images/icon-remove.png"></a>\
    </div>\
    </div>'); 
}


function ReplaceNumberWithCommas(thisnumber){
        //Seperates the components of the number
        var n= thisnumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
      }


      function inArray(needle, haystack){
        for(j = 0, len = haystack.length; j<len; j++){
          if(arraysEqual(needle, haystack[j])){
            return j;
          }
        }
        return -1;
      }

      function arraysEqual(a, b) {
        if (a === b) 
          return true;
        if (a == null || b == null) 
          return false;
        if (a.length != b.length) 
          return false;
        for (var i = 0; i < a.length; ++i) {
          if (a[i] !== b[i]) 
            return false;
        }
        return true;
      }

      function addNewBrand(){
        $('#prod_brand').val(1)
        $('#prod_brand').trigger( "change" );
        //validateWhiteTextBox("#brand_sch");
        jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/img_new_txt.png" />').show().css('display','inline-block');
      }

      $(document).on('change',".quantityText", function () {

        var txtBox = this;
        var v = parseInt(txtBox.value);
        var count = $(this).data('cnt');
        if (isNaN(v) || v <= 0) {
          txtBox.value = 1;
        } else {
          txtBox.value = v;
        }

        arraySelected[count]['quantity'] = txtBox.value;

      });

      $(document).on('change','.qtyTextClass',function(){

        var v = parseInt(this.value);
        if (isNaN(v) || v <= 0) {
          if(isNaN(v)){

            this.value = '1';
          } else if(v <= 0){
            this.value = Math.abs(v);
          }
        } else {
          this.value = v;
        }


      });

</script>

      <div class="clear"></div>  


<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css" rel="stylesheet" />
<div class="wrapper">

<div class="clear"></div>

  <div class="seller_product_content">

    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
      <input type="hidden" id="uploadstep2_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
      <div class="sell_steps sell_steps2">
        <ul>
          <li><a href="javascript:void(0)" id="step1_link">Step 1 : Select Category</a></li>
          <li><span>Step 2: </span> Upload Item</li>                   
          <li>Step 3: Select Shipping Courier</li>
          <li>Step 4: Success</li>
        </ul>
      </div>
      <input type="hidden" name="step1_content" id="step1_content" value='<?php echo isset($step1_content)?$step1_content:json_encode(array());?>'/>
    <?php if(isset($product_details)): ?>  
        <?php echo form_open('sell/edit/step1', array('id'=>'edit_step1'));?>
            <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_details['id_product'];?>">
        <?php echo form_close();?>
    <?php else: ?>
        <?php echo form_open('sell/step1', array('id'=>'edit_step1'));?>
            <input type="hidden" name="c_id" id="c_id" value="<?php echo $id;?>">
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
        echo form_open('', $attr);
        ?>


        <table class="step4" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="3">
              <?php echo $parent_to_last; ?> <!-- will show the parent category until to the last category selected (bread crumbs) -->
            </td>
          </tr>
          
          <tr>
            <td colspan="3" class="upload_step2_title">
              <h3>Describe your item</h3> (<strong class="required">*</strong>) required fields
            </td>
          </tr>
          <tr>
            <td  class="border-left" style="width:130px">Product Name: <font color="red">*</font></td> <!-- Title of the product -->
            <td  class="border-right" colspan="2"><input type="text" maxlength="255" placeholder="Enter title" autocomplete="off" id="prod_title" maxlength="255" name="prod_title" value="<?php echo (isset($product_details['name']))?$product_details['name']:'';?>"></td>
          </tr>
          <tr>
            <td class="border-left" style="width:130px">Brand: <font color="red">*</font></td> <!-- Title of the product -->
            <td class="border-right" colspan="2">
              <input type = "hidden" id="prod_brand" name="prod_brand" value="<?php echo isset($product_details['brand_id'])?$product_details['brand_id']:0?>"/>
              <input type = "text" id="brand_sch" name="brand_sch" autocomplete="off" placeholder="Search for your brand" value="<?php echo isset($product_details['brandname'])?$product_details['brandname']:''?>"/>
              <div class="brand_sch_loading"></div>
              <div id="brand_search_drop_content" class="brand_sch_drop_content"></div>
            </td>
          </tr>

          <!-- Start Condition of the product -->
          <tr>
            <td class="border-left border-bottom">
              Condition: <font color="red">*</font>
            </td> 
            <td class="border-right border-bottom" colspan="2">
                <select name="prod_condition" id="prod_condition">
                  <option value="0">--Select Condition--</option>          
                  <?php foreach($this->lang->line('product_condition') as $x): ?>
                  <option value="<?php echo $x;?>" <?php if(isset($product_details['condition'])){echo ($product_details['condition'])===$x?'selected':'';}?>><?php echo $x; ?></option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <!-- end Condition of the product -->
          <tr>
            <td colspan="3">&nbsp;
            </td>
          </tr>
          <!-- Upload Image Content -->
          <tr>
              <td colspan="3" class="upload_step2_title"> 
                  <h3>Add Photos</h3> <span class="required">You are required to have a minimum of 1 photo</span>
              </td>
          </tr>
          <tr>
              <td class="border-left border-right border-bottom" colspan="4">
                <div class="inputfiles"> 
                   <span class="labelfiles">
                        <span class="add_photo span_bg"></span>Browse Photo
                   </span>
                   <br /><span class="label_bttm_txt">You may select multiple images</span>
                   <input type="file" id="files" class="files active" name="files[]" multiple accept="image/*" required = "required"  /><br/><br/>
                </div> 

 
                <!-- this output will show all selected from input file field from above. this is multiple upload. -->
                <output id="list">
                    <!-- IF EDIT FUNCTION -->
                    <?php $main_img_cnt = 0;?>
                    <?php if(isset($main_images)):?> 
                        <?php foreach($main_images as $main_image): ?>       
                            <div id="editpreviewlist<?php echo $main_img_cnt;?>" class="edit_img upload_img_div <?php echo ($main_img_cnt===0)?'active_img':'';?>">
                                <a href="javascript:void(0)" class="removepic"  data-imgid="<?php echo $main_image['id_product_image'];?>">x</a>
                                <span class="upload_img_con"><img src =<?php echo base_url().$main_image['path'].'categoryview/'.$main_image['file'];?> ></span>
                                <br>
                                <a class="makeprimary" href="javascript:void(0)" data-imgid="<?php echo $main_image['id_product_image'];?>"><?php echo ($main_img_cnt===0)?'Your Primary':'Make Primary';?></a>
                            </div> 
                            <?php $main_img_cnt++; ?>
                        <?php endforeach; ?>
                  <?php endif; ?>
                </output>
                <!-- end of output -->
              </td>
          </tr>
          <!-- end of upload image -->

          <tr>
            <td colspan="3">&nbsp;
            </td>
          </tr>
          <!--Start of Description -->
          <tr>
              <td  class="upload_step2_title" colspan="3">
                <h3>Add a description</h3> (<strong class="required">*</strong>) required fields
              </td>
          </tr>
          <tr>
              <td class="border-left" style="width:130px">Brief description: <font color="red"> *</font></td><!-- Brief of the product -->
              <td class="border-right" colspan="2"><input type="text" autocomplete="off" maxlength="255" placeholder="Enter brief description" id="prod_brief_desc" name="prod_brief_desc"  value="<?php echo (isset($product_details['brief']))?$product_details['brief']:'';?>"></td>
          </tr>
          <tr>
            <td class="border-left" valign="top">Product Details: <font color="red">*</font></td><!-- Main Description of the product --> 
            <td class="border-right pad-right" colspan="3"><textarea style="width: 98%;height:100%" name="prod_description" class="mceEditor"  id="prod_description" placeholder="Enter description..."><?php echo (isset($product_details['description']))?$product_details['description']:'';?></textarea></td>
          </tr> 
          <!-- end of Description -->

          <!-- start of keywords -->
          <tr>
            <td class="border-left">Keywords (separated by spaces)</td>
            <td class="border-right" colspan="3"><input type="text" autocomplete="off" maxlength="1024" name="prod_keyword" id="prod_keyword" placeholder="Enter keyword for you item" value="<?php echo (isset($product_details['keywords']))?$product_details['keywords']:'';?>"></td>
          </tr>
          <!-- end of keywords -->
        </table> 

        <table class="step4_2" style="width:100%" cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="border-left border-right" colspan="4">
                <p class="view_more_product_details blue"><span class="span_bg vmd_img"></span>View more product details</p>
              </td>
            </tr>
          </thead>

          <!-- Start hide of more product details -->
          <tbody class="more_product_details_container">
          <!-- Add item specifics -->
          <tr>
            <td class="border-left border-right" colspan="4">
              <h3> Add item specifics</h3> 
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
            <td class="border-left"><?php echo  ucwords(strtolower($attribute[$i]['cat_name'])); ?></td>
            <td class="border-right" colspan="3">
              
                        <?php 
                        if(isset($product_attributes_spe[$input_id_attr]))
                         $cat_attr = $product_attributes_spe[$input_id_attr];
                       else
                         $cat_attr = array();   
                              #Removed case formatting for input type values
                       switch ($input_type) {
                        case 'SELECT':
                        echo '<span><select name="'.$input_type.'_'.$input_cat_name.'">';
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
                        echo "<span><input type='text'  autocomplete='off' value='".$value."' name='".$input_type.'_'.$input_cat_name."' /></span>";
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
                        echo "<span><input type='radio' value='".$list['name']."' name='".$input_type.'_'.$input_cat_name."' ".$checked.">".$list['name']."</span>";              
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
                      echo "<span><input type='checkbox' class='checkbox_itemattr' data-group='".$input_cat_name_with_space."'   data-attrid='". $id_attribute_value."' data-value='".$list['name']."' data-attr='".$input_type.'_'.$input_cat_name.'_'.str_replace(' ', '', $list['name'])."' value='".$list['name']."' name='".$input_type.'_'.$input_cat_name."[]' ".$checked.">".$list['name']."</span>";
                    }
                    break;
                  }
                  ?>
               
            </td>
          </tr>
              <?php
              $array_name_inputs = $array_name_inputs .'|'. $input_type.'_'.$input_cat_name.'/'.$input_id_attr;

               }
              ?>
          <tr>
            <td class="border-left border-right" colspan="4">
              <h3> Additional Information to your Item</h3> 
            </td>
          </tr>

          <?php 
          $j = 1;
          if(!isset($product_attributes_opt))
            $product_attributes_opt = array();  

          foreach($product_attributes_opt as $key=>$opt_attr): ?>

          <tr id="<?php echo ($j === 1)?'':'main'.$j; ?>">
            <td class="border-left"> <?php echo ($j===1)?'Others: (Optional)':''; ?></td>  
            <td class="border-right" colspan="3">
              <input type="text" name="prod_other_name[]" data-cnt="<?php echo $j; ?>" class="prod_<?php echo $j;?> other_name_class" autocomplete="off" placeholder="Enter name" value="<?php echo str_replace("'", '', $key);?>"> 
            </td>
          </tr>

          <?php $k = 0;
          foreach($opt_attr as $prod_attr): ?>
          <tr>
            <td>&nbsp;</td>
            <?php if($k > 0):?>
            <td style="display:none">
              <span>
                <input type="text" value ="<?php echo str_replace("'", '',$key);?>" data-cnt="<?php echo $j;?>" class="prod_<?php echo $j;?>" name="prod_other_name[]">
              </span>
            </td>
            <?php endif; ?>
            <td>
              <input type="text"  class="other_name_value otherNameValue<?php echo $j?>" data-cnt="<?php echo $j;?>" name="prod_other[]" autocomplete="off" data-otherid="<?php echo $prod_attr['value_id'];?>" placeholder="Enter description" value="<?php echo $prod_attr['value']?>">
            </td>
            <td style="display:none">
              <input type="text" name="prod_other_id[]" value="<?php echo $prod_attr['value_id']; ?>">
            </td>

            <td>
              <div class="" style="display:block">
                &#8369; <input type="text" name="prod_other_price[]" autocomplete="off" id="price_field" placeholder="Enter additional price (0.00)" value="<?php echo $prod_attr['price'];?>">
              </div>
            </td>

            <td>
              <div>
                <input class="option_image_input" type="file" name="prod_other_img[]">
                  <?php if((trim($prod_attr['img_path'])!=='')&&(trim($prod_attr['img_file'])!=='')):?>
                  <img src="<?php echo base_url().$prod_attr['img_path'].'thumbnail/'.$prod_attr['img_file']; ?>" class="option_image">
                <?php endif;?>
              </div>
            </td>
          </tr>
        <?php $k++; endforeach; ?>

        <tr id="<?php echo 'main1';?>">
          <td class="border-left"></td>
          <td class="border-right" colspan="3">
            <a class="add_more_link_value" data-value="<?php echo $j;?>" href="javascript:void(0)">+Add more value</a>
          </td>
        </tr>     
        <?php $j++; endforeach;?>

        <?php if($j===1):?>
        <tr class="main1">
          <td class="border-left"> Others: (Optional) </td> 
          <td class="border-right" colspan="3">
            <input type="text" name="prod_other_name[]" data-cnt="<?php echo $j;?>" class="<?php echo 'prod_'.$j;?> other_name_class" autocomplete="off" placeholder="Enter name"> 
            <a href="javascript:void(0)" class="lnkClearFirst">Clear This Group</a>
          </td>
        </tr>
        <tr class="main1 main1_2nd">
          <td class="border-left">&nbsp;</td>
          <td>
            <input type="text" name="prod_other[]"  class="other_name_value otherNameValue1"  autocomplete="off" data-cnt="<?php echo $j;?>" placeholder="Enter description">
          </td>
          <td>
            <div class="<?php echo 'h_if_'.$j;?> hdv">
              &#8369; <input type="text" name="prod_other_price[]" autocomplete="off" id="price_field" placeholder="Enter additional price (0.00)">
            </div>
          </td>
          <td class="border-right">
            <div class="<?php echo 'h_if_'.$j;?> hdv">
              <input type="file" name="prod_other_img[]"  >
              <a href="javascript:void(0)" class="removeOptionValue" data-cnt ="<?php echo $j;?>">Remove</a>
            </div>
          </td>
        </tr> 
        <tr id="main1">
          <td class="border-left"></td>
          <td class="border-right" colspan="3">
            <a class="add_more_link_value" data-value="<?php echo $j;?>" href="javascript:void(0)">+Add more value</a>
          </td>
        </tr>
        <?php endif; ?>


        
        
        </tbody>
        <!-- End hide of more product details -->
        <tfoot>
          <tr class="prod-details-add-more-link">
            <td class="border-left border-right" colspan="4">  
              <a class="add_more_link" href="javascript:void(0)">+ Add More Optional</a>

              <?php if(isset($product_details)): ?>
                <input type="hidden" name="p_id" value="<?php echo $product_details['id_product']; ?>">
              <?php endif;?>
            </td>
          </tr>
          <tr>
            <td class="border-left border-bottom">&nbsp;</td>
            <td class="border-right border-bottom" colspan="3">&nbsp;</td>
          </tr> 

          <tr>
            <td>&nbsp;</td>
            <td colspan="3">&nbsp;</td>
          </tr>  
          <!-- Start of Price Content -->
          <tr>
              <td class="upload_step2_title" colspan="4">
                <h3>Price and Quantity</h3> (<strong class="required">*</strong>) required fields
              </td>
          </tr> 
          <tr>
              <td width="110px" class="border-left">Base Price <font color="red"> *</font></td>
              <td class="border-right" colspan="3"><input type="text" autocomplete="off" name="prod_price" id="prod_price" placeholder="Enter price (0.00)" value="<?php echo (isset($product_details['price']))?$product_details['price']:'';?>"></td>
          </tr>
          <!-- end of Price Content -->

          <!-- start of sku code -->
          <tr>
            <td class="border-left">SKU Code <font color="red">*</font></td> <!-- SKU of the product -->
            <td class="border-right" colspan="3">
              <input type="text" autocomplete="off"  maxlength="45" placeholder="Enter SKU" id="prod_sku" name="prod_sku" value="<?php echo (isset($product_details['sku']))?$product_details['sku']:'';?>">
              <a class="tooltips" href="javascript:void(0)"><img src="<?= base_url() ?>assets/images/icon_qmark.png" alt=""><span>Stock Keeping Unit: you can assign any code in order to keep track of your items</span></a>
            </td>
          </tr>
          <!-- end of sku code -->

          <tr>
            <td class="border-left border-right" colspan="4"><h3 class="orange">Quantity</h3></td>
          </tr>
          <tr>
            <td class="border-left border-bottom"></td>
            <td class="border-right border-bottom" colspan="3">
              <div class="quantity_table">
                <div class="quantity_table_row">               
                  <div class="qty_title">
                    <span>Quantity:</span><br />
                    <input type="text" class="qtyTextClass" id="qtyTextClass" name="quantity"> 
                    <a href="javascript:void(0)" data-value="1" class="quantity_attr_done orange_btn3">Add</a>
                    <a class="tooltips qty_tooltip quantity_attr_done" href="javascript:void(0)" style="display:none"><img src="<?= base_url() ?>assets/images/icon_qmark.png" alt=""> <span> You can also set the availability of different attribute combinations of your item by clicking the Add button</span></a> 
                  </div>
                  <div class="quantity_attrs_content" id="quantity_attrs_content2"></div>
                </div>
              </div>
              <div class="clear"></div>
              <div class="combinationContainer"></div>
              <div class="clear"></div>

            </div>  
            <div class="clear"></div>
            <div class="quantity_table2">
            </div> 
            <?php echo form_close();?>

              <?php 
              $attributesForm = array('id' => 'hidden_form',
                'name'=>'hidden_form');
              echo form_open('sell/step3', $attributesForm);
              ?>
              <input type="hidden" name="prod_h_id" id="prod_h_id"> 
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
                    <div class="loader_div"><img src='<?php echo base_url().'assets/images/orange_loader.gif' ?>'></div>              
                </div>  
            </td>
          </tr>
          <tr>
            <td>
            
            
            </td> 
          </tr>
</tfoot>
</table> 

<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type="text/javascript">
  $('.view_more_product_details').on('click', function() {
    $('.more_product_details_container,.prod-details-add-more-link').slideToggle();
    $('.view_more_product_details').toggleClass('active-product-details');

  });

</script>

<script type="text/javascript">
$(document).ready(function(){
  var removeThisPictures = [];
  var pictureCount = 0;
  var primaryPicture = 0;
  var qtycnt = 1;
  var countSelected = 1;
  var arrayVar = [];
  var combination = []; 
  var arrayCombination = []; 
  var arraySelected = {};  
  
       
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
  var noCombination = true;


  function getCombination(array)
  {
    var result = [""]; 
    var resultHtml = "";
    var counter = 1;
    arrayCombination = []; 
    for (var i=0; i<array.length; i++) {
      var ai = array[i],
      l = ai.length;
      result = $.map(result, function(r) { 
        var ns = [];  
        for (var j=0; j<l; j++)  

          ns[j] = r + ai[j] +  '|-|';

        return ns;
      });  
    }

    for (var i = 0; i < result.length; i++) {
      var combo = result[i].split('|-|');
      var tempSpanHtml = "";
      var tempDivHtml = "";
      var value = "";
      for (var x = 0; x < combo.length; x++) {
        var comboSplit = combo[x].split('=');
        if(x !== combo.length - 1){
          tempSpanHtml += '<span class="displayPopUpItem"><b>'+ comboSplit[0] +'</b>: <span style="color:red">'+ comboSplit[1] +'</span></span>';
          value += comboSplit[2] + "-" ;
        }

      };
      dataHtml = '<div class="displaySelected display'+counter+'" >'+tempSpanHtml+'</div><br>';
      tempDivHtml += "<div class='displayPopUp displayPop"+counter+"' style='border:solid 1px;width:200px;margin:10px;padding:10px'><input type='radio' data-cnt='"+counter+"' data-html='"+dataHtml+"'   name='combinationSet' value='"+value.substring(0, value.length - 1)+"'>" +tempSpanHtml+"</div><br>";
      arrayCombination.push(tempDivHtml);
      counter++;
    }   
    return arrayCombination;
  }

  function print_r(arr,level)
  {
    var dumpedText = "";
    if(!level) level = 0;
    var levelPadding = "";
    for(var j=0;j<level+1;j++) levelPadding += "    ";
      if(typeof(arr) == 'object') {  
        for(var item in arr) {
          var value = arr[item];
          if(typeof(value) == 'object') {  
            dumpedText += levelPadding + "'" + item + "' ...\n";
            dumpedText += print_r(value,level+1);
          } else {
            dumpedText += levelPadding + "'" + item + "' => \"" + value + "\"\n";
          }
        }
      } else {  
        dumpedText = "===>"+arr+"<===("+typeof(arr)+")";
      }

      return dumpedText;
    }


    // document.getElementById('files').addEventListener('change', handleFileSelect, false);
    function handleFileSelect(evt)
    {
      var files = evt.target.files;  
      $('#list').empty();
      for (var i = 0, f; f = files[i]; i++) {

        if (!f.type.match('image.*')) {
          continue;
        }
        var reader = new FileReader();
        reader.onload = (function(theFile) {
          return function(e) { 
            var span = document.createElement('span');
            span.innerHTML = ['<img class="thumb" src="', e.target.result,
            '" title="', escape(theFile.name), '"/>'].join('');
            document.getElementById('list').insertBefore(span, null);
          };
        })(f);
        reader.readAsDataURL(f);
      }
    }

    function validateRedTextBox(idclass)
    {
      $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
        "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
        "box-shadow": "0px 0px 2px 2px #FF0000"});
    } 
    function validateWhiteTextBox(idclass)
    {
      $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
        "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
        "box-shadow": "0px 0px 2px 2px #FFFFFF"});
    }

    function removeSelected(row)
    {
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
    $('.main'+cnt).append('<td class="border-left">'+title+'</td> \
      <td  colspan="3"> \
      <input type="text" placeholder="Enter name" autocomplete="off" class="prod_'+cnt+' other_name_class" data-cnt="'+cnt+'" name="prod_other_name[]"> \
      '+link+' \
      </td>')
    }

    function resetFirstSecondRowOptional(cnt){
      $('.combinationContainer').empty();
       noCombination = true;
       arraySelected = {}; 
       $('.main'+cnt+'_2nd_add').remove();
       $('.main'+cnt+'_2nd').empty();
       $('.main'+cnt+'_2nd').append('<td class="border-left">&nbsp;</td> \
        <td>\
        <input type="text" placeholder="Enter description" data-cnt="'+cnt+'" autocomplete="off" class="other_name_value otherNameValue'+cnt+'" name="prod_other[]">\
        </td>\
        <td>\
        <div class="h_if_'+cnt+' hdv" style="display: none;">\
        ₱ <input type="text" placeholder="Enter additional price (0.00)" id="price_field" autocomplete="off" name="prod_other_price[]">\
        </div>\
        </td>\
        <td>\
        <div class="h_if_'+cnt+' hdv" style="display: none;">\
        <input type="file" name="prod_other_img[]">\
        <a data-cnt="'+cnt+'" class="removeOptionValue" href="javascript:void(0)">Remove</a>\
        </div>\
        </td>'); 
    }

    // remove optional

    
    $(document).on('click',".lnkClearFirst",function (){
  
      resetFirstOptional(1);
      resetFirstSecondRowOptional(1);
      var cnt = 1;
      var formatHeadValue = $.trim($('.prod_'+cnt).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
      var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
      }); 

      $('.main'+cnt+'_2nd').each(function(){
        var selfValue = $(this).find('td > .otherNameValue'+cnt).val();
        var value = selfValue+headValue; 
        var idHtmlId = headValue.replace(/ /g,'')+'Combination';
        $("#"+idHtmlId+" option[data-temp="+value+"]").remove(); 
        if($('#'+idHtmlId).has('option').length <= 0){
          $('#div'+idHtmlId).remove();
        }

      });

      $('.main'+cnt+'_2nd_add').each(function(){
        var selfValue = $(this).find('td > .otherNameValue'+cnt).val();
        var value = selfValue+headValue; 
        var idHtmlId = headValue.replace(/ /g,'')+'Combination';
        $("#"+idHtmlId+" option[data-temp="+value+"]").remove(); 
        if($('#'+idHtmlId).has('option').length <= 0){
          $('#div'+idHtmlId).remove();
        }
      });
    resetFirstOptional(1);
      resetFirstSecondRowOptional(1);

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
 
     var selfValue = $.trim($(this).closest('tr').find('.otherNameValue'+cnt).val());
     var value = selfValue+headValue; 
     var idHtmlId = headValue.replace(/ /g,'')+'Combination';
     $("#"+idHtmlId+" option[data-temp="+value+"]").remove(); 
     if($('#'+idHtmlId).has('option').length <= 0){
      $('#div'+idHtmlId).remove();
     }

     if($(this).closest("tr").hasClass('main'+cnt+'_2nd')){
       if ($(".main"+cnt+"_2nd_add").length > 0){
         $(this).closest("tr").remove();
         $('.main'+cnt+'_2nd_add:first').removeClass("main"+cnt+"_2nd_add").addClass("main"+cnt+"_2nd");
       }else{
         resetFirstOptional(cnt);
         resetFirstSecondRowOptional(cnt);
       }
     }else{
      $(this).closest("tr").remove();
    }

    });


    $(document).on('click',".removeOptionGroup",function (){

      var cnt = $(this).data("cnt");
     var formatHeadValue = $.trim($('.prod_'+cnt).val().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' '));
     var headValue = formatHeadValue.toLowerCase().replace(/\b[a-z]/g, function(letter) {
      return letter.toUpperCase();
    }); 

      $('.main'+cnt+'_2nd').each(function(){
        var selfValue = $(this).find('td > .otherNameValue'+cnt).val();
        var value = selfValue+headValue; 
        var idHtmlId = headValue.replace(/ /g,'')+'Combination';
        $("#"+idHtmlId+" option[data-temp="+value+"]").remove(); 
        if($('#'+idHtmlId).has('option').length <= 0){
          $('#div'+idHtmlId).remove();
        }

      });

      $('.main'+cnt+'_2nd_add').each(function(){
        var selfValue = $(this).find('td > .otherNameValue'+cnt).val();
        var value = selfValue+headValue; 
        var idHtmlId = headValue.replace(/ /g,'')+'Combination';
        $("#"+idHtmlId+" option[data-temp="+value+"]").remove(); 
        if($('#'+idHtmlId).has('option').length <= 0){
          $('#div'+idHtmlId).remove();
        }
        });


     $('.combinationContainer').empty();
     noCombination = true;
     arraySelected = {}; 
      $('.main'+cnt).remove();
      $('.main'+cnt+'_link').remove();
    });


    // ES_UPLOADER BETA
    $(".labelfiles").click(function(){
        $('.files.active').trigger('click'); 
    });
 
    $(document).on('change',".files",function (e){
     var fileList = this.files;
     var anyWindow = window.URL || window.webkitURL;
     for(var i = 0; i < fileList.length; i++){
      var objectUrl = anyWindow.createObjectURL(fileList[i]);
      var primaryText = "Make Primary";
      var activeText = "";
      pictureInDiv = $("#list > div").length;
      if(pictureInDiv == 0){
        primaryText = "Your Primary";
        activeText = "active_img";
        primaryPicture = pictureCount;

      }
      $('#list').append('<div id="previewList'+pictureCount+'" class="upload_img_div '+activeText+'"><span class="upload_img_con"><img src="'+objectUrl+'"></span><a href="javascript:void(0)" class="removepic" data-number="'+pictureCount+'">x</a><br><a href="javascript:void(0)" class="makeprimary photoprimary'+pictureCount+'" data-number="'+pictureCount+'">'+primaryText+'</a></div>');
      window.URL.revokeObjectURL(fileList[i]);
      pictureCount++;
    }

    $(".files").hide(); 
    //Wrapped in EACH just in case there are more than one active .files elements: unlikely but just in case
    $(".files.active").each(function(){
        $(this).removeClass('active');
    });
    $('.inputfiles').append('<input type="file" class="files active" name="files[]" multiple accept="image/*" required = "required"  /> ')

  });

    $(document).on('click',".removepic",function (){

      var idNumber = $(this).data('number');
      var text = $(".photoprimary"+idNumber).text();
      if(text == "Your Primary"){

        $('#previewList'+idNumber).remove();
        removeThisPictures.push(idNumber);
        primaryPicture = $("#list > div:first-child > .makeprimary" ).data('number');
        $("#list > div:first-child > .makeprimary").text('Your Primary');     

      $("#list > div:first-child").addClass("active_img"); 
      }else{
        $('#previewList'+idNumber).remove();
        removeThisPictures.push(idNumber);
      }
    });

    $(document).on('click','.makeprimary',function(){

      var idNumber = $(this).data('number');
      primaryPicture = idNumber;  
      $(".makeprimary").text('Make Primary');
      $(".photoprimary"+idNumber).text('Your Primary');

      $(".upload_img_div").removeClass("active_img");
      $("#previewList"+idNumber).addClass("active_img");
    });
   
    
    // ES_UPLOAER BETA END
    
    

    $( ".option_image_input" ).change(function(){
      $(this).siblings(".option_image").css('display','none');
    });

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
      htmlEach += '<div class="input_qty"><input type="textbox" class="quantityText" value="'+qtyTextbox.val()+'" data-cnt="'+thisValueCount+'"></div><div class="mid_inner_con_list">';

      $(function() { 
        $('.quantity_attrs_content option:selected').each(function(){
          haveValue = true;
          noCombination = false;
          var eachValue = $(this).val();
          var eachValueString = $(this).text();
          var eachGroup = $(this).data('group');  
          var eachDataValue = $(this).data('value');  
          combinationVal.push(eachDataValue+':'+eachValue);
          htmlEach += '<div>'+ eachGroup +': ' + eachValueString +'</div>';
        });

        if(isNaN(qtyTextboxValue) ||  qtyTextboxValue <= 0){ 
          qtyTextbox.val('1');
        }

        sortCombination = combinationVal.sort();

        for (var i = 0; i < sortCombination.length; i++) {
          arrayCombinationString += sortCombination[i] + '-';
        };

        for (var key in arraySelected) { 
          if (arraySelected.hasOwnProperty(key))
            if(arraySelected[key]['value'] === arrayCombinationString.slice(0, - 1)){
             alreadyExist = true;
             break;
           }
         }
       });

if(haveValue === true){
  if(alreadyExist === false){

   $('.combinationContainer').append('<div class="inner_quantity_list innerContainer'+thisValueCount+'"> '+ htmlEach +'</div> <a href="javascript:void(0)" class="removeSelected" data-row="'+thisValueCount+'"   style="color:Red">Remove</a></div>');
   dataCombination['quantity'] = qtyTextbox.val();
   dataCombination['value'] = arrayCombinationString.slice(0, - 1);
   arraySelected[thisValueCount] = dataCombination;
   thisValueCount++;
   $(this).data('value',thisValueCount);
 }else{
  alert('Combination Already Selected!');
  return false;
} 
}
// console.log(arraySelected);

});

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
    this.value = '1';
  } else {
    this.value = v;
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
        noCombination == true
      }else{
        $('.quantity_attr_done').show();
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
 var selfValue = $.trim($(this).val());
 var value = selfValue+headValue;
 $(this).data('temp','"'+value+'"');
 var attrVal = $(this).val();
 
 var idHtmlId = headValue.replace(/ /g,'')+'Combination';
 
 
 if(formatHeadValue.length >0){
  $("#"+idHtmlId+" option[data-temp="+temp+"]").remove();
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
              noCombination == true
            }else{
              $('.quantity_attr_done').show();
            }
          }


        }); 


$(".checkbox_itemattr").click(function(){
  addAttrQtySelection($(this));
});

$(".add_more_link").unbind("click").click(function(){
  cnt_o++;
  $('.step4_2').append('<tr id="main'+cnt_o+'" class="main'+cnt_o+'"><td class="border-left"></td><td class="border-right" colspan="3"><input type="text" data-cnt="'+cnt_o+'" autocomplete="off" name="prod_other_name[]" class="prod_'+cnt_o+' other_name_class" placeholder="Enter name"><a href="javascript:void(0)" data-cnt="'+cnt_o+'" class="removeOptionGroup">Remove This Group</a></td></tr><tr class="main'+cnt_o+' main'+cnt_o+'_2nd"><td class="border-left"></td><td><input type="text" autocomplete="off" data-cnt="'+cnt_o+'" class="other_name_value otherNameValue'+cnt_o+'" name="prod_other[]" placeholder="Enter description"></td><td> <div class="h_if_'+cnt_o+' hdv"  style="display:none">&#8369; <input type="text" name="prod_other_price[]"  class="price_text"   id="price_field"  autocomplete="off" placeholder="Enter additional price (0.00)"></div></td><td class="border-right"> <div class="h_if_'+cnt_o+' hdv" style="display:none"><input type="file" name="prod_other_img[]" ><a data-cnt="'+cnt_o+'" class="removeOptionValue" href="javascript:void(0)">Remove</a></div></td></tr><tr id="main1" class="main'+cnt_o+'_link"><td class="border-left"></td><td class="border-right" colspan="3"><a class="add_more_link_value" data-value="'+cnt_o+'" href="javascript:void(0)">+Add more value</a></td></tr>');
});

$('.upload_input_form').on('click', '.add_more_link_value', function() {
  var data =   $(this).data( "value" );   
  $(".h_if_"+data).css("display", "block");
  var attr = $('.prod_'+data).val();

  var  subClass = "main"+data+"_2nd_add";
 
  var newrow = $('<tr class="main'+data+' '+subClass+'"><td class="border-left"></td><td style="display:none"><span ><input type="text" value ="'+attr+'" data-cnt="'+data+'" class="prod_'+data+'" name="prod_other_name[]"></span></td><td><input type="text" autocomplete="off" data-cnt="'+data+'" class="other_name_value otherNameValue'+data+'"  name="prod_other[]" placeholder="Enter description"></td><td>&#8369; <input type="text" name="prod_other_price[]"  id="price_field" class="price_text"  autocomplete="off" placeholder="Enter additional price (0.00)"></td><td class="border-right"><input type="file" name="prod_other_img[]" ><a data-cnt="'+data+'" class="removeOptionValue" href="javascript:void(0)">Remove</a></td></tr>');
  if (data == 1){
    $('#main'+data).before(newrow);
  }else{
    $('#main'+data).after(newrow);
  }
});

$(document).on('change',"#price_field,#prod_price, .price_text",function () {
  //var priceval = this.value.replace(',','');
  var priceval = this.value.replace(new RegExp(",", "g"), '');
  var v = parseFloat(priceval);
  if (isNaN(v)) {
    this.value = '';
  } else {
    this.value = ReplaceNumberWithCommas(v.toFixed(2));
  }
  
  function ReplaceNumberWithCommas(thisnumber){
  //Seperates the components of the number
  var n= thisnumber.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
  }
  
});

$( "#prod_title,#prod_brief_desc,#prod_price,#prod_sku,#prod_condition" ).blur(function() {
  var value = $(this).val();
  var id = $(this).attr('id');

  if(value == "0" || value == ""){
    validateRedTextBox("#"+id);
  }else{
    validateWhiteTextBox("#"+id);
  }
});

$( "#brand_sch,#prod_title,#prod_brief_desc,#prod_price,#prod_sku,#qtyTextClass" ).keypress(function() {
  var id = $(this).attr('id');
  validateWhiteTextBox("#"+id);
});

$(document).on('change',"#prod_condition",function () {
  var id = $(this).attr('id');
  validateWhiteTextBox("#"+id);
});


$(".proceed_form").unbind("click").click(function(){
  tinyMCE.triggerSave();
  var description = tinyMCE.get('prod_description').getContent();
  var id = "<?php echo $id; ?>"; 
  var input_name = "<?php echo (string)$array_name_inputs; ?>";
  var action = "sell/processing"; 
  var title = $("#prod_title");
  var brief = $("#prod_brief_desc"); 
  var formData = new FormData(document.getElementById("form_product"));
  var combinationSelected = JSON.stringify(arraySelected);
  var otherCategory = "<?php echo isset($otherCategory)?$otherCategory:''; ?>";
  formData.append("inputs", input_name);
  formData.append("id", id);
  formData.append("combination",combinationSelected);
  formData.append("desc",description);
  formData.append("noCombination",noCombination);
  formData.append("otherCategory",otherCategory);
  formData.append("removeThisPictures",JSON.stringify(removeThisPictures));
  formData.append("primaryPicture",primaryPicture);
  formData.append("editRemoveThisPictures",JSON.stringify(editRemoveThisPictures));
  formData.append("editPrimaryPicture",editPrimaryPicture);

  var csrftoken = $('#uploadstep2_csrf').val();
  formData.append('es_csrf_token', csrftoken);

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

  if(brief.val().length == 0){
    $( "#prod_brief_desc" ).focus();
    validateRedTextBox("#prod_brief_desc");
  }

  if(price.val().length == 0){
    $( "#prod_price" ).focus();
    validateRedTextBox("#prod_price");
  }

  if(sku.val().length == 0){
    $( "#prod_sku" ).focus();
    validateRedTextBox("#prod_sku");
  }

  if(brand.val() == "0"){
    validateRedTextBox("#brand_sch");
  }else{
    validateWhiteTextBox("#brand_sch");
  }

  if(condition.val() == "0"){
    validateRedTextBox("#prod_condition");
  }else{
    validateWhiteTextBox("#prod_condition");
  }

  if(combinationQuantity.val() <= 0){
    alert('0 Quantity is Invalid!');
    return false;
  }

  if(title.val().length == 0 || brief.val().length == 0 || description.length == 0 || price.val().length == 0 || sku.val().length == 0 || brand.val() == "0"){
    alert("Fill (*) All Required Fields Properly!");
    return false;
  }else{
   var pricevalue = price.val().replace(new RegExp(",", "g"), '');
     if(pricevalue <= 0 || !$.isNumeric(pricevalue)){
      alert("Invalid Price. Price should be numeric!");
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
        formData.append("quantitySolo",quantity.val());
      }

      if(<?php echo json_encode((isset($is_edit))?$is_edit:false); ?>){

        $.ajax({
          async: false,
          type: "POST",
          url: '<?php echo base_url();?>' + 'sell/edit/processing2',
          mimeType:"multipart/form-data",
          contentType: false,
          cache: false,
          processData:false,
          data: formData, 
          dataType: "json",
          beforeSend: function(jqxhr, settings) {  
            $( ".button_div" ).hide();
            $( ".loader_div" ).show();
          },
          success: function(data) {
            if (data.e == 1) {
              $('#prod_h_id').val(data.d);
              $('#hidden_form').submit();
            } else {
             $( ".button_div" ).show();
             $( ".loader_div" ).hide();
             alert(data.d);
           }
         }
       });

      }else{

        $.ajax({
          async: false,
          type: "POST",
          url: '<?php echo base_url();?>sell/processing',
          mimeType:"multipart/form-data",
          contentType: false,
          cache: false,
          processData:false,
          data: formData , 
          dataType: "json",
          beforeSend: function(jqxhr, settings) {  
            $( ".button_div" ).hide();
            $( ".loader_div" ).show();
          },
          success: function(d) {
            if (d.e == 1) {
              $('#prod_h_id').val(d.d);
              $('#hidden_form').submit();
            } else {
              $( ".button_div" ).show();
              $( ".loader_div" ).hide();
              alert(d.d);
            }
          }
        });
      }

    }
  }
});

    /*
     * Product Edit javascript
     * Sam Gavinio
     */

    
    var itemPrice = parseInt($('#prod_price').data('price'));
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
            if($.inArray($(this).val(), $this.attr_lookuplist_item_id) !== -1){
              $(this).attr("selected",true);
            }
            //Optional product attributes
            else if($(this).attr('data-otherid')!=='undefined'){
              var idx = $.inArray($(this).attr('data-otherid'), $this.product_attribute_ids);
              if(idx !== -1){
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
        noCombination == true
      }else{
        $('.quantity_attr_done').show();
      }
    }

    //Marked for refactoring
    //Duplicate code: see $('.quantity_attr_done') click handler
  //$(function(){}) removed and $('.quantity_attrs_content option :select') replaced with if statement
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
        combinationVal.push(eachDataValue+':'+eachValue);
        htmlEach += '<div>'+ eachGroup +': ' + eachValueString +'</div>';
      }
    });

    if(isNaN(qtyTextboxValue) ||  qtyTextboxValue <= 0){ 
     qtyTextbox.val('1');
   }

   sortCombination = combinationVal.sort();

   for (var i = 0; i < sortCombination.length; i++) {
     arrayCombinationString += sortCombination[i] + '-';
   };

   for (var key in arraySelected) { 
     if (arraySelected.hasOwnProperty(key))
      if(arraySelected[key]['value'] === arrayCombinationString.slice(0, - 1)){
       alreadyExist = true;
       break;
     }
   }

   if(haveValue === true){
     if(alreadyExist === false){      
      $('.combinationContainer').append('<div class="inner_quantity_list innerContainer'+thisValueCount+'"> '+ htmlEach +'</div> <a href="javascript:void(0)" class="removeSelected" data-row="'+thisValueCount+'"   style="color:Red">Remove</a></div>');
      dataCombination['quantity'] = qtyTextbox.val();
      dataCombination['value'] = arrayCombinationString.slice(0, - 1);
      arraySelected[thisValueCount] = dataCombination;
      thisValueCount++;
      $('.quantity_attr_done.orange_btn3').data('value', thisValueCount);
    }else{
      alert('Combination Already Selected!');
      return false;
    }
  }
}

}); 
</script>
<script src="<?php echo base_url(); ?>assets/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript">
tinymce.init({
 mode : "specific_textareas",
 editor_selector : "mceEditor",
 //selector: "textarea",
  menubar: "table format view insert edit",
  statusbar: false,
  //selector: "textarea",

  statusbar: false,
  height: 300,
  plugins: [
  "lists link preview",
  "table jbimages fullscreen"
  //"advlist autolink link image lists charmap print preview hr anchor pagebreak",
  //"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
  //"table contextmenu directionality emoticons paste textcolor responsivefilemanager"
  ],  
  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
  //toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | image_advtab: true ",  
  relative_urls: false,
  
  //external_filemanager_path:"/assets/filemanager/",
  //filemanager_title:"Responsive Filemanager" ,
  //external_plugins: { "filemanager" : "/assets/filemanager/plugin.min.js"}
  
  
   setup: function(editor) {
        editor.on('change', function(e) {
            $('#prod_description').val(tinyMCE.get('prod_description').getContent());
            $('#prod_description').trigger( "change" );
        });
    }

});

tinymce.init({
    mode : "specific_textareas",
    editor_selector : "mceEditor_attr",
    //selector: "textarea",
    menubar: "table format view insert edit",

    statusbar: false,
    height: 200,
    plugins: [
        "lists link preview ",
        "table jbimages fullscreen" 
    ],  
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
    relative_urls: false
});


$(document).ready(function() { 
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
    if(typeof prev_content.condition !== "undefined"){
        $('#prod_condition').val(prev_content.prod_condition); //unsure
    }
    if(typeof prev_content.prod_keyword !== "undefined"){
        $('#prod_keyword').val(prev_content.prod_keyword);
    }
    if(typeof prev_content.prod_price !== "undefined"){
        $('#prod_price').val(prev_content.prod_price);
    }
    if(typeof prev_content.prod_sku !== "undefined"){
        $('#prod_sku').val(prev_content.prod_sku);
    }



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
      jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/check_icon.png" />').show().css('display','inline-block');
   }
   
   $('#brand_search_drop_content').on('click', 'li.brand_result', function(){
        $this = $(this);     
        $('#prod_brand').val($this.data('brandid'));
        $("#brand_sch").val($this.children('a').text())
        $('#prod_brand').trigger( "change" );
        $("#brand_sch").trigger( "change" );
        jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/check_icon.png" />').show().css('display','inline-block');
        $('#brand_search_drop_content').hide();
   });
})


$(document).ready(function(){
    var currentRequest = null;
    $( "#brand_sch" ).keyup(function() {
        $('#prod_brand').val(0)
        $('#prod_brand').trigger( "change" );
        jQuery(".brand_sch_loading").hide();
        var searchQuery = $(this).val();
        var csrftoken = $('#uploadstep2_csrf').val();
        if(searchQuery != ""){
            currentRequest = jQuery.ajax({
                type: "POST",
                url: '<?php echo base_url();?>product/searchBrand', 
                onLoading:jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/orange_loader_small.gif" />').show().css('display','inline-block'),
                data: "data="+searchQuery+"&es_csrf_token="+csrftoken, 
                beforeSend : function(){       
                    $("#brand_search_drop_content").empty();
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                    $('.brand_sch_drop_content').show();
                },
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    var html = '<ul>';
                    if((obj.length)>0){
                        jQuery.each(obj,function(){
                            html += '<li class="brand_result" data-brandid="'+(this.id_brand) +'"><a href="javascript:void(0)">'+(this.name)+'</a></li>' ;                             
                        });
                    }
                    else{
                       html = '<li> No results found </li>';
                    }
                    
                    html += '<li class="add_brand blue">Use your own brand name</li></ul>';
                    jQuery(".brand_sch_loading").hide();
                    $("#brand_search_drop_content").html(html);
                }
            });
        }
    });
    
    $(document).on("click",".add_brand", function(){
        $('#prod_brand').val(1)
        $('#prod_brand').trigger( "change" );
        if(currentRequest != null) {
            currentRequest.abort();
        }
        jQuery(".brand_sch_loading").html('<img src="<?= base_url() ?>assets/images/check_icon.png" />').show().css('display','inline-block');
        $('#brand_search_drop_content').hide();
    });

});



</script>

<script>
     $(document).ready(function() { 

        $('#brand_sch').focus(function() {
        $('#brand_search_drop_content').show();
        $(document).bind('focusin.brand_sch_drop_content click.brand_sch_drop_content',function(e) {
            if ($(e.target).closest('#brand_search_drop_content, #brand_sch').length) return;
            $('#brand_search_drop_content').hide();
            });
         });

        $('#brand_search_drop_content').hide();
    });
</script>
<script>

    $(function(){
        $('#step1_link').on('click', function(){
            $('#edit_step1').submit();
        });

    });
    
</script>

<div class="clear"></div>  


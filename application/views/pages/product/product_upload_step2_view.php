<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css" rel="stylesheet" />
<div class="wrapper">

  <div class="clear"></div>

  <div class="seller_product_content">

    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
	  <input type="hidden" id="uploadstep2_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">
      <div class="sell_steps sell_steps2">
        <ul>
          <li class="steps_item"><a href="#">Step 1 : Select Category</a></li>
          <li><a href="#">Step 2 : Upload Item</a></li>
          <li><a href="#">Step 3: Success</a></li>                    
                    <!-- <li><a href="#">Step 3: Select Shipping Courier</a></li>
                    <li><a href="#">Step 4: Success</a></li> -->
                  </ul>
                </div>


                <div class="clear"></div>

                <div class="upload_input_form form_input">
                  <!--<form class="form_product" id="form_product" name="form_product" enctype="multipart/form-data" method="POST" action="upload_step4_2">-->
				  <?php 
					$attr = array(
								'class' => 'form_product',
								'id' => 'form_product',
								'name' => 'form_product',
								'enctype' => 'multipart/form-data'
							);
					echo form_open('', $attr);
				  ?>


                    <table class="step4">

                      <tr>
                        <td colspan="3">
                          <?php echo $parent_to_last; ?> <!-- will show the parent category until to the last category selected (bread crumbs) -->
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">
                          <p class="notification"> <img src="<?= base_url() ?>assets/images/img_notification.png"> (<strong >*</strong>) required fields</p>
                          <h3  class="orange"> Describe your item</h3> 
                        </td>

                      </tr>
                      <tr>
                        <td  style="width:130px">Title <font color="red">*</font></td> <!-- Title of the product -->
                        <td colspan="2"><input type="text" placeholder="Enter title" autocomplete="off" id="prod_title" name="prod_title" value="<?php echo (isset($product_details['name']))?$product_details['name']:'';?>"></td>
                      </tr>
                      <tr>


                        <tr>
                          <td  style="width:130px">Brand <font color="red">*</font></td> <!-- Title of the product -->
                          <td colspan="2">
                            <select name="prod_brand" id="prod_brand">
                              <option value="0">--Select Brand--</option>
                              <?php 
                              foreach ($brand as $key) {
                                ?>
                                <option value="<?php echo $key['brand_id'] ?>" <?php if(isset($product_details['brand_id'])){echo ($product_details['brand_id'])===$key['brand_id']?'selected':'';}?>><?php echo $key['name'] ?></option>
                                <?php } ?>
                              </select>
                            </td>
                          </tr>

                          <td>Condition   
                           <font color="red">*</font></td> <!-- Condition of the product -->
                           <td colspan="2">
                            <select name="prod_condition" id="prod_condition">
                              <option value="0">--Select Condition--</option>          
                              <?php foreach($this->lang->line('product_condition') as $x): ?>
                              <option value="<?php echo $x;?>" <?php if(isset($product_details['condition'])){echo ($product_details['condition'])===$x?'selected':'';}?>><?php echo $x; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3">
                          <h3 class="orange"> Add item specifics</h3> 
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
                          <td><?php echo  ucwords(strtolower($attribute[$i]['cat_name'])); ?></td>
                          <td colspan="2">
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
                             echo "<textarea name='".$input_type.'_'.$input_cat_name."' style='width: 100%;height:100%' class='mceEditor' >".$value."</textarea></span>";
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

              </table> 

              <table class="step4_2" style="width:100%">
                <tr>
                  <td colspan="3" >
                    <h3 class="orange"> Add a description</h3> 
                  </td>
                </tr>
                <tr>
                  <td  style="width:130px">Brief description <font color="red"> *</font></td><!-- Brief of the product -->
                  <td colspan="2"><input type="text" autocomplete="off" placeholder="Enter brief description" id="prod_brief_desc" name="prod_brief_desc"  value="<?php echo (isset($product_details['brief']))?$product_details['brief']:'';?>"></td>
                </tr>
                <tr>
                  <td valign="top">Description <font color="red">*</font></td><!-- Main Description of the product --> 
                  <td colspan="3"><textarea style="width: 100%;height:100%" name="prod_description" class="mceEditor"  id="prod_description" placeholder="Enter description..."><?php echo (isset($product_details['description']))?$product_details['description']:'';?></textarea></td>
                </tr> 
                <tr>
                  <td>SKU Code <font color="red">*</font></td> <!-- SKU of the product -->
                  <td colspan="2">
                    <input type="text" autocomplete="off" placeholder="Enter SKU" id="prod_sku" name="prod_sku" value="<?php echo (isset($product_details['sku']))?$product_details['sku']:'';?>">
                    <a class="tooltips" href="javascript:void(0)"><img src="<?= base_url() ?>assets/images/icon_qmark.png" alt=""><span>Stock Keeping Unit: you can assign any code in order to keep track of your items</span></a>
                  </td>
                </tr>

                <tr>
                  <td colspan="3"> 
                    <h3 class="orange"> Image of your item</h3> (Select Multiple)
                  </td>
                </tr>
                <tr>

                  <td colspan="4">

                    <input type="file" id="files" name="files[]" multiple accept="image/*" required = "required"  /><br/>
                    <div style="display:inline">

                      <?php if(isset($main_images)):?> <!-- IF EDIT FUNCTION -->
                      <div>
                        <?php foreach($main_images as $main_image): ?>
                        <div class="prod_upload_img_container">
                          <img src =<?php echo base_url().$main_image['path'].'thumbnail/'.$main_image['file'];?> >
                          <input type="checkbox" class="prev_img" name="main_image[<?php echo $main_image['id_product_image'];?>]"/> Remove
                        </div> 
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>

                <output id="list"></output> <!-- this output will show all selected from input file field from above. this is multiple upload. -->

              </td>
            </tr>
            <tr>
              <td colspan="3">
                <h3 class="orange"> Price</h3> 
              </td>
            </tr>
            <tr>
              <td>Base Price <font color="red"> *</font></td>
              <td colspan="2"><input type="text" autocomplete="off" name="prod_price" id="prod_price" placeholder="Enter price (0.00)" value="<?php echo (isset($product_details['price']))?$product_details['price']:'';?>"></td>
            </tr>
            <tr>
              <td>Keywords (separated by spaces)</td>
              <td colspan="2"><input type="text" autocomplete="off" name="prod_keyword" id="prod_keyword" placeholder="Enter keyword for you item" value="<?php echo (isset($product_details['keywords']))?$product_details['keywords']:'';?>"></td>
            </tr>
            <tr>
              <td colspan="3">
                <h3  class="orange"> Additional Information to your Item</h3> 
              </td>
            </tr>

            
            
            <?php 
            $j = 1;
            if(!isset($product_attributes_opt))
              $product_attributes_opt = array();  
            
            foreach($product_attributes_opt as $key=>$opt_attr): ?>

            <tr id="<?php echo ($j === 1)?'':'main'.$j; ?>">
              <td> <?php echo ($j===1)?'Others: (Optional)':''; ?></td>  
              <td colspan="3">
                <input type="text" name="prod_other_name[]" data-cnt="<?php echo $j; ?>" class="prod_<?php echo $j;?> other_name_class" autocomplete="off" placeholder="Enter name" value="<?php echo str_replace("'", '', $key);?>"> 
              </td>
            </tr>

            <?php $k = 0;
            foreach($opt_attr as $prod_attr): ?>
            <tr>
              <td>&nbsp;</td>
              <?php if($k > 0):?>
              <td style="display:none">
                <span >
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
          <td></td>
          <td colspan="3"><a class="add_more_link_value" data-value="<?php echo $j;?>" href="javascript:void(0)">+Add more value</a></td>
        </tr>     
        <?php $j++; endforeach;?>



        <?php if($j===1):?>
        <tr id="">
          <td> Others: (Optional) </td> 
          <td colspan="3">
            <input type="text" name="prod_other_name[]" data-cnt="<?php echo $j;?>" class="<?php echo 'prod_'.$j;?> other_name_class" autocomplete="off" placeholder="Enter name"> 
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input type="text" name="prod_other[]"  class="other_name_value otherNameValue1"  autocomplete="off" data-cnt="<?php echo $j;?>" placeholder="Enter description">
          </td>
          <td>
            <div class="<?php echo 'h_if_'.$j;?> hdv">
              &#8369; <input type="text" name="prod_other_price[]" autocomplete="off" id="price_field" placeholder="Enter additional price (0.00)">
            </div>
          </td>
          <td>
            <div class="<?php echo 'h_if_'.$j;?> hdv">
              <input type="file" name="prod_other_img[]"  >
            </div>
          </td>
        </tr> 

        <tr id="main1">
          <td></td>
          <td colspan="3"><a class="add_more_link_value" data-value="<?php echo $j;?>" href="javascript:void(0)">+Add more value</a></td>
        </tr>
      <?php endif; ?>

      <tfoot>
        <tr>
          <td>&nbsp;</td>
          <td colspan="3">
            <input type="hidden" value="" name="desc" class="description_hidden"> 
            <a class="add_more_link" href="javascript:void(0)">+ Add More</a>

            <?php if(isset($product_details)): ?>
            <input type="hidden" name="p_id" value="<?php echo $product_details['id_product']; ?>">
          <?php endif;?>
        </td>
      </tr>
      
      <tr>
        <td colspan="4"><h3 class="orange">Quantity</h3></td>

      </tr>
      <tr>
        <td></td>
        <td colspan="3">
          <div class="quantity_table">
            <div class="quantity_table_row">               
              <div class="qty_title">
                <span>Quantity:</span><br />
                <input type="text" class="qtyTextClass" id="qtyTextClass" name="quantity"> 
                <a href="javascript:void(0)" data-value="1" class="quantity_attr_done orange_btn3">Add</a>
                <a class="tooltips qty_tooltip" href="javascript:void(0)" style="display:inline"><img src="<?= base_url() ?>assets/images/icon_qmark.png" alt=""> <span> You can also set the availability of different attribute combinations of your item by clicking the Add button</span></a> 
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
    <div class="add_category_submit">


      <div class="button_div"><input class="proceed_form" id="proceed_form" type="button" value="Proceed"></div>

    </div>  
    <div class="clear"></div>
    <div class="quantity_table2">
    </div> 
    <input type="hidden" id="qty_details" value='<?php echo (isset($item_quantity))?json_encode($item_quantity):json_encode(array());  ;?>'></input>
  </form>

  <div class="loader_div"><br><br><center><img src='<?php echo base_url().'assets/images/es_loader.gif' ?>'><br><br><br><br><br></center></div>
</div>
</div>
</div>
</td>
</tr>
</tfoot>
</table> 
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type="text/javascript">
$(document).ready(function(){

  var qtycnt = 1;
  var countSelected = 1;
  var arrayVar = [];
  var combination = []; 
  var arrayCombination = []; 
  var arraySelected = {};  
  
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


    document.getElementById('files').addEventListener('change', handleFileSelect, false);
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

 
        // $('#'+oldValue+'Combination option[data-value=1]').remove().appendTo('#'+headValue+'Combination');  
      
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

          // console.log(true); 
          // $('#'+oldValue+'Combination option[data-value=1]').remove().appendTo('#'+headValue+'Combination');  

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
  $('.step4_2').append('<tr id="main'+cnt_o+'"><td></td><td colspan="3"><input type="text" data-cnt="'+cnt_o+'" autocomplete="off" name="prod_other_name[]" class="prod_'+cnt_o+' other_name_class" placeholder="Enter name"></td></tr><tr><td></td><td><input type="text" autocomplete="off" data-cnt="'+cnt_o+'" class="other_name_value otherNameValue'+cnt_o+'" name="prod_other[]" placeholder="Enter description"></td><td> <div class="h_if_'+cnt_o+' hdv"  style="display:none">&#8369; <input type="text" name="prod_other_price[]"  class="price_text"   id="price_field"  autocomplete="off" placeholder="Enter additional price (0.00)"></div></td><td> <div class="h_if_'+cnt_o+' hdv" style="display:none"><input type="file" name="prod_other_img[]" ></div></td></tr><tr id="main1"><td></td><td colspan="3"><a class="add_more_link_value" data-value="'+cnt_o+'" href="javascript:void(0)">+Add more value</a></td></tr>');
});

$('.upload_input_form').on('click', '.add_more_link_value', function() {
  var data =   $(this).data( "value" );   
  $(".h_if_"+data).css("display", "block");
  var attr = $('.prod_'+data).val();
  var newrow = $('<tr><td></td><td style="display:none"><span ><input type="text" value ="'+attr+'" data-cnt="'+data+'" class="prod_'+data+'" name="prod_other_name[]"></span></td><td><input type="text" autocomplete="off" data-cnt="'+data+'" class="other_name_value otherNameValue'+data+'"  name="prod_other[]" placeholder="Enter description"></td><td>&#8369; <input type="text" name="prod_other_price[]"  id="price_field" class="price_text"  autocomplete="off" placeholder="Enter additional price (0.00)"></td><td><input type="file" name="prod_other_img[]" ></td></tr>');
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

$( "#prod_brand,#prod_title,#prod_brief_desc,#prod_price,#prod_sku,#prod_condition" ).blur(function() {
  var value = $(this).val();
  var id = $(this).attr('id');

  if(value == "0" || value == ""){
    validateRedTextBox("#"+id);
  }else{
    validateWhiteTextBox("#"+id);
  }
});

$( "#prod_title,#prod_brief_desc,#prod_price,#prod_sku,#qtyTextClass" ).keypress(function() {
  var id = $(this).attr('id');
  validateWhiteTextBox("#"+id);
});

$(document).on('change',"#prod_condition,#prod_brand",function () {
  var id = $(this).attr('id');
  validateWhiteTextBox("#"+id);
});


$(".proceed_form").unbind("click").click(function(){
  
  var description = tinyMCE.activeEditor.getContent();
  var id = "<?php echo $id; ?>"
  $('.description_hidden').val(description);
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
  formData.append("noCombination",noCombination);
  formData.append("otherCategory",otherCategory);
  
  var csrftoken = $('#uploadstep2_csrf').val();
  formData.append('es_csrf_token', csrftoken);
 
  var price = $("#prod_price");
  var other_price = $("#price_field");
  var sku = $("#prod_sku");  
  var brand = $("#prod_brand");
  var condition = $('#prod_condition');
  var brandAvailable = <?php echo json_encode($brand); ?>;
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
    validateRedTextBox("#prod_brand");
  }else{
    validateWhiteTextBox("#prod_brand");
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
    //if(price.val() <= 0 || !$.isNumeric(price.val())){
	if(pricevalue <= 0 || !$.isNumeric(pricevalue)){
      alert("Invalid Price. Price should be numeric!");
      validateRedTextBox("#prod_price");
      return false;
    }else{

      for (var key in brandAvailable) {
        if (brandAvailable.hasOwnProperty(key))
          if(brandAvailable[key]['brand_id'] === brand.val()){
           found = true;
           break;
         }
       }

       if(found === false){
         validateRedTextBox("#prod_brand");
         $( "#prod_brand" ).focus(); 
         alert('Brand selected not available. Please select other.');
         return false;
       } 

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
          url: '<?php echo base_url();?>' + 'productUpload/editProductSubmit',
          mimeType:"multipart/form-data",
          contentType: false,
          cache: false,
          processData:false,
          data: formData, 
          dataType: "json",
          beforeSend: function(jqxhr, settings) { 
            $('.description_hidden').val(description);
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
            $('.description_hidden').val(description);
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
		htmlEach += '<div><input type="textbox" class="quantityText" value="'+qtyTextbox.val()+'" data-cnt="'+thisValueCount+'"></div>';

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
				$('.combinationContainer').append('<div class="inner_quantity_list innerContainer'+thisValueCount+'"> '+ htmlEach +' <a href="javascript:void(0)" class="removeSelected" data-row="'+thisValueCount+'"   style="color:Red">Remove</a></div>');
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
  // mode : "specific_textareas",
  // editor_selector : "mceEditor",
  selector: "textarea",
 menubar: "table format view insert edit",

    statusbar: false,
  height: 300,
  plugins: [
  "lists link preview ",
  "table jbimages"
  ],  
  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
  relative_urls: false
});
</script> 
  <!-- <script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
CKEDITOR.replace( 'prod_description' );
</script>
-->

<div class="clear"></div>  




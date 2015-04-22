<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/vendor/bower_components/ion.rangeSlider.css" />
    <link rel="stylesheet" href="/assets/css/vendor/bower_components/ion.rangeSlider.skinFlat.css" />
    <link rel="stylesheet" href="/assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" /> 
    <link rel="stylesheet" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap-mods.css" type="text/css" media="screen"/>  
    <link rel="stylesheet" href="/assets/css/chosenwtihcreate.min.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/vendor/bower_components/jquery.cropper.css">
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.upload-step2.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>
    <link rel="stylesheet" href="/assets/css/font-awesome/css/font-awesome.min.css" type="text/css" media="screen" />


<script type="text/javascript">
    var af = new Array();
</script>
<!--  END OF simple slider-->
<div class="container">

    <div class="clear"></div>

    <div class="seller_product_content">

        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
            <div class="sell_steps sell_steps2">
                <ul> 
                    <li class="steps_txt_hide">
                        <a href="javascript:void(0)" class="step1_link">
                          <span class="span_bg left-arrow-shape2"></span>
                          <span class="steps_txt">Step 1: Select Category</span>
                          <span class="span_bg right-arrow-shape"></span>
                        </a>
                    </li>
                    <li>
                        <span class="span_bg left-arrow-shape ar_active"></span>
                        <span class="steps_txt_active"><span class="f18">Step 2: </span> Upload Item</span>
                        <span class="span_bg right-arrow-shape ar_r_active"></span>
                    </li>                   
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">Step 3: Shipping Location</span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">Success</span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>
                </ul>
            </div>
            <input type="hidden" name="step1_content" id="step1_content" value='<?php echo isset($step1_content)?$step1_content:json_encode(array());?>'/>
            
            <?php echo form_open('sell/step1', array('id'=>'edit_step1'));?> 
                <input type="hidden" name="p_id" id="p_id" value="<?php echo (isset($product_details['id_product']))?$product_details['id_product']:'';?>">  
                <input type="hidden" name="other_cat_name" id="other_cat_name" value="<?php echo $otherCategory;?>"> 
            <?php echo form_close();?>

            <div class="clear"></div>
  
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
                <input type="hidden" class="coordinates" id="coordinates" name="coordinates">
                <input type="hidden" class="imageCollections" id="imageCollections" name="imageCollections">
                <div id="inputList" class="inputList"></div>
            </form> 

            <?php 
            $attr = array(
                'class' => 'other_files',
                'id' => 'other_files',
                'name' => 'other_files',
                'enctype' => 'multipart/form-data'
            );
            echo form_open('productUpload/uploadimageOther', $attr);
            ?>
                <input type="hidden" class="imageCollectionsOther" id="imageCollectionsOther" name="imageCollections">
                <input type="file" class="attr-image-input" accept="image/*" style="left: -9999px;position: absolute;z-index: -1900;" name="attr-image-input" >
            </form> 

            <?php 
            $attributesForm = array('id' => 'hidden_form','name'=>'hidden_form');
            echo form_open('sell/step3', $attributesForm);
            ?>
                <input type="hidden" name="prod_h_id" id="prod_h_id"> 
                <?php if(isset($is_edit)): ?>
                <input type="hidden" name="is_edit" value="true">
                <?php endif; ?>
            </form> 

            <div class="upload_input_form form_input mrgn-top-35">
                <?php 
                $attr = array(
                    'class' => 'form_product',
                    'id' => 'form_product',
                    'name' => 'form_product',
                    'enctype' => 'multipart/form-data'
                    );
                echo isset($is_edit)?form_open('sell/edit/processing', $attr):form_open('sell/processing', $attr);
                echo isset($p_id) ? '<input type="hidden" name="p_id" id="p_id" value="'.$p_id.'">' : '';
                ?>                    
                    <input type="hidden" name="otherCategory" id="otherCategory" value="<?php echo $otherCategory;?>">
                    <input type="hidden" class="arrayNameOfFiles" name="arraynameoffiles"> 
                    <input type="hidden" name="id" value="<?=$id;?>">

                    <div class="product_upload_step2">
                        <div class="border-all">
                            <div class="upload_step2_title col-xs-12 bg-cl-e5e5e5">
                                <h5>
                                    Categories where your listing will appear 
                                    <a class="tooltips" href="javascript:void(0)">
                                        <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                        <span class="1line_tooltip">
                                            Your item will be listed under this EasyShop category
                                        </span>
                                    </a> 
                                </h5> 
                            </div>
                            <div class="clear"></div>
                            <div class="col-xs-12">
                                <div class="pd-tb-15">
                                    <p><?php echo $parent_to_last; ?></p> <!-- will show the parent category until to the last category selected (bread crumbs) -->
                                    <a href="javascript:void(0)" style="color:#0654BA;" class="step1_link">Change category</a> 
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="border-all mrgn-top-35">
                            <div class="upload_step2_title col-xs-12">
                                <h5>Describe your item <span class="required">(*) required fields</span> </h5> 
                            </div>
                            <div class="clear"></div>
                            <div class="pd-top-15">
                                <!-- Title of the product -->
                                <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">
                                    Product Name: <font color="red">*</font>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-10">
                                    <input class="width-50p ui-form-control" type="text" maxlength="255" placeholder="Enter title" autocomplete="off" id="prod_title" maxlength="255" name="prod_title" value="<?php echo (isset($product_details['name']))?html_escape($product_details['name']):'';?>">
                                    <a  class="tooltips" href="javascript:void(0)">
                                        <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                        <span class="lines_tooltip">
                                            Give your listing a descriptive title. Include necessary information so that other users may easily find your listing.
                                        </span>
                                    </a> 
                                </div>
                                <div class="clear"></div>
                            </div>

                            <!-- Start Condition of the product -->
                            <div class="pd-tb-15">                  
                                <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">
                                    Condition: <font color="red">*</font>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-10">
                                    <select name="prod_condition" id="prod_condition" class="width-50p ui-form-control">
                                        <option value="">--Select Condition--</option>          
                                        <?php foreach($this->lang->line('product_condition') as $x): ?>
                                            <option value="<?php echo $x;?>" <?php if(isset($product_details['condition'])){echo (html_escape($product_details['condition']))===$x?'selected':'';}?>><?php echo $x; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a class="tooltips" href="javascript:void(0)">
                                        <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                        <span class="lines_tooltip">
                                            Identify the condition of your item so potential buyers may know what they should be getting.
                                        </span>
                                    </a>
                                </div>
                                <div class="clear"></div>  
                            </div>
                            <!-- End Condition of the product -->
                        </div>

                        <!-- Upload Image Content -->
                        <div class="border-all mrgn-top-35">
                            <div class="upload_step2_title col-xs-12 bg-cl-e5e5e5"> 
                                <h5>
                                    Add Photos
                                    <span class="required">You are required to have a minimum of 1 photo</span>
                                    <a class="tooltips" href="javascript:void(0)">
                                        <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                        <span class="twolines_tooltip">
                                            Upload images for your listing. We recommend that you keep the image resolution within <?php echo $img_max_dimension[0].'x'.$img_max_dimension[1]; ?> for best quality of your images
                                        </span>
                                    </a>
                                </h5>
                            </div>
                            <div class="clear"></div>
                            <div class="col-xs-12 upload-photo-con">
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
                                            <script type="text/javascript">
                                                af.push("<?=$main_image['temp'] . '||' . $main_image['type'] ?>"); 
                                            </script>
                                            <div id="previewList<?php echo $main_img_cnt; ?>" class="edit_img upload_img_div <?php echo ($main_img_cnt===0)?'active_img':'';?>">
                                                <span class="upload_img_con">
                                                    <img src="<?php echo getAssetsDomain(); ?><?php echo $main_image['path'].'categoryview/'.$main_image['file'];?>" alt="/<?php echo $main_image['path'].'categoryview/'.$main_image['file'];?> ">
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
                            <div class="clear"></div>
                        </div>
                        <!-- end of upload image -->

                        <div class="border-all mrgn-top-35">
                            <div class="upload_step2_title col-xs-12 bg-cl-e5e5e5">
                                <h5>
                                    Add a description
                                    <span class="required">(*) required fields</span> 
                                </h5>
                            </div>
                            <div class="clear"></div>
                            <div class="pd-top-15">
                                <!-- Main Description of the product --> 
                                <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">
                                    Product Details: <font color="red">*</font>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-10">
                                    <textarea style="width: 100%;height:100%" name="prod_description" class="mceEditor"  id="prod_description" placeholder="Enter description..."><?php echo (isset($cleanDescription))?$cleanDescription:'';?></textarea>
                                </div>
                                <!-- end of Description -->
                                <div class="clear"></div>
                            </div>

                            <div class="pd-top-15">
                                <!-- Price of the product -->
                                <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8"> Base Price: <font color="red"> *</font></div>
                                <div class="col-xs-12 col-sm-10 col-md-10">
                                    <input type="text" class="width-50p ui-form-control"  maxlength="15" autocomplete="off" onkeypress="return isNumberKey(event)"  name="prod_price" id="prod_price" placeholder="Enter price (0.00)" value="<?php echo (isset($product_details['price']))?number_format($product_details['price'],2,'.',''):'';?>">
                                    <a class="tooltips" href="javascript:void(0)">
                                        <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                        <span>Set the base price for your listing. You may set the shipment fee separately in the following step.
                                        </span>
                                    </a>

                                    <br><a id="discnt_btn" class="blue">Add discount price</a>
                                    <div class="discounted_price_container">
                                        <strong>Discounted Price:</strong> &#8369;
                                        <span id="discounted_price_con">
                                            <?php echo (isset($product_details['price']))?number_format($product_details['price'], 2, '.', ','):'0.00';?>
                                        </span>
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
                                <div class="clear"></div>
                            </div>

                            <!-- start of keywords -->
                            <div class="pd-top-15">
                                <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">Additional Keywords
                                    <br>
                                    <span class="f11">(Seperated by spaces.)</span>
                                </div>
                                <div class="col-xs-12 col-sm-10 col-md-10">
                                    <input class="width-50p ui-form-control" type="text" autocomplete="off" maxlength="150" name="prod_keyword" id="prod_keyword" placeholder="Enter keyword for you item" value="<?php echo (isset($product_details['keywords']))?html_escape($product_details['keywords']):'';?>">
                                    <a class="tooltips" href="javascript:void(0)">
                                        <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                        <span>
                                            Provide meaningful keywords for your listing so that other users may search for it more easily.
                                        </span>
                                    </a>
                                    <br>
                                    <span class="countdown" style="color:gray;font-style:italic;font-size:12px" /></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <!-- end of keywords -->
                            <div class="col-xs-12 pd-tb-15">
                                <p class="view_more_product_details blue"><span class="span_bg vmd_img"></span>View more product details</p>
                            </div>
                            <div class="clear"></div>

                            <div class="more_product_details_container">
                                <div class="step4_2">
                                    <!-- Start hide of more product details -->
                                    <div class="col-xs-12 bg-cl-e5e5e5">
                                        <h5>Add item specifics</h5>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="pd-top-15">
                                        <!-- Add item specifics -->
                                        <div class="">
                                            <!-- Brief of the product -->
                                            <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">Brief description:</div>
                                            <div class="col-xs-12 col-sm-10 col-md-10">
                                                <input class="width-50p ui-form-control" type="text" autocomplete="off" maxlength="255" placeholder="Enter brief description" id="prod_brief_desc" name="prod_brief_desc"  value="<?php echo (isset($product_details['brief']))?html_escape($product_details['brief']):'';?>">
                                                <a class="tooltips" href="javascript:void(0)">
                                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                                    <span>Describe your item in a brief but precise way.</span>
                                                </a>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- start of brand -->
                                        <div class="pd-top-15">
                                            <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">Brand:</div> 
                                            <div class="col-xs-12 col-sm-10 col-md-10">
                                                <input type = "hidden" id="prod_brand" name="prod_brand" value="<?php echo isset($product_details['brand_id'])?$product_details['brand_id']:0?>"/>
                                                <input class="width-50p ui-form-control" type = "text" id="brand_sch" name="brand_sch" autocomplete="off" placeholder="Search for your brand" value="<?php echo isset($product_details['brandname'])?trim(html_escape($product_details['brandname'])):''?>"/>
                                                <div class="brand_sch_loading"></div>
                                                <div id="brand_search_drop_content" class="brand_sch_drop_content"></div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- end of brand -->
                                        <!-- start of sku code -->
                                        <div class="pd-top-15">
                                            <div class="col-xs-12 col-sm-2 col-md-2 pd-tb-8">SKU Code: </div> <!-- SKU of the product -->
                                            <div class="col-xs-12 col-sm-10 col-md-10">
                                                <input class="width-50p ui-form-control" type="text" autocomplete="off"  maxlength="45" placeholder="Enter SKU" id="prod_sku" name="prod_sku" value="<?php echo (isset($product_details['sku']))?html_escape($product_details['sku']):'';?>">
                                                <a class="tooltips" href="javascript:void(0)"><img src="<?php echo getAssetsDomain(); ?>assets/images/icon_qmark.png" alt="">
                                                    <span class="2lines_tooltip">Stock Keeping Unit: you can assign any code in order to keep track of your items</span>
                                                </a>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <!-- end of sku code -->
                                    </div>

                                    <!-- Start hide of more product details -->
                                    <div class="col-xs-12 bg-cl-e5e5e5 mrgn-top-35">
                                        <h5 class="">Quantity</h5>
                                    </div>
                                    <div class="clear"></div>
                                    <div class="row pd-8-12 quantity-panel">
                                        <!-- Add item specifics -->
                                        <div class="col-xs-12 col-sm-3 col-md-3"> 
                                            <div class="">
                                                <p class="qty-item-title">Item Property</p>
                                                <select class="" id="head-data" data-placeholder="(e.g Color, Size,...) ">
                                                    <option value="0" ></option> 
                                                    <?php foreach ($attributeArray as $key => $value):?>
                                                        <option><?=ucfirst(strtolower(html_escape($key)));?></option> 
                                                    <?php endforeach; ?>
                                                </select> 
                                            </div>
                                        </div>

                                        <div class="control-panel-container col-xs-12 col-sm-7 col-md-7">
                                            <p class="qty-item-title2">Item Property Value:</p>
                                            <div class="control-panel">
                                                <div class="control-panel-1 ctrl row">
                                                    <div class="display-ib width-100p-40max"></div>
                                                    <div class="value-section col-xs-5 col-sm-5 col-md-5 pd-bttm-10">
                                                        <div class="select-value-section">
                                                            <select id="value-data-1" class="value-data" data-cnt='1' data-placeholder="(e.g Blue, Red, Small, Large,...) ">
                                                                <option value="0"></option> 
                                                            </select>
                                                        </div> 
                                                    </div>
                                                    <div class="price-div col-xs-3 col-sm-3 col-md-3 pd-bttm-10">
                                                        &#8369; <input type="text"  maxlength="10"  onkeypress="return isNumberKey(event)"   class="price-val  price1 ui-form-control" placeholder="0.00" />
                                                    </div>
                                                    <div class="image-div col-xs-2 col-sm-2 col-md-3 pd-bttm-10">
                                                        <input type="hidden" class="image-val imageText1"/>
                                                        <input type="hidden" class="image-file imageFileText1"/>
                                                      
                                                        <a class="select-image qty-image-con image1" data-cnt='1' href="javascript:void(0)"><img src="<?php echo getAssetsDomain(); ?>assets/images/img_upload_photo.jpg"></a>
                                                        <a class="select-image image1 select-image-pencil" data-cnt='1' href="javascript:void(0)"><span class="glyphicon glyphicon-pencil"></span></a>
                                                       
                                                    </div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <div class="clear"> 
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <a class="add-property-value" href="javascript:void(0)">Add more property Value</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-2 col-md-2 add-property-con">
                                            <p>Actions:</p>
                                            <div class="add-property-btn-con">
                                                <input type="button" class="add-property orange_btn3 width-80p mrgn-bttm-10" value="Add Property" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pd-8-12">
                                        <div class="row pd-8-12 choosen-combination-div">
                                            <div class="col-xs-2 col-sm-1 col-md-1 div1 bg-cl-e5e5e5">
                                                <h5>Quantity</h5>
                                            </div>
                                            <div class="col-xs-7 col-sm-9 col-md-9 div2 bg-cl-e5e5e5">
                                                <h5>Item Property</h5>
                                            </div>
                                            <div class="col-xs-3 col-sm-2 col-md-2 div3 bg-cl-e5e5e5 text-center">
                                                <h5>Actions</h5>
                                            </div> 
                                        </div>
                                        <div class="list-choosen-combination-div">
                                            <?php $cmbcounter = 1; ?>
                                            <?php if(isset($eachAttribute) && count($eachAttribute) > 0):?>
                                                <?php if(!isset($noCombination)): ?>                                     
                                                    <?php foreach ($itemQuantity as $keyq => $valueq): ?>
                                                        <div class="div-combination zebra-div combination<?=$cmbcounter;?>" data-itemId="<?=$keyq;?>">
                                                            <div class="col-xs-2 col-sm-1 col-md-1 div1">
                                                                <input type="text"  size="3" value="<?=$valueq['quantity']; ?>" maxlength="4" class="qty ui-form-control"  onkeypress="return isNumberKey(event)">
                                                            </div>
                                                            <div class="col-xs-7 col-sm-9 col-md-9 div2">
                                                                <?php foreach ($eachAttribute as $key => $value): ?>
                                                                <span class="spanSelect<?=str_replace(' ', '', strtolower(html_escape($key)))?>">
                                                                    <select disabled id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>" class="selection width-30p ui-form-control" data-id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>">
                                                                        <?php foreach ($value as $key2 => $value2): ?>
                                                                            <option data-image="<?=$value2['img_path']?>" data-file="<?=$value2['file_path']?>" <?php echo ($valueq['data'][$key] == html_escape($value2['value_name'])) ? 'selected' : ''; ?>  data-price="<?=$value2['value_price']?>" data-head="<?=html_escape($key)?>" value="<?=html_escape($value2['value_name'])?>" data-value="<?=html_escape($value2['value_name'])?>"><?=html_escape($value2['value_name']) . ' - &#8369; '.number_format($value2['value_price'], 2, '.', ','); ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>     
                                                                    <a class="edit-attr" href="javascript:void(0)" data-head="<?=html_escape($key)?>" data-id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                                                </span>
                                                                <?php endforeach;?> 
                                                            </div>
                                                            <div class="col-xs-3 col-sm-2 col-md-2 div3 text-center">
                                                                <input type="button" value="Remove" data-cmbcnt="<?=$cmbcounter;?>" class="remove-combination btn btn-danger width-70p">
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <?php $cmbcounter++; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="container-select-control-panel-option clear bg-color">
                                            <div class="select-control-panel-option">
                                                <div class="col-xs-2 col-sm-1 col-md-1 div1">
                                                    <input type="text" name="allQuantity" value="<?=(isset($noCombinationQuantity))?$noCombinationQuantity:'1'; ?>" size="3" maxlength="4" onkeypress="return isNumberKey(event)" class="qty ui-form-control">
                                                </div>           
                                                <div class="col-xs-7 col-sm-9 col-md-9 div2">
                                                    <?php if(isset($eachAttribute) && count($eachAttribute) > 0):?> 
                                                        <?php foreach ($eachAttribute as $key => $value): ?>
                                                        <span class="spanSelect<?=str_replace(' ', '', strtolower(html_escape($key)))?>">
                                                            <select id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>" class="selection width-30p ui-form-control" data-id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>">
                                                                <?php foreach ($value as $key2 => $value2): ?>
                                                                <option data-image="<?=$value2['img_path']?>" data-file="<?=$value2['file_path']?>" data-price="<?=$value2['value_price']?>" data-head="<?=html_escape($key)?>" value="<?=html_escape($value2['value_name'])?>" data-value="<?=html_escape($value2['value_name'])?>"><?=html_escape($value2['value_name']) . ' - &#8369; '.number_format($value2['value_price'], 2, '.', ','); ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <a class="edit-attr" href="javascript:void(0)" data-head="<?=html_escape($key)?>" data-id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>"><span class="glyphicon glyphicon-pencil"></span></a>
                                                            <a class="remove-attr" href="javascript:void(0)" data-head="<?=html_escape($key)?>" data-id="<?=str_replace(' ', '', strtolower(html_escape($key)))?>"><span class="glyphicon glyphicon-remove"></span></a>
                                                        </span>
                                                        <?php endforeach;?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-xs-2 col-sm-2 col-md-2 div3 text-center">
                                                    <?php if(isset($eachAttribute) && count($eachAttribute) > 0):?> 
                                                        <input class="select-combination orange_btn3 width-70p" type="button" value="Add">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>

                        <div class="add_category_submit">
                            <div class="button_div"><input class="proceed_form" id="proceed_form" type="button" value="Proceed"></div>

                       
                            <div class="loader_div" style="display:none">
                                <img src='<?php echo getAssetsDomain(); ?>assets/images/orange_loader.gif'>
                                <div class="percentage"></div>
                            </div>
                   
                        </div> 
                        
                        
                    </div>
                </form>
            </div>
            <div id="question"></div>
            <div style="display:none" id="pop-image" class="simplemodal-container">
                <h1>Add or remove image</h1>
                <div class="pop-image-remove-container">
                    <a class="remove-attr-image vrtcl-top" data-cnt='1' href="javascript:void(0)"><span style="color:red" class="glyphicon glyphicon-trash"></span></a>
                </div>
                <div class="pop-image-container">
                    <a href="javascript:void(0)" class="attr-image"><img  src=""></a>
                </div>
                <div class="pd-tb-15 text-center">
                    <a class="simplemodal-close" title="Close"><span class="img-upload-save btn btn-default-3">Save</span></a>
                </div>
            </div>
            <div style="display:none" id="crop-image-main" class="simplemodal-container">
                <div class="imageContainer"> 
                    <img src="" id="imageTag">
                </div><br /> 
                <center>
                    <a class="zoomIn" title="Zoom In" href="javascript:void(0)"><i class="fa fa-search-plus fa-2x"></i></a>
                    <a class="rotateLeft" title="Rotate Left" href="javascript:void(0)"><i class="fa fa-undo fa-2x"></i></a>
                    <a class="refresh" title="Refresh" href="javascript:void(0)"><i class="fa fa-refresh fa-2x"></i></a>
                    <a class="rotateRight" title="Rotate Right" href="javascript:void(0)"><i class="fa fa-repeat fa-2x"></i></a>
                    <a class="zoomOut" title="Zoom Out" href="javascript:void(0)"><i class="fa fa-search-minus fa-2x"></i></a>
                </center>
            </div>
            
        </div>
    </div>
</div>

<!-- LOADING JAVASCRIPT FILES -->
<script type="text/javascript">
    var attributeArray = <?=json_encode($attributeArray);?>;
    var tempId = '<?= $tempId; ?>';
    var memberId = '<?= $memid; ?>';
    var fulldate = '<?=date("YmdGis");?>'; 
    var pictureCount = '<?=$main_img_cnt?>';  
    var tempDirectory = '<?=(isset($tempdirectory)) ? $tempdirectory : '' ;?>';
    var combinationcnt = '<?=$cmbcounter;?>';  
    var isEdit =  '<?=(isset($is_edit)) ? "1" : "0" ?>';
    var maxImageSize = parseInt('<?=$maxImageSize; ?>');
</script>
<script src="/assets/tinymce/tinymce.min.js" type="text/javascript"></script>


<?php if(strtolower(ENVIRONMENT) === 'development'): ?> 
    <script src="/assets/js/src/vendor/bower_components/jquery.cropper.js"></script> 
    <script src="/assets/js/src/vendor/bower_components/ion.rangeSlider.js"></script>
    <script src="/assets/js/src/vendor/chosenwithcreate.jquery.min.js" type="text/javascript"></script> 
    <script src="/assets/js/src/vendor/jquery.simplemodal.js" type='text/javascript' ></script>
    <script src="/assets/js/src/productUpload_step2.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript" ></script> 
    <script src="/assets/tinymce/plugins/jbimages/js/jquery.form.js"></script>
    <script src="/assets/js/src/vendor/bower_components/jquery.validate.js" type="text/javascript"></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.product_upload_step2_view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>
<?php if(isset($soloAttribute)): ?>
    <?php foreach ($soloAttribute as $key => $value): ?>
        <script type="text/javascript">
                $('#prod_description').val( $('#prod_description').val() + '<?=$key;?> : <?=$value;?> <br>'); 
        </script>
    <?php endforeach; ?>
<?php endif;?>

<style type="text/css">
    #simplemodal-container {
        width: 60%;
    }
    label.errorTxt{
        color:red;
        font-size: 12px;
        margin-left: 5px;
    }
</style>

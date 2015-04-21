<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-mods.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="/assets/css/product_preview.css?ver=<?=ES_FILE_VERSION?>" media="screen"/>
    <link rel="stylesheet" type="text/css" href="/assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" media="screen"/>
    <link rel="stylesheet" type="text/css" href='/assets/css/product_upload_tutorial.css?ver=<?=ES_FILE_VERSION?>' media='screen'/>
    <link rel="stylesheet" type="text/css" href="/assets/css/vendor/bower_components/chosen.min.css"  media="screen"/>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.upload-step3.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>


<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<div class="clear"></div>

    <div class="container seller_product_content">
        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
            <div class="sell_steps sell_steps3"> 
                <ul> 
                    <li class="steps_txt_hide">
                        <a href="javascript:void(0)" id="step1_link">
                            <span class="span_bg left-arrow-shape2"></span>
                            <span class="steps_txt">Step 1: Select Category
                            </span><span class="span_bg right-arrow-shape"></span>
                        </a>
                    </li>
                    <li class="steps_txt_hide">
                        <a href="javascript:void(0)" id="step2_link">
                            <span class="span_bg left-arrow-shape2"></span>
                            <span class="steps_txt">Step 2: Upload Item</span>
                            <span class="span_bg right-arrow-shape"></span>
                        </a>
                    </li>
                    <li>
                        <span class="span_bg left-arrow-shape ar_active"></span>
                        <span class="steps_txt_active"><span class="f18">Step 3:</span> Shipping Location</span>
                        <span class="span_bg right-arrow-shape ar_r_active"></span>
                    </li>
                    <li class="steps_txt_hide">
                        <span class="span_bg left-arrow-shape2"></span>
                        <span class="steps_txt">Success</span>
                        <span class="span_bg right-arrow-shape"></span>
                    </li>
                </ul>
            </div>
            <div class="clear"></div>
                
            <?php echo form_open('sell/edit/step2', array('id'=>'edit_step2'));?>
                <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_id;?>">
                <input type="hidden" name="hiddenattribute" value="<?php echo $product['cat_id']?>">
                <input type="hidden" name="othernamecategory" value="<?php echo $product['cat_other_name']?>">
            <?php echo form_close();?>

            <?php echo form_open('sell/step1', array('id'=>'edit_step1'));?>
                <input type="hidden" name="p_id" id="p_id" value="<?php echo $product_id;?>">
            <?php echo form_close();?>
            
            <div class="product_upload_success pd-top-30">
                <div class='row'>
                    <div class='text-center'>
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img_success.png">
                        <?php if(!isset($is_edit)): ?>
                            You have <strong>successfully</strong> uploaded <span>1 new item.</span>
                        <?php else: ?>   
                            You have <strong>successfully</strong> edited your listing for <span><?php echo html_escape($product['product_name']);?></span>. 
                        <?php endif; ?>  
                        <a class='desktop-item-lnk' href="<?php echo '/item/'.$product['slug']; ?>" class="blue">Click here to view your listing.</a>
                    </div>
                    
                    <div class='col-sm-6 text-center item-lnk'>
                        <a href="<?php echo '/item/'.$product['slug']; ?>" class="blue">Click here to view your listing.</a>
                    </div>

                </div>
                
                <br/>
                <p style='font-size: 13px;'>
                    Congratulations, your item has just been uploaded as an ad-listing on the site. Complete the shipment details of your item so that
                    other users may be able to purchase your item through Easyshop.ph. Once complete, other users can purchase your listing via the 
                    different available payment options.
                </p>
                <hr/>
            </div>
        </div>
    </div>

    <div class="clear"></div>  

    <?php if(empty($billing_info)): ?>
    <div id="bank_details">
        <?php foreach($billing_info as $idx=>$x): ?>
            <?php if(count($x['products'])): ?>
                <div style='display:none; height: 600px; overflow-y:scroll;' class='acct_prod' data-bid='<?php echo $idx; ?>'>
                    This account is currently in use for <strong><?php echo count($x['products']) ?></strong> products. Are you sure about this action?
                    <br/><br/>
                    <span style='font-size:10px;'>
                    * All purchases made for the items listed below will still be linked to the original account. We will call you to confirm if you have made any changes within the
                    current pay-out period before making a deposit. Should you wish to change the deposit account for any of your items, you can do it by editing your item listing.
                    </span>
                    
                    <br/><br/>
                    <?php foreach($x['products'] as $y): ?>
                        <div style='width:auto; height:20px;'><a href='/item/<?=$y['p_slug']?>'><span style='font-weight:bold'><?php echo html_escape($y['p_name']);?> - <?php echo date('m/d/Y', strtotime($y['p_date'])); ?></span> | <?php echo es_string_limit(html_escape($y['p_briefdesc']), 60);?></a></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>       
        <?php endforeach; ?>

        <div class="container step3_header_title">
            <h2 class="f24">Optional</h2>
        </div>
        <div class="container">
            <div class="paid_section_container table-bordered">
                <div class="col-xs-12 bg-cl-e5e5e5">
                    <h5>How am I going to be paid</h5> 
                </div>
                <div class="clear"></div>
                <div class="step3_content_con pd-tb-15">

                        <div class="col-md-8">
                            <div class="">
                                <div class="row pd-bttm-15">
                                    <div class="col-xs-12 col-sm-3">
                                        <label for="deposit_info">Deposit to: </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-9">
                                        <select id="deposit_info" class="form-control">
                                            <?php foreach($billing_info as $x): ?>
                                                <option data-bankname="<?php echo html_escape($x['bank_name']);?>" data-bankid="<?php echo $x['bank_id'];?>" data-acctname="<?php echo  html_escape($x['bank_account_name']); ?>" data-acctno="<?php echo  html_escape($x['bank_account_number']); ?>"    value="<?php echo $x['id_billing_info'];?>"><?php echo html_escape($x['bank_name']).' - '. html_escape($x['bank_account_name']);?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="0">ADD NEW PAYMENT ACCOUNT</option>
                                        </select>
                                    </div>
                                </div>
                                <?php $first_accnt = reset($billing_info);?>
                                <div class="row pd-bttm-15">
                                    <div class="col-xs-8 col-sm-3">
                                        <label for="deposit_acct_name">Account name: </label>
                                    </div>
                                    <div class="col-xs-12 col-sm-9">
                                        <input class="form-control" name="deposit_acct_name" id="deposit_acct_name" type ="text" value="<?php echo  html_escape(isset($first_accnt['bank_account_name'])?$first_accnt['bank_account_name']:''); ?>"  <?php echo isset($first_accnt['bank_account_name'])?'readonly':''; ?>/>
                                    </div>
                                </div>
                                <div class="row pd-bttm-15">
                                    <div class="col-xs-8 col-sm-3">
                                        <label for="deposit_acct_no">Account number:</label>
                                    </div>
                                    <div class="col-xs-12 col-sm-9">						
                                        <input class="form-control" name="deposit_acct_no" id="deposit_acct_no" type ="text" value="<?php echo  html_escape(isset($first_accnt['bank_account_number'])?$first_accnt['bank_account_number']:''); ?>" <?php echo isset($first_accnt['bank_account_number'])?'readonly':''; ?>/>
                                    </div>
                                </div>
                                <div class="row pd-bttm-15">
                                    <div class="col-xs-8 col-sm-3">
                                        <label for="bank_list">Bank:</label>
                                    </div>
                                    <div class="col-xs-12 col-sm-9">
                                            <select class="form-control" id="bank_list" <?php echo (isset($first_accnt['bank_id']))?'disabled':'';?>>
                                                <option value="0">Please select a bank</option>
                                                <?php foreach($bank_list as $x): ?>
                                                    <?php if(isset($first_accnt['bank_id'])): ?>
                                                        <option value="<?php echo $x['id_bank'];?>" <?php echo ((int)$x['id_bank'] === (int)$first_accnt['bank_id'] )?'selected':'';?>><?php echo  html_escape($x['bank_name']); ?>
                                                        </option>
                                                    <?php else: ?>
                                                        <option value="<?php echo $x['id_bank'];?>" ><?php echo  html_escape($x['bank_name']); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                    </div>
                                </div>
                                <div class="row pd-bttm-15">
                                    <div class="col-xs-12 col-sm-12 text-right">
                                            <input type="hidden" id="bank_name" value="<?php echo  html_escape(isset($first_accnt['bank_name'])?$first_accnt['bank_name']:''); ?>"/>
                                                
                                            <?php if(count($billing_info) > 0): ?>
                                                <span class="deposit_edit btn btn-default"><span class="span_bg"></span>Edit</span>
                                                <span class="deposit_save btn btn-default" style="display:none">Save</span>
                                            <?php else: ?>
                                                <span class="deposit_edit btn btn-default" style="display:none">Edit</span>
                                                <span class="deposit_save btn btn-default btn-primary">Save</span>
                                            <?php endif; ?>
                                                
                                            <span class="deposit_update btn btn-default btn-primary" style="display:none">Update</span>
                                            <span class="deposit_cancel btn btn-default" style="display:none">Cancel</span>
                                                
                                            <input type="hidden" id="temp_deposit_acct_name" value=""/>
                                            <input type="hidden" id="temp_deposit_acct_no" value=""/>
                                            <input type="hidden" id="temp_bank_list" value=""/>
                                            <input type="hidden" id="temp_bank_name" value=""/>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                   
                    
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
    <?php endif; ?>


    <!-- Input box for preference name -->
    <div id="dialog_preference_name" style="display:none;">
        <div class="row">
            <div class="col-xs-12 col-sm-2 pref-name">
                <label for="preference_name">Name: </label>
            </div>
            <div class="col-xs-12 col-sm-10">
                <input type="text" id="preference_name" name="preference_name" maxlength="30" class="ui-form-control width-100p">
            </div>
            <img src="<?php echo getAssetsDomain(); ?>assets/images/orange_loader_small.gif" class="loading" style="display:none;vertical-align:middle; float: right;"/>
        </div>
    </div>

    <select id="shiploc_clone" style="display:none;" class="shiploc" name="shiploc" multiple data-placeholder="Select location(s)">
        <?php foreach($shiploc['area'] as $island=>$loc):?>
            <option value="<?php echo $shiploc['islandkey'][$island];?>"><?php echo $island;?></option>
                <?php foreach($loc as $region=>$subloc):?>
                    <option value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" >&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                        <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                            <option value="<?php echo $id_cityprov;?>" style="margin-left:30px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
                        <?php endforeach;?>
                <?php endforeach;?>
        <?php endforeach;?>
    </select>



    <?php echo form_open('sell/step4', array("id"=>"form_shipping"));?>
    <input id="has_attr" name="has_attr" value="<?php echo $attr["has_attr"]?>" type="hidden">

    <div class="container">
    <div class="step3_shipping_options table-bordered mrgn-top-35">
        <div class="col-xs-12 bg-cl-e5e5e5">
            <h5>How would you like to deliver your item</h5>
        </div>
        <div class="clear"></div>
            <div class="col-xs-12 pd-bttm-15">
                <div class="step3_shipping_option_meetup sh1">
                    <span>
                        <input class="delivery_option radio-select" type="radio" id="meetup" name="delivery_option" value="meetup" <?php echo (bool)$product['is_meetup'] || $shipping_summary['is_delivery'] === false ? 'checked':''?> > <label for="meetup">Meet Up</label>
                    </span>
                    <span>
                        <input class="delivery_option radio-select" type="radio" id="delivery" name="delivery_option" value="delivery" <?php echo $shipping_summary['is_delivery'] ? 'checked' : ''?>> <label for="delivery">For Delivery</label>
                    </span>
                </div>
                <div id="delivery_options" class="sh2" style="display: <?php echo $shipping_summary['is_delivery'] ? '' : 'none'?> ">
                    <div class="pd-bttm-15">
                        <input class="delivery_option" type="checkbox" id="allow_cod" name="allow_cod" <?php echo (bool)$product['is_cod'] ? 'checked' : ''; ?>> <label for="allow_cod">Cash-on-Delivery</label>
                    </div>
                    <div class="pd-bttm-15">
                        Ships within: <input type="text" class="shipping-days form-control" name="ship_within" size=3 value="<?=$product['ships_within_days']; ?>" id="ship-within" onkeypress="return isNumberKey(event)" /> Days
                    </div>
                    <div class="pd-bottom-20 delivery-btn-con">
                        <div class="delivery_cost gbtn1 btn-block-2 <?php echo $shipping_summary['is_freeshipping'] ? 'active':''?>" id="set_free_shipping">Free Shipping
                        </div>
                        <div class="delivery_cost gbtn1 btn-block-2 <?php echo $shipping_summary['has_shippingsummary'] ? 'active':''?>" id="set_shipping_details">
                                Set Shipping Details
                        </div>
                    </div>
                </div>
                
                <div id="shipping_div" class="sh3 border-all col-md-12 pd-tb-20" style="display:<?php echo $shipping_summary['has_shippingsummary'] ? '' : 'none'?>;">
                    <?php $sgCounter = 0; #shipping_group counter used as input submit array key;?>
                    <input type="hidden" id="shipping_group_count" class="shipping_group_count" value="<?php echo count($shipping_summary['shipping_display'])-1?>">
                    
                    <?php foreach($shipping_summary['shipping_display'] as $shiparr):?>
                    <div class="shipping_group sg_css" data-sgkey="<?php echo $sgCounter;?>">
                        <?php $siCounter = 0; #shipping_input counter?>
                        <input type="hidden" class="shipping_input_count" value="<?php echo count($shiparr['location'])-1?>">
                    
                        <div class="prefsel_css row prefsel">
                            <div class="col-xs-12 col-sm-7 col-md-7 hidden-xs"></div>
                            <div class="col-xs-12 col-sm-5">								
                                    <select class="shipping_preference form-control">
                                        <option value="0">Select Preference</option>
                                        <?php foreach($shipping_preference['name'] as $headID=>$prefName):?>
                                            <option class="allow_del" value="<?php echo $headID?>"><?php echo $prefName?></option>								
                                        <?php endforeach;?>
                                    </select>								
                                    <span class="delete_ship_pref del_pref_css">Delete Preference</span>									
                            </div>
                        </div>
                    
                        <div class="data_group dg_css bg-cl-f0f0f0 pd-top-15 mrgntop-10">
                            <?php foreach($shiparr['location'] as $price=>$locarr):?>
                            <div class="clear"></div>
                            <div class="shipping_input si_css mrgn-bttm-15" data-sikey="<?php echo $siCounter;?>">
                                <div class="col-xs-4 col-sm-4 col-md-3">
                                    <label for="price">&#8369;</label>
                                    <input type="text" class="shipprice form-control" maxlength="15" name="shipprice[<?php echo $sgCounter?>][<?php echo $siCounter?>]" value="<?php echo html_escape($price);?>">
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-7 upload_chosen">
                                    <label for="location">Select Location</label>
                                    <select class="shiploc form-control" name="shiploc[<?php echo $sgCounter?>][<?php echo $siCounter?>][]" multiple data-placeholder="Select location(s)">
                                        <?php foreach($shiploc['area'] as $island=>$loc):?>
                                            <option value="<?php echo $shiploc['islandkey'][$island];?>" <?php echo in_array($shiploc['islandkey'][$island],$locarr) ? 'selected':''?> <?php echo in_array($shiploc['islandkey'][$island],$shiparr['disable_lookup']) && !in_array($shiploc['islandkey'][$island],$locarr) ? 'disabled':''?>><?php echo $island;?></option>
                                                <?php foreach($loc as $region=>$subloc):?>
                                                    <option value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" <?php echo in_array($shiploc['regionkey'][$region],$locarr) ? 'selected':''?>  <?php echo in_array($shiploc['regionkey'][$region],$shiparr['disable_lookup']) && !in_array($shiploc['regionkey'][$region],$locarr) ? 'disabled':''?> ><?php echo $region;?></option>
                                                        <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                                                            <option value="<?php echo $id_cityprov;?>" style="margin-left:30px;" <?php echo in_array($id_cityprov,$locarr) ? 'selected':''?>  <?php echo in_array($id_cityprov,$shiparr['disable_lookup']) && !in_array($id_cityprov,$locarr) ? 'disabled':''?> ><?php echo $cityprov;?></option>
                                                        <?php endforeach;?>
                                                <?php endforeach;?>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <?php if($siCounter > 0):?>
                                <div class="del_shipping_input col-xs-2">
                                    <span class="del_bgs3 glyphicon glyphicon-remove"></span>
                                    <span>Remove row</span>
                                </div>
                                <?php endif;?>
                                <div class="clear"></div>
                            </div>
                            
                            <?php $siCounter++; endforeach;?>
                            <div class="attr_border"></div>
                            

                            <!-- Display Attribute Div List and advanced button if attributes were provided-->
                            <?php if((int)$attr['has_attr'] === 1):?>
                            <div class="shipping_attr sa_css pd-8-12">
                                <h5>Item Properties</h5>
                                <?php foreach($attr['attributes'] as $attrkey=>$temp):?>
                                <div class="attr_cont_css">
                                    <label class="<?php echo (in_array($attrkey,$shiparr['attr']) && $shipping_summary['has_shippingsummary']) || !$shipping_summary['has_shippingsummary'] ? 'active':'' ?>">
                                        <input class="shipattr" type="checkbox" name="shipattr[<?php echo $sgCounter?>][]" value="<?php echo $attrkey?>" <?php echo (in_array($attrkey,$shiparr['attr']) && $shipping_summary['has_shippingsummary']) || !$shipping_summary['has_shippingsummary'] ? 'checked':'' ?> >
                                        <?php foreach($temp as $keyAttr => $pattr):?>
                                            <?php $borderStyle = count($temp) - 1 === $keyAttr ? 'border-right:none'  : ''; ?>
                                            <span style="<?=$borderStyle ?>"><?=html_escape($pattr['name']) . ' : ' . html_escape($pattr['value']);?></span>
                                        <?php endforeach; ?>
                                    </label>
                                    <br/>
                                </div>
                                <?php endforeach;?>
                                <div class="clear"></div>
                            </div>
                            <?php else:?>
                            <div class="shipping_attr" style="display:none;">
                                <input class="shipattr" type="checkbox" name="shipattr[<?php echo $sgCounter?>][]" value="<?php echo $attr['product_item_id'];?>" checked onclick="return false;">
                            </div>
                            <?php endif;?>
                            <!--End of attribute section-->
                        </div>
                        
                        <div class="button_group bg-cl-f0f0f0">
                            <div class="new_shipping_input wbtn">
                                <div class="btn btn-default">
                                    <span class="add_bgs3 glyphicon glyphicon-plus-sign"></span>
                                    <span>ADD ROW</span>
                                </div>
                            </div>
                            
                            <?php if($sgCounter > 0):?>
                            <div class="del_shipping_group wbtn">
                                <div class="btn btn-default">
                                    <span class="del_bgs3 glyphicon glyphicon-remove-sign"></span>
                                    <span>REMOVE GROUP</span>
                                </div>
                            </div>
                            <?php endif;?>
                            
                            <div class="add_ship_preference wbtn preference_btn_css fl-right">
                                <div class="btn btn-default">
                                    <span class="add_bgs3 glyphicon glyphicon-plus-sign"></span>
                                    <span>ADD TO PREFERENCE</span>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php $sgCounter++; endforeach;?>
                    
                    <!-- Enable adding of new group when more than 1 attr is provided -->
                    <?php if((int)$attr['has_attr'] === 1):?>
                    <div class="new_shipping_group wbtn">
                        <div class="btn btn-default">
                            <span class="add_bgs3 glyphicon glyphicon-plus-sign"></span>
                            <span>ADD GROUP</span>
                        </div>
                    </div>
                    <?php endif;?>
                    
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <!--CLOSE id=SHIPPING DIV-->
    </div>
    </div>
    <!-- CLOSE step3_shipping_options-->

    <input type="hidden" id="checkData" value='<?=$json_check_data?>'>
    <input type="hidden" id="shippingPreference" value='<?=$json_shippingpreference?>'>

    <div class="pd-tb-45 text-center">
        <input id="finish_step3" type="button" value="Finish" class="orange_btn3 width-20p">
        <input type="hidden" id="prod_h_id" name="prod_h_id" value="<?php echo $product['id_product']?>">
        <input type="hidden" id="billing_info_id" name="billing_info_id" value="<?php echo isset($first_accnt['id_billing_info'])?$first_accnt['id_billing_info']:'0'; ?>"/>
        <input type="hidden" id="prod_delivery_cost" name="prod_delivery_cost" value="<?php echo $shipping_summary['str_deliverycost']?>">
        
        <?php if( isset($is_edit) ):?>
            <input type="hidden" name="is_edit" value="true">
        <?php endif;?>
    </div>
    <?php echo form_close();?>

    <?php 
        $attr = array('id'=>'finish_upload_form');
        echo form_open('sell/finishupload', $attr);
    ?>
        <input type="hidden" name="prod_h_id" value="<?php echo $product_id?>">
    <?php echo form_close();?>

    <div class="clear"></div>  

<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/assets/js/src/productUpload_step3.js?ver=<?=ES_FILE_VERSION?>"></script>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.numeric.js"></script>
    <script type='text/javascript' src="/assets/js/src/vendor/jquery-ui.js"></script>
    <script type='text/javascript' src='/assets/js/src/vendor/jquery.jqpagination.min.js'></script>
    <script type="text/javascript" src="/assets/js/src/vendor/jquery.simplemodal.js"></script>
    <script type="text/javascript" src="/assets/js/src/vendor/bower_components/chosen.jquery.min.js" ></script>
<?php else: ?>
    <script src="/assets/js/min/easyshop.product_upload_step3_view.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
<?php endif; ?>

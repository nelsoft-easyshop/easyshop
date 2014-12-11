<link type="text/css" href="/assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link rel="stylesheet" href="/assets/css/product_preview.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<link rel="stylesheet" href="/assets/css/bootstrap-mods.css" type="text/css" media="screen"/>
<div class="container">
    <div class="seller_product_content row">
        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
                <div class="sell_steps sell_steps4">
                    <ul> 
                        <li class="steps_txt_hide">
                          <a href="javascript:void(0)" id="step1_link">
                            <span class="span_bg left-arrow-shape2"></span>
                            <span class="steps_txt">Step 1: Select Category</span>
                            <span class="span_bg right-arrow-shape"></span>
                          </a>
                         </li>
                        <li class="steps_txt_hide">
                            <a href="javascript:void(0)" id="step2_link">
                                <span class="span_bg left-arrow-shape2"></span>
                                <span class="steps_txt">Step 2: Upload Item</span>
                                <span class="span_bg right-arrow-shape"></span>
                            </a>
                        </li>                   
                        <li class="steps_txt_hide">
                            <a href="javascript:void(0)" id="step3_link">
                               <span class="span_bg left-arrow-shape2"></span>
                               <span class="steps_txt">Step 3: Shipping Location</span>
                               <span class="span_bg right-arrow-shape"></span>
                            </a>
                        </li>
                        <li>
                            <span class="span_bg left-arrow-shape ar_active"></span>
                            <span class="steps_txt_active"><span class="f18">Success</span></span>
                            <span class="span_bg right-arrow-shape ar_r_active"></span>
                        </li>
                    </ul>
                </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
        <?php echo form_open('sell/step1', array('id'=>'edit_step1'));?>
            <input type="hidden" name="p_id" id="p_id" value="<?=$product->getIdProduct();?>">
        <?php echo form_close();?>
        
        <?php echo form_open('sell/edit/step2', array('id'=>'edit_step2'));?>
            <input type="hidden" name="p_id" id="p_id" value="<?=$product->getIdProduct();?>">
            <input type="hidden" name="hiddenattribute" value="<?=$product->getCat()->getIdCat();?>">
            <input type="hidden" name="othernamecategory" value="<?=html_escape($product->getCatOtherName()); ?>">
        <?php echo form_close();?>

        <?php echo form_open('sell/step3', array('id'=>'edit_step3'));?>
            <input type="hidden" name="prod_h_id" id="p_id" value="<?=$product->getIdProduct();?>">
            <input type="hidden" name="is_edit" value="true">
        <?php echo form_close();?>

<div class="container step4_section mrgn-top-35">
    <div class="seller_product_content">
        <div class="row">
            <div class="step4_header col-xs-12">
                <h5>How you will be paid</h5>
            </div>
            <div class="clear"></div>
            <div class="step4_content step4_paysel pd-tb-15">
                <?php if( !empty($productBillingInfo) ):?>
                <div class="step4_bankdetails col-sx-12 col-sm-7 col-md-7 pd-bttm-15">
                    <div class="row pd-top-15">
                        <div class="col-xs-3 col-sm-4 col-md-4"><strong>Bank account name:</strong></div>
                        <div class="col-xs-9 col-sm-8 col-md-8"><?=html_escape($productBillingInfo['bankAccountName']);?></div>
                    </div>
                    <div class="row pd-top-15">
                        <div class="col-xs-3 col-sm-4 col-md-4"><strong>Bank account number:</strong></div>
                        <div class="col-xs-9 col-sm-8 col-md-8"><?=html_escape($productBillingInfo['bankAccountNumber']);?></div>
                    </div>
                    <div class="row pd-top-15">
                        <div class="col-xs-3 col-sm-4 col-md-4"><strong>Bank name:</strong></div>
                        <div class="col-xs-9 col-sm-8 col-md-8"><?=html_escape($productBillingInfo['bankName']);?></div>
                    </div>
                </div>
                <?php else:?>
                <div class="col-xs-12">
                    <h4><p>No Bank Details.</p></h4> 
                </div>
                <?php endif;?>
                <?php if( (int)$product->getIsCod() === 1 ):?>
                <div class="step4-cod col-sx-12 col-sm-5 col-md-5">
                    <span class="cod-images"></span>
                    <div class="cod-button">
                        <span>Cash on Delivery</span>
                    </div>
                </div>
                <?php endif;?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<div class="container step4_section mrgn-top-35">
    <div class="seller_product_content">
        <div class="row">
            <div class="step4_header col-xs-12">
                <h5>Product Delivery</h5>
            </div>
            <div class="clear"></div>
            <div class="step4_content step4_delivery col-xs-12 pd-top-15">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-9">
                        <div class="row">
                            <?php if( (int)$product->getIsMeetup() === 1 ):?>
                                <div class="col-sx-12 col-sm-12 col-md-3 pd-bttm-15">
                                    <div class="ok-btn glyphicon glyphicon-ok pd-8-12"></div> 
                                    <span class="pd-lr-10">For meetup</span>
                                </div>
                            <?php endif;?>
            
                            <?php if( $shipping_summary['is_delivery'] ):?>
                                <div class="col-sx-12 col-sm-12 col-md-3">
                                    <div class="ok-btn glyphicon glyphicon-ok pd-8-12"></div> 
                                    <span class="pd-lr-10">For delivery</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if( $shipping_summary['is_delivery'] ):?>
                    <div class="clear"></div>
                    <div>
                        <div class="step4_delivery_sub">
                            <div class="clear"></div>
                            <?php if( $shipping_summary['is_freeshipping'] ):?>
                                <p>Free shipping</p>
                            <?php elseif( $shipping_summary['has_shippingsummary'] ):?>
                                <p><strong>Shipping Details:</strong></p>
                                <?php foreach( $shipping_summary['shipping_display'] as $garr ):?>
                                    <div>
                                        <div class="clear"></div>
                                        <div class="pd-top-4">
                                            <?php foreach( $garr['location'] as $price=>$locarr ):?>
                                            <div class="row col-sx-mrgn">
                                                <div class="col-sx-12 col-sm-4 col-md-4">
                                                    <span>&#8369;</span>
                                                    <div class="delivery-sub-box step4-price"><?=html_escape($price)?></div>
                                                </div>
                                                <div class="col-sx-12 col-sm-8 col-md-8">
                                                    <span class="display-ib line-height">Locations:</span>
                                                    <div class="delivery-sub-box width-75p">
                                                        <?php foreach($locarr as $locID):?>
                                                        <span class="delivery-sub-box-item"><?=$shipping_summary['location_lookup'][$locID]?></span>
                                                        <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach;?>
                                            <div class="clear"></div>
                                            <div class="step4_attr">
                                                
                                                <div class="col-sx-12 col-sm-12 col-md-12">
                                                    <?php if( !$attr['has_attr'] ):?>
                                                        <p>&bull; All Combinations</p>
                                                    <?php else:?>
                                                        <?php foreach($garr['attr'] as $attrID): ?>
                                                            <p><span class="glyphicon glyphicon-chevron-right"></span>
                                                                <?php foreach( $attr['attributes'][$attrID] as $pattr ):?>
                                                                    <?php echo $pattr['name'] . ' : ' . $pattr['value'] . ' '?>
                                                                <?php endforeach;?>
                                                            </p>
                                                        <?php endforeach; ?>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endif;?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

        <div style="margin-top:3em;">
            <div>
                <div class="container step4_section" style="max-height:100%; border:1px solid #CECECE;padding:0 0 16px 0;">
                    <div class="step4_header col-xs-12">
                        <h5>Product Preview</h5>
                    </div>
                    <?=$productView; ?>
                </div>
            </div>
        </div>



<div class="container">
    <div class="seller_product_content">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 text-center">
                <div class="pd-tb-20">
                    <a href="/sell/step1" target="_blank" class="orange_btn3 vrtcl-mid">Sell another Item</a>
                    <a href="/item/<?=$product->getSlug();?>" target="_blank" class="btn btn-default">View Product</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>



<script src="/assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $('#tabs').tabs();
</script>
<script type="text/javascript">
    $('#step1_link').on('click', function(){
        $('#edit_step1').submit();
    });
    $('#step2_link').on('click', function(){
        $('#edit_step2').submit();
    });
    $('#step3_link').on('click', function(){
        $('#edit_step3').submit();
    });

</script>

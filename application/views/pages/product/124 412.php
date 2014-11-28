<link rel="stylesheet" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/> 
<link rel="stylesheet" href="/assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/> 


<div class="clear"></div>


<div class="wrapper" id="main_search_container">
  
    <?php echo $deals_banner; ?>

    <div class="cd_product_container" id="peak_hour_sale">
        <div class='product_list'>
            <?php if(isset($peak_hour_items)) : ?>
                <?php foreach($peak_hour_items as $item): ?>
                    <?php if($item['is_promote'] == 1 && $item['promo_type'] == 3): ?>
                        <div class="cd_product">	

                            <?php if($item['is_sold_out'] || !$item['can_purchase'] ): ?>
                                <a href="javascript:void(0)" style='cursor: default;' class="cd_link_con">
                            <?php else: ?>
                                <a href="<?= '/item/'.$item['slug'];?>" class="cd_link_con">
                            <?php endif; ?>
                        
                        
                                <?PHP $Md_start =  date('M d', strtotime($item['startdate'])); ?>
                                <?PHP $Md_second_start = date('M d', strtotime("-7 day",strtotime($item['startdate'])))?>
                    
                                <div class="product_buy_con prod_date <?PHP echo ($item['can_purchase'] || strtotime(date('M d'))===strtotime($Md_start) )  ?'':'disabled'; ?>">
                                    <span>
                                        <p><?php echo date('d',strtotime($Md_second_start)) . " and " . $Md_start; ?></p>
                                    </span>
                                    
                                    </div>
                                    <div class="product_buy_con">
                                        <span><span class="orange_btn3 <?php echo $item['is_sold_out']||(!$item['start_promo'])?'disabled':'enabled';?>">BUY NOW</span></span>
                                    </div>


                                    <?php if($item['is_sold_out']): ?>
                                        <div class="cd_soldout">
                                            <p>SOLDOUT</p>
                                        </div>
                                    <?php endif; ?>


                                    <div>

                                        <?php if(isset($item['percentage']) && $item['percentage'] > 0):?>
                                            <span class="cd_slide_discount">
                                                <span><?php echo number_format($item['percentage'],0,'.',',');?>%<br>OFF</span>
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <span class="cd_prod_img_con">
                                        <img src="/<?= $item['path'].'categoryview/'.$item['file']; ?>">
                                    </span>

                                    <h3>
                                        <?php echo  es_string_limit(html_escape($item['name']), 54, '...');?>
                                    </h3>
                    
                                    <div class="price-cnt">
                                        <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                                        <?php if(isset($item['percentage']) && $item['percentage'] > 0): ?>
                                            <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cd_condition">
                                        <b>Condition: </b> <span style='font-weight: 10px;'><?= ($item['is_free_shipping'])? es_string_limit(html_escape($item['condition']),15) : html_escape($item['condition']);?></span>
                                        <?PHP if($item['is_free_shipping']): ?>
                                            <span style="float:right;"><span class="span_bg img_free_shipping"></span>
                                        <?PHP endif; ?>	
                                    </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>



    <div id="easytreats">
        <?php foreach($items as $item): ?>
            <div class="cd_product">

                <?php if($item['is_sold_out'] || !	$item['can_purchase']): ?>
                    <a href="javascript:void(0)" style='cursor: default;' class="cd_link_con">
                <?php else: ?>
                    <a href="<?= '/item/'.$item['slug'];?>" class="cd_link_con">
                <?php endif; ?>
                
                            
                    <?php if(intval($item['is_promote']) === 1): ?>
                        
                        <div class="product_buy_con">
                            <span><span class="orange_btn3 <?php echo $item['is_sold_out']||(!$item['start_promo'])?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>

                    <?php else: ?>
                        <div class="product_buy_con">
                            <span><span class="orange_btn3 <?php echo $item['is_sold_out']?'disabled':'enabled';?>">BUY NOW</span></span>
                        </div>
                    <?php endif; ?>

                    <?php if($item['is_sold_out']): ?>
                        <div class="cd_soldout">
                            <p>SOLDOUT</p>
                        </div>
                    <?php endif; ?>


                    <div>

                        <?php if(isset($item['percentage']) && $item['percentage'] > 0):?>
                            <span class="cd_slide_discount">
                                <span><?php echo number_format($item['percentage'],0,'.',',');?>%<br>OFF</span>
                            </span>
                        <?php endif; ?>
                        
                    </div>

                    <span class="cd_prod_img_con">
                        <img src="/<?= $item['path'].'categoryview/'.$item['file']; ?>">
                    </span>

                    <h3>
                        <?php echo  es_string_limit(html_escape($item['name']), 54, '...');?>
                    </h3>
                            
                    <div class="price-cnt">
                        <div class="price">PHP <?php echo number_format($item['price'],2,'.',',');?></div>
                        <?php if(isset($item['percentage']) && $item['percentage'] > 0): ?>
                            <div class="discount_price">PHP <?php echo number_format($item['original_price'],2,'.',',');?></div>
                        <?php endif; ?>
                    </div>
                                
                                
                    <div class="cd_condition">
                        <b>Condition: </b> <span style='font-weight: 10px;'><?= ($item['is_free_shipping'])? es_string_limit(html_escape($item['condition']),15) : html_escape($item['condition']);?></span>
                        <?PHP if($item['is_free_shipping']): ?>
                            <span style="float:right;"><span class="span_bg img_free_shipping"></span>
                        <?PHP endif; ?>	
                    </div>
                        
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script>
    
    
    $(document).ready(function(){
        var base_url = '/';
        var offset = 1;
        var request_ajax = true;
        var ajax_is_on = false;
        var objHeight = $(window).height() - 50;
        var last_scroll_top = 0;
    
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $(window).scroll(function(event) {
            var st = $(this).scrollTop();
            if(st > last_scroll_top){
                if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {
                    if (request_ajax === true && ajax_is_on === false) {
                        ajax_is_on = true; 
                        $.ajax({
                            url: base_url + 'deals_more',
                            data:{page_number:offset,csrfname : csrftoken},
                            type: 'post',
                            dataType: 'JSON',
                            onLoading:jQuery(".loading_products").html('<img src="/assets/images/orange_loader.gif" />').show(),
                            success: function(d) {
                                if(d == "0"){
                                    ajax_is_on = true;
                                }else{ 
                                    $($.parseHTML(d.trim())).appendTo($('.product_list'));
                                    ajax_is_on = false;
                                    offset += 1;   
                                }
                               jQuery(".loading_products").fadeOut();    
                            }
                        });
                    }
                }
            }
            last_scroll_top = st;
        });


    })

</script>
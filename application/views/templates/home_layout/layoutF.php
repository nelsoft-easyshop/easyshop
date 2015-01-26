
<link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/bootstrap-mods.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/style.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/responsive_css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>


<div class="clear"></div>
<div class="mrgn-top-35"></div>
<div class="<?php echo ES_ENABLE_CHRISTMAS_MODS ? 'res_wrapper feeds-cont christmas-theme-fixed-con' : 'res_wrapper feeds-cont'?>">
    <div class="">
        <!-- LEFT PANEL --> 
        <div class="col-md-3 col-sm-12 l-screen feed-left-panel">
        <div id="feed-left-panel">
            <div class="row mrgn-bttm-8">
                <div class="col-md-12">
                    <div class="col-md-10">
                        <div class="row feed-cat">
                            <div id="feed-categories" class="pd-8-12">Categories</div>
                            <div id="feed-catlist" class="<?php echo $isCollapseCategories ? 'feed-catlist-collapseable' : ''; ?>">
                                <?php echo $category_navigation; ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
            <?php if(!$isCollapseCategories): ?>
                <div class="row mrgn-bttm-8">
                    <div class="col-md-12">
                        <div class="col-md-10">
                            <div class="row">
                                <a href="<?php echo $banners['left']['target']?>">
                                    <img class="img-responsive" src="/<?php echo $banners['left']['img']?>">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
      
            <div class="row mrgn-bttm-8">
                <div class="col-md-12">
                    <div class="col-md-10">
                        <div class="row table-bordered">
                            <div class="border-bottom pd-8-12 title">Followed Sellers</div>
                            <div class="followed-sellers-cont">                            
                                <?php if( count($followed_users) === 0 ):?>
                                    <p class="no-subs-lbl">You have no subscriptions.</p>
                                <?php else:?>
                                    <?php $count = 0 ; ?>
                                    <?php for($i = 0; $i < count($followed_users) && $i < $maxDisplayableSellers; $i ++): ?>
                                        <?php $fu = $followed_users[$i]; ?>
                                        <div class="media pd-8-12 side-panel">
                                            <div class="pull-left media-image">
                                                <a class="" href="/<?php echo html_escape($fu['userslug'])?>">
                                                    <img class="media-object" src="/<?php echo  $fu['imgurl']?>">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="title"><a href="/<?php echo html_escape($fu['userslug'])?>"><?php echo html_escape($fu['vendor_name'])?></a></h5>
                                                <span class="sub-title"><?php echo $fu['datecreated']?></span>
                                            </div>              
                                            <hr style='margin-bottom: 3px;'/>
                                        </div>
                                    <?php endfor?>     
                          

                                    <div class='following-lnk'>
                                            <span>Following (<?php echo count($followed_users)?>) users </span>
                                    </div>

                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if($isCollapseCategories): ?>
                <div class="row mrgn-bttm-8">
                    <div class="col-md-12">
                        <div class="col-md-10">
                            <div class="row">
                                <a href="<?php echo $banners['left']['target']?>">
                                    <img class="img-responsive" src="/<?php echo $banners['left']['img']?>">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        </div>
        
        <!-- MID PANEL -->
        <div class="col-md-6 col-xs-12 feed-mid-panel feed-middle-panel">
            <div class="row-2">
                <div class="row mrgn-bttm-8">
                    <div class="col-md-12 ">
                        <a href="<?php echo $banners['mid']['target']?>">
                            <img class="img-responsive" src="/<?php echo $banners['mid']['img']?>">
                        </a>
                    </div>
                </div>
                <div class="row mrgn-bttm-8  feed-center">
                    <div class="col-md-12">
                        <div class="mrgn-bttm-8">
                            <div class="">
                                <div class="table-bordered pd-8-12 ">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 feed-menu active"><a href="#featured_prod">Featured Products</a></div>
                                        <div class="col-md-4 col-xs-4 feed-menu"><a href="#new_prod">New Products</a></div>
                                        <div class="col-md-4 col-xs-4 feed-menu"><a href="#easytreats_prod">Easy Treats</a></div>
                                    </div>
                                    <div class="row s-screen m-screen">
                                        <div class="col-xs-4 feed-menu"><a href="#m_follow_seller">Followed Seller</a></div>
                                        <div class="col-xs-4 feed-menu"><a href="#m_promo_items">Promo Items</a></div>
                                        <div class="col-xs-6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach($featured_product as $prod):?>
                        <div class="media table-bordered mrgn-bttm-8 product feature">
                            <div class="col-md-9 col-sm-9 media-sub media-content">
                                <div class="pull-left media-image">
                                    <a href="<?php echo "/item/" . $prod['slug']?>">
                                        <img class="media-object" src="/<?php echo $prod['path'] . "categoryview/" . $prod['file']?>">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="content">
                                        <h5 class="title"><a href="<?php echo "/item/" . $prod['slug']?>"><?php echo $prod['product_name']?></a></h5>
                                        <?php echo html_escape($prod['brief'])?>
                                    </div>
                                    <div class="condition m-screen l-screen">
                                        <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                            <span class="span_bg img_free_shipping"></span>
                                        <?php endif;?>
                                        Condition: <?php echo html_escape($prod['condition'])?>
                                    </div>
                                </div>
                                <div class="condition s-screen">
                                    <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                        <span class="span_bg img_free_shipping"></span>
                                    <?php endif;?>
                                    Condition: <?php echo html_escape($prod['condition'])?>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                <p>PHP</p>
                                <p class="feed-price"><?php echo number_format($prod['price'],2,'.',',')?></p>
                                <div class="orig-price">
                                    <?php if( $prod['discount']>0 ):?>
                                        <?php echo "Php " . number_format($prod['original_price'],2,'.',',')?>
                                    <?php endif;?>
                                </div>
                                <div class="orange-btn"><a href="<?php echo  "/item/" . $prod['slug']?>">Buy Now</a></div>
                            </div>
                        </div>
                        <?php endforeach;?>

                        <div id="featured_prod" class="feed-prod-cont" style="display:block;">
                            <?php foreach( $featured_prod as $prod ):?>
                            <div class="media table-bordered mrgn-bttm-8 product">
                                <div class="col-md-9 col-sm-9 media-sub media-content">
                                    <div class="pull-left media-image">
                                        <a href="<?php echo "/item/" . $prod['slug']?>">
                                            <img class="media-object" src="/<?php echo $prod['path'] . "categoryview/" . $prod['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="content">
                                            <h5 class="title"><a href="<?php echo "/item/" . $prod['slug']?>"><?php echo html_escape($prod['name'])?></a></h5>
                                            <?php echo html_escape($prod['brief'])?>
                                        </div>
                                        <div class="condition m-screen l-screen">
                                            <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                                <span class="span_bg img_free_shipping"></span>
                                            <?php endif;?>
                                            Condition: <?php echo html_escape($prod['condition'])?>
                                        </div>
                                    </div>
                                    <div class="condition s-screen">
                                        <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                            <span class="span_bg img_free_shipping"></span>
                                        <?php endif;?>
                                        Condition: <?php echo html_escape($prod['condition'])?>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                    <p>PHP</p>
                                    <p class="feed-price"><?php echo number_format($prod['price'],2,'.',',')?></p>
                                    <div class="orig-price">
                                        <?php if( $prod['discount']>0 ):?>
                                            <?php echo "Php " . number_format($prod['original_price'],2,'.',',')?>
                                        <?php endif;?>
                                    </div>
                                    <div class="orange-btn"><a href="<?php echo "/item/" . $prod['slug']?>">Buy Now</a></div>
                                </div>
                            </div>
                            <?php endforeach;?>
                            
                            <div class="mrgn-bttm-8 row-loadmore load_more_div">
                                <div class="mrgn-top-35">
                                    <?php echo form_open("",array("class"=>"load_more_form"));?>
                                    <input type="hidden" name="feed_page" value="1">
                                    <input type="hidden" name="feed_set" value="1">
                                    <input type="hidden" name="ids" value='<?php echo $fpID?>'>
                                    <?php echo form_close();?>
                                    <input type="button" class="orange-btn load-more feed_load_more" value="Load More">
                                </div>
                            </div>
                        </div>
                        <div id="new_prod" class="feed-prod-cont">
                            <?php foreach( $new_prod as $prod ):?>
                            <div class="media table-bordered mrgn-bttm-8 product">
                                <div class="col-md-9 col-sm-9 media-sub media-content">
                                    <div class="pull-left media-image">
                                        <a href="<?php echo "/item/" . $prod['slug']?>">
                                            <img class="media-object" src="/<?php echo $prod['path'] . "categoryview/" . $prod['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="content">
                                            <h5 class="title"><a href="<?php echo "/item/" . $prod['slug']?>"><?php echo html_escape($prod['name'])?></a></h5>
                                            <?php echo html_escape($prod['brief'])?>
                                        </div>
                                        <div class="condition m-screen l-screen">
                                            <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                                <span class="span_bg img_free_shipping" style="float:right;"></span>
                                            <?php endif;?>
                                            Condition: <?php echo html_escape($prod['condition'])?>
                                        </div>
                                    </div>
                                    <div class="condition s-screen">
                                        <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                            <span class="span_bg img_free_shipping"></span>
                                        <?php endif;?>
                                        Condition: <?php echo html_escape($prod['condition'])?>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                    <p>PHP</p>
                                    <p class="feed-price"><?php echo number_format($prod['price'],2,'.',',')?></p>
                                    <div class="orig-price">
                                        <?php if( $prod['discount']>0 ):?>
                                            <?php echo "Php " . number_format($prod['original_price'],2,'.',',')?>
                                        <?php endif;?>
                                    </div>
                                    <div class="orange-btn"><a href="<?php echo  "/item/" . $prod['slug']?>">Buy Now</a></div>
                                </div>
                            </div>
                            <?php endforeach;?>
                            
                            <div class="mrgn-bttm-8 row-loadmore load_more_div">
                                <div class="">
                                    <?php echo form_open("",array("class"=>"load_more_form"));?>
                                    <input type="hidden" name="feed_page" value="1">
                                    <input type="hidden" name="feed_set" value="2">
                                    <?php echo form_close();?>
                                    <input type="button" class="orange-btn load-more feed_load_more" value="Load More">

                                </div>
                            </div>
                        </div>
                        <div id="easytreats_prod" class="feed-prod-cont">
                            <?php foreach( $easytreats_prod as $prod ):?>
                            <div class="media table-bordered mrgn-bttm-8 product">
                                <div class="col-md-9 col-sm-9 media-sub media-content">
                                    <div class="pull-left media-image">
                                        <a href="<?php echo "/item/" . $prod['slug']?>">
                                            <img class="media-object" src="/<?php echo $prod['path'] . "categoryview/" . $prod['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="content">
                                            <h5 class="title"><a href="<?php echo "/item/" . $prod['slug']?>"><?php echo html_escape($prod['name'])?></a></h5>
                                            <?php echo html_escape($prod['brief'])?>
                                        </div>
                                        <div class="condition m-screen l-screen">
                                            <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                                <span class="span_bg img_free_shipping"></span>
                                            <?php endif;?>
                                            Condition: <?php echo html_escape($prod['condition'])?>
                                        </div>
                                    </div>
                                    <div class="condition s-screen">
                                        <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                            <span class="span_bg img_free_shipping"></span>
                                        <?php endif;?>
                                        Condition: <?php echo html_escape($prod['condition'])?>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                    <p>PHP</p>
                                    <p class="feed-price"><?php echo number_format($prod['price'],2,'.',',')?></p>
                                    <div class="orig-price">
                                        <?php if( $prod['discount']>0 ):?>
                                            <?php echo "Php " . number_format($prod['original_price'],2,'.',',')?>
                                        <?php endif;?>
                                    </div>
                                    <div class="orange-btn"><a href="<?php echo "/item/" . $prod['slug']?>">Buy Now</a></div>
                                </div>
                            </div>
                            <?php endforeach;?>
                            
                            <div class="mrgn-bttm-8 row-loadmore load_more_div">
                                <div class="">
                                    <?php echo form_open("",array("class"=>"load_more_form"));?>
                                    <input type="hidden" name="feed_page" value="1">
                                    <input type="hidden" name="feed_set" value="3">
                                    <?php echo form_close();?>
                                    <input type="button" class="orange-btn load-more feed_load_more" value="Load More">
                                    
                                </div>
                            </div>
                        </div>
                        <div id="m_follow_seller" class="row feed-prod-cont">
                            <?php if( count($followed_users) === 0 ):?>
                                <div class="col-md-12">
                                    <p class="no-subs-lbl-responsive">You are not subscribed to anybody yet.</p>
                                </div>
                            <?php else:?>
                                <div class="col-xs-6">
                                <?PHP $usercount = count($followed_users); ?>
                                <?php $uCounter = 0; ?>
                                    <?php foreach($followed_users as $fu):?>
                                        <div class="media pd-8-12 seller-container">
                                            <div class="pull-left media-image" >
                                                <a href="/<?php echo html_escape($fu['vendor_name'])?>">
                                                    <span>
                                                        <img class="media-object" src="/<?php echo $fu['imgurl']?>">
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="title"><a href="/<?php echo html_escape($fu['userslug'])?>"><?php echo html_escape($fu['vendor_name'])?></a></h5>
                                                <span class="sub-title"><span class='join-lbl'>Joined: </span><?php echo $fu['datecreated']?></span>
                                            </div>
                                        </div>
                                        <?PHP $uCounter++; ?>
                                        <?php if( $uCounter%2 === 0): ?>
                                            </div><div class="col-xs-6">
                                        <?php else: ?>
                                            </div><div>
                                        <?PHP endif;?>
                                    
                                    <?php endforeach;?>
                                </div>
                            <?php endif;?>
                        </div>
                        <div id="m_promo_items" class="row feed-prod-cont">
                            <div class="col-md-12 ">
                                <?php foreach( $promo_items as $prod ):?>
                                <div class="media table-bordered mrgn-bttm-8 product">
                                    <div class="col-md-9 col-sm-9 media-sub media-content">
                                        <div class="pull-left media-image">
                                            <a href="<?php echo "/item/" . $prod['slug']?>">
                                                <img class="media-object" src="/<?php echo $prod['path'] . "categoryview/" . $prod['file']?>">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="content">
                                                <h5 class="title"><a href="<?php echo "/item/" . $prod['slug']?>"><?php echo html_escape($prod['product_name'])?></a></h5>
                                                <?php echo html_escape($prod['brief'])?>
                                            </div>
                                            <div class="condition m-screen l-screen">
                                                <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                                    <span class="span_bg img_free_shipping"></span>
                                                <?php endif;?>
                                                Condition: <?php echo html_escape($prod['condition'])?>
                                            </div>
                                        </div>
                                        <div class="condition s-screen">
                                            <?php if( intval($prod['is_free_shipping'])===1 ):?>
                                                <span class="span_bg img_free_shipping"></span>
                                            <?php endif;?>
                                            Condition: <?php echo html_escape($prod['condition'])?>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                        <p>PHP</p>
                                        <p class="feed-price"><?php echo number_format($prod['price'],2,'.',',')?></p>
                                        <div class="orig-price">
                                            <?php if( $prod['discount']>0 ):?>
                                                <?php echo "Php " . number_format($prod['original_price'],2,'.',',')?>
                                            <?php endif;?>
                                        </div>
                                        <div class="orange-btn"><a href="<?php echo "/item/" . $prod['slug']?>">Buy Now</a></div>
                                    </div>
                                </div>
                                <?php endforeach;?>
                                
                                <div class="mrgn-bttm-8 row-loadmore load_more_div">
                                    <div class="">
                                        <?php echo form_open("",array("class"=>"load_more_form"));?>
                                        <input type="hidden" name="feed_page" value="1">
                                        <input type="hidden" name="feed_set" value="2">
                                        <?php echo form_close();?>
                                        <input type="button" class="orange-btn load-more feed_load_more" value="Load More">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- RIGHT PANEL -->
        <div class="col-md-3 l-screen feed-right-panel">
            <div id="feed-right-panel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-10 col-md-offset-2 mrgn-bttm-8">
                            <div class="row">
                                <div class="table-bordered">
                                    <div class="border-bottom pd-8-12 title">Popular Items</div>
                                    <?php foreach($popular_items as $prod):?>
                                        <div class="media pd-8-12 side-panel">
                                            <div class="pull-left media-image">
                                                <a href="<?php echo "/item/" . $prod['slug']?>">
                                                    <img class="media-object" src="/<?php echo $prod['path'] . "thumbnail/" . $prod['file']?>">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="title"><a href="<?php echo "/item/" . $prod['slug']?>"><?php echo html_escape($prod['product_name'])?></a></h5>
                                                <span class="sub-title">PHP <?php echo number_format($prod['price'],2,'.',',')?></span>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10 col-md-offset-2 mrgn-bttm-8">
                            <div class="row">
                                <a href="<?php echo $banners['right']['target']?>">
                                    <img class="img-responsive" src="/<?php echo $banners['right']['img']?>">
                                </a>
                            </div>
                        </div>
                        <div class="col-md-10 col-md-offset-2 mrgn-bttm-8">
                            <div class="row">
                                <div class="table-bordered">
                                    <div class="border-bottom pd-8-12 title">Promo Items</div>
                                    <?php foreach($promo_items as $prod):?>
                                        <div class="media pd-8-12 side-panel">
                                            <div class="pull-left media-image">
                                                <a href="<?php echo "/item/" . $prod['slug']?>">
                                                    <img class="media-object" src="/<?php echo $prod['path'] . "thumbnail/" . $prod['file']?>">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h5 class="title"><a href="<?php echo  "/item/" . $prod['slug']?>"><?php echo html_escape($prod['product_name'])?></a></h5>
                                                <span class="sub-title">PHP <?php echo number_format($prod['price'],2,'.',',') ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    
</div>

<script type="text/javascript" src="/assets/js/src/vendor/jquery.sticky-sidebar-scroll.min.js"></script>
<script type="text/javascript" src="/assets/js/src/feed.js?ver=<?=ES_FILE_VERSION?>"></script>


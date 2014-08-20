<!DOCTYPE html>
<html lang="en">

    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/css/style.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?=base_url()?>assets/css/responsive_css.css" type="text/css" media="screen"/>
    <style type="text/css">
    .res_wrapper .top_nav ul li a, .res_wrapper .top_links_right a {
        font-size: 0.85em;
    }
    </style>

<div class="clear"></div>
<div class="mrgn-top-35"></div>
<div class="res_wrapper feeds-cont">
    <div class="">
        <!-- LEFT PANEL -->
        <div class="col-md-3 col-sm-12 l-screen">
            <div class="row mrgn-bttm-8">
                <div class="col-md-12">
                    <div class="col-md-10">
                        <div class="row feed-cat">
                            <div id="feed-categories" class="pd-8-12">Categories</div>
                            <div id="feed-catlist">
                                <?php echo $category_navigation; ?> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mrgn-bttm-8">
                <div class="col-md-12">
                    <div class="col-md-10">
                        <div class="row table-bordered">
                            <div class="border-bottom pd-8-12 title">Followed Sellers</div>
                            <div class="">
                                <?php if( count($followed_users) === 0 ):?>
                                    <p class="pd-8-12">No subscriptions yet</p>
                                    <?php else:?>
                                        <?php foreach($followed_users as $fu):?>
                                        <div class="media pd-8-12 ">
                                            <a class="pull-left" target="_blank" href="<?php echo base_url() . html_escape($fu['userslug'])?>">
                                                <img class="media-object" src="<?php echo base_url() . $fu['imgurl']?>">
                                            </a>
                                            <div class="media-body">
                                                <h5 class="title"><a target="_blank" href="<?php echo base_url() . html_escape($fu['userslug'])?>"><?php echo html_escape($fu['vendor_name'])?></a></h5>
                                                <?php echo $fu['datecreated']?>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mrgn-bttm-8">
                <div class="col-md-12">
                    <div class="col-md-10">
                        <div class="row">
                            <a href="<?php echo base_url() . $banners['left']['target']?>">
                                <img class="img-responsive" src="<?=base_url()?><?php echo $banners['left']['img']?>">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MID PANEL -->
        <div class="col-md-6 col-xs-12 feed-mid-panel">
            <div class="row-2">
                <div class="row mrgn-bttm-8">
                    <div class="col-md-12 ">
                        <a href="<?php echo base_url() . $banners['mid']['target']?>">
                            <img class="img-responsive" src="<?=base_url()?><?php echo $banners['mid']['img']?>">
                        </a>
                    </div>
                </div>
                <div class="row mrgn-bttm-8">
                    <div class="col-md-12">
                        <div class="mrgn-bttm-8">
                            <div class="">
                                <div class="table-bordered pd-8-12 ">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 feed-menu active"><a href="#featured_prod">Featured Products</a></div>
                                        <div class="col-md-4 col-xs-4 feed-menu"><a href="#new_prod">New Products</a></div>
                                        <div class="col-md-4 col-xs-4 feed-menu"><a href="#easy_treats">Easy Treats</a></div>
                                    </div>
                                    <div class="row s-screen m-screen">
                                        <div class="col-xs-4 feed-menu"><a href="#m_follow_seller">Followed Seller</a></div>
                                        <div class="col-xs-4 feed-menu"><a href="#m_promo_items">Promo Items</a></div>
                                        <div class="col-xs-6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach($featured_product as $p):?>
                        <div class="media table-bordered mrgn-bttm-8 product feature">
                            <div class="col-md-9 col-sm-9 media-sub media-content">
                                <div class="pull-left media-image">
                                    <a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>">
                                        <img class="media-object" src="<?=base_url()?><?php echo $p['path'] . "categoryview/" . $p['file']?>">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="content">
                                        <h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>"><?php echo $p['product_name']?></a></h5>
                                        <?php echo $p['brief']?>
                                    </div>
                                    <div class="condition m-screen l-screen">Condition: <?php echo $p['condition']?></div>
                                </div>
                                <div class="condition s-screen">Condition: <?php echo $p['condition']?></div>
                            </div>
                            <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                <p>Php</p>
                                <p class="feed-price"><?php echo html_escape(number_format($p['original_price'], 2, '.', ','))?></p>
                                <div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>">Buy Now</a></div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div id="featured_prod" class="feed-prod-cont" style="display:block;">
                            <?php foreach( $featured_prod as $prod ):?>
                            <div class="media table-bordered mrgn-bttm-8 product">
                                <div class="col-md-9 col-sm-9 media-sub media-content">
                                    <div class="pull-left media-image">
                                        <a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">
                                            <img class="media-object" src="<?php echo base_url() . $prod['path'] . "categoryview/" . $prod['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="content">
                                            <h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>"><?php echo $prod['name']?></a></h5>
                                            <?php echo $prod['brief']?>
                                        </div>
                                        <div class="condition m-screen l-screen">Condition: <?php echo $prod['condition']?></div>
                                    </div>
                                    <div class="condition s-screen">Condition: <?php echo $prod['condition']?></div>
                                </div>
                                <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                    <p>Php</p>
                                    <p class="feed-price"><?php echo $prod['price']?></p>
                                    <div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">Buy Now</a></div>
                                </div>
                            </div>
                            <?php endforeach;?>
                            
                            <div class="mrgn-bttm-8 row-loadmore load_more_div">
                                <div class="">
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
                                        <a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">
                                            <img class="media-object" src="<?php echo base_url() . $prod['path'] . "categoryview/" . $prod['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="content">
                                            <h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>"><?php echo $prod['name']?></a></h5>
                                            <?php echo $prod['brief']?>
                                        </div>
                                        <div class="condition m-screen l-screen">Condition: <?php echo $prod['condition']?></div>
                                    </div>
                                    <div class="condition s-screen">Condition: <?php echo $prod['condition']?></div>
                                </div>
                                <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                    <p>Php</p>
                                    <p class="feed-price"><?php echo $prod['price']?></p>
                                    <div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">Buy Now</a></div>
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
                        <div id="m_follow_seller" class="row feed-prod-cont">
                            <?php if( count($followed_users) === 0 ):?>
                                <div class="col-md-12">
                                    <p class="pd-8-12">No subscriptions yet</p>
                                </div>
                            <?php else:?>
                                <div class="col-xs-6">
                                <?PHP $usercount = count($followed_users);
                                    $uCounter = 0;
                                ?>
                                <?php foreach($followed_users as $fu):?>
                                    <div class="media pd-8-12 ">
                                        <a class="pull-left" target="_blank" href="<?php echo base_url() . html_escape($fu['vendor_name'])?>">
                                            <img class="media-object" src="<?php echo base_url() . $fu['imgurl']?>">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="title"><a target="_blank" href="<?php echo base_url() . html_escape($fu['vendor_name'])?>"><?php echo html_escape($fu['vendor_name'])?></a></h5>
                                            <?php echo $fu['datecreated']?>
                                        </div>
                                    </div>
                                <?PHP $uCounter++;
                                    if( $uCounter === $usercount/2 ):
                                ?>
                                    </div><div class="col-md-6">
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
                                            <a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">
                                                <img class="media-object" src="<?php echo base_url() . $prod['path'] . "categoryview/" . $prod['file']?>">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="content">
                                                <h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>"><?php echo html_escape($prod['product_name'])?></a></h5>
                                                <?php echo $prod['brief']?>
                                            </div>
                                            <div class="condition m-screen l-screen">Condition: <?php echo $prod['condition']?></div>
                                        </div>
                                        <div class="condition s-screen">Condition: <?php echo $prod['condition']?></div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 media-sub media-btn-panel">
                                        <p>Php</p>
                                        <p class="feed-price"><?php echo html_escape(number_format($p['original_price'], 2, '.', ','))?></p>
                                        <div class="orange-btn"><a target="_blank" href="<?php echo base_url() . "item/" . $prod['slug']?>">Buy Now</a></div>
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
        <div class="col-md-3 l-screen">
            <div class="row"><div class="col-md-12">
                <div class="col-md-10 col-md-offset-2 mrgn-bttm-8">
                    <div class="row">
                        <div class="table-bordered">
                            <div class="border-bottom pd-8-12 title">Popular Items</div>
                            <?php foreach($popular_items as $p):?>
                                <div class="media pd-8-12 side-panel">
                                    <div class="pull-left media-image">
                                        <a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>">
                                            <img class="media-object" src="<?=base_url()?><?php echo $p['path'] . "thumbnail/" . $p['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>"><?php echo html_escape($p['product_name'])?></a></h5>
                                        Php <?php echo html_escape(number_format($p['original_price'], 2, '.', ','))?>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-2 mrgn-bttm-8">
                    <div class="row">
                        <a href="<?php echo base_url() . $banners['right']['target']?>">
                            <img class="img-responsive" src="<?=base_url()?><?php echo $banners['right']['img']?>">
                        </a>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-2 mrgn-bttm-8">
                    <div class="row">
                        <div class="table-bordered">
                            <div class="border-bottom pd-8-12 title">Promo Items</div>
                            <?php foreach($promo_items as $p):?>
                                <div class="media pd-8-12 side-panel">
                                    <div class="pull-left media-image">
                                        <a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>">
                                            <img class="media-object" src="<?=base_url()?><?php echo $p['path'] . "thumbnail/" . $p['file']?>">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="title"><a target="_blank" href="<?php echo base_url() . "item/" . $p['slug']?>"><?php echo html_escape($p['product_name'])?></a></h5>
                                        Php <?php echo html_escape(number_format($p['original_price'], 2, '.', ','))?>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=base_url()?>assets/js/src/feed.js"></script>

</html>
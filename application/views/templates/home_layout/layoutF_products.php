<?php foreach( $products as $prod ):?>
    <div class="media table-bordered mrgn-bttm-8 product">
        <div class="col-md-9 col-sm-9 media-sub media-content">
            <div class="pull-left media-image">
                <a  href="<?php echo "/item/" . $prod['slug']?>">
                    <img class="media-object" src="/<?php echo $prod['path'] . "categoryview/" . $prod['file']?>">
                </a>
            </div>
            <div class="media-body">
                <div class="content">
                    <h5 class="title"><a  href="<?php echo "/item/" . $prod['slug']?>"><?php echo html_escape($prod['name'])?></a></h5>
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
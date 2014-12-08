<div class="wrapper electronics_gadgets_section">
       <div class="home_cat_product_title <?php echo $section['category_detail']['css_class']?>">
            <a href="/<?= $section['category_detail']['url']?>">
                <?PHP if(strlen(trim($section['category_detail']['imagepath'])) > 0): ?>
                    <img src="<?php echo getAssetsDomain() ?>assets/<?php echo substr($section['category_detail']['imagepath'], 0, strpos($section['category_detail']['imagepath'],'.'))?>_small<?php echo substr($section['category_detail']['imagepath'], strpos($section['category_detail']['imagepath'],'.'))?>" >
                <?PHP else: ?>
                     <img src="<?php echo getAssetsDomain() ?>assets/images/img_icon_partner_small.png" >
                <?PHP endif; ?>
               <h2><?php echo html_escape($section['category_detail']['name']); ?></h2>    
            </a>
        </div>
              
        <?php if(isset($section['category_detail']['subcategory']) && (count($section['category_detail']['subcategory']) > 0)): ?>
            <div class="home_cat_tabs <?php echo $section['category_detail']['css_class']?>">
                <ul>
                    <?php for($count = 0; $count < 5;$count++): ?>

                        <?php if(isset($section['category_detail']['subcategory'][$count])):?>
                            <?php $subcategory = $section['category_detail']['subcategory'][$count]; ?>
                            <li><a href="/category/<?php echo $subcategory['slug']?>"><?php echo html_escape($subcategory['name']);?></a></li>
                       <?php endif;?>
                    <?php endfor; ?>
                </ul>
            </div>
        <?PHP endif; ?>
        
        <?PHP $count = 0; ?>
    <div class="home_cat_items">
        <div class="first_panel_left">
            <div class="home_cat_item2_con border_btm">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>

            

            <div class="home_cat_item1_con">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>
        </div>

        <div class="first_panel_left">
            <div class="home_cat_item1_con border_btm">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>

            <div class="home_cat_item2_con">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>

            
        </div>
        <div class="first_panel_left">
            <div class="home_cat_item2_con border_btm">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>

            

            <div class="home_cat_item1_con">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>
        </div>


        <div class="first_panel_right">
            <div class="home_cat_item1_con border_btm">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>
            <div class="home_cat_item2_con">
                <?PHP $product = $section['product_panel'][$count++];?>
                <div class="home_cat_item_link">
                    <a href="<?= "/item/".$product['slug']; ?>">
                        <div>                            
                            <p><?php echo html_escape($product['product_name']);?> </p>
                            <p class="orange">PHP<?PHP echo number_format($product['price'], 2, '.', ',');?></p>                            
                        </div>
                        <div>
                            <small class="span_bg c_small_btn"></small>
                        </div>
                    </a>
                </div>
                <a href="<?= "/item/".$product['slug']; ?>">
                    <span>
                        <img src="<?php echo getAssetsDomain() ?><?php echo $product['path'];?>categoryview/<?php echo $product['file']?>" alt="ipad mini">
                    </span>
                </a>
            </div>
            
        </div>
    </div>
    
</div>
<br/>
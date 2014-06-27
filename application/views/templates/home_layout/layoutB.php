<div class="wrapper electronics_gadgets_section">


        <div class="home_cat_product_title <?php echo $section['category_detail']['css_class']?>">
            <a href="<?=base_url().$section['category_detail']['url']?>">
                <?PHP if(strlen(trim($section['category_detail']['imagepath'])) > 0): ?>
                    <img src="/assets/<?php echo substr($section['category_detail']['imagepath'], 0, strpos($section['category_detail']['imagepath'],'.'))?>_small<?php echo substr($section['category_detail']['imagepath'], strpos($section['category_detail']['imagepath'],'.'))?>" >
                <?PHP else: ?>
                     <img src="/assets/images/img_icon_partner_small.png" >
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
                            <li><a href="<?=base_url()?>category/<?php echo $subcategory['slug']?>"><?php echo html_escape($subcategory['name']);?></a></li>
                       <?php endif;?>
                    <?php endfor; ?>
                </ul>
            </div>
        <?PHP endif; ?>

        
             <?php $count = 0; ?>
        <div class="home_cat_items">
            <div class="first_panel_middle">
                 <ul class="slider3">
                    <?php foreach($section['product_panel_main'] as $product): ?>
                    <li>
                        <a href="<?=base_url()."item/".$product['slug']; ?>">                        
                            <span class="elec_slide_img_con">
                            <img class="cat2_slide_prod" src="<?=base_url().$product['path'].'/'.$product['file']; ?>" />
                            </span>
                        </a>                      
                        <span class="electronics_slider_price_con">
                            <a href="<?=base_url()."item/".$product['slug']; ?>">

                                <div>
                                    <h2><?=html_escape($product['product_name']);?></h2>
                                
                                    <p>Price: <span>&#8369;<?php echo number_format($product['price'],2,'.',',');?></span> onwards</p>
                                </div>
                                <div>
                                    <span class="cat_slide_btn">Buy Now  <small class="span_bg c_small_btn"></small></span>
                                </div>                                
                            </a>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul> 
                
                
                <div class="fpm_btm_items_con">
                    <div class="fpm_btm_items_left_con">
                        <div class="home_cat_item1_con">
                            <div class="home_cat_item1_con">
                               
                                <?php $product = $section['product_panel'][$count++];?>

                                <div class="home_cat_item_link">
                                    <a href="<?=base_url()?>item/<?php echo $product['slug'] ?>">
                                        <div>                            
                                            <p><?php echo html_escape($product['product_name']);?> </p>
                                            <p class="orange">PHP <?php echo number_format($product['price'],2,'.',',');?></p>                            
                                        </div>
                                        <div>
                                            <small class="span_bg c_small_btn"></small>
                                        </div>
                                    </a>
                                </div>
                                <a href="<?=base_url()?>item/<?php echo $product['slug'] ?>">
                                    <span>
                                        <img src="<?=base_url().$product['path'].'/'.$product['file'];?>" alt="<?php echo html_escape($product['product_name']);?>">
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                      
                    <div class="fpm_btm_items_right_con">
                        <div class="home_cat_item1_con">
                                <?php $product = $section['product_panel'][$count++];?>

                                <div class="home_cat_item_link">
                                    <a href="<?=base_url()?>item/<?php echo $product['slug'] ?>">
                                        <div>                            
                                            <p><?php echo html_escape($product['product_name']);?> </p>
                                            <p class="orange">PHP <?php echo number_format($product['price'],2,'.',',');?></p>                            
                                        </div>
                                        <div>
                                            <small class="span_bg c_small_btn"></small>
                                        </div>
                                    </a>
                                </div>
                                <a href="<?=base_url()?>item/<?php echo $product['slug'] ?>">
                                    <span>
                                        <img src="<?=base_url().$product['path'].'/'.$product['file'];?>" alt="<?php echo html_escape($product['product_name']);?>">
                                    </span>
                                </a>
                        </div>
                    </div>
                        
                </div>
                <div class="clear"></div>
            </div>
            
            
            
            
            <div class="first_panel_left">
                <div class="home_cat_item2_con border_btm">
                    <?php $product = $section['product_panel'][$count++];?>
                    <div class="home_cat_item_link">
                        <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                            <div>                            
                                <p><?PHP echo html_escape($product['product_name']);?></p>
                                <p class="orange">PHP <?PHP echo number_format($product['price'],2,'.',',');?></p>                            
                            </div>
                            <div>
                                <small class="span_bg c_small_btn"></small>
                            </div>
                        </a>
                    </div>
                    <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                        <span>
                            <img src="<?=base_url().$product['path'].'/'.$product['file'];?>" alt="<?PHP echo html_escape($product['product_name']);?>">
                        </span>
                    </a>
                </div>

                <div class="home_cat_item1_con">
                    <?php $product = $section['product_panel'][$count++];?>
                    <div class="home_cat_item_link">
                        <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                            <div>                            
                                <p><?PHP echo html_escape($product['product_name']);?></p>
                                <p class="orange">PHP <?PHP echo number_format($product['price'],2,'.',',');?></p>                            
                            </div>
                            <div>
                                <small class="span_bg c_small_btn"></small>
                            </div>
                        </a>
                    </div>
                    <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                        <span>
                            <img src="<?=base_url().$product['path'].'/'.$product['file'];?>" alt="<?PHP echo html_escape($product['product_name']);?>">
                        </span>
                    </a>
                </div>
            </div>

            


            <div class="first_panel_right">
                <div class="home_cat_item2_con border_btm">
                    <?php $product = $section['product_panel'][$count++];?>
                    <div class="home_cat_item_link">
                        <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                            <div>                            
                                <p><?PHP echo html_escape($product['product_name']);?></p>
                                <p class="orange">PHP <?PHP echo number_format($product['price'],2,'.',',');?></p>                            
                            </div>
                            <div>
                                <small class="span_bg c_small_btn"></small>
                            </div>
                        </a>
                    </div>
                    <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                        <span>
                            <img src="<?=base_url().$product['path'].'/'.$product['file'];?>" alt="<?PHP echo html_escape($product['product_name']);?>">
                        </span>
                    </a>
                </div>
                
                <div class="home_cat_item1_con">
                    <?php $product = $section['product_panel'][$count++];?>
                    <div class="home_cat_item_link">
                        <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                            <div>                            
                                <p><?PHP echo html_escape($product['product_name']);?></p>
                                <p class="orange">PHP <?PHP echo number_format($product['price'],2,'.',',');?></p>                            
                            </div>
                            <div>
                                <small class="span_bg c_small_btn"></small>
                            </div>
                        </a>
                    </div>
                    <a href="<?PHP echo base_url().'item/'.$product['slug'];?>">
                        <span>
                            <img src="<?=base_url().$product['path'].'/'.$product['file'];?>" alt="<?PHP echo html_escape($product['product_name']);?>">
                        </span>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    <br/>
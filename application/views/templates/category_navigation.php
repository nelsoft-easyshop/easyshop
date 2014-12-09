
<nav>                
    <ul>                    

    <?PHP for($x=0;$x < sizeof($cat_items);$x++): ?>                
        <li  class="category_item">
            <p style="background:url('/assets/<?PHP echo $cat_items[$x]['path']; ?>') no-repeat scroll left center #fff">
                <a href="/category/<?PHP echo $cat_items[$x]['slug']; ?>">
                    <span><?PHP echo html_escape($cat_items[$x]['NAME']); ?></span>
                </a>
            </p>
            
            <!--
            <?PHP if(sizeof($cat_items[$x][0]) >= 1): ?>
            <div id="<?PHP echo $cat_items[$x]['id_cat']; ?>childs" class="childs">               
                <?PHP for($x2=0;$x2 < sizeof($cat_items[$x][0]);$x2++): ?>     
                    <div class='inner_category'>
                        <h3>
                            <a href='/category/<?PHP echo $cat_items[$x][0][$x2]['slug']?>'><?PHP echo html_escape($cat_items[$x][0][$x2]['name']); ?></a>
                        </h3> 
                        <p>Recommended</p>
                        <ul class='slides_prod slider_product'>
                            <?PHP for($px=0;$px < sizeof($cat_items[$x][0][$x2]['popular']);$px++): ?>

                                <li>
                                    <a href='/item/<?= '/item/'.$cat_items[$x][0][$x2]['popular'][$px]['slug']?>'>
                                        <span class="cat_slide_img_wrapper">
                                            <span class="cat_slide_img_con">
                                                <img src="<?php echo getAssetsDomain().$cat_items[$x][0][$x2]['popular'][$px]['path'].'thumbnail/'.$cat_items[$x][0][$x2]['popular'][$px]['file']; ?>"/>
                                            </span>
                                        </span>
                                        <br />
                                        <?PHP echo html_escape($cat_items[$x][0][$x2]['popular'][$px]['product']); ?>
                                        <br />
                                    </a>
                                </li> 

                            <?PHP endfor; ?>                                        
                        </ul>
                        
                        <ul class='category_product_types'>         
                            <?PHP for($x3=0;$x3 < sizeof($cat_items[$x][0][$x2][6]);$x3++): ?>     
                            <li><a href='/category/<?PHP echo $cat_items[$x][0][$x2][6][$x3]['slug'];?>'><?PHP echo html_escape($cat_items[$x][0][$x2][6][$x3]['name']); ?></a></li>
                            <?PHP endfor; ?>     
                        </ul>  
                    </div>
                <?php endfor; ?>
            </div>
            <?PHP endif;?>
            -->
            
        </li>
    <?php endfor; ?>
    </ul>
</nav>



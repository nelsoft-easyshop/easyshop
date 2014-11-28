<div class="clear"></div>

<div class="clear"></div>

    <section>
        <div class="wrapper all_categories_container">
        <h2>All Categories</h2>
            <div>
                <?php if(count($categories)>0): ?>
                    <?php
                            $letterhead = substr($categories[0]['name'],0,1);
                            foreach($categories as $index=>$category): 
                            $new_letterhead = substr($category['name'],0,1);
                            if(($letterhead !== $new_letterhead)||($index===0)){
                                $letterhead = $new_letterhead;
                                echo ($index!==0)?'</ul>':'';
                                echo '<p>'.substr($category['name'],0,1).'</p>';
                                echo '<ul>';
                            }
                    ?>
            
                        <?php $category_cnt = count($category['subcategories']); ?>
                        <li><h3><a href="/category/<?php echo $category['slug']?>"><?php echo $category['name'];?></a></h3>
                            <?php
                                    $category_cnt = count($category['subcategories']);
                                    $category_quo = (int)($category_cnt/3);
                                    $category_rem = $category_cnt%3;
                                    $x = 0;
                                    echo '<ul>';
                                    foreach($category['subcategories'] as $subcategory): 
                                        if($x === ($category_rem+($category_quo===0?0:1))){
                                            echo '</ul><ul>';
                                            $x = 0;
                                            }
                                        $x++;
                            ?>
                            <li><a href="/category/<?php echo $subcategory['slug']?>"><?php echo $subcategory['name'] /*.'('.$subcategory['product_count'].')' */ ;?></a></li>
                                    <?php endforeach; ?>
                                    </ul>
                            </li>
                    <?php endforeach; ?>
                    
                <?php endif; ?>
            </div>
        </div>
    </section>

<div class="clear"></div>

<?php
 $class = "";
 if($typeofview == 1){
  $class = '-list';
 }
      if(isset($items))
      {
      $irclass = "";
        if($irrelivant){
          
          if($count == 0){
            $irclass = "phides";
            echo '<dob><div style="clear:both;"></div>
            <hr>
            <!--          
            <div>Next item is irrelevant</div>
            <hr>
            -->
            <input type="button" class="smr_btn" value="Show More Items">
            <div style="clear:both"></div></dob>
            <style>
            .phides{
              display:none;
            }
            </style>';
          }

        }
       for ($i=0; $i < sizeof($items); $i++) { 
        $pic = explode('/', $items[$i]['product_image_path']);
        ?>

                <div class="product<?php echo $class.' '. $irclass; ?>">
                <a href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>"><span class="prod_img_wrapper"><span class="prod_img_container"><img alt="<?php echo $items[$i]['name']; ?>" src="<?php echo base_url() . $pic[0] . '/' . $pic[1] . '/' . $pic[2] . '/' . $pic[3] . '/' . 'categoryview' . '/' . $pic[4]; ?>"></span></span></a>
                <h3 style="  -o-text-overflow: ellipsis;    
                text-overflow:    ellipsis;   
                overflow:hidden;             
                white-space:nowrap;  
                width: 225px; ">
                    <a href="<?= base_url() ?>item/<?php echo $items[$i]['slug']; ?>"><?php echo html_escape($items[$i]['name']); ?></a>
                </h3>

                <div class="price-cnt">
                    <div class="price">
                        Php <?php echo number_format($items[$i]['price'], 2); ?>
                    </div>
                </div>
                <div class="product_info_bottom">
                    <div>Condition: <strong><?php echo html_escape($items[$i]['condition']); ?></strong></div>
                    <!-- <div>Sold: <strong>32</strong></div> -->
                </div>
                <p>
                    <?php echo html_escape($items[$i]['brief']); ?>
                </p>
            </div>

      

    
    <?php
  }
}
?>
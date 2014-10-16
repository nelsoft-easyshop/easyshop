<li class="slider-template-4">
    <?php $sliderImage = $image; ?>
    <div class="slider-item">
        <div class="img-holder temp4-img-1 img-holder-mrgn-right-10">
            <a href="<?php echo $sliderImage[0]['target'] ?>">
                <img src="<?php echo $sliderImage[0]['path'] ?>">
            </a>
        </div>
        
        <div class="img-holder temp4-img-2">                            
            <div class="display-ib img-holder-mrgn-btn-10">
                 <a href="<?php echo $sliderImage[1]['target'] ?>">
                    <img src="<?php echo $sliderImage[1]['path'] ?>">
                </a>
            </div>
            <div class="">
                 <a href="<?php echo $sliderImage[2]['target'] ?>">
                    <img src="<?php echo  $sliderImage[2]['path'] ?>">
                </a>
            </div>
        </div>

        <div class="clear"></div>                       
    </div>                   
</li>
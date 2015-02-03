<li class="slider-template-3">
    <div class="slider-item">
        <?php $sliderImage = $image; ?>
        <div class="img-holder temp3-img-1 img-holder-mrgn-right-10">
            <a href="<?php echo $sliderImage[0]['target']['url'] ?>" target=<?php echo $sliderImage[0]['target']['targetString'];?>>
                <img src="<?php echo getAssetsDomain().'.'.$sliderImage[0]['path'] ?>">
            </a>
        </div>              
        
        <?php if(isset($sliderImage[1])):?>
        <div class="img-holder temp3-img-2">
              <a href="<?php echo $sliderImage[1]['target']['url'] ?>" target=<?php echo $sliderImage[1]['target']['targetString'];?>>
                <img src="<?php echo getAssetsDomain().'.'. $sliderImage[1]['path'] ?>">
            </a>
        </div>
        <?php endif; ?>
        
        <div class="clear"></div>                        
    </div>
</li>
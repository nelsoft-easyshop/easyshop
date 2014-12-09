<li class="slider-template-4">
    <?php $sliderImage = $image; ?>
    <div class="slider-item">
        <div class="img-holder temp4-img-1 img-holder-mrgn-right-10">
            <a href="<?php echo $sliderImage[0]['target']['url'] ?>" target=<?php echo $sliderImage[0]['target']['targetString'];?>>
                <img src="<?php echo getAssetsDomain().'.'.$sliderImage[0]['path'] ?>">
            </a>
        </div>
        
        <div class="img-holder temp4-img-2">                            
            <div class="display-ib img-holder-mrgn-btn-10">
                <a href="<?php echo $sliderImage[1]['target']['url'] ?>" target=<?php echo $sliderImage[1]['target']['targetString'];?>>
                    <img src="<?php echo getAssetsDomain().'.'.$sliderImage[1]['path'] ?>">
                </a>
            </div>
            <div class="">
                <a href="<?php echo $sliderImage[2]['target']['url'] ?>" target=<?php echo $sliderImage[2]['target']['targetString'];?>>
                    <img src="<?php echo getAssetsDomain().'.'.$sliderImage[2]['path'] ?>">
                </a>
            </div>
        </div>

        <div class="clear"></div>                       
    </div>                   
</li>
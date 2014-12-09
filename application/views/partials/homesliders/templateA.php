<li class="slider-template-1 slider-alt-template2">
    <div class="slider-item">
        <?php $sliderImage = reset($image); ?>
        <?php $smallerImage = end($image); ?>
        <a href="<?php echo $sliderImage['target']['url']?>" target=<?php echo $sliderImage['target']['targetString'];?>>
            <img src="<?php echo getAssetsDomain().'.'.$sliderImage['path'] ?>" class="slider-temp1-img1">
            <img src="<?php echo getAssetsDomain().'.'.$smallerImage['path'] ?>" class="slider-temp1-img2">
        </a>
    </div>
</li>
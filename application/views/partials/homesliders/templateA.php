<li class="slider-template-1">
    <div class="slider-item">
        <?php $sliderImage = reset($image); ?>
        <a href="<?php echo $sliderImage['target'] ?>">
            <img src="<?php echo $sliderImage['path'] ?>">
        </a>
    </div>
</li>
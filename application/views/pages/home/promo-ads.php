
<div class="row-fluid row-promo-ads">
    <div class="col-md-12">
        <div class="row">
        
            <?php shuffle($homeContent['adSection']); ?>
        
            <?php if(isset($homeContent['adSection'][0])): ?>
                <div class="col-md-4 col-sm-4 col-xs-6 promo-1" style="padding: 4px;">
                    <a href="/<?php echo count($homeContent['adSection'][0]['target']) === 0 ? '' : $homeContent['adSection'][0]['target']; ?>">
                        <img src="<?php echo getAssetsDomain(); ?><?php echo $homeContent['adSection'][0]['img']; ?>" class="img-responsive img-promo-banner">
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if(isset($homeContent['adSection'][1])): ?>
                <div class="col-md-4 col-sm-4 col-xs-6 promo-2" style="padding: 4px;">
                    <a href="/<?php echo count($homeContent['adSection'][1]['target']) === 0 ? '' : $homeContent['adSection'][1]['target']; ?>">
                        <img src="<?php echo getAssetsDomain(); ?><?php echo $homeContent['adSection'][1]['img']; ?>" class="img-responsive img-promo-banner">
                    </a>
                </div>
            <?php endif; ?>

            <?php if(isset($homeContent['adSection'][2])): ?>
                <div class="col-md-4 col-sm-4 col-xs-6 promo-3" style="padding: 4px;">
                    <a href="/<?php echo count($homeContent['adSection'][2]['target']) === 0 ? '' : $homeContent['adSection'][2]['target']; ?>">
                        <img src="<?php echo getAssetsDomain(); ?><?php echo $homeContent['adSection'][2]['img']; ?>" class="img-responsive img-promo-banner">
                    </a>
                </div>
            <?php endif; ?>
            
            
            
            
            
        </div>
    </div>
</div>
<div class="clear"></div>
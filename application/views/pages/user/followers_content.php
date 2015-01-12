
    <!-- foreach  --> 
    <div id="follow-div-page-<?=$page?>"> 
        <div class="div-follower-container-inner">   
            <?php foreach ($followers as $key => $value): ?>
                <?php $memberEntity = $value->getMember(); ?>
                <div class="col-sm-6 col-xs-12 col-follower">
                    <div class="follower-div">
                        <a href="/<?=html_escape($memberEntity->getSlug()); ?>">
                            <div class="div-img-cover">
                                <img src="<?php echo getAssetsDomain().'.'.$value->bannerImage ;?>" class="img-follower-cover"/>
                                <img src="<?php echo getAssetsDomain().'.'.$value->avatarImage;?>" class="vendor-follower-img"/>
                                <div class="cover-overlay"></div>
                            </div>
                        </a>
                        <div class="div-follower-details">
                            <div class="row">
                                <div class="col-xs-7">
                                    <img src="<?=$value->avatarImage;?>" class="img-mobile-follower pull-left">
                                    <a href="/<?=html_escape($memberEntity->getSlug()); ?>">
                                        <p class="p-follower-name">
                                            <?php $displayName = strlen(trim($memberEntity->getStoreName())) > 0 ? html_escape($memberEntity->getStoreName()) : html_escape($memberEntity->getUsername()); ?>
                                            <?php if(strlen($displayName) > 18 ):?>
                                                <?=substr($displayName,0,18).' ...'; ?>
                                            <?php else: ?>
                                                <?=$displayName; ?>
                                            <?php endif; ?>
                                        </p>
                                    </a>
                                    <p class="p-follower-location">
                                        <?php if($value->location):?>
                                            <?=$value->city;?>, <?=$value->stateRegion;?>
                                        <?php else: ?>
                                            Location not set
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-xs-5 col-follow-button follow-button-area" align="right">
                                    <?php if($isLoggedIn && $memberEntity->getIdMember() == $viewerId): ?>
                                    <?php elseif(strtolower($value->subscriptionStatus) === "unfollowed" || !$isLoggedIn): ?>
                                        <span class="follow-btn follow-right btn btn-default-2 subscription" data-btn="default" data-status="follow" data-slug="<?=html_escape($memberEntity->getSlug()); ?>" data-username="<?=html_escape($memberEntity->getUsername());?>">
                                            <span class="glyphicon glyphicon-plus-sign"></span> <span class="btn-text-follow">Follow</span>
                                        </span>
                                    <?php else: ?> 
                                        <span class="follow-btn follow-right btn btn-default-following following-user subscription" data-btn="default" data-status="unfollow" data-slug="<?=html_escape($memberEntity->getSlug()); ?>" data-username="<?=html_escape($memberEntity->getUsername());?>">
                                            <i class="fa fa-check"></i> <span class="btn-text-follow">Following</span>
                                        </span>
                                        <span class="follow-btn follow-right btn btn-default-following unfollow-user subscription" data-btn="default" data-status="unfollow" data-slug="<?=html_escape($memberEntity->getSlug()); ?>" data-username="<?=html_escape($memberEntity->getUsername());?>">
                                            <i class="fa fa-minus-circle"></i> <span class="btn-text-follow">Unfollow</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php
                                        $storeDesc = $memberEntity->getStoreDesc();
                                    ?>
                                    <p class="p-follower-description">
                                        <?php $storeDescDisplay = html_escape($storeDesc);?>
                                    
                                        <?php if(strlen($storeDescDisplay) > 100 ):?>
                                            <?=substr($storeDescDisplay,0,100).' ...'; ?>
                                        <?php else: ?>
                                            <?=$storeDescDisplay; ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- end of foreach -->
        </div>
        <div class="clear"></div>
        <div class="pagination-container">
            <center>
                <?=$pagination;?>
            </center>
        </div>
    </div>

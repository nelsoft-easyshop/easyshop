
    <!-- foreach  --> 
    <div id="follow-div-page-<?=$page?>">
        <?php foreach ($followers as $key => $value): ?>
            <?php $memberEntity = $value->getMember(); ?>
            <div class="col-xs-6 col-follower">
                <div class="follower-div">
                    <a href="/<?=$memberEntity->getSlug(); ?>">
                        <div class="div-img-cover">
                            <img src="<?=$value->bannerImage ;?>" class="img-follower-cover"/>
                            <img src="<?=$value->avatarImage;?>" class="vendor-follower-img"/>
                            <div class="cover-overlay"></div>
                        </div>
                    </a>
                    <div class="div-follower-details">
                        <div class="row">
                            <div class="col-xs-7">
                                <a href="/<?=$memberEntity->getSlug(); ?>">
                                    <p class="p-follower-name">
                                        <?=strlen($memberEntity->getStoreName()) > 0 ? html_escape($memberEntity->getStoreName()) : html_escape($memberEntity->getUsername()); ?>
                                    </p>
                                </a>
                                <p class="p-follower-location">
                                    Greenhills, San Juan City, Metro Manila
                                </p>
                            </div>
                            <div class="col-xs-5 col-follow-button follow-button-area" align="right">
                                <?php if($isLoggedIn && $memberEntity->getIdMember() == $viewerId): ?>
                                <?php elseif(strtolower($value->subscriptionStatus) === "unfollowed" || !$isLoggedIn): ?>
                                    <span class="follow-btn follow-right btn btn-default-2 subscription" data-status="follow" data-slug="<?=$memberEntity->getSlug(); ?>" data-username="<?=$memberEntity->getUsername();?>">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                <?php else: ?> 
                                    <span class="follow-btn follow-right btn btn-default-following following-user subscription"  data-status="unfollow" data-slug="<?=$memberEntity->getSlug(); ?>" data-username="<?=$memberEntity->getUsername();?>">
                                        <i class="fa fa-check"></i>Following
                                    </span>
                                    <span class="follow-btn follow-right btn btn-default-following unfollow-user subscription"  data-status="unfollow" data-slug="<?=$memberEntity->getSlug(); ?>" data-username="<?=$memberEntity->getUsername();?>">
                                        <i class="fa fa-minus-circle"></i> Unfollow
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
                                    <?=(strlen($storeDesc)>190)?substr_replace($storeDesc, "...", 190):$storeDesc;?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- end of foreach -->
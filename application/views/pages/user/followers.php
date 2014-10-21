<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/followers.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
            <div class="row row-contact">
            <div class="col-xs-3 no-padding col-left-wing">
                <div class="left-wing-contact">
                    <div class="panel-contact-details">
                        <p class="panel-title-contact">
                            WHO TO FOLLOW
                        </p>
                        <table width="100%"> 
                            <?=$follower_recommed_view;?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="followers-container">
                    <div class="loading_div" style="text-align:center;display:none;">
                        <img src="/assets/images/orange_loader.gif">
                    </div>
                    <div id="follower-container" class="row" style="min-height: 675px;">
                        <?php if(intval($followerCount) <= 0): ?>
                            <!--will appear if the vendor has no follower-->
                            <div class="panel-no-followers">
                                <div class="jumbotron no-feedback-list">
                                    <center>
                                        <strong><?=$storeName; ?> doesn't have followers</strong> 
                                    </center>
                                </div>
                            </div>
                            <!--end of div-->
                        <?php else:?>
                            <div id="follow-div-page-0">
                                <?=$follower_view;?>
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="clear"></div>
                    <div class="pagination-container">
                        <center>
                            <?=$pagination;?>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="storage" style="display:none">
    <div id="follow-div-page-0">
        <?=$follower_view;?>
    </div>
</div>
<input type="hidden" id="is_loggedin" value="<?php echo $isLoggedIn ? 1 : 0 ?>">
<input type="hidden" id="vendor_id" value="<?=$memberId?>"> 
<input type="hidden" id="userIds" value="<?=json_encode($memberIdsDisplay)?>" />
<script src="/assets/js/src/vendorpage_followers.js" type="text/javascript"></script>

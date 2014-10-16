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
                            <tr>
                                <td class="td-vendor-img">
                                   <a href="#"><img src="/assets/images/img_profile_pic.jpg" class="vendor-img"/></a>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Neneng B
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        Unit 8C Marc 2000 Tower, Manila
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-vendor-img">
                                   <a href="#"><img src="/assets/images/img_profile_pic_2.jpg" class="vendor-img"/></a>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Boy Pick Up
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        1620 Bulacan Street, Sta. Cruz, Manila
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-vendor-img">
                                   <a href="#"><img src="/assets/images/img_profile_pic_3.jpg" class="vendor-img"/></a>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Senyora Angelica Santiba&ntilde;ez
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        Hacienda Luisita Tarlac City, Tarlac, Philippines
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="followers-container">
                    <div class="row">
                        <!-- foreach  -->
                        <?php foreach ($followers as $key => $value): ?>
                            <?php $memberEntity = $value->getMember(); ?>
                            <div class="col-xs-6 col-follower">
                                <div class="follower-div">
                                    <a href="#">
                                    <div class="div-img-cover">
                                        <img src="/assets/images/img_cover_2.png" class="img-follower-cover"/>
                                        <img src="/assets/images/img_profile_pic_5.png" class="vendor-follower-img"/>
                                        <div class="cover-overlay"></div>
                                    </div>
                                    </a>
                                    <div class="div-follower-details">
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <a href="#">
                                                    <p class="p-follower-name">
                                                        <?=strlen($memberEntity->getStoreName()) > 0 ? html_escape($memberEntity->getStoreName()) : html_escape($memberEntity->getUsername()); ?>
                                                    </p>
                                                </a>
                                                <p class="p-follower-location">
                                                    Greenhills, San Juan City, Metro Manila
                                                </p>
                                            </div>
                                            <div class="col-xs-5 col-follow-button" align="right">
                                                <span class="follow-btn follow-right btn btn-default-2">
                                                    <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                                </span>
                                            </div>

<!--                                             <div class="col-xs-5 col-follow-button" align="right">
                                                <span class="follow-btn follow-right btn btn-default-following" id="following">
                                                    <i class="fa fa-check"></i>Following
                                                </span>
                                                <span class="follow-btn follow-right btn btn-default-following" id="unfollow">
                                                    <i class="fa fa-minus-circle"></i> Unfollow
                                                </span>
                                            </div> -->

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
                        <!-- end of foreach -->
                    </div>
                    <div class="clear"></div>
                    <div class="pagination-container">
                        <center>
                            <ul class="pagination pagination-followers">
                                <li><a href="#">&laquo;</a></li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">&raquo;</a></li>
                            </ul>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

                                                           <script>
                                                $( "#following" ).mouseenter(function() {
                                                  $( "#following" ).css("display", "none");
                                                  $( "#unfollow" ).css("display", "block");
                                                });
                                                
                                                $( "#unfollow" ).mouseout(function() {
                                                  $( "#following" ).css("display", "block");
                                                  $( "#unfollow" ).css("display", "none");
                                                });
                                                
                                                
                                            </script>
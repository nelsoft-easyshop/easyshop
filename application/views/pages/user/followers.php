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
                                   <img src="/assets/images/img_profile_pic.jpg" class="vendor-img"/>
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
                                   <img src="/assets/images/img_profile_pic_4.jpg" class="vendor-img"/>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Inon B
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        Antipolo Rizal
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-vendor-img">
                                   <img src="/assets/images/img_profile_pic_2.jpg" class="vendor-img"/>
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
                                   <img src="/assets/images/img_profile_pic_3.jpg" class="vendor-img"/>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Senyora Santiba&ntilde;ez
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
                        <div class="col-xs-6 col-follower">
                            <div class="follower-div">
                                <div class="div-img-cover">
                                    <img src="/assets/images/img_cover.jpg"/>
                                    <img src="/assets/images/img_profile_pic_3.jpg" class="vendor-img"/>
                                </div>
                                <div class="div-follower-details">
                                    <div class="row">
                                        <div class="col-xs-7">
                                            <a href="#">
                                                <p class="p-follower-name">
                                                    Senyora Santiba&ntilde;ez
                                                </p>
                                            </a>
                                            <p class="p-follower-location">
                                                Hacienda Luisita Tarlac City, Tarlac, Philippines
                                            </p>
                                        </div>
                                        <div class="col-xs-5 col-follow-button" align="right">
                                            <span class="follow-btn follow-right btn btn-default-2">
                                                <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <?php
                                                $dummy = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.";
                                            ?>
                                            <p class="p-follower-description">
                                                <?=(strlen($dummy)>190)?substr_replace($dummy, "...", 190):$dummy;?>
                                                
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                        asd
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
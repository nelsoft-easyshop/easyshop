<link type="text/css" href='/assets/css/font-awesome/css/font-awesome.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<div class="div-tab">
    <div class="div-member-stats">
        <div class="div-img-cover">
            <img src="<?=$bannerImage;?>" class="img-follower-cover"/>
            <img src="<?=$avatarImage; ?>" class="vendor-follower-img"/>
        </div>
        <div class="clear"></div>
        <div class="div-stats">
            <div class="div-top-name">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <p class="p-username"><span class="icon-profile"> </span><?=html_escape($member->getUserName());?></p>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <div class="row">
                        <div class="col-md-5 col-progress-text">
                            <div class="div-progress-container-text">
                                Account Progress: <?=$profilePercentage; ?>%
                            </div>
                        </div>
                        <div class="col-md-7 col-progress-bar">
                            <div class="div-progress-bar-container" align="right">
                                <div class="progress progress-account">
                                    <span class="span-account-progress-text-small">Account Progress: <?=$profilePercentage; ?>%</span>
                                    <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?=$profilePercentage; ?>%">
                                        <span class="sr-only"><?=$profilePercentage; ?>% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="div-stats-numbers">
                <div class="row">
                    <div class="col-md-7">
                        <p class="p-stat-title">
                            Shop Link: 
                        </p>
                        <div class="form-shop-link">
                            <input type="text" readonly="" class="input-shop-link" value="<?=base_url();?><?=$member->getSlug();?>"/>
                            <span class="icon-web"></span>
                        </div>
                        <div class="div-stat-summary">
                            <div class="row">
                                <div class="col-xs-3" align="center">
                                    <p class="p-label-stat">
                                        Followers
                                    </p>
                                    <p class="p-stat-total">
                                        <?=$followerCount; ?>
                                    </p>
                                </div>
                                <div class="col-xs-3" align="center">
                                    <p class="p-label-stat">
                                        Following
                                    </p>
                                    <p class="p-stat-total">
                                        <?=$followingCount; ?>
                                    </p>
                                </div>
                                <div class="col-xs-3" align="center">
                                    <p class="p-label-stat">
                                        Item(s)
                                    </p>
                                    <p class="p-stat-total">
                                        <?=$productCount; ?>
                                    </p>
                                </div>
                                <div class="col-xs-3" align="center">
                                    <p class="p-label-stat">
                                        Sold
                                    </p>
                                    <p class="p-stat-total">
                                        <?=$soldProductCount; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="div-feedback-stat">
                            <p class="p-stat-title">
                                Feedback:
                            </p>
                            <table width="100%" class="table-stat-ratings">
                                <tbody>
                                    <tr>
                                        <td class="td-criteria">Item Quality: </td>
                                        <td class="td-star" align="right">
                                        <?php $tempRating = $memberRating['rating1']; ?>
                                        <?php for ($i=0; $i < 5; $i++): ?>
                                            <i class="icon-star star-stat <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                            <?php $tempRating--; ?>
                                        <?php endfor; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-criteria">Communication: </td>
                                        <td class="td-star" align="right">
                                        <?php $tempRating = $memberRating['rating2']; ?>
                                        <?php for ($i=0; $i < 5; $i++): ?>
                                            <i class="icon-star star-stat <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                            <?php $tempRating--; ?>
                                        <?php endfor; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-criteria">Shipment Time: </td>
                                        <td class="td-star" align="right">
                                        <?php $tempRating = $memberRating['rating3']; ?>
                                        <?php for ($i=0; $i < 5; $i++): ?>
                                            <i class="icon-star star-stat <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                            <?php $tempRating--; ?>
                                        <?php endfor; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-criteria">Total Reviews: </td>
                                        <td class="td-total-review" align="center">
                                            <?=$feedBackTotalCount <= 0 ? "No Rating." : $feedBackTotalCount; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row-fluid row-mobile-rate">
                                <div class="col-xs-6 col-star-rate-mobile">
                                    <span class="span-criteria-mobile">Item Quality</span>
                                    <br/>
                                    <span class="span-star-mobile">
                                        <?php $tempRating = $memberRating['rating1']; ?>
                                        <?php for ($i=0; $i < 5; $i++): ?>
                                            <i class="icon-star star-stat-mobile <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                            <?php $tempRating--; ?>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="col-xs-6 col-star-rate-mobile">
                                    <span class="span-criteria-mobile">Communication</span>
                                    <br/>
                                    <span class="span-star-mobile">
                                        <?php $tempRating = $memberRating['rating2']; ?>
                                        <?php for ($i=0; $i < 5; $i++): ?>
                                            <i class="icon-star star-stat-mobile <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                            <?php $tempRating--; ?>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="col-xs-6 col-star-rate-mobile">
                                    <span class="span-criteria-mobile">Item Shipment</span>
                                    <br/>
                                    <span class="span-star-mobile">
                                        <?php $tempRating = $memberRating['rating3']; ?>
                                        <?php for ($i=0; $i < 5; $i++): ?>
                                            <i class="icon-star star-stat-mobile <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                            <?php $tempRating--; ?>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="col-xs-6 col-star-rate-mobile">
                                    <span class="span-criteria-mobile">Total Reviews</span>
                                    <br/>
                                    <span class="span-star-mobile">
                                        <?=$feedBackTotalCount <= 0 ? "No Rating." : $feedBackTotalCount; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebarTabs">
        <div class="submenu-container">
            <ul class="submenu-ul idTabs">
                <a href="#active-items"><li>Active Items<span class="circle-total"><?=$activeProductCount;?></span></li></a>
                <a href="#deleted-items"><li>Deleted Items<span class="circle-total deleted-span-circle"><?=$deletedProductCount;?></span></li></a>
                <a href="#draft-items"><li>Draft Items<span class="circle-total"><?=$draftedProductCount;?></span></li></a>
                <a href="#feedbacks"><li>Feedbacks<span class="circle-total"><?=$feedBackTotalCount;?></span></li></a>
                <a href="#sales"><li>Sales</li></a>
            </ul>
        </div>
        <div class="product-items">
            <div id="active-items">
                <?php if($activeProductCount > 0): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-search-item">
                                <input type="text" class="input-search-item search-field" placeholder="Search product from list..."/>
                                <span class="icon-search"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-filter">
                                <label>Sort by:</label>
                                <select class="select-filter-item search-filter">
                                    <option value="default">default sorting</option>
                                    <option value="lastmodified">last modified</option>
                                    <option value="new">new</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" class="container-id" value="active-product-container" />
                            <input type="hidden" class="request-type" value="active" />
                        </div>
                    </div>
                    
                    <div id="active-product-container">
                    <?=$activeProductView;?>
                    </div>
                <?php else:?> 
                    <div class="jumbotron no-items">
                        <i class="icon-category"></i>No items for this category
                    </div>
                <?php endif;?>

            </div>
            

            <div id="deleted-items"> 
                <?php if($deletedProductCount > 0): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-search-item">
                                <input type="text" class="input-search-item search-field" placeholder="Search product from list..."/>
                                <span class="icon-search"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-filter">
                                <label>Sort by:</label>
                                <select class="select-filter-item search-filter">
                                    <option value="default">default sorting</option>
                                    <option value="lastmodified">last modified</option>
                                    <option value="new">new</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" class="container-id" value="deleted-product-container" />
                            <input type="hidden" class="request-type" value="deleted" />
                        </div>
                    </div>
                    
                    <div id="deleted-product-container">
                    <?=$deletedProductView; ?>
                    </div>
               <?php else:?> 
                    <div class="jumbotron no-items">
                        <i class="icon-category"></i>No items for this category
                    </div>
                <?php endif;?>
            </div>
            
            <div id="draft-items"> 
                <?php if($draftedProductCount > 0): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-search-item">
                                <input type="text" class="input-search-item search-field" placeholder="Search product from list..."/>
                                <span class="icon-search"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-filter">
                                <label>Sort by:</label>
                                <select class="select-filter-item search-filter">
                                    <option value="default">default sorting</option>
                                    <option value="lastmodified">last modified</option>
                                    <option value="new">new</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" class="container-id" value="drafted-product-container" />
                            <input type="hidden" class="request-type" value="drafted" />
                        </div>
                    </div>
                
                    <div id="drafted-product-container">
                        <?=$draftedProductView;?>
                    </div>
                <?php else:?> 
                    <div class="jumbotron no-items">
                        <i class="icon-category"></i>No items for this category
                    </div>
                <?php endif;?>
            </div>
            
            <div id="feedbacks"> 
                <?=$allFeedBackView; ?> 
            </div>
            <div id="sales">
            <?php include("dashboard-sales.php");?>
                 <!--<div class="jumbotron no-items">
                    <i class="icon-category"></i>No items for this category
                </div>-->
            </div>
        </div>
    </div>
</div>

<div id="hidden-product-container">
    <div id="hidden-active-container">
        <div id="hidden-active-container-default">
            <div id="page-1">
                <?=$activeProductView;?>
            </div>
        </div>
        <div id="hidden-active-container-lastmodified">
            
        </div>
        <div id="hidden-active-container-new">
            
        </div>
    </div>
    <div id="hidden-deleted-container">
        <div id="hidden-deleted-container-default">
            <div id="page-1">
                <?=$deletedProductView;?>
            </div>
        </div>
        <div id="hidden-deleted-container-lastmodified">
            
        </div>
        <div id="hidden-deleted-container-new">
            
        </div>
    </div>
    <div id="hidden-drafted-container">
        <div id="hidden-drafted-container-default">
            <div id="page-1">
                <?=$draftedProductView;?>
            </div>
        </div>
        <div id="hidden-drafted-container-lastmodified">
            
        </div>
        <div id="hidden-drafted-container-new">
            
        </div>
    </div>
</div>

<div id="hidden-feedback-container">
    <div id="as-buyer">
        <div id="page-1">
            <?=$allFeedBackView['asBuyerView']; ?>
        </div>
    </div>
    <div id="as-seller"> 
        <div id="page-1">
            <?=$allFeedBackView['asSellerView']; ?>
        </div>
    </div>
    <div id="as-other-seller">
        <div id="page-1">
            <?=$allFeedBackView['asOtherSellerView']; ?>
        </div>
    </div>
    <div id="as-other-buyer">
        <div id="page-1">
            <?=$allFeedBackView['asOtherBuyerView']; ?>
        </div>
    </div>
</div>

<link type="text/css" href='/assets/css/font-awesome/css/font-awesome.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<div class="div-tab">
    <div class="div-member-stats">
        <div class="div-img-cover">
            <img src="<?=$bannerImage;?>" class="img-follower-cover"/>
            <img src="<?=$avatarImage; ?>" class="vendor-follower-img"/>
            <div class="cover-overlay"></div>
        </div>
        <div class="clear"></div>
        <div class="div-stats">
            <div class="div-top-name">
                <div class="row">
                    <div class="col-xs-5">
                        <p class="p-username"><span class="icon-profile"> </span><?=html_escape($member->getUserName());?></p>
                    </div>
                    <div class="col-xs-3 col-progress-text">
                        <div class="div-progress-container-text" align="right">
                            Account Progress: 70%
                        </div>
                    </div>
                    <div class="col-xs-4 col-progress-bar">
                        <div class="div-progress-bar-container" align="right">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                    <span class="sr-only">70% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="div-stats-numbers">
                <div class="row">
                    <div class="col-xs-7">
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
                    <div class="col-xs-5">
                        <div class="div-feedback-stat">
                            <p class="p-stat-title">
                                Feedback:
                            </p>
                            <table width="100%" class="table-stat-ratings">
                                <tbody>
                                    <tr>
                                        <td class="td-criteria">Item Quality: </td>
                                        <td class="td-star" align="right">
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-criteria">Communication: </td>
                                        <td class="td-star" align="right">
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-criteria">Shipment Time: </td>
                                        <td class="td-star" align="right">
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat star-active"></i>
                                            <i class="icon-star star-stat"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-criteria">Total Reviews: </td>
                                        <td class="td-total-review" align="center">
                                            20
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebarTabs">
        <div class="submenu-container">
            <ul class="submenu-ul">
                <a href="#active-items"><li>Active Items<span class="circle-total"><?=$activeProductCount;?></span></li></a>
                <a href="#deleted-items"><li>Deleted Items<span class="circle-total"><?=$deletedProductCount;?></span></li></a>
                <a href="#draft-items"><li>Draft Items<span class="circle-total"><?=$draftedProductCount;?></span></li></a>
                <a href="#feedbacks"><li>Feedbacks<span class="circle-total">0</span></li></a>
                <a href="#sale"><li>Sale</li></a>
            </ul>
        </div>
        <div class="product-items">
            <div id="active-items">
                <!--DISPLAY IF THERE ARE NO AVAILABLE ITEMS
                <div class="jumbotron no-items">
                    <i class="icon-category"></i>No items for this category
                </div>-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-search-item">
                            <input type="text" class="input-search-item" placeholder="Search product from list..."/>
                            <span class="icon-search"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-filter">
                            <label>Sort by:</label>
                            <select class="select-filter-item">
                                <option>last modified</option>
                                <option>new</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <?=$activeProductView;?>
               
            </div>
            

            <div id="deleted-items">
                <!--DISPLAY IF THERE ARE NO AVAILABLE ITEMS
                <div class="jumbotron no-items">
                    <i class="icon-category"></i>No items for this category
                </div>-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-search-item">
                            <input type="text" class="input-search-item" placeholder="Search product from list..."/>
                            <span class="icon-search"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-filter">
                            <label>Sort by:</label>
                            <select class="select-filter-item">
                                <option>last modified</option>
                                <option>new</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="items-list-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="item-list-panel">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td class="td-image-cont" width="20%" >
                                                <div class="div-product-image" style="background: url(/assets/images/products/htc-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                                    
                                                </div>
                                            </td>
                                            <td class="td-meta-info">
                                                <p class="item-list-name">
                                                    <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                        HTC One
                                                    </a>
                                                </p>
                                                <p class="item-amount">
                                                    <span class="item-current-amount">P34,000.00</span>
                                                </p>
                                                <div class="div-meta-description">
                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <span class="strong-label">Sold Item(s) : </span> 20
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <span class="strong-label">Available Stock(s) : </span> 2
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <table>
                                                                <tr>
                                                                    <td class="td-label-desc"><span class="strong-label">Description: </span></td>
                                                                    <td class="td-desc-item">
                                                                        <?php 
                                                                            $dummytext = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.";
                                                                            echo substr_replace( $dummytext, "...", 100);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                            <td class="td-item-actions" width="25%">
                                                <p>Last Modified: 2014-01-21</p>
                                                <div class="">
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat"></i>
                                                </div>
                                                <p>Total Reviews : 20</p>
                                                <button class="btn btn-action-edit">
                                                    <i class="icon-edit"></i>edit
                                                </button>
                                                
                                                <button class="btn btn-action-delete">
                                                    <i class="icon-delete"></i>delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            
                                            </td>
                                            <td colspan="2" class="td-attributes">
                                                <div class="info-main-cont">
                                                    <div class="toggle-info" id="info-item-3">
                                                        <i class="info-item-icon-3 fa fa-plus-circle"></i> <span class="text-info-icon-3">more info</span>
                                                    </div>
                                                    <div class="info-attributes" id="info-attributes-3">
                                                        <div class="row">
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Color : </span>blue, charcoal black, white
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Memory : </span>16gb, 32gb, 64gb
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">SIM : </span>single, dual
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Material : </span>plastic, metal
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--ITEM 2-->
                 <div class="items-list-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="item-list-panel">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td class="td-image-cont" width="20%" >
                                                <div class="div-product-image" style="background: url(/assets/images/products/lg-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                                    
                                                </div>
                                            </td>
                                            <td class="td-meta-info">
                                                <p class="item-list-name">
                                                    <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                        LG G3
                                                    </a>
                                                </p>
                                                <p class="item-amount">
                                                    <span class="item-current-amount">P21,000.00</span>
                                                </p>
                                                <div class="div-meta-description">
                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <span class="strong-label">Sold Item(s) : </span> 20
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <span class="strong-label">Available Stock(s) : </span> <span class="out-stock-text">Out of Stock</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <table>
                                                                <tr>
                                                                    <td class="td-label-desc"><span class="strong-label">Description: </span></td>
                                                                    <td class="td-desc-item">
                                                                        <?php 
                                                                            $dummytext = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.";
                                                                            echo substr_replace( $dummytext, "...", 100);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                            <td class="td-item-actions" width="25%">
                                                <p>Last Modified: 2014-01-21</p>
                                                <div class="">
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat"></i>
                                                </div>
                                                <p>Total Reviews : 20</p>
                                                <button class="btn btn-action-edit">
                                                    <i class="icon-edit"></i>edit
                                                </button>
                                                
                                                <button class="btn btn-action-delete">
                                                    <i class="icon-delete"></i>delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            
                                            </td>
                                             <td colspan="2" class="td-attributes">
                                                <div class="info-main-cont">
                                                    <div class="toggle-info" id="info-item-4">
                                                        <i class="info-item-icon-4 fa fa-plus-circle"></i> <span class="text-info-icon-4">more info</span>
                                                    </div>
                                                    <div class="info-attributes" id="info-attributes-4">
                                                        <div class="row">
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Color : </span>blue, charcoal black, white
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Memory : </span>16gb, 42gb, 64gb
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">SIM : </span>single, dual
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Material : </span>plastic, metal
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
            
            <div id="draft-items">
                <!--DISPLAY IF THERE ARE NO AVAILABLE ITEMS
                <div class="jumbotron no-items">
                    <i class="icon-category"></i>No items for this category
                </div>-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-search-item">
                            <input type="text" class="input-search-item" placeholder="Search product from list..."/>
                            <span class="icon-search"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-filter">
                            <label>Sort by:</label>
                            <select class="select-filter-item">
                                <option>last modified</option>
                                <option>new</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="items-list-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="item-list-panel">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td class="td-image-cont" width="20%" >
                                                <div class="div-product-image" style="background: url(/assets/images/products/graff-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                                    
                                                </div>
                                            </td>
                                            <td class="td-meta-info">
                                                <p class="item-list-name">
                                                    <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                        Graff Necklace
                                                    </a>
                                                </p>
                                                <p class="item-amount">
                                                    <span class="item-current-amount">P34,000.00</span>
                                                </p>
                                                <div class="div-meta-description">
                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <span class="strong-label">Sold Item(s) : </span> 20
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <span class="strong-label">Available Stock(s) : </span> 2
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <table>
                                                                <tr>
                                                                    <td class="td-label-desc"><span class="strong-label">Description: </span></td>
                                                                    <td class="td-desc-item">
                                                                        <?php 
                                                                            $dummytext = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.";
                                                                            echo substr_replace( $dummytext, "...", 100);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                            <td class="td-item-actions" width="25%">
                                                <p>Last Modified: 2014-01-21</p>
                                                <div class="">
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat"></i>
                                                </div>
                                                <p>Total Reviews : 20</p>
                                                <button class="btn btn-action-edit">
                                                    <i class="icon-edit"></i>edit
                                                </button>
                                                
                                                <button class="btn btn-action-delete">
                                                    <i class="icon-delete"></i>delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            
                                            </td>
                                            <td colspan="2" class="td-attributes">
                                                <div class="info-main-cont">
                                                    <div class="toggle-info" id="info-item-5">
                                                        <i class="info-item-icon-5 fa fa-plus-circle"></i> <span class="text-info-icon-5">more info</span>
                                                    </div>
                                                    <div class="info-attributes" id="info-attributes-5">
                                                        <div class="row">
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Color : </span>green, blue
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Material : </span>gold, silver, bronze
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--ITEM 2-->
                 <div class="items-list-container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="item-list-panel">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td class="td-image-cont" width="20%" >
                                                <div class="div-product-image" style="background: url(/assets/images/products/swar-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                                    
                                                </div>
                                            </td>
                                            <td class="td-meta-info">
                                                <p class="item-list-name">
                                                    <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                                        Swarovski Necklace
                                                    </a>
                                                </p>
                                                <p class="item-amount">
                                                    <span class="item-current-amount">P21,000.00</span>
                                                </p>
                                                <div class="div-meta-description">
                                                    <div class="row">
                                                        <div class="col-xs-4">
                                                            <span class="strong-label">Sold Item(s) : </span> 20
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <span class="strong-label">Available Stock(s) : </span> <span class="out-stock-text">Out of Stock</span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <table>
                                                                <tr>
                                                                    <td class="td-label-desc"><span class="strong-label">Description: </span></td>
                                                                    <td class="td-desc-item">
                                                                        <?php 
                                                                            $dummytext = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.";
                                                                            echo substr_replace( $dummytext, "...", 100);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                            <td class="td-item-actions" width="25%">
                                                <p>Last Modified: 2014-01-21</p>
                                                <div class="">
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat star-active"></i>
                                                    <i class="icon-star star-stat"></i>
                                                </div>
                                                <p>Total Reviews : 20</p>
                                                <button class="btn btn-action-edit">
                                                    <i class="icon-edit"></i>edit
                                                </button>
                                                
                                                <button class="btn btn-action-delete">
                                                    <i class="icon-delete"></i>delete
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            
                                            </td>
                                            <td colspan="2" class="td-attributes">
                                                <div class="info-main-cont">
                                                    <div class="toggle-info" id="info-item-6">
                                                        <i class="info-item-icon-6 fa fa-plus-circle"></i> <span class="text-info-icon-6">more info</span>
                                                    </div>
                                                    <div class="info-attributes" id="info-attributes-6">
                                                        <div class="row">
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Color : </span>green, blue
                                                            </div>
                                                            <div class="col-xs-5">
                                                                <span class="strong-label">Material : </span>gold, silver, bronze
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
               
            </div>
            
            <div id="feedbacks">
                <div class="jumbotron no-items">
                    <i class="icon-category"></i>No items for this category
                </div>
            </div>
            <div id="sale">
                <div class="jumbotron no-items">
                    <i class="icon-category"></i>No items for this category
                </div>
            </div>
        </div>
    </div>
</div>

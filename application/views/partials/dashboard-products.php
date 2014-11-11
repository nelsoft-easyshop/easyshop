
<?php foreach ($products as $product): ?>
<div class="items-list-container">
    <div class="row">
        <div class="col-md-12">
            <div class="item-list-panel">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td class="td-image-cont" width="20%" >
                                <div class="div-product-image" style="background: url(/assets/images/products/samsung-p.jpg) center no-repeat; background-cover: cover; background-size: 90%;">
                                    <div class="pin-discount">
                                        13%
                                    </div>
                                </div>
                                
                            </td>
                            <td class="td-meta-info">
                                <p class="item-list-name">
                                    <a class="color-default" target="_blank" href="https://easyshop.ph.local/item/boom">
                                        <?=htmlspecialchars($product->getName(),ENT_QUOTES,'ISO-8859-1');;?>
                                    </a>
                                </p>
                                <p class="item-amount">
                                    <span class="item-original-amount">P34,000.00</span>
                                    <span class="item-current-amount">P24,000.00</span>
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
                                            <span class="strong-label">Category : </span> Special Promo
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
                                    <div class="toggle-info" id="info-item-1">
                                        <i class="info-item-icon-1 fa fa-plus-circle"></i> <span class="text-info-icon-1">more info</span>
                                    </div>
                                    <div class="info-attributes" id="info-attributes-1">
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
<?php endforeach; ?>


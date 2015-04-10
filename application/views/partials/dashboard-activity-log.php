
<?php foreach ($activities as $activity): ?>

    <!--Layout for EDIT PROFILE INFORMATION-->
    <?php if($activity['type'] === \EasyShop\Entities\EsActivityType::INFORMATION_UPDATE 
            && $activity['data']['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_INFORMATION_UPDATE
            && count($activity['data']['contents']) > 0): ?>
        <div class="log-outer">
            <div class="row">
                <div class="col-xs-1 col-icon-container">
                    <div class="log-icon-container">
                        <center>
                            <div class="log-icon-circle">
                                <i class="fa icon-profile "></i>
                            </div>
                         </center>
                    </div>
                </div>
                <div class="col-xs-11 col-log-container">
                    <div class="log-container">
                        <div class="row">
                            <div class="col-xs-9 col-log-meta-cont">
                                <div class="row">
                                    <div class="col-md-9">
                                        <p class="log-title">
                                            Updated Personal Information
                                            <br>
                                            <?php foreach ($activity['data']['contents'] as $content): ?>
                                                <b><?=html_escape($content);?></b>
                                                <br>
                                            <?php endforeach; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3 col-date">
                                <div class="log-date-time">
                                    <span class="log-date">
                                        <?=$activity['activityDate']; ?>
                                    </span>
                                    <span class="log-time">
                                        <?=$activity['activityTime']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!--Layout for UPDATE COVER PHOTO-->
    <?php if($activity['type'] === \EasyShop\Entities\EsActivityType::INFORMATION_UPDATE 
            && ($activity['data']['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_BANNER_UPDATE
                || $activity['data']['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_AVATAR_UPDATE)): ?>
        <div class="log-outer">
            <div class="row">
                <div class="col-xs-1 col-icon-container">
                    <div class="log-icon-container">
                        <center>
                            <div class="log-icon-circle">
                                <i class="fa icon-image "></i>
                            </div>
                         </center>
                    </div>
                </div>
                <div class="col-xs-11 col-log-container">
                    <div class="log-container">
                        <div class="row">
                            <div class="col-xs-9 col-log-meta-cont">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="log-title">
                                            <?php if($activity['data']['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_BANNER_UPDATE):?>
                                                Updated cover photo
                                            <?php else: ?>
                                                Updated profile photo
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="#">
                                            <?php if($activity['data']['action'] === \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_BANNER_UPDATE):?>
                                                <img class="log-image-cover" src="<?php echo getAssetsDomain().'.'.$activity['data']['userImage']; ?>"/>
                                            <?php else: ?>
                                                <img class="log-image" src="<?php echo getAssetsDomain().'.'.$activity['data']['userImage']; ?>"/>
                                            <?php endif; ?>
                                        </a> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3 col-date">
                                <div class="log-date-time">
                                    <span class="log-date">
                                        <?=$activity['activityDate']; ?>
                                    </span>
                                    <span class="log-time">
                                        <?=$activity['activityTime']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!--Layout for FOLLOW AND UNFOLLOW activity-->
    <?php if($activity['type'] === \EasyShop\Entities\EsActivityType::VENDOR_SUBSCRIPTION): ?>
        <div class="log-outer">
            <div class="row">
                <div class="col-xs-1 col-icon-container">
                    <div class="log-icon-container">
                        <center>
                            <div class="log-icon-circle">
                                <i class="fa icon-add "></i>
                            </div>
                         </center>
                    </div>
                </div>
                <div class="col-xs-11 col-log-container">
                    <div class="log-container">
                        <div class="row">
                            <div class="col-xs-9 col-log-meta-cont">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="log-title">
                                            <?=html_escape($activity['data']['stringAction']);?> <a href="/<?=$activity['data']['slug'];?>"><?=html_escape($activity['data']['storeName']);?></a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="log-followed-user">
                                            <a href="/<?=$activity['data']['slug'];?>">
                                                <img class="log-image" src="<?php echo getAssetsDomain().'.'.$activity['data']['userImage']; ?>"/>
                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3 col-date">
                                <div class="log-date-time">
                                    <span class="log-date">
                                        <?=$activity['activityDate']; ?>
                                    </span>
                                    <span class="log-time">
                                        <?=$activity['activityTime']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!--Layout for CHECKOUT-->
    <?php if($activity['type'] === \EasyShop\Entities\EsActivityType::TRANSACTION_UPDATE): ?>
        <div class="log-outer">
            <div class="row">
                <div class="col-xs-1 col-icon-container">
                    <div class="log-icon-container">
                        <center>
                            <div class="log-icon-circle">
                                <i class="fa icon-payment "></i>
                            </div>
                         </center>
                    </div>
                </div>
                <?php if($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_BOUGHT): ?>
                    <div class="col-xs-11 col-log-container">
                        <div class="log-container">
                            <div class="row">
                                <div class="col-xs-9 col-log-meta-cont">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="log-title">
                                                Checked out item(s) through <b><?=html_escape($activity['data']['paymentType']); ?></b>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="log-meta-table">
                                                <tr>
                                                    <td class="v-align-top">
                                                        <div class="log-meta"> 
                                                            <div class="">
                                                                <b>Invoice Number : </b><br/><?=html_escape($activity['data']['invoiceNumber']);?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                 </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-date">
                                    <div class="log-date-time">
                                        <span class="log-date">
                                            <?=$activity['activityDate']; ?>
                                        </span>
                                        <span class="log-time">
                                            <?=$activity['activityTime']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-xs-11 col-log-container">
                        <div class="log-container">
                            <div class="row">
                                <div class="col-xs-9 col-log-meta-cont">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="log-title">
                                                <?php if($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_RECEIVED): ?>
                                                    <b>Received</b> an item
                                                <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_REFUNDED): ?>
                                                    <b>Cancelled</b> an item
                                                <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_COD_COMPLETED): ?>
                                                    Item mark as <b>Completed</b>
                                                <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_REJECTED): ?>
                                                    <b>Rejected</b> an item
                                                <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_UNREJECTED): ?>
                                                    <b>Unrejected</b> an item
                                                <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_ADD_SHIPMENT): ?>
                                                    <b>Add</b> shipment information
                                                <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_EDIT_SHIPMENT): ?> 
                                                    <b>Modify</b> shipment information
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="log-meta-table">
                                                <tr>
                                                    <td class="v-align-top">
                                                        <div class="log-image" style="background: url(<?php echo getAssetsDomain().$activity['data']['imageDirectory'].'thumbnail/'.$activity['data']['imageFile']; ?>) center no-repeat; background-size: cover;"></div>
                                                    </td>
                                                    <td class="v-align-top">
                                                        <div class="log-meta">
                                                            <a class="log-meta-link-title" href="/item/<?=$activity['data']['slug']; ?>">
                                                                <?=html_escape($activity['data']['name']); ?>
                                                            </a>
                                                            <div class="log-meta-price"> 
                                                                <span class="log-new-price">
                                                                    P <?=number_format($activity['data']['final_price'], 2, '.', ',')?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                 </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-date">
                                    <div class="log-date-time">
                                        <span class="log-date">
                                            <?=$activity['activityDate']; ?>
                                        </span>
                                        <span class="log-time">
                                            <?=$activity['activityTime']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!--Layout for MODIFIED ITEMS-->
    <?php if($activity['type'] === \EasyShop\Entities\EsActivityType::PRODUCT_UPDATE): ?>
        <div class="log-outer">
            <div class="row">
                <div class="col-xs-1 col-icon-container">
                    <div class="log-icon-container">
                        <center>
                            <div class="log-icon-circle">
                                <i class="fa icon-delete "></i>
                            </div>
                         </center>
                    </div>
                </div>
                <div class="col-xs-11 col-log-container">
                    <div class="log-container">
                        <div class="row">
                            <div class="col-xs-9 col-log-meta-cont">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="log-title">
                                            <?php if($activity['data']['action'] === \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_UPDATE): ?>
                                                Added item into your listing
                                            <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_SOFT_DELETE): ?>
                                                Temporarily Deleted item(s)
                                            <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_FULL_DELETE): ?>
                                                Permanently Deleted item(s)
                                            <?php elseif($activity['data']['action'] === \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_RESTORE): ?>
                                                Restore item(s)
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="log-meta-table">
                                            <tr>
                                                <td class="v-align-top">
                                                    <div class="log-image" style="background: url(<?php echo getAssetsDomain().$activity['data']['imageDirectory'].'thumbnail/'.$activity['data']['imageFile']; ?>) center no-repeat; background-size: cover;"></div>
                                                </td>
                                                <td class="v-align-top">
                                                    <div class="log-meta">
                                                        <a class="log-meta-link-title" href="/item/<?=$activity['data']['slug']; ?>">
                                                            <?=html_escape($activity['data']['name']); ?>
                                                        </a>
                                                        <div class="log-meta-price">
                                                            <?php if($activity['data']['discount'] > 0): ?>
                                                                <span class="log-original-price">
                                                                    <s>P <?=number_format($activity['data']['original_price'], 2, '.', ',')?></s>
                                                                </span> 
                                                            <?php endif; ?>
                                                            <span class="log-new-price">
                                                                P <?=number_format($activity['data']['final_price'], 2, '.', ',')?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                             </tr>
                                        </table> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3 col-date">
                                <div class="log-date-time">
                                    <span class="log-date">
                                        <?=$activity['activityDate']; ?>
                                    </span>
                                    <span class="log-time">
                                        <?=$activity['activityTime']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!--Layout for FEEDBACK-->
    <?php if($activity['type'] === \EasyShop\Entities\EsActivityType::FEEDBACK_UPDATE): ?>
        <div class="log-outer">
            <div class="row">
                <div class="col-xs-1 col-icon-container">
                    <div class="log-icon-container">
                        <center>
                            <div class="log-icon-circle">
                                <i class="fa icon-star"></i>
                            </div>
                         </center>
                    </div>
                </div>
                <div class="col-xs-11 col-log-container">
                    <div class="log-container">
                        <div class="row">
                            <div class="col-xs-9 col-log-meta-cont">
                                <?php if((int)$activity['data']['action'] === \EasyShop\Activity\ActivityTypeFeedbackUpdate::ACTION_FEEDBACK_USER): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="log-title">
                                                Posted feedback message to <a href="/<?=$activity['data']['revieweeSlug']; ?>"><?=html_escape($activity['data']['revieweeName']); ?></a>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="log-meta-table">
                                                <tr>
                                                    <td class="v-align-top">
                                                        <a href="#">
                                                            <img class="log-image" src="<?php echo getAssetsDomain().'.'.$activity['data']['revieweeImage']; ?>"/>
                                                        </a>
                                                    </td>
                                                    <td class="v-align-top">
                                                        <div class="log-meta">
                                                            <div>
                                                                <b>Item Quality : </b>
                                                                <span class="log-feed-rate">
                                                                    <?php $tempRating = $activity['data']['rating1']; ?>
                                                                    <?php for ($i=0; $i < 5; $i++): ?>
                                                                        <i class="fa icon-star <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                                                        <?php $tempRating--; ?>
                                                                    <?php endfor; ?> 
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <b>Communication : </b>
                                                                <span class="log-feed-rate">
                                                                    <?php $tempRating = $activity['data']['rating2']; ?>
                                                                    <?php for ($i=0; $i < 5; $i++): ?>
                                                                        <i class="fa icon-star <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                                                        <?php $tempRating--; ?>
                                                                    <?php endfor; ?> 
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <b>Shipment Time : </b>
                                                                <span class="log-feed-rate">
                                                                    <?php $tempRating = $activity['data']['rating3']; ?>
                                                                    <?php for ($i=0; $i < 5; $i++): ?>
                                                                        <i class="fa icon-star <?=$tempRating > 0 ? 'star-active' : '' ?>"></i>
                                                                        <?php $tempRating--; ?>
                                                                    <?php endfor; ?> 
                                                                </span>
                                                            </div>
                                                            <b>
                                                                <i>" <?=html_escape($activity['data']['message']); ?> "</i>
                                                            </b>
                                                        </div>
                                                    </td>
                                                 </tr>
                                            </table>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="log-title">
                                                Posted product feedback to <a href="/item/<?=$activity['data']['slug']; ?>"><?=html_escape($activity['data']['name']); ?></a>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="log-meta-table">
                                                <tr>
                                                    <td class="v-align-top">
                                                        <a href="/item/<?=$activity['data']['slug']; ?>">
                                                            <img class="log-image" src="<?php echo getAssetsDomain().$activity['data']['imageDirectory'].'thumbnail/'.$activity['data']['imageFile']; ?>"/>
                                                        </a>
                                                    </td>
                                                    <td class="v-align-top">
                                                        <div class="log-meta">
                                                            <b>
                                                                <i>" <?=html_escape($activity['data']['message']); ?> "</i>
                                                            </b>
                                                        </div>
                                                    </td>
                                                 </tr>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-3 col-date">
                                <div class="log-date-time">
                                    <span class="log-date">
                                        <?=$activity['activityDate']; ?>
                                    </span>
                                    <span class="log-time">
                                        <?=$activity['activityTime']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
<?php endforeach; ?>

<center>
    <?=$pagination; ?>
</center>


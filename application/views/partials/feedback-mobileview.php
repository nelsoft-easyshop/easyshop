<?php 
    switch($feedbackType){
        case EasyShop\Entities\EsMemberFeedback::TYPE_AS_BUYER:
            $mainContainerClass = 'feedback-from-seller';
            $containerTitle =  'Feedback from Seller';
            $tabCount = 1; 
            break;
        case EasyShop\Entities\EsMemberFeedback::TYPE_AS_SELLER: 
            $mainContainerClass = 'feedback-from-buyer';
            $containerTitle =  'Feedback from Buyer';
            $tabCount = 2; 
            break;
        case EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER: 
            $mainContainerClass = 'feedback-for-buyer';
            $containerTitle =  ' Feedback for Buyer';
            $tabCount = 3; 
            break;
        case EasyShop\Entities\EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER:
            $mainContainerClass = 'feedback-for-seller';
            $containerTitle =  'Feedback for Seller';
            $tabCount = 4; 
            break;
    }
?>

<div class="tab-pane" data-identifier="<?php echo html_escape($id); ?>">

    <div class="feedback-cat-mobile <?php echo $mainContainerClass ?> <?php echo (isset($isActive) && $isActive) ? 'active-bar' : '' ?>">
        <?php echo html_escape($containerTitle); ?>
    </div>
    <div class="feedback-mobile-cont feedback-mobile-<?php echo $tabCount; ?>" <?php echo (isset($isActive) && $isActive) ? 'style="display:block"' : '' ?> >
        
        <?php if(count($feedbacks) > 0): ?>
        
            <?php foreach($feedbacks as $feedback): ?>
            <div class="feedback-mobile-item">
                <table class="table-feed-mobile">
                    <tbody>
                        <tr>
                            <td>
                                <div class="div-user-image">
                                    <a href="/<?php echo html_escape($feedback['userslug'])?>">
                                        <img src="<?php echo $feedback['userImage'];?>" class="img-user-image">
                                    </a>
                                </div>
                            </td>
                            <td class="td-info-mobile">
                                <p class="p-user-name">
                                    <a href="/<?php echo html_escape($feedback['userslug'])?>">
                                        <?php echo html_escape($feedback['storename']); ?>
                                    </a>
                                </p>
                                <p class="p-date-feedback">
                                    <?php echo $feedback['dateadded']->format('jS F, Y'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="feedback-item-mobile-content">
                    <div class="row">
                        <?php $ratingCounter = 1; ?>
                        <?php foreach($ratingHeaders as $ratingHeader): ?>
                            <div class="col-md-4">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="td-feedback-criteria"><?php echo html_escape($ratingHeader) ?></td>
                                            <td class="td-feedback-star">
                                                <span>
                                                
                                                    <?php for($i = 0; $i < $feedback['rating'.$ratingCounter]; $i++): ?>
                                                        <i class="fa fa-star star-feed star-active"></i>
                                                    <?php endfor; ?>
                                                    
                                                    <?php for($i = 0; $i < 5 - $feedback['rating'.$ratingCounter]; $i++): ?>
                                                        <i class="fa fa-star star-feed"></i>
                                                    <?php endfor; ?>
                                                    
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php $ratingCounter++; ?>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 col-item-message">
                            "<?php echo html_escape($feedback['feedbMsg']); ?>"
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <?php endforeach; ?>
        
        <?php else: ?>
        
            <div class="jumbotron no-feedback-list">
                <center>
                    <span class="fa fa-clipboard fa-2x"></span>
                    <strong>NO FEEDBACK FOR THIS CATEGORY</strong>
                </center>
            </div>
            
        <?php endif; ?>
            
        <br/>
        
        <div style="text-align:center" class="pagination-container mobile-pagination">
            <?php echo $pagination; ?>
        </div>
        
    </div>
</div>


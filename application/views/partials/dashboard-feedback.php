<?php foreach ($feedbacks as $feedback): ?>
    <div class="row row-feedback-item">
        <div class="col-md-2 col-feedback-user">
            <div class="user-feeder">
                <div class="user-image-container">
                    <center class="center-image">
                        <div class="div-user-image">
                            <a href="/<?=html_escape($feedback['revieweeSlug']); ?>">
                                <img src="<?php echo getAssetsDomain().'.'.$feedback['revieweeAvatarImage'];?>" class="img-user-image"/>
                            <a/>
                        </div>
                    </center>
                </div>
                <p class="p-user-name">
                    <a href="/<?=html_escape($feedback['revieweeSlug']); ?>">
                        <?=html_escape($feedback['revieweeUsername']); ?>
                    </a>
                </p>
                <p class="p-date-feedback">
                    <?=$feedback['dateadded']->format('dS F , Y'); ?> 
                </p>
            </div>
            <table class="table-feed-mobile">
                <tbody>
                    <tr>
                        <td>
                            <div class="div-user-image">
                                <a href="/<?=html_escape($feedback['revieweeSlug']); ?>">
                                    <img src="<?php echo getAssetsDomain().'.'.$feedback['revieweeAvatarImage'];?>" class="img-user-image"/>
                                <a/>
                            </div>
                        </td>
                        <td class="td-info-mobile">
                            <p class="p-user-name">
                                <a href="/<?=html_escape($feedback['revieweeSlug']); ?>">
                                    <?=html_escape($feedback['revieweeUsername']); ?>
                                </a>
                            </p>
                            <p class="p-date-feedback">
                                <?=$feedback['dateadded']->format('d S F , Y'); ?> 
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-10 col-feedback-container" style="padding-left: 0px;">
            <div class="panel panel-default panel-feedback-item">
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="pin-cat">
                            <?php if( (int)$feedback['feedbKind'] === 0 
                                      && (int)$memberId === $feedback['revieweeId'] ): ?>
                            <p class="feedback-cat-title as-seller">Feedback from buyer<div class="green-tip"></div></p>
                            <?php elseif( (int)$feedback['feedbKind'] === 1 
                                      && (int)$memberId === $feedback['revieweeId'] ): ?>
                            <p class="feedback-cat-title as-buyer">Feedback from seller<div class="orange-tip"></div></p>
                            <?php elseif( (int)$feedback['feedbKind'] === 1 
                                      && (int)$memberId === $feedback['reviewerId'] ): ?>
                            <p class="feedback-cat-title for-seller">Feedback for buyer<div class="blue-tip"></div></p>
                            <?php elseif( (int)$feedback['feedbKind'] === 0 
                                      && (int)$memberId === $feedback['reviewerId'] ): ?>
                            <p class="feedback-cat-title for-buyer">Feedback for seller<div class="red-tip"></div></p>
                            <?php endif; ?>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td class="td-feedback-criteria">Item quality</td>
                                        <td class="td-feedback-star">
                                            <span>
                                                <?php for($i = 0; $i < $feedback['rating1']; $i++): ?>
                                                    <i class="fa fa-star star-feed star-active"></i>
                                                <?php endfor; ?>
                                                
                                                <?php for($i = 0; $i < 5 - $feedback['rating1']; $i++): ?>
                                                    <i class="fa fa-star star-feed"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td class="td-feedback-criteria">Communication</td>
                                        <td class="td-feedback-star">
                                            <span>
                                                <?php for($i = 0; $i < $feedback['rating2']; $i++): ?>
                                                    <i class="fa fa-star star-feed star-active"></i>
                                                <?php endfor; ?>
                                                
                                                <?php for($i = 0; $i < 5 - $feedback['rating2']; $i++): ?>
                                                    <i class="fa fa-star star-feed"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <table>
                                    <tr>
                                        <td class="td-feedback-criteria">Item shipment</td>
                                        <td class="td-feedback-star">
                                            <span>
                                                <?php for($i = 0; $i < $feedback['rating3']; $i++): ?>
                                                    <i class="fa fa-star star-feed star-active"></i>
                                                <?php endfor; ?>
                                                
                                                <?php for($i = 0; $i < 5 - $feedback['rating3']; $i++): ?>
                                                    <i class="fa fa-star star-feed"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                         <p class="feedback-content" style="text-align: left;"><i>"<?=html_escape($feedback['feedbMsg']); ?>"</i></p>
                         <p class="feedback-whom">&mdash; from <a href="/<?=html_escape($feedback['reviewerSlug']); ?>"><?=html_escape($feedback['reviewerUsername']); ?></a></p>
                   </div>
                    
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
<center>
    <?=$pagination; ?>
</center>
<br/>


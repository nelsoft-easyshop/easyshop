<?php foreach ($feedbacks as $feedback): ?>
    <div class="row">
        <div class="col-md-2 col-feedback-user">
            <div class="user-feeder">
                <div class="user-image-container">
                    <center class="center-image">
                        <div class="div-user-image">
                            <a href="/<?=$feedback['reviewerSlug']; ?>">
                                <img src="" class="img-user-image"/>
                            <a/>
                        </div>
                    </center>
                </div>
                <p class="p-user-name">
                    <a href="/<?=$feedback['reviewerSlug']; ?>">
                        <?=html_escape($feedback['reviewerUsername']); ?>
                    </a>
                </p>
                <p class="p-date-feedback">
                    <?=$feedback['dateadded']->format('M d, Y  h:i a'); ?> 
                </p>
            </div>
            <table class="table-feed-mobile">
                <tbody>
                    <tr>
                        <td>
                            <div class="div-user-image">
                                <a href="/<?=$feedback['reviewerSlug']; ?>">
                                    <img src="" class="img-user-image"/>
                                <a/>
                            </div>
                        </td>
                        <td class="td-info-mobile">
                            <p class="p-user-name">
                                <a href="/<?=$feedback['reviewerSlug']; ?>">
                                    <?=html_escape($feedback['reviewerUsername']); ?>
                                </a>
                            </p>
                            <p class="p-date-feedback">
                                <?=$feedback['dateadded']->format('M d, Y  h:i a'); ?> 
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-10 col-feedback-container" style="padding-left: 0px;">
            <div class="panel panel-default panel-feedback-item">
                <div class="row">
                    <div class="col-md-6">
                        <?php if( (int)$feedback['feedbKind'] === 1 
                                  && (int)$memberId === $feedback['reviewerId'] ): ?>
                        <p class="feedback-cat-title">Feedback as seller</p>
                        <?php elseif( (int)$feedback['feedbKind'] === 1 
                                  && (int)$memberId === $feedback['reviewerId'] ): ?>
                        <p class="feedback-cat-title">Feedback as seller</p>
                        <?php elseif( (int)$feedback['feedbKind'] === 0 
                                  && (int)$memberId === $feedback['reviewerId'] ): ?>
                        <p class="feedback-cat-title">Feedback as seller</p>
                        <?php elseif( (int)$feedback['feedbKind'] === 0 
                                  && (int)$memberId === $feedback['reviewerId'] ): ?>
                        <p class="feedback-cat-title">Feedback as seller</p>
                        <?php endif; ?>
                        <table>
                            <tr>
                                <td class="td-feedback-criteria">Item quality</td>
                                <td>
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
                            <tr>
                                <td class="td-feedback-criteria">Communication</td>
                                <td>
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
                            <tr>
                                <td class="td-feedback-criteria">Shipment time</td>
                                <td>
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
                    <div class="col-md-6 col-item-message">
                        <?=html_escape($feedback['feedbMsg']); ?>
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




<div class="tab-pane <?php echo (isset($isActive) && $isActive) ? 'active' : '' ?>" id="<?php echo $id?>">
    <?php if(count($feedbacks) > 0): ?>
        <?php foreach($feedbacks as $feedback): ?>
            <div class="row">
                <div class="col-xs-2 no-padding">
                    <center>
                        <div class="div-user-image">
                            <a href="/<?php echo html_escape($feedback['userslug'])?>">
                                <img src="<?php echo $feedback['userImage'];?>" class="img-user-image">
                            <a/>
                        </div>
                        <p class="p-user-name">
                            <a href="/<?php echo html_escape)$feedback['userslug']); ?>">
                                <?php echo html_escape($feedback['storename']); ?>
                            </a>
                        </p>
                        <p class="p-date-feedback">
                            <?php echo $feedback['dateadded']->format('jS F, Y'); ?>
                        </p>
                    </center>
                </div>
                <div class="col-xs-10 col-feedback-container">
                    <div class="panel panel-default panel-feedback-item">
                        <div class="row">
                            <div class="col-xs-6">
                                <table>
                                    <?php $ratingCounter = 1; ?>
                                    <?php foreach($ratingHeaders as $ratingHeader): ?>
                                        <tr>
                                            <td class="td-feedback-criteria"><?php echo html_escape($ratingHeader) ?></td>
                                            <td>
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
                                    <?php $ratingCounter++; ?>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                            <div class="col-xs-6 col-item-message">
                                <?php echo html_escape($feedback['feedbMsg']); ?>
                            </div>
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
    
    <div style="text-align:center" class="pagination-container">
        <?php echo $pagination; ?>
    </div>

</div>


<?php foreach ($feedbacks as $feedback): ?>
    <div class="row">
        <div class="col-xs-2">
            <center>
                <div class="div-user-image">
                    <a href="/<?=$feedback['userslug']; ?>">
                        <img src="<?=$feedback['userImage']; ?>" class="img-user-image"/>
                    <a/>
                </div>
                <p class="p-user-name">
                    <a href="/<?=$feedback['userslug']; ?>">
                        <?=html_escape($feedback['username']); ?>
                    </a>
                </p>
                <p class="p-date-feedback"> 
                    <?=$feedback['dateadded']->format('M d, Y  h:i a'); ?> 
                </p>
            </center>
        </div>
        <div class="col-xs-10 col-feedback-container" style="padding-left: 0px;">
            <div class="panel panel-default panel-feedback-item">
                <div class="row">
                    <div class="col-xs-6">
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
                    <div class="col-xs-6 col-item-message">
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


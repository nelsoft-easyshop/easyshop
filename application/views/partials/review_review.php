<div class="row">
    <div class="col-sm-2 col-xs-12 col-user-image" align="center">
        <a href="#"><div class="div-user-image">
            <img src="<?php echo getAssetsDomain().'.'.$userPic?>" class="img-user"/>
        </div></a>
        <div class="clear"></div>
        <a href="#"><p class="p-username"><?=html_escape($reviewUsername)?></p></a>
        <p class="p-date-review"><?=$datesubmitted?></p>
    </div>
    <div class="col-sm-10 col-xs-12">
        <div class="div-review-content-container">
            <div class="row div-row-user">
                <div class="me">
                <table>
                    <tr>
                        <td>
                            <a href="#">
                                <div class="div-user-image">
                                    <img src="<?php echo getAssetsDomain().'.'.$userPic?>" class="img-user"/>
                                </div>
                            </a>
                        </td>
                        <td class="td-user-info">
                            <a href="#"><p class="p-username"><?=html_escape($reviewUsername)?></p></a>
                            <p class="p-date-review"><?=$datesubmitted?></p> 
                        </td>
                    </tr>
                </table>
                </div>
            </div>
            <p class="p-review-title"><?=html_escape($title)?>
                <span class="span-review-item-rate">
                    <?php for ($i=0; $i < 5; $i++): ?>
                        <span class="fa fa-star fa-review-rate <?=($rating > 0) ? 'fa-star-active' : '' ?> "></span>
                        <?php $rating--; ?>
                    <?php endfor; ?> 
                </span>
            </p>
            <div class="clear"></div>
            <p class="p-review-content">
                <?=nl2br(html_escape($review))?>
            </p>

            <?php if($canReview): ?>
            <a href="javascript:void(0)" class="p-reply-text" >
                <p class="pull-right">
                    <span class="text-cancel">Cancel </span>Reply
                </p> 
            </a> 
            <?php endif; ?>
            <div class="clear"></div>
        </div>
        <?php if($canReview): ?>
        <div class="div-reply-container">
            <p class="p-reply-title">Write Reply</p> 
            <div class="clear"></div>
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="subject" class="col-sm-2 control-label label-comment" style="text-align: left !important; margin-left: 10px;">Comment: </label>
                            <div class="col-sm-10 col-text-area" style="margin-left: -10px;">
                                <textarea id="textareaReview<?=$idReview?>" class="input-textarea" rows="7"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" align="center">
                    <button class="btn-reply btn-js-reply" data-parent="<?=$idReview?>">
                        Submit
                    </button>
                </div>
            </div> 
        </div>
        <?php endif; ?>
        <div class="clear"></div>
        <div class="div-review-item-container review-container-<?=$idReview?>">
        </div>
    </div>
</div>


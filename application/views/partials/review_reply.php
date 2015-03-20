<div class="row">
    <div class="col-xs-2 col-user-image no-padding" align="center">
        <a href="#"><div class="div-user-image">
            <img src="<?php echo getAssetsDomain().'.'.$userPic?>" class="img-user"/>
        </div></a>
        <div class="clear"></div>
        <a href="#"><p class="p-username"><?=html_escape($reviewUsername)?></p></a>
        <p class="p-date-review-replied-item"><?=$datesubmitted?></p>
    </div>
    <div class="col-sm-10 col-xs-12">
        <div class="div-review-content-container div-reply-content-container">
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
            <div class="clear"></div>
            <p class="p-review-content"><?=nl2br(html_escape($review))?></p>
        </div>
    </div>
</div>


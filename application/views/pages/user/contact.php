<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
        <div class="row row-contact">
            <?php echo $userDetails; ?>
            <div class="col-xs-9">
                <div class="panel-contact-message">
                    <?PHP if (!( isset($isLoggedIn) && $isLoggedIn  )) : ?>
                        <div class="jumbotron no-feedback-list">
                            <center>
                                <strong><span class="fa fa-sign-in"></span> Please log in to send a message.</strong>
                            </center>
                        </div>
                    <?PHP elseif ($seller->getUsername() !== $user->getUsername()) : ?>
                        <p class="panel-title-contact">
                            Leave A Message
                        </p>
                        <div class="div-message-form">
                            <?php echo form_open('messages/doSendMessage'); ?>
                            <div class="row">
                                <div class="col-xs-12 col-message-2">
                                    <input type="text" class="input-lg input-message" placeholder="NAME..." value="Recipient: <?php echo html_escape($seller->getUsername())?>" disabled="disabled"/>
                                    <input type="hidden" name="recipient" value="<?php echo $seller->getIdMember()?>" id="msg_recipient">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-message-2">
                                    <textarea class="input-lg input-message" name="msg" rows="7" placeholder="MESSAGE..." id="message"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <center>
                                    <input type="submit" class="btn btn-send" id="btn-send-msg" value="SEND MESSAGE">
                                </center>
                            </div>
                        </div>
                        <?php echo form_close();?>
                    <?PHP else : ?>
                        <div class="jumbotron no-feedback-list">
                            <center>
                                <strong><span class="fa fa-ban"></span> You are not allowed to send a message to your self.</strong>
                            </center>
                        </div>
                    <?PHP endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<script src="/assets/js/src/vendorpage_contact.js?ver=<?=ES_FILE_VERSION?>"></script>

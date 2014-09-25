<link type="text/css" href='<?=base_url()?>assets/css/contact.css' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
        <div class="row row-contact">
            
             <?php echo $userDetails; ?>

            <div class="col-xs-9">
                <div class="panel-contact-message">
                <?PHP if ($message_recipient->getUsername() !== $user['username']) : ?>
                    <p class="panel-title-contact">
                        Leave A Message
                    </p>
                    <div class="div-message-form">
                        <?php echo form_open('home/sendMessage'); ?>
                        <div class="row">
                            <div class="col-xs-6 col-message-1">
                                <input type="text" class="input-lg input-message" placeholder="NAME..." value="<?=$user['username']?>" disabled="disabled"/>
                                <input type="hidden" name="recipient" value="<?=$message_recipient->getIdMember()?>" id="msg_recipient">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
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
                            <strong>You are not allowed to send message to your self.</strong>
                        </center>
                    </div>
                <?PHP endif; ?>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script src="/assets/js/src/vendorpage_contact.js"></script>

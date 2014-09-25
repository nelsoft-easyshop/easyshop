<link type="text/css" href='<?=base_url()?>assets/css/contact.css' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
        <div class="row row-contact">
            <div class="col-xs-3 no-padding col-left-wing">
                <div class="left-wing">
                    <div class="panel-contact-details">
                        <p class="panel-title-contact">
                            Details
                        </p>
                         <i class="fa fa-edit fa-edit-icon pull-right" rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Edit vendor details"></i>
                       <script>
                            $('[rel=tooltip]').tooltip() 
                       </script>
                       <i class="fa fa-ban fa-cancel-edit pull-right"  rel="tooltip" data-toggle="tooltip" data-placement="left"  title="Cancel"></i>
                       <script>
                            $('[rel=tooltip]').tooltip() 
                       </script>
                       <table width="100%" class="table-contact-details">
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-user fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact">Seller2DaMax</text>
                                    <input type="text" class="input-detail" placeholder="Seller Name..." value="Seller2DaMax">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-phone fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact">09171234567</text>
                                    <input type="text" class="input-detail" placeholder="Contact Number..." value="09171234567">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-print fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact">+61 3 8376 6284</text>
                                    <input type="text" class="input-detail" placeholder="Fax Number..." value=" +61 3 8376 6284">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-map-marker fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact">Unit 8C Marc 200 Tower 1973 Taft Ave. Malate, Manila</text>
                                    <input type="text" class="input-detail" placeholder="Address Line..." value="Unit 8C Marc 200 Tower 1973 Taft Ave. Malate, Manila">
                                    <select class="input-detail input-detail-select">
                                        <option>- City -</option>
                                        <option selected>Manila</option>
                                        <option selected>Marikina</option>
                                        <option selected>Pasay</option>
                                        <option selected>Pasig</option>
                                    </select>
                                    <select class="input-detail input-detail-select">
                                        <option>- Province -</option>
                                        <option selected>Batangas </option>
                                        <option>Quezon</option>
                                        <option>Rizal</option>
                                        <option>Romblon</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-envelope fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact">nikonshop@support.com</text>
                                    <input type="email" class="input-detail" placeholder="Email Address..." value="nikonshop@support.com">
                                </td>
                            </tr>
                            <tr>
                                <td class="td-contact-icon"><i class="fa fa-globe fa-2x"></i></td>
                                <td class="td-contact-detail">
                                    <text class="text-contact"><a href="#">www.nikonshop.com.ph</a></text>
                                    <input type="text" class="input-detail" placeholder="Website..." value="www.nikonshop.com.ph">
                                </td>
                            </tr>
                            <tr >
                                <td colspan="2">
                                    <center>
                                        <input type="submit"  id="save-edit" class="btn btn-default-3" value="Save Changes" />
                                    </center>
                                </td>
                            </tr>
                        </table>
                        <br/>
                        <center>
                            <span class="span-social-media">
                                <a href="#">
                                    <img src="<?=base_url()?>assets/images/fb64.png" width="32" height="32" rel="tooltip" data-toggle="tooltip" title="Facebook" data-placement="bottom">
                                </a>
                                <script>
                                    $('[rel=tooltip]').tooltip() 
                               </script>
                            </span>
                            <span class="span-social-media">
                                <a href="#">
                                    <img src="<?=base_url()?>assets/images/twitter64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="Twitter" data-placement="bottom">
                                </a>
                                <script>
                                    $('[rel=tooltip]').tooltip() 
                               </script>
                            </span>
                            <span class="span-social-media">
                                <a href="#">
                                    <img src="<?=base_url()?>assets/images/googleplus64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="Google+" data-placement="bottom">
                                </a>
                                <script>
                                    $('[rel=tooltip]').tooltip() 
                               </script>
                            </span>
                            <span class="span-social-media">
                                <a href="#">
                                    <img src="<?=base_url()?>assets/images/linkedin64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="LinkedIn" data-placement="bottom">
                                </a>
                                <script>
                                    $('[rel=tooltip]').tooltip() 
                               </script>
                            </span>
                            <span class="span-social-media">
                                <a href="#">
                                    <img src="<?=base_url()?>assets/images/skype64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="Skype" data-placement="bottom">
                                </a>
                                <script>
                                    $('[rel=tooltip]').tooltip() 
                               </script>
                            </span>
                            <span class="span-social-media">
                                <a href="#">
                                    <img src="<?=base_url()?>assets/images/youtube64.png" width="32" height="32"  rel="tooltip" data-toggle="tooltip" title="YouTube" data-placement="bottom">
                                </a>
                                <script>
                                    $('[rel=tooltip]').tooltip() 
                               </script>
                            </span>
                        </center>
                    </div>
                </div>
            </div>
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

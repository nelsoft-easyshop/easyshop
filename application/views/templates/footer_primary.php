        <section class="footer-primary">
            <div class="container">
                <div class="col-xs-12 tex-center footer-ac">
                    <a href="">About Us</a>
                    <a href="">Contact Us</a>
                </div>
                <div class="row row-fluid">
                    <div class="col-md-6 footer-top-con">
                        <div class="row">
                            <div class="col-md-6 col-xs-6">
                                <h1>Help Center</h1>
                                <ul>
                                    <li><a href="/register">Registration</a></li>
                                    <li><a href="/guide/buy">How to Shop</a></li>
                                    <li><a href="/guide/sell">How to Sell</a></li>
                                    <li><a href="/faq">FAQ</a></li>
                                    <li><a href="/report">Report a Problem</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-xs-6">
                                <h1>Information</h1>
                                <ul>
                                    <li><a href="/cart">View Cart</a></li>
                                    <li><a href="/contact">Contact Us</a></li>
                                    <li><a href="/policy">Privacy Policy</a></li>
                                    <li><a href="/terms">Terms of Use</a></li>
                                </ul>
                            </div>

                            <div class="col-md-12 col-xs-12 app-img-con">
                                <h1>Download our App</h1>
                               
                                <a href="https://itunes.apple.com/us/app/easyshop.ph/id935195239?mt=8" class="app-img-apple" target="_blank" >
                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-app-apple.png" alt="apple app">
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=com.nelsoft.easyshop" target="_blank">
                                    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-app-google.png" alt="google app">
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 footer-newsletter">
                        <h1>Newsletter</h1>
                        <p>
                            Get the word out. Make sure you don't miss interesting events, sale, and more
                            by joining our newsletter program.
                        </p>
                        <div>
                            <?php echo form_open('/subscribe');?>
                                <input type="text" class="subscribe_email ui-form-control" name="email" placeholder="Newsletter">
                                <input type="submit" value="subscribe" class="subscribe_btn btn btn-default-4" name="subscribe_btn">
                            <?php echo form_close();?>
                        </div>

                        <h1>Follow Us</h1>
                        <div class="social-media-wrapper">
                            <a href="<?php echo $facebook; ?>">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img-facebook-new.png" alt="easyshop facebook">
                            </a>
                            <a href="<?php echo $twitter; ?>">
                                <img src="<?php echo getAssetsDomain(); ?>assets/images/img-twitter-new.png" alt="easyshop twitter">
                            </a>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </section>
        <section class="footer-bottom-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-left footer-payment-opt">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img-visa.png" alt="Visa">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img-pesopay.png" alt="Peso Pay">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img-mastercard.png" alt="Mastercard">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img-dragonpay.png" alt="Dragon Pay">
                        <img src="<?php echo getAssetsDomain(); ?>assets/images/img-cod.png" alt="COD">
                    </div>
                    <div class="col-md-6 text-right footer-hide">
                        <p>Copyright &copy; <?php echo date("Y"); ?> Easyshop.ph</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="res-copyright">
            <div>
                <p>Copyright &copy; <?php echo date("Y"); ?> Easyshop.ph</p>
            </div>
        </section>
    </body>
</html>


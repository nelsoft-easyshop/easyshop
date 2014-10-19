        <section class="footer-primary">
            <div class="container">
                <div class="row-fluid">
                    <div class="col-md-6">
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
                                    <li><a href="/about">About Us</a></li>
                                    <li><a href="/contact">Contact Us</a></li>
                                    <li><a href="/policy">Privacy Policy</a></li>
                                    <li><a href="/terms">Terms of Use</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h1>Newsletter</h1>
                        <p>
                            Get the word out. Make sure you don't miss interesting events, sale, and more
                            by joining our newsletter program.
                        </p>
                        <div>
                            <?php echo form_open('subscribe');?>
                                <input type="text" class="subscribe_email ui-form-control" name="email">
                                <input type="submit" value="subscribe" class="subscribe_btn btn btn-default-4" name="subscribe_btn">
                            <?php echo form_close();?>
                        </div>

                        <h1>Follow Us</h1>
                        <div class="social-media-wrapper">
                            <ul>
                                <li><a href="https://www.facebook.com/EasyShopPhilippines"><img src="/assets/images/img-facebook-new.png" alt="easyshop facebook"></a></li>
                                <li><a href="https://twitter.com/EasyShopPH"><img src="/assets/images/img-twitter-new.png" alt="easyshop twitter"></a></li>
                            </ul>
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
                    <div class="col-md-6 text-left">
                        <img src="assets/images/img-visa.png" alt="Visa">
                        <img src="assets/images/img-paypal.png" alt="Paypal">
                        <img src="assets/images/img-mastercard.png" alt="Mastercard">
                        <img src="assets/images/img-dragonpay.png" alt="Dragon Pay">
                        <img src="assets/images/img-cod.png" alt="COD">
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


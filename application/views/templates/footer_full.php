<footer>
      <div class="res_wrapper wrapper footer_panel">
        <div class="social_media col-xs-12 col-sm-4 col-md-4 pd-bttm-10">
          
            <h5>Stay in Touch</h5>
            <a href="<?php echo $facebook; ?>"> <span class="span_bg facebook"></span></a>
            <a href="<?php echo $twitter; ?>"><span class="span_bg twitter"></span></a>
         
            <div class="signup">
                    <h5>Signup for Newsletter</h5>
                    <?php echo form_open('subscribe');?>
                        <input type="text" class="subscribe_email" name="email">
                        <input type="submit" value="Submit" class="subscribe_btn" name="subscribe_btn">
                    <?php echo form_close();?>
            </div>
          
        </div>

        <div class="about_footer col-xs-12 col-sm-8 col-md-8">
          <div class="border-left pd-lr-20">
            <h5>About</h5>
            <ul>
              <li><a href="/contact">Contact Us</a></li>
              <li><a href="/policy">Privacy Policy</a></li>
              <li><a href="/terms">Terms &amp; Conditions</a></li>
              <li><a href="/faq">F.A.Q.</a></li>
            </ul>
            <div class="clear"></div>
            <div class="footer_payment">
              <h5>Payment</h5>
                <span class="span_bg mastercard"></span>
                <span class="span_bg visa"></span>
                <span class="span_bg paypal"></span>
                <span class="span_bg dragonpay"></span>
                <span class="span_bg cod"></span>
            </div> 
          </div>  
      </div>
      <div class="clear"></div>
      <div class="res_wrapper wrapper footer_panel about_us_panel">
          <p><strong class="orange f16">WE ARE SHOPPING MADE EASY!</strong></p>
          <p>
            From the latest trends in fashion, gadgets and accessories to whatever you need to make life EASY, EASYSHOP.PH is here to give you the widest range of choices at very competitive prices!
          </p>
          <p>
            Soon to be the leading online shopping website in South East Asia, EasyShop.ph gives you an online shopping experience that is <strong>FAST, SAFE, EASY and CONVENIENT!</strong> EasyShop.ph is a partner of Nelsoft Technology, Inc. and PoziHongkong Technology Ltd. Co.
          </p>
          <p>
            <strong>EASYSHOP.PH</strong> gives you the widest range of options available in the market. It's a place where Online Buyers and Sellers converge, while letting you enjoy the benefits of one-stop shopping in the comfort of your home.
          </p>
          <p>
            <strong>EASYSHOP.PH</strong> also provides you with up-to-date information from Online Sellers giving you a clear picture of the items you are eyeing, thus helping you make the right purchasing decision!
          </p>
          <p>
            Earning an income is EASY at <strong>EASYSHOP.PH</strong>. As the online shopping website of choice, it gives you the chance to offer, promote and sell your goods to millions of people across the Philippines.
          </p>
          <p>
            <strong class="orange f16">WELCOME TO EASYSHOP.PH and HAPPY SHOPPING!</strong>
          </p>
      </div>
      <div class="copyright">
           <p style='font-weight:bold'>This page is best viewed with one of these browsers:
                <span class="browser-lnk">
                  <a href='https://www.google.com/intl/en/chrome/browser/' target="_blank"><img src='<?php echo getAssetsDomain(); ?>assets/images/icon_browser_chrome.png'> <span>Chrome</span></a>
                  <a href='http://www.mozilla.org/en-US/firefox/new/' target="_blank"><img src='<?php echo getAssetsDomain(); ?>assets/images/icon_browser_firefox.png'> <span>Firefox</span></a>
              </span>
           </p>
          <p>Copyright &copy; <?php echo date("Y"); ?> Easyshop.ph</p>
      </div>
    </div>

    <input id="user-session" type="hidden" value="<?php echo $this->session->userdata('session_id');?>">

</footer>
</body>
</html>



        
        <div class="clear"></div>
        <footer>
                <div class="res_wrapper wrapper">
                
                <div class="copyright <?php echo isset($minborder) && $minborder ? 'copyright-min-border' : ''?>">
                    <p class='browser-disclaimer'>This page is best viewed with one of these browsers:
                        <a href='https://www.google.com/intl/en/chrome/browser/' target="_blank"><img src='<?php echo getAssetsDomain(); ?>assets/images/icon_browser_chrome.png' style='margin-left:2px; margin-top:3px;'/> <span style='position:absolute;'>Chrome</span></a>
                        <a href='http://www.mozilla.org/en-US/firefox/new/' target="_blank"><img src='<?php echo getAssetsDomain(); ?>assets/images/icon_browser_firefox.png' style='margin-left:50px; margin-top:3px;'/> <span style=' position:absolute; margin-left:2px;'>Firefox</span></a>
                    </p>
                    <p>Copyright &copy; <?php echo date("Y"); ?> Easyshop.ph</p>
                    </div>

                </div>

                <input id="user-session" type="hidden" value="<?php echo $this->session->userdata('session_id');?>">
        </footer>
        <div class="notification_container">
            <h2>Notification</h2>
            <p> EasyShop.ph is a platform where you can facilitate easy and safe online business transactions. We are offering you a free stage for your business, and ensuring that buyers have a scam-free and easy-shopping experience. EasyShop.ph is where shopping is made easy!
            </p>
        </div>
    </body>
</html>





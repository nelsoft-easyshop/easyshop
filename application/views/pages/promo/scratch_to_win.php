<link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/promo.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />

<div class="clear"></div>

    <?PHP if (isset($product)) : ?>
<div class="promo-wrapper margin-top-10" id="main_search_container">
    <h2 class="head-cngrts">CONGRATULATIONS!</h2>
    <div class="scratch-congrats">
        <span id="checker" data_id="<?=$product[0]['id_product']?>" data_name="<? echo html_escape($product[0]['name'])?>"
              data_price="<?=$product[0]['price']?>" data_code="<?PHP echo $_GET['code'];?>">
        </span>
        <div class="claim-bg">
            <div id="prod_image">
                <img src="<?PHP echo base_url() . $product[0]['path']?>">
            </div>
            <div class="claim-details">
                <p>To claim your price, complete the registration form and visit<br>
                Easyshop.ph's office at 8th flr. Marc 2000 Tower, 1973 Taft Avenue,
                Malate, Manila<br>
                Don't forget to bring the winning scratch card AND two (2) valid ID's<br>
                You may claim your prize until December 31,2014.<br>
                Contact us for more information: (02) 353-0062 or (02)353-8337.</p>
            </div>
        </div>
    </div>
    <?PHP else: ?>
    <div class="promo-wrapper" id="main_search_container">
        <?php echo $deals_banner; ?>
        <div id="scratch-win">
            <div class="scratch-win-form">
                <span>Enter your code here: </span>

                <input type="text" id="scratch_txt" >

                <button id="send_btn" class="scratch-win-btn">ENTER</button>
                <div class="bottom-border"></div>
                <h3>Mechanics: </h3>
                <ol>
                    <li>Participants must obtain a scratch card distributed in the following areas: Manila, Makati, and Ortigas from September 1-December 31, 2014 for a chance to win HOT items from EasyShop.ph.</li>
                    <li>Participants must scratch the card to reveal a special code.</li>
                    <li>Participants should visit www.easyshop.ph/scratchcard to enter the code. They automatically get an item for FREE if they have the winning code.</li>
                    <li>Participants need to click on “CLAIM ITEM” and fill out the registration form or login to their accounts.</li>
                    <li>To claim the prizes, participants must present their winning scratch card and bring 2 valid IDs at the EasyShop Main Office located at 8th flr. Marc 2000 Tower, 1973 Taft Ave., Malate, Manila.</li>
                    <li>Redemption of prizes is until December 31, 2014.</li>
                    <li>For more details, visit the EasyShop website at www.easyshop.ph or call customer service at (02) 354-5973.</li>
                </ol>
                <br>
                <h3>Terms and Conditions: </h3>
                <ol>
                    <li>The contest is open to all individuals aged 21 years and above.</li>
                    <li>By registering to EasyShop.ph, individuals agree, warrant, and represent that all personal information provided is true, correct, and complete.</li>
                    <li>By participating in this promo, the participant voluntarily provides information that may be used for market research.</li>
                    <li>Prizes given are not convertible to cash.</li>
                    <li>The scratch card cannot be used in conjunction with other on-going promotions unless specifically stated in the scratch card mechanics.</li>
                    <li>EasyShop.ph reserves the right to amend the terms and conditions without prior notice.</li>
                    <li>Only residents of the Republic of the Philippines are eligible to participate in this promotion.</li>
                </ol>
                <br>
                <p>
                    Visit <a href="<?=base_url()?>">www.Easyshop.ph</a> for more details or you may <br>
                    call our customer service at (02)353-0062 or <br>
                    (02)353-8337.
                </p>
            </div>

            <div id="scratch-win-error">
                <h2>Sorry</h2>

                <p>
                    Your code did not match the item's code. Try your luck again with another scratch card.
                </p>

                <p>
                    You can also register at Easyshop.ph or follow us on Facebook and be updated of future promotions
                    <br>
                    Feel free to contact us for more information: (02)353-0062 or (02)353-8337.
                </p>
            </div>
            <div id="scratch-win-claim">
                <h2>Congratulations!</h2>

                <div class="claim-bg">
                    <div id="prod_image">
                        <img src="/assets/product/3_2_20140820/3_2_201408201818000.png">
                    </div>
                    <div class="claim-details">
                        <h3></h3>
                        <p>
                        </p>
                        <a id="scratch-win-claim-link" href="">CLAIM ITEM</a>
                    </div>
                </div>
            </div>
        </div>
    <?PHP endif; ?>
</div>

<script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script src="/assets/js/src/scratchwinpromo.js" type="text/javascript"></script>

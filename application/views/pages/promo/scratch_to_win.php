<link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="/assets/css/promo.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link type="text/css" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />

<div class="clear"></div>

    <?PHP if (isset($product)) : ?>
<div class="promo-wrapper margin-top-10" id="main_search_container">
    <h2 class="head-cngrts">CONGRATULATIONS!</h2>
    <div class="scratch-congrats">
        <span id="checker" data_id="<?=$product[0]['id_product']?>" data_name="<?=$product[0]['name']?>"
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
                <h3>Mechanics</h3>
                <ol>
                    <li>Scratch the card.</li>
                    <li>Got to easyshop.ph and click the Scratch Card link.</li>
                    <li>If your code matches one of the items, click on the item and fill out the registration form.</li>
                </ol>
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
                        <img src="<?PHP echo base_url()?>./assets/product/3_2_20140820/3_2_201408201818000.png">
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


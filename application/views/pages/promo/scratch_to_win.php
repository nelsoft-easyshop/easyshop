
<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/jquery.bxslider.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="all"/>
    <link rel="stylesheet" href="/assets/css/promo.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="all"/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href='/assets/css/min-easyshop.scratch-to-win.css?ver=<?=ES_FILE_VERSION?>' media='all'/>
<?php endif; ?>

<section class="bg-cl-fff">
<div class="clear"></div>
<?PHP if (isset($product)) : ?>
<div class="container" id="main_search_container">
    <?PHP if (isset($product['can_purchase']) && $product['can_purchase'] === FALSE) : ?>
    <div class="scratch-congrats">
        <h2 class="head-cngrts">SORRY</h2>
        <p>
            Unfortunately, you can only win once in the Scratch & Win Promo and this account has already won an item before.
        </p>
        <div class="claim-bg ">
            <div id="prod_image">
                <img src="<?php echo getAssetsDomain(); ?><?=$product['product_image_path']?>">
            </div>
            <div class="claim-details">
                <p>To claim your prize,
                    visit Easyshop.ph's office at 8th flr. Marc 2000 Tower, 1973 Taft Avenue,
                    Malate, Manila
                    Don't forget to bring the winning scratch card AND two (2) valid ID's
                    You may claim your prize until March 1, 2015.
                    Contact us for more information: (02) 353-0062 or (02)353-8337.
                </p>
            </div>
        </div>
        <p>This is to ensure that everyone gets an equal opportunity in winning an item.</p>
        <p>
            You can also register at <a href="/">Easyshop.ph</a> or follow us on <a href="<?php echo $facebook; ?>">Facebook</a> and be updated of future promotions
            <br>
            Feel free to contact us for more information: (02)353-0062 or (02)353-8337.
        </p>
    </div>
    <?PHP elseif($product == 'purchase-limit-error') : ?>
        <h2 class="head-cngrts">SORRY!</h2>
        <div class="scratch-congrats">
            <div class="claim-bg">
                <div class="claim-details">
                    <p>
                        Code has already been used.
                    </p>
                </div>
            </div>
        </div>
    <?PHP else : ?>
    <h2 class="head-cngrts">CONGRATULATIONS!</h2>
    <div class="scratch-congrats">
        <span id="checker" data_id="<?=$product['id_product']?>" data_name="<?= html_escape($product['product']); ?>"
                data_price="<?=$product['price']?>" data_code="<?=$code;?>" run_js="<?=$user->getFullName() ? TRUE : FALSE ?>">
        </span>
        <div class="claim-bg">
            <div class="row">
                <div id="prod_image" class="col-xs-12 col-sm-4">
                    <img src="<?php echo getAssetsDomain(); ?><?=$product['product_image_path']?>">
                </div>
                <div class="claim-details col-xs-12 col-sm-8">
                    <p>To claim your prize,  <?= html_escape($user->getFullName()) ?'': '<span id="complete">complete the <a href="javascript:void(0)" id="register">registration</a> form and </span>'?>visit
                        Easyshop.ph's office at 8th flr. Marc 2000 Tower, 1973 Taft Avenue,
                        Malate, Manila
                        Don't forget to print this page and bring the
                        winning scratch card and two (2) valid ID's
                        You may claim your prize until March 1, 2015.
                        Contact us for more information: (02) 353-0062 or (02)353-8337.
                    </p>
                    <a class="promo-print" href="javascript:void(0)" onClick="window.print()">PRINT</a>
                </div>
                <div class="clear"></div>
            </div>
            <div id="div-promo-modal">
                <div class="div-promo-modal-container form-group">
                    <label for="fullname" class="label-fullname">Complete name:</label>
                    <input name="fullname" type="text"  class="form-control" id="promo-fullname">
                </div>
                <br/>
                <span class="modalCloseImg simplemodal-close btn btn-default-1" id="mdl-cancel">Cancel</span>
                <button class="btn btn-default-3" id="apply-fullname">Apply</button>
                <img class="promoimgloader" src="<?php echo getAssetsDomain(); ?>assets/images/loading/preloader-whiteBG-small.gif">
            </div>
        </div>
        <input type="text" id="paymentToken" value="<?php echo md5(uniqid(mt_rand(), true)).'3';?>" style="display: none">
    </div>
    <div class="promo-gallery">
        <div class="header-galore33">
            <a href="/gadgetsgalore">YOU MAY ALSO LIKE GADGETS GALORE</a>
        </div>
        <div class="promo-gallery-row">
            <?PHP foreach($gadgets_galore as $product) : ?>
            <div class="promo-gallery-data">
                <div class="img-main-container">
                    <div class="img-container">
                        <img class="promo-img" src="<?php echo getAssetsDomain().$product['product_image_path']?>">
                    </div>
                </div>
                <p><?=html_escape($product['product_name'])?></p>
                <span>Php <?= number_format($product['price'], 2, '.', ',')?></span>
                <br>
                <a href="/item/<?=html_escape($product['slug'])?>"><img src="<?php echo getAssetsDomain()?>assets/images/img_cart2.jpg"> &nbsp; SHOP NOW</a>
            </div>
            <?PHP endforeach; ?>
        </div>
    </div>
    <?PHP endif; ?>
</div>
<?PHP else: ?>
    <div class="container" id="main_search_container">
        <?php echo $deals_banner; ?>
        <div id="scratch-win">
            <div class="scratch-win-form">
                <span>Enter your code here: </span>
                <form id="code-form">
                    <div class="form-group" id="scratch-txt-div">
                        <input type="text"  class="form-control"  id="scratch_txt" >
                        <button class="btn btn-primary">ENTER</button>
                    <div>
                </form>
                <div class="bottom-border"></div>
                
                <div class='instructions'>
                    <h3>How to join: </h3>
                    <ol>
                        <li>Participants must obtain a scratch card distributed in the following areas: Manila, Makati, and Ortigas from September 1-December 31, 2014 for a chance to win HOT items from EasyShop.ph.</li>
                        <li>Participants must scratch the card to reveal a special code.</li>
                        <li>Participants should visit <a href="/Scratch-And-Win">https://easyshop.ph/ScratchAndWin</a> to enter the code. They automatically get an item for FREE if they have the winning code.</li>
                        <li>Participants need to click on “CLAIM ITEM” and fill out the registration form or login to their accounts.</li>
                        <li>To be eligible to join the promo, participants must complete their registration by providing their complete name, email address, and contact number on their EasyShop.ph profile page. Participants with incomplete profile details will not be qualified to win any item.</li>
                        <h3>Prizes: </h3>
                            <table id="promo-prizes-tbl">
                                <thead>
                                    <tr>
                                        <th>MINOR PRIZES</th>
                                        <th>QUANTITY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EasyShop T-shirts</td>
                                        <td>100</td>
                                    </tr>
                                    <tr>
                                        <td>EasyShop Umbrella</td>
                                        <td>100</td>
                                    </tr>
                                    <tr>
                                        <td>EasyShop Planner</td>
                                        <td>100</td>
                                    </tr>
                                </tbody>
                                <thead>
                                    <tr>
                                        <th>MAJOR PRIZES</th>
                                        <th>QUANTITY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Yoobao 10400mAh Magic Cube II Power Bank (Silver)</td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>Western Digital Portable HDD 1TB</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>Xiaomi Mi3</td>
                                        <td>2</td>
                                    </tr>
                                    <tr>
                                        <td>iPad mini with Retina display 16GB Wi-Fi Only</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                                <thead>
                                <tr>
                                    <th class="tbl-red">TOTAL NUMBER OF WINNERS</th>
                                    <th class="tbl-red">307</th>
                                </tr>
                                </thead>
                            </table>
                        <li>To claim the prizes, participants must present their winning scratch card and bring 2 valid IDs at the EasyShop Main Office located at 8th flr. Marc 2000 Tower, 1973 Taft Ave., Malate, Manila.</li>
                        <li>Redemption of prizes is until March 1, 2015.</li>
                        <li>For more details, visit the EasyShop website at <a href="/">https://easyshop.ph</a> or call customer service at (02) 354-5973.</li>
                    </ol>
                    <br>
                    <h3>Terms and Conditions: </h3>
                    <ol>
                        <li>The contest is open to all individuals aged 21 years and above.</li>
                        <li>By registering to EasyShop.ph, individuals agree, warrant, and represent that all personal information provided is true, correct, and complete.</li>
                        <li>By participating in this promo, the participant voluntarily provides information that may be used for market research.</li>
                        <li>A participant can win only once.</li>
                        <li>Prizes given are not convertible to cash.</li>
                        <li>The scratch card cannot be used in conjunction with other on-going promotions unless specifically stated in the scratch card mechanics.</li>
                        <li>Employees of EasyShop Online Inc. and Click to Print High Street Corporation including their relatives up to second degree of consanguinity or affinity are disqualified from joining the promotion.</li>
                        <li>Only residents of the Republic of the Philippines are eligible to participate in this promotion.</li>
                    </ol>
                </div>
                <br>
            </div>
            <div class="scratch-win-error error">
                <h2>Sorry</h2>
                <p>
                    Your code did not match any of the promo's valid codes. Try your luck with another scratch card.
                </p>
                <p>
                    You can also register at <a href="/">Easyshop.ph</a> or follow us on <a href="<?php echo $facebook; ?>">Facebook</a> and be updated of future promotions
                    <br>
                    Feel free to contact us for more information: (02)353-0062 or (02)353-8337.
                </p>
            </div>
            <div class="scratch-win-error purchase-limit-error">
                <h2>Sorry</h2>
                <p>
                    Unfortunately, you can only win once in the Scratch & Win Promo and this account has already won an item before.
                </p>
                <div class="claim-bg error-claim">
                    <div id="prod_image">
                        <img src="<?php echo getAssetsDomain() ?>">
                    </div>
                    <div class="claim-details">
                        <p>To claim your prize, complete the <a href="/">registration</a> form and visit<br>
                            Easyshop.ph's office at 8th flr. Marc 2000 Tower, 1973 Taft Avenue,
                            Malate, Manila<br>
                            Don't forget to print this page and bring the <br>
                            winning scratch card and two (2) valid ID's<br>
                            You may claim your prize until March 1, 2015.<br>
                            Contact us for more information: (02) 353-0062 or (02)353-8337.
                        </p>
                        <h3></h3>
                        <p class="prod-description"></p>
                    </div>
                </div>
                <p>This is to ensure that everyone gets an equal opportunity in winning an item.</p>
                <p>
                    You can also register at <a href="/">Easyshop.ph</a> or follow us on <a href="<?php echo $facebook; ?>">Facebook</a> and be updated of future promotions
                    <br>
                    Feel free to contact us for more information: (02)353-0062 or (02)353-8337.
                </p>
            </div>
            <div class="scratch-win-error winning-error">
                <h2>Sorry</h2>
                <p>
                    This code has already been claimed. Try your luck again with another scratch card.
                </p>
                <p>
                    You can also register at <a href="/">Easyshop.ph</a> or follow us on <a href="<?php echo $facebook; ?>">Facebook</a> and be updated of future promotions
                    <br>
                    Feel free to contact us for more information: (02)353-0062 or (02)353-8337.
                </p>
            </div>
            <div id="scratch-win-claim" class="container">
                <h2>Congratulations!</h2>
                <div class="claim-bg row">
                    <div id="prod_image" class="col-sm-12 col-md-4">
                        <img src="<?php echo getAssetsDomain() ?>">
                    </div>
                    <div class="claim-details col-sm-12 col-md-8">
                        <h3></h3>
                        <p class="prod-description">
                        </p>
                        <a id="scratch-win-claim-link" href="">CLAIM ITEM</a>
                    </div>
                </div>
            </div>
        </div>
        <?PHP endif; ?>
    </div>
</div>
</div>
</section>
    <?php if(strtolower(ENVIRONMENT) === 'development'): ?>
        <script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
        <script src="/assets/js/src/vendor/jquery.simplemodal.js" type="text/javascript"></script>
        <script src="/assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
        <script src="/assets/js/src/promo/scratchwinpromo.js" type="text/javascript"></script>
    <?php else: ?>
        <script src="/assets/js/min/easyshop.scratch_to_win.js?ver=<?php echo ES_FILE_VERSION ?>" type="text/javascript"></script>
    <?php endif; ?>


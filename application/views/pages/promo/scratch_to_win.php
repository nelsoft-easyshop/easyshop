<<<<<<< HEAD
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/promo.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link type="text/css" href="<?=base_url()?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<div class="clear"></div>

<div class="promo-wrapper" id="main_search_container">
    <?PHP if (isset($product)) : ?>
    <h2 class="head-cngrts">CONGRATULATION</h2>
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
        <?php echo $deals_banner; ?>
        <div id="scratch-win">
            <div class="scratch-win-form">
                <span>Enter your code here: </span>

                <input type="text" id="scratch_txt" value="3217hdsgka2sdka">

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

<script src="<?= base_url() ?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script>
    (function ($) {
        var code = $('#scratch_txt');
        $(document).ready(function () {
            $('#scratch-win-error , #scratch-win-claim').hide();
            var base_url = config.base_url;
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var  img = '';
            $(document).on('click', '#send_btn', function () {
                $("#prod_image img").attr('src', '');
                $(".claim-details h3").html('');
                $(".claim-details p").html('');
                $('#scratch-win-error, #scratch-win-claim, .scratch-win-form > h3, .scratch-win-form > ol').hide();
                if (code.val().trim() == "") {
                    alert('Invalid code');

                    return false;
                }

                $.ajax({
                    url: 'promo/validateScratchCardCode',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        csrfname: csrftoken,
                        code: code.val().trim()
                    },
                    success: function (data) {
                        if (data.id_product) {
                            $('#claim_item').attr('data-code', code.val().trim());
                            $('#scratch-win-claim').slideDown();
                            $(".claim-details h3").html(data.name);
                            $(".claim-details p").html(data.brief);
                            $("#prod_image img").attr('src', 'https://local.easyshop/' + data.path);
                            if(data.logged_in){
                                $('#scratch-win-claim-link').attr('href', '/promo/claim?code=' + code.val().trim());
                            }
                            else{
                                $('#scratch-win-claim-link').attr('href', '/promo/claim/' + code.val().trim());
                            }
                        }
                        else {
                            $('#scratch-win-claim-link').attr('href', '');
                            $('#scratch-win-error').slideDown().show();
                        }
                    }
                });
            });

            if($('#checker').length){
                var i_id = $("#checker").attr("data_id");
                var i_name = $("#checker").attr("data_name");
                var i_qty =  1;
                var i_price = $("#checker").attr("data_price");
                var i_code = $("#checker").attr("data_code");
                var i_opt = {};
                var length = 0;
                var csrftoken = $("meta[name='csrf-token']").attr('content');
                var max_qty = 1;
                $.ajax({
                    async:false,
                    url: config.base_url + "cart/add_item",
                    type:"POST",
                    dataType:"JSON",
                    data:{
                        id: i_id,
                        qty: i_qty,
                        price: i_price,
                        opt: i_opt,
                        name: i_name,
                        length: length,
                        max_qty: max_qty,
                        promo_code: i_code,
                        csrfname:csrftoken
                    },
                    success:function(data){
                        if(data == "386f25bdf171542e69262bf316a8981d0ca571b8" ){
                            alert("An error occured,Try refreshing the site.");
                        }
                    }

                });
            }

        })
    })(jQuery)
</script>

=======
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/promo.css?ver=<?= ES_FILE_VERSION ?>" type="text/css" media="screen"/>
<link type="text/css" href="<?=base_url()?>assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<div class="clear"></div>

<div class="promo-wrapper" id="main_search_container">
    <?PHP if (isset($product)) : ?>
    <h2 class="head-cngrts">CONGRATULATION</h2>
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
        <?php echo $deals_banner; ?>
        <div id="scratch-win">
            <div class="scratch-win-form">
                <span>Enter your code here: </span>

                <input type="text" id="scratch_txt" value="3217hdsgka2sdka">

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

<script src="<?= base_url() ?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script>
    (function ($) {
        var code = $('#scratch_txt');
        $(document).ready(function () {
            $('#scratch-win-error , #scratch-win-claim').hide();
            var base_url = config.base_url;
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');
            var  img = '';
            $(document).on('click', '#send_btn', function () {
                $("#prod_image img").attr('src', '');
                $(".claim-details h3").html('');
                $(".claim-details p").html('');
                $('#scratch-win-error, #scratch-win-claim, .scratch-win-form > h3, .scratch-win-form > ol').hide();
                if (code.val().trim() == "") {
                    alert('Invalid code');

                    return false;
                }

                $.ajax({
                    url: 'promo/validateScratchCardCode',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        csrfname: csrftoken,
                        code: code.val().trim()
                    },
                    success: function (data) {
                        if (data.id_product) {
                            $('#claim_item').attr('data-code', code.val().trim());
                            $('#scratch-win-claim').slideDown();
                            $(".claim-details h3").html(data.name);
                            $(".claim-details p").html(data.brief);
                            $("#prod_image img").attr('src', 'https://local.easyshop/' + data.path);
                            if(data.logged_in){
                                $('#scratch-win-claim-link').attr('href', '/promo/claim?code=' + code.val().trim());
                            }
                            else{
                                $('#scratch-win-claim-link').attr('href', '/promo/claim/' + code.val().trim());
                            }
                        }
                        else {
                            $('#scratch-win-claim-link').attr('href', '');
                            $('#scratch-win-error').slideDown().show();
                        }
                    }
                });
            });

            if($('#checker').length){
                var i_id = $("#checker").attr("data_id");
                var i_name = $("#checker").attr("data_name");
                var i_qty =  1;
                var i_price = $("#checker").attr("data_price");
                var i_code = $("#checker").attr("data_code");
                var i_opt = {};
                var length = 0;
                var csrftoken = $("meta[name='csrf-token']").attr('content');
                var max_qty = 1;
                $.ajax({
                    async:false,
                    url: config.base_url + "cart/add_item",
                    type:"POST",
                    dataType:"JSON",
                    data:{
                        id: i_id,
                        qty: i_qty,
                        price: i_price,
                        opt: i_opt,
                        name: i_name,
                        length: length,
                        max_qty: max_qty,
                        promo_code: i_code,
                        csrfname:csrftoken
                    },
                    success:function(data){
                        if(data == "386f25bdf171542e69262bf316a8981d0ca571b8" ){
                            alert("An error occured,Try refreshing the site.");
                        }
                    }

                });
            }

        })
    })(jQuery)
</script>
>>>>>>> issue-269

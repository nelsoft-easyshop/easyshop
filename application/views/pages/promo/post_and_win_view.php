<link rel="stylesheet" href="/assets/css/style.css" type="text/css" media="screen"/>
<div class="wrapper info_wrapper">
    <img src="<?php echo getAssetsDomain(); ?>assets/images/img-triple-treat.png" alt="Triple Treats">
</div>

<div class="wrapper mrgntop-30">
    <div class="deals_header_con bg_lt_grey">
    </div>
    <div class="pd-all-30 brdr2 brdr_clrd_lt_grey">
        <div>
            <input type="text" class="pd-8-12 width-300" id="txt_status">
            <input type="button" value="Status" class="orange_btn3" id="btn_status_check">
            <img src="<?php echo getAssetsDomain(); ?>assets/images/orange_loader_small.gif" id="loading_img" class="login_loading_img" style="display:none"/>
        </div>
        <div class="status">
            <div class="qualified border_radius1">
                <div class="img_status_con">
                    <span class="span_bg img_check"></span>
                </div>
                <p>Qualified</p>
            </div>
            <div class="notqualified border_radius1">
                <div class="img_status_con">
                    <span class="span_bg img_cross"></span>
                </div>
                <p>Not Qualified</p>
            </div>
            <div class="pending border_radius1">
                <div class="img_status_con">
                    <span class="span_bg img_pending"></span>
                </div>
                <p>Pending</p>
            </div>
        </div>
        <div>
            <div class="mrgntop-10 notf">
                <span class="span_bg "  id="notf_user"></span>REGISTERED USER
            </div>
            <div class="mrgntop-10 notf">
                <span class="span_bg " id="notf_fb"></span>LIKE US ON FB
            </div>
            <div class="mrgntop-10 notf">
                <span class="span_bg " id="notf_itm"></span>UPLOAD THREE ITEMS (JULY 16 - 30)
            </div>
        </div>

        <div class="mrgntop-10 content_wrapper">
            <h3 class="htitle2">Mechanics and Prizes</h3>
            <ul>
                <li>
                    Like us on 
                    <a href="<?php echo $facebook; ?>" class="blue">Facebook</a>
                </li>
                <li>Upload three items at <a href="/" class="blue">EasyShop.ph</a></li>
                <li>Leave your EasyShop.ph username in the comment box</li>
            </ul>
            <p class="pd-top-30">
                Winners will be chosen through a raffle draw. There will be <b class="f14">ONE (1)</b> winner for each of the following prizes:
            </p>
            <ul>
                <li>iPhone 5s 16GB (Gold)</li>
                <li>Xiaomi Mi 3 16GB (Metallic Gray)</li>
                <li>Yoobao Powerbank (20400 mAH)</li>
            </ul>

            <h3 class="pd-top-30">Restrictions</h3>
                <ul>
                    <li>Winners can only win ONCE.</li>
                    <li>Open to all existing and potential online sellers.</li>
                    <li>Only residents of the Republic of the Philippines with a valid mailing address are eligible.</li>
                </ul>

                <h3 class="pd-top-30">Contest Period</h3>
                    <p>
                        July 16, 2014 (Wednesday) to July 30, 2014 (Wednesday). Winners will be announced on July 31, 2014 (Thursday).
                    </p>

                    <h3 class="pd-top-30">Prize Redemption</h3>
                        <p>
                            All winners will be required to pick up their prize at EasyShop.ph office located at Unit 8C Marc 2000 Tower 1973 Taft Avenue, Malate Manila 1004.
                        </p>
                        <p>
                            If in case the winner cannot come physically, s/he has to send a representative with the following: authorization letter, copy of the winner's valid ID (photocopy or photo of the ID will be accepted) and ID of the authorized representative.
                        </p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var txt_status = $('#txt_status');
        var btn_status = $("#btn_status_check");
        var base_url = '/';
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var notf_user = $('#notf_user');
        var notf_fb = $('#notf_fb');
        var notf_itm = $('#notf_itm');
        $('.border_radius1 , .notf').hide();
        btn_status.on('click',function(){
            if(!txt_status.val()){
                alert('Invalid username');
                return false;
            }
            $.ajax({
                url: base_url + 'product/PromoStatusCheck',
                dataType : 'JSON',
                type : 'POST',
                async : false,
                data : {'username':txt_status.val(),csrfname : csrftoken},
                beforeSend : function(){
                    $("#loading_img").show();
                    $("#btn_status_check ,.border_radius1 , .notf").hide();},
                success : function(result){
                    $('.notf , #btn_status_check').show();
                    $("#loading_img").hide();
                    notf_user.removeClass('chk_img icon_cancel_red');
                    notf_fb.removeClass('chk_img icon_cancel_red');
                    notf_itm.removeClass('chk_img icon_cancel_red');
                    if(result === 1){
                        $('.qualified').show();
                        notf_user.addClass('chk_img');
                        notf_fb.addClass('chk_img');
                        notf_itm.addClass('chk_img');
                    }else if(result === 0){
                        $('.pending').show();
                        notf_user.addClass('chk_img');
                        notf_fb.addClass('icon_cancel_red');
                        notf_itm.addClass('icon_cancel_red');
                    }else{
                        $('.notqualified').show();
                        notf_user.addClass('icon_cancel_red');
                        notf_fb.addClass('icon_cancel_red');
                        notf_itm.addClass('icon_cancel_red');
                    }
                }
            });
        });

    });
</script>
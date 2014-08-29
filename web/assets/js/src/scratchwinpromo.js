(function ($) {
    var code = $('#scratch_txt');
    var paymentToken = "<?php echo md5(uniqid(mt_rand(), true)).'3';?>";
    $(document).ready(function () {
        $('#scratch-win-error , #scratch-win-claim').hide();
        var base_url = config.base_url;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var  img = '';
        $(document).on('click', '#send_btn', function () {
            if (code.val().trim() == "") {
                alert('Invalid code');

                return false;
            }
            $("#prod_image img").attr('src', '');
            $(".claim-details h3").html('');
            $(".claim-details p").html('');
            $('#scratch-win-error, #scratch-win-claim, .scratch-win-form > h3, .scratch-win-form > ol').hide();

            $.ajax({
                url: '/promo/ScratchCard/validateScratchCardCode',
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
                            $('#scratch-win-claim-link').attr('href', '/promo/ScratchCard/claimScratchCardPrize?code=' + code.val().trim());
                        }
                        else{
                            $('#scratch-win-claim-link').attr('href', '/promo/ScratchCard/claimScratchCardPrize/claim/' + code.val().trim());
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
                url: "/cart/add_item",
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
            $.ajax({
                async:false,
                url: "/payment/payCashOnDelivery",
                type:"POST",
                dataType:"JSON",
                data:{
                    csrfname:csrftoken,
                    promo_type:5,
                    paymentToken:paymentToken
                },
                success:function(data){
                    if(data == "386f25bdf171542e69262bf316a8981d0ca571b8" ){
                        alert("An error occured,Try refreshing the site.");
                    }
                }
            });
            $.ajax({
                async:false,
                url: "/promo/ScratchCard/tieUpMemberToCode",
                type:"POST",
                dataType:"JSON",
                data:{
                    csrfname:csrftoken,
                    code:$("#checker").attr("data_code")
                },
                success:function(data){
                    if(data == false){
                        alert("An error occured,Try refreshing the site.");
                    }
                }
            });
        }

    })
})(jQuery)
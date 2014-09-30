(function ($) {
    var code = $('#scratch_txt');
    var paymentToken = $('#paymentToken').val();
    $(document).ready(function () {
        $('#scratch-win-claim').hide();
        var base_url = config.base_url;
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var img = '';
        $(document).on('click', '#send_btn', function () {
            if (code.val().trim() == "") {
                alert('Invalid code');
                return false;
            }
            $("#prod_image img").attr('src', '');
            $(".claim-details h3").html('');
            $(".claim-details p").html('');
            $('.scratch-win-error, #scratch-win-claim, .scratch-win-form > h3, .scratch-win-form > ol, .scratch-win-form > p').hide();
            $.ajax({
                url: '/promo/ScratchCard/validateScratchCardCode',
                type: 'POST',
                dataType: 'json',
                data: {
                    csrfname: csrftoken,
                    code: code.val().trim()
                },
                success: function (data) {
                    if (data.product) {
                        $('#claim_item').attr('data-code', code.val().trim());
                        $(".claim-details h3").html(data.name);
                        $(".claim-details p").html(data.brief);
                        $("#prod_image img").attr('src', data.product_image_path);
                        if(!data.can_purchase) {
                            $('.purchase-limit-error').slideDown().show();
                        }
                        else if (parseInt(data.c_id_code) !== 0) {
                            $('.winning-error').slideDown().show();
                        }
                        else if (!data.logged_in) {
                            $('#scratch-win-claim').slideDown();
                            $('#scratch-win-claim-link').attr('href', '/promo/ScratchCard/claimScratchCardPrize/claim/' + code.val().trim());
                        }
                        else {
                            $('#scratch-win-claim').slideDown();
                            $('#scratch-win-claim-link').attr('href', '/promo/ScratchCard/claimScratchCardPrize?code=' + code.val().trim());
                        }
                    }
                    else {
                        $('#scratch-win-claim-link').attr('href', '');
                        $('.error').slideDown().show();
                    }
                }
            });
        });
        $(document).on('click', '#register', function(){
            $('#div-promo-modal').modal({
                escClose: false,
                containerCss:{
                    maxWidth: 300,
                    minWidth: 290,
                    maxHeight: 220
                }
            });
        });
        $(document).on('click', '#apply-fullname', function(){
            var fullname = $('#promo-fullname').val().trim();
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            if(fullname === ""){
                alert('Invalid name');
                return false;
            }
            $.ajax({
                url : '/promo/ScratchCard/updateFulname',
                dataType : 'json',
                type: 'POST',
                data: {fullname:fullname, csrfname:csrftoken},
                success: function(result){
                    if(result === true){
                        $.modal.close();
                        alert('Registration complete.');
                    }else{
                        alert('Something went wrong, try again.');
                    }
                }
            });
        });
        if($('#checker').length){
            var i_id = $("#checker").attr("data_id");
            var i_name = $("#checker").attr("data_name");
            var i_qty = 1;
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
                    if(data === false ){
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

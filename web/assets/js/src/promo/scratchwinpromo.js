(function ($) {
    var code = $('#scratch_txt');
    var paymentToken = $('#paymentToken').val();
    $(document).ready(function () {
        $('#scratch-win-claim').hide();
        var base_url = '/';
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        var img = '';
        

        $('#code-form').submit(function(event){
            event.preventDefault();
            var codeValue = code.val().trim();
            if (codeValue == "") {
               $('#scratch-txt-div').addClass('has-error');
                return false;
            }
            $("#prod_image img").attr('src', '');
            $(".claim-details h3").html('');
            $(".claim-details p").html('');
            $('.scratch-win-error, #scratch-win-claim, .scratch-win-form .instructions, .scratch-win-form > p').hide();
            $.ajax({
                url: '/promo/ScratchCard/validateScratchCardCode',
                type: 'POST',
                dataType: 'json',
                data: {
                    csrfname: csrftoken,
                    code: codeValue
                },
                success: function (data) {
                    if (data.product) {
                        $('#claim_item').attr('data-code', codeValue);
                        $(".claim-details h3").html(escapeHtml(data.product));
                        $(".claim-details .prod-description").html(escapeHtml(data.brief));
                        $("#prod_image img").attr('src', config.assetsDomain + data.product_image_path);
                        if (!data.logged_in) {
                            $('#scratch-win-claim').slideDown();
                            $('#scratch-win-claim-link').attr('href', '/promo/ScratchCard/claimScratchCardPrize/claim/' + code.val().trim());
                        }
                        else if(!data.can_purchase) {
                            $('.purchase-limit-error').slideDown().show();
                        }
                        else if (parseInt(data.c_id_code) !== 0) {
                            $('.winning-error').slideDown().show();
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

        $('#scratch_txt').change(function(){
            $('#scratch-txt-div').removeClass('has-error');
        });
        
        $(document).on('click', '#register', function(){
            $('#div-promo-modal').modal({
                escClose: false,
                containerCss:{
                    maxWidth: 600,
                    minWidth: 290,
                    maxHeight: 220
                }
            });
            $('.promoimgloader').hide();
        });
        $(document).on('click', '#apply-fullname', function(){
            var fullname = $('#promo-fullname').val().trim();
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            if(fullname === ""){
                alert('Invalid name');
                return false;
            }
            $('.promoimgloader').show();
            $('#apply-fullname').hide();
            $('#mdl-cancel').hide();
            $.ajax({
                url : '/promo/ScratchCard/updateFullname',
                dataType : 'json',
                type: 'POST',
                data: {fullname:fullname, csrfname:csrftoken},
                success: function(result){
                    if(result === true){
                        success();
                        $('#complete').html('');
                    }else{
                        alert('Something went wrong, try again.');
                    }
                }
            });
        });
        var success = function() {
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
                url: "/cart/doAddItem",
                type:"POST",
                dataType:"JSON",
                data:{
                    productId: i_id,
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
                    $('.promoimgloader').hide();
                    $('.apply-fullname').show();
                    $.modal.close();
                    alert('Your registration has been completed. Thank you.');
                }
            });
        }

        if ($('#checker').attr('run_js') === '1') {
           success();
        }
    })
})(jQuery)

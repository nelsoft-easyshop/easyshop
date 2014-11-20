
    (function($) {
    
        $(document).on('click', '#send_registration', function() {
            $('#send_registration').html('Please wait');
            
            var $button = $(this);
            if(!$button.hasClass('enabled')){
                alert('Please select the attributes you want for this item.');
                return false;
            }
            
            console.log($button.data('canpurchase'));
            if($button.data('canpurchase') != true){
                alert('Sorry, this item is currently not available for purchase.');
                return false;
            }
            
            var $productId = $("#productId").val();
            var $csrftoken = $("meta[name='csrf-token']").attr('content'); 
            var msg = 'Kindly login to qualify for this promo.';
            $.ajax({
                url : '/promo/BuyAtZero/buyAtZeroRegistration',
                type : 'post',
                dataType : 'JSON',
                data : {
                    csrfname:$csrftoken,
                    id:$productId
                },
                success : function(data){
                    $('#send_registration').html('Buy Now');
                    if(data == "not-logged-in"){
                        msg = 'Kindly login to qualify for this promo.';
                        setTimeout(function() {
                            window.location = "/login";
                        }, 1000);
                    }
                    else if(data){
                        msg = "Congratulations! You now have the chance to win this  " + 
                            escapeHtml($('#pname').html())  + " item! The lucky winner will be " +
                            "announced on December 16, 2014. Stay tuned for more EasyShop.ph " +
                            "promotions. ";
                    }
                    else{
                        msg = "You are already currently subscribed for this promo. " +
                            "Stay tuned to find out whether you are one of the lucky winners.";
                    }
                    
                    alert("<div style='font-size: 13px; font-weight: lighter;'>" + msg + "</div>")
                }
            });
        });

    })(jQuery);



(function($) {
$(document).ready(function(){

    if (window.location.hash.substring(1) === "mechanics") {
        $('html, body').animate({
            scrollTop: $(".ss").offset().top
        }, 300);
    }

});

    $('.btn-primary').click(function() {

        $(".error").hide();
        var hasError = false;
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var emailaddressVal = $("#useremail").val();

        if(emailaddressVal == '') {
            $(".newsletter-info-blank").show();
            $(".newsletter-validate, .newsletter-info").hide();
            hasError = true;
        }

        else if(!emailReg.test(emailaddressVal)) {
            $(".newsletter-validate").show();
            $(".newsletter-info-blank, .newsletter-info").hide();
            hasError = true;
        }

        if(hasError == true) {
            return false;
        }

        else {

            $('#register').submit();
            $(".newsletter-info").show();
            $(".newsletter-validate, .newsletter-info-blank").hide();

            return false;
        }
    });

    $('#ddown-school').on('change', function() {
        var $this = $(this);
        var $selectedOpt = $this.find(':selected');

        $('[name="student"]').prop('checked', false);
        $('.display-none').hide();
        $('#'+$selectedOpt.val()).show();
    });

    $('#btn-vote').on('click', function() {
        var $this = $(this);
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var $selectedRadio = $('[name="student"]:checked');
        var schoolId = parseInt($selectedRadio.data('school'));
        var studentId = parseInt($selectedRadio.val());
        var url = '/promo/Estudyantrepreneur/vote';

        if (isNaN(schoolId) || isNaN(studentId) === 0) {
            alert("Choose School and Student to vote");
            return false;
        }

        $.ajax({
            type : 'POST',
            dataType : 'json',
            url : url,
            data : {schoolId: schoolId, studentId: studentId, csrfname: csrftoken},
            success : function (data) {
                if (data.isSuccessful === true) {
                    window.location.replace('/EstudyantrepreneurSuccess');
                }
                alert(data.errorMsg);
            }
        });
    });

})(jQuery);

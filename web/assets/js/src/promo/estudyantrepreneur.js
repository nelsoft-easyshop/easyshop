(function($) {
$(document).ready(function(){

    if (window.location.hash.substring(1) === "mechanics") {
        $('html, body').animate({
            scrollTop: $(".ss").offset().top
        }, 300);
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
                alert(data.errorMsg);
            }
        });
    });

})(jQuery);

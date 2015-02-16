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
        var students = $selectedOpt.data('students');
        var html = '';

        if ($this.val() !== '') {

            for (var index = 0; students.length > index; index++) {
                html += '<span>' +
                    '<input type="radio" name="school" value="'+ students[index].idStudent +'" data-school="'+ students[index].idSchool +'">' +
                    '<label > '+ students[index].student +'</label>' +
                    '</span>';
            }

        }

        $('#student-container').html(html);

    });

    $('#btn-vote').on('click', function() {
        var $selectedRadio = $('[name="school"]:checked');
        var schoolId = parseInt($selectedRadio.data('school'));
        var studentId = parseInt($selectedRadio.val());

        if (isNaN(schoolId) || isNaN(studentId) === 0) {
            alert("Choose School and Student to vote");
            return false;
        }

        $('#stud-id').val(studentId);
        $('#school-id').val(schoolId);
        $('#frm-vote').submit();

    });

    //TODO : Check if the user can vote for a specific school, validate school if student <= 3 ? qualified student for nextround : retrieve
    // Waiting for design.

})(jQuery);

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
        var isSuccessPage = $this.hasClass('success');

        if ($this.val() !== '') {

            for (var index = 0; students.length > index; index++) {
                if (isSuccessPage) {
                    html += '<li><span class="school-name">' + students[index].student  +
                            '</span>'+
                            '<span class="school-percentage">' + parseFloat(students[index].currentPercentage).toFixed(2) + '%</span>' +
                            '</li>';
                }
                else {
                    html += '<span>' +
                        '<input type="radio" name="school" value="'+ students[index].idStudent +'" data-school="'+ students[index].school +'">' +
                        '<label > '+ students[index].student +'</label>' +
                        '</span>';
                }
            }

        }

        $('#student-container').html(html);

    });

    $('#btn-vote').on('click', function() {
        var $selectedRadio = $('[name="school"]:checked');
        var $isRadioChecked = $('[name="school"]').is(':checked');

        if (!$isRadioChecked) {
            alert("Choose School and Student to vote");
            return false;
        }

        var schoolName = $selectedRadio.attr('data-school').trim();
        var studentId = parseInt($selectedRadio.val());

        if (schoolName === '' || isNaN(studentId) === 0) {
            alert("Choose School and Student to vote");
            return false;
        }

        if ($('#is-logged-in').val() === 'false') {
            alert('Kindly login to join this event');
            window.location.replace('/login');
            return false;
        }

        $('#stud-id').val(studentId);
        $('#school-name').val(schoolName);
        $('#frm-vote').submit();

    });

})(jQuery);

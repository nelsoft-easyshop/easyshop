(function ($) {

    formPersonalInfo.find( "#birthday-picker" ).datepicker({ dateFormat: "yy-mm-dd" });
    $("#formPersonalInfo").on('click','#savePersonalInfo',function (e) {
        e.preventDefault();

        var fullname = formPersonalInfo.find("#fname").val().trim() + " " + formPersonalInfo.find("#lname").val().trim();
        var gender = formPersonalInfo.find('input:radio[name=gender]:checked').val();        
        var bday = formPersonalInfo.find("#birthday-picker").val();
        var mobileNumber = formPersonalInfo.find("#mobileNumber").val();
        var email = formPersonalInfo.find("#emailAddress").val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        var csrfname = $("meta[name='csrf-name']").attr('content');
        $.ajax({
            type: 'post',
            data: {fullname:fullname, gender:gender, dateofbirth:bday, mobile: mobileNumber, email:email, csrfname : csrftoken},
            url: "/memberpage/edit_personal",
            success: function(data) {
                    var obj = jQuery.parseJSON(data);
                    console.log(obj);

                   
            },
        });     
        console.log(fname + lname + gender + bday + mobileNumber + email);

    });


}(jQuery));
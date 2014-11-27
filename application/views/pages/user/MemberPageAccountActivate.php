Hello there!<br>

We're really excited that you've decided to reactivate your account with us.<br>
Just follow the instructions below and and you'll be done in a jiffy.<br>
<br>
Please enter your new password:
<input type="password" id="new-password-txt"><br>
Please enter your new password again:
<input type="password" id="retype-password-txt"><br>

<script>
    (function ($) {
        var password = $('#new-password-txt').val();
        var retypePassword = $('#retype-password-txt').val();
        var csrftoken = $("meta[name='csrf-token']").attr('content');
        if (password != retypePassword) {
            alert('Password and Re-type password should match');
            return false;
        }
        $.ajax({
            url : '/memberpage/updatePassword',
            dataType : 'json',
            type : 'POST',
            data : {csrfname : csrftoken, password:password},
            success : function (data) {

            }
        });
    })(jQuery)
</script>
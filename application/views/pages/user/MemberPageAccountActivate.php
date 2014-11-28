<link type="text/css" href='/assets/css/new-dashboard.css' rel="stylesheet" media='screen'/>
<div class="activateAccount">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="panel panel-default panel-activate">
                    <div class="panel-heading">Activate your account</div>
                    <div class="panel-body">
                        <p class="p-panel-body">
                        Hello there!<br>

                        We're really excited that you've decided to reactivate your account with us.<br>
                        Just follow the instructions below and and you'll be done in a jiffy.<br>
                        </p>
                        <br>
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">New password : </label>
                                <div class="col-sm-7">
                                    <input type = "password"  id="new-password-txt" class="text-info text-required" placeholder="Type your new password here">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Confirm new password : </label>
                                <div class="col-sm-7">
                                    <input type = "password"  id="retype-password-txt" class="text-info text-required" placeholder="Confirm your new password here">
                                </div>
                            </div>
                        </form>
                        <div class="activateActions">
                            <center>
                                <input type="submit" class="btn btn-setting-save-btn" id="changePassBtn" name="changePassBtn"  value="Save Changes">
                                <span class="btn btn-setting-cancel-btn" id="cancel-edit-password">
                                    Cancel
                                </span>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
<link type="text/css" href='/assets/css/new-dashboard.css' rel="stylesheet" media='screen'/>
<style>
#simplemodal-container{
        height: 150px !important;
    }
#simplemodal-container h2{
    padding: 14px 20px;
    font-size: 18px;
    font-weight: 900;
    color: #565656;
    }

@media only screen and (max-width: 991px){
        #simplemodal-container{
            height: 200px !important;
        }
    }
</style>
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
                        <form class="form-horizontal" id="activateAccountForm">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Your password : </label>
                                <div class="col-sm-7">
                                    <input type = "hidden" name="h" value="<?php echo $hash; ?>">
                                    <input type = "hidden" name="userId" value="<?php echo $idMember; ?>">
                                    <input type = "password"  id="password" name="password" class="text-info text-required" placeholder="Type your new password here">
                                </div> 
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Confirm  password : </label>
                                <div class="col-sm-7">
                                    <input type = "password"  id="confirmPassword" name="confirmPassword" class="text-info text-required" placeholder="Confirm your new password here">
                                </div>
                            </div>
                            <div class="activateActions">
                                <center>
                                    <input type="submit" class="btn btn-setting-save-btn" id="activateAccountButton" name="activateAccountButton"  value="Save Changes">
                                    <span class="btn btn-setting-cancel-btn" id="cancel-edit-password">
                                        Cancel
                                    </span>
                                </center>
                            </div>
                            <img src="/assets/images/orange_loader_small.gif" id="deactivateAccountLoader" style="display:none"/>                                
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="activated-modal" style="display:none; height: 100px;">
    <div class="feedback-content">
        <div id="activated-message">
             Congratulations! You've just reactivated your account with EasyShop!

            <a href="/home">Click here</a> to go back to our Homepage and start browsing for your favourite items.
        </div>
    </div>
</div>

<script src="/assets/js/src/vendor/jquery-1.9.1.js?ver=<?=ES_FILE_VERSION?>"></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>

<script>
    (function ($) {


    var loadingimg = $('img#deactivateAccountLoader'); 
    var verifyspan = $('.activateActions');
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');  

    /********************* ACTIVATE ACCOUNT ****************************/

     $("#activateAccountForm").validate({
         rules: {
            password: {
                required: true,
                },
            confirmPassword: {
                required: true,
                equalTo: '#password'
                }
         },
         messages:{
            confirmPassword: {
                equalTo: 'Both of your entered passwords must match'
            },
         },
         errorElement: "span",
         errorPlacement: function(error, element) {
            error.addClass("val-error");
            error.appendTo(element.parent());
                      
         },
         submitHandler: function(form){

            verifyspan.hide();
            loadingimg.show();                          
            event.preventDefault();
            var postData = $("#activateAccountForm").serializeArray();
            $.ajax({
                type: 'get',
                data: postData,
                url: "/memberpage/activateAccount",                
                success: function(data) {                      
                    var obj = jQuery.parseJSON(data); 
                    if(obj.result === "success") {
                        login(obj.username,obj.password);
                    }
                    else {
                        alert("Invalid Password");
                    }
                },
            });            
        }
     });
    
    function login(username, password)
    {
        $.ajax({
            type: "post",
            data: {login_form:"submit",login_username:username, login_password:password, csrfname : csrftoken},
            url: "/login/authenticate",                
            success: function(data) {
                verifyspan.show();
                loadingimg.hide();                 
                $('#activated-modal').modal();
            },
        });          
    }

    /********************* END ACTIVATE ACCOUNT ****************************/

    })(jQuery)

</script>

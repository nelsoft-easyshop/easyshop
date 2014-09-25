<link type="text/css" href='<?=base_url()?>assets/css/contact.css' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
        <div class="row row-contact">
            
             <?php echo $userDetails; ?>

            <div class="col-xs-9">
                <div class="panel-contact-message">
                    <p class="panel-title-contact">
                        Leave A Message
                    </p>
                    <div class="div-message-form">
                        <div class="row">
                            <div class="col-xs-6 col-message-1">
                                <input type="text" class="input-lg input-message" placeholder="NAME..."/>
                            </div>
                            <div class="col-xs-6 col-message-2">
                                <input type="text" class="input-lg input-message" placeholder="PHONE NUMBER..."/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-message-1">
                                <input type="text" class="input-lg input-message" placeholder="EMAIL ADDRESS..."/>
                            </div>
                            <div class="col-xs-6 col-message-2">
                                <input type="text" class="input-lg input-message" placeholder="WEBSITE..."/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <textarea class="input-lg input-message" rows="7" placeholder="MESSAGE..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <center>
                                <input type="submit" class="btn btn-send" value="SEND MESSAGE">
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
<script>
    $( ".fa-edit-icon" ).click(function() {
        $(".input-detail").css("display","inline");
        $(".fa-cancel-edit").css("display","inline");
        $("#save-edit").css("display","inline");
        $(".fa-edit").css("display","none");
        $(".text-contact").css("display","none");
     });
    
     $( ".fa-cancel-edit" ).click(function() {
        $(".input-detail").css("display","none");
        $(".fa-cancel-edit").css("display","none");
        $("#save-edit").css("display","none");
        $(".fa-edit").css("display","inline");
        $(".text-contact").css("display","inline");
     });
</script>
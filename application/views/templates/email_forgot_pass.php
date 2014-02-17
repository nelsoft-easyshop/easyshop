<section>
  	<div align="right"><a href="<?=base_url()?>home"><img src="<?=base_url()?>assets/images/img_logo.png" alt="Easyshop.ph"></a> </div>
</section>
<section>
    <div>
        Hi <?php echo $username; ?>,<br /><br />
        We received a password reset request for your Easyshop.ph account. To reset your password, use the links below:<br />
        <a href="<?php echo base_url() . "login/resetconfirm?&confirm=". $trigger; ?>"><?php echo base_url() . "login/resetconfirm?confirm=". $trigger; ?></a>
        <br /><br />
        If you didn't request a password reset, you can let us know here:<br />
        <a href="https://Easyshop.ph/contact/">https://Easyshop.ph/contact/</a>. <br />
        Either way, you can ignore this message and your password will not be changed -- someone probably typed in your username or email address by accident.
        <br /><br />
        <strong>- The Easyshop.ph Team</strong>    
        <br /><br /><br /><br /><br /><br />
    </div>
</section>
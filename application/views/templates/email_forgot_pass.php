<table style="width:780px; margin:0px auto; border-top:5px solid #f18200; border-left:1px solid #f18200;border-right:1px solid #f18200;border-bottom:1px solid #f18200; font-family:arial">
      <thead>
        <tr>
          <td style="height:60px;text-align:center;">
            <a href="<?=base_url()?>"><img src="<?=base_url()?>assets/images/img_logo.png" alt="Easyshop.ph"></a>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>
                <div>
                Hi <?php echo $username; ?>,<br /><br />
                We received a password reset request for your Easyshop.ph account. To reset your password, use the links below:<br />
                <a href="<?php echo base_url() . "login/resetconfirm?&confirm=". $trigger; ?>"><?php echo base_url() . "login/resetconfirm?confirm=". $trigger; ?></a>
                <br /><br />
                If you didn't request a password reset, you can let us know here:<br />
                <a href="https://Easyshop.ph/contact/">info@easyshop.ph</a>. <br />
                Either way, you can ignore this message and your password will not be changed.
            </div>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
         <td style="text-align:left;padding-top:50px;padding-bottom:50px;font-size:12px;">
           <strong>- The Easyshop.ph Team</strong>   
         </td>
      </tr>
    </tfoot>
</table>

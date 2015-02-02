<table style="width:780px; margin:0px auto; border-top:5px solid #f18200; border-left:1px solid #f18200;border-right:1px solid #f18200;border-bottom:1px solid #f18200; font-family:arial">
      <thead>
        <tr>
          <td style="height:60px;text-align:center;">
            <a href="<?php echo base_url()?>"><img src="img_logo.png" alt="Easyshop.ph"></a>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>
                <div>
                Hi <?php echo html_escape($username); ?>,<br /><br />
                We received a password reset request for your Easyshop.ph account. To reset your password, kindly use the link below:<br />
                <a href="<?php echo base_url() . "login/updatePassword?&confirm=". $trigger; ?>"><?php echo base_url() . "login/updatePassword?confirm=". $trigger; ?></a>
                <br /><br />
                If you didn't make a request for a password reset, you can let us know at info@easyshop.ph. 
                <br/>
                Thank you. <br />
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

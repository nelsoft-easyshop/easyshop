<div class="clear"></div>

  
<section>
    <div class="wrapper">
        <span class="reg_title">Verify account information</span>
    </div>
</section>

<section>
    <div class="wrapper">
        <div class="success_content">
            <center>
            <div>
                <?php if(isset($isAlreadyVerified) && $isAlreadyVerified): ?>
                    <p style="color:#2e5014; font-weight:bold;">Hi <?php echo html_escape($username); ?>,<p>
                <?php else: ?>
                    <p style="color:#2e5014; font-weight:bold;">Hi <?php echo html_escape($username); ?>, congratulations! <p>
                <?php endif; ?>
                
                <p style="color:#2e5014; font-size: 16px; "><?php echo html_escape($verificationMessage); ?></p>
                
                <br/>
                <p style='font-size: 14px; color:black;'>
                    You may now  <a href="/login">log-in</a> to begin your online shopping experience.
                </p>
            </div>
            </center>
        </div>
    </div>
</section>


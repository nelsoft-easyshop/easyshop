<div class="clear"></div>

  
<section class="bg-cl-fff">
    <div class="container">
        <h1 class="email-h1-title border-bottom pd-bttm-10">Verify account information</h1>
    </div>
</section>

<section class="bg-cl-fff">
    <div class="container">
        <div class="success_content email-verification-content">
            <center>
                <div>
                    <?php if(isset($isAlreadyVerified) && $isAlreadyVerified): ?>
                        <p class="username-title">
                            <strong>Hi <?php echo html_escape($username); ?>,</strong>
                        <p>
                    <?php else: ?>
                        <p class="username-title">
                            <strong>Hi <?php echo html_escape($username); ?>, congratulations! </strong>
                        <p>
                    <?php endif; ?>
                    
                    <p><?php echo html_escape($verificationMessage); ?></p>
                    
                    <br/>
                    <p>
                        You may now  <a href="/login">log-in</a> to begin your online shopping experience.
                    </p>
                </div>
            </center>
        </div>
    </div>
</section>


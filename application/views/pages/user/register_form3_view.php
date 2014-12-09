
<div class="clear"></div>
    <div class="wrapper">
    <div class="clear"></div>
    <section>
      <div class="wrapper">
        <span class="reg_title">Verify account information</span>
       </div>
    </section>
    <section>
      <div class="wrapper">
        <div class="reg_steps reg_step3">
          <ul>
            <li>Step 1. Fill in the account information</li>
            <li>Step 2. Verify account information</li>
            <li>Step 3. Successful registration</li>
          </ul>
        </div>
        <div class="success_content">
          <div>
            <p><img src="<?php echo getAssetsDomain()?>assets/images/img_success.png" ><?php echo $member_username?></p>
          </div>
          <div>
			<br>
            <h2><?php echo $verification_msg?></h2>
			<br><br>
			<?php if($logged_in == false):?>
				<a href="/login" class="continue">Go to Login Page</a>
			<?php elseif($logged_in == true):?>
				<a href="/" class="continue">Go to Home Page</a>
			<?php endif;?>
			
          </div> 
        </div>
      </div>
    </section>
</div>
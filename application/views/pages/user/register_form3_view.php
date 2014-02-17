<script language="javascript" type="text/javascript">

	$(document).ready(function(){
		$('.search_wrapper').css('display','none');
	});

</script>


<div class="clear"></div>
    <section>
      <div class="wrapper">
        <div class="logo">
            <a href="<?=base_url()?>home"><img src="<?=base_url()?>assets/images/img_logo.png" alt="Logo"></a>
        </div>
    </section>
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
            <p><img src="<?=base_url()?>assets/images/img_success.png" ><?php echo $member_username?></p>
          </div>
          <div>
            <h2><?php echo $verification_msg?></h2> 
			<?php if($logged_in == false):?>
				<a href="<?=base_url()?>login" class="continue">Go to Login Page</a>
			<?php elseif($logged_in == true):?>
				<a href="<?=base_url()?>home" class="continue">Go to Home Page</a>
			<?php endif;?>
			
          </div> 
        </div>
      </div>
    </section>
</div>
<div class="clear"></div>
<section>
	<div class="wrapper"> <span class="reg_title">Change Password</span></div>
</section>
<div class="clear"></div>
<section>
      <div class="wrapper">
        <?php if(!$logged_in):?>
		
		<?php 
			$attr = array('id'=>'forgotpass','autocomplete'=>'off');
			echo form_open('',$attr);
		?>		
          <div class="fill_out_form">
		  		<?php 
					$toggle_view = $this->input->get('tgv');
				?>				
				<?php if ($toggle_view == ""): ?>
                    <div class="field">
                          <div class="password_label">
                                <label for="password">New Password:</label>
                                <input id="password" name="password" type="password" maxlength="25" />
								<input id="hash" name="hash" type="hidden" value="<?php echo $this->input->get('confirm'); ?>"/>
                          </div>
                          <span class="red ci_form_validation_error"><?php echo form_error('password'); ?></span>
                    </div>
                    <div class="clear"></div>
                    <div class="password_info"> 
                    	Passwords must be within 6-25 characters, and include numbers and upper-case and lower-case letters. 
                    </div>
                    <div class="field">
                          <label>Confirm New Password:</label>
                          <input id="cpassword" name="cpassword" type="password" maxlength="100" disabled="true">
                          <img src="<?=base_url()?>/assets/images/check_icon.png" id="cpassword_check" style="position: relative;display:none;"/>
                          <img src="<?=base_url()?>/assets/images/x_icon.png" id="cpassword_x" style="position: relative;display:none; "/>
                          <span class="red ci_form_validation_error"><?php echo form_error('cpassword'); ?></span>
                    </div>
					<div class="clear"></div>
			        <div class="reset_pword_btn_con">
                          <input type="button" name="forgotpass_btn" id="forgotpass_btn" value="Save"/>
                    	
					</div>
                <?php elseif($toggle_view == "69"): ?>
                    <p><b>Sorry, the link is invalid or is already expired.</b></p>
           	        <p><a href='<?=base_url()?>home'>Return to the Home Page.</a></p>
               <?php elseif($toggle_view == "0"): ?>
                   Password is successfully updated! <a href='<?=base_url()?>memberpage'>click HERE</a> to return.		        
               <?php else: ?>
                    <p><b>Sorry, the link is invalid or is already expired.</b></p>
           	        <p><a href='<?=base_url()?>home'>Return to the Home Page.</a></p>			   
               <?php endif; ?>  			           
          </div>
		  <input type="hidden" id="fc_csrf" name="<?php echo $my_csrf['csrf_name'];?>" value="<?php echo $my_csrf['csrf_hash'];?>">  
		<?php echo form_close();?>
        <?php else: ?>
			<?php redirect(base_url().'home');?>
        <?php endif; ?>
      </div>
</section>
<!-- password strength checker -->
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/mootools-core-1.4.5-full-compat.js"></script> 
<script type="text/javascript" src="<?=base_url()?>assets/JavaScript/js/password_meter.js"></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.numeric.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/js/jquery.validate.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/JavaScript/register.js'></script>
<script type="text/javascript">
$(document).ready(function(){

	 $("#forgotpass").validate({
		 rules: {				
			password: {
				required: true,
                minlength: 6,
                maxlength:25,
				alphanumeric: true,
				//case_all: true
				},
			cpassword: {
				required: true,
				minlength: 6,
                maxlength:25,
				equalTo: '#password'
				}
		 },
		 messages:{
			cpassword:{
				equalTo: ''
			}
		 },
		 errorElement: "span",
		 errorPlacement: function(error, element) {
				error.addClass('red');
				if(element.attr('name') == 'password'){
					var added_span = $('<span/>',{'class':"red"});
					error.insertBefore(element.next());
					added_span.insertBefore(element.next());
				}else{
					error.appendTo(element.parent());	
				}
		 }
	 	
	 });
	 
	$('.field input').on('click', function(){
		$('.ci_form_validation_error').text('');
	 });	 
	 
});

$(document).ready(function(){ 
    var currentRequest = null;
	var redurl = '<?php echo base_url();?>login/resetconfirm?&tgv='
    $( "#forgotpass_btn" ).click(function() {
		if($("#forgotpass").valid()){
			var pass = $("#password").val();		  
			var hash 	 = $('#hash').val();
			var csrftoken = $('#fc_csrf').val();		
			currentRequest = jQuery.ajax({
				type: "POST",
				url: '<?php echo base_url();?>login/xresetconfirm', 
				data: "hash="+hash+"&pass="+pass+"&es_csrf_token="+csrftoken, 
				beforeSend : function(){       
				},
				success: function(response){
					$("#password, #cpassword").val('');
					if(response == 0){
						window.location = redurl+response;
					}else if(response == 1){
						window.location = redurl+response;
					}else if(response == 69){
						window.location = redurl+response;
					}
				}
			});		
		}		
    });	
	
});
</script>

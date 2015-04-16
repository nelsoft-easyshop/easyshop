<form name='payFormCcard' id='payFormCcard' method='post' action='<?php echo $redirectUrl?>'> 
	<input type='hidden' name='merchantId' value='<?php echo $merchantId; ?>'>
	<input type='hidden' name='amount' value='<?php echo $amount?>' >
	<input type='hidden' name='orderRef' value='<?php echo $orderRef?>'>
	<input type='hidden' name='currCode' value='<?=$currCode; ?>' >
	<input type='hidden' name='mpsMode' value='NIL' >
	<input type='hidden' name='successUrl' value='<?php echo base_url() ?>payment/pesoPayReturnUrl?status=s'>
	<input type='hidden' name='failUrl' value='<?php echo base_url() ?>payment/pesoPayReturnUrl?status=f&Ref=<?php echo $orderRef; ?>'>
	<input type='hidden' name='cancelUrl'value='<?php echo base_url() ?>payment/review'>
	<input type='hidden' name='payType' value='<?=$payType; ?>'>
	<input type='hidden' name='lang' value='E'>
	<input type='hidden' name='payMethod' value='CC'>
	<input type='hidden' name='secureHash' value='<?=$secureHash; ?>'>
</form>
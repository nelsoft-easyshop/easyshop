<form name='payFormCcard' id='payFormCcard' method='post' action='<?php echo $url?>'> 
<input type='hidden' name='merchantId' value='18061489'>
<input type='hidden' name='amount' value='<?php echo $amount?>' >
<input type='hidden' name='orderRef' value='<?php echo $orderRef?>'>
<input type='hidden' name='currCode' value='608' >
<input type='hidden' name='mpsMode' value='NIL' >
<input type='hidden' name='successUrl' value='https://ryan.easyshop.ph/payment/pesoPayReturnUrl?status=s'>
<input type='hidden' name='failUrl' value='https://ryan.easyshop.ph/payment/pesoPayReturnUrl?status=f'>
<input type='hidden' name='cancelUrl'value='https://ryan.easyshop.ph/payment/review'>
<input type='hidden' name='payType' value='N'>
<input type='hidden' name='lang' value='E'>
<input type='hidden' name='payMethod' value='CC'>  
</form>
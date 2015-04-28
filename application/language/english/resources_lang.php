<?php
$lang['sms_header'] = "Welcome to EasyShop.ph ";
$lang['sms_body'] = ". Kindly enter the following confimation code on the last page of the online registration process: ";
$lang['sms_footer'] = ". Thank you.";
$lang['registration3_confirmation'] = "A message containing a confirmation code has been sent to your mobile number. Input the 8-digit code on the box below.";
$lang['registration3_complete_previous'] = "Kindly complete the previous pages.";
$lang['registration3_invalid_confirmation'] = "Invalid confirmation code.";

$lang['schoollevel_option'] = array('Please Select', 'Undergraduate degree','Masteral degree','Doctorate degree','High School','Elementary');
$lang['product_condition'] =  array('New', 'Slightly Used', 'New other (see details)','Manufacturer refurbished','Used','For parts or not working');

#Rating Criteria
$lang['rating']= array('Item quality', 'Communication', 'Shipment time');

#successful registration
$lang['success_registration'] = "Thank You for registering to Easyshop.ph! <br> Verify your email address to experience Easyshop.ph at it's fullest!";

$lang['success_email_verification'] = 'You have successfully verified your email address.';
$lang['success_mobile_verification'] = 'You have successfully verified your mobile number.';
$lang['expired_email_verification'] = 'This verification link has already expired or this email has already been verified.';

$lang['email_subject'] = "Easyshop.ph - Please verify your email address.";

#Forgot Password - forgotpass.php
$lang['msg1'] = "Password reset successfully sent!";
$lang['msg2'] = "Sorry, the email you provided is unregistered.";
$lang['msg3'] = "Unable to send, please try again later.";

#Purchase Notification - Email
$lang['notification_subject_buyer'] = "Easyshop.ph - Purchased Item Transaction Details";
$lang['notification_subject_seller'] = "Easyshop.ph - Sold Item Transaction Details";

#Purchase Notification - Email Payment Method Guidelines
$lang['payment_paypal_buyer'] = "Easyshop.ph has received your payment and has notified the seller(s) of the items you have purchased to 
ship your items at the soonest possible time. You may view and manage your orders through your transactions 
page by going to your page at <a href='".base_url()."me'>".base_url()."me</a> and by clicking on on-going transactions. 
<br><br>
Thank you and we hope you continue to use Easyshop.ph, where shopping is made easy.";

$lang['payment_pesopay_buyer'] = "Easyshop.ph has received your payment and has notified the seller(s) of the items you have purchased to 
ship your items at the soonest possible time. You may view and manage your orders through your transactions 
page by going to your page at <a href='".base_url()."me'>".base_url()."me</a> and by clicking on on-going transactions. 
<br><br>
Thank you and we hope you continue to use Easyshop.ph, where shopping is made easy.";

$lang['payment_cod_buyer'] = "Easyshop.ph has notified the seller(s) of the items you have purchased to ship your items at the soonest possible time.
You may view and manage your orders through your transactions page by going to your page at <a href='".base_url()."me'>".base_url()."me</a> and by clicking on on-going transactions. 
<br><br>
Thank you and we hope you continue to use Easyshop.ph, where shopping is made easy.";

$lang['payment_dp_buyer'] = "Please follow Dragonpay's instructions regarding the necessary steps you need to accomplish to complete your purchase.
Once confirmed, you may view and manage your orders through your transactions page by going to your page at <a href='".base_url()."me'>".base_url()."me</a> and by clicking on on-going transactions. 
We will also notify the seller(s) of the items you have purchased to ship your items at the soonest possible time.
<br><br>
Thank you and we hope you continue to use Easyshop.ph, where shopping is made easy.";

$lang['payment_bd_buyer'] = "Your order has been placed at Easyshop.ph and is currently awaiting confirmation. Deposit the specified amount at
the bank account listed below to complete the purchase of your items.
<br><br>
<table>
    <tr>
        <td>Bank : </td>
        <td><strong>{bank_name}</strong></td>
    </tr>
    <tr>
        <td>Bank Account Name : </td>
        <td><strong>{bank_accname}</strong></td>
    </tr>
    <tr>
        <td>Bank Account # :</td>
        <td><strong>{bank_accnum}</strong></td>
    </tr>
</table>
<br>
Once complete, you will need to enter the deposit details at your transaction page by going to <a href='".base_url()."me'>".base_url()."me</a> and by clicking on on-going transactions.
Look for your transaction,click on Enter Deposit Details and fill in the necessary information.
<br>
We will notify the seller(s) of the items you have purchased to ship your items at the soonest possible time once you have
accomplished all the necessary steps.
<br><br>
Thank you and we hope you continue to use Easyshop.ph, where shopping is made easy.";

$lang['payment_ppdp_seller'] = "A purchase has been made for your listing(s) at Easyshop.ph. The details of your sale
and the shipment information of your buyer are included in the summary below.
We have received the payment for this transaction and will move the payment to your
account as soon as the buyer has acknowledge that he has received your item.
<br><br>
Kindly ship/deliver your item at the soonest possible time. ";

$lang['payment_cod_seller'] = "A purchase has been made for your listing(s) at Easyshop.ph through Cash on Delivery.
The details of your sale and the shipment information of your buyer are included in the summary below.
<br><br>
Kindly ship/deliver your item at the soonest possible time.";

#Purchase Notification - Mobile
$lang['notification_txtmsg_buyer'] = ' - This is to notify you that you have made a purchase through Easyshop.ph. Transaction details have been sent to your email.';
$lang['notification_txtmsg_seller'] = ' - This is to notify you that you have sold an item(s) through Easyshop.ph. Transaction details have been sent to your email. Kindly ship the item(s) as soon as possible.';

#Transaction response by buyer or seller 
#payment flow direction (forward to seller or return to buyer)
$lang['notification_returntobuyer'] = "Easyshop.ph - Returned Payment Confirmation";
$lang['notification_forwardtoseller'] = "Easyshop.ph - Item Receipt Confirmation";

#Landing Page
$lang['subscription_subject'] = "Easyshop.ph - Thank you for subscribing!";
$lang['registration_subject'] = "Easyshop.ph - Thank you for registering!";
$lang['reverify_subject'] = "Easyshop.ph - Verify your email address";

#Notification to Seller and Buyer
$lang['message_to_seller'] = "This is to notify you that you have sold an item(s) through Easyshop.ph. Transaction details can be seen in your transactions page.  This is a system generated message, please do not reply to this message. Should you need any assistance contact us at info@easyshop.ph";
$lang['message_to_buyer'] = "This is to notify you that you have made a purchase through Easyshop.ph. Transaction details can be seen in your transactions page.  This is a system generated message,  please do not reply to this message. Should you need any assistance contact us at info@easyshop.ph";

# New Message notification
$lang['new_message_notif'] = "Easyshop.ph - New message received";

#Merge account notification
$lang['merge_subject'] = 'Easyshop.ph - Merging of account';

#Account activation notification
$lang['deactivate_subject'] = 'Easyshop.ph - Account Activation';

#Notification shipping comment provided
$lang['notification_shipping_comment'] = "Easyshop.ph - Shipping details for your purchased item";

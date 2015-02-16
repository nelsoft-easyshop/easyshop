<?php if(strtolower(ENVIRONMENT) === 'development'): ?>
    <link rel="stylesheet" href="/assets/css/bootstrap.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/my_cart_css.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/payment_review.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/assets/css/bootstrap-mods.css" type="text/css" media="screen"/>
<?php else: ?>
    <link type="text/css" href='/assets/css/min-easyshop.payment.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<?php endif; ?>


<div class="container font-roboto" style="max-width:980px; margin-top: 15px;">  
    <h2 class="checkout_title">Payment Result</h2>
    <div class="row">
        <br/>
        <div class="col-md-12">
            <?php echo $message; ?>
        </div>
        <br/>
    </div>
    <div class="row">
        <?php if($available): ?>
        <div class="col-md-7">
            <div class="panel panel-default no-border" style="min-height:336px !important;">
                <div class="panel-heading"><b>Transaction Details</b></div>
                <div class="panel-body" >
                    <?php if(isset($completepayment)): ?>
                    <table width="100%" class="hide-when-mobile-368" >
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Payment Reference Number:</b>
                            </td>
                            <td width="50%" style="padding: 5px;">
                                 <?php echo $txnid; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Payment Invoice Number:</b>
                            </td>
                            <td width="50%" style="padding: 5px;">
                                <?php echo $invoice_no; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Total Amount:</b>
                            </td>
                            <td width="50%" style="padding: 5px;">
                                &#8369; <?php echo number_format($total, 2, '.',','); ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Date of Transaction:</b>
                            </td>
                            <td width="50%" style="padding: 5px;">
                                <?php echo date("d/m/Y h:i A", strtotime($dateadded)); ?>
                            </td>
                        </tr>
                    <table width="100%" class="display-when-mobile-368" style="font-size: 11px !important;">
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Payment Reference Number:</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 2px 5px 5px 5px;">
                                 <?php echo $txnid; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Payment Invoice Number:</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 2px 5px 5px 5px;">
                                <?php echo $invoice_no; ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Total Amount:</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 2px 5px 5px 5px;">
                                &#8369; <?php echo number_format($total, 2, '.',','); ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <b>Date of Transaction:</b>
                            </td>
                        </tr>
                        <tr>
                            <td width="50%" style="padding: 5px;">
                                <?php echo date("d/m/Y h:i A", strtotime($dateadded)); ?>
                            </td>
                        </tr>
                    </table>
                    <?php else: ?>
                    <div class="payment_items_con">
                        <div>Transaction unsuccessful.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default no-border">
                <div class="panel-heading"><b>List of Products</b></div>
                <div class="panel-body">
                    <div style='overflow-y:scroll;overflow-x:no-scroll; min-height:260px; max-height: 260px; width: 100%; padding-right: 7px; '>
                        <table width="100%" class=" table-list">
                             <?php foreach ($itemList as $key => $value): ?>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Product:</b>
                                </td>
                                <td width="50%" style="padding: 5px;">
                                    <?php echo html_escape($value['name']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Store Name:</b>
                                </td>
                                <td width="50%" style="padding: 5px;">
                                    <?php echo html_escape($value['store_name']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Price:</b>
                                </td>
                                <td width="50%" style="padding: 5px;">
                                    <?php echo number_format($value['price'], 2, '.',','); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Quantity:</b>
                                </td>
                                <td width="50%" style="padding: 5px;">
                                    <?php echo $value['qty']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Subtotal:</b>
                                </td>
                                <td width="50%" style="padding: 5px;"> 
                                    <?php echo number_format($value['subtotal'], 2, '.',','); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Other Fee:</b>
                                </td>
                                <td width="50%" style="padding: 5px;">
                                    <?php echo number_format($value['otherFee'], 2, '.',','); ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="padding: 5px;">
                                    <b>Total Fee:</b>
                                </td>
                                <td width="50%" style="padding: 5px;">
                                    <?php echo  number_format($value['subtotal'] + $value['otherFee'], 2, '.',','); ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="border-top: 1px; border-style: dotted; border-color: #e6e6e6;">
                                    &nbsp;
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
        <?php endif ?> 
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if($completepayment): ?>
                    <div class="div-mess">
                        <p>
                            You have made a successful purchase on Easyshop.ph. An e-mail has been sent to you and the people from whom you purchased
                            regarding the status of your transaction. Please complete any necessary steps that are stated and thank you for using Easyshop.ph.
                            You may view your pending transactions by clicking <a style='text-decoration:underline; color:#f48000' href='/me?tab=ongoing'>here </a>
                        </p>
                    </div>
                <?php else: ?>
                    <div   class="div-mess">
                        <p>
                            We are sorry, your purchase cannot be completed at this time. Should you need any further assistance you may reach us through our customer hotline. Thank you for using Easyshop.ph. 
                        </p>
                    </div>
                <?php endif ?>
                
                 <div style='font-size: 14px; font-weight:bold;'>
                    <br/><br/>
                    <p>
                        <a href="/">Continue Shopping</a>
                    </p>
                </div>
        </div>
        
        
    </div>
</div>


<?php 
  if(ENVIRONMENT == 'production'){
?>
<script>
  ga('require', 'ecommerce', 'ecommerce.js');

  <?php
    foreach ($analytics as $key => $value) {
  ?>

  ga('ecommerce:addTransaction', {
      'id': '<?php echo $value["id"]; ?>',
      'affiliation': '<?php echo $value["affiliation"]; ?>',
      'revenue': '<?php echo $value["revenue"]; ?>',
      'shipping': '<?php echo $value["shipping"]; ?>',
      'tax': '<?php echo $value["tax"]; ?>',
      'currency': '<?php echo $value["currency"]; ?>'
  });

  ga('ecommerce:addItem', {
      id: '<?php echo $value["data"]["id"]; ?>',  
      sku: '<?php echo $value["data"]["sku"]; ?>',  
      name: '<?php echo $value["data"]["name"]; ?>',  
      category: '<?php echo $value["data"]["category"]; ?>',  
      price: '<?php echo $value["data"]["price"]; ?>',  
      quantity: '<?php echo $value["data"]["quantity"]; ?>'
  }); 

  ga('ecommerce:send');

  <?php
    }
  ?>

</script>

<?php } ?>
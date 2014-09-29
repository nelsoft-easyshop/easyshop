<html>
  <body style="color:#494949;">
    <table cellspacing="0" cellpadding="10" style="width:100%; max-width:780px; margin:0 auto; font-family: Arial, sans-serif;">
      <thead>
        <tr>
          <td>
            <div style="border-bottom:3px solid #f18200; padding-bottom:10px;">
              <a href=""><img src="images/img_logo.png" alt="Easyshop.ph"></a>
            </div>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <p>
              Dear {buyer_name},
            </p>
			<p style="text-align:justify;text-justify:inter-word;">
				{payment_msg_buyer}
			</p>
            <p>
              Details for the item(s) you've purchased are as follows:
            </p>
          </td>
        </tr>
      </tbody>
    </table>
    <table cellspacing="0" cellpadding="10" style="width:100%; max-width:760px; margin:0 auto;border:1px solid #cecece; font-family: Arial, sans-serif; font-size:12px;">
      <thead>
        <th style="text-align:left; background-color:#EBE9E9;">Summary of Purchased Items</th>
      </thead>
      <tr>
        <td>
          <span style="display:inline-block;font-weight:bold;width:100px;">Transaction # : </span>{invoice_no} <br>
          <span style="display:inline-block;font-weight:bold;width:100px;">Date :</span> {dateadded} <br>
          <span style="display:inline-block;font-weight:bold;width:100px;">Total Price :</span> Php {totalprice}<br><br>
        </td>
      </tr>
      <tr>
        <td>
          <strong>PURCHASED ITEMS:</strong>
        </td>
      </tr>
      {products}
      <tr>
        <td>
          <table style="font-size:12px; border-bottom:1px solid #cecece;width:100%;">
            <tbody>
              <tr>
                <td>
				  <span style="display:inline-block;font-weight:bold;width:150px;">Purchased from : </span>{seller} <br> 
				  <span style="display:inline-block;width:250px;margin-left:3em;"><a href="{store_link}{seller_slug}" target="_blank" style="color:orange;">View Store</a> || <a href="{msg_link}{seller}" target="_blank" style="color:orange;">Send Message</a></span> <br><br>
				  <span style="display:inline-block;font-weight:bold;width:150px;">Product Order #: </span>{order_product_id} <br>
                  <span style="display:inline-block;font-weight:bold;width:150px;">Product : </span>{name} <br>
				  <span style="display:inline-block;font-weight:bold;width:150px;">Base Price :</span> Php {baseprice} <br>
                  <span style="display:inline-block;font-weight:bold;width:150px;">Qty :</span> {order_quantity} <br>
                  <span style="display:inline-block;font-weight:bold;width:150px;">Handling Fee :</span> Php {handling_fee} <br>
                  <span style="display:inline-block;font-weight:bold;width:150px;">Final Price :</span> Php {finalprice} <br>
                  <span style="display:inline-block;font-weight:bold;width:150px;">Product Specifications:</span> <br>
                  {attr}
                    <span style="display:inline-block;font-weight:bold;width:150px;margin-left:2em;">{attr_name} :</span> {attr_value} <br>
                  {/attr} 
                  <br>
                </td>
              </tr>
            </tbody>
          </table>
          
        </td>
      </tr>
      {/products}
    </table>
  </body>
</html>
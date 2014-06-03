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
            <p>
              Thank you for purchasing through Easyshop.ph! Details for the item(s) you've purchased are as follows:
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
				  <span style="display:inline-block;font-weight:bold;width:100px;">Purchased from : </span>{seller} <br>
				  <span style="display:inline-block;font-weight:bold;width:100px;">Product Order #: </span>{order_product_id} <br>
                  <span style="display:inline-block;font-weight:bold;width:100px;">Product : </span>{name} <br>
                  <span style="display:inline-block;font-weight:bold;width:100px;">Qty :</span> {order_quantity} <br>
                  <span style="display:inline-block;font-weight:bold;width:100px;">Base Price :</span> Php {baseprice} <br>
                  <span style="display:inline-block;font-weight:bold;width:100px;">Other Fees :</span> Php {tax} <br>
                  <span style="display:inline-block;font-weight:bold;width:100px;">Final Price :</span> Php {finalprice} <br>
                  <span style="display:inline-block;font-weight:bold;width:100px;">Product Specifications:</span> <br>
                  {attr}
                    <span style="display:inline-block;font-weight:bold;width:100px;">{attr_name} :</span> {attr_value} <br>
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
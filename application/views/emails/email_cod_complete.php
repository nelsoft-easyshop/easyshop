<html>
    <body style="color:#494949;">
        <table cellspacing="0" cellpadding="10" style="width:100%; max-width:780px; margin:0 auto; font-family: Arial, sans-serif;">
            <thead>
                <tr>
                    <td colspan="3">
                        <div style="border-bottom:3px solid #f18200; padding-bottom:10px;">
                            <a href="" style="padding:10px 0; display:inline-block">
                                <img src="img_logo.png" alt="Easyshop.ph">
                            </a>
                        </div>  
                    </td>
                </tr>
            </thead>
            <tbody style="font-size:12px;">
                <tr>
                    <td colspan="3">
                        <span style="color:#f18200;font-weight:bold;">{user}</span> has just completed your Cash on Delivery Transaction
                        <span style="margin-left:1em;">
                            <a href="{store_link}" style="color:#f18200;">View Store</a> || <a href="{msg_link}" style="color:#f18200;">Send Message</a>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="120px"><strong>Transaction # :</strong> </td>
                    <td colspan="2">{invoice_no}</td>
                </tr>
                <tr>
                    <td><strong>Product Order # :</strong> </td>
                    <td colspan="2">{id_order_product}</td>
                </tr>
                <tr>
                    <td><strong>Product Name :</strong> </td>
                    <td colspan="2">{product_name}</td>
                </tr>
                <tr>
                    <td><strong>Base Price :</strong> </td>
                    <td colspan="2">{price}</td>
                </tr>
                <tr>
                    <td><strong>Qty :</strong> </td>
                    <td colspan="2">{order_quantity}</td>
                </tr>
                <tr>
                    <td><strong>Handling Fee :</strong> </td>
                    <td colspan="2">Php {handling_fee}</td>
                </tr>
                <tr>
                    <td><strong>Product Total Price :</strong> </td>
                    <td colspan="2">Php {total}</td>
                </tr>
                <tr>
                    <td><strong>Payment Method :</strong> </td>
                    <td colspan="2">{payment_method_name}</td>
                </tr>
                <tr>
                    <td><strong>Product Attributes : </strong></td>
                </tr>
                {attr}
                <tr>
                    <td><span style="margin-left:2em;">{field} : </span></td>
                    <td><span style="margin-left:2em;">{value}</span></td>
                </tr>
                {/attr}
            </tbody>
    </body>
</html>

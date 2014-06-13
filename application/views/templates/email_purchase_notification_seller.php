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
						<h2 style="font-size:16px;">
							<span style="color:#f18200;">{buyer_name}</span> purchased from you! 
							<span style="font-weight:normal;font-size:12px;margin-left:1em;">
								<a href="{store_link}" style="color:#f18200;">View Store</a> || <a href="{msg_link}" style="color:#f18200;">Send Message</a>
							</span>
						</h2>
						<p>
							Dear {seller_name},
						</p>
						<p>
							Thank you for selling through Easyshop.ph! Details for the item(s) you've sold are as follows:
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<table cellspacing="0" cellpadding="10" style="width:100%; max-width:760px; margin:0 auto;border:1px solid #cecece; font-family: Arial, sans-serif; font-size:12px;">
			<thead>
        		<th style="text-align:left; background-color:#EBE9E9;">Summary of Sold Items</th>
        	</thead>
        	<tbody>
				<tr>
					<td>
						<span style="display:inline-block;font-weight:bold;width:100px;">Transaction # :</span> {invoice_no} <br>
						<span style="display:inline-block;font-weight:bold;width:100px;">Date :</span> {dateadded} <br>
						<span style="display:inline-block;font-weight:bold;width:100px;">Net Amount :</span> Php {totalprice}<br>
						<span style="display:inline-block;font-weight:bold;width:100px;">Sold to :</span> {buyer_name} <br>
						<span style="display:inline-block;font-weight:bold;width:100px;margin-left:2em;">State/Region :</span> {stateregion} <br>
						<span style="display:inline-block;font-weight:bold;width:100px;margin-left:2em;">City :</span> {city} <br>
						<span style="display:inline-block;font-weight:bold;width:100px;margin-left:2em;">Address :</span> {address} <br>
						<span style="display:inline-block;font-weight:bold;width:100px;margin-left:2em;">Contact# :</span> {seller_contactno} <br><br>
					</td>
				</tr>
				<tr>
					<td>
						<strong>SOLD ITEMS:</strong>
					</td>
				</tr>
				{products}
				<tr>
					<td>
						<table style="font-size:12px; border-bottom:1px solid #cecece;width:100%;">
				            <tbody>
				              <tr>
				                <td>
									<span style="display:inline-block;font-weight:bold;width:170px;">Product Order #: </span>{order_product_id} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Product :</span> {name} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Base Price :</span> Php {baseprice} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Qty :</span> {order_quantity} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Handling Fee :</span> Php {handling_fee} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Product Final Price :</span> Php {finalprice} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Deductions :</span><br>
									<span style="display:inline-block;font-weight:bold;width:170px;margin-left:2em;">Easyshop charge :</span> Php {easyshop_charge} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;margin-left:2em;">Payment Method charge :</span> Php {payment_method_charge} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Net Amount to be received:</span> Php {net} <br>
									<span style="display:inline-block;font-weight:bold;width:170px;">Product Specifications:</span> <br>
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
			</tbody>
		</table>
	</body>
</html>
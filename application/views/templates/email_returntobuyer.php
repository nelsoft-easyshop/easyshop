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
						<span style="color:#f18200;font-weight:bold;">{user}</span> has just confirmed to return your payment for the following product:
					</td>
				</tr>
				<tr>
					<td width="120px"><strong>Transaction # :</strong> </td>
					<td colspan="2">{order_id}</td>
				</tr>
				<tr>
					<td><strong>Product Order # :</strong> </td>
					<td colspan="2">{order_product_id}</td>
				</tr>
				<tr>
					<td><strong>Product Name :</strong> </td>
					<td colspan="2"><a href="{product_link}" target="_blank">{name}</a></td>
				</tr>
				<tr>
					<td><strong>Quantity :</strong> </td>
					<td colspan="2">{order_quantity}</td>
				</tr>
				<tr>
					<td><strong>Price :</strong> </td>
					<td colspan="2">Php {price}</td>
				</tr>
				<tr>
					<td><strong>Product Attributes</strong></td>
					{attr}
						<td>{field} : </td>
						<td>{value}</td>
					{/attr}
				</tr>
			</tbody>
	</body>
</html>
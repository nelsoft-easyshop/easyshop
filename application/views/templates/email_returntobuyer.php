<html>
	<body>
		<table cellspacing="0" cellpadding="10" style="width:100%; max-width:780px; margin:0px auto; border-top:5px solid #f18200; 
		border-left:1px solid #f18200;border-right:1px solid #f18200;border-bottom:1px solid #f18200; font-family:arial; 
		font-size:12px;">
			<thead>
		 		<tr>
		 			<td colspan="3">
		 				<a href="" style="padding:10px 0; display:inline-block">
		 					<img src="img_logo.png" alt="Easyshop.ph">
		 				</a>
		 			</td>
		 		</tr>
		 	</thead>
		 	<tbody>
				<tr>
					<td colspan="3">
						{user} has just confirmed to return your payment for the following product:
					</td>
				</tr>
				<tr>
					<td width="100px">Transaction # : </td>
					<td colspan="2">{order_id}</td>
				</tr>
				<tr>
					<td>Product Order # : </td>
					<td colspan="2">{order_product_id}</td>
				</tr>
				<tr>
					<td>Product Name : </td>
					<td colspan="2"><a href="{product_link}" target="_blank">{name}</a></td>
				</tr>
				<tr>
					<td>Qty : </td>
					<td colspan="2">{order_quantity}</td>
				</tr>
				<tr>
					<td>Price : Php</td>
					<td colspan="2">{price}</td>
				</tr>
				<tr>
					<td>Product Attributes</td>
					{attr}
						<td>{field} : </td>
						<td>{value}</td>
					{/attr}
				</tr>
			</tbody>
	</body>
</html>
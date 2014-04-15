<html>
	<body>
		<table>
			<tr>
				<td>
					{user} has just confirmed to return your payment for the following product:
				</td>
			</tr>
			<tr>
				<td>Transaction # : </td>
				<td>{order_id}</td>
			</tr>
			<tr>
				<td>Product Order # : </td>
				<td>{order_product_id}</td>
			</tr>
			<tr>
				<td>Product Name : </td>
				<td><a href="{product_link}" target="_blank">{name}</a></td>
			</tr>
			<tr>
				<td>Qty : </td>
				<td>{order_quantity}</td>
			</tr>
			<tr>
				<td>Price : Php</td>
				<td>{price}</td>
			</tr>
			<tr>
				<td>Product Attributes</td>
				{attr}
					<td>{field} : </td>
					<td>{value}</td>
				{/attr}
			</tr>
	</body>
</html>
<html>
	<body>
		<h2 style="color:red;">Thank you for purchasing from Easyshop.ph</h2>
		<p>
			Dear {buyer_name},
		</p>
		<p>
			Thank you for purchasing through Easyshop.ph! Below are the details for your transaction.
		</p>
		<table style="border:1px solid black;">
			<th>Purchase Summary</th>
			<tr>
				<td>
					Transaction # : {id_order} <br>
					Date : {dateadded} <br>
					Total Price : {totalprice}<br><br>
				</td>
			</tr>
			<tr>
				<td>
					PURCHASED ITEMS:<br>
				</td>
			</tr>
			{products}
			<tr>
				<td>
					Sold by : {seller} <br>
					Product : {name} <br>
					Qty : {order_quantity} <br>
					Base Price : {baseprice} <br>
					Tax : {tax} <br>
					Final Price : Php {finalprice} <br>
					Product Specifications: <br>
					{attr}
						{attr_name} : {attr_value} <br>
					{/attr} 
					<br>
				</td>
			</tr>
			{/products}
		</table>
	</body>
</html>
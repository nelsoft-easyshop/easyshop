<html>
	<body>
		<h2 style="color:red;">{buyer_name} purchased from you!</h2>
		<p>
			Dear {seller_name},
		</p>
		<p>
			Thank you for selling through Easyshop.ph! Below are the transaction details for the item you sold.
		</p>
		<table style="border:1px solid black;">
			<th>Summary of Sold Items</th>
			<tr>
				<td>
					Transaction # : {id_order} <br>
					Date : {dateadded} <br>
					Total Price : {totalprice}<br><br>
				</td>
			</tr>
			<tr>
				<td>
					SOLD ITEMS:<br>	
				</td>
			</tr>
			{products}
			<tr>
				<td>
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
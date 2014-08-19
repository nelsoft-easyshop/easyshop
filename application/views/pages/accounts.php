<html>
<head>
	<title></title>
	
</head>
<body>	

<hr>
	<?php 

		$data = array("id" => "formid");
		echo "<b>getProdCount:</b><br/>";
		echo form_open("webservice/accountservice/getproductcount",$data);
	
		echo "ID:".form_input('id','0')."<br/>";
		echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
		
		echo form_submit('submit','Post',"id=submit");
		echo form_close();
	?>

<hr>
<hr>
	<?php 

		$data = array("id" => "formid");
		echo "<b>getAccounts:</b><br/>";
		echo form_open("webservice/accountservice/getusercount",$data);
		
		echo form_submit('submit','Post',"id=submit");
		echo form_close();
	?>

<hr>

</body>
</html>	



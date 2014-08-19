<html>
<head>
	<title></title>
	
</head>
<body>		
	
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>addSectionProduct:</b><br/>";
	echo form_open("homewebservice/addsectionproduct",$data);

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Product Index:".form_input('productindex','0')."<br/>";
	echo "Type:".form_input('type','')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	
	<?php 

	$data = array("id" => "formid");
	echo "<b>GetNode:</b><br/>";
	echo form_open("homewebservice/getnode",$data);
	echo "Node Name:".form_input('nodename','productSlide')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Index:".form_input('index','')."<br/>";
	echo "Inner Node:".form_input('innernode','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>SetText:</b><br/>";
	echo form_open("homewebservice/settext",$data);
	
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>SetMainSlide:</b><br/>";
	echo form_open("homewebservice/setmainslide",$data);
	
	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Coordinate:".form_input('coordinate','')."<br/>";
	echo "target:".form_input('target','')."<br/>";
	echo "order:".form_input('order','')."<br/>";
	echo form_hidden('nodeName','mainSlide')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>
	<hr>

	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>AddMainSlide:</b><br/>";
	echo form_open("homewebservice/addmainslide",$data);
	
	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Coordinate:".form_input('coordinate','')."<br/>";
	echo "target:".form_input('target','')."<br/>";
	echo form_hidden('nodeName','mainSlide')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>SetProductSlideTitle:</b><br/>";
	echo form_open("homewebservice/setproducttitle",$data);
	
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
		<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>SetProductSideBanner:</b><br/>";
	echo form_open("webservice/homewebservice/setproductsidebanner",$data);
	
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>SetSectionHead:</b><br/>";
	echo form_open("homewebservice/setsectionhead",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Type:".form_input('type','')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Css_class:".form_input('css_class','')."<br/>";
	echo "Title:".form_input('title','')."<br/>";
	echo "Layout:".form_input('layout','')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>setSectionProduct:</b><br/>";
	echo form_open("homewebservice/setsectionproduct",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Product Index:".form_input('productindex','0')."<br/>";
	echo "Type:".form_input('type','')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>addSectionProduct:</b><br/>";
	echo form_open("homewebservice/addsectionproduct",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Product Index:".form_input('productindex','0')."<br/>";
	echo "Type:".form_input('type','')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
		<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>addMainSlide:</b><br/>";
	echo form_open("homewebservice/addmainslide",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Coordinate:".form_input('coordinate','')."<br/>";
	echo "target:".form_input('target','')."<br/>";
	//echo "order:".form_input('order','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
			<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>SetProductSlide:</b><br/>";
	echo form_open("homewebservice/setproductslide",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Order:".form_input('order','')."<br/>";
	echo form_hidden('nodeName','productSlide')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>AddProductSlide:</b><br/>";
	echo form_open("homewebservice/addproductslide",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	
	echo form_hidden('nodeName','productSlide')."<br/>";
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
	<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>setSectionMainPanel:</b><br/>";
	echo form_open("homewebservice/setsectionmainpanel",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Product Index:".form_input('productindex','0')."<br/>";
	echo "Type:".form_input('type','')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Coordinate:".form_input('coordinate','')."<br/>";
	echo "Target:".form_input('target','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>
		<hr>
	<?php 

	$data = array("id" => "formid");
	echo "<b>addSectionMainPanel:</b><br/>";
	echo form_open("homewebservice/addsectionmainpanel",$data);
	

	echo "Index:".form_input('index','0')."<br/>";
	echo "User ID:".form_input('userid','1')."<br/>";
	echo "Hash:".form_input('hash','100BF27B6F9B7BB4B789C0410856F0776E7661EE*')."<br/>";
	echo "Product Index:".form_input('productindex','0')."<br/>";
	echo "Type:".form_input('type','')."<br/>";
	echo "Value:".form_input('value','')."<br/>";
	echo "Coordinate:".form_input('coordinate','')."<br/>";
	echo "Target:".form_input('target','')."<br/>";
	
	echo form_submit('submit','Post',"id=submit");
	echo form_close();
	?>

	<hr>

</body>
</html>	




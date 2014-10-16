<html>
<head>
    <title></title>
</head>
<body>

<h3>brandSection</h3>
<form  method="get" action="newhomewebservice/setBrandSection" >
    Index: <input type="text" name="index"><br/>
    brandId: <input type="text" name="brandId"><br/>
    <input type="submit" name="Submit"><br/>

</form>
<h3>otherCategories</h3>
<form  method="get" action="newhomewebservice/otherCategories" >
    Index: <input type="text" name="index"><br/>
    categorySlug: <input type="text" name="categorySlug"><br/>
    <input type="submit" name="Submit"><br/>

</form>

<h3>setSellerSectionProductPanell</h3>
<form  method="get" action="newhomewebservice/setSellerSectionProductPanel" >
    Index: <input type="text" name="index"><br/>
    Slug: <input type="text" name="slug"><br/>
    <input type="submit" name="Submit"><br/>

</form>

<h3>Set Product Panel</h3>
<form  method="get" action="newhomewebservice/setProductPanel" >
    Index: <input type="text" name="index"><br/>
    Product Panel Index: <input type="text" name="productPanelIndex"><br/>    
    Slug: <input type="text" name="slug"><br/>
    <input type="submit" name="Submit"><br/>

</form>

<h3>Set Category Section NAV</h3>
<form  method="get" action="newhomewebservice/categorySectionHead" >
    Index: <input type="text" name="index"><br/>
    categorySlug: <input type="text" name="categorySlug"><br/>
    Sub Index: <input type="text" name="subIndex"><br/>    
    Sub Value: <input type="text" name="sub"><br/>
    Sub Target: <input type="text" name="target"><br/>
    <input type="submit" name="Submit"><br/>

</form>

<h3>Add Slider</h3>
<form  enctype="multipart/form-data"  method="post" action="newhomewebservice/addSliderSection" >
    File: <input type="file" name="myfile"><br/>
    Index: <input type="text" name="index"><br/>
    Target: <input type="text" name="target"><br/>
    <input type="submit" name="Submit"><br/>

</form>
<h3>Set Categories</h3>
<form method="get" action="newhomewebservice/setMainCategories">
    Index: <input type="text" name="index"><br/>
    Value: <input type="text" name="value"><br/>
    <input type="submit" name="Submit"><br/>

</form>

<h3>Set Sub Categories</h3>
<form method="get" action="newhomewebservice/setSubCategories">
    Index: <input type="text" name="index"><br/>
    Sub Index: <input type="text" name="subIndex"><br/>
    Value: <input type="text" name="value"><br/>
    <input type="submit" name="Submit"><br/>

</form>
<h3>Add Sub Categories</h3>
<form method="get" action="newhomewebservice/addSubCategories">
    Index: <input type="text" name="index"><br/>
    Value: <input type="text" name="value"><br/>
    <input type="submit" name="Submit"><br/>

</form>
<h3>Set Other Categories</h3>
<form method="get" action="newhomewebservice/setOtherCategories">
    Index: <input type="text" name="index"><br/>
    Value: <input type="text" name="value"><br/>
    <input type="submit" name="Submit"><br/>

</form>



</body>
</html>
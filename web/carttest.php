<html>


<?php $cartStub =  [[
        "quantity" => 1,
        "id" => "ubuntu-valid-gd",
        "details" => ['mapAttributes' => ['color' => ["id" => "b_9", "value" => "green", "isSelected" => true, "isAvailable" => true]]],
        "isAvailable" => 1
    ]];
    

?>



<form method="post" action="/mobile/cart/persist">
    <input type="text" value='<?php echo json_encode($cartStub) ?>' name="cartData"/>
    <input type="hidden" value="omaha" name="skey"/>
    <input type="hidden" value="1" name="memberId"/>
    <input type='submit'/>
</form>


<form method="post" action="/mobile/cart/getCartData">
    <input type="text" value='1' name="memberId"/>
    <input type="hidden" value="omaha" name="skey"/>
    <input type='submit'/>
</form>
</html>
<html style="font-family: Century Gothic, Calibri">
    <body>
        <div style="">
            <div style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent; border-radius: 4px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05); box-shadow: 0 1px 1px rgba(0, 0, 0, .05); border-color: #bce8f1; border-radius: 0px !important;">
                
                <div style="padding: 10px 15px; border-bottom: 1px solid transparent; color: #31708f;background-color: #d9edf7; border-color: #bce8f1; font-weight: bold;">Summary of Sold Items</div>
                <div style="padding: 15px;">
               
                   <div style="min-height: 500px; display: static">
                        <center>
                            <div>
                                
                                <p style="color: #fff; background-color: #428bca; padding:10px; font-weight: bold;">
                                    TRANSACTION DETAILS
                                </p>
                                <br/>

                                <table style="font-size: 14px;">
                                    <thead>
                                        <tr style='text-align:center;'>
                                            <th>
                                                Transaction Number: 
                                            </th>
                                            <th>
                                                Product Name
                                            </th>
                                            <th>
                                                Buyer Name
                                            </th>   
                                            <th>
                                                Product Specifications
                                            </th>                                                                                       
                                            <th>
                                                Date of Transaction
                                            </th>
                                            <th>
                                                Order Quantity
                                            </th>
                                            <th >
                                                Payment Method
                                            </th>                                            
                                            <th>
                                                Price
                                            </th>   
                                        </tr>

                                    </thead>
                                    <tr>
                                      <?php foreach($transactions as $value): ?>
                                        <tr style="text-align:center;border: black 1px solid;">

                                            <td>
                                                <?php echo $value["invoiceNo"] ?>
                                            </td>
                                            <td>
                                               <?php echo html_escape($value["productname"]) ?>
                                            </td>
                                            <td>
                                               <?php echo ucwords(strtolower(html_escape($value["buyerStoreName"]))) ?>
                                            </td>  
                                            <td style='text-align:left;padding-left:2px;'>
                                                <?php foreach($value["product"] as $product): ?>
                                                    <?php if(isset($product["attr"]) && count($product["attr"] > 0)): ?>
                                                            <?php foreach($product["attr"] as $attr => $attrValue ): ?>
                                                                <?php echo ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue))."<br/>" ?>
                                                            <?php endforeach; ?>
                                                    <?php endif; ?>
                                                 <?php endforeach; ?>
                                            </td>                                                                                          
                                            <td>
                                                <?php print_r($value["dateadded"]->format('Y-m-d H:i:s')); ?>
                                            </td>                                        
                                            <td >
                                                <?php echo $value["orderQuantity"] ?>
                                            </td>                                                                             
                                            <td >
                                               <?php echo ucwords(strtolower($value["paymentMethod"])) ?>
                                            </td>   
                                            <td >
                                               PHP <?php echo number_format((float)$value["totalOrderProduct"], 2, '.', '')  ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tr>
                                </table>
                                <br/>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
            
            <div style="background: #3c475c; width: 100%; min-height: 100px; border-width:0px 0px 5px 0px; border-style:solid; border-color:#f18200; padding-top: 15px; padding-bottom: 15px;">
                <div style="min-height: 50px;">
                    <center>
                        <table>
                            <tr>
                                <td>
                                    <a href="https://www.facebook.com/EasyShopPhilippines">
                                        <img src="/assets/images/landingpage/templates/facebook.png" style="display: block; max-width: 100%; height: auto;" />
                                    </a>
                                </td>
                                <td>
                                    <a href="https://twitter.com/EasyShopPH">
                                        <img src="/assets/images/landingpage/templates/twitter.png" style="display: block; max-width: 100%; height: auto;" />
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </center>
                </div>
                <div>
                    <center>
                    <p style="color: #ffffff; text-align:center; margin-top:5px; font-size: 12px;">
                    Copyright &copy; 2014 Easyshop.ph
                    </p>
                    </center>
                </div>
            </div>
        </div>
    </body>
</html>
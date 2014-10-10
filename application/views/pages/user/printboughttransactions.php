
        <div style="">
            <div style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent; border-radius: 4px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05); box-shadow: 0 1px 1px rgba(0, 0, 0, .05); border-color: #bce8f1; border-radius: 0px !important;">
                
                <div style="padding: 10px 15px; border-bottom: 1px solid transparent; color: #31708f;background-color: #d9edf7; border-color: #bce8f1; font-weight: bold;">Summary of Bought Items</div>
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
                                        <tr>
                                            <th>
                                                Transaction Number: 
                                            </th>
                                            <th>
                                                Product Name
                                            </th>
                                            <th>
                                                Date of Transaction
                                            </th>
                                            <th>
                                                Order Quantity
                                            </th>
                                            <th>
                                                Payment Method
                                            </th>                                            
                                            <th>
                                                Price
                                            </th> 
                                            <th>
                                                Product Specifications
                                            </th>                                              

                                        </tr>

                                    </thead>
                                    <tr style='text-align:center;'>
                                      <?php foreach($transactions as $key => $value): ?>
                                        <tr style="text-align:center;border: black 1px solid;">
                                            <td>
                                                <?php echo $value["invoiceNo"]; ?>
                                            </td>
                                            <td>
                                                <?php echo $value["productname"]; ?>
                                            </td>
                                            <td>
                                                <?php print_r($value["dateadded"]->format('Y-m-d H:i:s')); ?>
                                            </td>                                        
                                            <td>
                                                <?php echo $value["orderQuantity"]; ?>
                                            </td>     
                                            <td >
                                                <?php echo ucwords(strtolower($value["paymentMethod"])); ?>
                                            </td>                                                                                     
                                            <td >
                                                PHP <?php echo number_format((float)$value["total"], 2, '.', '') ?>
                                            </td> 
                                            <td>
                                                <?php if(isset($value["0"])): ?>
                                                        <?php foreach($value["0"]["attributes"] as $attr): ?>
                                                            <?php echo ucwords($attr["attrName"]).":".ucwords($attr["attrValue"])."<br/>" ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <?php echo "N/A" ?>
                                                <?php endif; ?>
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
                <div>
                    <center>
                    <p style="color: #ffffff; text-align:center; margin-top:5px; font-size: 12px;">
                    Copyright &copy; 2014 Easyshop.ph
                    </p>
                    </center>
                </div>
            </div>
        </div>

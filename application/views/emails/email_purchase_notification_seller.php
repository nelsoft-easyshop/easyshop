<html style="font-family: Century Gothic, Calibri">
    <body>
        <div style="padding-right: 15px; padding-left:15px; margin-right: auto; margin-left:auto;">
            <div style="background-color: #013f94;">
                <center>
                    <img src="header-img.png" style="display: block; max-width: 100%; height: auto;">
                </center>
             </div>
             <br/>
            <div>
                <p>
                    <b style="font-weight:bold"><span style="color: #f18200;">{buyer_store}</span> purchased from you!</b>
                    <!--FOR FULL WIDTH SCREEN SIZE-->
                   
                </p>
                <p style="margin-top:-10px; margin-bottom:-20px;">
                    <!--FOR MOBILE SCREEN SIZE-->
                    <span>
                        <a href="{store_link}" style="text-decoration: none;">
                            <img src="appbar.home.png" width="20" height="20"/>
                        </a>
                    </span>
                    <span>
                        <a href="{msg_link}" style="text-decoration: none;">
                            <img src="appbar.message.png" width="20" height="20"/>
                        </a>
                    </span>
                </p>
            </div>
            <div style="margin-top: 20px; font-size: 13px;">
                
                <p>
                    Dear {seller_store},
                </p>
                
                 <p>
                    {payment_msg_seller}
                    <br/>
                    Thank you for selling through Easyshop.ph! Details for the item(s) you've sold are as follows:
                 </p>
            </div>
            
            <div style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent; border-radius: 4px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05); box-shadow: 0 1px 1px rgba(0, 0, 0, .05); border-color: #bce8f1; border-radius: 0px !important;">
                
                <div style="padding: 10px 15px; border-bottom: 1px solid transparent; color: #31708f;background-color: #d9edf7; border-color: #bce8f1; font-weight: bold;">Summary of Sold Items</div>
                <div style="padding: 15px;">
               
                   <div style="min-height: 500px; display: static">
                        <center>
                            <div style=" width: 83.33333333333334%;">
                                
                                <p style="color: #fff; background-color: #428bca; padding:10px; font-weight: bold;">
                                    TRANSACTION DETAILS
                                </p>
                                <table style="font-size: 14px;">
                                    <tr>
                                        <td align="left"  style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            Transaction Number: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px !important; width:20%;">
                                            {invoice_no}
                                        </td>
                                        <td align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            State/Region: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {stateregion}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            Sold To: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {buyer_store} 
                                        </td>
                                        <td align="left" align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            City: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {city}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            Date: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {dateadded}
                                        </td>
                                        <td align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            Street Address: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {address}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            Contact Number: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {buyer_contactno}
                                        </td>
                                        <td align="left" style="font-weight:bold; padding-bottom:15px !important; width:20%;">
                                            Telephone: 
                                        </td>
                                        <td align="left" style=" padding-left: 20px !important; padding-bottom:15px; width:20%;">
                                            {buyer_telephone}
                                        </td>
                                    </tr>
                                </table>
                                <br/>
                                <p style="color: #fff; background-color: #428bca; padding:10px; font-weight: bold; text-align: center;">
                                    SOLD ITEMS
                                </p>
                                <table border='0' width="100%">
                                    
                                    <tr>
                                        <td style="padding: 10px; vertical-align: top; width:50%">
                                        
                                        <div style='overflow-y:scroll; overflow: scroll; min-height:260px; max-height: 290px; width: 100%; padding-right: 7px; '>
                                            {products}
                                            
                                            <table width="100%" style="border-collapse: collapse; font-size: 12px;">
                                            <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Product Order Number: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        {order_product_id}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Product Name: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        {name}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Quantity: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        {order_quantity}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Base Price: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        PHP {baseprice}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                    Handling Fee: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                    PHP {handling_fee}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                    Product Final Price: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px; padding-right:7px;" align="right">
                                                        PHP {finalprice}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight:bold; vertical-align: top;" colspan="2">
                                                    PRODUCT SPECIFICATIONS
                                                    </td>
                                                    
                                                </tr>
                                                {attr}
                                                <tr>
                                                    <td style="font-weight:bold; text-transform: capitalize;padding-left:10px; width: 70px;" colspan="2">
                                                        <span>{attr_name}: </span> 
                                                        <span  style="font-weight: normal">{attr_value}</span>
                                                    </td>
                                                </tr>
                                                {/attr}
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td colspan='2'>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight:bold">
                                                        DEDUCTIONS
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight:bold;">
                                                    Easyshop Charge: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px; padding-right:127px;" align="right">
                                                    - PHP {easyshop_charge}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight:bold;">
                                                    {payment_method_name} charge: 
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px; padding-right:127px;" align="right">
                                                    - PHP {payment_method_charge}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight:bold;">
                                                    Net Amount to be Received:
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px; padding-right:7px;" align="right">
                                                    PHP {net}
                                                    </td>
                                                </tr>
                                            </table>
                                            <hr/>
                                            {/products}

                                        </div>
                                        </td>

                                    </tr>
                                </table>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
            
            <div style="background: #3c475c; width: 100%; min-height: 100px; border-width:0px 0px 5px 0px; border-style:solid; border-color:#f18200; padding-top: 15px; padding-bottom: 15px;">
                <div style="min-height: 50px;">
                    <center>
                        <table width="100%">
                            <tr>
                                <td>
                                    <a href="{facebook}">
                                        <img src="facebook.png" style="float: right; display: block; max-width: 100%; height: auto;" />
                                    </a>
                                </td>
                                <td>
                                    <a href="{twitter}">
                                        <img src="twitter.png" style="display: block; max-width: 100%; height: auto;" />
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

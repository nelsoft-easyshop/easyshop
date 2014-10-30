<style>
    @import url(https://fonts.googleapis.com/css?family=Roboto:400,300);
</style>
<html style="font-family: 'Roboto', Arial Century Gothic, Calibri">
    <body>
        <div style="padding-right: 15px; padding-left:15px; margin-right: auto; margin-left:auto;">
            <div style="background-color: #013f94;">
                <center>
                    <a href="https://www.easyshop.ph">
                        <img src="header-img.png" style="display: block; max-width: 100%; height: auto;" alt="EasyShop.ph">
                    </a>
                </center>
             </div>
             <br/>
            <div>
                <p style="margin-top:-10px; margin-bottom:-20px;">
                    <!--FOR MOBILE SCREEN SIZE-->
                    <span>
                        <a href="{store_link}">
                            <img src="appbar.home.png" width="30" height="30">
                        </a>
                    </span>
                    <span>
                        <a href="{msg_link}">
                            <img src="appbar.message.png" width="30" height="30">
                        </a>
                    </span>
                </p>
            </div>
            <div style="margin-top: 20px; font-size: 13px;">
                <p>
                    Dear {recipient},
                </p>
                 <p>
                    <b style="font-weight:bold"><span style="color: #f18200;">{user}</span> has just completed your Cash on Delivery Transaction
                 </p>
            </div>
            <div style="margin-bottom: 20px;background-color: #fff;border: 1px solid transparent; border-radius: 4px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05); box-shadow: 0 1px 1px rgba(0, 0, 0, .05); border-color: #bce8f1; border-radius: 0px !important;">
                <div style="padding: 10px 15px; border-bottom: 1px solid transparent; color: #31708f;background-color: #d9edf7; border-color: #bce8f1; font-weight: bold;">Summary of Returned Payment</div>
                <div style="padding: 15px;">
                   <div style="min-height: 500px; display: static">
                        <center>
                            <div style=" width: 83.33333333333334%;">
                                <table border='0' width="100%">
                                <tr>
                                    <td style="padding: 10px; vertical-align: top; width:50%">
                                        <div style='overflow-y:scroll;overflow-x:no-scroll; min-height:260px; max-height: 290px; width: 100%; padding-right: 7px; '>
                                            <table width="100%" style="border-collapse: collapse; font-size: 12px;">
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Transaction # :
                                                    </td>
                                                     <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                         {invoice_no}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Product Order # :
                                                    </td>
                                                     <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        {id_order_product}
                                                    </td>
                                                </tr>
                                                 <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Product Name :
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        {product_name}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Base Price :
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        Php {price}
                                                    </td>
                                                </tr>
                                                 <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Quantity :
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        {order_quantity}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                       Handling Fee :
                                                    </td>
                                                    <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px;  padding-top:10px; padding-right:7px; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;" align="right ">
                                                        Php {handling_fee}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                       Product Total Price :
                                                    </td>
                                                     <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px; padding-right:7px;" align="right">
                                                        Php {total}
                                                    </td>
                                                </tr>
                                                <tr style="border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted;">
                                                    <td align="left" style="font-weight:bold; border-color: #ADAD85; border-width: 0px 0px 1px 0px; border-style:dotted; padding-top:10px;">
                                                        Payment Method :
                                                    </td>
                                                     <td style="padding-left: 20px !important; text-align:right !important; padding-bottom:5px; padding-right:7px;" align="right">
                                                        {payment_method_name}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="font-weight:bold; vertical-align: top;" colspan="2">
                                                    Product Attributes : 
                                                    </td>
                                                </tr>
                                                {attr}
                                                <tr>
                                                    <td align="left" style="font-weight:bold; text-transform: capitalize;padding-left:10px; width: 70px;" colspan="2">
                                                        <span>{field} : </span>
                                                        <span  style="font-weight: normal">{value}</span>
                                                    </td>
                                                </tr>
                                                {/attr}
                                            </table>
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
                                    <a href="https://www.facebook.com/EasyShopPhilippines">
                                        <img src="facebook.png" style="float: right; text-align:center; display: block; max-width: 100%; height: auto;" />
                                    </a>
                                </td>
                                <td>
                                    <a href="https://twitter.com/EasyShopPH">
                                        <img src="twitter.png" style="text-align:center; display: block; max-width: 100%; height: auto;" />
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

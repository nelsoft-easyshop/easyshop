<link type="text/css" href="/assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<div class="wrapper2">

    <div class="clear"></div>

    <div class="seller_product_content">
        
        <div class="inner_seller_product_content">
            <h2 class="f24">Sell an Item</h2>
            <div class="sell_steps sell_steps1">
                <ul>
                    <li style="color:#FFFFFF">Step 1 : Select Category</li>
                    <li>Step 2 : Upload Item</li>             
                    <li>Step 3: Select Shipping Courier</li>
                    <li>Step 4: Success</li> 
                </ul>
            </div>

            <div class="clear"></div>
            <div class="add_product_category">
               <br/><br/>
               <p><h2>Verify your e-mail address to carry out a transaction.</h2></p>
               <p><a href = "/me?me=myinfo">You may also resend the verification e-mail by going to your member page.</a></p>
               <br/><br/><br/><br/><br/><br/>
            </div>

            <div class="clear"></div>
            <!--<form action="<?php echo '/productUpload/step2'; ?>" method="POST">-->
            <?php echo form_open('productUpload/step2');?>

                <div class="add_category_submit"> <input type="hidden" name="hidden_attribute" class="hidden_attribute"><input class="proceed_form" id="proceed_form" type="submit" value="Proceed"></div>
            <?php echo form_close();?>
        </div>
    </div>

    <div class="clear"></div>  

    <script>
            $(document).ready(function() {
                $("#box").unbind("click").click(function() {  // this function is for searching item on the list box every category
                    $('#box').keyup(function() {
                        var valThis = $(this).val().toLowerCase();
                        $('.navList>li').each(function() {
                            var text = $(this).text().toLowerCase();
                            (!text.contains(valThis) == 0) ? $(this).show() : $(this).hide();
                        });
                    });
                });

                $(".add_category_submit").hide();
                $(".select").unbind("click").click(function() { // requesting the child category from selected first level parent category

                    $(".add_category_submit").hide();
                    var D = eval('(' + $(this).attr('data') + ')');
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: '/' + D.action,
                        data: "cat_id=" + D.cat_id + "&level=" + D.level,
                        dataType: "json",
                        beforeSend: function(jqxhr, settings) {
                            $(".product_sub_items0").nextAll().remove();
                            $(".product_sub_items0").remove();
                        },
                        success: function(d) {
                            $(d).appendTo($('.product_sub_category'));
                        }
                    });
                });
            });
    </script>


    <script>
        $(document).ready(function() { // make the category selected highlighted
            $('.navList li a').on('click', function() {
                $(this).addClass('active').parent().siblings().children('a').removeClass('active');
            });
        });
    </script>
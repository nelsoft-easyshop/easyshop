<script>
    $(document).ready(function() {


        $('#box<?php echo $level; ?>').keyup(function() {  // this function is for searching item on the list box every category
            var valThis = $(this).val().toLowerCase();
            $('.navList<?php echo $level; ?>>li').each(function() {
                var text = $(this).text().toLowerCase();
                (!text.contains(valThis) == 0) ? $(this).show() : $(this).hide();
            });
        });


        $(".select2").unbind("click").click(function() { // requesting the child category from selected category
            var D = eval('(' + $(this).attr('data') + ')');
            var nlevel = parseInt(D.level) + 1;
             var action = 'productUpload/getChild';
            $(".add_category_submit").hide();
            $(".product_sub_items" + parseInt(D.level) + 1).remove();
            $(".product_sub_items" + nlevel).nextAll().remove();
            $.ajax({
                async: false,
                type: "POST",
                url: '<?php echo base_url(); ?>' +  action,
                data: "cat_id=" + D.cat_id + "&level=" + nlevel,
                dataType: "json",
                beforeSend: function(jqxhr, settings) {
                    $(".product_sub_items" + nlevel).remove();
                    $(".product_sub_items" + parseInt(D.level) + 1).remove();
                    $(".product_sub_items" + nlevel).nextAll().remove();
                },
                success: function(d) {
                    $(".product_sub_category").append(d);
                }
            });
        });



    });
</script>
<script type="text/javascript">
    (function($) {  // generating the carousel for better view
        $(function() {
            $('.jcarousel').jcarousel();

            $('.jcarousel-control-prev')
                    .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
                    .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
                    .jcarouselControl({
                target: '-=1'
            });

            $('.jcarousel-control-next')
                    .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
                    .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
                    .jcarouselControl({
                target: '+=1'
            });

            $('.jcarousel-pagination')
                    .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
                    .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
                    .jcarouselPagination();
        });
    })(jQuery);
</script>
<script>
    $(document).ready(function() { // make the category selected highlighted
        $('.product-list li a').on('click', function() {
            $(this).addClass('active').parent().siblings().children('a').removeClass('active');
        });
    });
</script>


<?php
if (empty($node)) { # if no more available item on selected category the button procedd will show 
    ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".add_category_submit").show();
            $('#proceed_form').attr('data', <?php echo $cat_id; ?>);
            $('.hidden_attribute').val(<?php echo $cat_id ?>);

        });     
    </script>
    <?php
} else {  # if there is more available item on selected category the list will generate
    ?>
    <div class='product_sub_items<?php echo $level; ?>'>
        <input type="text" class="box<?php echo $level; ?>" id="box<?php echo $level; ?>">
        <ul class="product-list navList<?php echo $level; ?>" style="list-style-type:none">
            <?php
            foreach ($node as $row) { # generating all child category base on selected parent category from product_upload_step3_view
                ?>

                <li  class="<?php echo $row['parent_id']; ?>"><a href="javascript:void(0)" data="{cat_id:'<?php echo $row['id_cat']; ?>',level:'<?php echo $level ?>'}" class="select2"><?php echo $row['name']; ?></a></li>

                <?php }
            ?>
        </ul>
    <?php
    }
    ?>
</div>


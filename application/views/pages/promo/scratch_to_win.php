<link rel="stylesheet" href="<?= base_url() ?>assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" type="text/css"  media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/style_new.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.bxslider.css?ver=<?=ES_FILE_VERSION?>" type="text/css" media="screen"/>


<div class="clear"></div>


<div class="wrapper" id="main_search_container">

    <?php echo $deals_banner; ?>

    <div id="scratch-win">

        <input type="text" id="scratch_txt">

        <button id="send_btn">Submit</button>

    </div>

</div>

<script src="<?=base_url()?>assets/js/src/vendor/jquery.plugin.min.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/src/vendor/jquery.countdown.min.js" type="text/javascript"></script>
<script>
    (function($){
        $(document).ready(function(){
            var base_url = config.base_url;
            var offset = 1;
            var request_ajax = true;
            var ajax_is_on = false;
            var objHeight = $(window).height() - 50;
            var last_scroll_top = 0;
            var csrftoken = $("meta[name='csrf-token']").attr('content');
            var csrfname = $("meta[name='csrf-name']").attr('content');

            $(window).scroll(function(event) {
                var st = $(this).scrollTop();
                if(st > last_scroll_top){
                    if ($(window).scrollTop() + 100 > $(document).height() - $(window).height()) {
                        if (request_ajax === true && ajax_is_on === false) {
                            ajax_is_on = true;
                            $.ajax({
                                url: base_url + 'deals_more',
                                data:{page_number:offset,csrfname : csrftoken},
                                type: 'post',
                                dataType: 'JSON',
                                onLoading:jQuery(".loading_products").html('<img src="<?= base_url() ?>assets/images/orange_loader.gif" />').show(),
                                success: function(d) {
                                    if(d == "0"){
                                        ajax_is_on = true;
                                    }else{
                                        $($.parseHTML(d.trim())).appendTo($('.product_list'));
                                        ajax_is_on = false;
                                        offset += 1;
                                    }
                                    jQuery(".loading_products").fadeOut();
                                }
                            });
                        }
                    }
                }
                last_scroll_top = st;
            });

            $(document).on('click', '#send_btn', function()
            {
                var code = $('#scratch_txt').val().trim();
                if(code === ""){
                    alert('Invalid content');
                    return false;
                }

                $.ajax({
                    url : 'promo/validateScratchCardCode',
                    dataType : 'JSON',
                    type : 'POST',
                    data :{
                        csrfname : csrftoken,
                        code : code
                    },
                    success:function(data){

                    }
                });
            });
        })
    })(jQuery)
</script>

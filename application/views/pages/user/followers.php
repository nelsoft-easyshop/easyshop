<link type="text/css" href='/assets/css/contact.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<link type="text/css" href='/assets/css/followers.css?ver=<?=ES_FILE_VERSION?>' rel="stylesheet" media='screen'/>
<div class="clear"></div>
<section class="bg-product-section color-default">
    <div class="container-non-responsive bg-product-section">
            <div class="row row-contact">
            <div class="col-xs-3 no-padding col-left-wing">
                <div class="left-wing-contact">
                    <div class="panel-contact-details">
                        <p class="panel-title-contact">
                            WHO TO FOLLOW
                        </p>
                        <table width="100%"> 
                            <?=$follower_recommed_view;?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="followers-container">
                    <div class="loading_div" style="text-align:center;display:none;">
                        <img src="/assets/images/orange_loader.gif">
                    </div>
                    <div id="follower-container" class="row">
                        <div id="follow-div-page-0">
                            <?=$follower_view;?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="pagination-container">
                        <center>
                            <?=$pagination;?>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="storage" style="display:none">
    <div id="follow-div-page-0">
        <?=$follower_view;?>
    </div>
</div>
<input type="hidden" id="is_loggedin" value="<?php echo $isLoggedIn ? 1 : 0 ?>">
<input type="hidden" id="vendor_id" value="<?=$memberId?>">
    <script type='text/javascript'>
        (function($) {

                var vendorId = $("#vendor_id").val(); 
                $('.pagination-container').on('click', '.individual', function(){
          
                    var page = $(this).data('page');
                    if($(this).hasClass('active')){
                        return false;
                    }
                    var currentPage = parseInt(page) - 1;

                    $(this).siblings('.individual').removeClass('active');
                    $(this).addClass('active');

                    // start counting
                    var currentPage = page - 1;
                    if($('#storage > #follow-div-page-'+currentPage).length > 0){ 
                        $('#follower-container').empty().append($('#storage > #follow-div-page-'+currentPage).clone());
                        return false;
                    }

                    ajaxRequest = $.ajax({
                        type: "GET",
                        url: config.base_url+'home/getMoreFollowers',
                        data: {page:page,vendorId:vendorId} ,
                        beforeSend: function(){ 
                            $('#follower-container').hide(); 
                            $('.loading_div').show();
                        },
                        success: function(d){ 
                            var obj = jQuery.parseJSON(d); 
                            $('.loading_div').hide();
                            $('#follower-container').empty().append(obj.html).show();
                            $('#storage').append(obj.html);
                        }
                    });
                });

                $(document).on('mouseenter',".following-user",function () {
                    $(this).next('span').css("display", "block");
                    $(this).css("display", "none");
                });

                $(document).on('mouseleave',".unfollow-user",function () {
                    $(this).prev('span').css("display", "block");
                    $(this).css("display", "none");
                });
 
                $(document).on('click',".subscription",function () {
                    var $this = $(this);
                    var csrftoken = $("meta[name='csrf-token']").attr('content');
                    var status = $this.data('status');
                    var name = $this.data('username');
                    var slug = $this.data('slug');
                    var isLoggedIn = parseInt($('#is_loggedin').val());
                    var buttonType = $this.data('btn');//recommend

                    if(isLoggedIn){ 
                        if(status == "follow"){
                            var text = '<span class="follow-btn follow-right btn btn-default-following following-user subscription" style="display:none" data-status="unfollow" data-slug="'+slug+'" data-username="'+name+'">\
                                            <i class="fa fa-check"></i>Following\
                                        </span>\
                                        <span class="follow-btn follow-right btn btn-default-following unfollow-user subscription" style="display:block" data-status="unfollow" data-slug="'+slug+'" data-username="'+name+'">\
                                            <i class="fa fa-minus-circle"></i> Unfollow\
                                        </span>';
                        }else{
                            var text = '<span class="follow-btn follow-right btn btn-default-2 subscription" data-status="follow"  data-slug="'+slug+'" data-username="'+name+'">\
                                            <span class="glyphicon glyphicon-plus-sign"></span>Follow\
                                        </span>';
                        }
                        $.ajax({ 
                            url: config.base_url+"memberpage/vendorSubscription",
                            type: "POST",
                            dataType: "json",
                            data: {name: name,vendorlink:slug, csrfname: csrftoken},
                            success: function (data) {
                                if (data.result != "success") {  
                                    alert(data.error);
                                }
                                else{
                                    if(buttonType == "default"){
                                        $this.parent().empty().append(text);
                                    }
                                    else{
                                        $.ajax({ 
                                            url: config.base_url+"home/getMoreRecommendToFollow",
                                            type: "GET",
                                            dataType: "json",
                                            data: {vendorId:vendorId},
                                            success: function (data2) {
                                                if(data2.count <= 0){
                                                    $this.closest('tr').remove();
                                                }
                                                else{
                                                    $this.closest('tr').replaceWith(data2.html);
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        });
                    }
                    else{
                        $.removeCookie('es_vendor_subscribe');
                        $.cookie('es_vendor_subscribe', slug, {path: '/'});
                        window.location.href = config.base_url + 'login';
                    }
                });
        })( jQuery );
    </script>
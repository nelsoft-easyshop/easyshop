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
                            <tr>
                                <td class="td-vendor-img">
                                   <a href="#"><img src="/assets/images/img_profile_pic.jpg" class="vendor-img"/></a>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Neneng B
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        Unit 8C Marc 2000 Tower, Manila
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-vendor-img">
                                   <a href="#"><img src="/assets/images/img_profile_pic_2.jpg" class="vendor-img"/></a>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Boy Pick Up
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        1620 Bulacan Street, Sta. Cruz, Manila
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="td-vendor-img">
                                   <a href="#"><img src="/assets/images/img_profile_pic_3.jpg" class="vendor-img"/></a>
                                </td>
                                <td class="td-vendor-details">
                                    <a href="#">
                                        <p class="p-vendor-name">
                                            Senyora Angelica Santiba&ntilde;ez
                                        </p>
                                    </a>
                                    <p class="p-vendor-location">
                                        Hacienda Luisita Tarlac City, Tarlac, Philippines
                                    </p>
                                    <span class="follow-btn btn btn-default-2">
                                        <span class="glyphicon glyphicon-plus-sign"></span>Follow
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="followers-container">
                    <div id="follower-container" class="row">
                        <?=$follower_view;?>
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
 <input type="hidden" id="is_loggedin" value="<?php echo $isLoggedIn ? 1 : 0 ?>">
 <input type="hidden" id="vendor_id" value="<?=$memberId?>">
    <script type='text/javascript'>
        (function($) {

                $('.pagination-container').on('click', '.individual', function(){
                    $(this).siblings('.individual').removeClass('active');
                    $(this).addClass('active');
                    var page = $(this).data('page');
                    var vendorId = $("#vendor_id").val();

                    ajaxRequest = $.ajax({
                        type: "GET",
                        url: config.base_url+'home/getMoreFollowers',
                        data: {page:page,vendorId:vendorId} ,
                        beforeSend: function(){ 
                            $('#follower-container').hide(); 
                        },
                        success: function(d){ 
                            var obj = jQuery.parseJSON(d); 
                            $('#follower-container').empty().append(obj.html).show();
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
                                    $this.parent().empty().append(text);
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
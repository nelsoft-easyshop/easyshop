(function ($) {
    $( "#my-store-menu-trigger" ).click(function() {
        $( "#my-store-menu" ).slideToggle( "slow", function() {
            var attr = $("i.m").attr("class");
        if(attr == "m icon-control-down toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-down toggle-down pull-right").addClass("m icon-control-up toggle-down pull-right");
        }
        else if(attr == "m icon-control-up toggle-down pull-right"){
            $('i.m').removeClass("m icon-control-up toggle-down pull-right").addClass("m icon-control-down toggle-down pull-right");
        }
        });
    });
    
    
    $( "#info-item-1" ).click(function() {
        $( "#info-attributes-1" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-1").attr("class");
         if(i_icon == "info-item-icon-1 fa fa-plus-circle"){
            $('i.info-item-icon-1').removeClass("info-item-icon-1 fa fa-plus-circle").addClass("info-item-icon-1 fa fa-minus-circle");
            $(".text-info-icon-1").text("less info");
        }
        else if(i_icon == "info-item-icon-1 fa fa-minus-circle"){
            $('i.info-item-icon-1').removeClass("info-item-icon-1 fa fa-minus-circle").addClass("info-item-icon-1 fa fa-plus-circle");
            $(".text-info-icon-1").text("more info");
        }
        });
    });
    
    $( "#info-item-2" ).click(function() {
        $( "#info-attributes-2" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-2").attr("class");
         if(i_icon == "info-item-icon-2 fa fa-plus-circle"){
            $('i.info-item-icon-2').removeClass("info-item-icon-2 fa fa-plus-circle").addClass("info-item-icon-2 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-2 fa fa-minus-circle"){
            $('i.info-item-icon-2').removeClass("info-item-icon-2 fa fa-minus-circle").addClass("info-item-icon-2 fa fa-plus-circle");
            $(".text-info-icon-2").text("more info");
        }
        });
    });  
    
    $( "#info-item-3" ).click(function() {
        $( "#info-attributes-3" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-3").attr("class");
         if(i_icon == "info-item-icon-3 fa fa-plus-circle"){
            $('i.info-item-icon-3').removeClass("info-item-icon-3 fa fa-plus-circle").addClass("info-item-icon-3 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-3 fa fa-minus-circle"){
            $('i.info-item-icon-3').removeClass("info-item-icon-3 fa fa-minus-circle").addClass("info-item-icon-3 fa fa-plus-circle");
            $(".text-info-icon-3").text("more info");
        }
        });
    });
    
    $( "#info-item-4" ).click(function() {
        $( "#info-attributes-4" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-4").attr("class");
         if(i_icon == "info-item-icon-4 fa fa-plus-circle"){
            $('i.info-item-icon-4').removeClass("info-item-icon-4 fa fa-plus-circle").addClass("info-item-icon-4 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-4 fa fa-minus-circle"){
            $('i.info-item-icon-4').removeClass("info-item-icon-4 fa fa-minus-circle").addClass("info-item-icon-4 fa fa-plus-circle");
            $(".text-info-icon-4").text("more info");
        }
        });
    }); 
    
    $( "#info-item-5" ).click(function() {
        $( "#info-attributes-5" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-5").attr("class");
         if(i_icon == "info-item-icon-5 fa fa-plus-circle"){
            $('i.info-item-icon-5').removeClass("info-item-icon-5 fa fa-plus-circle").addClass("info-item-icon-5 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-5 fa fa-minus-circle"){
            $('i.info-item-icon-5').removeClass("info-item-icon-5 fa fa-minus-circle").addClass("info-item-icon-5 fa fa-plus-circle");
            $(".text-info-icon-5").text("more info");
        }
        });
    }); 
    
    $( "#info-item-6" ).click(function() {
        $( "#info-attributes-6" ).slideToggle( "slow", function() {
        var i_icon = $("i.info-item-icon-6").attr("class");
         if(i_icon == "info-item-icon-6 fa fa-plus-circle"){
            $('i.info-item-icon-6').removeClass("info-item-icon-6 fa fa-plus-circle").addClass("info-item-icon-6 fa fa-minus-circle");
            $(".text-info-icon-2").text("less info");
        }
        else if(i_icon == "info-item-icon-6 fa fa-minus-circle"){
            $('i.info-item-icon-6').removeClass("info-item-icon-6 fa fa-minus-circle").addClass("info-item-icon-6 fa fa-plus-circle");
            $(".text-info-icon-6").text("more info");
        }
        });
    }); 
    
    
    $( "#dash" ).click(function() { 
        $("#active-items").css("display", "block");
    });

    $('.transaction-title-bought').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought').slideToggle();
        $('.on-going-transaction-list-sold').slideUp();
        $('.transaction-title-sold').removeClass("active-bar");
    });

    $('.transaction-title-sold').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold').slideToggle();
        $('.on-going-transaction-list-bought').slideUp();
        $('.transaction-title-bought').removeClass("active-bar");
    });

        $('.transaction-title-bought-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-bought-completed').slideToggle();
        $('.on-going-transaction-list-sold-completed').slideUp();
        $('.transaction-title-sold-completed').removeClass("active-bar");
    });

    $('.transaction-title-sold-completed').click(function() {
        $(this).toggleClass("active-bar",0);
        $(this).next('.on-going-transaction-list-sold-completed').slideToggle();
        $('.on-going-transaction-list-bought-completed').slideUp();
        $('.transaction-title-bought-completed').removeClass("active-bar");
    });

    $(".trans-item-info").click(function() {
        $(this).children("i").toggleClass("fa-minus-circle");
        $(this).next(".info-attributes").slideToggle();
    });

    $(".view-delivery-lnk").click(function() {
        $(this).next(".view-delivery-details").slideToggle();
    });

    $(document.body).on('click',".individual",function () {
        var $this = $(this);
        var $page = $this.data('page');
        var $parentContainer = $this.parent().parent().parent().parent();
        var $textInput = $parentContainer.find('.search-field').val();
        var $filterInput = $parentContainer.find('.search-filter').val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val();

        requestProduct($page, $textInput, $filterInput, $requestType, $container);
    });

    $(document.body).on('change','.search-filter',function () {
        var $this = $(this);
        var $page = 0;
        var $parentContainer = $this.parent().parent().parent();
        var $textInput = $parentContainer.find('.search-field').val();
        var $filterInput = $this.val();
        var $requestType = $parentContainer.find('.request-type').val();
        var $container = $parentContainer.find('.container-id').val(); 

        requestProduct($page, $textInput, $filterInput, $requestType, $container);
    });

    $(".search-field").keyup(function(event){
        if(event.keyCode == 13){
            var $this = $(this);
            var $page = 0;
            var $parentContainer = $this.parent().parent().parent();
            var $textInput = $this.val();
            var $filterInput = $parentContainer.find('.search-filter').val();
            var $requestType = $parentContainer.find('.request-type').val();
            var $container = $parentContainer.find('.container-id').val(); 

            requestProduct($page, $textInput, $filterInput, $requestType, $container);
        }
    });

    /**
     * Ajax Request for getting product in active, draft and 
     * @param  integer $page
     * @param  string  $textInput
     * @param  string  $filterInput
     * @param  string  $requestType
     * @param  string $container
     */
    var requestProduct = function($page, $textInput, $filterInput, $requestType, $container)
    {
        var $urlRequest = $('#request-url').val();
        var $ajaxRequest = $.ajax({
            type: "get",
            url: $urlRequest,
            data: {
                    page:$page,
                    search_string:$textInput,
                    sort:$filterInput,
                    request:$requestType,

                } ,
            beforeSend: function(){ 
                $('#'+$container).hide();
            },
            success: function(d){ 
                var $response = $.parseJSON(d);
                $('#'+$container).html($response.html);
                $('#'+$container).show();
            }
        });
    }

}(jQuery));
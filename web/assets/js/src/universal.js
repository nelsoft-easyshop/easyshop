
    if (typeof jQuery.ui != 'undefined') {
        window.alert = function(message, detail){
        var detail = (typeof detail === "undefined") ? "" : '<hr/>'+detail;
        var html_content = '<b>'+message+'</b>'+detail;        
        $(document.createElement('div'))
            .attr({title: '', class: 'alert'})
            .html(html_content)
            .dialog({
                buttons: {OK: function(){$(this).dialog('close');}},
                close: function(){$(this).remove();},
                draggable: true,
                modal: true,
                resizable: false,
                dialogClass: 'error-modal',
                width:'auto',
                fluid : true
            });
        };
    }

    // on window resize run function
    $(window).resize(function () {
        fluidDialog();
    });

    // catch dialog if opened within a viewport smaller than the dialog width
    $(document).on("dialogopen", ".ui-dialog", function (event, ui) {
        fluidDialog();
    });

    function fluidDialog() {
        var $visible = $(".ui-dialog:visible");
        // each open dialog
        $visible.each(function () {
            var $this = $(this);
            var dialog = $this.find(".ui-dialog-content").data("ui-dialog");
            // if fluid option == true
            if (dialog.options.fluid) {
                //reposition dialog
                dialog.option("position", dialog.options.position);
            }
        });
    };

    $(document).ready(function()
    {
        $('.external-links-container a').on('click', function ()
        {
            window.location.replace('/redirect?url=' + $(this).attr('href'))
            return false;
        });
    });


    var entityMap = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': '&quot;',
        "'": '&#39;',
        "/": '&#x2F;'
    };

    function escapeHtml(string) {
        return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
        });
    }

    function serverTime() { 
        var time = null; 
        $.ajax({url: '/home/getServerTime', 
            async: false, dataType: 'text', 
            success: function(text) { 
                time = new Date(text); 
            }, error: function(http, message, exc) { 
                time = new Date(); 
        }}); 
        return time; 
    }

    function reload(){
        window.location.reload();
    } 

    function replaceNumberWithCommas(thisnumber){
        //Seperates the components of the number
        var n= thisnumber.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    }

    function validateRedTextBox(idclass){
        $(idclass).css({ 
            "-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
            "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
            "box-shadow": "0px 0px 2px 2px #FF0000"
        }).addClass('my_err');
        $(idclass).focus();
    } 

    function validateWhiteTextBox(idclass){
        $(idclass).css({ 
            "-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
            "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
            "box-shadow": "0px 0px 2px 2px #FFFFFF"
        }).removeClass('my_err');
    }

    function updateMessageCountIcons(){
        $.ajax({
            type:"get",
            dataType : "json",
            url : "/MessageController/getNumberOfUnreadMessages",
            success : function(count)
            {   
                var numberOfUnreadMessages = $.parseJSON(count);
                var title = '';
                
                if($('#original-title').length === 0){
                    var originalTitleTag = document.createElement('meta');
                    originalTitleTag.id = "original-title";
                    originalTitleTag.name = "original-title";
                    title = $(document).prop('title');
                    originalTitleTag.content = title;
                    document.getElementsByTagName('head')[0].appendChild(originalTitleTag);
                }
                else{
                    title = $('#original-title').attr('content')
                }

                $('.msg_countr').html(numberOfUnreadMessages);
                if(parseInt(numberOfUnreadMessages) > 0){
                    $(document).prop('title', '(' + numberOfUnreadMessages + ') ' + title);
                    $('.msg_countr').css('display','inline-block');
                }
                else{
                    $(document).prop('title', title);
                    $('.msg_countr').css('display','none'); 
                }
            }
        }); 
        
    }

    function isNumberKey(evt, withDecimal)
    {
        var withDecimal = typeof withDecimal != 'undefined' ? withDecimal : true;
        var charCode = (evt.which) ? evt.which : event.keyCode;

        if(withDecimal){
            if (charCode != 46 && charCode > 31 
                && (charCode < 48 || charCode > 57)){
                return false;
            }
        }
        else{
            if (charCode > 31 && (charCode < 48 || charCode > 57)){
                return false;
            }
        }

        return true;
    }

    /**
     * Add to Cart AJAX
     * 
     * @param integer productId
     * @param integer quantity
     * @param string[] optionsObject
     * @param boolean isExpress
     * @param string productSlug
     * @param boolean isNotRedirectOnError
     */
    function addToCart(productId, quantity, optionsObject, isExpress, productSlug, isNotRedirectOnError)
    {
        var ajaxData;
        var csrftoken = $("meta[name='csrf-token']").attr('content'); 
        isExpress = typeof isExpress === 'undefined' || isExpress === null ? false : isExpress;
        isNotRedirectOnError = typeof isNotRedirectOnError === 'undefined' ||
                               isNotRedirectOnError === null 
                               ? false : isNotRedirectOnError;
        if(isExpress === false){
            ajaxData = {productId:productId,quantity:quantity,options:optionsObject,csrfname:csrftoken};
        }
        else{
            ajaxData = {express:true, productId:productId, csrfname:csrftoken};
        }

        $.ajax({
            url: "/cart/doAddItem",
            type:"POST",
            dataType:"JSON",
            data:ajaxData,
            success:function(result){
      
                if(!result.isLoggedIn){
                    delete ajaxData.csrfname;
                    if(typeof productSlug !== 'undefined' && productSlug !== null){
                        ajaxData.slug = productSlug;    
                    }
                    $.removeCookie("cartAddData", { path: '/'});
                    var cartAddString = JSON.stringify(ajaxData);
                    console.log(cartAddString);
                    $.cookie('cartAddData', cartAddString, { path: '/'});
                    window.location.replace("/login");
                }
                else if(result.isSuccessful){
                    window.location.replace("/cart");
                }
                else{
                    if(isNotRedirectOnError){
                        alert("We cannot process your request at this time. Please try again in a few moment");
                    }
                    else{
                        if(typeof productSlug !== 'undefined' && productSlug !== null){
                            window.location.replace("/item/"+productSlug);
                        }
                        else{
                            window.location.replace('/');
                        }
                        
                    }
                }   
            }
        });
    }

    /**
    * Determines if an email has valid format  
    * 
    * @param string email
    * @return boolean
    */
    function isEmailValid(email)
    {
        var emailRegex = /^[_a-z0-9-]+(.[_a-z0-9-\+]+)@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/;
        return emailRegex.test(email);
    }



$(document).ready(function(){
 

  $('#div_tutOptional .paging:not(:first)').hide();
  $('#div_tutSpec .paging:not(:first)').hide();
  $('#div_tutQty .paging:not(:first)').hide();
  
    $('#tutSpec').on('click',function(){
        $('#div_tutSpec').modal({
            //position: ['5%', '10%'],
            onShow: function(){
                $('#paging_tutSpec').jqPagination({
                    paged: function(page) {
                        $('#div_tutSpec .paging').hide();
                        $($('#div_tutSpec .paging')[page - 1]).show();
                    }
                });
                $('#paging_tutSpec').jqPagination('option', 'current_page', 1);
            },
            onOpen: function (dialog) {
                dialog.overlay.fadeIn(250, function () {
                    dialog.container.slideDown(250, function () {
                        dialog.data.fadeIn(250);
                    });
                });
            },
            onClose: function(dialog){
                $('#paging_tutSpec').jqPagination('destroy');
                dialog.data.fadeOut(200, function () {
                    dialog.container.slideUp(200, function () {
                        dialog.overlay.fadeOut(200, function () {
                            $.modal.close(); 
                        });
                    });
                });
            }
        });
    });

    $('#tutOptional').on('click',function(){
        $('#div_tutOptional').modal({
            //position: ['5%', '10%'],
            onShow: function(){
                $('#paging_tutOptional').jqPagination({
                    paged: function(page) {
                        $('#div_tutOptional .paging').hide();
                        $($('#div_tutOptional .paging')[page - 1]).show();
                    }
                });
                $('#paging_tutOptional').jqPagination('option', 'current_page', 1);
            },
            onOpen: function (dialog) {
                dialog.overlay.fadeIn(250, function () {
                    dialog.container.slideDown(250, function () {
                        dialog.data.fadeIn(250);
                    });
                });
            },
            onClose: function(dialog){
                $('#paging_tutOptional').jqPagination('destroy');
                dialog.data.fadeOut(200, function () {
                    dialog.container.slideUp(200, function () {
                        dialog.overlay.fadeOut(200, function () {
                            $.modal.close(); 
                        });
                    });
                });
            }
        });
    });
 
    $('#tutQty').on('click',function(){
        $('#div_tutQty').modal({
            //position: ['5%', '10%'],
            onShow: function(){
                $('#paging_tutQty').jqPagination({
                    paged: function(page) {
                        $('#div_tutQty .paging').hide();
                        $($('#div_tutQty .paging')[page - 1]).show();
                    }
                });
                $('#paging_tutQty').jqPagination('option', 'current_page', 1);
            },
            onOpen: function (dialog) {
                dialog.overlay.fadeIn(250, function () {
                    dialog.container.slideDown(250, function () {
                        dialog.data.fadeIn(250);
                    });
                });
            },
            onClose: function(dialog){
                $('#paging_tutQty').jqPagination('destroy');
                dialog.data.fadeOut(200, function () {
                    dialog.container.slideUp(200, function () {
                        dialog.overlay.fadeOut(200, function () {
                            $.modal.close(); 
                        });
                    });
                });
            }
        });
    });
    
    // if keyword change. counter will change also either increase or decrease until reach its limit..
    updateCountdown();
    $('#prod_keyword').change(updateCountdown);
    $('#prod_keyword').keyup(updateCountdown); 

    // search brand 

    $('#brand_sch').focus(function() {
        $('#brand_search_drop_content').show();
        $(document).bind('focusin.brand_sch_drop_content click.brand_sch_drop_content',function(e) {
          if ($(e.target).closest('#brand_search_drop_content, #brand_sch').length) return;
          $('#brand_search_drop_content').hide();
        });
    });

    $('#brand_search_drop_content').hide();
 
});


// TINYMCE
$(function(){
    tinymce.init({
        mode : "specific_textareas",
        editor_selector : "mceEditor", 
        menubar: "table format view insert edit",
        statusbar: false, 
        height: 300,
        plugins: ["lists link preview","table jbimages fullscreen","textcolor" ],  
        toolbar: "insertfile undo redo | sizeselect | fontselect  fontsizeselect styleselect  forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
        relative_urls: false,
        setup: function(editor) {
            editor.on('change', function(e) {
                $('#prod_description').val(tinyMCE.get('prod_description').getContent());
                $('#prod_description').trigger( "change" );
            });
        }
    });

    tinymce.init({
        mode : "specific_textareas",
        editor_selector : "mceEditor_attr", 
        menubar: "table format view insert edit",
        statusbar: false,
        height: 200,
        plugins: [
            "lists link preview ",
            "table jbimages fullscreen" 
        ],  
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
        relative_urls: false
    });
});

// FUNCTION FOR KEYWORD COUNTER
    function updateCountdown() {
        // 140 is the max message length
        var remaining = 150 - $('#prod_keyword').val().length;
        $('.countdown').text(remaining + ' characters remaining.');
    }

// ERROR HANDLER
    function validateRedTextBox(idclass)
    {
        $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FF0000",
          "-moz-box-shadow": "0px 0px 2px 2px #FF0000",
          "box-shadow": "0px 0px 2px 2px #FF0000"});
    } 

    function validateWhiteTextBox(idclass)
    {
        $(idclass).css({"-webkit-box-shadow": "0px 0px 2px 2px #FFFFFF",
          "-moz-box-shadow": "0px 0px 2px 2px #FFFFFF",
          "box-shadow": "0px 0px 2px 2px #FFFFFF"});
    }


// NUMBER ONLY IN SPECIFIC FIELDS
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 46 && charCode > 31 
          && (charCode < 48 || charCode > 57))
           return false;

        return true;
    }


 // ES_UPLOADER BETA
$(document).ready(function() {

    $(".labelfiles").click(function(){
        $('.files.active').click(); 
    });

});
// ES_UPLOADER BETA END
 
/** Hide discount container when click outside ***/


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
    
    


   
});


// TINYMCE
$(function(){
  tinymce.init({
 mode : "specific_textareas",
 editor_selector : "mceEditor",
 //selector: "textarea",
  menubar: "table format view insert edit",
  statusbar: false,
  //selector: "textarea",

  statusbar: false,
  height: 300,
  plugins: [
  "lists link preview",
  "table jbimages fullscreen"
  //"advlist autolink link image lists charmap print preview hr anchor pagebreak",
  //"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
  //"table contextmenu directionality emoticons paste textcolor responsivefilemanager"
  ],  
  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | jbimages | image_advtab: true ",  
  //toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | image_advtab: true ",  
  relative_urls: false,
  
  //external_filemanager_path:"/assets/filemanager/",
  //filemanager_title:"Responsive Filemanager" ,
  //external_plugins: { "filemanager" : "/assets/filemanager/plugin.min.js"}
  
  
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
    //selector: "textarea",
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



 


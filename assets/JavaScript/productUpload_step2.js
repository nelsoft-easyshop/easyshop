$(function(){

  $('#div_tutOptional .paging:not(:first)').hide();
  $('#div_tutSpec .paging:not(:first)').hide();
  $('#div_tutQty .paging:not(:first)').hide();
  
    $('#tutSpec').on('click',function(){
        $('#div_tutSpec').modal({
            position: ['5%', '10%'],
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
            position: ['5%', '10%'],
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
            position: ['5%', '10%'],
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
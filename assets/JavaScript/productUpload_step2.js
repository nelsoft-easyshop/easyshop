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
      onClose: function(){
        $('#paging_tutSpec').jqPagination('destroy');
        $.modal.close();
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
      onClose: function(){
        $('#paging_tutOptional').jqPagination('destroy');
        $.modal.close();
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
      onClose: function(){
        $('#paging_tutQty').jqPagination('destroy');
        $.modal.close();
      }
    });
  });
});
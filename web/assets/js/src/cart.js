
$(document).ready(function() {
    $('.has-tooltip').hover(function() {
        var tooltip =$(this).find('.tooltip');
        tooltip.html('<img src="' + $(this).data('image') + '" >').fadeIn();
   }, function() {
        $(this).find('.tooltip').hide();
    }).append('<span class="tooltip"></span>');
});

function sum(obj){
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    var value = $(obj).val();
    var id = $(obj).attr("id");
    var mx = $(obj).attr("mx");
    if (parseInt(value) > parseInt(mx)) {
        $(obj).val(mx);
        value = mx;
    }
    
    $.ajax({
        async:false,
        url: config.base_url + "cart/fnc_qty",
        type:"POST",
        dataType:"JSON",
        data:{id:id,qty:value, csrfname:csrftoken},
        success:function(data){      
            if(data['result']==false){
                location.reload();
            }
            else{
                $("#subtotal"+id).text(data['subtotal']);
                $("#total").text(data['total']);
                $(obj).val(data.qty);
            }
            $(obj).attr("mx",data['maxqty']);
        }
    });
    $("#checkAll").trigger('click');
    $("#checkAll").trigger('click');
}

function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;

   return true;
}
function del(id){
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var csrfname = $("meta[name='csrf-name']").attr('content');
    var r=confirm("Are you sure you would like to remove this item from the shopping cart?");
    if (r==true)
      {
        $.ajax({
            async:false,
            url: config.base_url + "cart/remove_item",
            type:"POST",
            dataType:"JSON",
            data:{id:id, csrfname:csrftoken},
            success:function(data){
                if(data['result']==true){
                    $("#row"+id).remove();
                    $("#total").text(data['total']);
                    $(".cart_no").text(data['total_items']);
                }
                else{
                    alert("Sorry, we are having a problem right now.");
                }
            }
        });
      }
}


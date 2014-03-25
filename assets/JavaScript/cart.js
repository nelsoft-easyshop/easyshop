
$(document).ready(function() {
    $('.has-tooltip').hover(function() {
        $(this).find('.tooltip').html('<img src="' + $(this).data('image') + '" >').fadeIn();
    }, function() {
        $(this).find('.tooltip').hide();
    }).append('<span class="tooltip"></span>');
});

function sum(obj){
    var csrftoken = $('#mycart').val();
    var value = $(obj).val();
    var id = $(obj).attr("id");
    var mx = $(obj).attr("mx");
    if (parseInt(value) > parseInt(mx)) {
        $(obj).val(mx);
        value = mx;
    }
    $.ajax({
        async:false,
        url: config.base_url + "cart/change_qty",
        type:"POST",
        dataType:"JSON",
        data:{id:id,qty:value, es_csrf_token:csrftoken},
        success:function(data){
            if(data==false){
                alert("Unable to change quantity");
            }
            else{
                $("#subtotal"+id).text(data['subtotal']);
                $("#total").text(data['total']);
            }
        }
    });
}
function del(id){
    var csrftoken = $('#mycart').val();
    var r=confirm("Are you sure you would like to remove this item from the shopping cart?");
    if (r==true)
      {
        $.ajax({
            async:false,
            url: config.base_url + "cart/remove_item",
            type:"POST",
            dataType:"JSON",
            data:{id:id, es_csrf_token:csrftoken},
            success:function(data){
                if(data['result']==true){
                    $("#row"+id).remove();
                    $("#total").text(data['total']);
                    $(".cart_no").text(data['total_items']);
                }
                else{
                    alert("Unable to remove!");
                }
            }
        });
      }
}


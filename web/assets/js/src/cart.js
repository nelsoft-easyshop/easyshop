(function($){
    $(document).ready(function () {
        $('.has-tooltip').hover(function () {
            var tooltip = $(this).find('.tooltip');
            tooltip.html('<img src="' + $(this).data('image') + '" >').fadeIn();
        },function () {
            $(this).find('.tooltip').hide();
        }).append('<span class="tooltip"></span>');

    });
})(jQuery)

function proceedPayment(obj){
    var className = '.' + $(obj).attr('child');
    var csrftoken = $("meta[name='csrf-token']").attr('content');

    var data1 = $("input:checkbox:not(:checked)").map(function () {
        return $(this).attr('data');
    }).toArray();
    var a = parseInt(0);
    var b = parseInt(0);
    if(className == ".single1_checkAll_tablet" && $('.single1_checkAll_desktop').is(':visible') ){
        className = ".single1_checkAll_desktop";
    }
    $(document).find(className).each(function () {
        if ($(this).prop('checked') == false) {
            a++;
        }
        b++;
    });
    if (a == b) {
        alert("You must select at least one item to proceed with your payment");
    }
    else {
        $.ajax({
            async: true,
            url: "/payment/cart_items",
            type: "POST",
            dataType: "json",
            data: {itm: data1, csrfname: csrftoken},
            success: function (data) {
                if (data == true) {
                    window.location.replace("/payment/review");
                } else {
                    alert(data, 'Remove these items from your cart to proceed with your checkout.');
                }
            }
        });
    }
}

function sum(obj)
{
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var value = $(obj).val();
    var id = $(obj).attr("id");
    var mx = $(obj).attr("mx");
    if (parseInt(value) > parseInt(mx)) {
        $(obj).val(mx);
        value = mx;
    }

    $.ajax({
        async: false,
        url: config.base_url + "cart/fnc_qty",
        type: "POST",
        dataType: "JSON",
        data: {id: id, qty: value, csrfname: csrftoken},
        success: function (data) {
            if (data == false) {
                location.reload();
            }
            else {
                $(".subtotal" + id).text(data['subtotal']);
                $("#total").text(data['total']);
                $(obj).val(data.qty);
                $('#rad_' + id).val(data.subtotal);
            }
            $(obj).attr("mx", data['maxqty']);
        }
    });
    $(".checkAll").trigger('click').trigger('click');
}

function selectAll(obj) {
    $(".single1_" + $(obj).attr('data_id')).prop('checked', obj.checked);
    value = 0;
    if ($(obj).prop('checked')) {
        for (var i = 0; i < $(".single1_" + $(obj).attr('data_id')).length; i++) {
            value = Number($(".single1_" + $(obj).attr('data_id')).eq(i).val().replace(/\$/g, '').replace(/,/g, '')) + value;
        }
        $("#total").html(Number(value).toLocaleString('en') + ".00");
        $("#total").html(numeral(parseFloat(value).toFixed(2)).format('0,0.00'));
    }
    else {
        $("#total").html("0.00");
    }
}

function singleSelect(obj){
    var total = 0;
    $('.' + $(obj).attr('class')).each(function () {
        if ($(this).prop('checked')) {
            total += parseFloat($(this).val().replace(/,/g, ''));
        }
    });
    var ttl = numeral(parseFloat(total).toFixed(2)).format('0,0.00');
    $("#total").html(ttl);
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}

function del(data)
{
    var prod_id = $(data).attr('val');
    var csrftoken = $("meta[name='csrf-token']").attr('content');
    var r = confirm("Are you sure you like to remove this item from the shopping cart?");
    if (r == true) {
        $.ajax({
            async: false,
            url: config.base_url + "cart/remove_item",
            type: "POST",
            dataType: "JSON",
            data: {id: prod_id, csrfname: csrftoken},
            success: function (data) {
                if (data['result'] == true) {
                    $(".checkAll").trigger('click').trigger('click');
                    $(".row_" + prod_id).remove();
                    $("#total").text(data['total']);
                    $(".cart_no").text(data['total_items']);
                    if(parseInt(data['total_items']) === 0){
                        $('.cart_no').hide();
                        $('.cart').css('width','28');
                    }
                }
                else {
                    alert("Sorry, we are having a problem right now.");
                }
            }
        });
    }
}


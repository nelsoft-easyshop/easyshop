(function ($) {
    $(document).ready (function(){
        window.cities = JSON.parse($( "#cityList" ).val());
     });
    $( "#regionSelect" ).change(function() {
        var data = window.cities[$( "#regionSelect" ).val()];
        var list = '';
        $("#citySelect").empty();
        for (var i=0; i < data.length; i++) {
            list += "<option value='" + data[i] + "'>" + data[i] + "</option>";
        }
        $("#citySelect").html(list);
    });   
})(jQuery);


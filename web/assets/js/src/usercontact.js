(function ($) {
    $(document).ready (function(){
        window.cities = JSON.parse($( "#cityList" ).val());

        if($( "#displayStoreName" ).val() == "" || $( "#displayContactNo" ).val() == "" || $( "#displayStreetAddr" ).val() == "" || parseInt($('#errorCount').val()) > 0){
            $( "#editIconOpen" ).click();
        }
        else{
            if($( "#website" ).val() == ""){
                $( "#websiteRow" ).hide();
            }
        }
     });

    $(" #contactNo ").numeric(
        {decimal: false, negative: false}, 
        function(){
            this.value = ""; this.focus();
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

    $( "#editIconOpen" ).click(function() {
        $(".input-detail").css("display","inline");
        $("#editIconClose").css("display","inline");
        $("#save-edit").css("display","inline");
        $("#editIconOpen").css("display","none");
        $(".text-contact").css("display","none");

        if($( "#storeName" ).val() == ""){
            $( "#storeNameRow" ).show();
        }
        if($( "#contactNo" ).val() == ""){
            $( "#contactNoRow" ).show();
        }
        if($( "#streetAddr" ).val() == ""){
            $( "#addressRow" ).show();
        }
        if($( "#website" ).val() == ""){
            $( "#websiteRow" ).show();
        }
     });
    
     $( "#editIconClose" ).click(function() {
        $(".input-detail").css("display","none");
        $("#editIconClose").css("display","none");
        $("#save-edit").css("display","none");
        $("#editIconOpen").css("display","inline");
        $(".text-contact").css("display","inline");

        if($( "#displayStoreName" ).val() == ""){
            $( "#storeNameRow" ).hide();
        }
        else{
            $( "#storeName" ).val($("#displayStoreName").val());
        }

        if($( "#displayContactNo" ).val() == ""){
            $( "#contactNoRow" ).hide();
        }
        else{
            $( "#contactNo" ).val($("#displayContactNo").val());
        }

        $( "#streetAddr" ).val($("#displayStreetAddr").val());
        $( "#regionSelect" ).val($("#displayRegion").val());
        $( "#regionSelect" ).change();
        $( "#citySelect" ).val($("#displayCity").val());

        if($( "#displayWebsite" ).val() == ""){
            $( "#websiteRow" ).hide();
        }
        else{
            $( "#website" ).val($("#displayWebsite").val());
        }
     });
})(jQuery);



(function ($) {
    $(document).ready (function(){
        window.cities = JSON.parse($( "#cityList" ).val());
      
        if($( "#storeName" ).val() == "" || $( "#contactNo" ).val() == "" || $( "#streetAddr" ).val() == ""){
            $( "#editIconOpen" ).click();
        }
        else{
            if($( "#website" ).val() == ""){
                $( "#websiteRow" ).hide();
            }
        }
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

        if($( "#storeName" ).val() == ""){
            $( "#storeNameRow" ).hide();
        }
        if($( "#contactNo" ).val() == ""){
            $( "#contactNoRow" ).hide();
        }
        if($( "#streetAddr" ).val() == ""){
            $( "#addressRow" ).hide();
        }
        if($( "#website" ).val() == ""){
            $( "#websiteRow" ).hide();
        }
     });
})(jQuery);


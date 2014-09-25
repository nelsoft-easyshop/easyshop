(function ($) {
    $(document).ready (function(){
        window.cities = JSON.parse($( "#cityList" ).val());

        if($( "#isEditable" ).val() == true){        
            if($( "#storeName" ).val() == "" || $( "#contactNo" ).val() == "" || $( "#streetAddr" ).val() == ""){
                $( ".fa-edit-icon" ).click();
            }
        }
        else{
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

    $( ".fa-edit-icon" ).click(function() {
        $(".input-detail").css("display","inline");
        $(".fa-cancel-edit").css("display","inline");
        $("#save-edit").css("display","inline");
        $(".fa-edit").css("display","none");
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
    
     $( ".fa-cancel-edit" ).click(function() {
        $(".input-detail").css("display","none");
        $(".fa-cancel-edit").css("display","none");
        $("#save-edit").css("display","none");
        $(".fa-edit").css("display","inline");
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


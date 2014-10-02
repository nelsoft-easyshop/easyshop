(function ($) {
    $(document).ready (function(){
        window.cities = JSON.parse($( "#cityList" ).val());

        if( $( "#displayStoreName" ).val() == ""        || ($( "#displayContactNo" ).val() == ""    || 
            $( "#displayContactNo" ).val().length < 11) || $( "#displayStreetAddr" ).val() == ""    || 
            parseInt($('#errorCount').val()) > 0        || $( "#displayCity" ).val() == ""          || 
            $( "#displayRegion" ).val() == ""){
            
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

        $( "#storeNameRow" ).show();
        $( "#contactNoRow" ).show();
        $( "#addressRow" ).show();
        $( "#websiteRow" ).show();
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
        $( "#storeName" ).val($("#displayStoreName").val());
        

        if($( "#displayContactNo" ).val() == "" || $( "#displayContactNo" ).val().length < 11){
            $( "#contactNoRow" ).hide();
        }
        $( "#contactNo" ).val($("#displayContactNo").val());

        if($("#displayCity" ).val() == "" && $( "#displayRegion" ).val() == "" && $("#displayStreetAddr").val() == ""){
            $( "#addressRow" ).hide();
        }
        
        if($("#displayCity" ).val() == "" && $( "#displayRegion" ).val() == ""){
            $( "#regionSelect" ).val($("#defaultRegion").val());
        }   
        else{
            $( "#regionSelect" ).val($("#displayRegion").val());
        }
        $( "#regionSelect" ).change();
        $( "#citySelect" ).val($("#displayCity").val());

        var addr = $("#displayStreetAddr").val();
        $( "#streetAddr" ).val(addr == "" ? "" : addr.substring(0, addr.length - 2));

        if($( "#displayWebsite" ).val() == ""){
            $( "#websiteRow" ).hide();
        }
        $( "#website" ).val($("#displayWebsite").val());
        
     });
})(jQuery);



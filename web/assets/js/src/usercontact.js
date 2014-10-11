(function ($) {
    $(document).ready (function(){
        window.cities = JSON.parse($( "#cityList" ).val());
        if($( "#isEditable" ).val() == false){
            // hide all fields that are empty
            if($( "p#validatedStoreName" ).html() == ""){
                $( "#storeNameRow" ).hide();
            }
            if($( "p#validatedContactNo" ).html() == ""){
                $( "#contactNoRow" ).hide();
            }
            if($( "a#validatedWebsite" ).html() == ""){
                $( "#websiteRow" ).hide();
            }
            if($("#validatedCity" ).val() == "" && $( "#validatedRegion" ).val() == ""){
                $( "#addressRow" ).hide();
            }
        }
        else{
            if( $( "p#validatedStoreName" ).html() == "" || $( "p#validatedContactNo" ).html() == "" || 
            $( "#validatedStreetAddr" ).val() == "" || parseInt($('#errorCount').val()) > 0){
            
                $( "#editIconOpen" ).click();
            }
            else{
                if($( "a#validatedWebsite" ).html() == ""){
                    $( "#websiteRow" ).hide();
                }
            }
        }        
     });

    $(" #contactNo ").numeric(
        {decimal: false, negative: false}, 
        function(){
            this.value = ""; this.focus();
        });

    $( "#regionSelect" ).change(function() {
        var regionValue = $( "#regionSelect" ).val();

        var list = "<option value='' selected>Select City</option>";
        $("#citySelect").empty();

        if(regionValue != ''){
            var list = "<option value=''>Select City</option>";
            var data = window.cities[regionValue];
            var city = $("#postCity" ).val();

            for (var i=0; i < data.length; i++) {
                list += "<option value='" + data[i] + "' " + (data[i] == city.substring(0, city.length - 2)? "selected>" : ">") + data[i] + "</option>";
            }
        }

        $("#citySelect").html(list);
    });   

    $( "#editIconOpen" ).click(function() {
        $(".input-detail").css("display","inline");
        $("#editIconClose").css("display","inline");
        $("#save-edit").css("display","inline");
        $("#editIconOpen").css("display","none");
        $(".text-contact").css("display","none");

        $( "#regionSelect" ).change();
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

        // hide all fields that are empty
        if($( "p#validatedStoreName" ).html() == ""){
            $( "#storeNameRow" ).hide();
        }
        if($( "p#validatedContactNo" ).html() == ""){
            $( "#contactNoRow" ).hide();
        }
        if($( "a#validatedWebsite" ).html() == ""){
            $( "#websiteRow" ).hide();
        }
        if($("#validatedCity" ).val() == "" && $( "#validatedRegion" ).val() == ""){
            $( "#addressRow" ).hide();
        }

        // revert all changes back to original using post hidden input
        $( "#storeName" ).val($("#postStoreName").val());
        $( "#contactNo" ).val($("#postContactNo").val());
        $( "#website" ).val($("#postWebsite").val());
        $( "#regionSelect" ).change();

        var addr = $("#postStreetAddr").val();
        $( "#streetAddr" ).val(addr == "" ? "" : addr.substring(0, addr.length - 2));
     });
})(jQuery);


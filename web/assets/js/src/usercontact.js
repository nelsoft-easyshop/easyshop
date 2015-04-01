(function ($) {
    $(document).ready (function(){
       
        $( ".drop-user-details" ).click(function() {
          $( ".user-details-container" ).slideToggle();
        });
        
        $( ".fa-edit-icon" ).click(function() {
          $( ".user-details-container" ).slideDown();
        });
        
        $( ".fa-cancel-edit" ).click(function() {
          $( ".user-details-container" ).slideDown();
        });
        
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
            var data = window.cities[parseInt(regionValue)];
            var city = $("#postCity" ).val();
            for(var key in data){
                list += "<option value='" + key + "' " + (key == city ? "selected>" : ">") + data[key] + "</option>";
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
        $(".external-links-container").css("display","none");

        $( "#regionSelect" ).change();
        $( "#storeNameRow" ).show();
        $( "#contactNoRow" ).show();
        $( "#addressRow" ).show();
        $( "#websiteRow" ).show();

        if($("#validatedCity" ).val() == "" && $( "#validatedRegion" ).val() == ""){
            $( "#streetAddr" ).val('');
        }
     });
    
     $( "#editIconClose" ).click(function() {
        $(".input-detail").css("display","none");
        $("#editIconClose").css("display","none");
        $("#save-edit").css("display","none");
        $("#editIconOpen").css("display","inline");
        $(".text-contact").css("display","block");
        $(".external-links-container").css("display","block");
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

        // revert all changes back to original using post hidden input
        $( "#storeName" ).val($("#postStoreName").val());
        $( "#contactNo" ).val($("#postContactNo").val());
        $( "#website" ).val($("#postWebsite").val());
        $( "#regionSelect" ).change();

        var addr = $("#postStreetAddr").val();
        $( "#streetAddr" ).val(addr == "" ? "" : addr.substring(0, addr.length - 2));
     });

    var $window = $(window);

    function checkWidth() {
        var windowsize = $window.width();
        if (windowsize > 440) {
            //if the window is greater than 440px wide then turn on jScrollPane..
            $( ".user-details-container" ).css("display","block");
        }
        else{
            $( ".user-details-container" ).css("display","none");
        }
    }

    // Execute on load
    checkWidth();
    // Bind event listener
    $window.resize(checkWidth);

    $window.on('load resize', function() {
        setTimeout(function(){ 
            var TdContactDetail = $(".panel-contact-details").width();
            var TdContactWidth = (TdContactDetail - 72);
            $(".text-contact").css("width", TdContactWidth);
      }, 300);
    });
    
})(jQuery);



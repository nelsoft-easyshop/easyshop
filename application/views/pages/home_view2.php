
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map-canvas { height: 305px;width: 620px;} 
    </style>
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=&sensor=false">
    </script>
    
    <script>

    $(document).ready(function(){
        $("#view_map").click(function(){       
        var streetno = $("#streetno").val();
        var streetname = $("#streetname").val();
        var barangay = $("#barangay").val();
        var citytown = $("#citytown").val();
        var country = $("#country").val();
        var address = streetno + " " + streetname + " Street " + ", " + barangay + " " + citytown + ", " + country;
        $.ajax({
            async:true,
            url:"home/toCoordinates",
            type:"POST",
            dataType:"JSON",
            data:{address:address},
            success:function(data){
                var myLatlng =  new google.maps.LatLng(data['lat'],data['lng']);
                
                $("#map").show();
            
                google.maps.event.addDomListener(window, 'load', initialize(myLatlng));
            }

            });
        });

        function initialize(myLatlng) {
        var mapOptions = {
          center:myLatlng,
          zoom: 15
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title:"You! :)"
            });
        }
    });

    </script>
    
    
  </head>
  <body>
    <div id="map-canvas"/>
    
    
            <div class="inner_profile_fields">
                    <div class="address_fields progress_update update_once">
                            <div class="address_fields_layer1">
                                    <div>
                                            <input type="text" name="streetno" id="streetno" value="786">
                                            <p>Street No./Bldg. No.</p>
                                    </div>
                                    <div>
                                            <input type="text" name="streetname" id="streetname"  value="Mandarin">
                                            <p>Street Name</p>
                                    </div>
                                    <div>
                                            <input type="text" name="barangay" id="barangay"  value="184">
                                            <p>Barangay</p>
                                    </div>
                            </div>
                            <div class="address_fields_layer2">
                                    <div>
                                            <input type="text" name="citytown" id="citytown"  value="North Caloocan">
                                            <p>City/Town</p>
                                    </div>
                                    <div>
                                            <input type="text" name="country" id="country"  value="Philippines">
                                            <p>Country</p>
                                    </div>
                                    <div>
                                            <input type="text" name="postalcode" id="postalcode" value="">
                                            <p>Postal Code</p>
                                    </div>
                            </div>
                            <input type="hidden" name="addresstype" value="0"/>
                            <div class="clear"></div>
                            <input type="hidden" class="progress_update_hidden" value="">
                    </div>
                    <div>
                            <label></label>
                            <span class="red ci_form_validation_error"><?php echo form_error('streetno'); ?></span>
                            <span class="red ci_form_validation_error"><?php echo form_error('streetname'); ?></span>
                            <span class="red ci_form_validation_error"><?php echo form_error('citytown'); ?></span>
                            <span class="red ci_form_validation_error"><?php echo form_error('country'); ?></span>
                    </div>
                    <div>
                            <input type="button" id="view_map" value="View on map">                            
                    </div>
                
            <div class="clear"></div>
    </div>
    <div id="map"  style="display: none">
        <div id="GoogleMapContainer" title="Google Map Container"></div>
        <a id="close" href="#">Close</a>
    </div>
  </body>
</html>
<div style='padding:10px;'> 
    <h2 style='color:#f48000;'><?= es_string_limit($item_name, 50, $trailer = '...') ?></h2><br>
    <hr/>
    This item is available in the following locations:
    <input id='p_shipment' type='hidden' value='<?php echo json_encode($shipment_information);?>'>  
    <select class="shiploc" id="shipment_locations">
        <option class="default" selected="" value="0">Select Location</option>
        <?php foreach($shiploc['area'] as $island=>$loc):?>
            <option data-price="0" data-type="1" id="<?php echo 'locationID_'.$shiploc['islandkey'][$island];?>" value="<?php echo $shiploc['islandkey'][$island];?>" disabled><?php echo $island;?></option>
            <?php foreach($loc as $region=>$subloc):?>
                <option data-price="0" data-type="2" id="<?php echo 'locationID_'.$shiploc['regionkey'][$region];?>" value="<?php echo $shiploc['regionkey'][$region];?>" style="margin-left:15px;" disabled>&nbsp;&nbsp;&nbsp;<?php echo $region;?></option>
                <?php foreach($subloc as $id_cityprov=>$cityprov):?>
                    <option data-price="0" data-type="3" id="<?php echo 'locationID_'.$id_cityprov;?>" value="<?php echo $id_cityprov;?>" style="margin-left:30px;" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $cityprov;?></option>
                <?php endforeach;?>
            <?php endforeach;?>
        <?php endforeach;?>
    </select>
    <br/>
    <br/>
</div>
<script type="text/javascript">
   
    var shipment = JSON.parse($('#p_shipment').val());
    var iid = '<?php echo $item_id;?>';
    (function($) { 
        $.each(shipment, function(index, value){
            if(iid == value.product_item_id){
                var option =  $('#locationID_' + value.location_id);
                option.data('price',value.price);
                option.prop('disabled', false);
                $.each(option.nextAll(), function(){
                    if($(this).data('type') === option.data('type')){
                        return false;
                    }
                    $(this).prop('disabled', false);
                    $(this).data('price',value.price);
                }); 
            }
        });

        if($('#shipment_locations :selected').is(':disabled')){
            $('#shipment_locations :nth-child(1)').prop('selected', true);
            $('.shipping_fee').html("Select location to view shipping fee");
        }

    })(jQuery);

</script>
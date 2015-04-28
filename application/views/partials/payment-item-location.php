<select class="form-es-control form-es-control-block">
    <option class="default" selected="" data-text="Select Location" value="0">Select Location</option>
    <?php foreach($selectLocation['area'] as $island => $loc):?>
        <?php if(in_array($selectLocation['islandkey'][$island], $locationAvailable)): ?>
            <option data-price="0" data-type="1"><?=html_escape($island);?></option>
        <?php endif; ?>
        <?php foreach($loc as $region => $subloc):?>
            <?php if(in_array($selectLocation['regionkey'][$region], $locationAvailable)): ?>
                <option data-price="0" data-type="2" value="<?=$selectLocation['regionkey'][$region];?>" style="margin-left:15px;"><?=html_escape($region);?></option>
            <?php endif; ?>
            <?php foreach($subloc as $id_cityprov => $cityprov):?>
                <?php if(in_array($id_cityprov, $locationAvailable)): ?>
                    <option data-price="0" data-type="3" style="margin-left:30px;"><?=html_escape($cityprov);?></option>
                <?php endif; ?>
            <?php endforeach;?>
        <?php endforeach;?>
    <?php endforeach;?>
</select>


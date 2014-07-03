<?php require_once("assets/includes/css.php"); ?>
<?php require_once("assets/includes/js.php"); ?>
<style type="text/css">
 
/* Container */
.simplemodal-container {
  height: auto !important;
  width: auto !important; 
  background-color:#0000;
  padding: 5px;
  top: 165px !important;
}
 
.current_selected img{
    height: 200px; 
}

#mainslider,#linklist,#productslide{
  padding: 30px;
}

.small_span{
  font-size: 12px;
  font-style: italic;
}
</style>

<link type="text/css" href="<?=base_url()?>assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<div id="linklist">
  <ul>
    <li>
      <a href="javascript:void(0)" class="showDiv" data-div="mainslider">EDIT MAIN SLIDER</a>
    </li>
    <li>
      <a href="javascript:void(0)" class="showDiv" data-div="productslide">EDIT PRODUCT SLIDES</a>
    </li>
  </ul>

</div>

<div id="mainslider" class="div_content">
    <div>
        <div class="current_selected">
            <?php foreach ($bannerImages as $key => $value): ?>
                <div id="main_slide_<?=$key;?>">
                    <img src="<?= base_url().$value['src']; ?>">    
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" 
                      data-ratiox="<?=explode(',' ,$value['imagemap']['coordinate'])[0]?>"
                      data-ratioy="<?=explode(',' ,$value['imagemap']['coordinate'])[1]?>"
                      data-ratioxx="<?=explode(',' ,$value['imagemap']['coordinate'])[2]?>"
                      data-ratioyy="<?=explode(',' ,$value['imagemap']['coordinate'])[3]?>"
                      data-link="<?=$value['imagemap']['target']?>"
                      class="editPic" href="javascript:void(0)">edit</a> |
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" class="removePic" href="javascript:void(0)">remove</a> |
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" class="moveUp" href="javascript:void(0)">up</a> |
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" class="moveDown" href="javascript:void(0)">down</a>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="javascript:void(0)" id="add_more_photo"><img src="" alt="Click here to add more"></a>    
    </div>

    <?php 
    $attr = array(
      'class' => 'picform',
      'id' => 'picform',
      'name' => 'picform',
      'enctype' => 'multipart/form-data'
      );
    echo form_open('manage/uploadimage', $attr);
    ?> 
        <input type="file" id="add_more_photo_input" name="files" class="add_more_photo_input" />
    </form>
</div>

<div id="pop_up_image_edit" class="simplemodal-container">
    <span> Select Coordinate! </span>
    <img src="" id="image_prev">  
    <br>
    x: <input type='text' name='x' value='0' readonly size="7" id='image_x'>
    y: <input type='text' name='y' value='0' readonly size="7"  id='image_y'>
    x2: <input type='text' name='xx' value='0' readonly size="7"  id='image_xx'>
    y2: <input type='text' name='yy' value='0' readonly size="7"  id='image_yy'>
    <br><br>
    Target Page: <input type='text' name='link'   id='link'>
    <br><br>
    <button id="saveImage" style="float:left">Save Image</button>
    <button id="resetCoordinates" style="float:right">Reset All</button>
</div>
 
<div id="productslide" class="div_content">

     
    <?php 
    $attr = array(
      'class' => 'slideProduct',
      'id' => 'slideProduct',
      'name' => 'slideProduct',
      'enctype' => 'multipart/form-data'
      );
    echo form_open('manage/editSlideProduct', $attr);
    ?> 
        Product Slide Title: <input type="text" size="47" name="productslide_title" value="<?=$productSlide_title?>" >
        <br><br>

        Product Side Banner: <input type="text" size="45" name="productsidebanner" value="<?=$productSideBanner['slug']?>" > <span class="small_span">Please specify SLUG of item</span>

        <br><br>
        <?php 
        $counter = 1;
        foreach ($productSlide as $key => $value):
            $cnt = $key + 1;
        ?>
            Product Slide <?=$counter?>: <input type="text" name="item[]" size="50" value="<?=$value['slug']?>"> <span class="small_span">Please specify SLUG of item</span>
            <br>
        <?php
            $counter++;
        endforeach; ?>
        <br><br>
    </form>
    <button id="slideProductBtn">Save Changes</button>
</div>


<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type='text/javascript' src='<?=base_url()?>assets/js/src/manage.js'></script>
<script type='text/javascript' src="<?=base_url()?>assets/tinymce/plugins/jbimages/js/jquery.form.js"></script>
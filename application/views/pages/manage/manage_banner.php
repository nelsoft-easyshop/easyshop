<?php require_once("assets/includes/css.php"); ?>
<?php require_once("assets/includes/js.php"); ?>
<style type="text/css">

#pop_up_image_edit,#simplemodal-container {
  height: auto !important;
  width: auto !important; 
  background-color:#0000;
  padding: 5px;
}

.main_images img{
    height: 200px; 
    border: solid 1px;
}

#mainslider,#linklist,#productslide{
  padding: 30px;
}

.small_span{
  font-size: 13px;
  font-style: italic;
}
.main_images{
  display: inline-block;
  width: 350px;   
}

#pop_up_image_edit , .div_content{
  display: none;
}
#linklist > ul > li{
  display: inline;
  border: solid 1px;
  padding: 5px;
  margin-right: 2px;
}
.imglink{
    background: none repeat scroll 0 0 white;
    border: 1px solid;
    position: relative;
    padding: 4px; 
}
.removePic{ 
    color: red;
    left: 299px;
    top: 22px;
}
.editPic{
    color: green;
    left: 302px;
    top: 22px;
}
.moveUp{  
    font-size: 20px;
    left: -41px;
    top: 198px;
    background: orange
}
.moveDown{  
    font-size: 20px; 
    left: 256px;
    top: 198px;
    background: orange

}
</style>

<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<div id="linklist">
  <ul>
    <li>
      <a href="javascript:void(0)" class="showDiv" data-div="mainslider">SLIDER</a>
    </li>
    <li>
      <a href="javascript:void(0)" class="showDiv" data-div="productslide">ITEMS</a>
    </li> 
  </ul>

</div>

<div id="action"></div>

<div id="mainslider" class="div_content">
    <div>
        <div class="current_selected">
            <?php foreach ($bannerImages as $key => $value): ?>
                <div class="main_images" id="main_slide_<?=$key;?>">
                    
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" 
                      data-ratiox="<?=explode(',' ,$value['imagemap']['coordinate'])[0]?>"
                      data-ratioy="<?=explode(',' ,$value['imagemap']['coordinate'])[1]?>"
                      data-ratioxx="<?=explode(',' ,$value['imagemap']['coordinate'])[2]?>"
                      data-ratioyy="<?=explode(',' ,$value['imagemap']['coordinate'])[3]?>"
                      data-link="<?=$value['imagemap']['target']?>"
                      class="editPic imglink" href="javascript:void(0)" title="Edit this photo">E</a>  
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" title="Remove this photo" class="removePic imglink" href="javascript:void(0)">X</a> 
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" title="Move this photo up" class="imglink movePosition moveUp" data-action="up" href="javascript:void(0)">&#10096;</a> 
                    <a data-node="<?=$value['src']?>" data-div="main_slide_<?=$key;?>" title="Move this photo down" class="imglink movePosition moveDown" data-action="down" href="javascript:void(0)">&#10097;</a>
                    <div><img data-node="<?php echo getAssetsDomain().'.'.$value['src']?>" data-div="main_slide_<?=$key;?>" 
                      data-ratiox="<?=explode(',' ,$value['imagemap']['coordinate'])[0]?>"
                      data-ratioy="<?=explode(',' ,$value['imagemap']['coordinate'])[1]?>"
                      data-ratioxx="<?=explode(',' ,$value['imagemap']['coordinate'])[2]?>"
                      data-ratioyy="<?=explode(',' ,$value['imagemap']['coordinate'])[3]?>"
                      data-link="<?=$value['imagemap']['target']?>"
                    class="editPic" src="<?= '/'.$value['src']; ?>">  </div>  
                </div>
            <?php endforeach; ?>
        </div>  
    </div>
    <br><br>
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

<div id="pop_up_image_edit">
    <span> <h3>Select Coordinate</h3> </span>
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
   
        <h2>COMPANION BOX SECTION</h2>
        <br>
        Companion Box Item: <input type="text" size="45" name="productsidebanner" value="<?=$productSideBanner['slug']?>" > <span class="small_span">Please specify SLUG of item (eg: "https://easyshop.ph/item/<span style="color:red;font-weight:bold">THIS-IS-SLUG</span>")</span>

        <br><br><hr><br>
        <h2>PARTY ITEMS SECTION</h2>
        <br>
             Party Items Legend: <input type="text" size="47" name="productslide_title" value="<?=$productSlide_title?>" >
             <br> <br>
        <?php 
        $counter = 1;
        foreach ($productSlide as $key => $value):
            $cnt = $key + 1;
        ?>
            Party Items <?=$counter?>: <input type="text" name="item[]" size="50" value="<?=$value['slug']?>"> <span class="small_span">Please specify SLUG of item (eg: "https://easyshop.ph/item/<span style="color:red;font-weight:bold">THIS-IS-SLUG</span>")</span>
            <br>
        <?php
            $counter++;
        endforeach; ?>
        <br><br>
    </form>
    <button id="slideProductBtn">Save Changes</button>
</div>


<script type='text/javascript' src='/assets/js/src/vendor/jquery.Jcrop.min.js'></script>
<script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js'></script>
<script type='text/javascript' src='/assets/js/src/manage.js'></script>
<script type='text/javascript' src="/assets/tinymce/plugins/jbimages/js/jquery.form.js"></script>
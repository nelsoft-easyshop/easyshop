<link type="text/css" href="<?=base_url()?>assets/css/sell_item.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" />
<div class="wrapper">

  
  <div class="clear"></div>

  <div class="seller_product_content">

    <div class="inner_seller_product_content">
      <h2 class="f24">Sell an Item</h2>
           <div class="sell_steps sell_steps4">
                <ul>
                    <li>Step 1: Select Category</li>
                    <li>Step 2: Upload Item</li>                   
                    <li>Step 3: Select Shipping Courier</li>
                    <li><span>Step 4: </span> Success</li>
                </ul>
            </div>

      <div class="clear"></div>
     
      <div class="product_upload_success">
        
        <p>
            <img src="<?=base_url()?>assets/images/img_success.png">
                <?php if(!isset($is_edit)): ?>
                    You have <strong>successfully</strong> uploaded <span>1 new item</span>
                <?php else: ?>   
                    You have <strong>successfully</strong> edited your listing for <span><?php echo html_escape($productname);?></span>. 
                <?php endif; ?>  
            <br />
            <a href="<?php echo base_url().'item/'.$slug; ?>">Click here to view</a>
        </p> 
         
         
      </div>
    </div>
  </div>

  <div class="clear"></div>  

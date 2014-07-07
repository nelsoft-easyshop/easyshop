 
 <?php
 $class = "";
 if($typeofview == 1){
  $class = '-list';
 }
      if(isset($items))
      {
       for ($i=0; $i < sizeof($items); $i++) { 
        ?>
        <div class="product<?php echo $class; ?>">
         <a href="<?=base_url()?>item/<?php echo $items[$i]['slug']; ?>">
         <span class="prod_img_wrapper">
	        
		<?php if((intval($items[$i]['is_promote']) == 1) && isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>					  
		      <span class="cd_slide_discount">
			      <span><?php echo number_format($items[$i]['percentage'],0,'.',',');?>%<br>OFF</span>
		      </span>
		<?php endif; ?>
         
	      <span class="prod_img_container">
		  <img alt="<?php echo $items[$i]['name']; ?>" src="<?php echo base_url().$items[$i]['path'].'categoryview/'.$items[$i]['file'];?>">
	      </span>
	 </span>
	 </a>
         
     
     
     <h3 style="  -o-text-overflow: ellipsis;    
         text-overflow:    ellipsis;   
         overflow:hidden;             
         white-space:nowrap;  
         width: 225px; ">
         <a href="<?=base_url()?>item/<?php echo $items[$i]['slug']; ?>"><?php echo html_escape($items[$i]['name']); ?></a>
       </h3>

       <div class="price-cnt">
        <div class="price">
          <span>&#8369;</span> <?php echo number_format($items[$i]['price'],2); ?>
        </div>
        
	<?php if(isset($items[$i]['percentage']) && $items[$i]['percentage'] > 0):?>		
	    <div>
	      <span class="original_price">
		      &#8369; <?PHP echo number_format($items[$i]['original_price'],2,'.',','); ?>
	      </span>	
	      <span style="height: 20px;">
		|&nbsp; <strong> <?PHP echo number_format($items[$i]['percentage'],0,'.',',');?>%OFF</strong>
	      </span>
	    </div>
	<?php endif; ?>
        
      </div>
      <div class="product_info_bottom">
          <div>Condition: <strong><?php echo ($items[$i]['is_free_shipping'])? es_string_limit(html_escape($items[$i]['condition']),15) : html_escape($items[$i]['condition']);?></strong></div>
	  <?PHP if($items[$i]['is_free_shipping']): ?>
	    <span style="float:right;"><span class="span_bg img_free_shipping"></span>
	  <?PHP endif; ?>	
      </div>
      <p>
        <?php echo html_escape($items[$i]['brief']); ?>
      </p>
    </div>

     

  
    <?php
  }
}
?> 
 
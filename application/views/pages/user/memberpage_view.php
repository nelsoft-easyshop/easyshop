
<link type="text/css" href="/assets/css/jquery-ui.css" rel="stylesheet" />
<link type="text/css" href="/assets/css/memberpage.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" media='screen'/>
<link type="text/css"  href='/assets/css/jqpagination.css' rel="stylesheet" media='screen'/>
<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<link type="text/css"  rel="stylesheet" href="/assets/css/chosen.min.css" media="screen"/>
<link type="text/css"  rel="stylesheet" href="/assets/css/font-awesome/css/font-awesome.css" media="screen"/>


<div id = "member_page_body">
    <div class="clear"></div>
    <section>
        <div class="wrapper profile_content">
            <div class="logo"> <a href="/"><span class="span_bg"></span></a> </div>
            <div class="profile_top_nav">               
            
                <div>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li>
                            <span>Setup</span>
                            <ul>
                                <li><a href="javascript:void(0)" onclick="triggerTab('dashboard');">Dashboard</a></li>
                                <!-- <li><a href="javascript:void(0)" onclick="triggerTab('wishlist');">Wishlist</a></li> -->
                                <li><a href="javascript:void(0)" onclick="triggerTab('personal_information');">Personal Information</a></li>
                                <li><a href="javascript:void(0)" onclick="triggerTab('payment');">Payment Accounts</a></li>
                                <li><a href="javascript:void(0)" onclick="triggerTab('delivery_address');">Delivery Address</a></li>
                                <li><a href="javascript:void(0)" onclick="triggerTab('transactions');">On-going Transactions</a></li>
                                <li><a href="javascript:void(0)" onclick="triggerTab('complete_transactions');">Completed Transactions</a></li>
                                <li><a href="javascript:void(0)" onclick="triggerTab('security_settings');">Settings</a></li>
                            </ul>
                        </li>
                        <li><a href="/home/under_construction">News</a></li>
                    </ul>
                </div>
                <div class="member_srch_wrapper">
                    <form method="get" action="/search/search.html">
                    <input type="text" name="q_str" id="member_sch" autocomplete='off'  onblur="this.placeholder = 'Search'" onfocus="this.placeholder = ''" placeholder="Search">
                    
                    <input type="submit" class="span_bg" value="">
                     </form>
                </div>    
                <div id="search_content" class="member_srch_container"></div>     	
            </div>
        </div>
    </section>
    <div class="clear"></div>
    <section>
    
    
        <div class="wrapper quickheader quickheader_css" style="display:<?php echo $hide_quickheader ? "none":""?>;">
            
            <span><strong>Link to your store:</strong></span>
            <div class="disp_vendor_url">
                <a href="/<?php echo $userslug?>" target="_blank">
                    <?php echo base_url()?><span class="disp_userslug"><?php echo $userslug?></span>
                </a>
                <?php if( $render_userslug_edit ):?>
                    <span class="edit_userslug edit_userslug_css"><span class="span_bg edit-lnk"> Edit </span></span>
                <?php endif;?>
            </div>
            
            <span class="quickheader_close" style="cursor:pointer;border:medium 1px;">X</span>
        </div>
        
        
        
        <div class="clear"></div>
        
        <div class="wrapper profile_wrapper">
            <div class="profile_left_nav">
                <div>
                    <div class="avatar">
                        <div class="avatar_edit" style="width:45px; position: relative; float:right"><span class="span_bg" name='avatar_edit_icon'></span>Edit</div>
                        <div class="clear"></div>
                        <div id="avatar_cont">
                            <span>
                                <img src="<?=$image_profile?>" id="user_image">
                            </span>
                        </div>
                        <?php echo form_open_multipart('memberpage/upload_img', 'id="form_image"');?>
                        <input type="file" style="visibility:hidden; height:0px; width:0px; position:absolute;" id="imgupload" accept="image/*" name="userfile"/>
                        <input type='hidden' name='x' value='0' id='image_x'>
                        <input type='hidden' name='y' value='0' id='image_y'>
                        <input type='hidden' name='w' value='0' id='image_w'>
                        <input type='hidden' name='h' value='0' id='image_h'>
                        </form>
                    </div>
                    <div id="div_user_image_prev">
                        <span> Crop your Photo! </span>
                        <img src="" id="user_image_prev">
                        <button>OK</button>
                    </div>
                    <div class="profile_completeness">
                        <span>Profile Completeness</span>
                        <span id="profprog_percentage" value=""></span>
                        <div id="progressbar" class="profile_progress"></div>
                    </div>
                </div>
                <div>
                    <ul class="idTabs member_side_nav"> 
                        <li><a href="#dashboard">Dashboard</a></li>
                        <li><a href="#personal_information" class="<?php echo ($tab=='myinfo')?'selected':'';?>">Personal Information</a></li>
                        <li><a href="#delivery_address">Delivery Address</a></li>
                        <li><a href="#payment" class="<?php echo ($tab=='pmnt')?'selected':'';?>">Payment Accounts</a></li>
                        <li><a href="#transactions" class="<?php echo ($tab=='pending')?'selected':'';?>">On-going Transactions</a></li>
                        <li><a href="#complete_transactions">Completed Transactions</a></li>
                        <li><a href="#security_settings" class="<?php echo ($tab=='settings')?'selected':'';?>">Settings</a></li>
                    </ul> 
                </div>	
            </div>

        <div class="profile_main_content" id="dashboard">
            <h2>Dashboard</h2>
            <div class="progress_bar_panel">
                <div>
                    <h3>Total Posted Items</h3>
                    <input class="db_total_items fm1"  readonly="readonly"  data-value="<?php echo $active_count + $deleted_count;?>"   value="<?php echo $active_count + $deleted_count;?>">
                </div>
                <div>
                    <h3>Active Items</h3>
                    <input class="db_active_items fm1" readonly="readonly"  data-width="150" data-fgColor="#ff4400" data-max="2000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true data-value="<?php echo $active_count;?>"   value="<?php echo $active_count;?>">
                </div>
                <div>
                    <h3>Total Sold Items</h3>
                    <input class="db_sold_items fm1" readonly="readonly"  data-width="150" data-fgColor="#7ad014" data-max="2000" data-thickness=".1" data-angleOffset="180" data-readOnly=true data-displayPrevious=true data-value="<?php echo $sold_count;?>" value="<?php echo $sold_count;?>">
                </div>
            </div>
             
             
             
            <div class="posted_feedbacks_top">
                <h3 class="fm1 f18">Feedback Score:</h3>
                <span>(<?php echo $allfeedbacks['rcount'];?> Feedback/s received)</span>
                <p><?php echo $this->lang->line('rating')[0].':'; ?> 
                    <span>
                        <?php if($allfeedbacks['rating1'] === 0 ):?>
                        You have not received ratings yet.
                    <?php else:?>
                    <?php for($i = 0; $i < $allfeedbacks['rating1']; $i++):?>
                    <img src="/assets/images/star-on.png">
                <?php endfor;?>
                <?php for($i = 0; $i < 5-$allfeedbacks['rating1']; $i++):?>
                <img src="/assets/images/star-off.png">
            <?php endfor;?>
        <?php endif;?>
    </span>
</p>
<p><?php echo $this->lang->line('rating')[1].':'; ?> 
    <span><?php if($allfeedbacks['rating2'] === 0 ):?>
        You have not received ratings yet.
    <?php else:?>
    <?php for($i = 0; $i < $allfeedbacks['rating2']; $i++):?>
    <img src="/assets/images/star-on.png">
<?php endfor;?>
<?php for($i = 0; $i < 5-$allfeedbacks['rating2']; $i++):?>
    <img src="/assets/images/star-off.png">
<?php endfor;?>
<?php endif;?>
</span>
</p>
<p><?php echo $this->lang->line('rating')[2].':'; ?> 
    <span>
        <?php if($allfeedbacks['rating3'] === 0 ):?>
        You have not received ratings yet.
    <?php else:?>
    <?php for($i = 0; $i < $allfeedbacks['rating3']; $i++):?>
    <img src="/assets/images/star-on.png">
<?php endfor;?>
<?php for($i = 0; $i < 5-$allfeedbacks['rating3']; $i++):?>
    <img src="/assets/images/star-off.png">
<?php endfor;?>
<?php endif;?>
</span>
</p>
</div>
<div class="clear"></div>
<div>
    <ul class="idTabs post_items">
        <li><a href="#active_items">Active Items <span class="db_active_items"><?php echo $active_count;?></span></a></li>
        <li><a href="#deleted_items">Deleted Items<span class="db_deleted_items"><?php echo $deleted_count;?></span></a></li>
        <li><a href="#draft_items">Draft Items<span class="db_deleted_items"><?php echo $draft_count;?></span></a></li>
        <li><a href="#dashboard-feedbacks">Feedbacks <span><?php echo $allfeedbacks['afbcount'];?></span></a></li>
        <li><a href="#dashboard-sales">Sales</a></li>
    </ul>
</div>
<div class="clear"></div>

<?php $items_per_page = 10; ?>

<div class="dashboard_table" id="active_items" data-key="active" data-controller="1">
    <h2>Active Items</h2>
    
    <div class="pagination" id="pagination_active">
        <a href="#" class="first" data-action="first">&laquo;</a>
        <a href="#" class="previous" data-action="previous">&lsaquo;</a>
        <input type="text" readonly="readonly" data-max-page="<?php echo ($active_count===0)?1:(ceil($active_count/$items_per_page));?>" data-origmaxpage = "<?php echo ($active_count===0)?1:(ceil($active_count/$items_per_page));?>"/>
        <a href="#" class="next" data-action="next">&rsaquo;</a>
        <a href="#" class="last" data-action="last">&raquo;</a>
    </div>
    
    <div class="post_item_srch_container">
        <input type="text" class="box sch_box item_sch_box" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
        <span class="span_bg sch_btn item_sch_btn"></span>
        <label for="active_sort">Sort By</label>
        <select name="active_sort" class="post_active_sort item_sort_select">
            <option value="1">Last Modified</option>
            <option value="2">Name</option>
            <option value="3">Price</option>
            <option value="4">Availability</option>
            <option value="5"># of Sold Items</option>
        </select>
        <span class="span_bg arrow_sort item_arrow_sort"></span>
        <img src="/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
    </div>
    
    
    <?php if($active_count === 0):?>
        <p><span class='nocontent'>No items on sale.</span></p>
    <?php else:?>
    
    <div class="page_load" style="display:none;text-align:center; margin-top: 50px;">
        <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
    </div>
    
    <?php $pageNum = 0;?>
    <div class="paging" data-page="<?php echo $pageNum;?>">					
        <?php $product_counter = 0; 
        foreach($active_products as $active_product): ?>
        <div class="post_items_content content-paging">
            <div class="post_item_content_left">
                <div class="post_item_img_table">
                                                                              
                <span class="post_item_img_con">
                    <img src="/<?php echo $active_product['path'].'categoryview/'.$active_product['file']; ?>" class="product_img">
                </span>
                </div>
                <p><small>Last modified : <?php echo date_format(date_create($active_product['lastmodifieddate']),'Y-m-d')?></small></p>
                <p class="star_rating_reviews">
                    <?php $rounded_score = round($active_product['average_rating']); ?>
                    <?php for($i = 0; $i < $rounded_score;$i++): ?>
                    <span class="span_bg star_on"></span>
                <?php endfor; ?>
                <?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
                <span class="span_bg star_off"></span>
            <?php endfor; ?>
            <br />
            <span class="span_bg reviews"></span><?php echo $active_product['review_count']; ?> Reviews
        </p>
    </div>
    <div class="post_item_content_right">
        <div class="product_title_container">
            <p class="post_item_product_title fm1"><a href="/item/<?php echo $active_product['slug'];?>"><?php echo html_escape($active_product['name']);?></a></p>
            
            <div class="post_item_button">
                <?php echo form_open('sell/edit/step2'); ?> 
                <input type="hidden" name="p_id" value ="<?php echo $active_product['id_product'];?>" /> 
                <input type="hidden" name="othernamecategory" value ="<?php echo $active_product['cat_other_name'];?>" /> 
                <input class="manage_lnk edit_lnk span_bg" type = "submit" value="Edit Item"> </input>
                <?php echo form_close(); ?> 
                <span class="border_white">|</span>
                
                <?php echo form_open('product/changeDelete'); ?>
                <input type="hidden" name="p_id" value ="<?php echo $active_product['id_product'];?>" /> 
                <input type="hidden" name="action" value ="delete" /> 
                <input class="delete_lnk span_bg" type = "submit" value="Delete Item"> </input>
                <?php echo form_close(); ?>
                
            </div>
        </div>
        <div class="price_container" data-prodprice="<?php echo $active_product['price'];?>">
            <p>
                <span class="f24">&#8369;</span>
                <span class="fm1 f24 orange pad_btm10">
                     <?php echo number_format($active_product['price'],2,'.',',');?>
                </span>
                <br />Price<br />

                <?PHP if($active_product['discount'] > 0): ?>   
                    <small class="original_price"> &#8369; <?php echo number_format($active_product['original_price'],2,'.',','); ?> </small> | <strong> <?php echo number_format( $active_product['percentage'],0,'.',',');?> % OFF  </strong>
                <?PHP endif;?>	
                    
            </p>
            
            <p><span class="fm1 f24 grn pad_btm10"><?php echo $active_product['sold'];?></span><br />Sold Items</p>
            <p>
                <span class="fm1 f24 pad_btm10"><?php echo $active_product['availability'];?></span>
                <br />Available Stock<br />
                
                 <?PHP IF($active_product['is_free_shipping']): ?>
                    <span class="span_bg img_free_shipping"></span>
                 <?PHP ENDIF; ?>
                
                
            </p>
        </div>
        <p><strong>Description:</strong><br />
            <span class="item_prod_desc_content">
                <?php echo html_escape($active_product['brief']); ?>
            </span>
            <span class="show_prod_desc blue f11">Read more</span>
        </p>
        <div class="clear"></div>
        <p class="post_item_category">
            <strong>Category:</strong><br />
            <?php foreach($active_product['parents'] as $parent):?>
            <?php echo $parent;?><?php echo (end($active_product['parents'])===$parent)?'':'<span class="span_bg img_arrow_right"></span>'; ?>
        <?php endforeach; ?>
    </p>
    
    <div class="show_more_options blue"><span class="span_bg"></span><p>View Features and Specifications</p></div>
    <div class="attr_hide">
        <?php $i = 0; 
        foreach($active_product['data_attr'] as $key=>$data_attr): ?>								
        <div class="item_attr_container">
            <div class="item_attr"><?php echo html_escape($key); ?>:</div>
            <div class="item_attr_content">
                <ul>
                    <?php foreach($data_attr as $foo): ?>
                    
                    <li><span><?php echo html_escape($foo['value']);?></span></li>
                    
                <?php endforeach; $i++;?>
            </ul>
        </div>
    </div>
<?php endforeach; ?>
</div>	


</div>
</div>

<?php $product_counter++;?>
    <?php if($product_counter === $items_per_page): 
        $product_counter = 0;
        $pageNum++;
    ?>
        </div><div class="paging" data-page="<?php echo $pageNum;?>">
    <?php endif;  ?>

<?php endforeach; ?>
</div> 	
<?php endif;?>
</div>



<div class="dashboard_table" id="deleted_items" data-key="deleted" data-controller="1">
    <h2>Deleted Items</h2>
    
    <div class="pagination" id="pagination_deleted">
        <a href="#" class="first" data-action="first">&laquo;</a>
        <a href="#" class="previous" data-action="previous">&lsaquo;</a>
        <input type="text" readonly="readonly" data-max-page="<?php echo ($deleted_count===0)?1:(ceil($deleted_count/$items_per_page));?>" data-origmaxpage = "<?php echo ($deleted_count===0)?1:(ceil($deleted_count/$items_per_page));?>" />
        <a href="#" class="next" data-action="next">&rsaquo;</a>
        <a href="#" class="last" data-action="last">&raquo;</a>
    </div>
    
    <div class="post_item_srch_container">
        <input type="text" class="box sch_box item_sch_box" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
        <span class="span_bg sch_btn item_sch_btn"></span>
        <label for="active_sort">Sort By</label>
        <select name="active_sort" class="post_active_sort item_sort_select">
            <option value="1">Date of Entry</option>
            <option value="2">Name</option>
            <option value="3">Price</option>
            <option value="4">Availability</option>
            <option value="5"># of Sold Items</option>
        </select>
        <span class="span_bg arrow_sort item_arrow_sort"></span>
        <img src="/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
    </div>
    
    <?php if($deleted_count == 0):?>
        <p><span class='nocontent'>No deleted items.</span></p>
    <?php else:?>
    
    <div class="page_load" style="display:none;text-align:center; margin-top:50px;">
        <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
    </div>
    
    <?php $pageNum = 0;?>
    <div class="paging" data-page="<?php echo $pageNum;?>">
        <?php $product_counter =0; $mycounter = 0;?>
        <?php foreach($deleted_products as $deleted_product):?>
        <div class="post_items_content content-paging">
        <div class="post_item_content_left">
                <div class="post_item_img_table">

                    <span class="post_item_img_con">
                        <img src="/<?php echo $deleted_product['path'].'categoryview/'.$deleted_product['file']; ?>" class="product_img">
                    </span>
                </div>
                <p><small>Last modified : <?php echo date_format(date_create($deleted_product['lastmodifieddate']),'Y-m-d')?></small></p>
                <p>
                    <?php $rounded_score = round($deleted_product['average_rating']); ?>
                    <?php for($i = 0; $i < $rounded_score ;$i++): ?>
                    <span class="span_bg star_on"></span>
                <?php endfor; ?>
                <?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
                <span class="span_bg star_off"></span>
            <?php endfor; ?>
            <br />
            <span class="span_bg reviews"></span><?php echo $deleted_product['review_count']; ?> Reviews
        </p>
    </div>
    <div class="post_item_content_right">
        <div class="product_title_container">
            <p class="post_item_product_title">
                <?php echo html_escape($deleted_product['name']);?>
            </p>
            <div class="post_item_button">
                <?php echo form_open('product/changeDelete'); ?>
                <input type="hidden" name="p_id" value ="<?php echo $deleted_product['id_product'];?>" /> 
                <input type="hidden" name="action" value ="restore" /> 
                <input class="manage_lnk restore_lnk span_bg" type = "submit" value="Restore Item"> </input>
                <?php echo form_close(); ?>
                
                <span class="border_white">|</span>
                <?php $attributes = array('class'=>'fulldelete'); ?>
                <?php echo form_open('product/changeDelete', $attributes); ?>
                    <input type="hidden" name="p_id" value ="<?php echo $deleted_product['id_product'];?>" /> 
                    <input type="hidden" name="action" value ="fulldelete" /> 
                    <input class="delete_lnk span_bg" type = "submit" value="Remove"> </input>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="price_container"> 
            <p>
                <span class="fm1 f24 orange">PHP <?php echo number_format($deleted_product['price'],2,'.',',');?></span>
                <br />Price <br/>
                
                
                <?PHP if($deleted_product['discount'] > 0): ?>   
                    <small class="original_price"> &#8369; <?php echo number_format($deleted_product['original_price'],2,'.',','); ?> </small> | <strong> <?php echo number_format( $deleted_product['percentage'],0,'.',',');?> % OFF  </strong>
                <?PHP endif;?>	

            </p>
          <p>
              <p><span class="fm1 f24 grn"><?php echo $deleted_product['sold'];?></span><br />Sold Items</p>
              <p><span class="fm1 f24"><?php echo $deleted_product['availability'];?></span>
              <br />Available Stock <br/>
              
              <?PHP IF($deleted_product['is_free_shipping']): ?>
                <span class="span_bg img_free_shipping"></span>
              <?PHP ENDIF; ?>
              </p>
          </p>
        </div>
        <p><strong>Description:</strong><br />
            <span class="item_prod_desc_content">
                <?php echo html_escape($deleted_product['brief']); ?>
            </span>
            <span class="show_prod_desc blue f11">Read more</span>
        </p>
        <div class="clear"></div>
        <p class="post_item_category">
            <strong>Category:</strong><br />
            <?php foreach($deleted_product['parents'] as $parent):?>
            <?php echo $parent;?><?php echo (end($deleted_product['parents'])===$parent)?'':'<span class="span_bg img_arrow_right"></span>'; ?>
        <?php endforeach; ?>
    </p>
    <div class="show_more_options blue"><span class="span_bg"></span><p>View Features and Specifications</p></div>
    <div class="attr_hide">
        
        <?php $i = 0; 
        foreach($deleted_product['data_attr'] as $key=>$data_attr): ?>								
        <div class="item_attr_container">
            <div class="item_attr"><?php echo html_escape($key); ?>:</div>
            <div class="item_attr_content">
                <ul>
                    <?php foreach($data_attr as $foo): ?>
                    
                    <li><span><?php echo html_escape($foo['value']);?></span></li>
                    
                <?php endforeach; $i++;?>
            </ul>
        </div>
    </div>
<?php endforeach; ?>	
</div>
</div>   
</div>



<?php $product_counter++;$mycounter++;?>
    <?php if($product_counter === $items_per_page): 
        $product_counter = 0; 
        $pageNum++;
    ?>
    </div><div class="paging" data-page="<?php echo $pageNum?>">
    <?php endif;  ?>
<?php endforeach; ?>
</div>
<?php endif;?>
</div>

<!-- Start of draft items dashboard -->
<div class="dashboard_table" id="draft_items" data-key="draft" data-controller="1">
    <h2>Draft Items</h2>
    
    <div class="pagination" id="pagination_draft">
        <a href="#" class="first" data-action="first">&laquo;</a>
        <a href="#" class="previous" data-action="previous">&lsaquo;</a>
        <input type="text" readonly="readonly" data-max-page="<?php echo ($draft_count===0)?1:(ceil($draft_count/$items_per_page));?>" data-origmaxpage = "<?php echo ($draft_count===0)?1:(ceil($draft_count/$items_per_page));?>"/>
        <a href="#" class="next" data-action="next">&rsaquo;</a>
        <a href="#" class="last" data-action="last">&raquo;</a>
    </div>
    
    <div class="post_item_srch_container">
        <input type="text" class="box sch_box item_sch_box" placeholder="Search" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
        <span class="span_bg sch_btn item_sch_btn"></span>
        <label for="active_sort">Sort By</label>
        <select name="active_sort" class="post_active_sort item_sort_select">
            <option value="1">Date of Entry</option>
            <option value="2">Name</option>
            <option value="3">Price</option>
            <option value="4">Availability</option>
            <option value="5"># of Sold Items</option>
        </select>
        <span class="span_bg arrow_sort item_arrow_sort"></span>
        <img src="/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
    </div>
    
    
    <?php if($draft_count === 0):?>
        <p><span class='nocontent'>No items on sale.</span></p>
    <?php else:?>
    
    <div class="page_load" style="display:none;text-align:center; margin-top: 50px;">
        <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
    </div>
    
    <?php $pageNum = 0;?>
    <div class="paging" data-page="<?php echo $pageNum;?>">					
        <?php $product_counter = 0; 
        foreach($draft_products as $draft_product): ?>
        <div class="post_items_content content-paging">
            <div class="post_item_content_left">
                <div class="post_item_img_table">

                <span class="post_item_img_con">
                    <img src="/<?php echo $draft_product['path'].'categoryview/'.$draft_product['file']; ?>" class="product_img">
                </span>
                </div>
                <p><small>Last modified : <?php echo date_format(date_create($draft_product['lastmodifieddate']),'Y-m-d')?></small></p>
                <p class="star_rating_reviews">
                    <?php $rounded_score = round($draft_product['average_rating']); ?>
                    <?php for($i = 0; $i < $rounded_score;$i++): ?>
                    <span class="span_bg star_on"></span>
                <?php endfor; ?>
                <?php for($i = 0; $i < 5-$rounded_score;$i++): ?>
                <span class="span_bg star_off"></span>
            <?php endfor; ?>
            <br />
            <span class="span_bg reviews"></span><?php echo $draft_product['review_count']; ?> Reviews
        </p>
    </div>
    <div class="post_item_content_right">
        <div class="product_title_container">
            <p class="post_item_product_title fm1"><span><?php echo html_escape($draft_product['name']);?></span></p>
            
            <div class="post_item_button">
                <?php echo form_open('sell/edit/step2'); ?>
                <input type="hidden" name="p_id" value ="<?php echo $draft_product['id_product'];?>" /> 
                <input type="hidden" name="othernamecategory" value ="<?php echo $draft_product['cat_other_name'];?>" /> 
                <input class="manage_lnk edit_lnk span_bg" type = "submit" value="Edit Item"> </input>
                <?php echo form_close(); ?> 
                <span class="border_white">|</span>
                
                <?php echo form_open('product/changeDelete'); ?>
                <input type="hidden" name="p_id" value ="<?php echo $draft_product['id_product'];?>" /> 
                <input type="hidden" name="action" value ="delete" /> 
                <input class="delete_lnk span_bg" type = "submit" value="Delete Item"> </input>
                <?php echo form_close(); ?>
                
            </div>
        </div>
        <div class="price_container" data-prodprice="<?php echo $draft_product['price'];?>">
            <p>
                <span class="f24">&#8369;</span>
                <span class="fm1 f24 orange pad_btm10">
                     <?php echo number_format($draft_product['price'],2,'.',',');?>
                </span>
                <br />Price<br />

                <?PHP if($draft_product['discount'] > 0): ?>   
                    <small class="original_price"> &#8369; <?php echo number_format($draft_product['original_price'],2,'.',','); ?> </small> | <strong> <?php echo number_format( $draft_product['percentage'],0,'.',',');?> % OFF  </strong>
                <?PHP endif;?>	
                    
            </p>
            
            <p><span class="fm1 f24 grn pad_btm10"><?php echo $draft_product['sold'];?></span><br />Sold Items</p>
            <p>
                <span class="fm1 f24 pad_btm10"><?php echo $draft_product['availability'];?></span>
                <br />Available Stock<br />
                
                 <?PHP IF($draft_product['is_free_shipping']): ?>
                    <span class="span_bg img_free_shipping"></span>
                 <?PHP ENDIF; ?>
                
                
            </p>
        </div>
        <p><strong>Description:</strong><br />
            <span class="item_prod_desc_content">
                <?php echo html_escape($draft_product['brief']); ?>
            </span>
            <span class="show_prod_desc blue f11">Read more</span>
        </p>
        <div class="clear"></div>
        <p class="post_item_category">
            <strong>Category:</strong><br />
            <?php foreach($draft_product['parents'] as $parent):?>
            <?php echo $parent;?><?php echo (end($draft_product['parents'])===$parent)?'':'<span class="span_bg img_arrow_right"></span>'; ?>
        <?php endforeach; ?>
    </p>
    
    <div class="show_more_options blue"><span class="span_bg"></span><p>View Features and Specifications</p></div>
    <div class="attr_hide">
        <?php $i = 0; 
        foreach($draft_product['data_attr'] as $key=>$data_attr): ?>								
        <div class="item_attr_container">
            <div class="item_attr"><?php echo html_escape($key); ?>:</div>
            <div class="item_attr_content">
                <ul>
                    <?php foreach($data_attr as $foo): ?>
                    
                    <li><span><?php echo html_escape($foo['value']);?></span></li>
                    
                <?php endforeach; $i++;?>
            </ul>
        </div>
    </div>
<?php endforeach; ?>
</div>	


</div>
</div>

<?php $product_counter++;?>
    <?php if($product_counter === $items_per_page): 
        $product_counter = 0;
        $pageNum++;
    ?>
        </div><div class="paging" data-page="<?php echo $pageNum;?>">
    <?php endif;  ?>

<?php endforeach; ?>
</div> 	
<?php endif;?>
</div>
<!-- End of draft items dashboard -->




<div class="dashboard_table" id="dashboard-feedbacks">
    <h2>Feedbacks</h2>
    <ul class="idTabs feedbacks_tabs">
        <li><a href="#op_buyer">Feedbacks from Sellers</a></li>
        <li><a href="#op_seller">Feedbacks from Buyers</a></li>
        <li><a href="#yp_buyer">Feedbacks to Sellers</a></li>
        <li><a href="#yp_seller">Feedbacks to Buyers</a></li>
    </ul>

    <div class="clear"></div>
    <div id="others_post">

        <div id="op_buyer">
            <h4>Feedbacks others left for you as a buyer</h4>
            <?php if(count($allfeedbacks['otherspost_buyer'])==0):?>
            <p><span class='nocontent'>You have not yet received any feedbacks for this category.</span></p>
        <?php else:?>
        <?php $afb_counter = 0;?>
        <div class="paging posted_feedbacks">
            <?php foreach($allfeedbacks['otherspost_buyer'] as $k=>$tempafb):?>
            <div>
                <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
                <?php foreach($tempafb as $key=>$afb):?>
                <p>From: <a href="/<?php echo $afb['member_slug'];?>"><?php echo $afb['member_name'];?></a> | on: <?php echo $afb['dateadded'];?></p>
                <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
                <p><?php echo $this->lang->line('rating')[0].':'; ?> 
                    <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                    <span class="span_bg star_on"></span>
                <?php endfor;?>
                <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
                <span class="span_bg star_off"></span>
            <?php endfor;?>
        </p>
        <p><?php echo $this->lang->line('rating')[1].':'; ?> 
            <?php for($i = 0; $i < $afb['rating2']; $i++):?>
            <span class="span_bg star_on"></span>
        <?php endfor;?>
        <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
        <span class="span_bg star_off"></span>
    <?php endfor;?>
</p>
<p><?php echo $this->lang->line('rating')[2].':'; ?>  
    <?php for($i = 0; $i < $afb['rating3']; $i++):?>
    <span class="span_bg star_on"></span>
<?php endfor;?>
<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<?php endforeach;?>
</div>
<?php $afb_counter++;?>
<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
</div><div class="paging">
<?php endif;?>

<?php endforeach;?>
</div>
<div class="pagination" id="pagination-opbuyer">
    <a href="#" class="first" data-action="first">&laquo;</a>
    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
    <input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['otherspost_buyer'])===0)?1:(ceil(count($allfeedbacks['otherspost_buyer'])/$items_per_page));?>" />
    <a href="#" class="next" data-action="next">&rsaquo;</a>
    <a href="#" class="last" data-action="last">&raquo;</a>
</div>
<?php endif;?>						
</div>


<div id="op_seller">
    <h4>Feedbacks others left for you as a seller</h4>
    <?php if(count($allfeedbacks['otherspost_seller'])==0):?>
    <p><span class='nocontent'>You have not yet received any feedbacks for this category.</span></p>
<?php else:?>
    <?php $afb_counter = 0;?>
    <div class="paging posted_feedbacks">
        <?php foreach($allfeedbacks['otherspost_seller'] as $k=>$tempafb):?>
        
        <div>
            <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
            <?php foreach($tempafb as $afb):?>
            <p>From: <a href="/<?php echo $afb['member_slug'];?>"><?php echo $afb['member_name'];?></a> | on: <?php echo $afb['dateadded'];?></p>
            <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
            <p><?php echo $this->lang->line('rating')[0].':'; ?> 
                <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                <span class="span_bg star_on"></span>
            <?php endfor;?>
            <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
            <span class="span_bg star_off"></span>
        <?php endfor;?>
    </p>
    <p><?php echo $this->lang->line('rating')[1].':'; ?> 
        <?php for($i = 0; $i < $afb['rating2']; $i++):?>
        <span class="span_bg star_on"></span>
    <?php endfor;?>
    <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<p><?php echo $this->lang->line('rating')[2].':'; ?> 
    <?php for($i = 0; $i < $afb['rating3']; $i++):?>
    <span class="span_bg star_on"></span>
<?php endfor;?>
<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<?php endforeach;?>
</div>
<?php $afb_counter++;?>
<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
</div><div class="paging">
<?php endif;?>
<?php endforeach;?>
</div>
<div class="pagination" id="pagination-opseller">
    <a href="#" class="first" data-action="first">&laquo;</a>
    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
    <input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['otherspost_seller'])===0)?1:(ceil(count($allfeedbacks['otherspost_seller'])/$items_per_page));?>" />
    <a href="#" class="next" data-action="next">&rsaquo;</a>
    <a href="#" class="last" data-action="last">&raquo;</a>
</div>
<?php endif;?>
</div>
<div id="yp_buyer">
    <h4>Feedbacks you posted as a buyer</h4>
    <?php if(count($allfeedbacks['youpost_buyer'])==0):?>
    <p><span class='nocontent'>You have not yet received any feedbacks for this category.</span></p>
<?php else:?>
    <?php $afb_counter = 0;?>
    <div class="paging posted_feedbacks">
        <?php foreach($allfeedbacks['youpost_buyer'] as $k=>$tempafb):?>
        <div>
            <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
            <?php foreach($tempafb as $afb):?>
            <p>For: <a href="/<?php echo $afb['for_memberslug'];?>"><?php echo $afb['for_membername'];?></a> | on: <?php echo $afb['dateadded'];?></p>
            <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
            <p><?php echo $this->lang->line('rating')[0].':'; ?> 
                <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                <span class="span_bg star_on"></span>
            <?php endfor;?>
            <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
            <span class="span_bg star_off"></span>
        <?php endfor;?>
    </p>
    <p><?php echo $this->lang->line('rating')[1].':'; ?>  
        <?php for($i = 0; $i < $afb['rating2']; $i++):?>
        <span class="span_bg star_on"></span>
    <?php endfor;?>
    <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<p><?php echo $this->lang->line('rating')[2].':'; ?> 
    <?php for($i = 0; $i < $afb['rating3']; $i++):?>
    <span class="span_bg star_on"></span>
<?php endfor;?>
<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<?php endforeach;?>
</div>
<?php $afb_counter++;?>
<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
</div><div class="paging">
<?php endif;?>	
<?php endforeach;?>
</div>
<div class="pagination" id="pagination-ypbuyer">
    <a href="#" class="first" data-action="first">&laquo;</a>
    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
    <input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['youpost_buyer'])===0)?1:(ceil(count($allfeedbacks['youpost_buyer'])/$items_per_page));?>" />
    <a href="#" class="next" data-action="next">&rsaquo;</a>
    <a href="#" class="last" data-action="last">&raquo;</a>
</div>
<?php endif;?>
</div>
<div id="yp_seller">
    <h4>Feedbacks you posted as a seller</h4>
    <?php if(count($allfeedbacks['youpost_seller'])==0):?>
    <p><span  class='nocontent'>You have not yet received any feedbacks for this category.</span></p>
<?php else:?>
    <?php $afb_counter = 0;?>
    <div class="paging posted_feedbacks">
        <?php foreach($allfeedbacks['youpost_seller'] as $k=>$tempafb):?>
        <div>
            <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
            <?php foreach($tempafb as $afb):?>
            <p>For: <a href="/<?php echo $afb['for_memberslug'];?>"><?php echo $afb['for_membername'];?></a> | on: <?php echo $afb['dateadded'];?></p>
            <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
            <p><?php echo $this->lang->line('rating')[0].':'; ?> 
                <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                <span class="span_bg star_on"></span>
            <?php endfor;?>
            <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
            <span class="span_bg star_off"></span>
        <?php endfor;?>
    </p>
    <p><?php echo $this->lang->line('rating')[1].':'; ?> 
        <?php for($i = 0; $i < $afb['rating2']; $i++):?>
        <span class="span_bg star_on"></span>
    <?php endfor;?>
    <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<p><?php echo $this->lang->line('rating')[2].':'; ?> 
    <?php for($i = 0; $i < $afb['rating3']; $i++):?>
    <span class="span_bg star_on"></span>
<?php endfor;?>
<?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
    <span class="span_bg star_off"></span>
<?php endfor;?>
</p>
<?php endforeach;?>
</div>
<?php $afb_counter++;?>
<?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
</div><div class="paging">
<?php endif;?>
<?php endforeach;?>
</div>
<div class="pagination" id="pagination-ypseller">
    <a href="#" class="first" data-action="first">&laquo;</a>
    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
    <input type="text" readonly="readonly" data-max-page="<?php echo (count($allfeedbacks['youpost_seller'])===0)?1:(ceil(count($allfeedbacks['youpost_seller'])/$items_per_page));?>" />
    <a href="#" class="next" data-action="next">&rsaquo;</a>
    <a href="#" class="last" data-action="last">&raquo;</a>
</div>
<?php endif;?>
</div>
</div>

</div>

    <div id="dashboard-sales" class="dashboard_table">
        <h2>Sales</h2>
        <div>
            <table class="sales_summary" cellspacing="0" cellpadding="0">
                <tr class="sales_info" data-div="balance">
                    <td class="label">Total:</td>
                    <td class="amount">Php <?php echo number_format(round($sales['balance']['balance'],2), 2)?></td>
                    <td class="sales_info blue" data-div="balance" align="center">View More Details</td>
                </tr>
                <tr class="sales_info" data-div="payout">
                    <td class="label">Next Payout:</td>
                    <td class="amount">Php <?php echo number_format(round($sales['release']['payout'],2), 2)?></td>
                    <td class="sales_info blue" data-div="payout" align="center">View More Details</td>
                </tr>
            </table>
        </div>
        
        <div id="sales_balance" style="display:none;" class="sales_details">
                <h2>Total Amount : Php <?php echo number_format(round($sales['balance']['balance'],2), 2)?></h2>
                <table class="sales_details_content" cellpadding="0" cellspacing="0">
                    <tr class="header">
                        <th>Product Name</th>
                        <th>Base Price</th>
                        <th>Quantity</th>
                        <th>Handling Fee</th>
                        <th>Total</th>
                        <th>Easyshop Charge</th>
                        <th>Payment Method Charge</th>
                        <th>Net Amount</th>
                    </tr>
                <?php foreach($sales['balance']['list'] as $balance):?>
                    <tr class="tx_header">
                        <th>Transaction #: <?php echo $balance['invoice'];?></th>
                        <th colspan="7"></th>
                    </tr>
                    <?php foreach($balance['product'] as $product):?>
                    <tr class="tx_details">
                        <td><?php echo html_escape($product['name'])?></td>
                        <td><?php echo number_format(round($product['base_price'],2),2)?></td>
                        <td><?php echo $product['qty']?></td>
                        <td><?php echo number_format(round($product['handling_fee'],2),2)?></td>
                        <td><?php echo number_format(round($product['prd_total_price'],2),2)?></td>
                        <td><?php echo number_format(round($product['easyshop_charge'],2),2)?></td>
                        <td><?php echo number_format(round($product['payment_method_charge'],2),2)?></td>
                        <td><?php echo number_format(round($product['prd_net'],2),2)?></td>
                    </tr>
                    <?php endforeach;?>
                    <tr class="tx_total">
                        <td colspan="6"></td>
                        <td><strong>Transaction Net Amount : </strong></td>
                        <td><strong><?php echo number_format(round($balance['tx_net'],2),2)?></strong></td>
                    </tr>
                <?php endforeach;?>
                </table>
        </div>
    
        <div id="sales_payout" style="display:none;" class="sales_details">
                <h2>Payout Amount: Php <?php echo number_format(round($sales['release']['payout'],2), 2)?></h2> 
                <span id="payout_date">To be credited on: <strong><?php echo $sales['release']['payout_date'];?></strong></span> 
                <span>From: <strong><?php echo $sales['release']['start_date']?></strong> To: <strong><?php echo $sales['release']['end_date']?></strong></span>
                <table class="sales_details_content" cellpadding="0" cellspacing="0">
                    <tr class="header">
                        <th>Product Name</th>
                        <th>Base Price</th>
                        <th>Quantity</th>
                        <th>Handling Fee</th>
                        <th>Total</th>
                        <th>Easyshop Charge</th>
                        <th>Payment Method Charge</th>
                        <th>Net Amount</th>
                    </tr>
                <?php foreach($sales['release']['list'] as $release):?>
                    <tr class="tx_header">
                        <th>Transaction # : <?php echo $release['invoice']?></th>
                        <th colspan="7"></th>
                    </tr>
                    <?php foreach($release['product'] as $product):?>
                    <tr class="tx_details">
                        <td><?php echo html_escape($product['name'])?></td>
                        <td><?php echo number_format(round($product['base_price'],2),2)?></td>
                        <td><?php echo $product['qty']?></td>
                        <td><?php echo number_format(round($product['handling_fee'],2),2)?></td>
                        <td><?php echo number_format(round($product['prd_total_price'],2),2)?></td>
                        <td><?php echo number_format(round($product['easyshop_charge'],2),2)?></td>
                        <td><?php echo number_format(round($product['payment_method_charge'],2),2)?></td>
                        <td><?php echo number_format(round($product['prd_net'],2),2)?></td>
                    </tr>
                    <?php endforeach;?>
                    <tr class="tx_total">
                        <td colspan="6"></td>
                        <td><strong>Transaction Net Amount : </strong></td>
                        <td><strong><?php echo number_format(round($release['tx_net'],2),2)?></strong></td>
                    </tr>
                <?php endforeach;?>
                </table>
        </div>
            
    </div>

</div>

        <div class="profile_main_content" id="personal_information">
            <!--<form method="post" id="personal_profile_main" name="personal_profile_main">-->
            <?php 
            $attr=array('id'=>'personal_profile_main', 'name'=>'personal_profile_main');
            echo form_open('',$attr);
            ?>
            <h2>Personal Information</h2>
            <div class="clear"></div>
            <div class="profile_fields progress_update update_all" >
                <div>
                    <label for="nickname">Nickname:</label>
                    <input name="nickname" type="text" value="<?php echo html_escape($nickname)?>">
                </div>
                <div>
                    <label for="fullname">Real name:</label>
                    <input name="fullname" type="text" value="<?php echo html_escape($fullname)?>">
                </div>
                <div>
                    <label for="gender">Gender:</label>
                    <input type="radio" name="gender" value="M" <?php echo ($gender=='M'?'checked="true"':'') ?>/> Male
                    <input type="radio" name="gender" value="F" <?php echo ($gender=='F'?'checked="true"':'') ?>/> Female
                </div>
                <div>
                    <label for="dateofbirth">Birthday:</label>
                    <input type="text" name="dateofbirth" id="datepicker" value="<?php echo html_escape($birthday == '0000-00-00' || $birthday == '0001-01-01'?'':$birthday)?>">
                    <span class="red ci_form_validation_error"><?php echo form_error('dateofbirth'); ?></span>
                </div>	

                <div id="mobilediv">
                    <label for="mobile">Mobile:</label>
                    <input type="text" name="mobile" id="mobile" maxlength="11" value="<?php echo html_escape($contactno);?>" <?php echo (trim($contactno)==''?'':'disabled');?> placeholder="e.g. 09051234567">
                    <span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
                    <input type="hidden" name="mobile_orig" value="<?php echo $contactno;?>">
                    <input type="hidden" name="is_contactno_verify" value="<?php echo $is_contactno_verify;?>">

                    
                    <span class="personal_contact_cont" style="<?php echo trim($contactno)!==''?'':'display:none;' ?>">
                        <span class="edit_personal_contact">
                            <span class="span_bg edit_btn"></span><span>Edit</span>
                        </span>
                        <span  class="cancel_personal_contact">
                            <span class="span_bg cancel_btn"></span><span>Cancel</span>
                        </span>
                    </span>
                    
                    <span class="red ci_form_validation_error"><?php echo form_error('mobile'); ?></span>
                </div>
                
                <div id="cont_mobilediv" class="errordiv" style="display:none;">
                    <span></span>
                </div>
                
                <div id="verifcode_div" style="display:none;">
                    <p>Verification code sent. Please enter the verification code below:</p>
                    <input type="text" name="verifcode" id="verifcode">
                    <p class="verifcode_error error red" style="display:none;">Incorrect verification code.</p>
                </div>
                
                <div id="emaildiv">
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" value="<?php echo html_escape($email);?>" <?php echo (trim($email)==''?'':'disabled');?>>
                    <span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
                    <input type="hidden" name="email_orig" value="<?php echo $email;?>">
                    <input type="hidden" name="is_email_verify" value="<?php echo $is_email_verify;?>">
                    
                    <span class="verify toverify" style="<?php echo $is_email_verify == 0 && trim($email) !== ''?'':'display:none;'?>">
                        <span class="verify_now">Verify Now</span>
                        <img src="/assets/images/orange_loader_small.gif" class="verify_img" style="display:none"/>
                    </span>	
                    <span class="verify doneverify" style="<?php echo $is_email_verify == 0?'display:none;':''?>">
                        <span class="span_bg chk_img"></span><span style='font-size:14px; font-weight:bold; color:#1f4f98;'><strong>Verified</strong></span>
                    </span>
                    
                    <span class="personal_contact_cont" style="<?php echo trim($email)!==''?'':'display:none;' ?>">
                        <span class="edit_personal_contact">
                            <span class="span_bg edit_btn"></span><span>Edit</span>
                        </span>
                        <span  class="cancel_personal_contact">
                            <span class="span_bg cancel_btn"></span><span>Cancel</span>
                        </span>
                    </span>
                    
                    <span class="red ci_form_validation_error"><?php echo form_error('email'); ?></span>
                </div>	
                
                <div id="cont_emaildiv" class="errordiv" style="display:none;">
                    <span></span>
                </div>
                
                <div class="save_con">
                    <input type="submit" name="personal_profile_main" value="Save" id="ppm_btn"/>
                
                </div>

                <input type="hidden" class="progress_update_hidden" value="">
            </div>
            <?php echo form_close();?>

            <div>
                <!--<form method="post" id="personal_profile_address" name="personal_profile_address" class="dropdownform">-->
                <?php
                $attr = array('id'=>'personal_profile_address','name'=>'personal_profile_address', 'class'=>'dropdownform');
                echo form_open('',$attr);
                ?>
                <div class="personal_info_title">
                    <span class="personal_info_icon address_img span_bg"></span> Address
                </div>
                <div class="edit_profile">
                    <h2>+ Add an Address</h2>
                </div>
                <div class="address_information gen_information">
                    <div class="add_info echoed_info">
                        <?php if(trim($stateregion) != '' && trim($city) != ''):?>
                            <?php echo $stateregion . ', ' . $city . '<br>' . html_escape($address)?>
                        <?php endif;?>
                    </div>
                    <div class="edit_address edit_info_btn">
                        <span><span class="span_bg edit_btn"></span> Edit</span>
                    </div>
                    <div class="delete_information" name="del_address">
                        <span><span class="span_bg delete_btn"></span> Delete</span>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <!--<input id="json_city" value='<?php echo $json_city;?>' type="hidden">-->
                
                <div class="edit_fields profile_fields">
                    <div class="inner_profile_fields">
                        <div class="address_fields progress_update update_once">
                            <div class="address_fields_layer1">
                                <div>
                                    <select name="stateregion" id="personal_stateregion" class="address_dropdown stateregionselect" data-status="<?php echo $stateregionID?>">
                                        <option value="0">--- Select State/Region ---</option>
                                        <?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
                                            <option class="echo" value="<?php echo $srkey?>" <?php echo $stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <p>State/Region</p>
                                    <input type="hidden" name="stateregion_orig" value="<?php echo $stateregionID?>">
                                </div>
                                <div>
                                    <select name="city" id="personal_city" class="address_dropdown cityselect" data-status="<?php echo $cityID?>">
                                        <option value="0">--- Select City ---</option>
                                        <option class="optionclone" value="" style="display:none;" disabled></option>
                                        
                                        <?php if($cityID != '' && $stateregionID != ''):?>
                                            <?php foreach($city_lookup[$stateregionID] as $lockey=>$city):?>
                                                <option class="echo" value="<?php echo $lockey?>" <?php echo $cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        
                                    </select>
                                    <p>City</p>
                                    <input type="hidden" name="city_orig" value="<?php echo $cityID?>">
                                </div>
                                <div>
                                    <select class="disabled_country" disabled>
                                        <option selected=""><?php echo $country_name?></option>
                                    </select>
                                    <input type="hidden" name="country" value="<?php echo $country_id?>">
                                    <p>Country</p>
                                </div>
                            </div>
                            <div class="address_fields_layer2">
                                <div>
                                    <input type="text" name="address" value="<?php echo html_escape($address)?>">
                                    <p>Street Address</p>
                                    <input type="hidden" name="address_orig" value="<?php echo html_escape($address)?>"> 
                                </div>
                            </div>
                            <input type="hidden" name="addresstype" value="0"/>
                            <div class="clear"></div>
                            <input type="hidden" class="progress_update_hidden" value="">
                        </div>
                        
                        <div class="view_map_btn">
                            <input type="button" class="view_map" value="Mark on map" name="personal">&nbsp;| 
                            <?php if($lat == 0 && $lng == 0):?>
                                <span class="maploc_stat">Location not marked</span>
                            <?php else:?>
                                <span class="maploc_stat">Location marked</span>
                            <?php endif;?>
                            <input type="hidden" name="map_lat" id="map_lat" value="<?php echo $lat;?>">
                            <input type="hidden" name="map_lng" id="map_lng" value="<?php echo $lng;?>">
                            <input type="hidden" name="temp_lat" id="temp_lat" value="<?php echo $lat;?>">
                            <input type="hidden" name="temp_lng" id="temp_lng" value="<?php echo $lng;?>">
                        </div>
                        
                        <div class="map_nav" style="display: none">
                            <span class="refresh_map" name="personal_rmap">Search address</span>
                            <span class="current_loc" name="personal_cmap">Current location</span>
                            <a class="close" href="javascript:void(0)">Close</a>
                            <div id="GoogleMapContainer" title="Google Map Container"></div>
                        </div>
                        
                        <div id="personal_mapcanvas" class="map-canvas"></div>
                        
                        <div class="clear"></div>
                        <div class="btn_con">
                            <span class="cancel" name="cancel_address">Cancel</span>
                            <input type="submit" name="personal_profile_address_btn" class="save_address" value="save">
                        
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="error_container" style="padding-left:100px"></div>
                </div>
                <?php echo form_close();?>
                
                <div class="clear"></div>
                <div>
                    <!--<form method="post" id="personal_profile_school" name="personal_profile_school" class="dropdownform">-->
                    <?php
                    $attr = array('id'=>'personal_profile_school', 'name'=>'personal_profile_school', 'class'=>'dropdownform');
                    echo form_open('', $attr);
                    ?>
                    <div class="personal_info_title">
                        <span class="personal_info_icon school_img span_bg"></span> School
                    </div>
                    <div class="edit_profile">
                        <h2>+ Add a School</h2>
                    </div>
                    <div class="school_information gen_information">
                        <div class="school_info echoed_info">
                            <?php 	if(count($school)>0){
                                foreach ($school as $i){
                                    echo '<p>'. html_escape($i['schoolname']) .' '. html_escape($i['schoolyear']) .' ';
                                    switch ($i['schoollevel']){
                                        case '1': echo $this->lang->line('schoollevel_option')[1]; break;
                                        case '2': echo $this->lang->line('schoollevel_option')[2]; break;
                                        case '3': echo $this->lang->line('schoollevel_option')[3]; break;
                                        case '4': echo $this->lang->line('schoollevel_option')[4]; break;
                                        case '5': echo $this->lang->line('schoollevel_option')[5]; break;
                                        default: echo $this->lang->line('schoollevel_option')[0];
                                    }
                                    echo '</p>';
                                }
                            }
                            ?> 
                        </div>
                        <div class="edit_school edit_info_btn">
                            <span><span class="span_bg edit_btn"></span> Edit</span>
                        </div>
                        <div class="delete_information" name="del_school">
                            <span><span class="span_bg delete_btn"></span> Delete</span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="edit_fields profile_fields">
                        <div class="inner_profile_fields school_fields progress_update update_once">
                            <div id="add_school">
                                <div>
                                    <input type="text" name="schoolname1" value="<?php echo isset($school[0]['schoolname'])?html_escape($school[0]['schoolname']):"";?> ">
                                    <p>School Name</p>
                                </div>
                                <div>
                                    <input type="text" name="schoolyear1" class="year" maxlength="4" value="<?php echo isset($school[0]['schoolyear'])?html_escape($school[0]['schoolyear']):"";?>">
                                    <p>Year</p>
                                </div>
                                <div>
                                    <select name="schoollevel1" data-status="<?php echo isset($school[0]['schoollevel'])?$school[0]['schoollevel']:"";?>">
                                        <option value="0" <?php echo isset($school[0]['schoollevel'])?"":"selected";?> ><?php echo $this->lang->line('schoollevel_option')[0]?></option>
                                        <option value="1" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 1 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[1]?></option>
                                        <option value="2" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 2 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[2]?></option>
                                        <option value="3" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 3 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[3]?></option>
                                        <option value="4" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 4 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[4]?></option>
                                        <option value="5" <?php echo isset($school[0]['schoollevel']) && $school[0]['schoollevel'] == 5 ? "selected":"";?>><?php echo $this->lang->line('schoollevel_option')[5]?></option>
                                    </select>
                                    <p>Education Attainment</p>
                                    <input type="hidden" name="schoolcount1" value="1"/>
                                </div>
                            </div>
                            <div id="container_school">
                                <?php if(count($school)>1):?>
                                <?php for($schcount = 1; $schcount < count($school); $schcount++ ):?>
                                <div class="add_another_school dynamic_dd" style="display:block;">
                                    <div>
                                        <input type="text" name="schoolname<?php echo $school[$schcount]['schoolcount']?>" value="<?php echo html_escape($school[$schcount]['schoolname'])?>">
                                        <p>School Name</p>
                                    </div>
                                    <div>
                                        <input type="text" class="year" name="schoolyear<?php echo $school[$schcount]['schoolcount']?>" value="<?php echo html_escape($school[$schcount]['schoolyear'])?>">
                                        <p>Year</p>
                                    </div>
                                    <div>
                                        <select name="schoollevel<?php echo $school[$schcount]['schoolcount']?>" data-status="<?php echo $school[$schcount]['schoollevel']?>" >
                                            <option value="0" <?php echo $school[$schcount]['schoollevel'] == 0 ? "selected":""; ?> ><?php echo $this->lang->line('schoollevel_option')[0]?></option>
                                            <option value="1" <?php echo $school[$schcount]['schoollevel'] == 1 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[1]?></option>
                                            <option value="2" <?php echo $school[$schcount]['schoollevel'] == 2 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[2]?></option>
                                            <option value="3" <?php echo $school[$schcount]['schoollevel'] == 3 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[3]?></option>
                                            <option value="4" <?php echo $school[$schcount]['schoollevel'] == 4 ? "selected":"";?> ><?php echo $this->lang->line('schoollevel_option')[4]?></option>
                                            <option value="5" <?php echo $school[$schcount]['schoollevel'] == 5 ? "selected":"";?>><?php echo $this->lang->line('schoollevel_option')[5]?></option>
                                        </select>
                                        <p>Education Attainment</p>
                                    </div>
                                    <input type="hidden" name="schoolcount<?php echo $school[$schcount]['schoolcount']?>" value="<?php echo $school[$schcount]['schoolcount']?>"/>
                                </div>
                            <?php endfor;?>
                        <?php endif;?>
                    </div>
                    <div class="clear"></div>
                    <p href="#" id="addRow_school" class="add_new_dynamicdd"> + Add another school</p>		
                    <div>
                        <span class="red ci_form_validation_error"><?php echo form_error('schoolname'); ?></span>
                        <span class="red ci_form_validation_error"><?php echo form_error('schoolyear'); ?></span>
                        <span class="red ci_form_validation_error"><?php echo form_error('schoollevel'); ?></span>
                    </div>
                    <input type="hidden" class="progress_update_hidden" value="">
                </div>
                <div class="clear"></div>	
                <div class="btn_con">						
                    <span class="cancel" name="cancel_school">Cancel</span>
                    <input type="submit" class="save_school" name="personal_profile_school" value="Save"/>
                
                </div>	
            </div>
            <div class="clear"></div>
            <?php echo form_close();?>
        </div>
        
        <div class="clear"></div>
        
        <!--<form method="post" id="personal_profile_work" name="personal_profile_work" class="dropdownform">-->
        <?php 
        $attr = array('id'=>'personal_profile_work', 'name'=>'personal_profile_work', 'class'=>'dropdownform');
        echo form_open('',$attr);
        ?>
        <div>
            <div class="personal_info_title">
                <span class="personal_info_icon work_img span_bg"></span> Work
            </div>
            <div class="edit_profile">
                <h2>+ Add Work</h2>
            </div>
            <div class="work_information gen_information">	
                <div class="work_info echoed_info">
                    <?php 	if(count($work)>0){
                        foreach ($work as $i){
                            echo "<p>" . html_escape($i['companyname']) . " " . html_escape($i['designation']) . " " . html_escape($i['year']) . "</p>";
                        }
                    }
                    ?>
                </div>
                <div class="edit_work edit_info_btn">
                    <span><span class="span_bg edit_btn"></span> Edit</span>
                </div>
                <div class="delete_information" name="del_work">
                    <span><span class="span_bg delete_btn"></span> Delete</span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="edit_fields profile_fields">
                <div class="inner_profile_fields work_fields progress_update update_once">
                    <div id="add_work">
                        <div>
                            <input type="text" name="companyname1" value="<?php echo isset($work[0]['companyname'])?html_escape($work[0]['companyname']):"";?>">
                            <p>Company Name</p>
                        </div>
                        <div>
                            <input type="text" name="designation1" value="<?php echo isset($work[0]['designation'])?html_escape($work[0]['designation']):"";?>">
                            <p>Designation</p>
                        </div>
                        <div>
                            <input type="text" class="year" name="year1" value="<?php echo isset($work[0]['year'])?html_escape($work[0]['year']):"";?>" maxlength="4">
                            <p>Year</p>
                        </div>
                        <input type="hidden" name="workcount1" value="1"/>
                    </div>
                    <div id="container_work">
                        <?php if(count($work) > 1):?>
                        <?php for($workcount = 1; $workcount < count($work); $workcount++):?>
                        <div class="add_another_work dynamic_dd" style="display: block;">
                            <div>
                                <input type="text" name="companyname<?php echo $work[$workcount]['count']?>" value="<?php echo html_escape($work[$workcount]['companyname'])?>">
                                <p>Company Name</p>
                            </div>
                            <div>
                                <input type="text" name="designation<?php echo $work[$workcount]['count']?>" value="<?php echo html_escape($work[$workcount]['designation'])?>">
                                <p>Designation</p>
                            </div>
                            <div>
                                <input type="text" class="year" name="year<?php echo $work[$workcount]['count']?>" value="<?php echo html_escape($work[$workcount]['year'])?>">
                                <p>Year</p>
                            </div>
                            <input type="hidden" name="workcount<?php echo $work[$workcount]['count']?>" value="<?php echo $work[$workcount]['count']?>"/>
                        </div>
                    <?php endfor;?>
                <?php endif;?>
            </div>
            <div class="clear"></div>
            <p href="#" id="addRow_work" class="add_new_dynamicdd"> + Add another work</p>
            <div>
                <label></label>
                <span class="red ci_form_validation_error"><?php echo form_error('companyname1');?></span>
                <span class="red ci_form_validation_error"><?php echo form_error('designation1');?></span>
                <span class="red ci_form_validation_error"><?php echo form_error('year1');?></span>
            </div>
            <input type="hidden" class="progress_update_hidden" value="">
        </div>
        <div class="clear"></div>
        <div class="btn_con">
            <span class="cancel" name="cancel_work">Cancel</span>
            <input type="submit" name="personal_profile_work_btn" value="Save" class="save_work">
        </div>	
    </div>
</div>
<?php echo form_close();?>

</div>
</div>


<div class="profile_main_content" id="payment">
    <h2>Update your payment details</h2>
    <p>
    <!--	Any changes to banking information after the 15th day of the month will not be in effect until the following month's payment. -->
    </p>
    
    <div align="right">
        <input type="button" id="abi_btn" class="blue_btn" name="abi_btn" value="+ Add Bank" />
    </div>	
    <div id="abi" style="display:none">
        <?php
            $attr = array('id'=>'billing_info', 'name'=>'billing_info');
            echo form_open('',$attr);
        ?>	
        <div class="profile_fields">
            <div class="inner_profile_fields progress_update update_once">
                <div>
                    <input type="hidden" name="bi_payment_type" id="bi_payment_type" value="Bank" />
                </div>				
                <div class="control-group">
                        <label for="bi_bank">Bank:</label>
                        <select id="bi_bank" name="bi_bank" style="width:50%" placeholder="Select Bank">
                            <option value="">Select a bank...</option>						
                        </select>
                        <div id="bi_err_add" style="float: right; display:none; margin-top: 10px;" >
                            <span style='color:red; font-weight:bold;'>* Duplicate Account Number</span>
                        </div>					
                </div>				
                <div>
                    <label for="bi_acct_name">Account Name:</label>
                    <input type="text" name="bi_acct_name" id="bi_acct_name" maxlength="60" value="<?php #echo html_escape($bill_info[0][$bank_account_name])?>">
                    <span class="red ci_form_validation_error"><?php echo form_error('bi_acct_name');?></span>
                </div>
                <div>
                    <label for="bi_acct_no">Account Number:</label>
                    <input type="text" name="bi_acct_no" id="bi_acct_no" value="<?php #echo html_escape($bank_account_number)?>" maxlength="18">
                    <span class="red ci_form_validation_error"><?php echo form_error('bi_acct_no');?></span>
                </div>				
            </div>
        </div>
        <div class="clear"></div>
        <div class="bottom_save" style="text-align:left">
            <input type="button" name="billing_info_btn" id="billing_info_btn" class="orange_btn3" value="Save">
            <img src="/assets/images/orange_loader_small.gif" id="load_deliver_address" style="position: relative; top:12px; left:15px;  display:none"/>
        </div>
        <?php echo form_close();?>			
    </div>
    <hr style="display: block; height: 1px; border: 0; border-top: 1px solid #ccc; margin: 1em 0; padding: 0;" />
    <div class="billing_info_grid">            
        <?php foreach($bill as $rows => $billing_info){ ?>
            <?php 
                $bi_checked = "";
                $bi_checked_val = "";
                    
                if($billing_info['is_default'] == "1"){ 
                    $bi_checked = " checked='checked' ";
                    $bi_checked_val = "checked";
                } 
            ?>
            <?php
            if($rows >= 0){
                $attr = array('id'=>'ubi_bictr'.$rows, 'name'=>'ubi_bictr'.$rows);
                echo form_open('',$attr);
            ?>
                <div style="width:inherit;">
                    <div id="bi-right" style="float:right; width:200px; text-align:right;">
                            <div class="post_item_button payment_btns">
                                <input type="button" name="bictr<?php echo $rows; ?>" id="bictr<?php echo $rows; ?>" class="edit_lnk span_bg" value="Edit">
                                <input type="button" name="sv_bictr<?php echo $rows; ?>" id="sv_bictr<?php echo $rows; ?>" class="save_lnk span_bg" value="Save" style="display:none">				 	
                                <input type="button" name="cn_bictr<?php echo $rows; ?>" id="cn_bictr<?php echo $rows; ?>" class="cancel_lnk span_bg" value="Cancel" style="display:none">
                                <input type="button" name="del_bictr<?php echo $rows; ?>" id="del_bictr<?php echo $rows; ?>" class="delete_lnk span_bg"  value="Delete">
                                <input type="hidden" name="bi_id_bictr<?php echo $rows; ?>" id="bi_id_bictr<?php echo $rows; ?>" value ="<?php echo $billing_info['id_billing_info'];?>" />
                            </div>
                            <?php if($billing_info['is_default'] == 0):?>
                            <div style='height:40px;' id="bi_ctxt_bictr<?php echo $rows; ?>">
                                <span class='set_default_lnk' id="bi_txt_bictr<?php echo $rows; ?>">Set as default account</span>
                            </div>
                            <?php endif;?>
                            <div id="bi_check_bictr<?php echo $rows; ?>"  style="display:none; width: 38px;" class="txt_alert_save " >
                                <span class="span_bg chk_img"></span><span>Saved</span>
                            </div>
                            <span id="bi_err_bictr<?php echo $rows; ?>" style="display:none; width:auto; margin-top:0px;" >
                                <br/><span style='color:red; font-weight:bold;'>* Duplicate Account Number</span>
                            </span>								
                    </div>
                    <div id="bi-left" style="float:left; width:inherit;">
                            <div class="profile_fields" id="bi_div_bictr<?php echo $rows; ?>">
                                <div class="inner_profile_fields progress_update update_once">		
                                    <div class='acct_container'>						
                                        <label for="bi_ban_bictr<?php echo $rows; ?>">Account Name: </label>
                                        <input type="text" class="bi_input" name="bi_ban_bictr<?php echo $rows; ?>" id="bi_ban_bictr<?php echo $rows; ?>" value ="<?php echo html_escape($billing_info['bank_account_name']);?>" disabled="disabled" maxlength="60" style="width:250px;"/>
                                        <input type="hidden" name="hbi_ban_bictr<?php echo $rows; ?>" id="hbi_ban_bictr<?php echo $rows; ?>" value ="<?php echo html_escape($billing_info['bank_account_name']);?>"/>
                                        <span class="red ci_form_validation_error"><?php #echo form_error('bi_acct_name');?></span>
                                    </div>
                                    <div class='acct_container'>
                                        <label for="bi_bar_bictr<?php echo $rows; ?>">Account Number: </label>
                                        <input type="text" class="bi_input" name="bi_bar_bictr<?php echo $rows; ?>" id="bi_bar_bictr<?php echo $rows; ?>" value ="<?php echo html_escape($billing_info['bank_account_number']);?>" disabled="disabled" maxlength="18" style="width:250px;"/>
                                        <input type="hidden" name="hbi_bar_bictr<?php echo $rows; ?>" id="hbi_bar_bictr<?php echo $rows; ?>" value ="<?php echo html_escape($billing_info['bank_account_number']);?>"/>
                                        <span class="red ci_form_validation_error"><?php #echo form_error('bi_acct_name');?></span>
                                    </div>
                                    <div class='acct_container'>
                                        <label for="bi_bn_bictr<?php echo $rows; ?>">Bank: </label>
                                        <select name="bi_bns_bictr<?php echo $rows; ?>" id="bi_bns_bictr<?php echo $rows; ?>" style="display:none; width:400px;"></select>
                                        <input type="text" class="bi_input" name="bi_bn_bictr<?php echo $rows; ?>" id="bi_bn_bictr<?php echo $rows; ?>" value ="<?php echo html_escape($billing_info['bank_name']);?>" disabled="disabled" style="width:400px;"/>
                                        <input type="hidden" name="hbi_bn_bictr<?php echo $rows; ?>" id="hbi_bn_bictr<?php echo $rows; ?>" value ="<?php echo html_escape($billing_info['bank_name']);?>"/>
                                        <span class="red ci_form_validation_error"><?php #echo form_error('bi_acct_name');?></span>
                                    </div>	
                                    
                                    <?php if(count($billing_info['products'])): ?>
                                        <div style='display:none; height: 600px; overflow-y:scroll;' id='acct_prod_bictr<?php echo $rows; ?>'>
                                            This account is currently in use for <strong><?php echo count($billing_info['products']) ?></strong> products. Are you sure about this action?
                                            <br/><br/>
                                            <span style='font-size:10px;'>
                                            * All purchases made for the items listed below will still be linked to the original account. We will call you to confirm if you have made any changes within the
                                            current pay-out period before making a deposit. Should you wish to change the deposit account for any of your items, you can do it by editing your item listing.
                                            </span>
                                            
                                            <br/><br/>
                                            <?php foreach($billing_info['products'] as $x): ?>
                                                <div style='width:auto;'><a href='/item/<?=$x['p_slug']?>'><span style='font-weight:bold'><?php echo html_escape($x['p_name']);?> - <?php echo date('m/d/Y', strtotime($x['p_date'])); ?></span> | <?php echo es_string_limit(html_escape($x['p_briefdesc']), 60);?></a></div>
                                            <?php endforeach; ?>
                                        </div>
                                   <?php endif; ?>

                                </div>
                            </div>	
                    </div>
                    <div style="clear:both"></div>
                </div>
                
            <?php 
            echo form_close(); 
            }
            ?>	
        <?php }?>	
    </div>		
</div>


<div class="profile_main_content" id="delivery_address">
    <!--<form method="post" id="c_deliver_address" name="c_deliver_address">	-->

    <?php
    $attr = array('id'=>'c_deliver_address', 'name'=>'c_deliver_address');
    echo form_open('',$attr);
    ?>
    <h2>Delivery Address</h2>
    <div class="profile_fields">
        <div class="inner_profile_fields progress_update update_consignee">
            <div>
                <label for="consignee_name">Consignee name:</label>
                <input type="text" name="consignee" id="consignee" value="<?php echo html_escape($consignee)?>">
                <span class="red ci_form_validation_error"><?php echo form_error('consignee');?></span>
            </div>
            <div>
                <label for="mobile_num">Mobile No:</label>
                <input maxlength="11" placeholder="eg. 09051235678" type="text" name="c_mobile" id="c_mobile" value="<?php echo html_escape($c_mobile)?>">
                <span class="red ci_form_validation_error"><?php echo form_error('c_mobile');?></span>
            </div>
            <div>
                <label for="telephone_num">Telephone No:</label>
                <input type="text" name="c_telephone" id="c_telephone" value="<?php echo html_escape($c_telephone)?>">
                <span class="red ci_form_validation_error"><?php echo form_error('c_telephone');?></span>
            </div>
            <div class="address_label">
                <label>Address:</label>
            </div>	
            <div class="delivery_address_content">
                <div class="delivery_address_content_layer1">
                    <div>
                        <select name="c_stateregion" id="delivery_stateregion" class="address_dropdown stateregionselect" data-status="<?php echo $c_stateregionID?>">
                            <option value="0">--- Select State/Region ---</option>
                            <?php foreach($stateregion_lookup as $srkey=>$stateregion):?>
                                <option class="echo" value="<?php echo $srkey?>" <?php echo $c_stateregionID == $srkey ? "selected":"" ?>><?php echo $stateregion?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" name="cstateregion_orig" value="<?php echo $c_stateregionID?>">
                        <p>State/Region</p>
                    </div>
                    <div>
                        <select name="c_city" id="delivery_city" class="address_dropdown cityselect" data-status="<?php echo $c_cityID?>">
                            <option value="0">--- Select City ---</option>
                            <option class="optionclone" value="" style="display:none;" disabled></option>
                            <?php if($c_cityID != '' && $c_stateregionID != ''):?>
                                <?php foreach($city_lookup[$c_stateregionID] as $lockey=>$city):?>
                                    <option class="echo" value="<?php echo $lockey?>" <?php echo $c_cityID == $lockey ? "selected":"" ?> ><?php echo $city?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
                        <input type="hidden" name="ccity_orig" value="<?php echo $c_cityID?>">
                        <p>City</p>
                    </div>
                    <div>
                        <select class="disabled_country" disabled>
                            <option selected=""><?php echo $country_name?></option>
                        </select>
                        <input type="hidden" name="c_country" value="<?php echo $country_id?>">
                        <p>Country</p>
                    </div>
                </div>
                <div class="delivery_address_content_layer2">
                    <div>
                        <input type="text" name="c_address" value="<?php echo html_escape($c_address)?>">
                        <p>Street address</p>
                        <input type="hidden" name="caddress_orig" value="<?php echo html_escape($c_address)?>">
                    </div>
                </div>
            </div>
            <div id="c_defaddress_div" style="<?php echo $show_default_address ? '':'display:none;' ?>" >
                <label></label>
                <input type="checkbox" name="c_def_address" id="c_def_address"> <span>Set as Default Address</span>
                <a class="tooltips" href="javascript:void(0)"><p class="span_bg"></p><span>Setting as default updates address in Personal Information</span></a>
            </div>
            
            
            <div class="view_map_btn">
                <input type="button" class="view_map" value="Mark on Map" name="delivery">&nbsp;|
                <?php if($c_lat == 0 && $c_lng == 0):?>
                    <span class="maploc_stat">Location not marked</span>
                <?php else:?>
                    <span class="maploc_stat">Location marked</span>
                <?php endif;?>
                <input type="hidden" name="map_lat" id="map_clat" value="<?php echo $c_lat;?>">
                <input type="hidden" name="map_lng" id="map_clng" value="<?php echo $c_lng;?>">
                <input type="hidden" name="temp_lat" id="temp_clat" value="<?php echo $c_lat;?>">
                <input type="hidden" name="temp_lng" id="temp_clng" value="<?php echo $c_lng;?>">
            </div>
            
            <div class="map_nav" style="display: none">
                <span class="refresh_map" name="delivery_rmap">Search address</span>
                <span class="current_loc" name="delivery_cmap">Current location</span>
                <a class="close" href="javascript:void(0)">Close</a>
                <div id="GoogleMapContainer" title="Google Map Container"></div>
            </div>
            
            <div id="delivery_mapcanvas" class="map-canvas" ></div>
            
            <div style='margin-left: 113px;'>
                <span class='uptd'>Information Updated</span>
            </div>
            
            <br>
            <div style="padding-left:100px">
                <label></label>
                <br><span class="red ci_form_validation_error"><?php echo form_error('c_stateregion');?></span>
                <br><span class="red ci_form_validation_error"><?php echo form_error('c_city');?></span>
            </div>
            <div id="progressbar" class="profile_progress"></div>
            <input type="hidden" class="progress_update_hidden" value="">
        </div>
    </div>
    <div class="clear"></div>
    <div class="bottom_save" style='text-align:left;'>
        <input type="submit" name="c_deliver_address_btn" value="Save" id="c_deliver_address_btn" style="position: relative; left:40%;">
        <img src="/assets/images/orange_loader_small.gif" id="load_cdeliver_address" style="position: relative; top:12px; left:42%;  display:none"/>
    </div>	
    <?php echo form_close();?>

</div>	

<!-- Used by transaction response when password is not yet set-->
<div id="tx_dialog" style="display:none;" title="Password Authentication">
    
    <p>Are you sure about submitting this request?</p>

    <div id="tx_dialog_pass_cont">
     
        <label for="tx_password">Enter your password:</label>
        <input type="password" id="tx_password" name="tx_password">
        <span class="error red"></span>
        <img src="/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;vertical-align:middle;margin-left:3px;"/>
    </div>
    
    <br/>
    <div style='border-bottom: 1px solid #eee;'></div>

    <p class="forward msg">Please, make sure you've received your order in good quality.</p>
    <p class="return msg">When canceled you won't receive any payment for this product.</p>
    <p class="cod msg">This transaction will be marked as completed.</p>
    
    <div id="tx_dialog_loadingimg" style="text-align:center;">
        <img src="/assets/images/orange_loader_small.gif" class="loading_img" style="display:none;"/>
    </div>
    <br/>
</div>

<div id="map_modalcont" style="display:none;">
    <div id="tsold_mapview" style="height: 400px; width: 650px; "></div>
</div>

<div class="profile_main_content" id="transactions">
    <h2>On-going Transactions</h2>
    
    <div>
        <ul class="idTabs transact_tabs">
            <li><a href="#bought">Bought <span><?php echo $transaction['count']['buy'];?></span></a> </li>
            <li><a href="#sold">Sold 	<span><?php echo $transaction['count']['sell'];?></span></a> </li>
        </ul>
    </div>
    
    <div id="bought" class="transactions-buy dashboard_table" data-key="buy" data-controller="2">
        <h2>Bought Items </h2>
        <?php if($transaction['count']['buy']===0):?>
            <br/>
            <div><span class='nocontent'>You have not bought any items yet.</span></div>
        <?php else: ?>

    <div class="pagination" id="pagination-bought">
        <a href="#" class="first" data-action="first">&laquo;</a>
        <a href="#" class="previous" data-action="previous">&lsaquo;</a>
        <input type="text" readonly="readonly" data-max-page="<?php echo ($transaction['count']['buy']===0)?1:(ceil($transaction['count']['buy']/$items_per_page));?>" data-origmaxpage="<?php echo ($transaction['count']['buy']===0)?1:(ceil($transaction['count']['buy']/$items_per_page));?>" />
        <a href="#" class="next" data-action="next">&rsaquo;</a>
        <a href="#" class="last" data-action="last">&raquo;</a>
    </div>
    
    <div class="post_item_srch_container">

            <input type="text" class="box sch_box tx_sch_box" placeholder="Transaction No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
            <span class="span_bg sch_btn tx_sch_btn"></span>

            <label for="active_sort">Payment Filter</label>
            <select name="active_sort" class="post_active_sort tx_sort_select">
                <option value="0">Show All</option>
                <option value="1">PayPal</option>
                <option value="2">DragonPay</option>
                <option value="3">Cash on Delivery</option>
                <option value="5">Direct Bank Deposit</option>
            </select>
            <span class="span_bg arrow_sort tx_arrow_sort"></span>
        </div>

        
    <div class="page_load" style="display:none;text-align:center; margin-top: 50px;">
        <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
    </div>
    
    <?php $transac_counter = 0;$pageNum = 0;?>
    <div class="paging" data-page="<?php echo $pageNum?>">
        <?php foreach($transaction['buy'] as $tk=>$transact):?>
        <div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
            <div class="transac_title">
                <?php if($transact['payment_method']==1 && $transact['is_flag'] == 1):?>
                    <span><strong>ON HOLD - PAYPAL PAYMENT UNDER REVIEW</strong></span>
                <?php else:?>
                <div class="transac_title_table">
                    <div class="transac_title_col1">
                        <span><strong>Transaction No.:</strong></span>
                        <span><?php echo $transact['invoice_no'];?></span>
                    </div>
                    <div class="transac_title_col2">
                        <span><strong>Date:</strong></span>
                        <span class="transac_title_date"><?php echo $transact['dateadded']?></span>
                    </div>
        

                    <!-- If payment method is dragon pay / direct bank deposit-->

                    <div class="transac_title_col3">
                        <?php if( ($transact['payment_method'] == 2 || $transact['payment_method'] == 4) && $transact['transac_stat'] == 99):?>
                            <?php $attr = array('class'=>'');
                                echo form_open('',$attr);
                            ?>             
                                <input type="button" class="dragonpay_update_btn css_dp_btn" name="dragonpay_update_btn" value="Confirm Dragonpay Payment">
                                <input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
                                <input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
                                <input type="hidden" name="dragonpay" value="1">
                            <?php echo form_close();?>
                        <?php elseif($transact['payment_method'] == 5 && $transact['transac_stat'] == 99):?>
                                
                                <a class='payment_details_btn' href='javascript:void(0)' style='float:right; text-decoration:underline; font-weight:bold;'>+ Add Deposit Details</a>
                                
                                <div class="payment_details_cont" style="display:none;">
                                <?php $attr = array('class'=>'payment_bankdeposit');
                                    $disable = $transact['bd_details']['bd_datemodified'] != '' ? true : false;
                                    echo form_open('',$attr);
                                ?>
                                    <h2>Payment Details</h2>
                                    <label for="bank"><span style="color:red;">*</span> Bank: </label> 
                                    <input type="text" name="bank" value="<?php echo html_escape($transact['bd_details']['bank'])?>" <?php echo $disable ? 'disabled':''?>><br/>
                                    <label for="ref_num"><span style="color:red;">*</span> Reference #: </label>
                                    <input type="text" name="ref_num" value="<?php echo html_escape($transact['bd_details']['ref_num'])?>" <?php echo $disable ? 'disabled':''?>><br/>
                                    <label for="amount"><span style="color:red;">*</span> Amount Deposited: </label>
                                    <input type="text" name="amount" class="bankdeposit_amount price" value="<?php echo  $transact['bd_details']['amount'] != '' ? html_escape(number_format($transact['bd_details']['amount'],2,'.',',')) : '';?>" <?php echo $disable ? 'disabled':''?>><br/>
                                    <label for="date"><span style="color:red;">*</span> Date of Deposit: </label>
                                    <input type="text" name="date" class="modal_date" value="<?php echo html_escape($transact['bd_details']['date_deposit'])?>" <?php echo $disable ? 'disabled':''?> autocomplete="off"><br/>
                                    <label for="comment">Comments: </label>
                                    <textarea name="comment" cols="55" rows="5" data-value="<?php echo html_escape($transact['bd_details']['comment'])?>" <?php echo $disable ? 'disabled':''?>><?php echo html_escape($transact['bd_details']['comment'])?></textarea>
                                    <input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
                                    <input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
                                    <input type="hidden" name="bank_deposit" value="1">
                                    <input type="submit" class="bank_deposit_submit orange_btn3" name="bank_deposit_submit" value="Submit">
                                    <div class="tx_modal_edit css_modal_edit footer" style="display:<?php echo $disable ? '':'none;'?>">Edit</div>
                                    <div class="tx_modal_cancel css_modal_cancel footer" style="display:none;">Cancel</div>
                                    <div class="css_modal_date footer"><?php echo $transact['bd_details']['bd_datemodified']?></div>
                                <?php echo form_close();?>
                                </div>
                        <?php endif;?>
                    </div>
                    <!-- End of dragonpay / direct bank deposit button-->
                </div>
                <?php endif;?>
            </div>
            
            <?php if( $transact['payment_method'] == 5 && $transact['transac_stat'] == 99 ):?>
                <div style="margin-left:5px;border-bottom:1px dotted #CECECE;margin-bottom:10px;padding-bottom:10px;">
                    <p><strong>Deposit payment to:</strong></p>
                    <p>
                        <span style="margin:0px 5px 0px 3em;">Bank:</span>
                        <span style="padding-right:2em;margin-right:3em;font-weight:bold;"><?php echo $transact['bank_template']['bank_name']?></span>
                        <br>
                        <span style="margin-right:5px;margin-left:3em;">Bank Account Name:</span>
                        <span style="padding-right:2em;margin-right:3em;border-right:1px dotted #CECECE;font-weight:bold;"><?php echo $transact['bank_template']['bank_accname']?></span>
                        <span style="margin-right:5px;">Bank Account Number:</span>
                        <span style="padding-right:2em;margin-right:3em;font-weight:bold;"><?php echo $transact['bank_template']['bank_accnum']?></span>
                    </p>
                </div>
            <?php endif;?>
            
                <div class="transac_prod_wrapper">
                    <div class="transac-product-container">
                        <?php foreach($transact['products'] as $opk=>$product):?>
                        <div class="transac_prod_first">
                            <div class="img_transac_prod_con">
                                <span class="img_transac_prod">
                                    <img src="/<?php echo $product['product_image_path'];?>">
                                </span>
                            </div>
                            <div class="info_transac_prod_con">
                                <div class="title_top_transac_table">
                                    <span class="title_top_transac_col1">								
                                        <a href="/item/<?php echo $product['slug'];?>"><?php echo html_escape($product['name']);?></a>
                                    </span>
                                    <span class="title_top_transac_col2">
                                        Quantity: <span><?php echo $product['order_quantity']?></span>
                                    </span>	
                                    <span class="title_top_transac_col3">
                                        Total: <span>Php <?php echo number_format($product['price'],2,'.',',');?></span>
                                    </span>
                                </div>	
                                <div class="transac_bought_con tx_cont">
                                    <span class="transac_bought_con_col1">Bought from: </span>
                                    <span class="transac_bought_con_col2">
                                        <a href="/<?php echo $product['seller_slug'];?>">
                                            <?php echo html_escape($product['seller']);?>
                                        </a>
                                    </span>
                                    <span class="transac_bought_con_col3 tx_cont_col3">
                                        Status:
                                        <?php if($transact['transac_stat'] == 0 && $transact['is_flag'] == 0):?>
                                            <?php if($product['is_reject'] == 1):?>
                                                <span class="trans_alert trans_red">Item Rejected</span>
                                            <?php else:?>
                                                <?php if($product['status'] == 0):?>
                                                    <?php if( $transact['payment_method'] == 3 ):?>
                                                        <span class="trans_alert trans_green">Cash on delivery</span>
                                                    <?php else:?>
                                                        <?php if( $product['has_shipping_summary'] == 0 ):?>
                                                            <span class="trans_alert trans_red">Pending Shipping Info</span>
                                                        <?php elseif( $product['has_shipping_summary'] == 1 ):?>
                                                            <span class="trans_alert trans_orange">Item on route</span>
                                                        <?php endif;?>
                                                    <?php endif;?>
                                                <?php elseif($product['status'] == 1):?>
                                                    <span class="trans_alert trans_green">Item Received</span>
                                                <?php elseif($product['status'] == 2):?>
                                                    <span class="trans_alert trans_red">Seller canceled order</span>
                                                <?php elseif($product['status'] == 3):?>
                                                    <span class="trans_alert trans_green">Cash on delivery</span>
                                                <?php elseif($product['status'] == 4):?>
                                                    <span class="trans_alert trans_green">Paid</span>
                                                <?php elseif($product['status'] == 5):?>
                                                    <span class="trans_alert trans_red">Payment Refunded</span>
                                                <?php endif;?>
                                            <?php endif;?>
                                        <?php else:?>
                                            <?php if($transact['payment_method'] == 2):?>
                                                <span class="trans_alert trans_red">CONFIRM DRAGONPAY PAYMENT</span>
                                            <?php elseif($transact['payment_method'] == 5):?>
                                                <?php if( $transact['bd_details']['bd_datemodified'] != '' ):?>
                                                    <?php if($transact['bd_details']['is_invalid'] == 1):?>
                                                        <span class="trans_alert trans_red">WRONG DEPOSIT DETAILS</span>
                                                    <?php else:?>
                                                        <span class="trans_alert trans_orange">PROCESSING DEPOSIT DETAILS</span>
                                                    <?php endif;?>
                                                <?php else:?>
                                                    <span class="trans_alert trans_red">DEPOSIT DETAILS REQUIRED</span>
                                                <?php endif;?>
                                            <?php elseif($transact['payment_method'] == 1 && $transact['is_flag'] == 1):?>
                                                <span class="trans_alert trans_red">On hold</span>
                                            <?php endif;?>
                                        <?php endif;?>
                                        
                                        <?php if( $product['has_shipping_summary'] == 1 ):?>
                                            <div><span class="shipping_comment">+ View shipment detail</span></div>
                                            <div class="shipping_comment_cont" style="display:none;">
                                                <h2>Shipping Details</h2>
                                                <div>   
                                                    <br/>
                                                    <label for="courier">Shipped By: </label>
                                                    <input type="text" name="courier" value="<?php echo html_escape($product['courier']);?>" disabled ><br/>
                                                    <label for="tracking_num">Tracking Number: </label>
                                                    <input type="text" name="tracking_num" value="<?php echo html_escape($product['tracking_num']);?>" disabled ><br/>
                                                    <label for="delivery_date">Delivery Date: </label>
                                                    <input type="text" name="delivery_date" value="<?php echo html_escape($product['delivery_date'])?>" disabled> <br/>
                                                    <label for="expected_date">Expected Date of Arrival: </label>
                                                    <input type="text" name="expected_date" value="<?php echo html_escape($product['expected_date'])?>" disabled><br/>
                                                    <br/>
                                                    <label for="comment">Comments: </label>
                                                    <textarea name="comment" cols="55" rows="5" disabled ><?php echo html_escape($product['shipping_comment']); ?></textarea>								
                                                    <span style="display: block;margin-left: 33em;padding: 10px 0; font-weight:bold;"><?php echo $product['datemodified'];?></span>
                                                </div>
                                            </div>
                                        <?php endif;?>
                                    </span>
                                </div>
                                
                                <?php if( $product['has_shipping_summary'] == 1 && $transact['transac_stat'] == 0 && $product['status'] == 0 && $transact['payment_method'] != 3 && $transact['is_flag'] == 0):?>
                                    <div class="transac_prod_btns tx_btns">
                                        <?php
                                            $attr = array('class'=>'transac_response');
                                            echo form_open('',$attr);
                                        ?>							
                                            <input type="button" value="Item received" class="transac_response_btn tx_forward transac_orange_btn">
                                            <input type="hidden" name="buyer_response" value="<?php echo $opk;?>">
                                            <input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
                                            <input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
                                        <?php echo form_close();?>
                                        
                                        <?php echo form_open('');?>
                                            <?php if($product['is_reject'] == 0):?>
                                                <input type="button" value="Reject Item" class="reject_btn reject_item	reject">
                                                <input type="hidden" name="method" value="reject">
                                            <?php else:?>
                                                <input type="button" value="Unreject Item" class=" reject_btn reject_item unreject">
                                                <input type="hidden" name="method" value="unreject">
                                            <?php endif;?>
                                            
                                            <input type="hidden" name="order_product" value="<?php echo $opk;?>">
                                            <input type="hidden" name="transact_num" value="<?php echo $tk;?>">
                                            <input type="hidden" name="seller_id" value="<?php echo $product['seller_id']?>">
                                        <?php echo form_close();?>										
                                    </div>
                                <?php endif; ?>
                                
                                <?php if( count($product['attr']) !== 0 ):?>
                                    <div class="show_more_options blue">										
                                        <span class="span_bg"></span>
                                        <p>View Features and Specifications</p>
                                    </div>
                                    <div class="attr_hide">
                                        <?php foreach($product['attr'] as $temp):?>
                                            <p class="feat_and_specs_items"><strong><?php echo html_escape($temp['field']);?>:</strong> <?php echo html_escape($temp['value']);?></p>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php endforeach;?>
                    </div>
                 <div class="feedback_wrapper">
                    <?php foreach($transact['users'] as $uk=>$user):?>
                    <div class="feedback_container">
                        <?php if( $user['has_feedb'] == 0 ): ?>          

                            <p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
                            <div class="transac-feedback-container">
                                <h2>Feedback</h2>
                                <?php
                                $attr = array('class'=>'transac-feedback-form');
                                echo form_open('',$attr);
                                ?>
                                <input type="hidden" name="feedb_kind" value="0">
                                <input type="hidden" name="order_id" value="<?php echo $tk;?>">
                                <input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
                                <textarea rows="4" cols="50" name="feedback-field"></textarea>
                                <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                <br>
                                <span class="star-label"><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div>
                                <span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
                                <br>
                                <span class="star-label"><?php echo $this->lang->line('rating')[1].':'; ?> </span><div class="feedb-star rating2"></div>
                                <span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
                                <br>
                                <span class="star-label"><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div>
                                <span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
                                <br>
                                <span class="raty-error"></span>
                                <br>
                                <span class="feedback-submit">Submit</span><span class="feedback-cancel">Cancel</span>
                                <?php echo form_close();?>
                            </div>
                        <?php endif;?>
                    </div>
                    <?php endforeach;?>
                </div> 
</div>
</div>
<!--<div class="clear"></div>-->
<?php $transac_counter++;?>
<?php if($transac_counter === $items_per_page): $transac_counter = 0;$pageNum++;?>
</div><div class="paging">
<?php endif;?>
<?php endforeach;?>
</div>

<?php endif; ?>
</div>

    <div id="sold" class="transactions-sell dashboard_table" data-key="sell" data-controller="2">
        <h2>Sold Items</h2>
        <?php if($transaction['count']['sell']===0):?>
        <br/>
        <div><span class='nocontent'>You have not sold any items yet.</span></div>
    <?php else: ?>
    
        <div class="pagination" id="pagination-sold">
            <a href="#" class="first" data-action="first">&laquo;</a>
            <a href="#" class="previous" data-action="previous">&lsaquo;</a>
            <input type="text" readonly="readonly" data-max-page="<?php echo ($transaction['count']['sell']===0)?1:(ceil($transaction['count']['sell']/$items_per_page));?>" data-origmaxpage="<?php echo ($transaction['count']['sell']===0)?1:(ceil($transaction['count']['sell']/$items_per_page));?>"/>
            <a href="#" class="next" data-action="next">&rsaquo;</a>
            <a href="#" class="last" data-action="last">&raquo;</a>
        </div>
        
        <div class="post_item_srch_container">
            <input type="text" class="box sch_box tx_sch_box" placeholder="Transaction No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
            <span class="span_bg sch_btn tx_sch_btn"></span>
            <label for="active_sort">Payment Filter</label>
            <select name="active_sort" class="post_active_sort tx_sort_select">
                <option value="0">Show All</option>
                <option value="1">PayPal</option>
                <option value="2">DragonPay</option>
                <option value="3">Cash on Delivery</option>
                <option value="5">Direct Bank Deposit</option>
            </select>
            <span class="span_bg arrow_sort tx_arrow_sort"></span>
        </div>
        
        <div class="page_load" style="display:none;text-align:center; margin-top: 50px;">
            <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
        </div>
        
        <?php $transac_counter = 0; $pageNum = 0;?>
        <div class="paging" data-page="<?php echo $pageNum?>">
            <?php foreach($transaction['sell'] as $tk=>$transact):?>
            <div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
                <div class="transac_title">
                    <?php if($transact['transac_stat'] != 99 && $transact['is_flag'] == 0): ?>
                    <div class="transac_title_table">
                        <div class="transac_title_col1">
                            <span><strong>Transaction No.: </strong></span>		
                            <span><?php echo $transact['invoice_no'];?></span>  
                        </div>
                        <div class="transac_title_col2">
                            <span><strong>Date:</strong></span>
                            <span class="transac_title_date"><?php echo $transact['dateadded']?></span>
                        </div>
                        <div class="transac_title_col3">
                            <strong>Sold to: </strong> <a href="/<?php echo $transact['buyer_slug']?>"><?php echo html_escape($transact['buyer']);?></a> <br />
                            <span class="transac_address_details_show" style="color:#0191C8;cursor:pointer;font-size:10px;text-decoration:underline;">View Delivery details</span>
                            <div style="display:none;" class="transac_address_cont">
                                <?php foreach($transact['users'] as $uk=>$user):?>
                                    <span class="tad_1 consignee">
                                        <strong>Consignee: </strong>
                                        <span><?php echo html_escape($user['address']['consignee']);?></span>
                                    </span>
                                    <?php if(trim($user['address']['mobile']) != ''):?>
                                        <span class="tad_1 mobile">
                                            <strong>Mobile: </strong> 
                                            <span><?php echo html_escape($user['address']['mobile']);?></span>
                                        </span>
                                    <?php endif;?>
                                    <?php if(trim($user['address']['telephone']) != ''):?>
                                        <span class="tad_1 telephone">
                                            <strong>Telephone: </strong>
                                            <span><?php echo html_escape($user['address']['telephone']);?></span> 
                                        </span>
                                    <?php endif;?>
                                    <span style="display:block;"></span>
                                    <span class="tad_2 stateregion">
                                        <strong>State/Region:</strong>
                                        <span><?php echo html_escape($user['address']['stateregion'])?></span> 
                                    </span>
                                    <span class="tad_2 city">
                                        <strong>City:</strong>
                                        <span><?php echo html_escape($user['address']['city']);?></span> 
                                    </span>
                                    <span class="tad_3 fulladdress">
                                        <strong>Address:</strong>
                                        <span><?php echo html_escape($user['address']['fulladd']);?></span> 
                                    </span>
                                    <?php if( $user['address']['lat']!=0 && $user['address']['lng']!=0 ):?>
                                        <span class="tsold_viewmap" data-lat="<?php echo $user['address']['lat'];?>" data-lng="<?php echo $user['address']['lng'];?>">View Map</span>
                                        <div class="map_modalcont" style="display:none;"></div>
                                    <?php endif;?>
                                <?php endforeach;?>
                                <span class="transac_address_details_hide">Close</span>
                            </div>
                            </span>
                        </div>
                    </div>
                    <?php else:?>
                        <?php if($transact['payment_method'] == 2):?>
                            <span><strong>ON HOLD - PENDING DRAGONPAY PAYMENT FROM <?php echo $transact['buyer']?></strong></span>
                        <?php elseif($transact['payment_method'] == 5):?>
                            <span><strong>ON HOLD - PENDING BANK DEPOSIT DETAILS FROM <?php echo $transact['buyer']?></strong></span>
                        <?php elseif($transact['payment_method'] == 1 && $transact['is_flag'] == 1):?>
                            <span><strong>ON HOLD - PAYPAL PAYMENT UNDER REVIEW FROM <?php echo $transact['buyer']?></strong></span>
                        <?php endif;?>
                    <?php endif;?>
                </div>
                <div class="transac_prod_wrapper">
                    
                    <?php foreach($transact['products'] as $opk=>$product):?>
                    <div class="sold_prod_container transac-product-container">
                        <div class="transac_prod_first">
                            <div class="img_transac_prod_con">
                                <span class="img_transac_prod">
                                    <img src="/<?php echo $product['product_image_path'];?>">
                                </span>
                            </div>
                            <div class="info_transac_prod_con">
                                <div class="title_top_transac_table">
                                    <span class="title_top_transac_col1">
                                        <a href="/item/<?php echo $product['slug'];?>"><?php echo html_escape($product['name']);?></a>
                                    </span>
                                    <span class="title_top_transac_col2">
                                        Quantity:<span><?php echo $product['order_quantity']?></span>
                                    </span>
                                    <span class="title_top_transac_col3">
                                        Total:<span>Php<?php echo number_format($product['price'],2,'.',',');?></span>
                                    </span>
                                </div>
                                <div class="transac_bought_con tx_cont">
                                    
                                    <span class="transac_bought_con_col3 tx_cont_col3">
                                        Status:
                                    <?php if($transact['transac_stat'] == 0 && $transact['is_flag'] == 0):?>
                                        <?php if($product['is_reject'] == 1):?>
                                            <span class="trans_alert trans_red">Item Rejected</span>
                                        <?php else:?>
                                            <?php if($product['status'] == 0):?>
                                                <?php if( $transact['payment_method'] == 3 ):?>
                                                    <span class="trans_alert trans_green">Cash on delivery</span>
                                                <?php else:?>
                                                    <?php if( $product['has_shipping_summary'] == 0 ):?>
                                                        <span class="trans_alert trans_red">Easyshop received payment</span>
                                                    <?php elseif( $product['has_shipping_summary'] == 1 ):?>
                                                        <span class="trans_alert trans_orange">Item shipped</span>
                                                    <?php endif;?>
                                                <?php endif;?>
                                            <?php elseif($product['status'] == 1):?>
                                                <span class="trans_alert trans_green">Buyer received item</span>
                                            <?php elseif($product['status'] == 2):?>
                                                <span class="trans_alert trans_red">Order Canceled</span>
                                            <?php elseif($product['status'] == 3):?>
                                                <span class="trans_alert trans_green">Cash on delivery</span>
                                            <?php elseif($product['status'] == 4):?>
                                                <span class="trans_alert trans_green">Payment Received</span>
                                            <?php elseif($product['status'] == 5):?>
                                                <span class="trans_alert trans_red">Payment Returned</span>
                                            <?php endif;?>
                                        <?php endif;?>
                                    <?php else:?>
                                        <span class="trans_alert trans_red">On Hold</span>
                                    <?php endif;?>
                                    </span>
                
                                </div>
                                
                                <div class="transac_prod_btns tx_btns">
                                    <?php if($transact['transac_stat'] == 0 && $product['status'] == 0 && $transact['payment_method'] != 3  && $transact['is_flag'] == 0):?>										
                                        
                                        <div style="display:inline-block;"><input type="button" class="shipping_comment isform transac_orange_btn" value="Ship Item"></div>
                                        <div class="shipping_comment_cont" style="display:none;">
                                            <h2>Shipping Details</h2>
                                            <div>
                                                <?php
                                                    $disable = trim($product['shipping_comment']) == '' ? false : true;
                                                    $attr = array('class'=>'shipping_details');
                                                    echo form_open('',$attr);
                                                ?>
                                                    <br/>
                                                    <label for="courier"><span style="color:red;">*</span> Shipped By: </label>
                                                    <input type="text" name="courier" value="<?php echo html_escape($product['courier']);?>" placeholder="(e.g. LBC, Air21)"<?php echo $disable ? 'disabled':''; ?> ><br/>
                                                    <label for="tracking_num">Tracking Number: </label>
                                                    <input type="text" name="tracking_num" value="<?php echo html_escape($product['tracking_num']);?>" <?php echo $disable ? 'disabled':''; ?> ><br/>
                                                    <label for="delivery_date"><span style="color:red;">*</span> Delivery Date: </label>
                                                    <input autocomplete="off" type="text" class="modal_date" name="delivery_date" value="<?php echo html_escape($product['delivery_date'])?>" <?php echo $disable ? 'disabled':''; ?> > <br/>
                                                    <label for="expected_date">Expected Date of Arrival: </label>
                                                    <input autocomplete="off" type="text" class="modal_date" name="expected_date" value="<?php echo html_escape($product['expected_date'])?>" <?php echo $disable ? 'disabled':''; ?> ><br/>
                                                    <br/>
                                                    <label for="comment">Comments: </label>
                                                    <textarea name="comment" cols="55" rows="5" data-value="<?php echo html_escape($product['shipping_comment']); ?>" <?php echo $disable ? 'disabled':''; ?>><?php echo html_escape($product['shipping_comment']); ?></textarea>
                                                    <input name="order_product" type="hidden" value="<?php echo $opk;?>">
                                                    <input name="transact_num" type="hidden" value="<?php echo $tk;?>">
                                                    <input class="shipping_comment_submit orange_btn3" type="submit" value="Save">
                                                    <div class="tx_modal_edit css_modal_edit footer" style="display: <?php echo $disable ? '':'none'?>;">Edit</div>
                                                    <div class="tx_modal_cancel css_modal_cancel footer" style="display:none;">Cancel</div>
                                                    <div class="css_modal_date footer"><?php echo $product['datemodified'];?></div>
                                                <?php echo form_close();?>
                                            </div>
                                        </div>
                                        
                                        <?php
                                            $attr = array('class'=>'transac_response');
                                            echo form_open('',$attr);
                                        ?>
                                        <input type="button" value="Cancel Order" class="transac_response_btn tx_return">
                                        <input type="hidden" name="seller_response" value="<?php echo $opk;?>">
                                        <input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
                                        <input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
                                        <?php echo form_close();?>
                                        
                                    <?php elseif( $transact['transac_stat'] == 0 && $product['status'] == 0 && $transact['payment_method'] == 3 ):?>
                                        <?php
                                            $attr = array('class'=>'transac_response');
                                            echo form_open('',$attr);
                                        ?>
                                        <input type="button" value="Completed" class="transac_response_btn tx_cod">
                                        <input type="hidden" name="cash_on_delivery" value="<?php echo $opk;?>">
                                        <input type="hidden" name="transaction_num" value="<?php echo $tk;?>">
                                        <input type="hidden" name="invoice_num" value="<?php echo $transact['invoice_no'];?>">
                                        <?php echo form_close();?>
                                    <?php endif;?>
                                </div>
                                
                                <?php if( count($product['attr']) !== 0 ):?>
                                    <div class="show_more_options blue">										
                                        <span class="span_bg"></span>
                                        <p>View Features and Specifications</p>
                                    </div>
                                    <div class="attr_hide">
                                        <?php foreach($product['attr'] as $temp):?>
                                            <p class="feat_and_specs_items"><strong><?php echo html_escape($temp['field']);?>:</strong> <?php echo html_escape($temp['value']);?></p>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                                
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>

            <div class="feedback_wrapper">
                <?php foreach($transact['users'] as $uk=>$user):?>
                <?php if($transact['transac_stat'] != 99):?>
                <div class="feedback_container">
                    <?php if( $user['has_feedb'] == 0 ): ?>
                        <p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
                        <div class="transac-feedback-container">
                            <h2>Feedback</h2>
                            <?php
                            $attr = array('class'=>'transac-feedback-form');
                            echo form_open('',$attr);
                            ?>
                            <input type="hidden" name="feedb_kind" value="1">
                            <input type="hidden" name="order_id" value="<?php echo $tk;?>">
                            <input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
                            <textarea rows="4" cols="50" name="feedback-field"></textarea>
                            <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                            <br>
                            <span class="star-label"><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div>
                            <span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
                            <br>
                            <span class="star-label"><?php echo $this->lang->line('rating')[1].':'; ?> </span><div class="feedb-star rating2"></div>
                            <span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
                            <br>
                            <span class="star-label"><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div>
                            <span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
                            <br>
                            <span class="raty-error"></span>
                            <br>
                            <span class="feedback-submit">Submit</span> <span class="feedback-cancel">Cancel</span>
                            <?php echo form_close();?>
                        </div>
                    <?php endif;?>
                </div>
            <?php endif;?>
            <?php endforeach;?>
            </div>
    </div>
    <!--<div class="clear"></div>-->
    <?php $transac_counter++;?>
    <?php if($transac_counter === $items_per_page): 
        $transac_counter = 0;
        $pageNum++;
    ?>
        </div><div class="paging" data-page="<?php echo $pageNum?>">
    <?php endif;?>
    <?php endforeach;?>
    </div>
    
    <?php endif; ?>					
    </div>
</div>


<div id="complete_transactions" class="profile_main_content">
    <h2>Completed Transactions</h2>
    <ul class="idTabs transact_tabs">
        <li><a href="#complete_buy">Bought<span><?php echo $transaction['count']['cbuy'];?></span></a></li>
        <li><a href="#complete_sell">Sold<span><?php echo $transaction['count']['csell'];?></span></a></li>
    </ul>
    <div id="complete_buy" class="dashboard_table" data-key="cbuy" data-controller="2">
        <h2>Bought Items</h2>
        <?php if($transaction['count']['cbuy']===0):?>
            <br/>
            <div><span class='nocontent'>There are no transactions for this category.</span></div>
        <?php else: ?>
        
        <div class="pagination" id="pagination-complete-bought">
            <a href="#" class="first" data-action="first">&laquo;</a>
            <a href="#" class="previous" data-action="previous">&lsaquo;</a>
            <input type="text" readonly="readonly" data-max-page="<?php echo ($transaction['count']['cbuy']===0)?1:(ceil($transaction['count']['cbuy']/$items_per_page));?>" data-origmaxpage="<?php echo ($transaction['count']['cbuy']===0)?1:(ceil($transaction['count']['cbuy']/$items_per_page));?>"/>
            <a href="#" class="next" data-action="next">&rsaquo;</a>
            <a href="#" class="last" data-action="last">&raquo;</a>
        </div>
        
        <div class="post_item_srch_container">
            <input type="text" class="box sch_box tx_sch_box" placeholder="Transaction No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
            <span class="span_bg sch_btn tx_sch_btn"></span>
            <label for="active_sort">Payment Filter</label>
            <select name="active_sort" class="post_active_sort tx_sort_select">
                <option value="0">Show All</option>
                <option value="1">PayPal</option>
                <option value="2">DragonPay</option>
                <option value="3">Cash on Delivery</option>
                <option value="5">Direct Bank Deposit</option>
            </select>
            <span class="span_bg arrow_sort tx_arrow_sort"></span>
        </div>
        
        <div class="page_load" style="display:none;text-align:center; margin-top: 50px;">
            <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
        </div>
        
        <?php $transac_counter = 0; $pageNum = 0;?>
        <div class="paging" data-page="<?php echo $pageNum?>">
            <?php foreach($transaction['complete']['buy'] as $tk=>$transact):?>
            <div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
                <div class="transac_title">
                    <div class="transac_title_table">
                        <div class="transac_title_col1">
                            <span><strong>Transaction No.:</strong></span>
                            <span><?php echo $transact['invoice_no'];?></span>
                        </div>
                        <div class="transac_title_col2">
                            <span><strong>Date:</strong></span>
                            <span class="transac_title_date"><?php echo $transact['dateadded']?></span>
                        </div>
                        <div class="transac_title_col3">

                        </div>
                    </div>
                </div>
                <div class="transac_prod_wrapper">
                    <div class="transac-product-container">
                        <?php foreach($transact['products'] as $opk=>$product):?>
                        <div class="transac_prod_first">
                            <div class="img_transac_prod_con">
                                <span class="img_transac_prod">
                                    <img src="/<?php echo $product['product_image_path'];?>">
                                </span>
                            </div>
                            <div class="info_transac_prod_con">
                                <div class="title_top_transac_table">
                                    <span class="title_top_transac_col1">
                                        <a href="/item/<?php echo $product['slug'];?>"><?php echo html_escape($product['name']);?></a>
                                    </span>
                                    <span class="title_top_transac_col2">
                                        Quantity:<span><?php echo $product['order_quantity']?></span>
                                    </span>
                                    <span class="title_top_transac_col3">
                                        Total:<span>Php<?php echo number_format($product['price'],2,'.',',');?></span>
                                    </span>
                                </div>
                                <div class="transac_bought_con">
                                    <span class="transac_bought_con_col1">Bought from: </span>
                                    <span class="transac_bought_con_col2"><a href="/<?php echo $product['seller_slug'];?>"><?php echo html_escape($product['seller']);?></a></span>
                                    <span class="transac_bought_con_col3">
                                        Status:
                                        <?php if($product['status'] == 1):?>
                                            <span class="trans_alert trans_green">Item Received</span>
                                        <?php elseif($product['status'] == 2):?>
                                            <span class="trans_alert trans_red">Order Canceled</span>
                                        <?php elseif($product['status'] == 3):?>
                                            <span class="trans_alert trans_green">Cash on delivery</span>
                                        <?php elseif($product['status'] == 4):?>
                                            <span class="trans_alert trans_green">Paid</span>
                                        <?php elseif($product['status'] == 5):?>
                                            <span class="trans_alert trans_red">Payment Returned</span>
                                        <?php endif;?>
                                    </span>
                                </div>
                                <?php if( count($product['attr']) !== 0 ):?>
                                    <div class="show_more_options blue">										
                                        <span class="span_bg"></span>
                                        <p>View Features and Specifications</p>
                                    </div>
                                    <div class="attr_hide">
                                        <?php foreach($product['attr'] as $temp):?>
                                            <p class="feat_and_specs_items"><strong><?php echo html_escape($temp['field']);?>:</strong> <?php echo html_escape($temp['value']);?></p>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <?php endforeach;?>	
                    </div>
                    <div class="feedback_wrapper">
                        <?php foreach($transact['users'] as $uk=>$user):?>
                        <div class="feedback_container">
                            <?php if( $user['has_feedb'] == 0 ): ?>
                                <p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
                                <div class="transac-feedback-container">
                                    <h2>Feedback</h2>
                                    <?php
                                    $attr = array('class'=>'transac-feedback-form');
                                    echo form_open('',$attr);
                                    ?>
                                    <input type="hidden" name="feedb_kind" value="0">
                                    <input type="hidden" name="order_id" value="<?php echo $tk;?>">
                                    <input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
                                    <textarea rows="4" cols="50" name="feedback-field"></textarea>
                                    <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                    <br>
                                    <span class="star-label"><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div>
                                    <span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
                                    <br>
                                    <span class="star-label"><?php echo $this->lang->line('rating')[1].':'; ?> </span><div class="feedb-star rating2"></div>
                                    <span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
                                    <br>
                                    <span class="star-label"><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div>
                                    <span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
                                    <br>
                                    <span class="raty-error"></span>
                                    <br>
                                    <span class="feedback-submit">Submit</span><span class="feedback-cancel">Cancel</span>
                                    <?php echo form_close();?>
                                </div>
                            <?php endif;?>
                        </div>
                    <?php endforeach;?>
                    </div>
    </div>
    </div>
    <!--<div class="clear"></div>-->
    <?php $transac_counter++;?>
    <?php if($transac_counter === $items_per_page): $transac_counter = 0;$pageNum++;?>
    </div><div class="paging" data-page="<?php echo $pageNum?>">
    <?php endif;?>
    <?php endforeach;?>
    </div>
    <?php endif; ?>
    </div>
    
    
    <div id="complete_sell" class="dashboard_table" data-key="csell" data-controller="2">
        <h2>Sold Items</h2>
        <?php if($transaction['count']['csell']===0):?>
        <br/>
        <div><span class='nocontent'>There are no transactions for this category.</span></div>
        <?php else: ?>
        
        <div class="pagination" id="pagination-complete-sold">
            <a href="#" class="first" data-action="first">&laquo;</a>
            <a href="#" class="previous" data-action="previous">&lsaquo;</a>
            <input type="text" readonly="readonly" data-max-page="<?php echo ($transaction['count']['csell']===0)?1:(ceil($transaction['count']['csell']/$items_per_page));?>" data-origmaxpage="<?php echo ($transaction['count']['csell']===0)?1:(ceil($transaction['count']['csell']/$items_per_page));?>"/>
            <a href="#" class="next" data-action="next">&rsaquo;</a>
            <a href="#" class="last" data-action="last">&raquo;</a>
        </div>
        
        <div class="post_item_srch_container">
            <input type="text" class="box sch_box tx_sch_box" placeholder="Transaction No." onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search'" />
            <span class="span_bg sch_btn tx_sch_btn"></span>
            <label for="active_sort">Payment Filter</label>
            <select name="active_sort" class="post_active_sort tx_sort_select">
                <option value="0">Show All</option>
                <option value="1">PayPal</option>
                <option value="2">DragonPay</option>
                <option value="3">Cash on Delivery</option>
                <option value="5">Direct Bank Deposit</option>
            </select>
            <span class="span_bg arrow_sort tx_arrow_sort "></span>
        </div>
        
        <div class="page_load" style="display:none;text-align:center; margin-top: 50px;">
            <img src="/assets/images/orange_loader_small.gif" class="loading_img"/>
        </div>
        
        <?php $transac_counter = 0;$pageNum=0;?>
        <div class="paging" data-page="<?php echo $pageNum?>">
            <?php foreach($transaction['complete']['sell'] as $tk=>$transact):?>
            <div class="transac-container content-paging" data-pm="<?php echo $transact['payment_method']?>" data-invoice="<?php echo $transact['invoice_no']?>">
                <div class="transac_title">
                    <div class="transac_title_table">
                        <div class="transac_title_col1">
                            <span><strong>Transaction No.:</strong></span>
                            <span><?php echo $transact['invoice_no'];?></span> 
                        </div>
                        <div class="transac_title_col2">
                            <span><strong>Date:</strong></span>
                            <span class="transac_title_date"><?php echo $transact['dateadded']?></span>
                        </div>
                        <div class="transac_title_col3">
                            <strong>Sold to: </strong>
                            <a href="/<?php echo $transact['buyer_slug']?>"><?php echo $transact['buyer']?></a> <br />
                            <span class="transac_address_details_show" style="color:#0191C8;cursor:pointer;font-size:10px;text-decoration:underline;">View Delivery details</span>
                            <div style="display:none;" class="transac_address_cont">
                                <?php foreach($transact['users'] as $uk=>$user):?>
                                    <span class="tad_1 consignee">
                                        <strong>Consignee: </strong>
                                        <span><?php echo html_escape($user['address']['consignee']);?></span> 
                                    </span>
                                    <?php if(trim($user['address']['mobile']) != ''):?>
                                        <span class="tad_1 mobile">
                                            <strong>Mobile: </strong>
                                            <span><?php echo html_escape($user['address']['mobile']);?></span> 
                                        </span>
                                    <?php endif;?>
                                    <?php if(trim($user['address']['telephone']) != ''):?>
                                        <span class="tad_1 telephone">
                                            <strong>Telephone: </strong>
                                            <span><?php echo html_escape($user['address']['telephone']);?></span> 
                                        </span>
                                    <?php endif;?>
                                    <span style="display:block;"></span>
                                    <span class="tad_2 stateregion">
                                        <strong>State/Region:</strong>
                                        <span><?php echo $user['address']['stateregion']?></span> 
                                    </span>
                                    <span class="tad_2 city">
                                        <strong>City:</strong>
                                        <span><?php echo $user['address']['city'];?></span> 
                                    </span>
                                    <span class="tad_3 fulladdress">
                                        <strong>Address:</strong>
                                        <span><?php echo html_escape($user['address']['fulladd']);?></span> 
                                    </span>
                                    <?php if( $user['address']['lat']!=0 && $user['address']['lng']!=0 ):?>
                                        <span class="tsold_viewmap" data-lat="<?php echo $user['address']['lat'];?>" data-lng="<?php echo $user['address']['lng'];?>">View Map</span>
                                        <div class="map_modalcont" style="display:none;"></div>
                                    <?php endif;?>
                                <?php endforeach;?>
                                <span class="transac_address_details_hide">Close</span>
                            </div>
                        </div>			
                    </div>
                </div>
                <div class="transac_prod_wrapper">
                    
                    <?php foreach($transact['products'] as $opk=>$product):?>
                    <div class="sold_prod_container transac-product-container">
                        <div class="transac_prod_first">
                            <div class="img_transac_prod_con">
                                <span class="img_transac_prod">
                                    <img src="/<?php echo $product['product_image_path'];?>">
                                </span>
                            </div>
                            <div class="info_transac_prod_con">
                                <div class="title_top_transac_table">
                                    <span class="title_top_transac_col1">
                                        <a href="/item/<?php echo $product['slug'];?>"><?php echo html_escape($product['name']);?></a>
                                    </span>
                                    <span class="title_top_transac_col2">
                                        Quantity:<span><?php echo $product['order_quantity']?></span>
                                    </span>
                                    <span class="title_top_transac_col3">
                                        Total:<span>Php<?php echo number_format($product['price'],2,'.',',');?></span>
                                    </span>
                                </div>
                                <div class="transac_bought_con">
                                    <span class="transac_bought_con_col1"></span>
                                    <span class="transac_bought_con_col2"></span>
                                    <span class="transac_bought_con_col3">
                                        Status:
                                        <?php if($product['status'] == 1):?>
                                            <span class="trans_alert trans_green">Item Delivered</span>
                                        <?php elseif($product['status'] == 2):?>
                                            <span class="trans_alert trans_red">Order Canceled</span>
                                        <?php elseif($product['status'] == 3):?>
                                            <span class="trans_alert trans_green">Cash on delivery</span>
                                        <?php elseif($product['status'] == 4):?>
                                            <span class="trans_alert trans_green">Payment Received</span>
                                        <?php elseif($product['status'] == 5):?>
                                            <span class="trans_alert trans_red">Payment Returned</span>
                                        <?php endif;?>						
                                    </span>
                                </div>
                                <?php if( count($product['attr']) !== 0 ):?>
                                    <div class="show_more_options blue">										
                                        <span class="span_bg"></span>
                                        <p>View Features and Specifications</p>
                                    </div>
                                    <div class="attr_hide">
                                        <?php foreach($product['attr'] as $temp):?>
                                            <p class="feat_and_specs_items"><strong><?php echo html_escape($temp['field']);?>:</strong> <?php echo html_escape($temp['value']);?></p>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                                
                            </div>	
                        </div>		
                    </div>
                <?php endforeach;?>				
            </div>

            <div class="feedback_wrapper">
                <?php foreach($transact['users'] as $uk=>$user):?>
                    <div class="feedback_container">
                        <?php if( $user['has_feedb'] == 0 ): ?>
                            <p class="transac-feedback-btn"> + Feedback for <?php echo $user['name'];?></p>
                            <div class="transac-feedback-container">
                                <h2>Feedback</h2>
                                <?php
                                $attr = array('class'=>'transac-feedback-form');
                                echo form_open('',$attr);
                                ?>
                                <input type="hidden" name="feedb_kind" value="1">
                                <input type="hidden" name="order_id" value="<?php echo $tk;?>">
                                <input type="hidden" name="for_memberid" value="<?php echo $uk;?>">
                                <textarea rows="4" cols="50" name="feedback-field"></textarea>
                                <span class="red ci_form_validation_error"><?php echo form_error('feedback-field'); ?></span>
                                <br>
                                <span class="star-label"><?php echo $this->lang->line('rating')[0].':'; ?>  </span><div class="feedb-star rating1"></div>
                                <span class="red ci_form_validation_error"><?php echo form_error('rating1'); ?></span>
                                <br>
                                <span class="star-label"><?php echo $this->lang->line('rating')[1].':'; ?> </span><div class="feedb-star rating2"></div>
                                <span class="red ci_form_validation_error"><?php echo form_error('rating2'); ?></span>
                                <br>
                                <span class="star-label"><?php echo $this->lang->line('rating')[2].':'; ?>  </span><div class="feedb-star rating3"></div>
                                <span class="red ci_form_validation_error"><?php echo form_error('rating3'); ?></span>
                                <br>
                                <span class="raty-error"></span>
                                <br>
                                <span class="feedback-submit">Submit</span> <span class="feedback-cancel">Cancel</span>
                                <?php echo form_close();?>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endforeach;?>
            </div>
    </div>
    <!--<div class="clear"></div>-->
    <?php $transac_counter++;?>
    <?php if($transac_counter === $items_per_page): $transac_counter = 0;$pageNum++;?>
    </div><div class="paging" data-page="<?php echo $pageNum?>">
    <?php endif;?>
    <?php endforeach;?>
    </div>
    <?php endif; ?>					
    </div>
    
</div>

<div id="vendor_url_dialog" style="display:none;">
    This is a one time change of URL! You won't be able to change this again!<br><br>
    Continue?
</div>

        <div class="profile_main_content" id="security_settings">
            <h2>Settings</h2>
            
            <div class="profile_fields">
                <p>Store URL</p>
                <div>
                
                    <div class="disp_vendor_url settings_vendordiv_css">
                        <a href="/<?php echo $userslug?>" target="_blank">
                            <?php echo base_url()?><span class="disp_userslug"><?php echo $userslug?></span>
                        </a>
                        <?php if( $render_userslug_edit ):?>
       
                            <span class="span_bg edit_userslug edit-lnk">Edit </span>
                            
                        <?php endif;?>
                    </div>
                    
                    <?php if( $render_userslug_edit ):?>
                    <div class="datafield settings_vendordiv_css" style="display:none;">
                        <?php echo form_open('',array('id'=>'form_userslug'));?>
                            <?php echo base_url()?><input type="text" name="userslug" value="<?php echo html_escape($userslug)?>">
                            <input type="submit" class="save_userslug editslug_btn_css" name="userslug_save" value="Save">
                            <input type="button" class="cancel_userslug editslug_btn_css" value="Cancel">
                        <?php echo form_close();?>
                    </div>
                    <?php endif;?>
                    
                    <p style='font-size: 13px; width: 95%;'>
                        Take note that you can only change your easyshop store URL once. 
                        Visit your store and use the available to features to connect with your buyers.
                    </p>
                    
                </div>
            </div>
            <br/>
            <div class="profile_fields">

                        <p>Login password</p>
                        <div>
                            <p>****************** <a href="/chngepaswd" class="change_password">change password</a></p>
                            <p>
                                Having a strong password makes your account more secure. We recommend that you change your password regularly. For the best security, use a combination of numbers, letters and special characters.
                            </p>
                        </div>
    
                    </div>
                </div>			
                
            </section>
            <div class="clear"></div>
        </div>


        <script type='text/javascript' src='/assets/js/src/vendor/jquery.simplemodal.js?ver=<?=ES_FILE_VERSION?>'></script>
        <script type='text/javascript' src='/assets/js/src/vendor/jquery.numeric.js'></script>
        <script type='text/javascript' src='/assets/js/src/vendor/jquery.validate.js'></script>
        <script type='text/javascript' src='/assets/js/src/vendor/jquery.Jcrop.min.js'></script>
        <script type="text/javascript" src="/assets/js/src/vendor/jquery.raty.min.js"></script>
        <script type='text/javascript' src='/assets/js/src/vendor/jquery.jqpagination.min.js'></script>
        <script type="text/javascript" src="/assets/js/src/vendor/jquery.idTabs.min.js" ></script>
        <script type="text/javascript" src="/assets/js/src/vendor/chosen.jquery.min.js" ></script>
        <script type="text/javascript" src="/assets/js/src/vendor/jquery.cookie.js" ></script>
        <script type="text/javascript" src="/assets/js/src/memberpage.js?ver=<?=ES_FILE_VERSION?>"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=&sensor=false"></script>

        <script type="text/javascript">

                                       
                    var jsonCity = <?php echo $json_city;?>;
                    var tx = {
                        u:'<?php echo $username;?>',
                        p:''
                    };


                    $(document).ready(function() { 
                        var srchdropcontent= $('#search_content');
                        $('#member_sch').focus(function() {
                            if(srchdropcontent.find("ul").length > 0){
                                $('#search_content').fadeIn(150);
                            }

                            $(document).bind('focusin.search_content click.search_content',function(e) {
                                if ($(e.target).closest('#search_content, #member_sch').length) 
                                    return;
                                $('#search_content').fadeOut('fast');
                            });
                        });
 
                        $('#search_content').hide();
           
                    });

                    $(document).ready(function() { 
                        var shipping_comment_container= $('.shipping_comment_cont');
                        $(shipping_comment_container).parents('#simplemodal-container').css('width','506px');
                    });
                    
                    

</script>
<script src="/assets/js/src/vendor/jquery.easing.min.js" type="text/javascript"></script>
<script src="/assets/js/src/vendor/jquery.scrollUp.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
    $.scrollUp({
                scrollName: 'scrollUp', // Element ID
                scrollDistance: 300, // Distance from top/bottom before showing element (px)
                scrollFrom: 'top', // 'top' or 'bottom'
                scrollSpeed: 300, // Speed back to top (ms)
                easingType: 'linear', // Scroll to top easing (see http://easings.net/)
                animation: 'fade', // Fade, slide, none
                animationInSpeed: 200, // Animation in speed (ms)
                animationOutSpeed: 200, // Animation out speed (ms)
                scrollText: 'Scroll to top', // Text for element, can contain HTML
                scrollTitle: false, // Set a custom <a> title if required. Defaults to scrollText
                scrollImg: false, // Set true to use image
                activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
                zIndex: 2147483647 // Z-Index for the overlay
            });
});

</script>

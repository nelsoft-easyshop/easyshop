<!--[if lt IE 9]>
        <script src="assets/js/src/vendor/excanvas.js"></script>
<![endif]-->

<link type="text/css" href="/assets/css/product_search_category.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet"  media="screen"/> 
<link type="text/css" href='/assets/css/jqpagination.css' rel="stylesheet" media='screen'/>
<link type="text/css" href="/assets/css/jquery.Jcrop.min.css" rel="stylesheet" media='screen'/>
<link type="text/css" href="/assets/css/memberpage.css?ver=<?=ES_FILE_VERSION?>" rel="stylesheet" media='screen'/>

<div id = "member_page_body">
    <div class="clear"></div>
    <section>
        <div class="wrapper profile_content">
            <div class="logo"> <a href="/"><span class="span_bg"></span></a> </div>			
        </div>
    </section>
    <div class="clear"></div>
    
    <!-- MODAL JCROP PREVIEW DIV-->
    <div id="div_user_image_prev" style="display:none;">
        <span> Crop your Photo! </span>
        <img src="" id="user_image_prev">
        <button>OK</button>
    </div>
    <section>
        <div class="wrapper">
            <div class="vendor_cover">
                <?php if($renderEdit):?>
                <div id="banner_edit" class="edit_vendor_cover border_radius1 img_edit"><span class="span_bg edit_btn"></span>Edit</div>
                <?php 
                    $attr = array('id'=>'banner_form','data-tag'=>'banner');
                    echo form_open_multipart('memberpage/banner_upload', $attr);
                ?>
                    
                    <input type="file" class="img_file_input" style="visibility:hidden;height:0px;width:0px;position:absolute;" id="bannerupload" accept="image/*" name="userfile"/>
                    <input type='hidden' name='x' value='0' class='image_x'>
                    <input type='hidden' name='y' value='0' class='image_y'>
                    <input type='hidden' name='w' value='0' class='image_w'>
                    <input type='hidden' name='h' value='0' class='image_h'>
                <?php echo form_close();?>
                <?php endif;?>
                
                <?php if(strpos($banner,'default')):?>
                    <?php if($renderEdit):?>
                    <div class="edit_vendor_txt_cover">
                        <h1 class="f24">cover image</h1>
                        <p>
                            <span class="f18">980 x 270 pixels</span><br />
                            <span class="f14">(GIF, JPEG, JPG and PNG files only with maximum file size of 5MB)</span>
                        </p>
                    </div>
                    <?php endif;?>
                <?php endif;?>
                <img src="<?php echo $banner;?>">
            </div>
            <div class="vendor_info_wrapper">
                <div class="vendor_avatar_wrapper">
                    <?php if($renderEdit):?>
                    <div id="avatar_edit" class="vendor_edit_avatar border_radius1 img_edit"><small class="span_bg edit_btn"></small> Edit</div>
                    <?php 
                        $attr = array('id'=>'avatar_form','data-tag'=>'avatar');
                        echo form_open_multipart('memberpage/upload_img', $attr);
                    ?>
                        <input type="file" class="img_file_input" style="visibility:hidden;height:0px;width:0px;position:absolute;" id="avatarupload" accept="image/*" name="userfile"/>
                        <input type='hidden' name='x' value='0' class='image_x'>
                        <input type='hidden' name='y' value='0' class='image_y'>
                        <input type='hidden' name='w' value='0' class='image_w'>
                        <input type='hidden' name='h' value='0' class='image_h'>
                        <input type="hidden" name="vendor" value="vendor">
                    <?php echo form_close();?>
                    <?php endif;?>
                    <div class="vendor-avatar">
                        <span>
                            <?php echo $image_profile?>	
                        </span>
                    </div>
                    

                    <p class='follow-cnt'>
                        <span class='span-cont' id='follower-lnk'><?php echo count($followers)?> <label>followers</label></span>
                        <span class='span-cont' id='following-lnk'><?php echo count($following)?> <label>following</label></span>
                    </p>
     
                    <?php if( !$renderEdit ):?>
                        <input id="subscribe_status" type="hidden" value="<?php echo $subscribe_status?>">
                        <?php echo form_open('');?>
                        <p id="follow_btn" class="subscription_btn btn2 btn-follow" style="text-align:center; display:<?php echo $subscribe_status==='unfollowed'?'':'none'?>">
                            <small class="sprite-bg follow-icon"></small> Follow
                        </p>
                        <p class="subscription_btn btn2 btn-unfollow" style="text-align:center; display:<?php echo $subscribe_status==='followed'?'':'none'?>">
                            <small class="sprite-bg unfollow-icon"></small> Unfollow
                        </p>
                        <input id="vendor_name" type="hidden" value="<?php echo $vendordetails['username']?>" name="name">
                        <input type="hidden" value="<?php echo $vendordetails['userslug']?>" name="userlink">
                        <?php echo form_close();?>
                    <?php endif;?>

                </div>
                <div class="vendor_info_con">
                    <div class="vendor_info_con_left">
                        <div id="user_store_name"> 
                            <div id="user_store_echo">
                                <h2><?php echo html_escape($store_name);?></h2>
                                <small id="username_echo" style="display: <?php echo $hasStoreName ? '' : 'none' ?>">by <?php echo $vendordetails['username']?></small>
                                
                                <?php if($renderEdit):?>
                                    <div id="store_name_edit"><small class="span_bg edit_btn"></small> Edit</div>
                                <?php endif;?>
                            </div>
                            <?php if($renderEdit):?>
                            <div id="user_store_edit" style="display:none;">
                                <?php echo form_open('');?>
                                    <input type="text" class="store-name-field" data-origname="<?php echo $hasStoreName ? html_escape($store_name) : $vendordetails['username'] ?>"
                                           name="store_name" value="<?php echo $hasStoreName ? html_escape($store_name) : $vendordetails['username'] ?>"
                                           placeholder="Enter a new store name...">
                                    <input type="hidden" name="store_name_hidden" value="1">
                                    <input id="store_name_submit" type="button" value="Save">
                                    <input id="store_name_cancel" type="button" value="Cancel">
                                <?php echo form_close();?>
                            </div>
                            <?php endif;?>
                        </div>
                        
                        <div class="vendor_store_desc">
                            <div id="store_desc_echo" style="display:<?php echo $hasStoreDesc ? '' : 'none'?>;" class="vendor_desc_dis_con">
                                <p><?php echo html_escape($vendordetails['store_desc']);?></p>
                                <?php if($renderEdit):?>
                                <span id="store_desc_edit" style="display:none;" class="border_radius1"><small class="span_bg edit_btn"></small> Edit</span>
                                <?php endif;?>
                            </div>
                            <?php if($renderEdit):?>
                            <div style="display:<?php echo $hasStoreDesc ? 'none' : ''?>;" class="vendor_edit_con">
                                <?php echo form_open('');?>
                                    <textarea name="desc" value="<?php echo $vendordetails['store_desc']?>" placeholder="Write a few sentences to tell people about your store (the kind of products you sell, your mission, etc)."><?php echo $vendordetails['store_desc']?></textarea>
                                    <input type="hidden" name="store_desc" value="1">
                                    <input id="store_desc_submit" class="orange_btn3" type="button" value="Save">
                                <?php echo form_close();?>
                            </div>
                            <?php endif;?>
                        </div>
                        <div class="vendor_info_member_details">
                            <p><strong>Member since: </strong><?php echo $vendordetails['datecreated'] != '' ? $vendordetails['datecreated'] : 'N/A';?></p>
                            <p><strong>Contact No.: </strong><?php echo $vendordetails['contactno'] != '' ? '0'.html_escape($vendordetails['contactno']) : 'N/A'?></p>
                            <p>
                            <span class="span_bg vendor_map"></span> 
                                <?php echo $vendordetails['stateregionname'] != '' && $vendordetails['cityname'] != '' ? $vendordetails['stateregionname'] . ", " . $vendordetails['cityname'] : "Location not set."?>
                            </p>

                            <div class="vendor-msg-modal">
                                <div>
                                    <a id="modal-launcher" href="javascript:void(0)" class="orange_btn3 modal-launcher2">
                                        <small class="span_bg prod_message"></small> Send a message
                                    </a>
                                </div>
                            </div>
                            
                        </div>

                        
                        <?php if($renderEdit):?>
                            <div class="progress_bar_panel">
                                <div>
                                    <h3>Total Posted Items</h3>
                                    <input class="db_total_items fm1" readonly="readonly"  data-value="<?php echo $active_count + $deleted_count;?>" value="<?php echo $active_count + $deleted_count;?>">
                                </div>
                                <div>
                                    <h3>Active Items</h3>
                                    <input class="db_total_items fm1" readonly="readonly" data-value="<?php echo $active_count;?>" value="<?php echo $active_count;?>">
                                </div>
                                <div>
                                    <h3>Sold Items</h3>
                                    <input class="db_total_items fm1" readonly="readonly" data-value="<?php echo $sold_count;?>" value="<?php echo $sold_count;?>">
                                </div>
                            </div>
                        <?php endif; ?>
                      

                    </div>
                    <div class="vendor_info_con_right">
                        <div class="posted_feedbacks_top">
                            <h3 class="f14">Feedback Score:</h3>
                            <span>(<?php echo $allfeedbacks['rcount'];?> Feedback/s received)</span>
                            <p><strong><?php echo $this->lang->line('rating')[0].':'; ?></strong> 
                            <span>
                            <?php if($allfeedbacks['rating1'] === 0 ):?>
                                This user is not yet rated
                            <?php else:?>
                                <?php for($i = 0; $i < $allfeedbacks['rating1']; $i++):?>
                                    <span class="span_bg star_on"></span>
                                <?php endfor;?>
                                <?php for($i = 0; $i < 5-$allfeedbacks['rating1']; $i++):?>
                                    <span class="span_bg star_off"></span>
                                <?php endfor;?>
                            <?php endif;?>
                            </span>
                            </p>
                            <p><strong><?php echo $this->lang->line('rating')[1].':'; ?></strong> 
                            <span><?php if($allfeedbacks['rating2'] === 0 ):?>
                                This user is not yet rated
                            <?php else:?>
                                <?php for($i = 0; $i < $allfeedbacks['rating2']; $i++):?>
                                    <span class="span_bg star_on"></span>
                                <?php endfor;?>
                                <?php for($i = 0; $i < 5-$allfeedbacks['rating2']; $i++):?>
                                    <span class="span_bg star_off"></span>
                                <?php endfor;?>
                            <?php endif;?>
                            </span>
                            </p>
                            <p><strong><?php echo $this->lang->line('rating')[2].':'; ?></strong> 
                            <span>
                            <?php if($allfeedbacks['rating3'] === 0 ):?>
                                This user is not yet rated
                            <?php else:?>
                                <?php for($i = 0; $i < $allfeedbacks['rating3']; $i++):?>
                                    <span class="span_bg star_on"></span>
                                <?php endfor;?>
                                <?php for($i = 0; $i < 5-$allfeedbacks['rating3']; $i++):?>
                                    <span class="span_bg star_off"></span>
                                <?php endfor;?>
                            <?php endif;?>
                            </span>
                            </p>
                            <div>
                                <a href="javascript:void(0)" class="view_all_feedbacks blue"><small class="span_bg plus_btn"></small> View All Feedbacks</a>
                            </div>

                        </div>
                        <div class="clear"></div>

                    </div>	
                </div>
                <div class="clear"></div>
            </div>

            <?php $items_per_page = 10;?>
            <!-- *********** Start Feedback ********** -->
            <div class="dashboard_table vendor_feedbacks_table user-tab" id="dashboard-feedbacks">
                    <h2>Feedbacks</h2><a href="javascript:void(0)" class="hide_all_feedbacks blue"><span class="span_bg minus_btn"></span> Hide All Feedbacks</a>
                        <ul class="idTabs">
                            <li><a href="#op_buyer">Feedbacks from Sellers</a></li>
                            <li><a href="#op_seller">Feedbacks from Buyers</a></li>
                            <li><a href="#yp_buyer">Feedbacks to Sellers</a></li>
                            <li><a href="#yp_seller">Feedbacks to Buyers</a></li>
                        </ul>

                        <div class="clear"></div>
                        <div id="others_post">

                            <div id="op_buyer">
                                <h4>Feedbacks others posted for <?php echo $vendordetails['username'];?> as a buyer</h4>
                                <?php if(count($allfeedbacks['otherspost_buyer'])==0):?>
                                <div class="default_empty_feedback">
                                    <p><img src="<?=base_url()?>assets/images/img_default_feedback.png"><strong><?php echo $vendordetails['username'];?> have not yet received any feedbacks for this category.</strong></p>
                                </div>
                                <?php else:?>
                                    <?php $afb_counter = 0;?>
                                    <div class="paging posted_feedbacks">
                                    <?php foreach($allfeedbacks['otherspost_buyer'] as $k=>$tempafb):?>
                                            <div>
                                                <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
                                                <?php foreach($tempafb as $key=>$afb):?>
                                                <div class="fbck_info_con">
                                                    <a href="<?php echo base_url();?>
                                                    <?php echo $afb['member_name'];?>">
                                                        <img src="<?php echo $afb['user_image']?>" class="img_feedback">
                                                    </a>
                                                    <p>
                                                        <a href="<?php echo base_url();?><?php echo $afb['member_name'];?>">
                                                            <?php echo $afb['member_name'];?>
                                                        </a> <br />
                                                        <?php echo $afb['dateadded'];?>
                                                    </p>
                                                </div>
                                                
                                                <div class="fbck_rating_message_con">
                                                    <div class="fbck_rating_con">
                                                        <p>
                                                            <span class="txt_fb_rating">
                                                                <?php echo $this->lang->line('rating')[0].':'; ?>
                                                            </span>
                                                            <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                                                                <span class="span_bg star_on"></span>
                                                            <?php endfor;?>
                                                            <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
                                                                <span class="span_bg star_off"></span>
                                                            <?php endfor;?>
                                                        </p>
                                                        <p>
                                                            <span class="txt_fb_rating">
                                                                <?php echo $this->lang->line('rating')[1].':'; ?>
                                                            </span>
                                                            <?php for($i = 0; $i < $afb['rating2']; $i++):?>
                                                                <span class="span_bg star_on"></span>
                                                            <?php endfor;?>
                                                            <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
                                                                <span class="span_bg star_off"></span>
                                                            <?php endfor;?>
                                                        </p>
                                                        <p>
                                                            <span class="txt_fb_rating">
                                                                <?php echo $this->lang->line('rating')[2].':'; ?>
                                                            </span>
                                                            <?php for($i = 0; $i < $afb['rating3']; $i++):?>
                                                                <span class="span_bg star_on"></span>
                                                            <?php endfor;?>
                                                            <?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
                                                                <span class="span_bg star_off"></span>
                                                            <?php endfor;?>
                                                        </p>
                                                    </div>
                                                    <div class="fbck_message_con">
                                                        <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
                                                    </div>
                                                </div>																								
                                                <?php endforeach;?>
                                            </div>
                                            <?php $afb_counter++;?>
                                            <?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
                                                <div class="paging"></div>
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
                                <h4>Feedbacks others posted for <?php echo $vendordetails['username'];?> as a seller</h4>
                                <?php if(count($allfeedbacks['otherspost_seller'])==0):?>
                                <div class="default_empty_feedback">
                                    <p>
                                        <img src="<?=base_url()?>assets/images/img_default_feedback.png">
                                        <strong><?php echo $vendordetails['username'];?> have not yet received any feedbacks for this category.</strong>
                                    </p>
                                </div>									
                                <?php else:?>
                                    <?php $afb_counter = 0;?>
                                    <div class="paging posted_feedbacks">
                                    <?php foreach($allfeedbacks['otherspost_seller'] as $k=>$tempafb):?>
                                        
                                            <div>
                                                <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
                                                <?php foreach($tempafb as $afb):?>
                                                <div class="fbck_info_con">
                                                    <a href="<?php echo base_url();?><?php echo $afb['member_name'];?>">
                                                        <img src="<?php echo $afb['user_image']?>" class="img_feedback">
                                                    </a>											
                                                    <p>
                                                        <a href="<?php echo base_url();?><?php echo $afb['member_name'];?>">
                                                            <?php echo $afb['member_name'];?>
                                                        </a><br />
                                                        <?php echo $afb['dateadded'];?>
                                                    </p>
                                                </div>
                                                <div class="fbck_rating_message_con">
                                                <div class="fbck_rating_con">
                                                    <p>
                                                        <span class="txt_fb_rating"><?php echo $this->lang->line('rating')[0].':'; ?></span> 
                                                        <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                                                            <span class="span_bg star_on"></span>
                                                        <?php endfor;?>
                                                        <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
                                                            <span class="span_bg star_off"></span>
                                                        <?php endfor;?>
                                                    </p>
                                                    <p>
                                                        <span class="txt_fb_rating"><?php echo $this->lang->line('rating')[1].':'; ?></span> 
                                                        <?php for($i = 0; $i < $afb['rating2']; $i++):?>
                                                            <span class="span_bg star_on"></span>
                                                        <?php endfor;?>
                                                        <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
                                                            <span class="span_bg star_off"></span>
                                                        <?php endfor;?>
                                                    </p>
                                                    <p>
                                                        <span class="txt_fb_rating"><?php echo $this->lang->line('rating')[2].':'; ?></span> 
                                                        <?php for($i = 0; $i < $afb['rating3']; $i++):?>
                                                            <span class="span_bg star_on"></span>
                                                        <?php endfor;?>
                                                        <?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
                                                            <span class="span_bg star_off"></span>
                                                        <?php endfor;?>
                                                    </p>
                                                </div>
                                                <div class="fbck_message_con">
                                                    <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
                                                </div>
                                                </div>
                                                <div class="clear"></div>
                                                    <?php endforeach;?>
                                            </div>
                                            <?php $afb_counter++;?>
                                            <?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
                                                <div class="paging"></div>
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
                            <h4>Feedbacks <?php echo $vendordetails['username'];?> posted as a buyer</h4>
                            <?php if(count($allfeedbacks['youpost_buyer'])==0):?>
                                <div class="default_empty_feedback">
                                    <p>
                                        <img src="<?=base_url()?>assets/images/img_default_feedback.png">
                                        <strong><?php echo $vendordetails['username'];?> have not yet posted any feedbacks for this category.</strong>
                                    </p>
                                </div>	
                                
                            <?php else:?>
                            <?php $afb_counter = 0;?>
                                <div class="paging posted_feedbacks">
                                <?php foreach($allfeedbacks['youpost_buyer'] as $k=>$tempafb):?>
                                    <div>
                                        <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
                                        <?php foreach($tempafb as $afb):?>
                                        <div class="fbck_info_con">
                                            <span class="img_feedback_user"><?php echo $image_profile?></span>										
                                            <p>
                                                <?php echo $vendordetails['username'];?><br />
                                                <?php echo $afb['dateadded'];?>
                                            </p>												
                                        </div>
                                        <div class="fbck_rating_message_con">
                                            <p class="fbck_membername">
                                                For:
                                                <a href="<?php echo base_url();?><?php echo $afb['for_membername'];?>">
                                                     <strong><?php echo $afb['for_membername'];?></strong> 
                                                </a>												
                                            </p>
                                            <div class="fbck_rating_con">
                                                <p>
                                                    <span class="txt_fb_rating">
                                                        <?php echo $this->lang->line('rating')[0].':'; ?>
                                                    </span>
                                                    <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                                                        <span class="span_bg star_on"></span>
                                                    <?php endfor;?>
                                                    <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
                                                        <span class="span_bg star_off"></span>
                                                    <?php endfor;?>
                                                </p>
                                                <p>
                                                    <span class="txt_fb_rating">
                                                        <?php echo $this->lang->line('rating')[1].':'; ?>
                                                    </span>
                                                    <?php for($i = 0; $i < $afb['rating2']; $i++):?>
                                                        <span class="span_bg star_on"></span>
                                                    <?php endfor;?>
                                                    <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
                                                        <span class="span_bg star_off"></span>
                                                    <?php endfor;?>
                                                </p>
                                                <p>
                                                    <span class="txt_fb_rating">
                                                        <?php echo $this->lang->line('rating')[2].':'; ?>
                                                    </span>
                                                    <?php for($i = 0; $i < $afb['rating3']; $i++):?>
                                                        <span class="span_bg star_on"></span>
                                                    <?php endfor;?>
                                                    <?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
                                                        <span class="span_bg star_off"></span>
                                                    <?php endfor;?>
                                                </p>
                                            </div>
                                            <div class="fbck_message_con">
                                                <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
                                            </div>
                                        </div>

                                        <?php endforeach;?>
                                    </div>
                                    <?php $afb_counter++;?>
                                    <?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
                                        <div class="paging"></div>
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
                            <h4>Feedbacks <?php echo $vendordetails['username'];?> posted as a seller</h4>
                            <?php if(count($allfeedbacks['youpost_seller'])==0):?>
                                <div class="default_empty_feedback">
                                    <p>
                                        <img src="<?=base_url()?>assets/images/img_default_feedback.png">
                                        <strong><?php echo $vendordetails['username'];?> have not yet posted any feedbacks for this category.</strong>
                                    </p>
                                </div>	
                            <?php else:?>
                            <?php $afb_counter = 0;?>
                                <div class="paging posted_feedbacks">
                                <?php foreach($allfeedbacks['youpost_seller'] as $k=>$tempafb):?>
                                        <div>
                                            <!--<h3>Feedback from Transaction#: <?php echo $k;?></h3>-->
                                            <?php foreach($tempafb as $afb):?>
                                            <div class="fbck_info_con">
                                                <span class="img_feedback_user"><?php echo $image_profile?></span>	
                                                <p>
                                                    <?php echo $vendordetails['username'];?><br />
                                                    <?php echo $afb['dateadded'];?>
                                                </p>												
                                            </div>
                                            
                                            <div class="fbck_rating_message_con">
                                                <p>
                                                    For:
                                                    <a href="<?php echo base_url();?><?php echo $afb['for_membername'];?>">
                                                        <strong><?php echo $afb['for_membername'];?></strong>
                                                    </a>
                                                </p>
                                                <div class="fbck_rating_con">
                                                    <p>
                                                        <span class="txt_fb_rating">
                                                            <?php echo $this->lang->line('rating')[0].':'; ?>
                                                        </span>
                                                        <?php for($i = 0; $i < $afb['rating1']; $i++):?>
                                                            <span class="span_bg star_on"></span>
                                                        <?php endfor;?>
                                                        <?php for($i = 0; $i < 5-$afb['rating1']; $i++):?>
                                                            <span class="span_bg star_off"></span>
                                                        <?php endfor;?>
                                                    </p>
                                                    <p>
                                                        <span class="txt_fb_rating">
                                                            <?php echo $this->lang->line('rating')[1].':'; ?>
                                                        </span>
                                                        <?php for($i = 0; $i < $afb['rating2']; $i++):?>
                                                            <span class="span_bg star_on"></span>
                                                        <?php endfor;?>
                                                        <?php for($i = 0; $i < 5-$afb['rating2']; $i++):?>
                                                            <span class="span_bg star_off"></span>
                                                        <?php endfor;?>
                                                    </p>
                                                    <p>
                                                        <span class="txt_fb_rating">
                                                            <?php echo $this->lang->line('rating')[2].':'; ?>
                                                        </span>
                                                        <?php for($i = 0; $i < $afb['rating3']; $i++):?>
                                                            <span class="span_bg star_on"></span>
                                                        <?php endfor;?>
                                                        <?php for($i = 0; $i < 5-$afb['rating3']; $i++):?>
                                                            <span class="span_bg star_off"></span>
                                                        <?php endfor;?>
                                                    </p>
                                                </div>
                                                <div class="fbck_message_con">
                                                    <p>"<?php echo html_escape($afb['feedb_msg'])?>"</p>
                                                </div>
                                            </div>												
                                            <?php endforeach;?>
                                        </div>
                                        <?php $afb_counter++;?>
                                        <?php if($afb_counter === $items_per_page): $afb_counter = 0;?>
                                            <div class="paging"></div>
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

                <!-- ********** Start of vendor product ************ -->
                <div class="wrapper">
                <div class="vendor_products_wrapper user-tab">
                <?php if($product_count > 0):?>
                    <?php foreach($products as $catID=>$p):?>
                    <div class="vendor_txt_prod_header" class="<?php echo $p['slug']?>">
                        <div class="home_cat_product_title" style="background-color:#0078d5;">
                            <a target="_blank" href="<?php echo $p['cat_link']?>" <?php echo $p['cat_link']==="" ? 'onclick="return false"':""?> >
                                <img src="<?=base_url()?><?php echo $p['cat_img']?>">
                                <h2><?php echo $p['name']?></h2> 
                            </a>   
                        </div>
                    </div>
                    <div class="vendor_prod_items">
                        <?php foreach($p['products'] as $prod):?>
                            <div class="product vendor_product">
                                <a target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                    <span class="prod_img_wrapper">
                                        <span class="prod_img_container">
                                           <img src="<?=base_url()?><?php echo $prod['product_image_path']?>">
                                        </span>
                                    </span>
                                </a>    
                                <h3>
                                    <a target="_blank" href="<?php echo base_url() . 'item/' . $prod['slug']?>">
                                       <?php echo html_escape($prod['name'])?>
                                    </a>
                                </h3>
                                 <div class="price-cnt">
                                    <div class="price">
                                        Php <?php echo html_escape($prod['price'])?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <div class="txt_load_more_con v_loadmore">
                        <a target="_blank" href="<?php echo $p['loadmore_link']?>" class="grey_btn">LOAD MORE ITEMS</a>
                    </div>
                    <?php endforeach;?>
                <?php endif;?>
                </div>
                </div>
            <!-- ********** end of vendor product ************ -->
        </div>
        
        
        <div class='subscription_cont user-tab' id='follower-tab'>
            <div class='header'>
                <img src='/assets/images/img_icon_partner_small.png'/>
                <span><?php echo count($followers) ?> follower(s)</span>
                <a class='close'></a>
            </div>

            <?php foreach($followers as $user): ?>
                <div class="seller_div_r3">
                    <a href='/<?php echo $user['userslug']?>'>
                        <div class='img-wrapper'>
                            <span class='img-cont'>
                                <img src="<?php echo $user['imgurl']?>"/>
                            <span>
                        </div>
                    </a>
                    <div class='content'>
                        <a href='/<?php echo $user['userslug']?>'>
                            <span class='title'><?php echo html_escape($user['vendor_name']); ?></span>
                        </a>
                        <span class='sub-title'><?php echo $user['follower_cnt'] ?> follower(s)</span>
                        
                        <?php if( $renderEdit && $logged_in ):?>
                            <?php if(intval($user['is_followed']) === 0): ?>
                                <?php echo form_open('');?>
                                
                                <div class='follow-btn'> 
                                    <span class='subscription_btn' style='display:inline'><a href="javascript:void(0)">+ Subscribe</a></span> 
                                    <span class='subscription_btn' style='display:none'><a href="javascript:void(0)">- Unsubscribe</a></span> 
                                </div>
                                
                                <input type="hidden" value="<?php echo $user['vendor_name']?>" name="name">
                                <?php echo form_close();?>
                            <?php else: ?>
                                <div class='follow-btn'> 
                                    <span>Already following</span>
                                </div>
                            <?php endif; ?>
                        <?php endif;?>
                        
                   
                    </div>
                </div>
            <?php endforeach; ?>

            <div class='clear'></div>
        
        </div>
        
        <div class='subscription_cont user-tab' id='following-tab'>
            <div class='header'>
                <img src='/assets/images/img_icon_partner_small.png'/>
                <span>Following <?php echo count($following) ?> user(s)</span>
                <a class='close'></a>
            </div>

            <?php foreach($following as $user): ?>
                <div class="seller_div_r3">
                        <a href='/<?php echo $user['userslug']?>'>
                            <div class='img-wrapper'>
                                <span class='img-cont'>
                                    <img src="<?php echo $user['imgurl']?>"/>
                                <span>
                            </div>
                        </a>
                        <div class='content'>
                            <a href='/<?php echo $user['userslug']?>'>
                                <span class='title'><?php echo html_escape($user['vendor_name']); ?></span>
                            </a>
                        <span class='sub-title'><?php echo $user['follower_cnt'] ?> follower(s)</span>
                        
                        <?php if( $renderEdit && $logged_in ):?>
                            <?php echo form_open('');?>
                            
                            <div class='follow-btn'> 
                                <span class='subscription_btn' style='display:none'><a href="javascript:void(0)">+ Subscribe</a></span> 
                                <span class='subscription_btn' style='display:inline'><a href="javascript:void(0)">- Unsubscribe</a></span> 
                            </div>
                            
                            <input type="hidden" value="<?php echo $user['vendor_name']?>" name="name">
                            <?php echo form_close();?>
                        <?php endif;?>
                        
                    </div>
                </div>
            <?php endforeach; ?>

            <div class='clear'></div>
        
        </div>

</div>

      
<div id="modal-background"></div>
<div id="modal-container">
    <div id="modal-div-header">
        <button id="modal-close">X</button>        
    </div>
    <div id="modal-inside-container" class="mrgn-top-10">
        <div>
            <label>To : </label>
            <input type="text" value="<?=$vendordetails['username'];?>" disabled id="msg_name" name="msg_name" class="ui-form-control" >
        </div>
        <div>
            <label>Message : </label>
            <textarea cols="40" rows="5" name="msg-message" id="msg-message" class="ui-form-control" placeholder="Say something.."></textarea>		
        </div>
    </div>
    <button id="modal_send_btn">Send</button>
</div>


<input type='hidden' id='tab-cmd' value='<?php echo html_escape($tab);?>'/>
<input type='hidden' id='user-id' value='<?php echo $my_id ?>'/>
<input type='hidden' id='seller-id' value='<?php echo $vendordetails['id_member'] ?>'/>
<input type='hidden' id='seller-username' value='<?php echo $vendordetails['username'] ?>'/>
        
<script src="/assets/js/src/vendor/jquery.raty.min.js" type="text/javascript"></script>
<script src='/assets/js/src/vendor/jquery.jqpagination.min.js' type='text/javascript'></script>
<script src="/assets/js/src/vendor/jquery.idTabs.min.js" type="text/javascript"></script>
<script src='/assets/js/src/vendor/jquery.Jcrop.min.js' type='text/javascript' ></script>
<script src='/assets/js/src/vendor/jquery.simplemodal.js' type='text/javascript' ></script>
<script src="/assets/js/src/vendorpage.js?ver=<?=ES_FILE_VERSION?>" type="text/javascript"></script>

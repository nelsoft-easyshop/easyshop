<style>
    <?php $colorHexadecimal = $storeColorScheme->getHexadecimal(); ?>
    
    .followers-circle{
        background: #<?php echo html_escape($colorHexadecimal) ?> !important;
    }
    
    .vendor-nav li a.vendor-nav-active {
        color: #<?php echo html_escape($colorHexadecimal) ?> !important;
        border-bottom: 3px solid #<?php echo html_escape($colorHexadecimal) ?> !important;
    }
    
    .vendor-nav li a:hover {
        border-bottom: 3px solid #<?php echo html_escape($colorHexadecimal) ?> !important;
    }
    
    .panel-category-heading,  .panel-category-heading > .panel-title > a{
        background: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
    
    .active-view{
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
    
    .icon-view:hover {
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
    
    .active-category{
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
    
    .pagination-items > li.active > a,
    .pagination-items > li.active > span{
        background-color: #<?php echo html_escape($colorHexadecimal); ?> !important;
        border: solid #<?php echo html_escape($colorHexadecimal); ?> 1px !important
    }
    
    .vendor-footer-con{
        border-bottom: 4px solid #<?php echo html_escape($colorHexadecimal); ?>;
    }
    
    #scrollUp, #scrollUp:hover{
        background: #<?php echo html_escape($colorHexadecimal); ?>
                    url("<?php echo getAssetsDomain(); ?>assets/images/img_arrow_down_white2.png") center no-repeat !important;
    }
    
    .tab-active-link{
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
    
    .div-feedback-list{
        border-top: solid #<?php echo html_escape($colorHexadecimal); ?> 3px !important;
    }
    
    .a-tab:hover {
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }

    .list-category > li > a.active,
    .list-sub-category > li > a.active,
    .list-category > li > a:hover,
    .list-sub-category > li > a:hover {
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
    
    .tab_categories:active,
    .tab_categories:visited,
    .tab_categories:focus{
        color: #<?php echo html_escape($colorHexadecimal); ?> !important;
    }
</style>


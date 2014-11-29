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
    .pagination-items > li.active > a{
        background-color: #<?php echo html_escape($colorHexadecimal); ?> !important;
        border: solid #<?php echo html_escape($colorHexadecimal); ?> 1px !important
    }
    .vendor-footer-con{
        border-bottom: 4px solid #<?php echo html_escape($colorHexadecimal); ?>;
    }
    #scrollUp, #scrollUp:hover{
        background: #<?php echo html_escape($colorHexadecimal); ?>
                    url("/assets/images/img_arrow_down_white2.png") center no-repeat !important;
    }
</style>
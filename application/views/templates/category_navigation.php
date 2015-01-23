
<nav>                
    <ul>                    

    <?PHP for($x=0;$x < sizeof($cat_items);$x++): ?>                
        <li  class="category_item">
            <p style="background:url('<?php echo getAssetsDomain(); ?>assets/<?PHP echo $cat_items[$x]['path']; ?>') no-repeat scroll left center #fff">
                <a href="/category/<?PHP echo $cat_items[$x]['slug']; ?>">
                    <span><?PHP echo html_escape($cat_items[$x]['NAME']); ?></span>
                </a>
            </p>
        </li>
    <?php endfor; ?>
    </ul>
</nav>



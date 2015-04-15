
<?php

    $currentPage = isset($currentPage) ? (int)$currentPage : 1;
    
    $totalPage = isset($totalPage) ? (int) $totalPage : 1;
    
    $hasHashtag = isset($hasHashtag) ? (bool) $hasHashtag : true;
?>

<ul data-lastpage="<?=$totalPage; ?>" class="pagination pagination-items nav">
<?php for ($i=1; $i <= $totalPage; $i++): ?>
    <li data-page='<?=$i; ?>' class="<?= $currentPage === $i ? 'active' : ''; ?> individual">
        <a href="<?php echo $hasHashtag ? '#page-'.$i : 'javascript:void(0);'; ?>" >
            <span>
                <?=$i?>
            </span>
        </a>
    </li>
<?php endfor; ?>
</ul>



<?php

    $currentPage = isset($currentPage) ? $currentPage : 1;
?>

<ul data-lastpage="<?=$totalPage; ?>" class="pagination pagination-items nav">
<?php for ($i=1; $i <= $totalPage; $i++): ?>
    <li data-page='<?=$i; ?>' class="<?= $currentPage === $i ? 'active' : ''; ?> individual">
        <a href="#page-<?=$i; ?>" >
            <span>
                <?=$i?>
            </span>
        </a>
    </li>
<?php endfor; ?>
</ul>


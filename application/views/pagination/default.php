<?php 

    /**
     * The current page
     *
     * @var integer
     */
    $currentPage = isset($currentPage) ? (int) $currentPage : 1;
    
    /**
     * The last page
     *
     * @var integer
     */
    $lastPage = isset($lastPage) ? (int) $lastPage : 1;

    /**
     * The max number of pages after the current page 
     * Also equals to the number of pages before pagination logic kicks in
     *
     * @var integer
     */
    $maxPages = isset($maxPages) ? (int) $maxPages : 9;
    
    /**
     * Maximum number of pages to the left of the currentPage to maintain
     *
     * @var integer
     */
    $pagesBefore = isset($pagesBefore) ? (int) $pagesBefore : 3;
    
    /**
     * Add hyperlink or not
     *
     * @var bool
     */
    $isHyperLink = isset($isHyperLink) ? (bool) $isHyperLink : true;

    /**
     * The redirect url for hyperlinks
     * 
     * @var string
     */
    $url = isset($url) ? $url : '';

    $start = $lastPage > $maxPages ? $currentPage-$pagesBefore : 1; 
    $start = $start < 1 ? 1 : $start;
    
    $pagesAfterCurrentPage = $lastPage - $currentPage - 1; 
    $pagesAfterCurrentPage = $pagesAfterCurrentPage > $maxPages ? $maxPages : $pagesAfterCurrentPage;

    $end = $lastPage > $maxPages ? $currentPage + $pagesAfterCurrentPage : $lastPage; 
    $end = $end > $lastPage ? $lastPage : $end ;

?>


<ul class="pagination pagination-items" data-lastpage="<?php echo $lastPage?>">

    <?php if($lastPage > 0): ?>
        <?php $previousPage = ($currentPage - 1) > 1 ? ($currentPage - 1) : 1; ?>
        <li data-page='<?php echo $previousPage ?>' class="extremes previous">
            <a href='<?php echo $isHyperLink ? $url.'?page='.$previousPage : 'javascript:void(0)' ?>'>
                <span> &laquo; </span>
            </a>
        </li>
    <?php endif; ?>
    
    <?php if($start > 1): ?>
        <li class='individual' data-page='1'>
            <a href='<?php echo $isHyperLink ? $url.'?page=1' : 'javascript:void(0)' ?>'>
                <span>1</span>
            </a>
        </li>
        <?php if(intval($start) !== 2): ?>
            <li><span>...</span></li>
        <?php endif; ?>
    <?php endif; ?>

    <?php for($i = $start ; $i <= $end; $i++): ?>
        <li class='<?php echo (int)$i === (int)$currentPage ? 'active' : '' ?> individual' data-page='<?php echo $i ?>'>
            <a href='<?php echo $isHyperLink ? $url.'?page='.$i : 'javascript:void(0)' ?>'>
                <span><?php echo $i ?></span>
            </a>
        </li>
    <?php endfor; ?>
    
    <?php if($end < $lastPage): ?>
        <?php if(intval($end) !== intval($lastPage)-1): ?>
            <li><span>...</span></li>
        <?php endif; ?>
        <li class='individual' data-page='<?php echo $lastPage ?>'>
            <a href='<?php echo $isHyperLink ? $url.'?page='.$lastPage : 'javascript:void(0)' ?>'>
                <span><?php echo $lastPage?></span>
            </a>
        </li>
    <?php endif; ?>
 
    <?php if($lastPage > 0): ?>
        <?php $nextPage = ($currentPage + 1) <= $lastPage ? ($currentPage + 1) : $lastPage; ?>
        <li data-page='<?php echo $nextPage ?>' class="extremes next">
            <a href='<?php echo $isHyperLink ? $url.'?page='.$nextPage : 'javascript:void(0)' ?>'>
                <span> &raquo; </span>
            </a>
        </li>
    <?php endif; ?>
</ul>


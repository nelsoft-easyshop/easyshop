<?php 

    /**
     * The current page
     *
     * @var integer
     */
    $currentPage = isset($currentPage) ? $currentPage : 1;
    
    /**
     * The last page
     *
     * @var integer
     */
    $lastPage = isset($lastPage) ? $lastPage : 1;


    /**
     * number of pages before pagination logic kicks in
     *
     * @var integer
     */
    $maxPages = isset($maxPages) ? $maxPages : 10;

    /**
     * Pages to the left of the currentPage to maintain
     *
     * @var integer
     */
    $pagesBefore = isset($pagesBefore) ? $pagesBefore : 5;
    
    /**
     * Add hyperlink or not
     *
     * @var bool
     */
    $isHyperLink = isset($isHyperLink) ? $isHyperLink : true;

    /**
     * The redirect url for hyperlinks
     * 
     * @var string
     */
    $url = isset($url) ? $url : '';

    $start = $lastPage > $maxPages ? $currentPage-$pagesBefore : 1; 
    $start = $start < 1 ? 1 : $start;
    
    $range = ($currentPage - $start);
    $range = $range > $maxPages ? $maxPages : $range;

    $end = $lastPage > $maxPages ? $currentPage+$range : $lastPage; 
    $end = $end > $lastPage ? $lastPage : $end ;
    
?>


<ul class="pagination pagination-items">

    <?php if($lastPage > 0): ?>
        <?php $previousPage = ($currentPage - 1) > 1 ? ($currentPage - 1) : 1; ?>
        <li data-page='<?php echo $previousPage ?>'>
            <a href='<?php echo $isHyperLink ? $url.'?page='.$previousPage : 'javascript:void(0)' ?>'>
                <span> &laquo; </span>
            </a>
        </li>
    <?php endif; ?>

    <?php for($i = $start ; $i <= $end; $i++): ?>
        <li class='<?php echo $i === $currentPage ? 'active' : '' ?>' data-page='<?php echo $i ?>'>
            <a href='<?php echo $isHyperLink ? $url.'?page='.$i : 'javascript:void(0)' ?>'>
                <span><?php echo $i ?></span>
            </a>
        </li>
    <?php endfor; ?>
    
    <?php if($lastPage > 0): ?>
        <?php $nextPage = ($currentPage + 1) <= $lastPage ? ($currentPage + 1) : $lastPage; ?>
        <li data-page='<?php echo $nextPage ?>'>
            <a href='<?php echo $isHyperLink ? $url.'?page='.$nextPage : 'javascript:void(0)' ?>'>
                <span> &raquo; </span>
            </a>
        </li>
    <?php endif; ?>
</ul>


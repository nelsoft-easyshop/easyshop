
<div itemscope itemtype="http://schema.org/Product">
    <?php foreach ($breadCrumbs as $crumbs): ?> 
    <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="<?=base_url().'/category/'.html_escape($crumbs['slug']); ?>" itemprop="url"> 
            <meta itemprop="title" content="<?=html_escape($crumbs['name']);?>" />
        </a>
    </div> 
    <?php endforeach; ?>
    
    <meta itemprop="name" content="<?=html_escape($product->getName());?>">
    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer"> 
        <meta itemprop="price" content="<?=number_format($product->getFinalPrice(), 2); ?>" />
        <meta itemprop="priceCurrency" content="PHP" />
        <meta itemprop="seller" content="<?=html_escape($product->getMember()->getStoreName());?>" />
    </div>
    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"> 
        <meta itemprop="ratingValue" content="<?=$averageRating; ?>" />
        <meta itemprop="ratingCount" content="<?=$reviewCount; ?>" /> 
        <meta itemprop="bestRating" content="<?=EasyShop\Entities\EsProductReview::REVIEW_BEST_RATING; ?>" />
    </div>
</div>


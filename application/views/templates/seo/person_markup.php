
<div itemscope itemtype="http://schema.org/Person">
    <a itemprop="url" style="display:block" href="<?=base_url().html_escape($userslug); ?>">
        <meta itemprop="name" content="<?=html_escape($store_name)?>" />
    </a>

    <div itemscope itemtype="http://schema.org/Organization"> 
        <meta itemprop="name" content="Easyshop.ph" />
    </div>

    <meta itemprop="email" content="<?=html_escape($email)?>" />
    <meta itemprop="jobtitle" content="Easyshop Vendor" /> 
    <meta itemprop="description" content="<?=html_escape($store_desc)?>" />
 
    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <meta itemprop="streetAddress" content="<?=html_escape($address)?>" />
        <meta itemprop="addressLocality" content="<?=html_escape($cityname)?>" />
        <meta itemprop="addressRegion" content="<?=html_escape($stateregionname)?>" /> 
        <meta itemprop="addressCountry" content="<?=html_escape($country)?>" /> 
    </div>
 
</div>
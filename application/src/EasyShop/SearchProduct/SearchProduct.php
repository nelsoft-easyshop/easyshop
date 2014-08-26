<?php

namespace EasyShop\SearchProduct;

use EasyShop\Entities\EsProduct;

/**
 * Search Product Class
 *
 * @author Ryan Vasquez
 */
class SearchProduct
{

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }

    /**
     * Search all product within category given in parameter
     * @param  array $catId  
     * @return array;
     */
    public function filterByCategory($catId = array())
    { 
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findBy(['cat' => $catId]); 

        return $products;
    }

    /**
     * Search all product using string given in parameter
     * @param  string $string
     * @return array;
     */
    public function filterBySearchString($string = "")
    {
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findByKeyword();
    }
}

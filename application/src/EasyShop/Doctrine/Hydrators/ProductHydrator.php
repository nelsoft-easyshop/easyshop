<?php 

namespace EasyShop\Doctrine\Hydrators;

use Doctrine\ORM\Internal\Hydration\ObjectHydrator;

/**
 * Custom Hydrator for the Product entity, does nothing at the moment.
 * Leaving it here so other people may see how it can be used
 * Register this custom hydrator by adding the following
 * to the kernel: 
 *
 * $container['entity_manager']->getConfiguration()
 *      ->addCustomHydrationMode('ProductHydrator', 
 *                              'EasyShop\Doctrine\Hydrators\ProductHydrator');
 *     
 * USAGE:
 * $query =  $this->em->createQueryBuilder()-
 *                     ...
 *                    ->getQuery(); 
 *  $result = $query->getResult('ProductHydrator');
 *       
 *
 * @author sam gavinio <samgavinio@easyshop.ph>
 * 
 */
class ProductHydrator extends ObjectHydrator
{

    protected function hydrateAllData()
    {
        $result = parent::hydrateAllData()[0];
        //do whatever you need with the result
        return $result;
    }
}


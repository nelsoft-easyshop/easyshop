<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsOrderProductAttrRepository extends EntityRepository
{

    /**
     * Returns product attributes in each order product
     * @param int $orderid
     * @return object
     */    
    public function getOrderProductAttributes($orderid)
    {
        $qb = $this->_em->createQueryBuilder();
        $queryBuilder = $qb->select("orderAttr.attrName as attrName,                                                         
                                     orderAttr.attrValue as attrValue,                                                         
                                     orderAttr.attrPrice as attrPrice ")
                            ->from('EasyShop\Entities\EsOrderProductAttr','orderAttr')
                            ->leftJoin('EasyShop\Entities\EsOrderProduct', 'o', 'with', "o.idOrderProduct = orderAttr.orderProduct")    
                            ->where("o.idOrderProduct = :orderid")
                            ->setParameter('orderid', $orderid)
                            ->getQuery(); 

        return $queryBuilder->getResult();                                                 
       
    }

}
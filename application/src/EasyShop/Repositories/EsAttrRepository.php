<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsAttr;
use Doctrine\ORM\Query\ResultSetMapping;

class EsAttrRepository extends EntityRepository
{
    /**
     * Get attribute available in category
     * @param  array $categories 
     * @return mixed
     */
    public function getAttributeListByCategory($categories)
    {
        $this->em =  $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('attrName', 'attrName'); 
        $rsm->addScalarResult('lookuplistId', 'lookuplistId'); 
        $query = $this->em->createNativeQuery("
        SELECT DISTINCT
            a.name AS attrName 
            , a.attr_lookuplist_id as lookuplistId
        FROM
            es_attr a
            , es_datatype b
            , es_attr_lookuplist c
        WHERE a.datatype_id = b.id_datatype
            AND a.attr_lookuplist_id = c.id_attr_lookuplist
            AND a.cat_id IN (:categories)
            GROUP BY attrName, a.attr_lookuplist_id
            ORDER BY attrName ASC
        ", $rsm);

        $query->setParameter('categories', $categories); 
        $results = $query->getResult();

        return $results;
    }
}


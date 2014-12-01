<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

class EsCategoryNestedSetRepository extends EntityRepository
{

    /**
     * Gets the number of category nested set 
     *
     * @return integer
     */
    public function getNestedSetCategoryCount()
    {
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('count', 'count');
        $sql = "SELECT COUNT(*) as count FROM es_category_nested_set WHERE 1 ";
        $query = $this->_em->createNativeQuery($sql, $rsm);
        return $query->getResult()[0]['count'];
    }


}
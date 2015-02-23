<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsSchoolRepository extends EntityRepository
{
    /**
     * Get all schools
     * @return array
     */
    public function getAllSchools()
    {
        $qb = $this->_em->createQueryBuilder();
        $query = $qb->select('tblSchool.name')
                    ->from('EasyShop\Entities\EsSchool', 'tblSchool')
                    ->getQuery();

        return $query->getResult();
    }
}

<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsBankInfo;
use Doctrine\ORM\Query;

class EsBankInfoRepository extends EntityRepository
{
    
    /**
     * Returns all the banks
     *
     * @param boolean $getAsArray
     * @return mixed
     */
    public function getAllBanks($getAsArray = false)
    {
        $em = $this->_em;       
        $query = $em->createQueryBuilder()
                    ->select('b')
                    ->from('EasyShop\Entities\EsBankInfo','b')
                    ->getQuery();
        $banks =  $getAsArray ? $query->getResult(Query::HYDRATE_ARRAY) : $query->getResult();         
        
        return $banks;
    }                          
                              
}


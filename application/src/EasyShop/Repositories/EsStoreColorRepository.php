<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

class EsStoreColorRepository extends EntityRepository
{

    /**
     * Get all store colors
     *
     * @param boolean $asArray
     * @return mixed
     */
    public function getAllColors($asArray = false)
    {
        $query = $this->_em->createQueryBuilder()
                            ->select('storeColor') 
                            ->from('EasyShop\Entities\EsStoreColor','storeColor')
                            ->getQuery();
        if($asArray){
            $colors = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
        else{
            $colors = $query->getResult();
        }

        return $colors;
    }
    
    
}


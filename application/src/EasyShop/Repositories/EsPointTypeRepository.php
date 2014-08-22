<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsPointType;

class EsPointTypeRepository extends EntityRepository
{
    /*
    public function getActionId($actionString)
    {
        
		return $this->_em->createQueryBuilder()
							->select('p.id')
							->from('EasyShop\Entities\EsPointType','p')
							->where('p.name = :name')
							->setParameter('name',$actionString)
							->setMaxResults(1)
							->getQuery()
							->getResult();

    }

    public function getPointEquivalent($actionId)
    {
    	return $this->_em->createQueryBuilder()
    						->select('p.point')
    						->from('EasyShop\Entities\EsPointType','p')
    						->where('p.id = :id')
    						->setParameter('id',$actionId)
    						->setMaxResults(1)
    						->getQuery()
    						->getResult();
    }
    */
}
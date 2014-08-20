<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsPoint;

class EsPointRepository extends EntityRepository
{

	public function getUserPointData($userId)
	{
		return $this->_em->createQueryBuilder()
							->select('p')
							->from('EasyShop\Entities\EsPoint','p')
							->where('p.m = :id')
							->setParameter('id',$userId)
							->getQuery()
							->getOneOrNullResult();
		
	}

}
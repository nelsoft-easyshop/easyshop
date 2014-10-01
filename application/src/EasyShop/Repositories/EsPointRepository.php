<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsPoint;

class EsPointRepository extends EntityRepository
{
    /**
     * Queries DB for users current amount of points
     *
     * @param int $memberId User Member Id
     *
     * @return string
     */ 
    public function getMaxPoint($memberId)
    {
        $points = $this->_em->getRepository('EasyShop\Entities\EsPoint')
                            ->findOneBy(['member' => intval($memberId)]);
        return $points ? $points->getPoint() : 0;
    }
}

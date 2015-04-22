<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;

class EsMemberMergeRepository extends EntityRepository
{
    /**
     * Checks if a member has been merged
     *
     * @param integer $memberId
     * @retrun mixed
     */
    public function isMemberMerged($memberId)
    {
        $em = $this->_em;
        $dql = "
            SELECT mm
            FROM EasyShop\Entities\EsMemberMerge mm
            WHERE mm.member = :memberId
        ";

        $query = $em->createQuery($dql)
                    ->setParameter("memberId", $memberId);

        $isMemberMerged = empty($query->getResult()) === false;
                      
        return $isMemberMerged;
    }
}

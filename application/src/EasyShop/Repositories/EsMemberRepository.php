<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 *  es_member Repository
 *
 *  @author stephenjanz
 */
class EsMemberRepository extends EntityRepository
{
    /**
     *  Fetch entries in es_member with exact storeName excluding excludeMemberId
     *
     *  @param integer $excludeMemberId
     *  @param string $storeName
     *
     *  @return boolean
     */
    public function getUsedStoreName($excludeMemberId, $storeName)
    {
        $em = $this->_em;

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('EasyShop\Entities\EsMember','m');
        $rsm->addFieldResult('m','id_member','idMember');
        $rsm->addFieldResult('m','store_name','storeName');

        $query = $em->createNativeQuery(
            'SELECT id_member, store_name
            FROM es_member
            WHERE id_member != ? AND store_name LIKE ?'
        , $rsm);

        $query->setParameter(1,$excludeMemberId);
        $query->setParameter(2,$storeName);

        return $query->getResult();
    }
}

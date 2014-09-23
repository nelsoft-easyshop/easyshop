<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsAddress; 

class EsAddressRepository extends EntityRepository
{
    public function getAddressDetails($memberId, $type)
    {
        $em = $this->_em;
        $dql = "
            SELECT a,s,c
            FROM EasyShop\Entities\EsAddress a
            JOIN a.stateregion s
            JOIN a.city c
            WHERE a.idMember = :member_id
                AND a.type = :type
        ";

        $query = $em->createQuery($dql)
                    ->setParameter('member_id', $memberId)
                    ->setParameter('type', $type);

        return $query->getResult();
    }

    public function getShippingAddress($memberId)
    {
        $address = $this->_em->getRepository('EasyShop\Entities\EsAddress')
                        ->findOneBy(['idMember' => $memberId, 'type' => '1']);
        return $address->getStateregion()->getIdLocation();
    }
}

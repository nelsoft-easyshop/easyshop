<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsAddress; 

class EsAddressRepository extends EntityRepository
{
    public function getShippingAddress($memberId)
    {
        $address = $this->_em->getRepository('EasyShop\Entities\EsAddress')
                        ->findOneBy(['idMember' => $memberId, 'type' => '1']);
        return $address->getStateregion()->getIdLocation();
    }
}

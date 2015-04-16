<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsAddress as EsAddress; 

class EsAddressRepository extends EntityRepository
{   
    /**
     * Return state region id of address
     * @param  integer $memberId [description]
     * @param  integer $type     [description]
     * @return integer
     */
    public function getAddressStateRegionId($memberId, $type = EsAddress::TYPE_DELIVERY)
    {
        $address = $this->_em->getRepository('EasyShop\Entities\EsAddress')
                        ->findOneBy(['idMember' => $memberId, 'type' => '1']);
        if($address){
            return $address->getStateregion()->getIdLocation();
        }
        return false;
    }

    /**
     * Retrieves consignee address
     * @param int $memberId
     * @param string $type
     * @param bool $asArray
     * 
     * @return EsAddress Entity
     */        
    public function getConsigneeAddress($memberId, $type, $asArray = false) 
    {
        $query = $this->_em->createQueryBuilder()
                            ->select('consigneeAddress as address
                                    ,IDENTITY(consigneeAddress.city) as city
                                    ,IDENTITY(consigneeAddress.stateregion) as stateRegion
                                    ') 
                            ->from('EasyShop\Entities\EsAddress','consigneeAddress')
                            ->where('consigneeAddress.idMember = :memberId')
                            ->andWhere('consigneeAddress.type = :type')
                            ->setParameter("memberId",$memberId)
                            ->setParameter("type",$type)
                            ->getQuery();
        if($asArray){
            $address = $query->getOneOrNullresult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }
        else{
            $address = $query->getOneOrNullresult();
        }

        return $address;
      
    }
}

<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsLocationLookup;
use Doctrine\ORM\Query\ResultSetMapping;

class EsLocationLookupRepository extends EntityRepository
{
    /**
     * Fetch Locations from location lookup table to fill dropdown listbox
     */
    public function getLocation()
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder()
                            ->select('e1.idLocation') 
                            ->addSelect('e1.location') 
                            ->addSelect('e2.idLocation as idRegion') 
                            ->addSelect('e2.location as region')
                            ->addSelect('e3.idLocation as idCityprov')
                            ->addSelect('e3.location as cityprov')
                            ->from('EasyShop\Entities\EsLocationLookup','e1')
                            ->leftJoin('EasyShop\Entities\EsLocationLookup', 'e2','WITH','e2.parent = e1.idLocation AND e1.type = 1 AND e2.type = 2')
                            ->leftJoin('EasyShop\Entities\EsLocationLookup', 'e3','WITH','e3.parent = e2.idLocation AND e3.type = 3 AND e2.type = 2')
                            ->where('e1.type = 1')  
                            ->getQuery();

        $result = $qb->getResult();

        $data = array();
        foreach($result as $r){
            $data['area'][$r['location']][$r['region']][$r['idCityprov']] = $r['cityprov'];
            $data['islandkey'][$r['location']] = $r['idLocation'];
            $data['regionkey'][$r['region']] = $r['idRegion'];
        }
        
        return $data;
    }

    /**
     * Retrieves Parent Location of a specific location
     */
    public function getParentLocation($idLocation)
    {
        $this->em = $this->_em;

        $locationLookup = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                ->find($idLocation);

        return $locationLookup->getParent();
    }

    /**
     * Retrieves locations with the given type
     */
    public function getAllLocationType($type)
    {
        $this->em = $this->_em;

        $locations = $this->em->createQueryBuilder()
                        ->select('l.location')
                        ->from('EasyShop\Entities\EsLocationLookup','l')
                        ->where('l.type=:type')
                        ->orderBy('l.location','ASC')
                        ->setParameter('type', $type)
                        ->getQuery()
                        ->getResult();

        return $locations;
    }

    public function getCities($stateRegion)
    {
        $this->em = $this->_em;
        
        $state = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                ->findOneBy(["location" => $stateRegion]);

        $locations = $this->em->createQueryBuilder()
                        ->select('l.location')
                        ->from('EasyShop\Entities\EsLocationLookup','l')
                        ->where('l.parent=:parent')
                        ->orderBy('l.location','ASC')
                        ->setParameter('parent', $state->getIdLocation())
                        ->getQuery()
                        ->getResult();
        return $locations;
    }
}


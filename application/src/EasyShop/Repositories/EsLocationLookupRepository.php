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

    public function verifyLocationCombination($stateRegionId, $cityId)
    {
        $em = $this->_em;
        $dql = "
            SELECT loc, p
            FROM EasyShop\Entities\EsLocationLookup loc
            JOIN loc.parent p
            WHERE loc.idLocation = :city_id
                AND loc.parent = :stateregion_id
        ";

        $query = $em->createQuery($dql)
                    ->setParameter('city_id', $cityId)
                    ->setParameter('stateregion_id', $stateRegionId);

        return $query->getResult();
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
     * Retrieves stateregions cities that is used for delivery address tab in memberpage
     * @param bool $isJsonReturn (parameter that is used to convert citylookup to JSON)
     * @return mixed
     */
    public function getLocationLookup($isJsonReturn = false)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
 
        $qbResult = $qb->select('loc')
                        ->from('EasyShop\Entities\EsLocationLookup','loc')
                        ->where(
                                    $qb->expr()->in('loc.type', [EsLocationLookup::TYPE_COUNTRY
                                                                ,EsLocationLookup::TYPE_STATEREGION
                                                                ,EsLocationLookup::TYPE_CITY])
                                )
                        ->orderBy('loc.location', 'ASC')
                        ->getQuery();

        $result = $qbResult->getResult();

        foreach($result as $key => $value){
            $locationType = intval($value->getType());
            if($locationType === 0){ 
                $data['countryName'] = $value->getLocation();
                $data['countryId'] =  $value->getidLocation();
            }
            else if($locationType === 3){
                $data['stateRegionLookup'][$value->getidLocation()] = $value->getLocation();
            }
            else if($locationType === 4){
                $data['cityLookup'][$value->getParent()->getIdLocation()][$value->getidLocation()] = $value->getLocation();
            }
        }

        if($isJsonReturn) {
            $data['json_city'] = json_encode($data['cityLookup'], JSON_FORCE_OBJECT); 
        }
        return $data;
    }
 
    /**
     * Retrieves locations with the given type
     */
    public function getAllLocationType($type, $format = FALSE)
    {
        $this->em = $this->_em;

        if($format){
            $locations = $this->em->createQueryBuilder()
                        ->select('l1.location as location1')
                        ->addSelect('l2.location as location2')
                        ->from('EasyShop\Entities\EsLocationLookup','l1')
                        ->leftJoin('EasyShop\Entities\EsLocationLookup', 'l2','WITH','l2.parent = l1.idLocation AND l1.type = 3 AND l2.type = 4')
                        ->where('l1.type =:type')
                        ->setParameter('type', $type)
                        ->getQuery()
                        ->getResult();

            $formattedLocations = [];
            foreach ($locations as $index => $data) {
                $formattedLocations[$data['location1']][] = $data['location2'];
            }
            $locations = $formattedLocations;
        }
        else{
            $locations = $this->em->createQueryBuilder()
                        ->select('l.location')
                        ->from('EasyShop\Entities\EsLocationLookup','l')
                        ->where('l.type=:type')
                        ->orderBy('l.location','ASC')
                        ->setParameter('type', $type)
                        ->getQuery()
                        ->getResult();
        }
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


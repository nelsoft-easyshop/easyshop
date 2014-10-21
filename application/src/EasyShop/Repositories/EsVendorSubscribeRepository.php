<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 
use Doctrine\ORM\Query\ResultSetMapping;

// NOTE
// If a user follows another, add their ids to this table
// where member_id is the user that follows 
// and vendor_id is the user he's following.

class EsVendorSubscribeRepository extends EntityRepository
{
    /**
     * Get users followers
     * @param  integer $userId
     * @param  integer $offset
     * @param  integer $perPage
     * @return mixed
     */
    public function getFollowers($userId,$offset = 0,$perPage = 6)
    {
        $this->em =  $this->_em;
        $qb = $this->em->createQueryBuilder();
        $qbResult = $qb->select('vs')
                            ->from('EasyShop\Entities\EsVendorSubscribe','vs')
                            ->where('vs.vendor = :userId')
                            ->setParameter('userId', $userId)
                            ->getQuery();
        $totalCount = count($qbResult->getResult());
        $qbResult->setFirstResult($offset*$perPage)
                 ->setMaxResults($perPage);
        $result = $qbResult->getResult();

        return array(
                    'count' =>  $totalCount,
                    'followers' => $result
                );
    }

    /**
     * get recommended followers on page
     * @param  integer $memberId member id of the page owner
     * @param  integer $viewerId member id of the page viewer
     * @param  integer $perPage
     * @param  array   $ids list of member id that will not be displayed
     * @return mixed
     */
    public function getRecommendToFollow($memberId,$viewerId,$perPage = 6,$ids=[])
    {
        $em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_member','id_member'); 

        $addQuery = "";
        if(!empty($ids)){
            $addQuery = " AND a.id_member NOT IN (:ids) ";
        }

        $sql = "SELECT id_member
                FROM es_member  a  
                WHERE a.id_member NOT IN (:member_id,:viewer_id)
                AND a.id_member NOT IN (
                    SELECT vendor_id from es_vendor_subscribe where member_id = :viewer_id
                )
                AND a.id_member NOT IN (
                    SELECT member_id from es_vendor_subscribe where vendor_id = :member_id
                )
                $addQuery
                ORDER BY RAND()
                LIMIT :per_page";

        $query = $em->createNativeQuery($sql,$rsm)
                        ->setParameter('member_id', $memberId)
                        ->setParameter('viewer_id', $viewerId)
                        ->setParameter('per_page', $perPage);

        if(!empty($ids)){
            $query = $query->setParameter('ids', $ids);
        }

        $result = $query->getResult();
        $memberIds = [];
        foreach ($result as $key => $value) {
            $memberIds[] = $value['id_member'];
        }

        $resultMembers = $em->getRepository('EasyShop\Entities\EsMember')
                        ->findBy(['idMember' => $memberIds]); 

        return $resultMembers;
    }
}

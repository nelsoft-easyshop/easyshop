<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsActivityType as EsActivityType;
use EasyShop\Entities\EsActivityHistory as EsActivityHistory;

class EsActivityHistoryRepository extends EntityRepository
{   
    /**
     * Create and insert log in to table
     * @param  integer $activityType
     * @param  string  $jsonData
     * @param  \EasyShop\Entities\EsMember $member
     * @return \EasyShop\Entities\EsActivityHistory
     */
    public function createAcitivityLog($activityType, $jsonData, $member)
    {   
        $this->em =  $this->_em;
        $activity = new EsActivityHistory();
        $activity->setActivityType($activityType);
        $activity->setJsonData($jsonData);
        $activity->setMember($member);
        $activity->setActivityDatetime(date_create(date("Y-m-d H:i:s")));
        $this->em->persist($activity);
        $this->em->flush();
    }
    
    /**
     * Get user activities
     *
     * @param integer $memberId
     * @param integer $limit
     * @param integer $offset
     * @param date    $fromDate
     * @param date    $toDate
     * @return EasyShop\Entities\EsActivityHistory[]
     */
    public function getActivities($memberId, $limit, $offset, $fromDate = null, $toDate = null, $sortAscending = false)
    {
        $em = $this->_em;
        $query = $em->createQueryBuilder()
                    ->select('A') 
                    ->from('EasyShop\Entities\EsActivityHistory','A')
                    ->where('A.member = :memberId')
                    ->setParameter("memberId", $memberId);

        if($fromDate !== null && $toDate !== null){
            $query->andWhere('A.activityDatetime BETWEEN :dateFrom AND :dateTo')
                  ->setParameter("dateFrom", $fromDate)
                  ->setParameter("dateTo", $toDate);
        }

        $activities = $query->setFirstResult( $offset )
                            ->setMaxResults( $limit )
                            ->orderBy('A.idActivityHistory', $sortAscending ? "ASC" : "DESC")
                            ->getQuery()
                            ->getResult();
 
        return $activities;
    }

    /**
     * Count all user activity per member
     * @param integer $memberId
     * @param date    $fromDate
     * @param date    $toDate
     * @return integer
     */
    public function countActivityCount($memberId, $fromDate = null, $toDate = null)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('count', 'count');

        $sql = " 
          SELECT COUNT(*) as count
          FROM es_activity_history
          WHERE member_id = :memberId
        ";

        if($fromDate !== null && $toDate !== null){
            $sql .= " AND activity_datetime BETWEEN :dateFrom AND :dateTo";
        }
        
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('memberId', $memberId);
        if($fromDate !== null && $toDate !== null){
            $query->setParameter("dateFrom", $fromDate);
            $query->setParameter("dateTo", $toDate);
        }
        $result = $query->getOneOrNullResult();

        return $result['count'];
    }
}

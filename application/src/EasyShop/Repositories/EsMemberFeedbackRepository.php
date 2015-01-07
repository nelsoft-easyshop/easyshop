<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use DateTime;
use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsMemberFeedback as EsMemberFeedback;

class EsMemberFeedbackRepository extends EntityRepository
{
    /**
     * Get all feedbacks of a member
     *
     * @param integer $memberId
     * @return mixed
     */
    public function getAllFeedback($memberId)
    {
        $em =  $this->_em;
        $queryBuilder = $em->createQueryBuilder();

        $feedbacks = $queryBuilder->select('reviewer.idMember as reviewerId, 
                                            reviewer.username as reviewerUsername, 
                                            reviewer.slug as reviewerSlug, 
                                            reviewee.idMember as revieweeId, 
                                            reviewee.username as revieweeUsername, 
                                            reviewee.slug as revieweeSlug, 
                                            fb.feedbMsg,
                                            fb.dateadded,
                                            fb.rating1,
                                            fb.rating2,
                                            fb.rating3,
                                            fb.feedbKind,
                                            orderTransaction.idOrder')
                            ->from('EasyShop\Entities\EsMemberFeedback','fb')
                            ->leftJoin('EasyShop\Entities\EsMember','reviewer',
                                                'WITH','reviewer.idMember = fb.member')
                            ->leftJoin('EasyShop\Entities\EsMember','reviewee',
                                                'WITH','reviewee.idMember = fb.forMemberid')
                            ->leftJoin('EasyShop\Entities\EsOrder', 'orderTransaction',
                                                'WITH', 'orderTransaction.idOrder = fb.order')
                            ->where('fb.member = :fromMemberId')
                            ->orWhere('fb.forMemberid = :forMemberId')
                            ->setParameter('fromMemberId', $memberId)
                            ->setParameter('forMemberId', $memberId)
                            ->addOrderBy('fb.dateadded', 'DESC')
                            ->addOrderBy('orderTransaction.idOrder', 'DESC')
                            ->getQuery()
                            ->getResult();

        return $feedbacks;
    }

    /**
     * Get all feedback of member by giving its type
     * @param  integer $memberId
     * @param  integer $feedType
     * @param  integer $limit
     * @param  integer $page
     * @return mixed
     */
    public function getUserFeedbackByType($memberId, $feedType, $limit, $page = 0)
    {
        $em =  $this->_em;
        $queryBuilder = $em->createQueryBuilder();

        $queryBuilder = $queryBuilder->select("reviewer.idMember as reviewerId, 
                                            COALESCE(NULLIF(reviewer.storeName, ''), reviewer.username) AS reviewerUsername, 
                                            reviewer.slug as reviewerSlug, 
                                            reviewee.idMember as revieweeId,  
                                            COALESCE(NULLIF(reviewee.storeName, ''), reviewee.username) AS revieweeUsername, 
                                            reviewee.slug as revieweeSlug, 
                                            fb.feedbMsg,
                                            fb.dateadded,
                                            fb.rating1,
                                            fb.rating2,
                                            fb.rating3,
                                            fb.feedbKind,
                                            orderTransaction.idOrder")
                            ->from('EasyShop\Entities\EsMemberFeedback','fb')
                            ->leftJoin('EasyShop\Entities\EsMember','reviewer',
                                                'WITH','reviewer.idMember = fb.member')
                            ->leftJoin('EasyShop\Entities\EsMember','reviewee',
                                                'WITH','reviewee.idMember = fb.forMemberid')
                            ->leftJoin('EasyShop\Entities\EsOrder', 'orderTransaction',
                                                'WITH', 'orderTransaction.idOrder = fb.order')
                            ->orderBy('orderTransaction.idOrder', 'DESC')
                            ->addOrderBy('fb.dateadded', 'DESC')
                            ->setFirstResult($page * $limit)
                            ->setMaxResults($limit);

       switch($feedType){
            case EsMemberFeedback::TYPE_AS_SELLER: 
                $queryBuilder = $queryBuilder->where('fb.forMemberid = :forMemberid')
                                             ->andWhere('fb.feedbKind = 0')
                                             ->setParameter('forMemberid', $memberId);
                break;
            case EsMemberFeedback::TYPE_AS_BUYER: 
                $queryBuilder = $queryBuilder->where('fb.forMemberid = :forMemberid')
                                             ->andWhere('fb.feedbKind = 1')
                                             ->setParameter('forMemberid', $memberId);
                break;
            case EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER: 
                $queryBuilder = $queryBuilder->where('fb.member = :forMemberid')
                                             ->andWhere('fb.feedbKind = 1')
                                             ->setParameter('forMemberid', $memberId);
                break;
            case EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER: 
                $queryBuilder = $queryBuilder->where('fb.member = :forMemberid')
                                             ->andWhere('fb.feedbKind = 0')
                                             ->setParameter('forMemberid', $memberId);
                break;
            default:
                $queryBuilder = $queryBuilder->where('fb.member = :fromMemberId')
                                             ->orWhere('fb.forMemberid = :forMemberId')
                                             ->setParameter('fromMemberId', $memberId)
                                             ->setParameter('forMemberId', $memberId);
                break;
        }

        $feedbacks = $queryBuilder->getQuery()
                                  ->getResult();

        return $feedbacks;
    }

    /**
     * Get total count of feedback per user
     * @param  integer $memberId
     * @param  boolean $all
     * @return integer
     */
    public function getUserTotalFeedBackCount($memberId, $includeOwnFeedback = true)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('count', 'count');

        $sql = "
            SELECT 
                COUNT(id_feedback) as count
            FROM
                es_member_feedback
            WHERE
                for_memberid = :member_id
        ";

        if($includeOwnFeedback){
            $sql .= " OR member_id = :member_id";
        }
        
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('member_id', $memberId); 
        $result = $query->getOneOrNullResult();

        return (int) $result['count'];
    }

    /**
     * Get User average ratings
     * @param  integer $memberId
     * @return array
     */
    public function getUserFeedbackAverageRating($memberId)
    {
        $this->em = $this->_em;
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('rating1', 'rating1');
        $rsm->addScalarResult('rating2', 'rating2');
        $rsm->addScalarResult('rating3', 'rating3');

        $sql = "
            SELECT 
                ROUND(AVG(rating1)) as rating1,
                ROUND(AVG(rating2)) as rating2,
                ROUND(AVG(rating3)) as rating3
            FROM
                es_member_feedback
            WHERE
                for_memberid = :member_id
        ";
        
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('member_id', $memberId); 
        $result = $query->getOneOrNullResult();

        return $result;
    }
    
    /**
     * Get the feedbacks for others as a buyer
     *
     * @param integer $memberId
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function getFeedbacksForOthersAsBuyer($memberId, $limit = 15, $page = 0)
    {
        $em =  $this->_em;
        $queryBuilder = $em->createQueryBuilder();
        
        $feedbacks = $queryBuilder->select('reviewee.idMember as userId, 
                                            reviewee.username as username, 
                                            reviewee.slug as userslug,
                                            fb.feedbMsg,
                                            fb.dateadded,
                                            fb.rating1,
                                            fb.rating2,
                                            fb.rating3,
                                            orderTransaction.idOrder')
                                  ->from('EasyShop\Entities\EsMemberFeedback','fb')
                                  ->leftJoin('EasyShop\Entities\EsMember','reviewee',
                                                      'WITH','reviewee.idMember = fb.forMemberid')
                                  ->leftJoin('EasyShop\Entities\EsOrder', 'orderTransaction',
                                                      'WITH', 'orderTransaction.idOrder = fb.order')
                                  ->where('fb.member = :fromMemberId')
                                  ->andWhere('fb.feedbKind = 0')
                                  ->setParameter('fromMemberId', $memberId)
                                  ->addOrderBy('fb.dateadded', 'DESC')
                                  ->addOrderBy('orderTransaction.idOrder', 'DESC') 
                                  ->setFirstResult($page * $limit)
                                  ->setMaxResults($limit)
                                  ->getQuery()
                                  ->getResult();
        return $feedbacks;
    }
    
    /**
     * Get the feedbacks for others as a seller
     *
     * @param integer $memberId
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function getFeedbacksForOthersAsSeller($memberId, $limit = 15, $page = 0)
    {
        $em =  $this->_em;
        $queryBuilder = $em->createQueryBuilder();

        $feedbacks = $queryBuilder->select('reviewee.idMember as userId, 
                                            reviewee.username as username, 
                                            reviewee.slug as userslug,
                                            fb.feedbMsg,
                                            fb.dateadded,
                                            fb.rating1,
                                            fb.rating2,
                                            fb.rating3,
                                            orderTransaction.idOrder')
                                  ->from('EasyShop\Entities\EsMemberFeedback','fb')
                                  ->leftJoin('EasyShop\Entities\EsMember','reviewee',
                                                      'WITH','reviewee.idMember = fb.forMemberid')
                                  ->leftJoin('EasyShop\Entities\EsOrder', 'orderTransaction',
                                                      'WITH', 'orderTransaction.idOrder = fb.order')
                                  ->where('fb.member = :fromMemberId')
                                  ->andWhere('fb.feedbKind = 1')
                                  ->setParameter('fromMemberId', $memberId)
                                  ->addOrderBy('fb.dateadded', 'DESC')
                                  ->addOrderBy('orderTransaction.idOrder', 'DESC') 
                                  ->setFirstResult($page * $limit)
                                  ->setMaxResults($limit)
                                  ->getQuery()
                                  ->getResult();
        return $feedbacks;
    }
    
    
    /**
     * Get the feedbacks from others as a buyer
     *
     * @param integer $memberId
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function getFeedbacksAsBuyer($memberId, $limit = 15, $page = 0)
    {
        $em =  $this->_em;
        $queryBuilder = $em->createQueryBuilder();

        $feedbacks = $queryBuilder->select('reviewer.idMember as userId, 
                                            reviewer.username as username, 
                                            reviewer.slug as userslug,
                                            fb.feedbMsg,
                                            fb.dateadded,
                                            fb.rating1,
                                            fb.rating2,
                                            fb.rating3,
                                            orderTransaction.idOrder')
                                  ->from('EasyShop\Entities\EsMemberFeedback','fb')
                                  ->leftJoin('EasyShop\Entities\EsMember','reviewer',
                                                      'WITH','reviewer.idMember = fb.member')
                                  ->leftJoin('EasyShop\Entities\EsOrder', 'orderTransaction',
                                                      'WITH', 'orderTransaction.idOrder = fb.order')
                                  ->where('fb.forMemberid = :forMemberid')
                                  ->andWhere('fb.feedbKind = 1')
                                  ->setParameter('forMemberid', $memberId)
                                  ->orderBy('fb.dateadded', 'DESC')
                                  ->addOrderBy('orderTransaction.idOrder', 'DESC') 
                                  ->setFirstResult($page * $limit)
                                  ->setMaxResults($limit)
                                  ->getQuery()
                                  ->getResult();
        return $feedbacks;
    }
    
    
    /**
     * Get the feedbacks from others as a seller
     *
     * @param integer $memberId
     * @param integer $page
     * @param integer $limit
     * @return mixed
     */
    public function getFeedbacksAsSeller($memberId, $limit = 15, $page = 0)
    {
        $em =  $this->_em;
        $queryBuilder = $em->createQueryBuilder();

        $feedbacks = $queryBuilder->select('reviewer.idMember as userId, 
                                            reviewer.username as username, 
                                            reviewer.slug as userslug,
                                            fb.feedbMsg,
                                            fb.dateadded,
                                            fb.rating1,
                                            fb.rating2,
                                            fb.rating3,
                                            orderTransaction.idOrder')
                                  ->from('EasyShop\Entities\EsMemberFeedback','fb')
                                  ->leftJoin('EasyShop\Entities\EsMember','reviewer',
                                                      'WITH','reviewer.idMember = fb.member')
                                  ->leftJoin('EasyShop\Entities\EsOrder', 'orderTransaction',
                                                      'WITH', 'orderTransaction.idOrder = fb.order')
                                  ->where('fb.forMemberid = :forMemberid')
                                  ->andWhere('fb.feedbKind = 0')
                                  ->setParameter('forMemberid', $memberId)
                                  ->orderBy('fb.dateadded', 'DESC')
                                  ->addOrderBy('orderTransaction.idOrder', 'DESC')
                                  ->setFirstResult($page * $limit)
                                  ->setMaxResults($limit)
                                  ->getQuery()
                                  ->getResult();
        return $feedbacks;
    }

    /**
     * Add new Feedback
     * @param $memberId
     * @param $forMemberId
     * @param $feedbackMessage
     * @param $feedbackKind
     * @param $orderId
     * @param $rating1
     * @param $rating2
     * @param $rating3
     * @return EsMemberFeedback
     */
    public function addFeedback($memberId, $forMemberId, $feedbackMessage, $feedbackKind, $orderId, $rating1, $rating2, $rating3)
    {
        $esMemberFeedback = new EsMemberFeedback();
        $esMemberFeedback->setMember($memberId);
        $esMemberFeedback->setForMemberid($forMemberId);
        $esMemberFeedback->setFeedbMsg($feedbackMessage);
        $esMemberFeedback->setFeedbKind($feedbackKind);
        $esMemberFeedback->setOrder($orderId);
        $esMemberFeedback->setRating1($rating1);
        $esMemberFeedback->setRating2($rating2);
        $esMemberFeedback->setRating3($rating3);
        $esMemberFeedback->setDateadded(new DateTime('now'));
        $this->_em->persist($esMemberFeedback);
        $this->_em->flush();

        return $esMemberFeedback;
    }
}


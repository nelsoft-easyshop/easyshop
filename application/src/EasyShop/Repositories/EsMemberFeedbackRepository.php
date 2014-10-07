<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository; 

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
                                            reviewee.idMember as revieweeId, 
                                            reviewee.username as revieweeUsername, 
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
                            ->addOrderBy('fb.dateadded', 'DESC')
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
                            ->addOrderBy('fb.dateadded', 'DESC')
                            ->addOrderBy('orderTransaction.idOrder', 'DESC')                           
                            ->setFirstResult($page * $limit)
                            ->setMaxResults($limit)
                            ->getQuery()
                            ->getResult();
        return $feedbacks;
    }
    
    
    

}


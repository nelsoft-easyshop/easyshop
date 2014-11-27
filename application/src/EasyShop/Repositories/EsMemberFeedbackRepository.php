<?php

namespace EasyShop\Repositories;

use Doctrine\ORM\EntityRepository;
use EasyShop\Entities\EsMemberFeedback;
use DateTime;

class EsMemberFeedbackRepository extends EntityRepository
{

    /**
     * Returns the average ratings of a user
     *
     * @param integer $userId
     * @return mixed
     */
    public function getAverageRatings($userId)
    {
        $ratings = $this->_em->getRepository('EasyShop\Entities\EsMemberFeedback')
                             ->findBy(['forMemberid' => $userId]);

        $averageRatings = array(
            'count' => 0,
            'rating1' => 0,
            'rating2' => 0,
            'rating3' => 0,
        );

        foreach($ratings as $rating){
            $averageRatings['count']++;
            $averageRatings['rating1'] += intval($rating->getRating1());
            $averageRatings['rating2'] += intval($rating->getRating2());
            $averageRatings['rating3'] += intval($rating->getRating3());
        }
        if($averageRatings['count'] > 0){
            $averageRatings['rating1'] /= $averageRatings['count'];
            $averageRatings['rating2'] /= $averageRatings['count'];
            $averageRatings['rating3'] /= $averageRatings['count'];
        }

        return $averageRatings;
    }

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


<?php

namespace EasyShop\Review;

use EasyShop\Entities\EsPointType as EsPointType;

/**
 * Search Product Class
 *
 */
class FeedbackTransactionService
{

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Point Tracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    private $pointTracker;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em, $pointTracker)
    {
        $this->em = $em;
        $this->pointTracker = $pointTracker;
    }

    /**
     * Create feedback on transaction
     * @param  EasyShop\Entites\EsMember $member
     * @param  EasyShop\Entites\EsMember $forMemberId
     * @param  string                    $feedbackMessage
     * @param  integer                   $feedbackKind
     * @param  EasyShop\Entites\EsOrder  $order
     * @param  integer                   $rating1
     * @param  integer                   $rating2
     * @param  integer                   $rating3
     * @return EasyShop\Entites\EsMemberFeedback
     */
    public function createTransactionFeedback($member,
                                              $forMember, 
                                              $feedbackMessage, 
                                              $feedbackKind, 
                                              $order, 
                                              $rating1, 
                                              $rating2, 
                                              $rating3)
    {
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        
        $doesFeedbackExists = $esMemberFeedbackRepo->findOneBy([
            'member' => $member,
            'forMemberid' => $forMember,
            'feedbKind' => $feedbackKind,
            'order' => $order
        ]);

        if($doesFeedbackExists === null){
            $newFeedback = $esMemberFeedbackRepo->addFeedback(
                $member,
                $forMember, 
                $feedbackMessage, 
                $feedbackKind, 
                $order, 
                $rating1, 
                $rating2, 
                $rating3
            );

            $this->pointTracker
                 ->addUserPoint($member->getIdMember(), EsPointType::TYPE_TRANSACTION_FEEDBACK);

            return $newFeedback;
        }

        return false;
    }
 
}


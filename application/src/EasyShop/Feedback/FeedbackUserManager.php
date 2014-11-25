<?php

namespace EasyShop\Feedback;

/**
 * Feedback User Manager Class
 */
class FeedbackUserManager
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * User Manager Instance
     *
     * @var Easyshop\User\UserManager
     */
    private $userManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,$userManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * Apply user image in each user in feedback
     * @param  object $feedbacks [description]
     * @return reference
     */
    public function applyUserImage(&$feedbacks)
    {
       foreach ($feedbacks as $key => $feedback) {
            $feedbacks[$key]['revieweeAvatarImage'] = $this->userManager->getUserImage($feedback['revieweeId'], "small");
            $feedbacks[$key]['reviewerAvatarImage'] = $this->userManager->getUserImage($feedback['reviewerId'], "small");
        }
    }
}


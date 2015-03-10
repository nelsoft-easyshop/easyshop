<?php

namespace EasyShop\Review;

use EasyShop\Entities\EsProductReview as EsProductReview;
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
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em)
    {
        $this->em = $em; 
    }

    public function createTransactionFeedback()
    {
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        
    }
 
}


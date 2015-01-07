<?php

namespace EasyShop\Review;

use EasyShop\Entities\EsProductReview as EsProductReview;
/**
 * Search Product Class
 *
 */
class ReviewProductService
{

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * User manager instance
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em, $userManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * Return boolean if the user can review to the specific product
     *
     * @param integer $viewerId
     * @param integer $productId
     * @return boolean
     */
    public function checkIfCanReview($viewerId, $productId)
    {
        $memberEntity = $this->em->getRepository('EasyShop\Entities\EsMember')
                                 ->find($viewerId);

        $productEntity = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                  ->find($productId);

        if($memberEntity && $productEntity){
            $buyCount = $this->em->getRepository("EasyShop\Entities\EsOrderProduct")
                                 ->getProductBuyCountByUser($viewerId,$productId);

            return $buyCount > 0;
        }

        return false;
    }

   /**
     * Return product review of the given product
     *
     * @param integer $productId
     * @return mixed
     */
    public function getProductReview($productId)
    {
        $recentReviews = []; 
        $esProductReviewRepo = $this->em->getRepository('EasyShop\Entities\EsProductReview');
        $productReviews = $esProductReviewRepo->getProductReview($productId);
        if($productReviews){
            $reviewIds = [];
            foreach ($productReviews as $value) {
                $reviewIds[] = $value->getIdReview();
            }

            $productReviewReplies = $esProductReviewRepo->getReviewReplies($productId, $reviewIds);

            foreach($productReviewReplies as $value) {
                $value->setReview(html_escape($value->getReview()));
            }

            $i = 0;
            foreach ($productReviews as $value) { 
                $recentReviews[$i]['id_review'] = $value->getIdReview();
                $recentReviews[$i]['title'] = $value->getTitle();
                $recentReviews[$i]['review'] = $value->getReview();
                $recentReviews[$i]['rating'] = $value->getRating();
                $recentReviews[$i]['datesubmitted'] = $value->getDatesubmitted()->format('Y-m-d H:i:s');
                $recentReviews[$i]['reviewer'] = $value->getMember()->getStoreName();
                $recentReviews[$i]['reviewer_slug'] = $value->getMember()->getSlug();
                $recentReviews[$i]['reviewer_avatar'] = $this->userManager->getUserImage($value->getMember()->getIdMember());

                $recentReviews[$i]['replies'] = [];
                $recentReviews[$i]['reply_count'] = 0;

                foreach($productReviewReplies as $reply){ 
                    if($value->getIdReview() == $reply->getPReviewid()){  
                        $recentReviews[$i]['replies'][] = [
                                                    'id_review' => $reply->getIdReview(),
                                                    'review' => $reply->getReview(),
                                                    'rating' => $reply->getRating(),
                                                    'datesubmitted' => $reply->getDatesubmitted()->format('Y-m-d H:i:s'),
                                                    'reviewer' => $reply->getMember()->getStoreName(),
                                                    'reviewer_slug' => $reply->getMember()->getSlug(),
                                                    'reviewer_avatar' => $this->userManager->getUserImage($reply->getMember()->getIdMember()),
                                                ];
                        $recentReviews[$i]['reply_count']++;
                    }
                }
                $i++;
            } 
        }

        return $recentReviews;
    }

    /**
     * Insert review to database
     * @param  integer $memberId
     * @param  mixed   $inputData
     *
     * $inputData breakdown
     * @var  $inputData['review']
     * @var  $inputData['parent_review']
     * @var  $inputData['product_id']
     * @var  $inputData['rating']
     * @var  $inputData['title']
     * 
     * @return mixed
     */
    public function submitReview($memberId, $inputData)
    {
        $review = trim($inputData['review']);
        $parentReviewId = isset($inputData['parent_review']) ? intval($inputData['parent_review']) : 0;
        $productId = intval($inputData['product_id']); 
        $title = isset($inputData['title']) ? trim($inputData['title']) : ""; 
        $rating = isset($inputData['rating']) ? intval($inputData['rating']) : 0; 
        $dateSubmitted = date_create(date("Y-m-d H:i:s"));

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find($memberId);

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                  ->find($productId);

        $reviewObj = new EsProductReview();
        $reviewObj->setPReviewid($parentReviewId);
        $reviewObj->setMember($member);
        $reviewObj->setDatesubmitted($dateSubmitted);  
        $reviewObj->setDatehidden($dateSubmitted);  
        $reviewObj->setReview($review); 
        $reviewObj->setTitle($title); 
        $reviewObj->setRating($rating); 
        $reviewObj->setProduct($product);

        $this->em->persist($reviewObj);
        $this->em->flush();

        $returnArray = [
                    'datesubmitted' => $dateSubmitted->format('Y-m-d H:i:s'),
                    'reviewUsername' => html_escape($member->getStoreName()),
                    'review' => html_escape($review),
                    'userPic' => $this->userManager->getUserImage($memberId),
                    'title' => $title,
                    'rating' => $rating,
                    'idReview' => $reviewObj->getIdReview(),
                    'canReview' => $this->checkIfCanReview($memberId,$productId),
                ]; 

        return $returnArray;
    }
}


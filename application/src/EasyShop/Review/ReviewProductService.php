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
    public function __construct($em,$userManager)
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
    public function checkIfCanReview($viewerId,$productId)
    {
        $buyCount = $this->em->getRepository("EasyShop\Entities\EsOrderProduct")
                                              ->getProductBuyCountByUser($viewerId,$productId);

        return ($buyCount > 0) ? TRUE : FALSE;
    }

   /**
     * Return product review of the given product
     *
     * @param integer $productId
     * @return mixed
     */
    public function getProductReview($productId)
    {
        $recentReview = []; 
        $productReviewObject = $this->em->getRepository('EasyShop\Entities\EsProductReview')
                                        ->getProductReview($productId);
        if($productReviewObject){
            $retrieve = array();
            foreach ($productReviewObject as $key => $value) {
                $retrieve[] = $value->getIdReview();
            }

            $productRepliesObject = $this->em->getRepository('EasyShop\Entities\EsProductReview')
                                                ->getReviewReplies($productId,$retrieve);

            foreach($productRepliesObject as $key=>$temp){
                $temp->setReview(html_escape($temp->getReview()));
            }

            $i = 0;
            foreach ($productReviewObject as $key => $value) { 
                $recentReview[$i]['id_review'] = $value->getIdReview();
                $recentReview[$i]['title'] = $value->getTitle();
                $recentReview[$i]['review'] = $value->getReview();
                $recentReview[$i]['rating'] = $value->getRating();
                $recentReview[$i]['datesubmitted'] = $value->getDatesubmitted()->format('Y-m-d H:i:s');
                $recentReview[$i]['reviewer'] = $value->getMember()->getUsername();
                $recentReview[$i]['reviewer_avatar'] = $this->userManager->getUserImage($value->getMember()->getIdMember());

                $recentReview[$i]['replies'] = array();
                $recentReview[$i]['reply_count'] = 0;

                foreach($productRepliesObject as $reply){ 
                    if($value->getIdReview() == $reply->getPReviewid()){  
                        $recentReview[$i]['replies'][] = array(
                                                    'id_review' => $reply->getIdReview(),
                                                    'review' => $reply->getReview(),
                                                    'rating' => $reply->getRating(),
                                                    'datesubmitted' => $reply->getDatesubmitted()->format('Y-m-d H:i:s'),
                                                    'reviewer' => $reply->getMember()->getUsername(),
                                                    'reviewer_avatar' => $this->userManager->getUserImage($reply->getMember()->getIdMember()),
                                                );
                        asort($recentReview[$i]['replies']);
                        $recentReview[$i]['reply_count']++;
                    }
                }
                $i++;
            } 
        }

        return $recentReview;
    }

    public function submitReview($memberId,$inputData)
    {

        // get user inputs
        $review = trim($inputData['review']);
        $parentReviewId = (isset($inputData['parent_review'])) ? intval($inputData['parent_review']) : 0;
        $productId = intval($inputData['product_id']); 
        $title = (isset($inputData['title'])) ? trim($inputData['title']) : ""; 
        $rating = (isset($inputData['rating'])) ? intval($inputData['rating']) : 0; 
        $booleanSuccess = FALSE;
        $error = "";

        // find member if exist
        $memberEntity = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find($memberId);

        // find product if exist
        $productEntity = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->find($productId);

        if(strlen(trim($review)) > 0){
            if($memberEntity && $productEntity){

                $dateSubmitted = date_create(date("Y-m-d H:i:s"));

                $reviewObj = new EsProductReview();
                $reviewObj->setPReviewid($parentReviewId);
                $reviewObj->setMember($memberEntity);
                $reviewObj->setDatesubmitted($dateSubmitted);  
                $reviewObj->setDatehidden($dateSubmitted);  
                $reviewObj->setReview($review); 
                $reviewObj->setTitle($title); 
                $reviewObj->setRating($rating); 
                $reviewObj->setProduct($productEntity);

                $this->em->persist($reviewObj);
                $this->em->flush();

                $booleanSuccess = TRUE;

                $returnArray = array(
                            'isSuccess'=>$booleanSuccess,
                            'error' => $error,
                            'datesubmitted' => $dateSubmitted->format('Y-m-d H:i:s'),
                            'reviewUsername' => html_escape($memberEntity->getUsername()),
                            'review' => html_escape($review),
                            'userPic' => $this->userManager->getUserImage($memberId),
                            'title' => $title,
                            'rating' => $rating,
                            'idReview' => $reviewObj->getIdReview(),
                            'canReview' => $this->checkIfCanReview($memberId,$productId),
                        );
            }
            else{
                $error = "Something went wrong. Please try again later.";
                $returnArray = array(
                            'isSuccess'=>$booleanSuccess,
                            'error' => $error,
                        );
            }
        }
        else{ 
            $error = "Reply cannot be empty!";
            $returnArray = array(
                            'isSuccess'=>$booleanSuccess,
                            'error' => $error,
                        );
        }
        
        return $returnArray;
    }
}
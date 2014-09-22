<?php

namespace EasyShop\User;

use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsMemberFeedback as EsMemberFeedback;

/**
 *  User Manager Class
 *
 *  @author stephenjanz
 */
class UserManager
{

    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Configuration Loader
     *
     */
    private $configLoader;
    
    /**
     *  Constructor. Retrieves Entity Manager instance
     *
     * @param Doctrine\Orm\EntityManager $em
     * @param EasyShop\ConfigLoader\ConfigLoader ConfigLoader
     */
    public function __construct($em, $configLoader)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
    }

    /**
     *  Set member's store name.
     *
     *  @return boolean
     */
    public function setStoreName($memberId, $storeName)
    {
        $user = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $storeName = trim($storeName);
        $objUsedStoreName = array();

        if( strlen($storeName) > 0 ){
            $objUsedStoreName = $this->em->getRepository('EasyShop\Entities\EsMember')
                                       ->getUsedStoreName($memberId,$storeName);
        }
        
        // If store name is not yet used, set user's storename to $storeName
        if( empty($objUsedStoreName) ){
            $user->setStoreName($storeName);
            $this->em->persist($user);
            $this->em->flush();

            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * Returns the formatted feedback
     *
     * @param integer $integer
     * @param integer $type
     */
    public function getFormattedFeedbacks($memberId, $type = EsMemberFeedback::TYPE_ALL)
    {
 
        if($type === EsMemberFeedback::TYPE_ALL){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                              ->getAllFeedback($memberId);
            $data = array(
                'youpost_buyer' => array(),
                'youpost_seller' => array(),
                'otherspost_seller' => array(),
                'otherspost_buyer' => array(),
                'rating1' => 0,
                'rating2' => 0,
                'rating3' => 0,
                'reviewForSellerCount' => 0,
                'totalFeedbackCount' => 0,
            );
    
            $memberId = intval($memberId);
            foreach($feedbacks as $feedback){
                $feedBackKind = intval($feedback['feedbKind']);
                $feedbackDetails = array(
                                        'feedb_msg' => $feedback['feedbMsg'],
                                        'dateadded' => $feedback['dateadded']->format('jS F, Y'),
                                        'rating1' => $feedback['rating1'],
                                        'rating2' => $feedback['rating2'],
                                        'rating3' => $feedback['rating3'],
                                        );
                                                                                                
                if(intval($feedback['reviewerId']) === $memberId){
                    $feedbackDetails['for_memberId'] = $feedback['revieweeId'];
                    $feedbackDetails['for_membername'] = $feedback['revieweeUsername'];
                    if($feedBackKind === 0){
                        $data['youpost_buyer'][$feedback['idOrder']]  = $feedbackDetails;
                    }
                    else if($feedBackKind === 1){
                        $data['youpost_seller'][$feedback['idOrder']] = $feedbackDetails;
                    }
                }
                else if(intval($feedback['revieweeId']) === $memberId){
                    $feedbackDetails['from_memberId'] = $feedback['reviewerId'];
                    $feedbackDetails['from_membername'] = $feedback['reviewerUsername'];
                    $data['rating1'] += $feedback['rating1'];
                    $data['rating2'] += $feedback['rating2'];
                    $data['rating3'] += $feedback['rating3'];
                    $data['reviewForSellerCount']++;
                    if($feedBackKind === 0){
                        $data['otherspost_seller'][$feedback['idOrder']]  = $feedbackDetails;
                    }
                    else if($feedBackKind === 1){
                        $data['otherspost_buyer'][$feedback['idOrder']] = $feedbackDetails;
                    }
                }
                $data['totalFeedbackCount']++;
            }
            if($data['reviewForSellerCount'] !== 0 ){
                $data['rating1Summary'] = round($data['rating1'] / $data['reviewForSellerCount']);
                $data['rating2Summary'] = round($data['rating2'] / $data['reviewForSellerCount']);
                $data['rating3Summary'] = round($data['rating3'] / $data['reviewForSellerCount']);
            }
            $feedbacks = $data;
        }
        else if($type === EsMemberFeedback::TYPE_AS_SELLER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksAsSeller($memberId);
            
        }
        else if($type === EsMemberFeedback::TYPE_AS_BUYER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksAsBuyer($memberId);
        }
        else if($type === EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksForOthersAsSeller($memberId);
        
        }
        else if($type === EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksForOthersAsBuyer($memberId);
        }   
        
        
        if($type !== EsMemberFeedback::TYPE_ALL){
            foreach($feedbacks as $key => $feedback){
                $feedbacks[$key]['userImage'] = $this->getUserImage($feedback['userId'],'small');
            }
        }

        return $feedbacks;
    }

        
    /**
     * Returns the image associated with a user
     * 
     * @param integer $memberId
     * @param string $selector
     */
    public function getUserImage($memberId, $selector = NULL)
    {
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->find($memberId);
        $defaultImagePath = $this->configLoader->getItem('image_path','user_img_directory');                
        $imageURL = $member->getImgurl();
        switch($selector){
            case "banner":
                $imgFile = '/banner.png';
                break;
            case "small":
                $imgFile = '/60x60.png';
                break;
            case NULL:
            default:
                $imgFile = '/150x150.png';
                break;
        }
                
        if(!file_exists($imageURL.$imgFile)||(trim($imageURL) === '')){
            $user_image = '/'.$defaultImagePath.'default'.$imgFile.'?ver='.time();
        }
        else{
            $user_image = '/'.$imageURL.$imgFile.'?'.time();
        }
        
        return $user_image;
    }

    
}

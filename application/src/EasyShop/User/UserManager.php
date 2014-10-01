<?php

namespace EasyShop\User;

use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsAddress;
use EasyShop\Entities\EsLocationLookup;
use EasyShop\Entities\EsMemberFeedback as EsMemberFeedback;
use EasyShop\Entities\EsVendorSubscribe;

/**
 *  User Manager Class
 *  Manage everything specific to a user
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
     *  Member id
     */
    private $memberId;

    /**
     *  Member entity
     *
     *  @var EasyShop\Entities\EsMember
     */
    private $memberEntity;

    /**
     *  Check if method chain returns true.
     *  Checked by magic function __call(), for all private functions
     *
     *  @var boolean
     */
    private $valid;

    /**
     *  Container for error encountered by method chain.
     *
     *  @var string
     */
    private $err;

    /**
     * Configuration Loader
     *
     * @var Object
     */
    private $configLoader;

    /**
     * Form Validation
     * @var [type]
     */
    private $formValidation;

    /**
     * Form Factory service
     * @var [type]
     */
    private $formFactory;

    /**
     * Form error helper
     * @var [type]
     */
    private $formErrorHelper;
    
    /**
     *  Constructor. Retrieves Entity Manager instance
     *
     * @param Doctrine\Orm\EntityManager $em
     * @param EasyShop\ConfigLoader\ConfigLoader ConfigLoader
     */
    public function __construct($em,$configLoader,$formValidation,$formFactory,$formErrorHelper)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->valid = true;
        $this->formValidation = $formValidation;
        $this->formFactory = $formFactory;
        $this->formErrorHelper = $formErrorHelper;
    }

    /**
     *  Magic function. Called when accessing private functions from outside class.
     */
    public function __call($name, $args)
    {
        if($this->valid){
            $this->valid = call_user_func_array(array($this,$name), $args);
        }
        return $this;
    }

    /**
     *  Displays error encountered by method chain.
     */
    public function errorInfo()
    {
        return $this->err;
    }

    /**
     *  Print desired info in this function.
     */
    public function showDetails()
    {
        print("Member ID: ". $this->memberId . "<br>");
    }

    /**
     *  REQUIRED! Initializes user to work on
     *
     *  @return boolean
     */
    private function setUser($memberId)
    {
        $memberEntity = $this->em->find('EasyShop\Entities\EsMember', $memberId);

        if( $memberEntity !== null ){
            $this->memberId = $memberId;
            $this->memberEntity = $memberEntity;
            return true;
        }
        else{
            $this->err = "User does not exist.";
            return false;
        }
    }

    /**
     *  Set personal mobile in es_member table
     *
     *  @return boolean
     */
    private function setMobile($mobileNum)
    {
        $isValidMobile = $this->isValidMobile($mobileNum);

        $thisMember = array();

        if( $isValidMobile || $mobileNum === "" ){
            if( $mobileNum !== "" ){
                $thisMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                                    ->getUserExistingMobile($this->memberId, $mobileNum);
            }

            // If mobile not used
            if( empty($thisMember) ){
                $this->memberEntity->setContactno($mobileNum);
                $this->em->persist($this->memberEntity);
                return true;
            }
            else{
                $this->err = "Mobile number already used.";
            }
        }
        else{
            $this->err = "Invalid mobile number.";
            
        }

        return false;
    }

    /**
     *  Set storename in es_member table
     *
     *  @return boolean
     */
    private function setStoreName($storeName)
    {
        $storeName = trim($storeName);
        $objUsedStoreName = array();

        if( strlen($storeName) > 0 ){
            $objUsedStoreName = $this->em->getRepository('EasyShop\Entities\EsMember')
                                       ->getUsedStoreName($this->memberId,$storeName);
        }
        
        // If store name is not yet used, set user's storename to $storeName
        if( empty($objUsedStoreName) ){
            $this->memberEntity->setStoreName($storeName);
            $this->em->persist($this->memberEntity);
            return true;
        }
        else{
            $this->err = "Store name already used!";
            return false;
        }
    }

    /**
     *  Set es_address table values
     *
     *  @return boolean
     */
    private function setAddressTable($stateRegionId, $cityId, $strAddress, $type, $lat=0, $lng=0, $consignee="", $mobileNum="", $telephone="", $country=1)
    {
        // Verify location validity
        $locationEntity = $this->em->getRepository("EasyShop\Entities\EsLocationLookup")
                                    ->verifyLocationCombination($stateRegionId, $cityId);
        $isValidLocation = !empty($locationEntity);
        
        $isValidMobile = $this->isValidMobile($mobileNum);
        if( !$isValidMobile && $mobileNum !== "" ){
            $this->err = "Invalid mobile number.";
            return false;            
        }

        if( $isValidLocation ){
            $arrAddressEntity = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                    ->getAddressDetails($this->memberId, $type);
            
            if( !empty($arrAddressEntity) ){
                $address = $arrAddressEntity[0];
            }
            else{
                $address = new EsAddress();
                $address->setIdMember($this->memberEntity);
            }                

            $stateRegionEntity = $this->em->find('EasyShop\Entities\EsLocationLookup', $stateRegionId);
            $cityEntity = $this->em->find('EasyShop\Entities\EsLocationLookup', $cityId);
            $countryEntity = $this->em->find('EasyShop\Entities\EsLocationLookup', $country);
            
            $address->setStateregion($stateRegionEntity)
                    ->setCity($cityEntity)
                    ->setAddress($strAddress)
                    ->setType($type)
                    ->setLat($lat)
                    ->setLng($lng)
                    ->setConsignee($consignee)
                    ->setMobile($mobileNum)
                    ->setTelephone($telephone)
                    ->setCountry($countryEntity);

            $this->em->persist($address);

            return true;
        }
        else{
            $this->err = "Invalid location combination";
        }

        return false;
    }

    /**
     *  Flush all persisted entities set above.
     */
    public function save()
    {
        $this->em->flush();

        return $this->valid;
    }

    /****************** UTILITY FUNCTIONS *******************/

    /**
     *  Used to check if mobile format is valid. 
     *  Prepares mobile for database input if format is valid
     *
     *  @return boolean
     */
    private function isValidMobile(&$mobileNum)
    {
        $isValidMobile = preg_match('/^(08|09)[0-9]{9}$/', $mobileNum);

        if($isValidMobile){
            $mobileNum = ltrim($mobileNum,"0");
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
    public function getFormattedFeedbacks($memberId, $type = EsMemberFeedback::TYPE_ALL, $limit = PHP_INT_MAX, $page = 1)
    {
        $page--;
        $page = ($page < 0) ? 0 : $page;
                
        if($type === EsMemberFeedback::TYPE_ALL){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                              ->getAllFeedback($memberId);
            $data = array(
                'youpost_buyer' => array(),
                'youpost_seller' => array(),
                'otherspost_seller' => array(),
                'otherspost_buyer' => array(),
                'rating1Summary' => 0,
                'rating2Summary' => 0,
                'rating3Summary' => 0,
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
                    $data['rating1Summary'] += $feedback['rating1'];
                    $data['rating2Summary'] += $feedback['rating2'];
                    $data['rating3Summary'] += $feedback['rating3'];
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
                $data['rating1Summary'] = round($data['rating1Summary'] / $data['reviewForSellerCount']);
                $data['rating2Summary'] = round($data['rating2Summary'] / $data['reviewForSellerCount']);
                $data['rating3Summary'] = round($data['rating3Summary'] / $data['reviewForSellerCount']);
            }
            $feedbacks = $data;
        }
        else if($type === EsMemberFeedback::TYPE_AS_SELLER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksAsSeller($memberId, $limit, $page);
            
        }
        else if($type === EsMemberFeedback::TYPE_AS_BUYER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksAsBuyer($memberId, $limit, $page);
        }
        else if($type === EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksForOthersAsSeller($memberId, $limit, $page);
        
        }
        else if($type === EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER){
            $feedbacks = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback')
                                  ->getFeedbacksForOthersAsBuyer($memberId, $limit, $page);
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
            default:
                $imgFile = '/150x150.png';
                break;
        }
                
        if(!file_exists($imageURL.$imgFile)){
            $user_image = '/'.$defaultImagePath.'default'.$imgFile.'?ver='.time();
        }
        else{
            $user_image = '/'.$imageURL.$imgFile.'?'.time();
        }
        
        return $user_image;
    }

    /**
     * Remove user image and back to default image of easyshop
     *
     * @param integer $memberId
     * @return boolean
     */
    public function removeUserImage($memberId)
    {
        // Get member object
        $EsMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(['idMember' => $memberId]);

        if($EsMember !== null){
            // Update user image
            $EsMember->setImgurl(""); 
            $this->em->flush();

            return $this->getUserImage($memberId);
        }
        else{
            return false;
        }
    }

    /**
     *  Check if user is subscribed to vendor
     *
     *  @return string
     */
    public function getVendorSubscriptionStatus($memberId, $sellername)
    {
        $vendorEntity = $this->em->getRepository("EasyShop\Entities\EsMember")
                                ->findOneBy(array("username"=>$sellername));

        if(empty($vendorEntity)){
            return false;
        }

        $subscriptionEntity = $this->em->getRepository("EasyShop\Entities\EsVendorSubscribe")
                                        ->findOneBy(array("memberId" => $memberId, "vendorId" => $vendorEntity->getIdMember()));

        if(!empty($subscriptionEntity)){
            return "followed";
        }
        else{
            return "unfollowed";
        }
    }

    /**
     *  Insert entry into es_vendor_subscribe
     *
     *  @return boolean
     */
    public function subscribeToVendor($memberId, $sellername)
    {
        $memberEntity = $this->em->find("EasyShop\Entities\EsMember", $memberId);
        $vendorEntity = $this->em->getRepository("EasyShop\Entities\EsMember")
                                ->findOneBy(array("username"=>$sellername));

        if(empty($memberEntity) || empty($vendorEntity) ){
            return false;
        }

        $subscriptionEntity = new EsVendorSubscribe();
        $subscriptionEntity->setMemberId($memberId)
                            ->setVendorId($vendorEntity->getIdMember());
        $this->em->persist($subscriptionEntity);
        $this->em->flush();

        return true;
    }

    /**
     *  Delete entry in es_vendor_subscribe
     *
     *  @return boolean
     */
    public function unsubscribeToVendor($memberId, $sellername)
    {
        $vendorEntity = $this->em->getRepository("EasyShop\Entities\EsMember")
                                ->findOneBy(array("username"=>$sellername));

        $subscriptionEntity = $this->em->getRepository("EasyShop\Entities\EsVendorSubscribe")
                                        ->findOneBy(array(
                                                    "memberId"=>$memberId
                                                    ,"vendorId"=>$vendorEntity->getIdMember()
                                                ));
        if(!empty($subscriptionEntity)){
            $this->em->remove($subscriptionEntity);
            $this->em->flush();
        }

        return true;
    }

    /**
     * Update or insert address of the user
     * @param string   $streetAddress   [description]
     * @param integer  $region          [description]
     * @param integer  $city            [description]
     * @param integer  $memberId        [description]
     * @param integer  $type            [description]
     * @param string   $consignee       [description]
     * @param string   $mobileNumber    [description]
     * @param string   $telephoneNumber [description]
     * @param interger $country
     */
    public function setAddress($streetAddress,$region,$city,$memberId,$type=0,$consignee="",$mobileNumber="",$telephoneNumber = "",$country = 1)
    { 
        $formValidation = $this->formValidation; 
        $formFactory = $this->formFactory;
        $rules = $formValidation->getRules('user_shipping_address'); 
        $data['isSuccessful'] = false;

        if(intval($type)===EsAddress::TYPE_DELIVERY){

            $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                ->setMethod('POST')
                                ->add('consignee', 'text', array('constraints' => $rules['consignee']))
                                ->add('mobile_number', 'text', array('constraints' => $rules['mobile_number']))
                                ->add('telephone_number', 'text')
                                ->add('street_address', 'text', array('constraints' => $rules['street_address']))
                                ->add('region', 'text', array('constraints' => $rules['region'])) 
                                ->add('city', 'text', array('constraints' => $rules['city']))
                                ->getForm();

            $form->submit([ 
                'consignee' => $consignee,
                'mobile_number' => $mobileNumber,
                'telephone_number' => $streetAddress,
                'street_address' => $streetAddress,
                'region' => $region,
                'city' => $city,
            ]);

            $data['errors'] = [];
            if($form->isValid()){

                $addressEntity = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                            ->findOneBy([
                                                'idMember' => $memberId, 
                                                'type' => EsAddress::TYPE_DELIVERY
                                            ]);

                $memberIdObject = $this->em->getRepository('EasyShop\Entities\EsMember')
                                            ->find($memberId);

                $stateRegionObject = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                            ->find($region);

                $cityObject = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                            ->find($city);

                $countryObject = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                            ->find($country);

                // Update existing shipping address of the user 
                if( $addressEntity !== null ){
                    $esAddress = $addressEntity; 
                }
                // Insert shipping address to database
                else{
                    $esAddress = new EsAddress();
                }
                    $esAddress->setAddress($streetAddress);
                    $esAddress->setCountry($countryObject);
                    $esAddress->setStateregion($stateRegionObject);
                    $esAddress->setCity($cityObject);
                    $esAddress->setIdMember($memberIdObject);
                    $esAddress->setType(EsAddress::TYPE_DELIVERY);
                    $esAddress->setConsignee($consignee);
                    $esAddress->setMobile(substr($mobileNumber,1));
                    $esAddress->setTelephone($telephoneNumber);
                    $this->em->persist($esAddress);
                    $this->em->flush();

                $data['isSuccessful'] = true;
            }
            else{
                 $data['errors'] = $this->formErrorHelper->getFormErrors($form);
            }
        }

        $mobileErrors = [];
        $errCounter = 0;
        foreach ($data['errors'] as $key => $value) {
            $mobileErrors[$errCounter]['type'] = $key;
            $mobileErrors[$errCounter]['message'] = $value;
            $errCounter++;
        }
        $data['mobile_errors'] = $mobileErrors;
        
        return $data;
    }

}

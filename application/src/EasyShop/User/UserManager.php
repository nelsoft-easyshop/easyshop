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
use EasyShop\Entities\EsVendorSubscribeHistory as EsVendorSubscribeHistory;

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
     *  Flag for calling EntityManager::flush() in function save()
     *
     *  @var boolean
     */
    private $hasError;

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
     * String utility object
     *
     * @var EasyShop\Utility\StringUtility
     */
    private $stringUtility;
    
    /**
     * Array of reserved keywords that cannot be used for the slug
     *
     * @var string[]
     */
    private $reservedSlugs;

    /**
     * Environment Variable
     *
     * @var string
     */
    private $environment;

    /**
     *  Constructor. Retrieves Entity Manager instance
     *
     * @param Doctrine\Orm\EntityManager $em
     * @param EasyShop\ConfigLoader\ConfigLoader $ConfigLoader
     * @param EasyShop\Utility\StringUtility $stringUtility
     */
    public function __construct($em, 
                                $configLoader,
                                $formValidation,
                                $formFactory,
                                $formErrorHelper, 
                                $stringUtility,
                                $reservedSlugs = [],
                                $environment)
    {
        $this->em = $em;
        $this->configLoader = $configLoader;
        $this->hasError = FALSE;
        $this->err = array();
        $this->formFactory = $formFactory;
        $this->formValidation = $formValidation;
        $this->formErrorHelper = $formErrorHelper;
        $this->stringUtility = $stringUtility;
        $this->reservedSlugs = $reservedSlugs;
        $this->environment = $environment;
    }

    /**
     *  Displays error encountered by method chain.
     */
    public function errorInfo()
    {
        return $this->err;
    }

    /**
     *  REQUIRED! Initializes user to work on
     *
     *  @return object
     */
    public function setUser($memberId)
    {
        $memberEntity = $this->em->find('EasyShop\Entities\EsMember', $memberId);

        if( $memberEntity !== null ){
            $this->memberId = $memberId;
            $this->memberEntity = $memberEntity;
        }
        else{
            $this->err['user'] = "User does not exist.";
            $this->hasError = TRUE;
        }

        return $this;
    }

    /**
     *  Set personal mobile in es_member table
     *
     *  @return object
     */
    public function setMobile($mobileNum)
    {
        $mobileNum = ltrim($mobileNum, "0");
        $thisMember = array();

        if( $mobileNum !== "" ){
            $thisMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->getUserExistingMobile($this->memberId, $mobileNum);
        }

        // If mobile not used
        if( empty($thisMember) ){
            
            $boolContactnoVerify = (string)$this->memberEntity->getContactno() === $mobileNum ? (bool)$this->memberEntity->getIsContactnoVerify() : FALSE;
            
            $this->memberEntity->setContactno($mobileNum);
            $this->memberEntity->setIsContactnoVerify($boolContactnoVerify);
            $this->em->persist($this->memberEntity);
        }
        else{
            $this->err['mobile'] = "Mobile number already used.";
            $this->hasError = TRUE;
        }

        return $this;
    }

    /**
     *  Set personal email in es_member
     *  Checks if user is allowed to change email
     *  Checks if email has already been used by another user
     *
     *  @return object
     */
    public function setEmail($email)
    {
        $thisMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->getUserExistingEmail($this->memberId, $email);

        $authenticationId = $this->memberEntity->getOauthId();
        $authenticationProvider = $this->memberEntity->getOauthProvider();
        $oldEmail = $this->memberEntity->getEmail();

        if( $email !== $oldEmail && ( $authenticationId !== "0" || strlen($authenticationProvider) > 0 ) ){
            $this->err['email'] = "Change of email not permitted for this user";
            $this->hasError = TRUE;

            return $this;
        }

        if(empty($thisMember)){

            $boolEmailVerify = (string)$this->memberEntity->getEmail() === (string)$email ? (bool)$this->memberEntity->getIsEmailVerify() : FALSE;

            $this->memberEntity->setEmail($email);
            $this->memberEntity->setIsEmailVerify($boolEmailVerify);
            $this->em->persist($this->memberEntity);
        }
        else{
            $this->err['email'] = "Email already used.";
            $this->hasError = TRUE;
        }

        return $this;
    }

    /**
     *  Set storename in es_member table
     *
     *  @return object
     */
    public function setStoreName($storeName)
    {
        $storeName = trim($storeName);
        $isSuccessful = $this->updateStorename($this->memberEntity, $storeName, false);
        if(!$isSuccessful){
            $this->err['storename'] = "Store name already used!";
            $this->hasError = TRUE;
        }

        return $this;
    }


    /**
     *  Set misc table values for es_member
     *
     *  @param array $array - array('EntityFunctionName' => 'value')
     *
     *  @return object
     */
    public function setMemberMisc($array)
    {
        foreach($array as $function=>$value){
            $this->memberEntity->$function($value);
        }
        $this->em->persist($this->memberEntity);

        return $this;
    }

    /**
     *  Set es_address table values
     *
     *  @return object
     */
    public function setAddressTable($stateRegionId, $cityId, $strAddress, $type, $lat=0, $lng=0, $consignee="", $mobileNum="", $telephone="", $country=1)
    {
        $mobileNum = ltrim($mobileNum, "0");

        // Verify location validity
        $locationEntity = $this->em->getRepository("EasyShop\Entities\EsLocationLookup")
                                    ->verifyLocationCombination($stateRegionId, $cityId);
        $isValidLocation = !empty($locationEntity);
        
        if( $isValidLocation ){

            $addressEntity = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                      ->findOneBy([
                                            'idMember' => $this->memberId,
                                            'type' => $type
                                        ]);
            
            if( $addressEntity ){
                $address = $addressEntity;
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
        }
        else{
            $this->err['address'] = "Invalid location combination";
            $this->hasError = TRUE;
        }

        return $this;
    }

    public function deleteAddressTable($type)
    {
        $addressEntity = $this->em->getRepository("EasyShop\Entities\EsAddress")
                                ->findOneBy(array(
                                            "idMember"=>$this->memberEntity
                                            , "type" => (string)$type
                                            ));

        if( !empty($addressEntity) ){
            $this->em->remove($addressEntity);
        }

        return $this;
    }

    /**
     *  Flush all persisted entities set above.
     */
    public function save()
    {
        if($this->hasError){
            $this->em->clear();
        }
        else{
            $this->em->flush();
        }
        
        return !$this->hasError;
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
            $data = [
                'youpost_buyer' => [],
                'youpost_seller' => [],
                'otherspost_seller' => [],
                'otherspost_buyer' => [],
                'rating1Summary' => 0,
                'rating2Summary' => 0,
                'rating3Summary' => 0,
                'reviewForSellerCount' => 0,
                'totalFeedbackCount' => 0,
            ];
    
            $memberId = intval($memberId);
            foreach($feedbacks as $feedback){
                $feedBackKind = intval($feedback['feedbKind']);
                $feedbackDetails = [
                    'feedb_msg' => $feedback['feedbMsg'],
                    'dateadded' => $feedback['dateadded']->format('jS F, Y'),
                    'rating1' => $feedback['rating1'],
                    'rating2' => $feedback['rating2'],
                    'rating3' => $feedback['rating3'],
                ];
                                                                                                
                if(intval($feedback['reviewerId']) === $memberId){
                    $feedbackDetails['for_memberId'] = $feedback['revieweeId'];
                    $feedbackDetails['for_membername'] = $feedback['revieweeUsername'];
                    if($feedBackKind === EsMemberFeedback::REVIEWER_AS_BUYER){
                        $data['youpost_buyer'][$feedback['idOrder']]  = $feedbackDetails;
                    }
                    else if($feedBackKind === EsMemberFeedback::REVIEWER_AS_SELLER){
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
                    if($feedBackKind === EsMemberFeedback::REVIEWER_AS_BUYER){
                        $data['otherspost_seller'][$feedback['idOrder']]  = $feedbackDetails;
                    }
                    else if($feedBackKind === EsMemberFeedback::REVIEWER_AS_SELLER){
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
     * @return string
     */
    public function getUserImage($memberId, $selector = NULL)
    {
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->find($memberId);
        switch($selector){
            case "banner":
                $imgFile = '/'.EsMember::DEFAULT_IMG_BANNER;
                $isHide = (boolean) $member->getIsHideBanner();
                break;
            case "small":
                $imgFile = '/'.EsMember::DEFAULT_IMG_SMALL_SIZE;
                $isHide = (boolean) $member->getIsHideAvatar();
                break;
            default:
                $imgFile = '/'.EsMember::DEFAULT_IMG_NORMAL_SIZE;
                $isHide = (boolean) $member->getIsHideAvatar();
                break;
        }
                
        if($isHide){
            $user_image = '/'.EsMember::DEFAULT_IMG_PATH.$imgFile.'?ver='.time();
        }
        else{
            $imageURL = $member->getImgurl();
            if($imageURL === null || $imageURL === ""){
                $user_image = '/'.EsMember::DEFAULT_IMG_PATH.$imgFile.'?ver='.time();
            }
            else{
                $user_image = '/'.$imageURL.$imgFile.'?'.time();                
                if(strtolower($this->environment) === 'development'){
                    if( file_exists($imageURL.$imgFile) === false){
                        $user_image = '/'.EsMember::DEFAULT_IMG_PATH.$imgFile.'?ver='.time();
                    }
                }
            }
        }
        
        return $user_image;
    }

    /**
     * Remove user image and back to default image of easyshop
     *
     * @param integer $memberId
     * @return boolean
     */
    public function removeUserImage($memberId, $selector = NULL)
    {
        // Get member object
        $EsMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(['idMember' => $memberId]);

        if($EsMember !== null){
            switch($selector){
                case "banner":
                    $EsMember->setIsHideBanner(TRUE);
                    $userImage = $this->getUserImage($memberId, "banner");
                    break;
                default:
                    $EsMember->setIsHideAvatar(TRUE);
                    $userImage = $this->getUserImage($memberId);
                    break;
            }
            $this->em->flush();

            return $userImage;
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
        $memberEntity = $this->em->find("EasyShop\Entities\EsMember", $memberId);

        $subscriptionEntity = $this->em->getRepository("EasyShop\Entities\EsVendorSubscribe")
                                        ->findOneBy(array(
                                                        "member" => $memberEntity
                                                        , "vendor" => $vendorEntity
                                                    ));

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

        if(empty($memberEntity) || empty($vendorEntity) || 
            ( (int)$memberEntity->getIdMember()===(int)$vendorEntity->getIdMember() ) ){
            return false;
        }

        $subscriptionEntity = new EsVendorSubscribe();
        $subscriptionEntity->setMember($memberEntity)
                            ->setVendor($vendorEntity)
                            ->setCreateddate(date_create(date("Y-m-d H:i:s", time())));
        $this->em->persist($subscriptionEntity);
        $this->em->flush();

        // Insert to history 
        $subscribeHistory = new EsVendorSubscribeHistory();
        $subscribeHistory->setMember($memberEntity); 
        $subscribeHistory->setVendor($vendorEntity); 
        $subscribeHistory->setAction("FOLLOW"); 
        $subscribeHistory->setTimestamp(date_create(date("Y-m-d H:i:s"))); 
        $this->em->persist($subscribeHistory);
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
        $memberEntity = $this->em->find("EasyShop\Entities\EsMember", $memberId);

        $subscriptionEntity = $this->em->getRepository("EasyShop\Entities\EsVendorSubscribe")
                                        ->findOneBy(array(
                                                    "member"=>$memberEntity
                                                    ,"vendor"=>$vendorEntity
                                                ));
        if(!empty($subscriptionEntity)){
            $this->em->remove($subscriptionEntity);
            $this->em->flush();

            // Insert to history 
            $subscribeHistory = new EsVendorSubscribeHistory();
            $subscribeHistory->setMember($memberEntity); 
            $subscribeHistory->setVendor($vendorEntity); 
            $subscribeHistory->setAction("UNFOLLOW"); 
            $subscribeHistory->setTimestamp(date_create(date("Y-m-d H:i:s"))); 
            $this->em->persist($subscribeHistory);
            $this->em->flush();
        }

        return true;
    }
    
    /**
     * Generate user slug
     *
     * @param $memberId
     * @return EasyShop\Entities\EsMember
     */
    public function generateUserSlug($memberId)
    {
        $member = $this->em->getRepository("EasyShop\Entities\EsMember")
                           ->findOneBy(array("idMember"=>$memberId));
 
        $slug = $this->stringUtility->cleanString($member->getUsername());
        
        $membersWithSlug = $this->em->getRepository("EasyShop\Entities\EsMember")
                                    ->findOneBy(array("slug"=>$slug));
        if($membersWithSlug){
            $slug = $slug.$member->getIdMember();
        }
        
        $member->setSlug($slug);
        $this->em->flush();

        return $member;   
    }

    /**
     * Update or insert address of the user
     * @param string   $streetAddress
     * @param integer  $region
     * @param integer  $city
     * @param integer  $memberId
     * @param integer  $type
     * @param string   $consignee
     * @param string   $mobileNumber
     * @param string   $telephoneNumber
     * @param interger $country
     *
     * @return array
     */
    public function setAddress($streetAddress,
                               $region,
                               $city,
                               $memberId,
                               $consignee="",
                               $mobileNumber="",
                               $telephoneNumber = "", 
                               $lat = EsAddress::DEFAULT_LAT, 
                               $lng = EsAddress::DEFAULT_LNG, 
                               $country = EsLocationLookup::PHILIPPINES_LOCATION_ID, 
                               $type = EsAddress::TYPE_DELIVERY)
    { 
        $formValidation = $this->formValidation; 
        $formFactory = $this->formFactory;
        $rules = $formValidation->getRules('user_shipping_address'); 
        $data['isSuccessful'] = false;
        $lat = trim($lat) === "" ? EsAddress::DEFAULT_LAT : trim($lat);
        $lng = trim($lng) === "" ? EsAddress::DEFAULT_LNG : trim($lng);
        if(intval($type)===EsAddress::TYPE_DELIVERY){
            $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                ->setMethod('POST')
                                ->add('consignee', 'text', array('constraints' => $rules['consignee']))
                                ->add('mobile_number', 'text', array('constraints' => $rules['mobile_number']))
                                ->add('telephone_number', 'text', array('constraints' => $rules['telephone_number']))
                                ->add('street_address', 'text', array('constraints' => $rules['street_address']))
                                ->add('region', 'text', array('constraints' => $rules['region'])) 
                                ->add('city', 'text', array('constraints' => $rules['city']))
                                ->getForm();
                   
            $form->submit([ 
                'consignee' => $consignee,
                'mobile_number' => $mobileNumber,
                'telephone_number' => $telephoneNumber,
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
                    $esAddress->setLat($lat);
                    $esAddress->setLng($lng);
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

    /**
     * Get Profile completeness percentage
     * @param  object $memberEntity
     * @return integer
     */
    public function getProfileCompletePercent($memberEntity)
    {
        $counter = 0;

        if($memberEntity->getFullname() !== ""){
            $counter++;
        }

        if((string)$memberEntity->getGender() !== "0"){
            $counter++;
        }

        if((string)$memberEntity->getBirthday()->format('Y-m-d') !== "0001-01-01"){
            $counter++;
        }

        if((string)$memberEntity->getContactno() !== ""){ 
            $counter++;
        }

        if((boolean)$memberEntity->getIsEmailVerify()){
            $counter++;
        }

        $addressEntity = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                  ->findOneBy([
                                        'idMember' => $memberEntity->getIdMember(), 
                                        'type' => EsAddress::TYPE_DELIVERY
                                  ]);

        if($addressEntity){
            $counter += 4;
        }

        $imageURL = $memberEntity->getImgurl();
        $isHideBanner = (boolean)$memberEntity->getIsHideBanner();
        $isHideAvatar = (boolean)$memberEntity->getIsHideAvatar();

        if(file_exists($imageURL.'/'.EsMember::DEFAULT_IMG_NORMAL_SIZE) && !$isHideAvatar){
            $counter++;
        }

        if(file_exists($imageURL.'/'.EsMember::DEFAULT_IMG_BANNER) && !$isHideBanner){
            $counter++;
        }

        $percentage = ceil($counter/11 * 100);

        return $percentage;
    }

    
    /**
     * Updates the slug of a user
     *
     * @param EasyShop\Entities\EsMember $memberEntity
     * @param string $storeSlug
     * @param string[] $routes
     * @return bool
     */
    public function updateSlug($memberEntity, $storeSlug, $routes)
    {
        if($memberEntity->getIsSlugChanged()){
            return false;
        }
    
        $usersWithSlug = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->getUsersWithSlug($storeSlug, 
                                                   $memberEntity->getIdMember());
        $restrictedRoutes = [];
        foreach( $routes as $userRoute => $appRoute ){
            $userRouteWithoutParentheses = preg_replace('/\(.{2,5}\)/','',$userRoute); 
            $firstSegmentUserRoute = explode("/", $userRouteWithoutParentheses)[0];
            $firstSegmentAppRoute = explode("/", $appRoute)[0];
            if( !in_array($firstSegmentUserRoute, $restrictedRoutes) ){
                $restrictedRoutes[] = $firstSegmentUserRoute;
            }
            if( !in_array($firstSegmentAppRoute, $restrictedRoutes) ){
                $restrictedRoutes[] = $firstSegmentAppRoute;
            }
        }
        $restrictedRoutes = array_unique(array_merge($restrictedRoutes , $this->reservedSlugs));

        $isRestricted = $this->isStringReservered($storeSlug);
        
        if(empty($usersWithSlug) && !in_array($storeSlug, $restrictedRoutes) && !$isRestricted){
            $memberEntity->setSlug($storeSlug);
            $memberEntity->setIsSlugChanged(true);
            $isSuccessful = true;
            try{
                $this->em->flush();
            }
            catch(\Doctrine\ORM\Query\QueryException $e){
                $isSuccessful = false;
            }
            return $isSuccessful;
        }
        return false;
    }
    
    /**
     * Updates the user store name
     *
     * @param EasyShop\Entities\EsMember $memberEntity
     * @param string $name
     * @param bool $executeFlush
     * @return bool
     */
    public function updateStorename($memberEntity, $name, $executeFlush = true)
    {
        $isSuccessful = false;
        $usersWithStorename = $this->em->getRepository('EasyShop\Entities\EsMember')
                                   ->getUserWithStoreNameOrUsername($name, $memberEntity->getIdMember());
        $isRestricted = $this->isStringReservered($name);
        if($usersWithStorename === null && !$isRestricted){
            $memberEntity->setStorename($name);
            $isSuccessful = true;
            if($executeFlush){
                try{
                    $this->em->flush();
                }
                catch(\Doctrine\ORM\Query\QueryException $e){
                    $isSuccessful = false;
                }
            }
        }
        return $isSuccessful;
    }
    
    /**
     * Checks if a string is reserved
     *
     * @param string $string
     * @return boolean
     */
    public function isStringReservered($string)
    {
        $reservedKeywords = $this->configLoader->getItem('reserved');
        $isRestricted = false;
        foreach($reservedKeywords as $keyword){
            if( preg_match( '/' . $keyword . '/i' , $string ) ){
                $isRestricted = true;
                break;
            }    
        }
        return $isRestricted;
    }
    
}

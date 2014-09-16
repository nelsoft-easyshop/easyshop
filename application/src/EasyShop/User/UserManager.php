<?php

namespace EasyShop\User;

use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use EasyShop\Entities\EsProduct;

use EasyShop\Entities\EsAddress;
use EasyShop\Entities\EsLocationLookup;

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
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($em)
    {
        $this->em = $em;
        $this->valid = true;
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
                                    ->getUsedMobile($this->memberId, $mobileNum);
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
        $isValidMobile = preg_match('/^(08|09)[0-9]{9}/', $mobileNum);

        if($isValidMobile){
            $mobileNum = ltrim($mobileNum,"0");
            return true;
        }
        else{
            return false;
        }
    }

}

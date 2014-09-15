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
     *  @var EasyShope\Entities\EsMember
     */
    private $memberEntity;

    private $valid;

    private $err;

    /**
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($em)
    {
        $this->em = $em;
        $this->valid = true;
    }

    public function __call($name, $args)
    {
        if($this->valid){
            $this->valid = call_user_func_array(array($this,$name), $args);
        }
        return $this;
    }

    public function errorInfo()
    {
        print($this->err);
    }

    public function showDetails()
    {
        print("Member ID: ". $this->memberId . "<br>");
    }

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

    private function setAddressTable($stateRegionId, $cityId, $strAddress, $type, $lat=0, $lng=0, $consignee="", $mobileNum="", $telephone="", $country=1)
    {
        // Verify location validity
        $locationEntity = $this->em->getRepository("EasyShop\Entities\EsLocationLookup")
                                    ->verifyLocationCombination($stateRegionId, $cityId);
        $isValidLocation = !empty($locationEntity);

        // Verify mobile format
        $validMobile = preg_match('/^(08|09)[0-9]{9}/', $mobileNum);

        if( $isValidLocation ){

            $arrAddressEntity = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                    ->getAddressDetails($this->memberId, $type);
            
            if( !empty($arrAddressEntity) ){
                $address = $arrAddressEntity[0];
                /*
                $dbStateRegionId = $address->getStateregion()->getIdLocation();
                $dbCityId = $address->getCity()->getIdLocation();
                $dbAddressString = $address->getAddress();
                $dbLat = $address->getLat();
                $dbLng = $address->getLng();
                
                $isNotEqualStateRegion = (string)$stateRegionId !== (string)$dbStateRegionId ? TRUE:FALSE;
                $isNotEqualCity = (string)$cityId !== (string)$dbCityId ? TRUE:FALSE;
                $isNotEqualAddressString = (string)$strAddress !== (string)$dbAddressString ? TRUE:FALSE;
                $isNotEqualLat = (string)$lat !== (string)$dbLat ? TRUE:FALSE;
                $isNotEqualLng = (string)$lng !== (string)$dbLng ? TRUE:FALSE;

                if( $isNotEqualStateRegion || $isNotEqualCity || $isNotEqualAddressString || $isNotEqualLat || $isNotEqualLng){
                    $localLat = $lat;
                    $localLng = $lng;
                }
                else{
                    $localLat = $dbLat;
                    $localLng = $dbLng;
                }*/
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
                        ->setTelephone($telephone)
                        ->setMobile($mobileNum)
                        ->setConsignee($consignee)
                        ->setCountry($countryEntity);

                $this->em->persist($address);
            

            return true;
        }
        else{
            $this->err = "Invalid location combination";
        }

        return false;
    }

    public function save()
    {
        $this->em->flush();

        return $this->valid;
    }

    /****************** UTILITY FUNCTIONS *******************/

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

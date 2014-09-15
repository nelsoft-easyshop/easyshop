<?php

namespace EasyShop\User;

use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsMemberCat;
use EasyShop\Entities\EsMemberProdcat;
use EasyShop\Entities\EsProduct;

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

    private $mobile;

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
        print($this->memberId);
        print($this->mobile);
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

    private function setPersonalMobile($mobileNum)
    {
        //$mobileNum = ltrim($mobileNum,'0');

        $isAvailable = $mobileNum === "09177050441" ? true:false;

        if($isAvailable){
            $this->mobile = "09177050441 stored.";
            return true;
        }
        else{
            $this->err = "Mobile number already in use";
            return false;
        }

    }

    public function flush()
    {
        return $this->valid;
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

    

}

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
     *  Member ID
     */
    private $memberId;

    /**
     *  EasyShop\Entities\EsMember entity object
     */
    private $memberObj;

    /**
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     *  REQUIRED! Identify User to work on
     *
     *  @return object $this
     */
    public function setUser($memberId)
    {
        $this->memberId = $memberId;
        $this->memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);

        return $this;
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

        if($user === null){
            return false;
        }

        $checkStoreName = $this->em->getRepository('EasyShop\Entities\EsMember')
                                   ->getMemberStoreName($memberId,$storeName);

        // If store name is not yet used, set user's storename to $storeName
        if( empty($checkStoreName) ){
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

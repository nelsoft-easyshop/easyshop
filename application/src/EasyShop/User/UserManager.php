<?php

namespace EasyShop\User;

use EasyShop\Entities\EsMember;

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
     *  Constructor. Retrieves Entity Manager instance
     */
    public function __construct($em)
    {
        $this->em = $em;
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
        $checkStoreName = array();

        if($user === null){
            return false;
        }

        if( strlen($storeName) > 0 ){
            $checkStoreName = $this->em->getRepository('EasyShop\Entities\EsMember')
                                       ->getMemberStoreName($memberId,$storeName);
        }

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

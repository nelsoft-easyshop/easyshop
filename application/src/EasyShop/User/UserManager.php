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

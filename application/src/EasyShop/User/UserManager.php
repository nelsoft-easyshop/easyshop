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
    public function __construct()
    {
        $this->em = &get_instance()->kernel->serviceContainer['entity_manager'];
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
            ->validStoreName($memberId,$storeName);

        // If store name is not yet used, set user's storename to $storeName
        if($checkStoreName){
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
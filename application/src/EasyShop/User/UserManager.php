<?php

namespace EasyShop\User

use EasyShop\Entities\EsMember;

class UserManager
{


    private $em;

    public function __construct()
    {
        $this->em = &get_instance()->kernel->serviceContainer['user_manager'];
    }

    
}
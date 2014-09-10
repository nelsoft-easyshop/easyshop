<?php

namespace EasyShop\LoginThrottler;

use EasyShop\Entities\EsFailedLoginHistory;
use \DateTime;

class LoginThrottler
{


    /**
     * Login Throttle Time Constant
     *
     * @var array
     */    
    private $throttleTime = [
            0, // 1st failed attempt
            0, // 2nd failed attempt
            0, // 3rd failed attempt
            30, // 4th failed attempt
            45, // 5th failed attempt
            60 // 6th+ failed attempt
        ];

    /**
     * Entity Manager
     * 
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Http-foundation Request
     * 
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * Constructor.
     */
    public function __construct($em, $request)
    {
        $this->em = $em;
        $this->request = $request;
    }

    public function logFailedAttempt($username)
    {
        $loginHistory = new EsFailedLoginHistory();
        $loginHistory->setLoginUsername($username);
        $loginHistory->setLoginIp($request->getClientIp());
        $loginHistory->setLoginDatetime(date_create(date("Y-m-d H:i:s")));

        $this->em->persist($loginHistory);
        $this->em->flush();

        return $loginHistory;
    }

}
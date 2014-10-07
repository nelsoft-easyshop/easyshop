<?php

namespace EasyShop\LoginThrottler;

use EasyShop\Entities\EsFailedLoginHistory;
use EasyShop\Entities\EsMember;
use \DateTime;

/**
 * Login Throttler Class
 *
 * @author LA Roberto
 */

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
            0, // 4th failed attempt
            0, // 5th failed attempt
            30, // 6th failed attempt
            45, // 7th failed attempt
            60 // 8th+ failed attempt
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

    /**
     * Logs failed attempt site-wide
     *
     * @param string $username Username/email of member
     *
     * @return EasyShop\Entities\EsFailedLoginHistory
     */
    public function logFailedAttempt($username)
    {
        // log failed attempt regardless
        $loginHistory = new EsFailedLoginHistory();
        $loginHistory->setLoginUsername($username);
        $loginHistory->setLoginIp($this->request->getClientIp());
        $loginHistory->setLoginDatetime(date_create(date("Y-m-d H:i:s")));

        $this->em->persist($loginHistory);
        $this->em->flush();
        return $loginHistory;
    }

    /**
     * Computes and sets timeout basetime
     *
     * @param string $username Username/email of member
     *
     * @return EasyShop\Entities\EsMember
     */
    public function updateMemberAttempt($username)
    {
        $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->getUser($username);

        if($user !== NULL){            
            $user->setFailedLoginCount($user->getFailedLoginCount()+1);
            $user->setLastFailedLoginDatetime(date_create(date("Y-m-d H:i:s")));
            $this->em->flush();
        }

        return $user;
    }

    /**
     * Returns total timeout left in seconds
     *
     * @param string $username Username/email of member
     *
     * @return integer
     */
    public function getTimeoutLeft($username)
    {
        $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->getUser($username);

        if($user !== NULL && $user->getFailedLoginCount() > 0){
            $timeout = $user->getLastFailedLoginDatetime()->getTimeStamp(); 
            $loginAttemptCount = $user->getFailedLoginCount() - 1;
            $throttle = 0;

            if($loginAttemptCount <= count($this->throttleTime)-1){
                $throttle = $this->throttleTime[$loginAttemptCount];
            }
            else{
                $throttle = $this->throttleTime[count($this->throttleTime)-1];
            }
            return ($timeout + $throttle) - time();   
        }
        return 0;
    }
}


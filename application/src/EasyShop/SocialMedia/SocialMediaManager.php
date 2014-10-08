<?php

namespace Easyshop\SocialMedia;

use \DateTime;
use EasyShop\Entities\EsMember;
use Facebook\FacebookSession;

class SocialMediaManager
{

    const FACEBOOK = 1;
    const GOOGLE = 2;
    
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * UserManager
     *
     * @var EasyShop\User\UserManager;
     */
    private $userManager;

    public function __construct($appId, $secret, $fbRedirectLoginHelper, $googleClient, $em, $userManager)
    {
        $this->appId = $appId;
        $this->secret = $secret;
        $this->fbRedirectLoginHelper = $fbRedirectLoginHelper;
        $this->googleClient = $googleClient;
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * Returns a URL to which the user should be sent to
     *   in order to continue the login process with Social Media.
     *
     * @param $account
     * @param array $scope List of permissions to request during login
     * @return string
     */
    public function getLoginUrl($account, $scope = array())
    {
        switch ($account) {
            case self::FACEBOOK :
                $loginUrl = $this->fbRedirectLoginHelper->getLoginUrl($scope);
                break;
            case self::GOOGLE :
                $this->googleClient->setScopes($scope);
                $loginUrl = $this->googleClient->createAuthUrl();
        }

        return $loginUrl;
    }

    /**
     * @Facebook
     *  1>Handles response from Facebook and returns FacebookSession
     *  2>Returns a new request using the given session
     *  3>Makes the request to Facebook and returns the result
     *  4>Gets the result as a graph object
     * @Google
     * 1>Makes request to Google and returns the result
     * 2>Returns the user account/info object
     *
     * @param $account
     * @return userAccountObject
     */
    public function getAccount($account)
    {
        switch ($account) {
            case self::FACEBOOK :
                FacebookSession::setDefaultApplication($this->appId, $this->secret);
                $session = $this->fbRedirectLoginHelper->getSessionFromRedirect();
                $userProfile = (new \Facebook\FacebookRequest(
                            $session, 'GET', '/me'
                        ))->execute()->getGraphObject(\Facebook\GraphUser::className());
                break;
            case self::GOOGLE :
                $googleData = new \Google_Service_Oauth2($this->googleClient);
                $userProfile = $googleData->userinfo->get();
                break;
        }

        return $userProfile;
    }

    /**
     * @return google client class
     */
    public function getGoogleClient()
    {
        return $this->googleClient;
    }

    /**
     * Authenticate social media account
     *
     * @param $oauthId
     * @param $oauthProvider
     * @return false or EsMember
     */
    public function authenticateAccount($oauthId, $oauthProvider)
    {
        $response = FALSE;
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->findOneBy(array(
                                'oauthId' => $oauthId,
                                'oauthProvider' => $oauthProvider
                            ));
        if($member){
            $response = $member;
        }

        return $response;
    }

    /**
     * Register social media account to Easyshop
     *
     * @param $username
     * @param $fullname
     * @param $gender
     * @param $email
     * @param $emailVerify
     * @param $oAuthId
     * @param $oAuthProvider
     * @return EsMember
     */
    public function registerAccount($username, $fullname, $gender, $email, $emailVerify, $oAuthId, $oAuthProvider)
    {
        $member = new EsMember();
        $member->setUsername($username);
        $member->setFullname($fullname);
        $member->setGender($gender);
        $member->setEmail($email);
        $member->setIsEmailVerify($emailVerify);
        $member->setDatecreated(new DateTime('now'));
        $member->setLastmodifieddate(new DateTime('now'));
        $member->setLastLoginDatetime(new DateTime('now'));
        $member->setBirthday(new DateTime(date('0001-01-01 00:00:00')));
        $member->setLastFailedLoginDatetime(new DateTime('now'));
        $member->setOauthId($oAuthId);
        $member->setOauthProvider($oAuthProvider);
        $member->setSlug('');
        $this->em->persist($member);
        $this->em->flush();

        $this->userManager->generateUserSlug($member->getIdMember());
        
        return $member;
    }

    /**
     * Create session that was previously implemented in stored procedure
     * @param $memberId
     * @return mixed
     */
    public function createSession($memberId)
    {
        $date =  new DateTime('now');
        return sha1($memberId + $date->format('Y-m-d H:i:s'));
    }
}

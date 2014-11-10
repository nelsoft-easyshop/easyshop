<?php

namespace Easyshop\SocialMedia;

use \DateTime;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsMemberMerge;
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
    
    /**
     * Facebook Redirect login helper
     *
     * @var Facebook\FacebookRedirectLoginHelper
     */
    private $fbRedirectLoginHelper;
    
    /**
     * Google Client
     *
     * @var Google_Client
     */
    private $googleClient;

    /**
     * Oauth Configuration
     *
     * @var mixed
     */
    private $oauthConfig;

    /**
     * String utility
     *
     * @var EasyShop\Utility\StringUtility
     */
    private $stringUtility;

    /**
     * Form validation
     *
     */
    private $formValidation;

    /**
     *
     * Form Factory
     *
     */
    private $formFactory;
    
    /**
     * Class constructor. Loads dependencies.
     *
     * @param Facebook\FacebookRedirectLoginHelper $fbRedirectLoginHelper
     * @param Google_Client $googleClient
     * @param Doctrine\ORM\EntityManager $em
     * @param EasyShop\User\UserManager $userManager
     * @param EasyShop\Core\ConfigLoader $configLoader
     * @param EasyShop\Utility\StringUtility
     * @param EasShop\\EasyShop\FormValidation\ValidationRules
     * @param Symfony\Component\Form\Forms
     */
    public function __construct($fbRedirectLoginHelper, $googleClient, $em, $userManager, $configLoader, $stringUtility, $formValidation, $formFactory)
    {
        $this->fbRedirectLoginHelper = $fbRedirectLoginHelper;
        $this->googleClient = $googleClient;
        $this->em = $em;
        $this->userManager = $userManager;
        $this->oauthConfig = $configLoader->getItem('oauth');
        $this->stringUtility = $stringUtility;
        $this->formValidation = $formValidation;
        $this->formFactory = $formFactory;
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
                session_start();
                $facebookConfig = $this->oauthConfig['facebook']['key'];
                FacebookSession::setDefaultApplication($facebookConfig['appId'], $facebookConfig['secret']);
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
     * @param $email
     * @return array
     */
    public function authenticateAccount($oauthId, $oauthProvider, $email)
    {
        $doesEmailExists = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(array(
                                    'email' => $email
                                ));
        $socialMediaAccount = $this->em->getRepository('EasyShop\Entities\EsMemberMerge')
                                    ->findOneBy(array(
                                        'socialMediaId' => $oauthId,
                                        'socialMediaProvider' => $oauthProvider
                                    ));
        if ($socialMediaAccount) {
            $doesEmailExists = $this->em->getRepository('EasyShop\Entities\EsMember')
                                    ->find($socialMediaAccount->getMember());
        }
        $response = array(
            'doesAccountExists' => $doesEmailExists,
            'doesAccountMerged' => $socialMediaAccount
        );

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
        $member = false;
        $rules = $this->formValidation->getRules('register');
        $form = $this->formFactory->createBuilder('form', null, array('csrf_protection' => false))
            ->setMethod('POST')
            ->add('username', 'text', array('constraints' => $rules['username']))
            ->getForm();

        $form->submit([ 'username' => $username]);
        if ($form->isValid()) {
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
            $member->setSlug('');
            $this->em->persist($member);
            $this->em->flush();

            $this->userManager->generateUserSlug($member->getIdMember());
            $member = $this->mergeAccount($member, $oAuthId, $oAuthProvider);
        }

        return $member;
    }

    /**
     * Merge account
     * @param $member
     * @param $oAuthId
     * @param $oAuthProvider
     * @return EsMember
     */
    public function mergeAccount($member, $oAuthId, $oAuthProvider)
    {
        $doesAccountMerged = $this->em->getRepository('EasyShop\Entities\EsMemberMerge')
                                ->findBy([
                                    'member' => $member->getidMember(),
                                    'socialMediaId' => $oAuthId,
                                    'socialMediaProvider' => $oAuthProvider->getIdSocialMediaProvider()
                                ]);
        if (!$doesAccountMerged) {
            $socialAccount = new EsMemberMerge();
            $socialAccount->setMember($member);
            $socialAccount->setSocialMediaId($oAuthId);
            $socialAccount->setSocialMediaProvider($oAuthProvider);
            $socialAccount->setCreatedAt(new DateTime('now'));
            $this->em->persist($socialAccount);
            $this->em->flush();
        }

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
    
        
    /**
     * Returns the facebook type constant
     *
     * @return integer
     */
    public function getFacebookTypeConstant()
    {
        return self::FACEBOOK;
    }
    
    /**
     * Returns the google type constant
     *
     * @return integer
     */
    public function getGoogleTypeConstant()
    {
        return self::GOOGLE;
    }

}

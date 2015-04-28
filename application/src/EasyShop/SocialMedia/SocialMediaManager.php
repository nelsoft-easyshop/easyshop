<?php

namespace Easyshop\SocialMedia;

use \DateTime;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsMemberMerge;
use EasyShop\Entities\EsSocialMediaProvider;
use EasyShop\Entities\EsStoreColor;
use Facebook\FacebookSession;
use EasyShop\Entities\EsBanType as EsBanType;
use EasyShop\Entities\EsPointType as EsPointType;

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
     * Config Loader 
     *
     * @var EasyShop\Core\ConfigLoader
     */
    private $configLoader;

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
     * Form error helper
     *
     */
    private $formErrorHelper;

    /**
     * Point tracker 
     */
    private $pointTracker;

    /**
     * Class constructor. Loads dependencies.
     *
     * @param Facebook\FacebookRedirectLoginHelper $fbRedirectLoginHelper
     * @param Google_Client $googleClient
     * @param Doctrine\ORM\EntityManager $em
     * @param EasyShop\User\UserManager $userManager
     * @param EasyShop\Core\ConfigLoader $configLoader
     * @param EasyShop\Utility\StringUtility
     * @param EasyShop\FormValidation\ValidationRules
     * @param Symfony\Component\Form\Forms
     * @param EasyShop\FormValidation\FormHelpers\FormErrorHelper
     */
    public function __construct($fbRedirectLoginHelper, 
                                $googleClient, 
                                $em, 
                                $userManager, 
                                $configLoader,
                                $stringUtility, 
                                $formValidation, 
                                $formFactory, 
                                $formErrorHelper,
                                $pointTracker)
    {
        $this->fbRedirectLoginHelper = $fbRedirectLoginHelper;
        $this->googleClient = $googleClient;
        $this->em = $em;
        $this->userManager = $userManager;
        $this->configLoader = $configLoader;
        $this->stringUtility = $stringUtility;
        $this->formValidation = $formValidation;
        $this->formFactory = $formFactory;
        $this->formErrorHelper = $formErrorHelper;
        $this->pointTracker = $pointTracker;
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
            case EsSocialMediaProvider::FACEBOOK :
                $loginUrl = $this->fbRedirectLoginHelper->getLoginUrl($scope);
                break;
            case EsSocialMediaProvider::GOOGLE :
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
        $oauthConfig = $this->configLoader->getItem('oauth');
        switch ($account) {
            case EsSocialMediaProvider::FACEBOOK :
                session_start();
                $facebookConfig = $oauthConfig['facebook']['key'];
                FacebookSession::setDefaultApplication($facebookConfig['appId'], $facebookConfig['secret']);
                $session = $this->fbRedirectLoginHelper->getSessionFromRedirect();
                $userProfile = (new \Facebook\FacebookRequest(
                            $session, 'GET', '/me'
                        ))->execute()->getGraphObject(\Facebook\GraphUser::className());
                break;
            case EsSocialMediaProvider::GOOGLE :
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
        $getMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy([
                                    'email' => $email
                                 ]);
        $socialMediaAccount = $this->em->getRepository('EasyShop\Entities\EsMemberMerge')
                                        ->findOneBy([
                                            'socialMediaId' => $oauthId,
                                            'socialMediaProvider' => $oauthProvider
                                        ]);
        if ($socialMediaAccount) {
            $getMember = $this->em->getRepository('EasyShop\Entities\EsMember')
                                    ->find($socialMediaAccount->getMember());
        }
        $response = [
            'getMember' => $getMember,
            'isAccountMerged' => (bool) $socialMediaAccount
        ];

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
        $form = $this->formFactory->createBuilder('form', null, ['csrf_protection' => false])
                     ->setMethod('POST')
                     ->add('username', 'text', ['constraints' => $rules['username']])
                     ->add('gender', 'text', ['constraints' => $rules['gender']])
                     ->add('email', 'text', ['constraints' => $rules['email']])
                     ->getForm();
                     
        $form->submit([ 
            'username' => $username,
            'gender' => $gender,
            'email' => $email,
        ]);
        if ($form->isValid()) {
            $defaultStoreColor = $this->em->getRepository('EasyShop\Entities\EsStoreColor')
                                          ->findOneBy(['idStoreColor' => EsStoreColor::DEFAULT_COLOR_ID]);
            $banType = $this->em->getRepository('EasyShop\Entities\EsBanType')
                                ->find(EsBanType::NOT_BANNED);
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
            $member->setStoreColor($defaultStoreColor);
            $member->setBanType($banType);
            $member->setLastAvatarChanged(new DateTime('now'));
            $member->setLastBannerChanged(new DateTime('now'));

            $this->em->persist($member);
            $this->em->flush();

            $this->userManager->generateUserSlug($member->getIdMember());
            $member = $this->mergeAccount($member, $oAuthId, $oAuthProvider);
            $this->pointTracker->addUserPoint($member->getIdMember(), EsPointType::TYPE_REGISTER);
        }

        $response = [
            'member' => $member,
            'errors' => $this->formErrorHelper->getFormErrors($form),
        ];

        return $response;
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
     * Updates email if current saved email is incorrect.
     * @param $esMember
     * @param $socialMedialEmail
     * @return EsMember
     */
    public function fixSocialMediaEmail($esMember, $socialMedialEmail)
    {
        $newEsMember = $esMember;
        if (stripos($esMember->getEmail(), '@') === false)
        {
            $newEsMember->setEmail($socialMedialEmail);
            $this->em->persist($newEsMember);
            $this->em->flush();
        }

        return $newEsMember;
    }

    /**
     * Merge old social media account to new account social media account
     * @param $socialMediaId
     * @param $socialMediaProvider
     * @param $newSocialMediaAccount
     */
    public function mergeOldSocialMediaAccountToNew($socialMediaId, $socialMediaProvider, $newSocialMediaAccount)
    {
        $oldSocialMediaAccount = $this->em->getRepository('EasyShop\Entities\EsMember')
            ->findOneBy([
                'oauthId' => $socialMediaId,
                'oauthProvider' => (int) $socialMediaProvider === 1 ? 'Facebook' : 'Google'
            ]);
        if ($oldSocialMediaAccount) {
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsOrder', 'tblOrder')
                ->set('tblOrder.buyer', ':newId')
                ->where('tblOrder.buyer = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsOrderProduct', 'tblOrderProduct')
                ->set('tblOrderProduct.seller', ':newId')
                ->where('tblOrderProduct.seller = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsProduct', 'tblProduct')
                ->set('tblProduct.member', ':newId')
                ->where('tblProduct.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsBillingInfo', 'tblBillingInfo')
                ->set('tblBillingInfo.member', ':newId')
                ->where('tblBillingInfo.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsMemberFeedback', 'tblMemberFeedback')
                ->set('tblMemberFeedback.member', ':newId')
                ->where('tblMemberFeedback.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsMemberFeedback', 'tblMemberFeedback')
                ->set('tblMemberFeedback.forMemberid', ':newId')
                ->where('tblMemberFeedback.forMemberid = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsMessages', 'tblMessages')
                ->set('tblMessages.to', ':newId')
                ->where('tblMessages.to = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsMessages', 'tblMessages')
                ->set('tblMessages.from', ':newId')
                ->where('tblMessages.from = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsOrderProductTag', 'tblOrderProductTag')
                ->set('tblOrderProductTag.seller', ':newId')
                ->where('tblOrderProductTag.seller = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsProductReview', 'tblProductReview')
                ->set('tblProductReview.member', ':newId')
                ->where('tblProductReview.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsProductShippingComment', 'tblProductShippingComment')
                ->set('tblProductShippingComment.member', ':newId')
                ->where('tblProductShippingComment.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsProductShippingPreferenceHead', 'tblProductShippingPrefHead')
                ->set('tblProductShippingPrefHead.member', ':newId')
                ->where('tblProductShippingPrefHead.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsPromo', 'tblPromo')
                ->set('tblPromo.memberId', ':newId')
                ->where('tblPromo.memberId = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsVendorSubscribe', 'tblVendorSubscribe')
                ->set('tblVendorSubscribe.member', ':newId')
                ->where('tblVendorSubscribe.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsVendorSubscribe', 'tblVendorSubscribe')
                ->set('tblVendorSubscribe.vendor', ':newId')
                ->where('tblVendorSubscribe.vendor = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsVendorSubscribeHistory', 'tblVendorSubscribeHistory')
                ->set('tblVendorSubscribeHistory.member', ':newId')
                ->where('tblVendorSubscribeHistory.member = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
            $qb = $this->em->createQueryBuilder();
            $query = $qb->update('EasyShop\Entities\EsVendorSubscribeHistory', 'tblVendorSubscribeHistory')
                ->set('tblVendorSubscribeHistory.vendor', ':newId')
                ->where('tblVendorSubscribeHistory.vendor = :oldId')
                ->setParameter(':newId', $newSocialMediaAccount->getIdMember())
                ->setParameter(':oldId', $oldSocialMediaAccount->getIdMember())
                ->getQuery();
            $query->execute();
        }
    }

    /**
     *  Return social media links
     *  @return string[]
     */
    public function getSocialMediaLinks()
    {
        return $this->configLoader->getItem('social_media_links');       
    }
    
}

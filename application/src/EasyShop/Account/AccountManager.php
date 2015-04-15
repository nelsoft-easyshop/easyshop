<?php 

namespace EasyShop\Account;

use \DateTime;
use EasyShop\Entities\EsWebserviceUser;
use EasyShop\Entities\EsVerifcode as EsVerifcode; 
use Easyshop\Entities\EsMember;
use Easyshop\Entities\EsStoreColor as EsStoreColor;
use EasyShop\Entities\EsBanType as EsBanType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query as Query;
use Elnur\BlowfishPasswordEncoderBundle\Security\Encoder\BlowfishPasswordEncoder as BlowfishPasswordEncoder;

class AccountManager
{

    const REMEMBER_ME_COOKIE_LIFESPAN_IN_SEC = 86500;
    
    /**
     * Users have to wait for EMAIL_COOLDOWN_DURATION_IN_MINUTES minutes before 
     * requesting for another email once the limit has been reached. This is
     * to prevent possible DOS.
     */
    const EMAIL_VERIFICATION_REQUEST_LIMIT = 4;
    
    const EMAIL_COOLDOWN_DURATION_IN_MINUTES = 30;
    
    const PASSWORD_RESET_LINK_LIFESPAN_MINUTES = 1440;
    

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
        
    /**
     * Blowfish Encoder
     *
     *
     */
    private $bcryptEncoder;
    
    
    /**
     * The user manager
     *
     */
    private $userManager;
    
    /**
     * Form factory
     *
     */
    private $formFactory;
    
    /**
     * Form validation
     *
     */
    private $formValidation;
    
    /**
     * Form error helper
     *
     */
    private $formErrorHelper;
    
    /**
     * String utility helper
     *
     */
    private $stringUtility;
    
    
    /**
     * Symfony's HTTP request class
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $httpRequest;

    /**
     * Email Notification
     *
     */
    private $emailNotification;   
    
    /**
     * CI_Parser
     *
     * @var CI_Parser
     */
    private $parser;
    
    /**
     * CI_Encrypt
     *
     * @var CI_Encrypt
     */
    private $encrypter;
    
    /**
     * Config Loader
     *
     * @var EasyShop\ConfigLoader\ConfigLoader
     */
    private $configLoader;
    
    /**
     * Language Loader
     *
     * @var EasyShop\LanguageLoader\LanguageLoader
     */
    private $languageLoader;
    
    /**
     * Hash Utility
     *
     * @var EasyShop\Utility\HashUtility
     */
    private $hashUtility;
    
    
    /**
     * The social media manager
     *
     * @var EasyShop\SociaMedia\SocialMediaManager
     */
    private $socialMediaManager;
    
    /**
     * Intialize dependencies
     *
     */
    public function __construct($em, 
                                BlowfishPasswordEncoder $bcryptEncoder, 
                                $userManager, 
                                $formFactory, 
                                $formValidation,
                                $formErrorHelper, 
                                $stringUtility,
                                $httpRequest,
                                $emailNotification,
                                $parser,
                                $encrypter,
                                $configLoader,
                                $languageLoader,
                                $hashUtility,
                                $socialMediaManager)
    {
        $this->em = $em;
        $this->bcryptEncoder = $bcryptEncoder;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->formValidation = $formValidation;
        $this->formErrorHelper = $formErrorHelper;
        $this->stringUtility = $stringUtility;
        $this->httpRequest = $httpRequest;
        $this->emailNotification = $emailNotification;
        $this->parser = $parser;
        $this->encrypter = $encrypter;
        $this->configLoader = $configLoader;
        $this->languageLoader = $languageLoader;
        $this->hashUtility = $hashUtility;
        $this->socialMediaManager = $socialMediaManager;
    }

    /**
     * Sends an email verification
     *
     * @param EasyShop\Entities\EsMember $member
     * @param boolean $isNew
     * @param boolean $excludeVerificationLink
     * @return mixed
     */
    public function sendAccountVerificationLinks($member, $isNew = true, $excludeVerificationLink = false)
    {
        $response= [
            'isSuccessful' => false,
            'error' => ''
        ];
        
        $verifcodeRepository = $this->em->getRepository('EasyShop\Entities\EsVerifcode');
        $verifCode = null;
        if(!$isNew){
            $verifCode = $verifcodeRepository->findOneBy(['member' => $member]);
            if($verifCode !== null){
                $emailCount = $verifCode->getEmailCount();
                $dateNow =  new \DateTime();
                $dateOfLastRequest = $verifCode->getFpTimestamp();
                $deltaTime = $dateNow->diff($dateOfLastRequest);
                
                $elapsedMinutes = $deltaTime->days * 24 * 60;
                $elapsedMinutes += $deltaTime->h * 60;
                $elapsedMinutes += $deltaTime->i;

                if($emailCount > self::EMAIL_VERIFICATION_REQUEST_LIMIT &&
                    $elapsedMinutes <= self::EMAIL_COOLDOWN_DURATION_IN_MINUTES
                ){
                    $response['error'] = 'limit-of-requests-reached';
                }
                else if($emailCount > self::EMAIL_VERIFICATION_REQUEST_LIMIT &&
                    $elapsedMinutes > self::EMAIL_COOLDOWN_DURATION_IN_MINUTES
                ){
                    $verifCode->setEmailcount(0);
                    $this->em->flush();
                }
            }
        }
    
        if($response['error'] === ''){
            $emailAddress = $member->getEmail();
            $username = $member->getUserName();
            $emailSecretHash = sha1($emailAddress.time());
            $socialMediaLinks = $this->socialMediaManager
                                     ->getSocialMediaLinks();
            $parseData = [
                'user' => $username,
                'hash' => $this->encrypter
                               ->encode($emailAddress.'|'.$username.'|'.$emailSecretHash),
                'site_url' => site_url('register/email_verification'),
                'baseUrl' => base_url(),
                'facebook' => $socialMediaLinks["facebook"],
                'twitter' => $socialMediaLinks["twitter"],
            ];
            
            if($excludeVerificationLink){
                $parseData['emailVerified'] = true;
            }
            
            $imageArray = $this->configLoader->getItem('email', 'images');  
            $message = $this->parser->parse('emails/email-verification' , $parseData,true);
            
            $this->emailNotification->setRecipient($emailAddress);
            $this->emailNotification->setSubject($this->languageLoader->getLine('registration_subject'));
            $this->emailNotification->setMessage($message, $imageArray);
            /**
             * Mobile verification can be added here (unused for the time being)
             */
            $mobileCode = $this->hashUtility->generateRandomAlphaNumeric(6);
            if($this->emailNotification->queueMail()){
                if($verifCode === null){
                    $response['isSuccessful'] = $verifcodeRepository->createNewMemberVerifCode($member, $emailSecretHash, $mobileCode) ? true : false;
                }
                else{
                    $response['isSuccessful'] = $verifcodeRepository->updateVerifCode($verifCode, $emailSecretHash, $mobileCode);
                }
            }
        }
        return $response;
    }

    /**
     * Authenticate a web service client
     * 
     * @param string $clientUsername
     * @param string $clientPassword
     * @return boolean
     */
    public function authenticateWebServiceClient($clientUsername, $clientPassword)
    {    
        $response = false;
        
        $webServiceUser = $this->em->getRepository('EasyShop\Entities\EsWebserviceUser')
                           ->findOneBy(array('username' => $clientUsername));
        if($member){
            if($this->bcryptEncoder->isPasswordValid($member->getPassword(), 
                                                      $clientPassword))
            {
                $response = true;
            }
        }
        
        return $response;
    }
    
    /**
     * Authenticate a member
     * 
     * @param string $username
     * @param string $password
     * @param bool $asArray
     * @return mixed Returns an array of the error and the member entity
     */    
    public function authenticateMember($username, $password, $asArray = false, $doIgnoreActiveStatus = false)
    {
        $errors = array();
        $member = null;
        
        $rules = $this->formValidation->getRules('login');
        $form = $this->formFactory->createBuilder('form', null, array('csrf_protection' => false))
                        ->setMethod('POST')
                        ->add('username', 'text', array('constraints' => $rules['username']))
                        ->add('password', 'text', array('constraints' => $rules['password']))
                        ->getForm();
        
        $form->submit([ 'username' => $username,
                        'password' => $password
                    ]);
        
        if ($form->isValid()) {
            $formData = $form->getData();
            $validatedUsername = $formData['username'];
            $validatedPassword = $formData['password'];
            /**
             * Intialize error array
             */
            array_push($errors, ['login' => 'Invalid Username/Password']);  
            
            if(strpos($validatedUsername, '@') !== false){
                $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                               ->findOneBy(['email' => $validatedUsername]);
            }
            else{
                $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                               ->findOneByUsernameCase($validatedUsername);
            }
            
            if($member){
                $memberUsername =  $member->getUsername();       
                $encryptedPassword = $member->getPassword();
                if(!$this->bcryptEncoder->isPasswordValid($encryptedPassword, $validatedPassword)) {
                    if(!$this->authenticateByReverseHashing($memberUsername, $validatedPassword, $member)){
                        $member = null;   
                    }
                }       
            }
            
            if($member){
                unset($errors[0]);  
                $member->setFailedLoginCount(0);
                if((bool)$member->getIsBanned() && $member->getBanType()->getIdBanType() !== 0){
                    $errors[] = [
                        'login' => 'Account Banned',
                        'id' => $member->getIdMember(),
                        'message' => $member->getBanType()->getMessage(),
                    ];
                    $member = null;  
                }
                else if(!(bool)$member->getIsActive() && !$doIgnoreActiveStatus) {
                    $errors[] = [
                        'login' => 'Account Deactivated',
                        'id' => $member->getIdMember(),
                    ];
                    $member = null;    
                }
                else {
                    $member->setLastLoginDatetime(date_create(date("Y-m-d H:i:s")));
                    $member->setLastLoginIp($this->httpRequest->getClientIp());
                    $member->setLoginCount($member->getLoginCount() + 1);
                    $this->em->flush(); 
                    $member = !$asArray ? $member :  $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                                                                        ->getHydratedMember($validatedUsername, $asArray);                    
                }                                                                    
            }
        }

        return [
            'errors' => array_merge($errors, $this->formErrorHelper->getFormErrors($form)),
            'member' => $member
        ];
    
    }
    
    /**
     * Registers a new user in es_member
     *
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $contactno
     * @return mixed Returns an array of the error and the member entity
     */
    public function registerMember($username, $password, $email, $contactno, $isEmailVerify = false)
    {
        $member = null;
        $rules = $this->formValidation->getRules('register');
        $form = $this->formFactory->createBuilder('form', null, array('csrf_protection' => false))
                ->setMethod('POST')
                ->add('username', 'text', array('constraints' => $rules['username']))
                ->add('password', 'text', array('constraints' => $rules['password']))
                ->add('contactno', 'text', array('constraints' => $rules['contactno']))
                ->add('email','text' ,array('constraints' => $rules['email']))
                ->getForm();

        $form->submit([ 'username' => $username,
                        'password' => $password,
                        'contactno' => $contactno,
                        'email' => $email
                    ]);
                    
        if ($form->isValid()) {
            $formData = $form->getData();
            $validatedUsername =  $formData['username'];
            $validatedPassword = $formData['password'];
            $validatedEmail = $formData['email'];
            $validateContactno = substr($formData['contactno'],1); 
            $hashedPassword = $this->bcryptEncoder->encodePassword($validatedPassword);
            
            $storeColor = $this->em->getRepository('EasyShop\Entities\EsStoreColor')
                                   ->find(EsStoreColor::DEFAULT_COLOR_ID);
            $banType = $this->em->getRepository('EasyShop\Entities\EsBanType')
                                ->find(EsBanType::NOT_BANNED);
            
            $member = new EsMember();
            $member->setUsername($validatedUsername);
            $member->setPassword($hashedPassword);
            $member->setEmail($validatedEmail);
            $member->setContactno($validateContactno);
            $member->setDatecreated(new DateTime('now'));
            $member->setLastmodifieddate(new DateTime('now'));
            $member->setLastLoginDatetime(new DateTime('now'));
            $member->setLastFailedLoginDateTime(new DateTime('now'));
            $member->setBirthday(new DateTime(date('0001-01-01 00:00:00')));
            $member->setSlug($this->stringUtility->cleanString($username));   
            $member->setIsEmailVerify($isEmailVerify); 
            $member->setStoreColor($storeColor);  
            $member->setBanType($banType);
            
            $this->em->persist($member);
            $this->em->flush();
        }

        return ['errors' => $this->formErrorHelper->getFormErrors($form),
                'member' => $member];
    }

  
    
    /**
     * Authentication implementation for accounts with old password hashing
     *
     * @param string $username
     * @param string $password
     * @param Entity $member
     *
     * @return bool
     */
    public function authenticateByReverseHashing($username, $password, $member)
    {
        $hashedPassword = $this->hashUtility->generalPurposeHash($username, $password);

        if($member->getUsername() === $username && $member->getPassword() === $hashedPassword) {
            $this->updatePassword($member->getIdMember(), $password);
            $isAuthenticated = true;
        }
        else {
            $isAuthenticated = false;
        }

        return $isAuthenticated;        
    }

    /**
     * Converts password hashing to bCrypt encryption
     *
     * @param int $memberId
     * @param string $bCryptPassword
     */
    public function updatePassword($memberId, $password)
    {   
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->findOneBy(['idMember' => $memberId]);
        $member->setPassword($this->bcryptEncoder->encodePassword($password));
        $this->em->flush();
        return true;
    }

    /**
     * Authenticates a user via the remember me cookie
     * 
     * @param string $cookie
     * @param string $ipAddress
     * @param string $userAgent
     * @param string $cisessionId
     */
    public function authenticateViaCookie($cookie, $ipAddress, $userAgent, $cisessionId)
    {
        $returnData = [
            'isSuccessful' => false,
        ];
        if($cookie && $cookie !== ''){             
            $rememberMeData = $this->em->getRepository('EasyShop\Entities\EsKeeplogin')
                                       ->findOneBy([
                                            'token' => $cookie,
                                            'lastIp' => $ipAddress,
                                            'useragent' => $userAgent,
                                        ]);

            if($rememberMeData){
                $member = $rememberMeData->getIdMember();
                if($member && !(bool)$member->getIsBanned() && (bool)$member->getIsActive()){
                    $newUserSession = $this->generateUsersessionId($member->getIdMember());
                    $newToken = $this->persistRememberMeCookie($member, $ipAddress, $userAgent, $cisessionId);
                    $member->setUsersession($newUserSession);
                    $this->em->flush();
                    $returnData['isSuccessful'] = true;
                    $returnData['usersession'] = $newUserSession;
                    $returnData['newCookie'] = $newToken;     
                    $returnData['member'] = $member;
                }
            }
        }
        return $returnData;
    }

    /**
     * Returns a unique usersession key
     *
     * @param integer $id
     * @return string
     */
    public function generateUsersessionId($id)
    {
        return sha1($id.date("Y-m-d H:i:s"));
    }

    
    /**
     * Persists the remember-me cookie in to the database
     *
     * @param EasyShop\Entities\EsMember $member
     * @param string $ipAddress
     * @param string $userAgent
     * @param string $ciSessionId
     * @return string $newToken
     */
    public function persistRememberMeCookie($member, $ipAddress, $userAgent, $ciSessionId)
    {
        if(!$member){
            return false;
        }
     
        $memberId = $member->getIdMember();
        $keepLoginData = $this->em->getRepository('EasyShop\Entities\EsKeeplogin')
                                  ->findOneBy(['idMember' => $member]);
        if(!$keepLoginData){
            $keepLoginData = new \EasyShop\Entities\EsKeeplogin();
        }
        $newToken = $this->createRememberMeHash($member->getIdMember(), $ciSessionId);
        $keepLoginData->setIdMember($member);
        $keepLoginData->setLastIp($ipAddress);
        $keepLoginData->setUseragent($userAgent);
        $keepLoginData->setToken($newToken);
        $this->em->persist($keepLoginData);
        try{
            $this->em->flush();
        }
        catch(\Exception $e){
            return false;
        }
        return $newToken;
    }
    
    /**
     * Creates the remember me hash
     *
     * @param integer $memberId
     * @param string $cisessionId
     * @return string
     */
    public function createRememberMeHash($memberId, $cisessionId)
    {
        return sha1($memberId.$cisessionId.date('Y-m-d H:i:s'));
    }
    
    
    /** 
     * Destroys the remember me cookie from the database
     *
     * @param integer $memberId
     * @param string $ipAddress
     * @param string $useragent
     * @param string $token
     */
    public function unpersistRememberMeCookie($memberId, $ipAddress, $useragent, $token)
    {
        $member = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        if($member){
            $rememberMeCookieData = $this->em->getRepository('EasyShop\Entities\EsKeeplogin')
                                         ->findOneBy([
                                            'idMember' => $member,
                                            'lastIp' => $ipAddress,
                                            'useragent' => $useragent,
                                            'token' => $token,
                                        ]);
            if($rememberMeCookieData){
                $this->em->remove($rememberMeCookieData);
                $this->em->flush();
            }
        }
    }
    
    /**
     * Sends the forgot password email
     *
     * @param EasyShop\Entities\EsMember $member
     * @return bool
     */
    public function sendForgotPasswordLink($member)
    {
        $dateNow = date('Y-m-d H:i:s');
        $hash = $this->hashUtility->generalPurposeHash($dateNow, $dateNow);
        $verificationCode = $this->em->getRepository('EasyShop\Entities\EsVerifcode')
                                 ->findOneBy(['member' => $member]);
        if(!$verificationCode){
            $verificationCode = $this->em->getRepository('EasyShop\Entities\EsVerifcode')
                                         ->createNewMemberVerifCode($member);
        }
        $verificationCode->setFpTimestamp(new DateTime('now'));
        $verificationCode->setFpCode($hash);
        try{
            $this->em->flush();
        }
        catch(\Exception $e){
            return false;
        }
        $socialMediaLinks = $this->socialMediaManager
                                 ->getSocialMediaLinks();
        $parseData = [
            'username' => $member->getUsername(),
            'baseUrl' => base_url(),
            'facebook' => $socialMediaLinks["facebook"],
            'twitter' => $socialMediaLinks["twitter"],
            'updatePasswordLink' => base_url().'login/updatePassword?confirm='.$hash,
        ];
        $imageArray = $this->configLoader->getItem('email', 'images');  
        $message = $this->parser->parse('emails/password-reset' , $parseData,true);
        $this->emailNotification->setRecipient($member->getEmail());
        $this->emailNotification->setSubject('Password reset on Easyshop.ph');
        $this->emailNotification->setMessage($message, $imageArray);    
        return $this->emailNotification->queueMail();
    }
    
    
    /**
     * Validates the password reset link and updates the user password
     *
     * @param string $newPassword
     * @param string $hash
     * @return bool
     */
    public function validatePasswordReset($newPassword, $hash)
    {
        $verifCode = $this->em->getRepository('EasyShop\Entities\EsVerifcode')
                          ->findOneBy(['fpCode' => $hash]);
        $response = [
            'isSuccessful' => false,
            'member' => null,
        ];

        if($verifCode){
            $codeGeneationTime = $verifCode->getFpTimestamp();
            $dateNow = new DateTime('now');
            $deltaTime = $dateNow->diff($codeGeneationTime);
            $elapsedMinutes = $deltaTime->days * 24 * 60;
            $elapsedMinutes += $deltaTime->h * 60;
            $elapsedMinutes += $deltaTime->i;
            if($elapsedMinutes <= self::PASSWORD_RESET_LINK_LIFESPAN_MINUTES){
                $verifCode->setFpTimestamp($dateNow);
                $verifCode->setFpCode(null);
                $member = $verifCode->getMember();
                $response['member'] = $member;
                $response['isSuccessful'] = $this->updatePassword($member->getIdMember(), $newPassword);  
            }
        }
       
        return $response;
       
    }
    

}


<?php 

namespace EasyShop\Account;

use \DateTime;
use EasyShop\Entities\EsWebserviceUser;
use Easyshop\Entities\EsMember;
use Easyshop\Entities\EsStoreColor as EsStoreColor;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query as Query;
use Elnur\BlowfishPasswordEncoderBundle\Security\Encoder\BlowfishPasswordEncoder as BlowfishPasswordEncoder;

class AccountManager
{

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
                                $httpRequest)
    {
        $this->em = $em;
        $this->bcryptEncoder = $bcryptEncoder;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->formValidation = $formValidation;
        $this->formErrorHelper = $formErrorHelper;
        $this->stringUtility = $stringUtility;
        $this->httpRequest = $httpRequest;
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
                               ->findOneBy(['username' => $validatedUsername]);
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
                    $member->setFailedLoginCount(0);
                    $member->setLoginCount($member->getLoginCount() + 1);
                    $this->em->flush(); 
                    $member = !$asArray ? $member :  $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                                                                        ->getHydratedMember($validatedUsername, $asArray);                    
                }                                                                    
            }
        }

        return ['errors' => array_merge($errors, $this->formErrorHelper->getFormErrors($form)),
                'member' => $member];
    
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
                        'email' => $email,
                    ]);
                    
        if ($form->isValid()) {
            $formData = $form->getData();
            $validatedUsername =  $formData['username'];
            $validatedPassword = $formData['password'];
            $validatedEmail = $formData['email'];
            $validateContactno = substr($formData['contactno'],1); 
            $hashedPassword = $this->hashMemberPassword($validatedUsername,$validatedPassword);
            $storeColor = $this->em->getRepository('EasyShop\Entities\EsStoreColor')
                                   ->find(EsStoreColor::DEFAULT_COLOR_ID);
            
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
        $hashedPassword = $this->hashMemberPassword($username, $password);

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
     * Hashes the user password, previously implemented in a stored procedure
     *
     * @param string $username
     * @param string $password
     * @return string
     */
    public function hashMemberPassword($username, $password)
    {
        $rsm = new ResultSetMapping(); 
        $rsm->addScalarResult('hash', 'hash');
        $sql = "SELECT reverse(PASSWORD(concat(md5(:username),sha1(:password)))) as hash";
        $query = $this->em->createNativeQuery($sql, $rsm);
        $query->setParameter('username', $username);
        $query->setParameter('password', $password); 
        $result = $query->getOneOrNullResult();

        return $result['hash'];
    }    



}
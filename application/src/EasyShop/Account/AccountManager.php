<?php 

namespace EasyShop\Account;

use \DateTime;
use EasyShop\Entities\EsWebserviceUser;
use Easyshop\Entities\EsMember;
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
     * Intialize dependencies
     *
     */
    public function __construct($em, 
                                BlowfishPasswordEncoder $bcryptEncoder, 
                                $userManager, 
                                $formFactory, 
                                $formValidation,
                                $formErrorHelper, 
                                $stringUtility)
    {
        $this->em = $em;
        $this->bcryptEncoder = $bcryptEncoder;
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
        $this->formValidation = $formValidation;
        $this->formErrorHelper = $formErrorHelper;
        $this->stringUtility = $stringUtility;
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
    public function authenticateMember($username, $password, $asArray = false)
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

            if(strpos($validatedUsername, '@') !== false){
                $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->findOneBy(['email' => $validatedUsername]);
                if($member){
                    $validatedUsername = $member->getUsername();              
                }
            }

            $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                            ->findBy(['username' => $validatedUsername]);

            $member = isset($member[0]) ? $member[0] : $member;
            if($member) {
                if(!$this->bcryptEncoder->isPasswordValid($member->getPassword(), $validatedPassword)) {
                    if(!$this->oldAuthentication($validatedUsername, $validatedPassword)) {
                        $member = null;                        
                        array_push($errors, ['login' => 'Invalid Username/Password']);  
                    }
                }
            }
            else {
                $member = null;
                array_push($errors, ['login' => 'Invalid Username/Password']);
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
    public function registerMember($username, $password, $email, $contactno)
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
     * @param bool $asArray
     * @return Entity
     */
    public function oldAuthentication($username, $password, $asArray = false)
    {
        $hashedPassword = $this->hashMemberPassword($username, $password);
        $query =  $this->em->createQueryBuilder()
                ->select('m')
                ->from('EasyShop\Entities\EsMember', 'm')
                ->where('m.username= :username')
                ->andWhere('m.password= :password')
                ->setParameter('username', $username)
                ->setParameter('password', $hashedPassword)
                ->setMaxResults(1)
                ->getQuery();
        $hydrator = ($asArray) ? Query::HYDRATE_ARRAY : Query::HYDRATE_OBJECT;
        $member = $query->getResult($hydrator);
        $member = isset($member[0]) ? $member[0] : $member;
        if($member) {
            $this->migrateMemberPasswordToBcrypt($member->getIdMember(), $this->bcryptEncoder->encodePassword($password));
        }

        return $member;        
    }

    /**
     * Converts password hashing to bCrypt encryption
     *
     * @param int $memberId
     * @param string $bCryptPassword
     */
    public function migrateMemberPasswordToBcrypt($memberId, $bCryptPassword)
    {   
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                        ->findOneBy(['idMember' => $memberId]);
        $member->setPassword($bCryptPassword);
        $this->em->flush();
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
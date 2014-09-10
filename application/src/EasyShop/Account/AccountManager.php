<?php 

namespace EasyShop\Account;

use \DateTime;
use EasyShop\Entities\EsWebserviceUser;
use Easyshop\Entities\EsMember;
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
        
        $member = $this->em->getRepository('EasyShop\Entities\EsWebserviceUser')
                           ->findOneBy(array('username' => $clientUsername));
        if($member){
             if($this->bcryptEncoder->isPasswordValid($member->getPassword(), $clientPassword)){
                $response = true;
            }
        }
        
        return $response;
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
            
            $member = new EsMember();
            $member->setUsername($validatedUsername);
            $member->setPassword($validatedPassword);
            $member->setEmail($validatedEmail);
            $member->setContactno($validateContactno);
            $member->setDatecreated(new DateTime('now'));
            $member->setLastmodifieddate(new DateTime('now'));
            $member->setLastLoginDatetime(new DateTime('now'));
            $member->setBirthday(new DateTime(date('0001-01-01 00:00:00')));
            $member->setSlug($this->stringUtility->cleanString($username));   
            
            $this->em->persist($member);
            $this->em->flush();
            
        }
    
        return ['errors' => $this->formErrorHelper->getFormErrors($form),
                'member' => $member];
    }
    
    

}
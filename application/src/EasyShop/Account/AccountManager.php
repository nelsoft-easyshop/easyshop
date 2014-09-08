<?php 

namespace EasyShop\Account;

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
     * Intialize dependencies
     *
     */
    public function __construct($em, BlowfishPasswordEncoder $bcryptEncoder, $userManager)
    {
        $this->em = $em;
        $this->bcryptEncoder = $bcryptEncoder;
        $this->userManager = $userManager;
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
     * @return EasyShop\Entities\EsMember
     */
    public function registerMember($username, $password, $email, $contactno)
    {
        $member = new EsMember();
        $member->setUsername($username);
        $member->setPassword($password);
        $member->setEmail($email);
        $member->setContactno($contactno);
        $member->setDatecreated(date('Y-m-d H:i:s'));
        $member->setSlug($this->userManager->generateSlug($username));   
        $this->em->persist($member);
        $this->em->flush();
        
        return $member;
    }
    
    

}
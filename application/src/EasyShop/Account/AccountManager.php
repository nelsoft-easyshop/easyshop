<?php 

namespace EasyShop\Account;

use Elnur\BlowfishPasswordEncoderBundle\Security\Encoder\BlowfishPasswordEncoder as BlowfishPasswordEncoder;
use EasyShop\Entities\EsWebserviceUser;


class AccountManager
{

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * bcyript cost factor
     *
     *
     */
    private $costFactor = 5;
    
    /**
     * Blowfish Encoder
     *
     *
     */
    private $bcryptEncoder;
    
    
    /**
     * Intialize dependencies
     *
     */
    public function __construct()
    {
        $this->bcryptEncoder = new BlowfishPasswordEncoder($this->costFactor);
        $this->em = get_instance()->kernel->serviceContainer['entity_manager']; 
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
    public function registerNewMember($username, $password, $email, $contactno)
    {
        
    }
    
    

}
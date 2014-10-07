<?php 

use EasyShop\Entities\EsProduct; 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AccountService extends MY_Controller
{

    /**
     *  Constructor Declaration
     */
    private $em;    
    public function __construct()
    {   
        parent::__construct();
        $this->em = $this->serviceContainer['entity_manager'];              
    }


    /**
     *  Returns the number of active products in the site
     *
     *  @return integer $count
     */
    public function getProductCount() 
    {       
        $count  = $this->em->getRepository('EasyShop\Entities\EsProduct')->getActiveProductCount();        
        print($count);
    }

    /**
     *  Returns the number of users in the site
     *
     *  @return integer $count
     */
    public function getUserCount()  
    {
        $count  = $this->em->getRepository('EasyShop\Entities\EsMember')->getUserCount();
        print($count);
    }           

}




<?php

namespace EasyShop\Webservice;

/**
 * Webservice Authentication Service
 *
 * @author Inon Baguio
 */
class AuthenticateRequest
{

    /**
     *  Entity Manager Instance
     *
     *  @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     *  The hash string
     *
     *  @var string
     */
    private $hash = "";    

    /**
     *  Exceptional values that must not be included in the hash string
     *
     *  @var string
     */
    private $exceptionalData = [
        "hash", 
        "_token", 
        "csrfname", 
        "callback", 
        "password", 
        "_", 
        "checkuser",
        "product",
        "image"
    ];  

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Handles the request authentication method
     * @param  array  $postedData
     * @param  string  $postedHash    
     * @param  boolean $isAdminRequest
     * @return bool
     */
    public function authenticate($postedData, $postedHash, $isAdminRequest = false)
    {

        foreach ($postedData as $data => $value) {
            if(!in_array($data, $this->exceptionalData)) {
                $this->hash .= $value;            
            }
        }

        if($isAdminRequest) {
            $adminUser = $this->em->getRepository("EasyShop\Entities\EsAdminMember")
                                  ->find($postedData["userid"]);
            $this->hash .= $adminUser->getPassword();            
        }

        return (sha1($this->hash) === $postedHash);
    }

}



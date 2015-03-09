<?php

namespace EasyShop\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * OauthClients
 *
 * @ORM\Table(name="oauth_clients")
 * @ORM\Entity
 */
class OauthClients
{
    /**
     * @var string
     *
     * @ORM\Column(name="client_id", type="string", length=80, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $clientId;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", length=80, nullable=false)
     */
    private $clientSecret;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_uri", type="string", length=2000, nullable=false)
     */
    private $redirectUri;

    /**
     * @var string
     *
     * @ORM\Column(name="grant_types", type="string", length=80, nullable=true)
     */
    private $grantTypes;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string", length=100, nullable=true)
     */
    private $scope;

    /**
     * @var string
     *
     * @ORM\Column(name="user_id", type="string", length=80, nullable=true)
     */
    private $userId;

    const PROD_CLIENTID = "j0EHlg4idoNwT7GWltd7VHV52";

    const DEV_CLIENTID = "mobile";


    /**
     * Get clientId
     *
     * @return string 
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientSecret
     *
     * @param string $clientSecret
     * @return OauthClients
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get clientSecret
     *
     * @return string 
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Set redirectUri
     *
     * @param string $redirectUri
     * @return OauthClients
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * Get redirectUri
     *
     * @return string 
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * Set grantTypes
     *
     * @param string $grantTypes
     * @return OauthClients
     */
    public function setGrantTypes($grantTypes)
    {
        $this->grantTypes = $grantTypes;

        return $this;
    }

    /**
     * Get grantTypes
     *
     * @return string 
     */
    public function getGrantTypes()
    {
        return $this->grantTypes;
    }

    /**
     * Set scope
     *
     * @param string $scope
     * @return OauthClients
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string 
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set userId
     *
     * @param string $userId
     * @return OauthClients
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string 
     */
    public function getUserId()
    {
        return $this->userId;
    }
}

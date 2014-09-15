<?php


namespace EasyShop\OAuth\Storage;

use \EasyShop\Account\AccountManager as AccountManager;

/**
 * UserCredentials storage implementation
 *
 */
class UserCredentials implements \OAuth2\Storage\UserCredentialsInterface
{
    
    /**
     * Account Manager
     *
     * @var EasyShop\Account\AccountManager
     */
    private $accountManager;
    
    /**
     * EsMember object
     *
     * @var EasyShop\Entities\EsMember
     */
    private $user = null;
    
    public function __construct(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function checkUserCredentials($username, $password)
    {
        $authenticationResult = $this->accountManager->authenticateMember($username, $password, true);
        
        if(count($authenticationResult['errors']) !== 0){
            return false;
        }
        $this->user = $authenticationResult['member'];
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUserDetails($username)
    {
        $this->user = array_merge($this->user, ['user_id' => $this->user['idMember']]);
        return $this->user;
    }
    
}
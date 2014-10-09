<?php

class SocialMediaController extends MY_Controller
{
    
    /**
     * Class constructor
     *
     */
    function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->config('oauth', TRUE);
        $this->socialMediaManager = $this->serviceContainer['social_media_manager'];
    }
    /**
     * Register Facebook account to easyshop
     * @return mixed Returns the member entity if exist or successfully registered and false if sharing of email was declined
     */
    public function registerFacebookUser()
    {
    
        if ($this->input->get('error')){
            redirect('/login', 'refresh');
        }
        $facebookType = $this->socialMediaManager->getFacebookTypeConstant();
        $facebookData = $this->socialMediaManager->getAccount($facebookType);
        if ($facebookData->getProperty('email')) {
            $validateFacebookData = $this->socialMediaManager->authenticateAccount($facebookData->getId(), 'Facebook');
            if (!$validateFacebookData) {
                $response = $this->socialMediaManager->registerAccount(
                    $facebookData->getFirstName(),
                    $facebookData->getName(),
                    $facebookData->getProperty('gender') === 'male' ? 'M' : 'F',
                    $facebookData->getProperty('email'),
                    TRUE,
                    $facebookData->getId(),
                    'Facebook'
                );
            }
            else {
                $response = $validateFacebookData;
            }
            $this->login($response);
            redirect('/', 'refresh');
        }
        
        redirect('/login', 'refresh');

    }

    /**
     * Register Google account to easyshop
     * 
     */
    public function registerGoogleAccount()
    {
        if ($this->input->get('error')){
            redirect('/login', 'refresh');
        }

        $google = $this->socialMediaManager->getGoogleClient();  
        $googleType = $this->socialMediaManager->getGoogleTypeConstant();
        $httpRequest = $this->serviceContainer['http_request'];
        
        if($this->input->get('code'))
        {
            $code = $this->input->get('code');
            $google->authenticate($code);
            $this->session->set_userdata('access_token', $google->getAccessToken());
            $uri = $httpRequest->getUri();
            $qmarkLocation = strpos($uri, '?');
            if($qmarkLocation !== FALSE){
                $uri = substr($uri, 0, $qmarkLocation);
            }
            redirect($uri);
        }
        
        if($this->session->userdata('access_token')){
            $google->setAccessToken($this->session->userdata('access_token'));
            $googleData = $this->socialMediaManager->getAccount($googleType);
            
            $validateGoogleData = $this->socialMediaManager->authenticateAccount($googleData->getId(), 'Google');
            if(!$validateGoogleData) {
                $response = $this->socialMediaManager->registerAccount(
                    $googleData->getGivenName(),
                    $googleData->getName(),
                    '',
                    $googleData->getEmail(),
                    TRUE,
                    $googleData->getId(),
                    'Google'
                );
            }
            else {
                $response = $validateGoogleData;
            }
            $this->login($response);
            redirect('/', 'refresh'); 
        }
        
        redirect('/login', 'refresh');
    }

    /**
     * Create Session and login
     */
    public function login($userData)
    {
        $session = $this->socialMediaManager->createSession($userData->getIdMember());
        $em = $this->serviceContainer['entity_manager'];
        $user = $em->find('\EasyShop\Entities\EsMember', ['idMember' => $userData->getIdMember()]);
        $cartData = unserialize($user->getUserdata());
        $cartData = $cartData ? $cartData : array();

        $this->session->set_userdata('member_id', $userData->getIdMember());
        $this->session->set_userdata('usersession', $session);
        $this->session->set_userdata('cart_contents', $cartData);

        $session = $em->find('\EasyShop\Entities\CiSessions', ['sessionId' => $this->session->userdata('session_id')]);
        $authenticatedSession = new \EasyShop\Entities\EsAuthenticatedSession();
        $authenticatedSession->setMember($user)
            ->setSession($session);
        $em->persist($authenticatedSession);
        $em->flush();
    }


}

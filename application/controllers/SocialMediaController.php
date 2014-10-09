<?php

class SocialMediaController extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        session_start();
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
        if ($this->input->get('error')) {
            redirect('/login', 'refresh');
        }
        $facebookData = $this->socialMediaManager->getAccount(1);
        echo $facebookData->getProperty('email');
        if ($facebookData->getProperty('email')) {
            $validateFacebookData = $this->socialMediaManager->authenticateAccount($facebookData->getId(), 'Facebook');
            print "<pre>";
            print_r($validateFacebookData);
            print "</pre>";
            if (!$validateFacebookData) {
                echo '1';
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
                echo '2';
                $response = $validateFacebookData;
            }
            print "<pre>";
            print_r($response->getIdMember());
            print "</pre>";

//            $this->login($response);
//            redirect('/', 'refresh');
        }
        else {
            redirect('/login', 'refresh');
        }

    }

    /**
     * Register Google account to easyshop
     *
     */
    public function registerGoogleAccount()
    {
        if ($this->input->get('error') || !($this->input->get('code')))
        {
            redirect('/login', 'refresh');
        }
        else {
            $socialMedia = 2;
            $google = $this->socialMediaManager->getGoogleClient();
            $google->authenticate($this->input->get('code'));
            $this->session->set_userdata('access_token', $google->getAccessToken());
            if ($this->session->userdata('access_token')) {
                $google->setAccessToken($this->session->userdata('access_token'));
            }
            if ($google->getAccessToken())
            {
                $googleData = $this->socialMediaManager->getAccount($socialMedia);
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
        }
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

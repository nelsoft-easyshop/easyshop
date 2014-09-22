<?php

class socialMediaSetup extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        session_start();
        $this->load->config('thirdPartyConfig', TRUE);
        $this->socialMediaManager = $this->serviceContainer['social_media_manager'];
    }

    /**
     * Register Facebook account to easyshop
     * @return mixed Returns the member entity if exist or successfully registered and false if sharing of email was declined
     */
    public function registerFacebookUser()
    {
        if (isset($_REQUEST['error'])){
            redirect(base_url() . 'login', 'refresh');
        }
        $facebookData = $this->socialMediaManager->getAccount('facebook');
        $response = FALSE;
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
            redirect(base_url(), 'refresh');
        }
        else {
            redirect(base_url() . 'login', 'refresh');
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
            header('Location: /socialMediaSetup');
        }
        else {
            $google = $this->socialMediaManager->getGoogleClient();
            $google->authenticate($_GET['code']);
            $_SESSION['access_token'] = $google->getAccessToken();
            if (isset($_SESSION['access_token'])) {
                $google->setAccessToken($_SESSION['access_token']);
            }
            if ($google->getAccessToken())
            {
                $googleData = $this->socialMediaManager->getAccount('google');
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
                redirect(base_url(), 'refresh');
            }
        }
    }

    /**
     * Create Session and login
     */
    public function login($userData)
    {
        $this->load->model('cart_model');
        $this->load->model('user_model');
        $row = $this->user_model->VerifySocialMediaAccount($userData->getUsername(), $userData->getOauthId(), $userData->getOauthProvider());
        if ($row['o_success'] >= 1) {
            $this->session->set_userdata('member_id', $row['o_memberid']);
            $this->session->set_userdata('usersession', $row['o_session']);
            $this->session->set_userdata('cart_contents', $this->cart_model->cartdata($row['o_memberid'],$this->session->userdata('cart_contents')));

            $em = $this->serviceContainer['entity_manager'];
            $user = $em->find('\EasyShop\Entities\EsMember', ['idMember' => $row['o_memberid']]);
            $session = $em->find('\EasyShop\Entities\CiSessions', ['sessionId' => $this->session->userdata('session_id')]);

            $authenticatedSession = new \EasyShop\Entities\EsAuthenticatedSession();
            $authenticatedSession->setMember($user)
                ->setSession($session);
            $em->persist($authenticatedSession);
            $em->flush();
        }
    }
}
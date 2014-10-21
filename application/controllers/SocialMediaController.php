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
        $this->load->library('encrypt');
        $this->load->config('oauth', TRUE);
        $this->socialMediaManager = $this->serviceContainer['social_media_manager'];
        $this->entityManager = $this->serviceContainer['entity_manager'];
        $this->stringUtility = $this->serviceContainer['string_utility'];
        $this->emailNotification = $this->serviceContainer['email_notification'];
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

        $facebookType = $this->socialMediaManager->getFacebookTypeConstant();
        $facebookData = $this->socialMediaManager->getAccount($facebookType);
        if ($facebookData->getProperty('email')) {
            $esMember = $this->entityManager
                                ->getRepository('EasyShop\Entities\EsMember')
                                    ->findOneBy(['email' => $facebookData->getProperty('email')]);
            $isUsernameExists = $esMember->getUsername() ? true : false;
            if ($esMember && $isUsernameExists) {
                if (!$esMember->getOauthProvider()) {
                    $data = $this->encrypt->encode(
                        $esMember->getIdMember() . '~' . $facebookType
                    );
                    redirect('SocialMediaController/merge?h=' . $data, 'refresh');
                }
            }
            else {
                $username = $this->stringUtility->cleanString(strtolower($facebookData->getFirstName()));
                if (!$esMember && !$isUsernameExists) {
                    $esMember = $this->socialMediaManager->registerAccount(
                        $username,
                        $facebookData->getName(),
                        $facebookData->getProperty('gender') === 'male' ? 'M' : 'F',
                        $facebookData->getProperty('email'),
                        TRUE,
                        $facebookData->getId(),
                        $facebookType
                    );
                }

                if (!$esMember->getUsername()) {
                    $data = $this->encrypt->encode(
                        $esMember->getIdMember().
                        '~'.
                        $username
                    );
                    redirect('SocialMediaController/register?h=' . $data, 'refresh');
                }
            }
            $this->login($esMember);
            redirect('/', 'refresh');
        }
        
        redirect('/login', 'refresh');
    }

    /**
     * Register Google account to easyshop
     */
    public function registerGoogleAccount()
    {
        if ($this->input->get('error')) {
            redirect('/login', 'refresh');
        }

        $google = $this->socialMediaManager->getGoogleClient();  
        $googleType = $this->socialMediaManager->getGoogleTypeConstant();
        $httpRequest = $this->serviceContainer['http_request'];
        if ($this->input->get('code')) {
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
        
        if ($this->session->userdata('access_token')) {
            $google->setAccessToken($this->session->userdata('access_token'));
            $googleData = $this->socialMediaManager->getAccount($googleType);
            $esMember = $this->entityManager
                ->getRepository('EasyShop\Entities\EsMember')
                ->findOneBy(['email' => $googleData->getEmail()]);
            $isUsernameExists = $esMember->getUsername() ? true : false;
            if($esMember && !$isUsernameExists) {
                if (!$esMember->getOauthProvider()) {
                    $data = $this->encrypt->encode(
                        $esMember->getIdMember() . '~' . $googleType
                    );
                    redirect('SocialMediaController/merge?h=' . $data, 'refresh');
                }
            }
            else {
                $username = $this->stringUtility->cleanString(strtolower($googleData->getGivenName()));
                if (!$esMember && !$isUsernameExists) {
                    $response = $this->socialMediaManager->registerAccount(
                        $username,
                        $googleData->getName(),
                        '',
                        $googleData->getEmail(),
                        TRUE,
                        $googleData->getId(),
                        $googleType
                    );
                }

                if (!$esMember->getUsername()) {
                    $data = $this->encrypt->encode(
                        $esMember->getIdMember().
                        '~'.
                        $username
                    );
                    redirect('SocialMediaController/register?h=' . $data, 'refresh');
                }
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
        $user = $this->entityManager->find('\EasyShop\Entities\EsMember', ['idMember' => $userData->getIdMember()]);
        $cartData = $this->serviceContainer['cart_manager']->synchCart($user->getIdMember());;

        $this->session->set_userdata('member_id', $userData->getIdMember());
        $this->session->set_userdata('usersession', $session);
        $this->session->set_userdata('cart_contents', $cartData);
        
        $loginCount = $userData->getLoginCount();
        $userData->setLoginCount(intval($loginCount) + 1);
        $userData->setUsersession($session);
        $userData->setLastLoginDatetime(new DateTime('now'));
        $userData->setLastLoginIp($this->serviceContainer['http_request']->getClientIp());
        $userData->setFailedLoginCount(0);

        $session = $this->entityManager->find('\EasyShop\Entities\CiSessions', ['sessionId' => $this->session->userdata('session_id')]);
        $authenticatedSession = new \EasyShop\Entities\EsAuthenticatedSession();
        $authenticatedSession->setMember($user)
                             ->setSession($session);
        $this->entityManager->persist($authenticatedSession);
        $this->entityManager->flush();
    }

    /**
     * Show merge page
     */
    public function merge()
    {
        $this->load->library('parser');
        $hash = html_escape($this->input->get('h'));
        $enc = str_replace(" ", "+", $hash);
        $decrypted = $this->encrypt->decode($enc);
        $getdata = explode('~', $decrypted);
        if (intval($getdata[0]) === 0 || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }

        $data['member'] = $this->entityManager
            ->getRepository('EasyShop\Entities\EsMember')
            ->findOneBy([
                'idMember' => $getdata[0],
                'oauthProvider' => '',
                'oauthId' => '0'
            ]);
        $data['oauthProvider'] = $getdata[1];
        #Send Email notification
        $parseData = array(
            'username' => $data['member']->getUsername(),
            'hash' => $this->encrypt->encode($data['member']->getIdMember() . '~' . $data['oauthProvider']),
            'site_url' => site_url('SocialMediaController/mergeAccount')
        );
        $message = $this->parser->parse('templates/email_merge_account', $parseData, true);
        $this->emailNotification->setRecipient($data['member']->getEmail());
        $this->emailNotification->setSubject($this->lang->line('merge_subject'));
        $this->emailNotification->setMessage($message);
        $this->emailNotification->sendMail();

        $this->load->view('pages/user/SocialMediaMerge', $data);
    }

    /**
     * Merge account
     */
    public function mergeAccount()
    {
        $hash = html_escape($this->input->get('h'));
        $enc = str_replace(" ", "+", $hash);
        $decrypted = $this->encrypt->decode($enc);
        $getData = explode("~", $decrypted);
        $member = $this->entityManager
            ->getRepository('EasyShop\Entities\EsMember')
            ->findOneBy([
                'idMember' => $getData[0],
                'oauthProvider' => '',
                'oauthId' => '0'
            ]);

        if (intval($getData[0]) === 0 || !$member) {
            redirect('/login', 'refresh');
        }
        $member = $this->socialMediaManager->updateOauthProvider($member->getIdMember() ,$getData[1]);
        $this->login($member);
        redirect('/', 'refresh');
    }

    /**
     * Show update username page
     */
    public function register()
    {
        $hash = html_escape($this->input->get('h'));
        $enc = str_replace(' ', '+', $hash);
        $decrypt = $this->encrypt->decode($enc);
        $getData = explode('~', $decrypt);
        $member = $this->entityManager
            ->getRepository('EasyShop\Entities\EsMember')
            ->findOneBy([
                'idMember' => $getData[0],
                'oauthProvider' => '',
                'oauthId' => '0'
            ]);
        if (intval($getData[0]) === 0) {
            redirect('/login', 'refresh');
        }
        $data['member'] = $member;
        $data['invalidUsername'] = $getData[1];

        $this->load->view('pages/user/SocialMediaRegistration', $data);
    }

    /**
     * Update username
     * @return bool
     */
    public function updateUsername()
    {
        $result = false;
        $username = $this->stringUtility->cleanString(strtolower($this->input->get('username')));
        $esMember = $this->entityManager('EasyShop\Entities\EsMember')
                        ->findOneBy(['username' => $username]);
        if (!$esMember) {
            $result = $this->socialMediaManager->updateUsername($esMember->getIdMember(), $username);
        }

        return $result;
    }
}

#landing page for update user's username
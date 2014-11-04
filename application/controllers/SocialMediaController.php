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
            $data = $this->socialMediaManager
                            ->authenticateAccount($facebookData->getId(), $facebookType, $facebookData->getProperty('email'));
            $esMember = $data['doesAccountExists'];
            $doesAccountMerged = $data['doesAccountMerged'];
            if ($esMember && $doesAccountMerged) {
                $this->login($esMember);
                redirect('/', 'refresh');
            }
            else if ($esMember && !$doesAccountMerged) {
                $data = $this->encrypt->encode(
                                            $esMember->getIdMember().
                                            '~'.
                                            $facebookType.
                                            '~'.
                                            $facebookData->getId()
                                        );
                redirect('SocialMediaController/merge?h=' . $data, 'refresh');
            }
            else if (!$esMember) {
                $username = $this->stringUtility->cleanString(strtolower($facebookData->getFirstName()));
                $gender =  $facebookData->getProperty('gender') === 'male' ? 'M' : 'F';
                $data = $this->encrypt->encode(
                                            $facebookType.
                                            '~'.
                                            $facebookData->getId().
                                            '~'.
                                            $username.
                                            '~'.
                                            $facebookData->getName().
                                            '~'.
                                            $gender.
                                            '~'.
                                            $facebookData->getProperty('email')
                                        );
                redirect('SocialMediaController/register?h=' . $data, 'refresh');
            }
            else {
                redirect('/login', 'refresh');
            }
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
            $data = $this->socialMediaManager
                            ->authenticateAccount($googleData->getId(), $googleType, $googleData->getEmail());
            $esMember = $data['doesAccountExists'];
            $doesAccountMerged = $data['doesAccountMerged'];
            if ($esMember && $doesAccountMerged) {
                $this->login($esMember);
                redirect('/', 'refresh');
            }
            else if ($esMember && !$doesAccountMerged) {
                $data = $this->encrypt->encode(
                                            $esMember->getIdMember().
                                            '~'.
                                            $googleType.
                                            '~'.
                                            $googleData->getId()
                                        );
                redirect('SocialMediaController/merge?h=' . $data, 'refresh');
            }
            else if (!$esMember) {
                $username = $this->stringUtility->cleanString(strtolower($googleData->getGivenName()));
                $data = $this->encrypt->encode(
                                            $googleType.
                                            '~'.
                                            $googleData->getId().
                                            '~'.
                                            $username.
                                            '~'.
                                            $googleData->getName().
                                            '~'.
                                            ''.
                                            '~'.
                                            $googleData->getId()
                                        );
                redirect('SocialMediaController/register?h=' . $data, 'refresh');
            }
            else {
                redirect('/login', 'refresh');
            }
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
        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));

        if (intval($getData[0]) === 0 || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }

        $data['member'] = $this->entityManager
                                    ->getRepository('EasyShop\Entities\EsMember')
                                        ->findOneBy([
                                            'idMember' => $getData[0]
                                        ]);
        $data['oauthProvider'] = $getData[1];
        $data['oauthId'] = $getData[2];

        $this->load->view('pages/user/SocialMediaMerge', $data);
    }

    /**
     * Send email message
     * @param receiver
     * @param memberId
     * @param username
     * @param oauthId
     * @param oauthProvider
     */
    public function sendMergeNotification()
    {
        $this->load->library('parser');
        $parseData = array(
            'username' => $this->input->post('username'),
            'hash' => $this->encrypt->encode(
                                            $this->input->post('memberId') .
                                            '~' .
                                            $this->input->post('oauthId') .
                                            '~' .
                                            $this->input->post('oauthProvider')
                                        ),
            'site_url' => site_url('SocialMediaController/mergeAccount')
        );
        $message = $this->parser->parse('templates/email_merge_account', $parseData, true);
        $this->emailNotification->setRecipient($this->input->post('receiver'));
        $this->emailNotification->setSubject($this->lang->line('merge_subject'));
        $this->emailNotification->setMessage($message);
        $this->emailNotification->sendMail();
    }

    /**
     * Merge account
     */
    public function mergeAccount()
    {
        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));
        $memberObj = $this->entityManager
                            ->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy([
                                    'idMember' => $getData[0]
                                ]);
        $socialMediaProvider = $this->entityManager
                                        ->getRepository('EasyShop\Entities\EsSocialMediaProvider')
                                            ->find($getData[2]);
        if (intval($getData[0]) === 0 || !$memberObj || !$this->input->get('h') || !$socialMediaProvider) {
            redirect('/login', 'refresh');
        }

        $member = $this->socialMediaManager->mergeAccount($memberObj, $getData[1], $socialMediaProvider);
        $this->login($member);
        redirect('/', 'refresh');
    }

    /**
     * Show update username page
     */
    public function register()
    {
        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));
        if (intval($getData[0]) === 0 || !isset($getData[1]) || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }
        $data = array (
            'social_media_type'=> $getData[0],
            'social_media_id'=> $getData[1],
            'username'=> $getData[2],
            'fullname'=> $getData[3],
            'gender'=> $getData[4],
            'email'=> $getData[5]
        );

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
            $result = $this->entityManager('EasyShop\Entities\EsMember')->updateUsername($esMember, $username);
        }

        return $result;
    }
    
     public function sendMergeAccountEmail()
    {
        $socialMediaLinks = $this->getSocialMediaLinks();
        $viewData['facebook'] = $socialMediaLinks["facebook"];
        $viewData['twitter'] = $socialMediaLinks["twitter"];        
        $this->load->view('templates/header_new'); //must be templates/header_primary
        $this->load->view('pages/user/send-merge-email');
        $this->load->view('templates/footer_primary', $viewData);
    }

    public function sendMergeAccountUsername()
    {
        $socialMediaLinks = $this->getSocialMediaLinks();
        $viewData['facebook'] = $socialMediaLinks["facebook"];
        $viewData['twitter'] = $socialMediaLinks["twitter"]; 

        $this->load->view('templates/header_new'); //must be templates/header_primary
        $this->load->view('pages/user/send-merge-username');
        $this->load->view('templates/footer_primary', $viewData);
    }
}

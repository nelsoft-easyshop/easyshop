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
        $this->userManager = $this->serviceContainer['user_manager'];
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
            $esMember = $data['getMember'];
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
                redirect('SocialMediaController/mergeEmail?h=' . $data, 'refresh');
            }
            else if (!$esMember && !$doesAccountMerged) {
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
            $esMember = $data['getMember'];
            $doesAccountMerged = $data['doesAccountMerged'];
            if ($esMember && $doesAccountMerged) {
                $esMember = $this->socialMediaManager->fixSocialMediaEmail($esMember, $googleData->getEmail());
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
                redirect('SocialMediaController/mergeEmail?h=' . $data, 'refresh');
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
                                            $googleData->getEmail()
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
    public function mergeEmail()
    {
        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));

        if (intval($getData[0]) === 0 || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }

        $data = array(
            'title' => ' Shopping made easy | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
        );
        $data = array_merge($data, $this->fill_header());
        $data['member'] = $this->entityManager
                                    ->getRepository('EasyShop\Entities\EsMember')
                                    ->findOneBy([
                                        'idMember' => $getData[0]
                                    ]);
        $data['oauthProvider'] = $getData[1];
        $data['oauthId'] = $getData[2];

        $socialMediaLinks = $this->config->load('social_media_links', TRUE);
        $footerData = [ 'facebook' => $socialMediaLinks["facebook"],
                        'twitter' => $socialMediaLinks["twitter"], ];
        $this->load->view('templates/header_new', $data);
        $this->load->view('pages/user/SocialMediaMerge', $data);
        $this->load->view('templates/footer_primary', $footerData);
    }

    /**
     * Send email message
     * @param email
     * @param memberId
     * @param oauthId
     * @param oauthProvider
     * @return boolean
     */
    public function sendMergeNotification()
    {
        $result = false;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                        ->findOneBy(['email' => $this->input->post('email')]);
        $socialMediaLinks = $this->getSocialMediaLinks();
        if ($member) {
            $result = true;
            $this->load->library('parser');
            $parseData = array(
                'username' => $member->getUsername(),
                'hash' => $this->encrypt->encode(
                    $member->getIdMember() .
                    '~' .
                    $this->input->post('oauthId') .
                    '~' .
                    $this->input->post('oauthProvider')
                ),
                'site_url' => site_url('SocialMediaController/mergeAccount'),
                'error_in' => $this->input->post('error'),
                'facebook' => $socialMediaLinks["facebook"],
                'twitter' => $socialMediaLinks["twitter"]

            );
            $images = array("/assets/images/landingpage/templates/facebook.png",
                "/assets/images/landingpage/templates/twitter.png",
                "/assets/images/appbar.home.png",
                "/assets/images/appbar.message.png",
                "/assets/images/landingpage/templates/header-img.png");

            $message = $this->parser->parse('emails/merge-account', $parseData, true);
            $this->emailNotification->setRecipient($member->getEmail());
            $this->emailNotification->setSubject($this->lang->line('merge_subject'));
            $this->emailNotification->setMessage($message, $images);
            $this->emailNotification->sendMail();
        }

        echo json_encode($result);
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
        $doesSocialMediaAccountExists = $this->entityManager
                                            ->getRepository('EasyShop\Entities\EsMemberMerge')
                                            ->findOneBy([
                                                'socialMediaId' => $getData[1],
                                                'socialMediaProvider' => $getData[2]
                                            ]);
        if (intval($getData[0]) === 0 || !$memberObj || !$this->input->get('h') || !$socialMediaProvider || $doesSocialMediaAccountExists) {
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

        $doesSocialMediaAccountExists = $this->entityManager
                                            ->getRepository('EasyShop\Entities\EsMemberMerge')
                                            ->findOneBy([
                                                'socialMediaId' => $getData[1],
                                                'socialMediaProvider' => $getData[2]
                                            ]);
        if ($doesSocialMediaAccountExists) {
            redirect('/', 'refresh');
        }

        $data = array(
            'title' => ' Shopping made easy | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
        );
        $data = array_merge($data, $this->fill_header());
        $userData = array (
            'social_media_type'=> $getData[0],
            'social_media_id'=> $getData[1],
            'username'=> $getData[2],
            'fullname'=> $getData[3],
            'gender'=> $getData[4],
            'email'=> $getData[5]
        );
        
        $socialMediaLinks = $this->config->load('social_media_links', TRUE);
        $footerData = [ 'facebook' => $socialMediaLinks["facebook"],
                    'twitter' => $socialMediaLinks["twitter"], ];
        $this->load->view('templates/header_new', $data);
        $this->load->view('pages/user/SocialMediaRegistration', $userData);
        $this->load->view('templates/footer_primary', $footerData);
    }

    /**
     * Register Social Media Account
     * @return mixed
     */
    public function registerSocialMediaAccount()
    {
        $result = false;
        $username = $this->stringUtility->cleanString(strtolower($this->input->post('username')));
        $esMember = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                            ->findOneBy(['username' => $username]);
        $socialMediaProvider = $this->entityManager
                                    ->getRepository('EasyShop\Entities\EsSocialMediaProvider')
                                    ->find($this->input->post('provider'));
        if (!$esMember && $socialMediaProvider) {
            $result = $this->socialMediaManager->registerAccount(
                                                    $username,
                                                    $this->input->post('fname'),
                                                    $this->input->post('gender'),
                                                    $this->input->post('email'),
                                                    true,
                                                    $this->input->post('id'),
                                                    $socialMediaProvider
                                                );

            if ($result) {
                $this->login($result);
            }
            else {
                $result = 'Invalid Username';
            }
        }

        echo json_encode($result);
    }

    /**
     * Check if email exists
     * @param email
     * @return boolean
     */
    public function checkEmailAvailability()
    {
        $result = false;
        $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                      ->findOneBy(['email' => $this->input->post('email')]);
        if ($member) {
            $result = array(
                'username' => $member->getUsername(),
                'email' => $member->getEmail(),
                'location' => '',
                'image' =>  $this->userManager->getUserImage($member->getIdMember())
            );
        }

        echo json_encode($result);
    }
}

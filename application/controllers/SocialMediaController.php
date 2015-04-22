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

        $facebookType = \EasyShop\Entities\EsSocialMediaProvider::FACEBOOK;
        $facebookData = $this->socialMediaManager->getAccount($facebookType);        
        if($facebookData ){    
            $facebookEmail = $facebookData->getProperty('email');
            if ($facebookEmail) {
                $authenticationResult = $this->socialMediaManager
                                             ->authenticateAccount($facebookData->getId(), $facebookType, $facebookEmail);
                $esMember = $authenticationResult['getMember'];
                $isAccountMerged = $authenticationResult['isAccountMerged'];
                if ($esMember && $isAccountMerged) {
                    $this->socialMediaManager->mergeOldSocialMediaAccountToNew($facebookData->getId(), $facebookType, $esMember);
                    $this->login($esMember);
                    redirect('/', 'refresh');
                }
                else if ($esMember && $isAccountMerged === false) {
                    $data = serialize([
                        'memberId' => $esMember->getIdMember(),
                        'socialMediaProvider' => $facebookType,
                        'socialMediaId' => $facebookData->getId(),
                    ]);
                    $hashedData = $this->encrypt->encode($data);
                    redirect('SocialMediaController/mergeEmail?h=' . $hashedData, 'refresh');
                }
                else if (!$esMember && $isAccountMerged === false) {
                    $facebookUsername = $this->stringUtility->cleanString(strtolower($facebookData->getFirstName()));
                    $facebookFullname = $facebookData->getName();
                    $gender =  strtolower($facebookData->getProperty('gender')) === 'male' ? 'M' : 'F';
                    $facebookId = $facebookData->getId();
                    $data = serialize([
                        'socialMediaProvider' => $facebookType,
                        'socialMediaId' => $facebookId,
                        'username' => $facebookUsername,
                        'fullname' => $facebookFullname,
                        'gender' => $gender,
                        'email' => $facebookEmail,
                    ]);
                    $hashedData = $this->encrypt->encode($data);
                    redirect('SocialMediaController/register?h=' . $hashedData, 'refresh');
                }
            }
            else{
                /**
                 * For users with no email address (registered using contact no)
                 */
                $authenticationResult = $this->socialMediaManager
                                             ->authenticateAccount($facebookData->getId(), $facebookType, null);
                $esMember = $authenticationResult['getMember'];
                $isAccountMerged = $authenticationResult['isAccountMerged'];
                if($isAccountMerged !== false){
                    $this->login($esMember);
                    redirect('/', 'refresh');
                }
                else{
                    $facebookUsername = $this->stringUtility->cleanString(strtolower($facebookData->getFirstName()));
                    $facebookFullname = $facebookData->getName();
                    $gender =  strtolower($facebookData->getProperty('gender')) === 'male' ? 'M' : 'F';
                    $facebookId = $facebookData->getId();;
                    $data = serialize([
                        'socialMediaProvider' => $facebookType,
                        'socialMediaId' => $facebookId,
                        'username' => $facebookUsername,
                        'fullname' => $facebookFullname,
                        'gender' => $gender,
                    ]);
                    $hashedData = $this->encrypt->encode($data);
                    redirect('/SocialMediaController/register?h=' . $hashedData, 'refresh');
                }
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
        $googleType = \EasyShop\Entities\EsSocialMediaProvider::GOOGLE;
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
            $isAccountMerged = $data['isAccountMerged'];
            if ($esMember && $isAccountMerged) {
                $this->socialMediaManager->mergeOldSocialMediaAccountToNew($googleData->getId(), $googleType, $esMember);
                $esMember = $this->socialMediaManager->fixSocialMediaEmail($esMember, $googleData->getEmail());
                $this->login($esMember);
                redirect('/', 'refresh');
            }
            else if ($esMember && $isAccountMerged === false) {
                $data = serialize([
                    'memberId' => $esMember->getIdMember(),
                    'socialMediaProvider' => $googleType,
                    'socialMediaId' => $googleData->getId(),
                ]);
                $hashedData = $this->encrypt->encode($data);
                redirect('SocialMediaController/mergeEmail?h=' . $hashedData, 'refresh');
            }
            else if (!$esMember) {
                $username = $this->stringUtility->cleanString(strtolower($googleData->getGivenName()));
                $data = serialize([
                    'socialMediaProvider' => $googleType,
                    'socialMediaId' => $googleData->getId(),
                    'username' => $username,
                    'fullname' => $googleData->getName(),
                    'gender' => '',
                    'email' => $googleData->getEmail()
                ]);
                $hashedData = $this->encrypt->encode($data);
                redirect('SocialMediaController/register?h=' . $hashedData, 'refresh');
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
    private function login($userData)
    {
        $session = $this->socialMediaManager->createSession($userData->getIdMember());
        $user = $this->entityManager->find('\EasyShop\Entities\EsMember', ['idMember' => $userData->getIdMember()]);
        $cartData = $this->serviceContainer['cart_manager']->synchCart($user->getIdMember());;

        $this->session->set_userdata('member_id', $userData->getIdMember());
        $this->session->set_userdata('usersession', $session);
        $this->session->set_userdata('cart_contents', $cartData);

        $this->serviceContainer['account_manager']->updateUserLoginDetails($user);
    }

    /**
     * Show merge page
     */
    public function mergeEmail()
    {
        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));
        if (intval($getData['socialMediaId']) === 0 || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => ' Shopping made easy | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
        ];
        $data['member'] = $this->entityManager
                               ->getRepository('EasyShop\Entities\EsMember')
                               ->find($getData['memberId']);
        $data['oauthProvider'] = $getData['socialMediaProvider'];
        $data['oauthId'] = $getData['socialMediaId'];

        $this->load->spark('decorator');    
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/user/SocialMediaMerge', $data);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));  
        
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
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        if ($member) {
            $result = true;
            $this->load->library('parser');
            $data = serialize([
                'memberId' => $member->getIdMember(),
                'socialMediaId' => $this->input->post('oauthId'),
                'socialMediaProvider' => $this->input->post('oauthProvider')
            ]);
            $parseData = [
                'username' => $member->getUsername(),
                'facebook' => $socialMediaLinks["facebook"],
                'twitter' => $socialMediaLinks["twitter"],
                'baseUrl' => base_url(),
                'mergeLink' => site_url('SocialMediaController/mergeAccount').'?h='.$this->encrypt->encode($data)
            ];

            $this->config->load('email', true);
            $imageArray = $this->config->config['images'];
            $message = $this->parser->parse('emails/merge-account', $parseData, true);
            $this->emailNotification->setRecipient($member->getEmail());
            $this->emailNotification->setSubject($this->lang->line('merge_subject'));
            $this->emailNotification->setMessage($message, $imageArray);
            $this->emailNotification->queueMail();
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
                            ->find($getData['memberId']);
        $socialMediaProvider = $this->entityManager
                                    ->getRepository('EasyShop\Entities\EsSocialMediaProvider')
                                    ->find($getData['socialMediaProvider']);
        $doesSocialMediaAccountExists = $this->entityManager
                                             ->getRepository('EasyShop\Entities\EsMemberMerge')
                                             ->findOneBy([
                                                 'socialMediaId' => $getData['socialMediaId'],
                                                 'socialMediaProvider' => $getData['socialMediaProvider']
                                             ]);

        if (intval($getData['socialMediaId']) === 0 || !$memberObj || !$this->input->get('h') || !$socialMediaProvider || $doesSocialMediaAccountExists) {
            redirect('/login', 'refresh');
        }
        $member = $this->socialMediaManager->mergeAccount($memberObj, $getData['socialMediaId'], $socialMediaProvider);
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
        if (intval($getData['socialMediaProvider']) === 0 || !isset($getData['socialMediaProvider']) || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }

        $doesSocialMediaAccountExists = $this->entityManager
                                             ->getRepository('EasyShop\Entities\EsMemberMerge')
                                             ->findOneBy([
                                                 'socialMediaId' => $getData['socialMediaId'],
                                                 'socialMediaProvider' => $getData['socialMediaProvider']
                                             ]);
        if ($doesSocialMediaAccountExists) {
            redirect('/', 'refresh');
        }

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => ' Shopping made easy | Easyshop.ph',
            'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
        ];
        
        $userData = [
            'social_media_type'=> $getData['socialMediaProvider'],
            'social_media_id'=> $getData['socialMediaId'],
            'username'=> $getData['username'],
            'fullname'=> $getData['fullname'],
            'gender'=> $getData['gender'],
            'email'=> isset($getData['email']) ? $getData['email'] : '',
        ];
        
        $this->load->spark('decorator');    
        $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/user/SocialMediaRegistration', $userData);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));  
    }

    /**
     * Register Social Media Account
     *
     * @return JSON
     */
    public function registerSocialMediaAccount()
    {
        $jsonResult = [
            'isSuccessful' => false,
            'errors' => [],
        ];
        $username = $this->stringUtility->cleanString(strtolower($this->input->post('username')));
        $socialMediaProvider = $this->entityManager
                                    ->getRepository('EasyShop\Entities\EsSocialMediaProvider')
                                    ->find($this->input->post('provider'));
        $gender = $this->input->post('gender') ? $this->input->post('gender') : 'M';
        if ($socialMediaProvider) {
            $result = $this->socialMediaManager->registerAccount(
                                                    $username,
                                                    $this->input->post('fname'),
                                                    $gender,
                                                    $this->input->post('email'),
                                                    true,
                                                    $this->input->post('id'),
                                                    $socialMediaProvider
                                                );
            if (empty($result['errors']) && $result['member']) {
                $this->login($result['member']);
                $jsonResult['isSuccessful'] = true;
            }
            else {
                $jsonResult['errors'] = $result['errors'];
            }
        }

        echo json_encode($jsonResult);
    }

    /**
     * Check if email exists
     * @param email
     * @return boolean
     */
    public function checkEmailAvailability()
    {
        if($this->input->post('email')){
            $result = false;
            $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                          ->findOneBy([
                                                'email' => $this->input->post('email')
                                           ]);
            if ($member) { 
                $isMerge = $this->entityManager->getRepository('EasyShop\Entities\EsMemberMerge')
                                               ->isMemberMerged($member->getIdMember());
                $result = [
                    'email' => $member->getEmail(),
                    'image' =>  $this->userManager->getUserImage($member->getIdMember()),
                    'isMerged' => $isMerge,
                ];
            }
        }
        echo json_encode($result);
    }

}

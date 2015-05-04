<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MY_Controller 
{

    private $throttleService;

    /**
     * Inject dependencies
     *
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('register_model');
        $this->load->model('user_model');
        $this->load->model('cart_model');
        $this->load->library('encrypt');
        session_start();
        $this->load->config('oauth', TRUE);
        $this->socialMediaManager = $this->serviceContainer['social_media_manager'];
        $this->throttleService = $this->serviceContainer['login_throttler'];
        $this->accountManager = $this->serviceContainer['account_manager'];
    }
    
    /**
     * Renders the login page
     */
    public function index() 
    {
        if ($this->session->userdata('usersession')) {
            redirect('/');
        }
        $redirectUrl = $this->session->userdata('uri_string');
        $isPromo = false;
        if (strpos($redirectUrl, 'ScratchCard') !== false || strpos($redirectUrl, 'estudyantrepreneur') !== false ) {
            $isPromo = true;
        }

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Easyshop.ph - Welcome to Easyshop.ph',
            'metadescription' => 'Sign-in at Easyshop.ph to start your buying and selling experience.',
            'relCanonical' => base_url().'login',
            'renderSearchbar' => false,
        ];
        $this->load->config('officeoperation', true);
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $facebookScope = $this->config->item('facebook', 'oauth');
        $googleScope = $this->config->item('google', 'oauth');
        $daysInWeek = $this->config->item('dayRange', 'officeoperation');
        $firstDayOfWeek = date('D', strtotime("Sunday + ".$daysInWeek[0]." days"));
        $lastDayOfWeek = date('D', strtotime("Sunday + ".$daysInWeek[1]." days"));
        $hoursInDay = $this->config->item('hourRange', 'officeoperation');

        $bodyData = [
            'redirect_url' => $redirectUrl,
            'is_promo' => $isPromo,
            'facebook' => $socialMediaLinks["facebook"],
            'twitter' => $socialMediaLinks["twitter"],
            'facebook_login_url' =>$this->socialMediaManager
                                        ->getLoginUrl(\EasyShop\Entities\EsSocialMediaProvider::FACEBOOK,
                                            $facebookScope['permission_to_access']),
            'google_login_url' => $this->socialMediaManager
                                       ->getLoginUrl(\EasyShop\Entities\EsSocialMediaProvider::GOOGLE,
                                           $googleScope['permission_to_access']),
            'officeContactNo' => $this->config->item('contactno', 'officeoperation'),
            'dayRange' => $firstDayOfWeek.' to '.$lastDayOfWeek,
            'hourRange' => date('h:i A', strtotime($hoursInDay[0])).' to '.date('h:i A', strtotime($hoursInDay[1])),
        ];
        $loginData = [];

        if ($this->input->post('login_form')) {

            if ($this->form_validation->run('login_form')) {
                $uname = $this->input->post('login_username');
                $pass = $this->input->post('login_password');
                $loginData = $this->login($uname, $pass);
            }

            if (isset($loginData['o_success']) && $loginData['o_success'] >= 1) {
                redirect('/');
            }
            else {
                if (array_key_exists('timeoutLeft', $loginData) && $loginData['timeoutLeft'] >= 1) {
                    $bodyData['loginFail'] = true;
                    $bodyData['timeoutLeft'] = $loginData['timeoutLeft'];
                }
            }  
        }
        
        $bodyData = array_merge($bodyData, $loginData);

        $this->load->spark('decorator');
        $this->load->view('pages/user/register', array_merge($this->decorator->decorate('header', 'view', $headerData), $bodyData));
      }

    /**
     * Authenticates if the user is able to login succesfully or not
     *
     * @param string $login_uername
     * @param string $login_password
     * @return JSON
     */
    public function authenticate() 
    {
        if(($this->input->post('login_form'))&&($this->form_validation->run('login_form'))){
            $uname = $this->input->post('login_username');
            $pass = $this->input->post('login_password');	
            $row =  $this->login($uname, $pass);
            echo json_encode($row);
        }
    }
  
    
    /**  
     * Function for creating sessions and making database changes upon login
     *  
     * @param string $uname
     * @param string $pass
     */
    private function login($uname, $pass)
    {
        // if user still has timeout left, do not process this login attempt
        if($this->throttleService->getTimeoutLeft($uname) >= 1){
            $authenticationResult['o_success'] = 0;
            $authenticationResult['timeoutLeft'] = $this->throttleService->getTimeoutLeft($uname);
        }
        else{         
            $authenticationResult = $this->accountManager->authenticateMember($uname, $pass);

            if (empty($authenticationResult["member"]) === false) {

                /**
                 * Force codeigniter to reissue new session to prevent session fixation attacks
                 */
                $this->session->sess_destroy();
                $this->session->sess_create();
                
                $memberId =  $authenticationResult["member"]->getIdMember();
                
                $authenticationResult['o_success'] = 1;
                $authenticationResult["o_memberid"] = $memberId;
                $authenticationResult["o_session"] = $this->accountManager->generateUsersessionId($memberId);

                $em = $this->serviceContainer['entity_manager'];
                $cartManager = $this->serviceContainer['cart_manager'];
                $user = $em->find('\EasyShop\Entities\EsMember', ['idMember' => $memberId]);
                $session = $em->find('\EasyShop\Entities\CiSessions', ['sessionId' => $this->session->userdata('session_id')]);
                $cartData = $cartManager->synchCart($user->getIdMember());

                $this->session->set_userdata('member_id', $authenticationResult['o_memberid']);
                $this->session->set_userdata('usersession', $authenticationResult['o_session']);
                $this->session->set_userdata('cart_contents', $cartData);

                if($this->input->post('keepmeloggedin') == 'on'){
                    $member = $em->find('\EasyShop\Entities\EsMember', ['idMember' => $memberId]);
                    $ipAddress = $this->session->userdata('ip_address');
                    $userAgent = $this->session->userdata('user_agent');
                    $cisessionId = $this->session->userdata('session_id');
                    $newToken = $this->accountManager->persistRememberMeCookie($member, $ipAddress, $userAgent, $cisessionId);
                    $cookiedata = [
                        'name' => 'es_usr',
                        'value' => $newToken,
                        'expire' => EasyShop\Account\AccountManager::REMEMBER_ME_COOKIE_LIFESPAN_IN_SEC,
                    ];
                    set_cookie($cookiedata);
                }
          
                /**
                 * Register authenticated session
                 */
                $user->setUsersession($authenticationResult["o_session"] );
                $authenticatedSession = new \EasyShop\Entities\EsAuthenticatedSession();
                $authenticatedSession->setMember($user)
                                     ->setSession($session);
                $em->persist($authenticatedSession);
                $em->flush();
                /**
                 * Save JWT token to the session
                 */
                $jwtData = [
                    "iss" => base_url(),
                    "aud" => base_url(),
                    "iat" => time(),
                    'sub' => $user->getIdMember(),
                    'storename' => $user->getStorename(),
                ];
                $jwtSecret = $this->serviceContainer['message_manager']
                                  ->getWebTokenSecret();
                $jwtToken = $this->serviceContainer['json_web_token']
                                 ->encode($jwtData, $jwtSecret);
                $this->session->set_userdata('jwtToken', $jwtToken);
                /**
                 * Clean data for outputting
                 */
                unset($authenticationResult['member']);
                unset($authenticationResult['o_memberid']);
                unset($authenticationResult['o_session']);
            }
            else{ 
                $this->throttleService->logFailedAttempt($uname);
                $this->throttleService->updateMemberAttempt($uname);
                $authenticationResult['timeoutLeft'] = $this->throttleService->getTimeoutLeft($uname);
                $authenticationResult['o_message'] = $authenticationResult["errors"][0]["login"];
                $authenticationResult['o_success'] = 0;
            }
        }

        return $authenticationResult;
    }
    
    /**
     * Log-outs a user by destroying the pertinent session details
     *
     */
    public function logout() 
    {
        $cart_items = serialize($this->session->userdata('cart_contents'));
        $memberId = $this->session->userdata('member_id');        
        $this->cart_model->save_cartitems($cart_items,$memberId);
        $this->user_model->logout();

        $ipAddress = $this->session->userdata('ip_address');
        $useragent = $this->session->userdata('user_agent');
        $token = get_cookie('es_usr');
        $this->accountManager->unpersistRememberMeCookie($memberId, $ipAddress, $useragent, $token);
        delete_cookie('es_usr');        
        delete_cookie('es_vendor_subscribe');

        $sessionData = $this->session->all_userdata();
        foreach ($sessionData as $key => $sessionField) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        
        $referrer = $this->input->get('referrer');
        if(trim($referrer)){
            redirect('/'.$referrer);
        }
        else{
            redirect('/login');
        }
    }

    
    /**
     * Renders the forgot password page where the user provides a valid email
     *
     */
    public function identifyEmail()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Reset your Password | Easyshop.ph',
            'metadescription' => '',
            'relCanonical' => '',
        ];
        
        $bodyData = [
            'isPost' => false,
            'isSuccessful' => false,
            'message' => '',
        ];
        
        if($this->input->post()){
            $bodyData['isPost'] = true;
            $em = $this->serviceContainer['entity_manager'];
            $rules = $this->serviceContainer['form_validation']->getRules('reset_password')['email'];
            $formFactory = $this->serviceContainer['form_factory'];
            $formErrorHelper = $this->serviceContainer['form_error_helper']; 

            $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                ->setMethod('POST')
                                ->add('email', 'text', ['required' => false, 'label' => false, 'constraints' => $rules])
                                ->getForm();
                                
        
            $form->submit([ 'email' => $this->input->post('email')]);
            if ($form->isValid()) {
                $member = $em->getRepository('EasyShop\Entities\EsMember')
                             ->findOneBy(['email' => $this->input->post('email')]);
                if($member){
                    $isEmailSent = $this->serviceContainer['account_manager']
                                        ->sendForgotPasswordLink($member);
                    $bodyData['isSuccessful'] = $isEmailSent;
                    if(!$isEmailSent){
                        $bodyData['message'][] = "We can't send an email at this time.";
                    }
                }
                else{
                    $bodyData['message'][] = "The email you provided is unregistered." ;
                }
            }
            else{
                $bodyData['message'] = $formErrorHelper->getFormErrors($form)['email'];
            }
        }
    
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/user/forgotpass', $bodyData);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }
    

    /**
     * Reset the password of a user
     *
     *
     */
    public function updatePassword()
    {
        $minPasswordLength = 6;
        $passwordRules = $this->serviceContainer['form_validation']->getRules('register')['password'];
        $hashRules = $this->serviceContainer['form_validation']->getRules('reset_password')['hash'];
        
        $bodyData = [
            'isSuccessful' => false,
            'isPost' => false,
            'message' => '',
            'isLoggedin' => $this->session->userdata('usersession'),
            'hash' => $this->input->get('confirm'),
        ];
        
        if($this->input->post()){
            $bodyData['isPost'] = true;
            $invalidLinkMessage = "The link that you have provided is either invalid or already expired.";
            if(trim($this->input->post('password')) === trim($this->input->post('confirmpassword'))){
            
                $em = $this->serviceContainer['entity_manager'];
                $formFactory = $this->serviceContainer['form_factory'];
                $formErrorHelper = $this->serviceContainer['form_error_helper']; 
                $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                    ->setMethod('POST')
                                    ->add('password', 'text', ['required' => false, 'label' => false, 'constraints' => $passwordRules])
                                    ->add('hash', 'text',['required' => false, 'label' => false, 'constraints' => $hashRules])
                                    ->getForm();
                $form->submit([ 
                    'password' => $this->input->post('password'),
                    'hash' => $this->input->post('hash'),
                ]);
                if ($form->isValid()) {
                    $formData = $form->getData();
                    $validatePassword =  $formData['password'];
                    $validatedHash = $formData['hash'];
                    $validationResult = $this->serviceContainer['account_manager']
                                             ->validatePasswordReset($validatePassword, $validatedHash);                          
                    $bodyData['user'] = $validationResult['member'];                                     
                    $bodyData['isSuccessful'] = $validationResult['isSuccessful'];
                    if(!$bodyData['isSuccessful']){
                        $bodyData['message'][] = $invalidLinkMessage;
                    }
              
                }
                else{
                    $bodyData['message'] = isset($formErrorHelper->getFormErrors($form)['password']) ? $formErrorHelper->getFormErrors($form)['password'] : [ $invalidLinkMessage ];
                }
            }
            else{
                $bodyData['message'][] = "The passwords that you entered do not match";
            }
            $bodyData['hash'] = $this->input->post('hash');
        }
    
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Reset your Password | Easyshop.ph',
            'metadescription' => '',
            'relCanonical' => '',
        ];
        foreach($passwordRules as $passwordRule){
            if($passwordRule instanceof \Symfony\Component\Validator\Constraints\Length){
                $minPasswordLength = $passwordRule->min;
                break;
            }
        }

     
        $bodyData['minPasswordLength'] = $minPasswordLength;
        
        $this->load->spark('decorator');  
        $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/user/forgotpass_update_password', $bodyData);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
    }

    

}


/* End of file login.php */
/* Location: ./application/controllers/login.php */
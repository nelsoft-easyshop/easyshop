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
    public function __construct() {
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
     *
     */
    public function index() 
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Login | Easyshop.ph',
            'metadescription' => 'Sign-in at Easyshop.ph to start your buying and selling experience.',
            'relCanonical' => base_url().'login',
            'renderSearchbar' => false,
        ];
        $bodyData['url'] = $this->session->userdata('uri_string');
        $facebookScope = $this->config->item('facebook', 'oauth');
        $googleScope = $this->config->item('google', 'oauth');
        $bodyData['facebook_login_url'] = $this->socialMediaManager
                                                ->getLoginUrl(EasyShop\SocialMedia\SocialMediaManager::FACEBOOK, 
                                                              $facebookScope['permission_to_access']);
        $bodyData['google_login_url'] = $this->socialMediaManager
                                             ->getLoginUrl(EasyShop\SocialMedia\SocialMediaManager::GOOGLE,
                                                           $googleScope['permission_to_access']);
        $loginData = [];

        if($this->input->post('login_form')){
            if($this->form_validation->run('login_form')){
                $uname = $this->input->post('login_username');
                $pass = $this->input->post('login_password');
                $loginData = $this->login($uname, $pass);
            }
            if(isset($loginData['o_success']) && $loginData['o_success'] >= 1){
                redirect('/');
            }
            else{
                if(array_key_exists('timeoutLeft', $loginData) && $loginData['timeoutLeft'] >= 1){
                    $bodyData['loginFail'] = true;
                    $bodyData['timeoutLeft'] = $loginData['timeoutLeft'];
                }
            }  
        }
        
        $bodyData = array_merge($bodyData, $loginData);

        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/user/login_view',$bodyData);
        $this->load->view('templates/footer');
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
     * Display the reset password page
     *
     * @return View
     */
    public function identify()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Forgot Password | Easyshop.ph',
            'metadescription' => '',
            'relCanonical' => '',
            'render_searchbar' => false,
        ];
            
        $bodyData['toggle_view'] = "";
        if(($this->input->post('identify_btn')) && ($this->form_validation->run('identify_form'))){
                $email = $this->input->post('email');
                $result = $this->register_model->check_registered_email($email);
            if (isset($result['username'])){
                // Send email and update database 
                if ($this->register_model->forgotpass($email, $result['username'], $result['id_member']) == 1){
                    $bodyData['toggle_view'] = "1";
                }else{
                    $bodyData['toggle_view'] = "3";
                }
            }else{
                $bodyData['toggle_view'] = "2";
            }
        }
        
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));
        $this->load->view('pages/user/forgotpass', $bodyData);
        $this->load->view('templates/footer');	
    }

    public function resetconfirm()
    {
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Reset Password | Easyshop.ph',
            'metadescription' => '',
            'relCanonical' => '',
            'render_searchbar' => false,
        ];
        $bodyData['toggle_view'] = '';
        if($this->input->post()){
            $bodyData['toggle_view'] = $this->input->post('tgv');
        }
        else{
            $bodyData['hash'] = $this->input->get('confirm');
        }      
        $this->load->spark('decorator');  
        $this->load->view('templates/header', $this->decorator->decorate('header', 'view', $headerData));	
        $this->load->view('pages/user/forgotpass_confirm', $bodyData);
        $this->load->view('templates/footer');
    }

    public function xresetconfirm()
    {
        $pass = $this->input->post('password');
        $hash = $this->input->post('hash');
        $result = $this->register_model->forgotpass_email($hash);
        if(isset($pass) && !empty($pass) && $this->form_validation->run('forgotpass')){
            if(isset($result['username'])){
                $user = $result['username'];
                $curpass = $result['password'];
                $mid = $result['member_id'];			
                $data = array(
                        'username' => $user,
                        'cur_password' => $curpass,
                        'member_id' => $mid,
                        'password' => $pass
                );
                $this->register_model->forgotpass_update($data); 
                echo "1";	
            }else{
                echo "0";
            }
        }else{
            echo "0";
        }
    }

}


/* End of file login.php */
/* Location: ./application/controllers/login.php */
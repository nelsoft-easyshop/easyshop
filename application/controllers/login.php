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
            'title' => 'Login | Easyshop.ph',
            'metadescription' => 'Sign-in at Easyshop.ph to start your buying and selling experience.',
            'relCanonical' => base_url().'login',
            'renderSearchbar' => false,
        ];
        $bodyData['url'] = $this->session->userdata('uri_string');
        $facebookScope = $this->config->item('facebook', 'oauth');
        $googleScope = $this->config->item('google', 'oauth');
        $bodyData['facebook_login_url'] = $this->socialMediaManager
                                                ->getLoginUrl(1, $facebookScope['permission_to_access']);
        $bodyData['google_login_url'] = $this->socialMediaManager
                                             ->getLoginUrl(2, $googleScope['permission_to_access']);

        if($this->input->post('login_form')){
            $row = array();
            if($this->form_validation->run('login_form')){
                $uname = $this->input->post('login_username');
                $pass = $this->input->post('login_password');
                $row = $this->login($uname, $pass);
            }
            if(isset($row['o_success']) && $row['o_success'] >= 1){
                redirect('home');
                exit();
            }
            else{
                $bodyData['form_error'] = 'Invalid username or password';
                if(array_key_exists('timeoutLeft', $row) && $row['timeoutLeft'] >= 1){
                    $bodyData['loginFail'] = true;
                    $bodyData['timeoutLeft'] = $row['timeoutLeft'];
                }
            }  
        }

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
            $row['o_success'] = 0;
            $row['timeoutLeft'] = $this->throttleService->getTimeoutLeft($uname);
        }
        else{
            $dataval = array('login_username' => $uname, 'login_password' => $pass);             
            $row = $this->accountManager->authenticateMember($uname, $pass);

            if (!empty($row["member"])) {
            
                $row['o_success'] = 1;
                $row["o_memberid"] = $row["member"]->getIdMember();
                $row["o_session"] = sha1($row["member"]->getIdMember().date("Y-m-d H:i:s"));

                $em = $this->serviceContainer['entity_manager'];
                $cartManager = $this->serviceContainer['cart_manager'];
                $user = $em->find('\EasyShop\Entities\EsMember', ['idMember' => $row['o_memberid']]);
                $session = $em->find('\EasyShop\Entities\CiSessions', ['sessionId' => $this->session->userdata('session_id')]);
                $cartData = $cartManager->synchCart($user->getIdMember());

                $this->session->set_userdata('member_id', $row['o_memberid']);
                $this->session->set_userdata('usersession', $row['o_session']);
                $this->session->set_userdata('cart_contents', $cartData);

                if($this->input->post('keepmeloggedin') == 'on'){ //create cookie bound to memberid||ip||browser 
                    $temp = array(
                        'member_id' => $this->session->userdata('member_id'),
                        'ip' => $this->session->userdata('ip_address'),
                        'useragent' => $this->session->userdata('user_agent'),
                        'session' => $this->session->userdata('session_id'),
                    );
                    $cookieval = $this->user_model->dbsave_cookie_keeplogin($temp)['o_token'];
                    $this->user_model->create_cookie($cookieval);
                }
                
                /**
                 * Register authenticated session
                 */
                $user->setUsersession($row["o_session"] );
                $authenticatedSession = new \EasyShop\Entities\EsAuthenticatedSession();
                $authenticatedSession->setMember($user)
                                     ->setSession($session);
                $em->persist($authenticatedSession);
                $em->flush();
            }
            else{ 
                $this->throttleService->logFailedAttempt($uname);
                $this->throttleService->updateMemberAttempt($uname);
                $row['timeoutLeft'] = $this->throttleService->getTimeoutLeft($uname);
                $row['o_message'] = $row["errors"][0]["login"];
                $row['o_success'] = 0;
            }
        }
        return $row;
    }
    
    
    public function logout() 
    {
        
        $cart_items = serialize($this->session->userdata('cart_contents'));
        $id = $this->session->userdata('member_id');        
        $this->cart_model->save_cartitems($cart_items,$id);
        $this->user_model->logout();
        $temp = array(
                'member_id' => $this->session->userdata('member_id'),
                'ip' => $this->session->userdata('ip_address'),
                'useragent' => $this->session->userdata('user_agent'),
                'token' => get_cookie('es_usr')
        );

        $this->user_model->dbdelete_cookie_keeplogin($temp);
        delete_cookie('es_usr');
        delete_cookie('es_vendor_subscribe');
        $this->session->sess_destroy();
        $referrer = $this->input->get('referrer');
        if(trim($referrer))
            redirect('/'.$referrer);
        else
            redirect('/login');
    }

    public function identify()
    {
        $headerData = [
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
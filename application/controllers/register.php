<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends MY_Controller
{

    function __construct() 
    {
        parent::__construct();
        $this->load->model("register_model");
        $this->load->library('encrypt');
        $this->load->library('session');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function index()
    {
        $url = 'landingpage';
        $is_promo = FALSE;
        if (strpos($this->session->userdata('uri_string'), 'ScratchCard') !== FALSE) {
            $code = trim($this->session->userdata('uri_string'), 'promo/ScratchCard/claimScratchCardPrize/claim/');
            $url = 'promo/ScratchCard/claimScratchCardPrize/claim/'.$code;
            $is_promo = TRUE;
         }
        $data = array(
            'title' => 'Easyshop.ph - Welcome to Easyshop.ph',
            'metadescription' => 'Register now at Easyshop.ph to start your buying and selling experience',
            'redirect_url' => $url,
            'is_promo' =>$is_promo
        );
        $socialMediaLinks = $this->getSocialMediaLinks();
        $data['facebook'] = $socialMediaLinks["facebook"];
        $data['twitter'] = $socialMediaLinks["twitter"];        
        $data = array_merge($data, $this->fill_header());
        $this->load->view('pages/user/register', $data);
    }


    /**
     *   Registration Handler
     */
    public function signup()
    {
        $signUpResponse = array(
            'result' => 0,
            'error' => array()
        );

        if($this->input->post()) {
            $this->accountManager = $this->serviceContainer['account_manager'];    
            $this->em = $this->serviceContainer['entity_manager'];            
            $serverResponse = $this->accountManager->registerMember(
                                                                $this->input->post("username"),
                                                                $this->input->post("password"),
                                                                $this->input->post("email"),
                                                                $this->input->post("mobile")
                                                            );        
            $emailCode = sha1($serverResponse["member"]->getEmail().time());
            $this->load->library('parser');
            $parseData = array(
                'user' => $serverResponse["member"]->getUserName(),
                'hash' => $this->encrypt->encode($serverResponse["member"]->getEmail().'|'.$serverResponse["member"]->getUserName().'|'.$emailCode),
                'site_url' => site_url('register/email_verification')
            );
            $this->emailNotification = $this->serviceContainer['email_notification'];
            $message = $this->parser->parse('templates/landingpage/lp_reg_email',$parseData,true);                                                              
            $this->emailNotification->setRecipient($serverResponse["member"]->getEmail());
            $this->emailNotification->setSubject($this->lang->line('registration_subject'));
            $this->emailNotification->setMessage($message);
            $emailResult = (bool) $this->emailNotification->sendMail();

            $data = [
                "memberId" => $serverResponse["member"]->getIdMember(),
                "emailCode" => $emailCode,
                "mobileCode" => $this->rand_alphanumeric(6),
                "email" => ($emailResult) ? 1 : 0,
            ];
            $isVerifCodeSuccess = $this->accountManager->storeMemberVerifCode($data);
            $isRegistrationSuccess = true;
            if(is_null($serverResponse["member"]) || !$serverResponse["member"]) {
                $signUpResponse["error"] = "Database registration failure <br>";
                $isRegistrationSuccess = false;
            }
            if(!$emailResult) {
                $signUpResponse["error"] = "Failed to send verification email. Please verify in user page upon logging in.";
            }
            if(!$isVerifCodeSuccess) {
                $signUpResponse["error"] = "Database verifcode error <br>";
            }            
            if($isRegistrationSuccess && $isVerifCodeSuccess) {
                $signUpResponse["result"] = 1;
            }
            else {
                $signUpResponse["result"] = 0;

            }

        }

        echo json_encode($signUpResponse);
    }


    public function username_check()
    {
        if($this->input->post('username')){
            $username = $this->input->post('username');
            if($this->register_model->validate_username($username))
                echo 1;
            else
                echo 0;
        }
    }

    public function email_check()
    {
        if($this->input->post('email')){
            $email = $this->input->post('email');
            if($this->register_model->checkEmailIfExists($email)){
                echo 0;
            }
            else {
                echo 1;
            }
        }
    }

    public function mobile_check()
    {
        if($this->input->post('mobile')){
            $mobile = $this->input->post('mobile');
            if($this->register_model->checkMobileIfExists($mobile)){
                echo 0;
            }
            else {
                echo 1;
            }
        }
    }

    public function external_callbacks( $postdata, $param )
    {
            $param_values = explode( ',', $param ); 
            $model = $param_values[0];
            $this->load->model( $model );
            $method = $param_values[1];
            
            if( count( $param_values ) > 2 )
            {
                array_shift( $param_values );
                array_shift( $param_values );
                $argument = $param_values;
            }

            if( isset( $argument ) )
            {
            if($method == 'checkifrequired'){
                $thisdata = trim(html_escape($postdata));
                $checkwith = trim(html_escape($this->input->post($argument[0])));
                $callback_result = $this->$model->$method($thisdata, $checkwith);
            }
            else
                $callback_result = $this->$model->$method( $postdata, $argument );
            }
            else
            {
            if($method == 'validate_captcha')
                $callback_result = $this->$model->$method( $postdata, $this->session->userdata('captcha_word'));
            else     
                $callback_result = $this->$model->$method( $postdata );
            }
            return $callback_result;
    }

    public function success($action = '')
    {
        $data['title'] = 'Easyshop.ph - Thank You';
        $referrer = $this->input->post('referrer') ? trim($this->input->post('referrer')) : '';
        $socialMediaLinks = $this->getSocialMediaLinks();        
        if(!($referrer)){
            $data['title'] = 'Page not found';
            $data = array_merge($data,$this->fill_header());
            $this->load->view('templates/header', $data); 
            $this->load->view('pages/general_error');


            $viewData['facebook'] = $socialMediaLinks["facebook"];
            $viewData['twitter'] = $socialMediaLinks["twitter"];

            $this->load->view('templates/footer_full', $viewData);
        }
        else{
            if ($referrer === 'landingpage') {
                $data['content'] = 'You have successfully registered!';
                $data['sub_content'] = 'You have successfully registered with Easyshop.ph. Verify your e-mail to begin selling your products online.';

                $data['facebook'] = $socialMediaLinks["facebook"];
                $data['twitter'] = $socialMediaLinks["twitter"];    
                            
                $this->load->view('pages/user/register_subscribe_success', $data);
            }
            else {
                $this->session->set_userdata('uri_string', $referrer);
                redirect('/login', 'refresh');
            }
        }
    } 
    

    public function subscribe()
    {
        $em = $this->serviceContainer['entity_manager'];
        $rules = $this->serviceContainer['form_validation']->getRules('subscribe')['email'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper']; 
        $emailer = $this->serviceContainer['email_notification'];

        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                            ->setMethod('POST')
                            ->add('email', 'text', array('required' => false, 'label' => false, 'constraints' => $rules))
                            ->getForm();

        $form->submit([ 'email' => $this->input->post('email')]);


        if ($form->isValid()) {
  
            $formData = $form->getData();
            if(!$em->getRepository('EasyShop\Entities\EsSubscribe')->findBy(['email' => $formData['email']])){
                $subscriber = new EasyShop\Entities\EsSubscribe();
                $subscriber->setEmail($formData['email']);
                $subscriber->setDatecreated(date_create(date("Y-m-d H:i:s")));
                $em->persist($subscriber);
                $em->flush();

                $this->config->load('email', true);
                $imageArray = $this->config->config['images'];
                $message = $this->load->view('templates/landingpage/lp_subscription_email', array(), true);
                
                $emailer->setRecipient($subscriber->getEmail());
                $emailer->setSubject($this->lang->line('subscription_subject'));
                $emailer->setMessage($message, $imageArray);
                $emailer->sendMail();
                
                $data['content'] = 'You have successfully Subscribed!';
            }
            else{
                $data['content'] = 'You are already subscribed to Easyshop.ph.';
            }
            $data['title'] = 'Successful Subscription | Easyshop.ph';
            $data['sub_content'] =  'Thank you for choosing to keep in touch with Easyshop.ph. Expect to hear many things from us soon.';
            $this->load->view('pages/user/register_subscribe_success', $data);
        }
        else{
            redirect('/','refresh');
        }
       
    }
    
    /**
     * Renders change password view
     *
     */
    public function changepass()
    {
        $result = false;
        $username = $this->input->post('wsx');
        $cur_password = $this->input->post('cur_password');
        $password = $this->input->post('password');         

        $dataval = array('login_username' => $username, 'login_password' => $cur_password);
        $this->accountManager = $this->serviceContainer['account_manager'];            
        $row = $this->accountManager->authenticateMember($username, $cur_password);

        if (!empty($row["member"])){
            $result = $this->accountManager->updatePassword($row["member"]->getIdMember(), $password);
        }
        else {
            $result = false;
        }


        $serverResponse = array(
            'result' => $result ? 'success' : 'error'
            , 'error' => $result ? '' : 'Invalid current password'
        );
        echo json_encode($serverResponse);
    }
    
    function email_verification(){

        $this->load->library('encrypt');

        $socialMediaLinks = $this->getSocialMediaLinks();
        $viewData['facebook'] = $socialMediaLinks["facebook"];
        $viewData['twitter'] = $socialMediaLinks["twitter"];

        //Decrypt and re-assign data
        $enc = html_escape($this->input->get('h'));
        $enc = str_replace(" ", "+", $enc);
        $decrypted = $this->encrypt->decode($enc);
        $getdata = explode("|", $decrypted);
        
        $email = $getdata[0];
        $username = $getdata[1];
        $hash = $getdata[2];

        $data = array(
            'title' => 'Easyshop.ph - Email Verification',
            'member_username' => $username,
            'email' => $email,
            'render_logo' => false,
            'render_searchbar' => false
        );
        $data = array_merge($data, $this->fill_header());

        $member_id = $this->register_model->get_memberid($username)['id_member'];

        if($member_id === 0){
            $this->load->view('templates/header', $data);
            $this->load->view('pages/user/err_email_verif', $data);
            $this->load->view('templates/footer_full', $viewData);
            return;
        }

        $data_val = $this->register_model->get_verifcode($member_id);


        if($email === $data_val['email'] && $hash === $data_val['emailcode'] && $username === $data_val['username'])
        {
        
            if($data_val['is_email_verify'] == 1){
                $data['verification_msg'] = $this->lang->line('expired_email_verification');
                $this->load->view('templates/header', $data);
                $this->load->view('pages/user/register_form3_view', $data);
                $this->load->view('templates/footer_full', $viewData);
                return;
            }

            $temp = array(
                'is_email_verify' => 1,
                'member_id' => $member_id
            );

            $this->register_model->update_verification_status($temp);
            
            $data['verification_msg'] = $this->lang->line('success_email_verification');
            $data['render_searchbar'] = false;
            $data['render_logo'] = false;
            $this->load->view('templates/header', $data);
            $this->load->view('pages/user/register_form3_view', $data);
            $this->load->view('templates/footer_full', $viewData);
        }
        else{
            $this->load->view('templates/header', $data);
            $this->load->view('pages/user/err_email_verif', $data);
            $this->load->view('templates/footer_full', $viewData);
        }
    }
 
 
    
}// close class


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
        session_start();
        $this->load->config('oauth', true);
        $this->form_validation->set_error_delimiters('', '');
        $this->socialMediaManager = $this->serviceContainer['social_media_manager'];
    }

    /**
     *   Registration Handler
     */
    public function signup()
    {
        $signUpResponse = [
            'result' => 0
        ];

        if($this->input->post()) {
            $this->accountManager = $this->serviceContainer['account_manager'];    
            $this->em = $this->serviceContainer['entity_manager'];            
            $registrationResult = $this->accountManager->registerMember(
                                                                $this->input->post("username"),
                                                                $this->input->post("password"),
                                                                $this->input->post("email"),
                                                                $this->input->post("mobile")
                                                            );     
            if(!empty($registrationResult["errors"])) {
                $signUpResponse["errors"] = $registrationResult["errors"];
            }
            else {
                $isVerificationSendingSuccessful =  $this->accountManager
                                                         ->sendAccountVerificationLinks($registrationResult["member"])['isSuccessful'];
                $signUpResponse["result"] = true;
                if(is_null($registrationResult["member"]) || !$registrationResult["member"]) {
                    $signUpResponse["dbError"] = "Database registration failure <br>";
                    $signUpResponse["result"] = false;
                }
                if(!$isVerificationSendingSuccessful) {
                    $signUpResponse["dbError"] = "Sorry, we cannot send out a verification email at this time. Please verify through your dashboard upon logging in.";
                    $signUpResponse["result"] = false;
                }
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


    public function success($action = '')
    {
        $data['title'] = 'Easyshop.ph - Thank You';

        $socialMediaLinks = $this->serviceContainer['social_media_manager'] 
                                 ->getSocialMediaLinks();      
        if($this->input->post('registration_referrer') === false){
            show_404();
        }
        else{
            $referrer =  trim($this->input->post('registration_referrer'));
            if ($referrer === 'registration') {
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
        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                 ->getSocialMediaLinks();
        $this->load->library('parser');
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
                $parseData = [
                    'baseUrl' => base_url(),
                    'facebook' => $socialMediaLinks['facebook'],
                    'twitter' => $socialMediaLinks['twitter'],
                ];

                $message = $this->parser->parse('emails/newsletter-subscription' , $parseData, true);
                $emailer->setRecipient($subscriber->getEmail());
                $emailer->setSubject($this->lang->line('subscription_subject'));
                $emailer->setMessage($message, $imageArray);
                $emailer->queueMail();

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
        $memberObj = $this->serviceContainer['entity_manager']
                          ->getRepository('EasyShop\Entities\EsMember')   
                          ->find($this->session->userdata('member_id'));
        $currentPassword = $this->input->post('currentPassword');
        $password = $this->input->post('password');         

        $this->accountManager = $this->serviceContainer['account_manager'];            
        $row = $this->accountManager->authenticateMember($memberObj->getUserName(), $currentPassword);

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
    
    
    /**
     * Checks if a user's email is succesfully verified
     *
     */
    public function email_verification()
    {
        $this->load->library('encrypt');

        //Decrypt and re-assign data
        $enc = html_escape($this->input->get('h'));
        $enc = str_replace(" ", "+", $enc);
        $decrypted = $this->encrypt->decode($enc);
        $getdata = explode("|", $decrypted);
        
        $email = isset($getdata[0]) ? $getdata[0] : null;
        $username = isset($getdata[1]) ? $getdata[1] : null;
        $hash = isset($getdata[2]) ? $getdata[2] : null;

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Easyshop.ph - Email Verification',
            'metadescription' => '',
            'relCanonical' => '',
            'renderSearchbar' => false,
        ];         
        
        $bodyData = [ 
            'username' => $username,
            'email' => $email,
        ];

        $member_id = $this->register_model->get_memberid($username)['id_member'];
 
        if($member_id === 0){
            $this->load->spark('decorator');    
            $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('errors/email-verification');
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
            return;
        }

        $verificationData = $this->register_model->get_verifcode($member_id);
        
        if($email === $verificationData['email'] && 
           $hash === $verificationData['emailcode'] && 
           $username === $verificationData['username'])
        {
        
            if((bool)$verificationData['is_email_verify']){
                $bodyData['verificationMessage'] = $this->lang->line('expired_email_verification');
                $bodyData['isAlreadyVerified']  = true;
                $this->load->spark('decorator');    
                $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
                $this->load->view('pages/user/email-verification-success', $bodyData);
                $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
                return;
            }

            $temp = [
                'is_email_verify' => 1,
                'member_id' => $member_id
            ];

            $this->register_model->update_verification_status($temp);
            
            $bodyData['verificationMessage'] = $this->lang->line('success_email_verification');
            $this->load->spark('decorator');    
            
            $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/user/email-verification-success', $bodyData);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));

        }
        else{
            $this->load->spark('decorator');    
            $this->load->view('templates/header_primary', $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('errors/email-verification');
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
        }
    }
 
 
    
}// close class


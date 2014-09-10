<?php

class FacebookSetup extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->config('thirdPartyConfig', TRUE);
    }

    function index()
    {
        $fbConfig = $this->config->item('facebook', 'thirdPartyConfig');

        $this->load->library('facebook', $fbConfig);
        $facebook = new Facebook($fbConfig);
        $user = $facebook->getUser();
        if ($user) {
            try {
                $data['user_profile']  = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }

        if ($user) {
            $data['logout_url'] = $this->facebook
                ->getLogoutUrl();
        } else {
            $data['login_url'] = $this->facebook
                ->getLoginUrl();
        }


        $this->load->view('pages/facebook/facebook_view',$data);
    }

    public function loginOnFacebook()
    {
        $fbConfig = $this->config->item('facebook', 'thirdPartyConfig');
        $this->load->library('facebook', $fbConfig);
        $facebook = new Facebook($fbConfig);
        $params = array(
            'redirect_uri' => 'https://local.easyshop/facebookSetup/viewData'
        );
        $data['login_url'] = $facebook->getLoginUrl($params);

        $this->load->view('pages/facebook/facebook_view',$data);
    }

    public function viewData()
    {
        $fbConfig = $this->config->item('facebook', 'thirdPartyConfig');
        $this->load->library('facebook', $fbConfig);
        $facebook = new Facebook($fbConfig);
        $data['user_profile'] = $facebook->api('/me');
        $data['logout_url'] = $facebook->getLogoutUrl();

        $this->load->view('pages/facebook/facebook_view',$data);

    }

    public function logoutOnFacebook()
    {
        $fb_config = $this->config->item('facebook', 'thirdPartyConfig');
        $this->load->library('facebook', $fb_config);
        $this->facebook->destroySession();
    }
}
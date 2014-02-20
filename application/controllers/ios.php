<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ios extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('home_xml');
    }



    public function url(){

        $ipAddress = "192.168.1.50:81";
        echo 'LIST OF URL';
        echo '<br><br>';
        echo 'LOGIN: <a  style="font-weight:bold;color:maroon" href="http://'. $ipAddress.'/ios/authenticate?uname=&upwd=">http://'. $ipAddress.'/ios/authenticate?uname=&upwd=</a>';
        echo '<br><br>'; 
        echo 'HOME: <a  style="font-weight:bold;color:maroon" href="http://'. $ipAddress.'/ios/home">http://'. $ipAddress.'/ios/home</a>';
        echo '<br><br>'; 
        echo 'http://www.google.fr/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&ved=0CB4QFjAA&url=http%3A%2F%2Fallseeing-i.com%2FASIHTTPRequest%2F&ei=-dFZULfKO7SU0QW-1YGoAw&usg=AFQjCNFpUZprrMAY9mTk0aGzEzwSG8L9sg';

    }

    public function home(){
        $items =  $this->home_xml->getFilenameID('home_files');
        echo json_encode($items,JSON_PRETTY_PRINT);
    }

    public function authenticate() {

        $uname = $this->input->get('uname');
        $pass = $this->input->get('upwd');   
        $dataval = array('login_username' => $uname, 'login_password' => $pass);
        $row = $this->user_model->verify_member($dataval);               

        if ($row['o_success'] >= 1) {
            $this->session->set_userdata('member_id', $row['o_memberid']);
            $this->session->set_userdata('usersession', $row['o_session']);
            $this->session->set_userdata('cart_contents', $this->cart_model->cartdata($row['o_memberid']));
            if($this->input->post('keepmeloggedin') == 'on'){ 
                $temp = array(
                    'member_id' => $this->session->userdata('member_id'),
                    'ip' => $this->session->userdata('ip_address'),
                    'useragent' => $this->session->userdata('user_agent'),
                    'session' => $this->session->userdata('session_id'),
                    );
                $cookieval = $this->user_model->dbsave_cookie_keeplogin($temp)['o_token'];
                $this->user_model->create_cookie($cookieval);
            }
        }  
        echo json_encode($row,JSON_PRETTY_PRINT);

    }
}

/* End of file ios.php */
/* Location: ./application/controllers/home.php */

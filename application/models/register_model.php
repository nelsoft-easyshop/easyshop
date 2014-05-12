<?php
class Register_model extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->config->load('image_path');
		$this->load->library('sqlmap');
	}
	
	/*
	 *	Function used to check if username exists in registration page
	 */
	function get_member_by_username($member_username)
	{
		$query = $this->sqlmap->getFilenameID('users', 'get_member_by_username');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $member_username);
        $sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		return $result;
	}

	// USED BY AJAX CHECK
	function checkEmailIfExists($email)
	{
		$query = $this->sqlmap->getFilenameID('users', 'getEmail');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':email', $email);
        $sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		
		return $result;
	}
	// USED BY AJAX CHECK
	function checkMobileIfExists($mobile)
	{
		$query = $this->sqlmap->getFilenameID('users', 'getMobile');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':contactno', $mobile);
        $sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		return $result;
	}
	
	function get_memberid($member_username){
		$query = $this->sqlmap->getFilenameID('users', 'getUserID');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $member_username);
        $sth->execute();
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		
		if(count($result) == 0)
			$result = array('id_member' => 0);
		
		return $result;
	}
	
	/*function signup_member($data=array())
	{	
		$query = $this->sqlmap->getFilenameID('users', 'signup_member');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $data['register_username']);
		$sth->bindParam(':password', $data['register_password']);
		$sth->bindParam(':contactno', $data['register_mobile']);
		$sth->bindParam(':email', $data['register_email']);
		$sth->bindParam(':region', $data['register_region']);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
		
	}*/
	
	function signupMember($data) 
	{
		$query = $this->sqlmap->getFilenameID('users', 'signup_member');
        $sth = $this->db->conn_id->prepare($query);
		$blank = '';
        $sth->bindParam(':username', $data['username']);
		$sth->bindParam(':password', $data['password']);
		$sth->bindParam(':email', $data['email']);
		$sth->bindParam(':contactno', $blank);
		$sth->bindParam(':region', $blank);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
	}
	
	
	function changepass($data=array())
	{	
		$query = $this->sqlmap->getFilenameID('users', 'changepass');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $data['username']);
		$sth->bindParam(':cur_password', $data['cur_password']);
		$sth->bindParam(':password', $data['password']);
        $sth->execute();	
        $result = $sth->fetch(PDO::FETCH_ASSOC);        
        return ($result['o_success']==='true'?true:false);
	}	
		
	function validate_captcha($user_captcha, $data)
	{
		if(strtolower($user_captcha) == strtolower($data))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('external_callbacks', 'Unmatched captcha');
			return FALSE;
		}
	}
	
	function validate_username($username)
	{
		$query = $this->sqlmap->getFilenameID('users', 'get_member_by_username');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $username);
        $sth->execute();	
		if($sth->rowcount() == 0){
			return true;
		}
		else{
			$this->form_validation->set_message('external_callbacks', 'Username already exists');
			return false;
		}
	}
	
	// FOR FIRST TIME REGISTRATION ONLY
	function validate_email($email)
    {	
		$query = $this->sqlmap->getFilenameID('users', 'getEmail');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':email', $email);
		$sth->execute();
		if($sth->rowcount() == 0){
			return true;
		}
		else{
			$this->form_validation->set_message('external_callbacks', 'Email already used');
			return false;
		}
	}
	// FOR FIRST TIME REGISTRATION ONLY
	function validate_mobile($mobile)
	{
		$query = $this->sqlmap->getFilenameID('users', 'getMobile');
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':contactno', $mobile);
		$sth->execute();
		if($sth->rowcount() == 0){
			return true;
		}
		else{
			$this->form_validation->set_message('external_callbacks', 'Mobile already used');
			return false;
		}
	}
	
	function validate_password($password)
	{
		if(!((preg_match('/[a-zA-Z]/', $password))&&(preg_match('/\d/',$password))))
		{
			$this->form_validation->set_message('external_callbacks', 'Must contain numbers and letters');
			return FALSE;
		}
		/*else if(!((preg_match('/[a-z]/', $password))&&(preg_match('/[A-Z]/', $password))))
		{
			$this->form_validation->set_message('external_callbacks', 'Must contain upper-case and lower-case letters');
			return FALSE;
		}*/
		else
			return TRUE;
	}	
	
	function alphanumeric_underscore($em)
	{
		if(!(preg_match('/^\w+$/i', $em)))
		{
			$this->form_validation->set_message('external_callbacks', 'Only letters, numbers, and underscores are allowed');
			return FALSE;
		}
		return TRUE;
	}
	
	function rand_alphanumeric($length)
	{
		$characters = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ123456789';
		$string = '';
		for ($i = 0; $i < $length; $i++) 
		{
			  $string .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $string;
	}
	

	function send_mobile_msg($username, $mobile, $confirmation_code)
	{
		//$result = $this->register_model->register_mobile($username, $mobile, $confirmation_code);
		$result['sms_result'] ='201';

		if(array_key_exists('error', $result)||($result['sms_result'] != '201'))
			return 'error';
		else
			return 'success';
	}

	
	function register_mobile($username, $mobile, $confirmation_code)
	{
		$data=array();
		$this->load->library("nuSoap_lib");
		$this->nusoap_client = new nusoap_client("http://iplaypen.globelabs.com.ph:1881/axis2/services/Platform/");
		$this->nusoap_client->soap_defencoding = 'UTF-8';
		$err = $this->nusoap_client->getError();
		if($err) return $data['error'] = $err;
		$sms_result = $this->nusoap_client->call('sendSMS', array( 'uName' => 'z9wmupdx1',
											  'uPin' => '21736792',
											  'MSISDN' => $mobile,
											  'messageString' =>  $this->lang->line('sms_header').$username.$this->lang->line('sms_body').$confirmation_code.$this->lang->line('sms_footer'),
											  'Display' => '0',
											  'udh' => '',
											  'mwi' => '',
											  'coding' => '0'),"http://ESCPlatform/xsd");
		$data['sms_result'] = $sms_result;
		return $data;
	}
	
	
	/*
	function register_mobile($username, $mobile, $confirmation_code)
	{
		$fields = array();
		$fields["api"] = "dgsMQ8q77hewW766aqxK";
		$fields["number"] = 9177050441; //safe use 63
		$fields["message"] = 'hello Janz from semaphore';
		$fields["from"] = 'semaphore';
		$fields_string = http_build_query($fields);
		$outbound_endpoint = "http://api.semaphore.co/api/sms";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $outbound_endpoint);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		
		return $output;
	}
	*/
	
	function send_email_msg($email, $username, $hash)
	{
		$this->load->library('encrypt');
		$enc = $this->encrypt->encode($email.'|'.$username.'|'.$hash);

		$x = $this->register_email($email, $enc, $username);
		if($x){
			return 'success';
		}
		else{
			return 'error';
		}
	}

	function register_email($email, $enc, $username){

		$this->load->library('email');	
		$this->load->library('parser');
		
		$this->email->set_newline("\r\n");
		$this->email->to($email);
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');
		$this->email->subject($this->lang->line('email_subject'));
		//Windows code
		//$this->email->attach(getcwd() . "\assets\images\img_logo.png", "inline");
		//CentOS code - working for windows as well
		$this->email->attach(getcwd() . "/assets/images/img_logo.png", "inline");

		$data = array(
			'site_url' => site_url('register/email_verification'),
			'hash' => $enc,
			'user' => $username
		);
		
		$msg = $this->parser->parse('templates/email_template',$data,true);
		$this->email->message($msg);
		$result = $this->email->send();

		//$errmsg = $this->email->print_debugger();
		//print_r($errmsg);
		//die();

		return $result;
	}

	function check_contactinfo($data){
		$query = $this->sqlmap->getFilenameID('users', 'check_contactinfo');
        $sth = $this->db->conn_id->prepare($query);

        $data['contactno'] = $data['contactno'] === '' ? 0:$data['contactno'];
        $data['email'] = $data['email'] === '' ? 0:$data['email'];

		$sth->bindParam(':contactno', $data['contactno']);
		$sth->bindParam(':email', $data['email']);
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->execute();
		$row = $sth->fetchAll(PDO::FETCH_ASSOC);

		$result = array(
			'mobile' => 0,
			'email' => 0
		);

		if(count($row) > 0){
			foreach($row as $r){
				if($data['contactno'] == $r['contactno'])
					$result['mobile'] = 1;
				
				if($data['email'] == $r['email'])
					$result['email'] = 1;
			}
		}

		return $result;
	}
	

	function store_verifcode($data){
		$query = $this->sqlmap->getFilenameID('users', 'store_verifcode');
        $sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':member_id', $data['member_id']);
		$sth->bindParam(':mobilecode', $data['mobilecode']);
		$sth->bindParam(':emailcode', $data['emailcode']);
		$sth->bindParam(':mobile', $data['mobile']);
		$sth->bindParam(':email', $data['email']);
		
        $result = $sth->execute();
		
		return $result;
	}
	

	function get_verifcode($member_id)
	{
		$query = $this->sqlmap->getFilenameID('users', 'get_verifcode');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':member_id', $member_id);
        $sth->execute();
		
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		
		if(count($result) == 0){
			$result = array(
				'emailcount' => 0,
				'mobilecount' => 0,
				'time' => 31
			);
		}

		return $result;
	}


	function update_verification_status($data = array())
	{
		if(isset($data['is_contactno_verify'])){
			$query = $this->sqlmap->getFilenameID('users', 'update_mobileverif_status');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':member_id', $data['member_id']);
			$sth->bindParam(':is_contactno_verify', $data['is_contactno_verify']);	
			
			$sth->execute();
		}

		if(isset($data['is_email_verify'])){
			$query = $this->sqlmap->getFilenameID('users', 'update_emailverif_status');
			$sth = $this->db->conn_id->prepare($query);
			$sth->bindParam(':member_id', $data['member_id']);
			$sth->bindParam(':is_email_verify', $data['is_email_verify']);				
			$sth->execute();
		}
	}
	

	function checkifrequired($thisdata, $checkwith)
    {
		if($thisdata != '' || $checkwith != '')
			return true;//should be true - put to false for test
		else
			return false;
	}
	
	
	function check_registered_email($email)
    {	
		$query = "SELECT id_member, username FROM `es_member` WHERE email = :email LIMIT 1 ";
		$sth = $this->db->conn_id->prepare($query);
		$sth->bindParam(':email', $email);
		$sth->execute();
		$result = $sth->fetch();
		return $result;
	}

    function forgotpass($email, $username, $id_member)
    {
		//generate hash
		$query = "SELECT reverse(PASSWORD(concat(md5(now()),sha1(now())))) AS fp_code, now() AS fp_time;";
		$sth = $this->db->conn_id->prepare($query);
		$sth->execute();
		$rows = $sth->fetch(PDO::FETCH_ASSOC);	

        $fp_time = $rows['fp_time'];
        $fp_code = $rows['fp_code'];
        
		//update to database
        $query = "UPDATE es_verifcode SET fp_timestamp = :fp_time, fp_code = :fp_code WHERE member_id = :id_member;";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':fp_time', $fp_time);
		$sth->bindParam(':fp_code', $fp_code);
        $sth->bindParam(':id_member', $id_member);
		$sth->execute();
        
		//email generation starts here		

		$this->load->library('email');
		$this->load->library('parser');
	
		$this->email->set_newline("\r\n");
		$this->email->to($email);
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');
		$this->email->subject('Password reset on Easyshop.ph');
		$this->email->attach(getcwd() . "/assets/images/img_logo.png", "inline");
		
		$data = array(
			'site_url' => site_url('login/success_email_verification'),
			'username' => $username,
			'trigger' => $fp_code,
		);		
		
		$msg = $this->parser->parse('templates/email_forgot_pass',$data,true);
		$this->email->message($msg);
		$result = $this->email->send();
  
		return $result;
	}
	
	function forgotpass_email($hash)
    {   // check ko yung email kung ok pa yung code.
		$query = "SELECT a.fp_timestamp, a.fp_code, b.username, b.password, a.member_id FROM es_verifcode a
		LEFT JOIN es_member b ON a.member_id = b.id_member WHERE a.fp_code = :hash
		AND a.fp_timestamp < DATE_ADD(NOW(), INTERVAL 1 HOUR) LIMIT 1";
		$sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':hash', $hash);
		$sth->execute();
		$result = $sth->fetch();
		return $result;
	}
	
	function forgotpass_update($data=array())
	{
		
		//update to database
        $query = "UPDATE es_verifcode SET fp_timestamp = NOW(), fp_code = NULL WHERE member_id = :id_member;";
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':id_member',  $data['member_id']);
		$sth->execute();
				
		$query = $this->sqlmap->getFilenameID('users', 'forgotpass');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':username', $data['username']);
		$sth->bindParam(':password', $data['password']);
        $sth->execute();	
	}	
 
	/*************	LANDING PAGE SUBSCRIPTION	*****************/
	
	/*
	 *	Save email to subscription table
	 */
	public function subscribe($email)
	{
		$query = $this->sqlmap->getFilenameID('users', 'subscribe');
        $sth = $this->db->conn_id->prepare($query);
        $sth->bindParam(':email', $email);
        $result = $sth->execute();
		
		return $result;
	}
	
	/*
	 *	Send notification email after registration / subscription
	 *	$data = 'username', 'email', 'password', emailcode
	 */
	public function sendNotification($data, $type="")
	{
		$this->load->library('email');	
		$this->load->library('parser');
		$this->load->library('encrypt');
		
		$this->email->set_newline("\r\n");
		$this->email->to($data['email']);
		$this->email->from('noreply@easyshop.ph', 'Easyshop.ph');		
		//$this->email->attach(getcwd() . "\assets\images\img_logo.png", "inline"); //Windows code
		$this->email->attach(getcwd() . "/assets/images/landingpage/templates/header-img.png", "inline"); //CentOS code - working for windows as well
		$this->email->attach(getcwd() . "/assets/images/landingpage/templates/facebook.png", "inline");
		//$this->email->attach(getcwd() . "/assets/images/landingpage/templates/googleplus.png", "inline");
		$this->email->attach(getcwd() . "/assets/images/landingpage/templates/twitter.png", "inline");
		
		if($type === 'signup'){
			$this->email->subject($this->lang->line('registration_subject'));
			$parseData = array(
				'user' => $data['username'],
				'hash' => $this->encrypt->encode($data['email'].'|'.$data['username'].'|'.$data['emailcode']),
				'site_url' => site_url('register/email_verification')
			);
			$msg = $this->parser->parse('templates/landingpage/lp_reg_email',$parseData,true);
		}
		else if ($type === 'subscribe'){
			$this->email->subject($this->lang->line('subscription_subject'));
			$parseData = array();
			$msg = $this->parser->parse('templates/landingpage/lp_subscription_email',$parseData,true);
		}
		
		$this->email->message($msg);
		$result = $this->email->send();

		return $result;
	}
	
	function is_validmobile($mobile)
	{
		if($mobile == '' ){
			return true;
		}
	
		if(preg_match('/^9[0-9]{9}/', $mobile)){
			return true;
		}
		else{
			$this->form_validation->set_message('external_callbacks', 'The mobile number you entered is invalid');
			return false;
		}
		
	}
	
	function signupMember_landingpage($data) 
	{
		$query = $this->sqlmap->getFilenameID('users', 'signup_member');
        $sth = $this->db->conn_id->prepare($query);
		$blank = '';
        $sth->bindParam(':username', $data['username']);
		$sth->bindParam(':password', $data['password']);
		$sth->bindParam(':email', $data['email']);
		$sth->bindParam(':contactno', $data['mobile']);
		$sth->bindParam(':region', $blank);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);

        return $row;
	}
	
}

/* End of file register_model.php */
/* Location: ./application/models/register_model.php */
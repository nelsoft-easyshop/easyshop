<?php
class DragonPay {
    	
	public $merchantId  = 'EASYSHOP'; 
	public $merchantPwd = 'UT78W5VQ'; 
	public $url = 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl';
	public $ps = "http://test.dragonpay.ph/Pay.aspx";
	public $errorCodes = array(
			'000' => 'SUCCESS',
			'101' => 'Ivalid payment gateway id',
			'102' => 'Incorrect secret key',
			'103' => 'Invalid reference number',
			'104' => 'Unauthorized access',
			'105' => 'Invalid token',
			'106' => 'Currency not supported',
			'107' => 'Transaction cancelled',
			'108' => 'Insufficient funds',
			'109' => 'Transaction limit exceeded',
			'110' => 'Error in operation',
			'111' => 'Invalid parameters',
			'201' => 'Invalid Merchant Id',
			'202' => 'Invalid Merchant Password'
		);

    public function __construct() {
        require_once('nuSoap/lib/nusoap.php');  
    }

	 function getProcessors()
	 { 
		$client = new nusoap_client($this->url, 'wsdl');
		$result = $client->call('GetProcessors');
		return $result['GetProcessorsResult']['ProcessorInfo'];
	 }

	 function getTxnToken($amount,$description,$email)
	 {
	 	$errorCodes = $this->errorCodes;
     	$txnId = '048';
        $ccy = 'PHP';
        $param = array(
        		'merchantId' => $this->merchantId,
        		'password' => $this->merchantPwd,
        		'merchantTxnId' => $txnId,
        		'amount' => $amount,
        		'ccy' => $ccy,
        		'description' => $description,
        		'email' => $email
        	);
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('GetTxnToken',$param);
        $token = $result['GetTxnTokenResult'];
        
        if(strlen($token) <= 3){
        	return '{"e":"0","m":"'.$errorCodes[$token].'","c":"'.$token.'"}';
        	exit();
        }else{
    		return '{"e":"1","m":"SUCCESS","c":"'.$token.'","u":"'.$this->ps.'?tokenid='.$token.'"}';
    		exit();
        }
	}	
}

/* End of file paypal.php */
/* Location: ./application/libraries/paypal.php */

 // array( 'uName' => 'z9wmupdx1',
	// 										  'uPin' => '21736792',
	// 										  'MSISDN' => $mobile,
	// 										  'messageString' =>  $this->lang->line('sms_header').$username.$this->lang->line('sms_body').$confirmation_code.$this->lang->line('sms_footer'),
	// 										  'Display' => '0',
	// 										  'udh' => '',
	// 										  'mwi' => '',
	// 										  'coding' => '0'),"http://ESCPlatform/xsd");
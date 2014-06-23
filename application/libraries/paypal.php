<?php
class PayPal {

	public $PayPalMode; 
	public $PayPalApiUsername; 
	public $PayPalApiPassword; 
	public $PayPalApiSignature;  

	public function __construct()
	{

		$this->declareEnvironment();
	}
    

    function declareEnvironment(){

    	if(ES_PRODUCTION){

			// LIVE
    		$this->PayPalMode             = ''; 
    		$this->PayPalApiUsername      = 'admin_api1.easyshop.ph'; 
    		$this->PayPalApiPassword      = 'GDWFS6D9ACFG45E7'; 
    		$this->PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Adro7yAfl2NInYAAVfFFipJ-QQhT'; 
    	}else{
			// SANDBOX
    		$this->PayPalMode             = 'sandbox'; 
    		$this->PayPalApiUsername      = 'easyseller_api1.yahoo.com'; 
    		$this->PayPalApiPassword      = '1396000698'; 
    		$this->PayPalApiSignature     = 'AFcWxV21C7fd0v3bYYYRCpSSRl31Au1bGvwwVcv0garAliLq12YWfivG';  	
    	}
    }


	function PPHttpPost($methodName_, $nvpStr_) {
			// Set up your API credentials, PayPal end point, and API version.
			$API_UserName = urlencode($this->PayPalApiUsername);
			$API_Password = urlencode($this->PayPalApiPassword);
			$API_Signature = urlencode($this->PayPalApiSignature);
			$API_Mode = urlencode($this->PayPalMode);
			
			if($API_Mode=='sandbox'){
				$paypalmode 	=	'.sandbox';
			}else{
				$paypalmode 	=	'';
			}
	
			$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
			$version = urlencode('98.0');
		
			// Set the curl parameters.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
		
			// Turn off the server and peer verification (TrustManager Concept).
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
		
			// Set the API operation, version, and API signature in the request.
			$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
			// Set the request as a POST FIELD for curl.
			curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
			// Get response from the server.
			$httpResponse = curl_exec($ch);
		
			if(!$httpResponse) {
				exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			}
		
			// Extract the response details.
			$httpResponseAr = explode("&", $httpResponse);
		
			$httpParsedResponseAr = array();
			foreach ($httpResponseAr as $i => $value) {
				$tmpAr = explode("=", $value);
				if(sizeof($tmpAr) > 1) {
					$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
				}
			}
		
			if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
				exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
			}
		
		return $httpParsedResponseAr;
	}

	function getMode()
	{
		$API_Mode = urlencode($this->PayPalMode);
		if($API_Mode=='sandbox'){
			$paypalmode 	=	'.sandbox';
		}else{
			$paypalmode 	=	'';
		}

		return $paypalmode;
	}
		
}

/* End of file paypal.php */
/* Location: ./application/libraries/paypal.php */
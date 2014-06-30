<?php
class PesoPay {

	public $url; 
	public $merchantId; 

	public function __construct()
	{

		$this->declareEnvironment();
	}

	function declareEnvironment(){

		if(ES_PRODUCTION){
        // LIVE
			$this->url = 'https://www.pesopay.com/b2c2/eng/payment/payForm.jsp';
			$this->merchantId = "18139485";
		}else{
        // SANDBOX
			$this->url = 'https://test.pesopay.com/b2cDemo/eng/payment/payForm.jsp';
			$this->merchantId = "18061489"; 
		}
	}

	function getMode(){
		return $arrayName = array(
			'url' => $this->url,
			'merchantId' => $this->merchantId
			);
	}

}

/* End of file pesopay.php */
/* Location: ./application/libraries/pesopay.php */
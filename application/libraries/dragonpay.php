<?php
class DragonPay {
        
    public $merchantId  = 'EASYSHOP'; 
    public $merchantPwd = 'UT78W5VQ';  
    public $url;
    public $ps;


    function declareEnvironment(){
        if(!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production'){
        // LIVE
            $this->url = 'https://secure.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl';
            $this->ps = "https://gw.dragonpay.ph/Pay.aspx";
        }
        else{
        // SANDBOX
            $this->url = 'http://test.dragonpay.ph/DragonPayWebService/MerchantService.asmx?wsdl';
            $this->ps = "http://test.dragonpay.ph/Pay.aspx"; 
        } 
    }

    
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
        require_once('NuSOAP/lib/nusoap.php'); 
        $this->declareEnvironment(); 
    }

    function getProcessors()
    { 
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('GetProcessors');
        return $result['GetProcessorsResult']['ProcessorInfo'];
    }

    function getTxnToken($amount,$description,$email,$txnId)
    {
        $errorCodes = $this->errorCodes;
         
        $ccy = 'PHP';
        $param = array(
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $txnId,
            'amount' => $amount,
            'ccy' => $ccy,
            'description' => $description,
            'email' => $email,
            'mode'=>'1'
            );
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('GetTxnToken',$param);
        $token = $result['GetTxnTokenResult'];

        if(strlen($token) <= 3){
            return '{"e":"0","m":"'.$errorCodes[$token].'","c":"'.$token.'"}';
        }else{
            return '{"e":"1","m":"SUCCESS","c":"'.$token.'","tid":"'.$txnId.'","u":"'.$this->ps.'?tokenid='.$token.'&mode=7"}';
        }
    }

    function getStatus($txnId)
    { 
        $param = array(
                'merchantId' => $this->merchantId,
                'password' => $this->merchantPwd,
                'txnId' => $txnId
            );
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('GetTxnStatus',$param);
        return $result['GetTxnStatusResult'];
    }

    function voidTransaction($txnId)    
    {
        $param = array(
                'merchantId' => $this->merchantId,
                'password' => $this->merchantPwd,
                'merchantTxnId' => $txnId
            );
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('CancelTransaction',$param);
        return $result['CancelTransactionResult'];
    }
}

/* End of file dragonpay.php */
/* Location: ./application/libraries/dragonpay.php */

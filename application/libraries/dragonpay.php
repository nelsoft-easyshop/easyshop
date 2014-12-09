<?php
class DragonPay
{
    private $merchantId; 
    private $merchantPwd;  
    private $url;
    private $ps;
    private $mode;
    private $errorCodes = [
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
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        require_once('NuSOAP/lib/nusoap.php'); 
        $this->ci =& get_instance();
        $this->declareEnvironment(); 
    }

    /**
     * Get parameter based on enviroment type
     */
    private function declareEnvironment()
    {
        $this->ci->config->load('payment', true);
        if(!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production'){
            // LIVE
            $paymentConfig = $this->ci->config->item('production', 'payment')['payment_type']['dragonpay']['Easyshop'];
        }
        else{
            // SANDBOX
            $paymentConfig = $this->ci->config->item('testing', 'payment')['payment_type']['dragonpay']['Easyshop'];
        } 

        $this->merchantId = $paymentConfig['merchant_id'];
        $this->merchantPwd = $paymentConfig['merchant_password'];
        $this->url = $paymentConfig['webservice_url'];
        $this->ps = $paymentConfig['redirect_url'];
        $this->mode = $paymentConfig['mode'];
    }

    /**
     * Request payment token to dragonpay gateway
     * @param  float  $amount
     * @param  string $description
     * @param  string $email
     * @param  string $txnId
     * @return string
     */
    public function getTxnToken($amount,$description,$email,$txnId)
    {
        $errorCodes = $this->errorCodes;
        $ccy = 'PHP';
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $txnId,
            'amount' => $amount,
            'ccy' => $ccy,
            'description' => $description,
            'email' => $email,
            'mode'=> $this->mode,
            'param1' => 'Easyshop'
        ];
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('GetTxnToken',$param);
        $token = $result['GetTxnTokenResult'];

        if(strlen($token) <= 3){
            return '{"e":"0","m":"'.$errorCodes[$token].'","c":"'.$token.'"}';
        }
        else{
            return '{"e":"1","m":"SUCCESS","c":"'.$token.'","tid":"'.$txnId.'","u":"'.$this->ps.'?tokenid='.$token.'&mode='.$this->mode.'"}';
        }
    }

    /**
     * Get Status of the current payment via txnID
     * @param  string $txnId
     * @return string
     */
    public function getStatus($txnId)
    { 
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'txnId' => $txnId
        ];
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('GetTxnStatus',$param);

        return $result['GetTxnStatusResult'];
    }

    /**
     * Void payment transaction via txnID
     * @param  string $txnId
     * @return string
     */
    public function voidTransaction($txnId)
    {
        $param = [
            'merchantId' => $this->merchantId,
            'password' => $this->merchantPwd,
            'merchantTxnId' => $txnId
        ];
        $client = new nusoap_client($this->url, 'wsdl');
        $result = $client->call('CancelTransaction',$param);

        return $result['CancelTransactionResult'];
    }
}

/* End of file dragonpay.php */
/* Location: ./application/libraries/dragonpay.php */

<?php

namespace EasyShop\Notifications;

/**
 *  SMS Notification throught Semaphore service
 *  Chainable Methods except for sendSMS
 *
 *  @author stephenjanz <stephenjanz@easyshop.ph>
 */
class MobileNotification
{
    /**
     *  Configuration file from config/sms.php
     */
    private $smsConfig;

    /**
     *  SMS Specific Parameters
     */
    private $mobileNum;
    private $msg;

    /**
     *  Constructor
     */
    public function __construct($smsConfig)
    {
        $this->smsConfig = $smsConfig;

        return $this;
    }

    /**
     *  Echo details for object instance
     */
    public function __toString()
    {
        print('<pre>' .
            '<br>Mobile Num: ' . $this->mobileNum . PHP_EOL .
            '<br>Message: ' . $this->msg . PHP_EOL .
            '<br>SMS Config: '
        );
        print_r($this->smsConfig);
        
        return "";
    }

    /**
     *  Set Recipient mobile number
     *
     *  @param string $mobileNum
     */
    public function setMobile($mobileNum)
    {
        $this->mobileNum = ltrim((string)$mobileNum,'0');

        return $this;
    }

    /**
     *  Set SMS message
     *
     *  @param string $msg
     */
    public function setMessage($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     *  Send SMS through Semaphore service
     *
     *  @return JSON $output - JSON Data from Semaphore regarding SMS status
     */
    public function sendSMS()
    {
        $smsParam = array(
            'api' => $this->smsConfig['api'],
            'number' => $this->mobileNum,
            'message' => $this->msg,
            'from' => $this->smsConfig['from'],
        );
        $outbound_endpoint = $this->smsConfig['outbound_endpoint'];
        $smsParam_string = http_build_query($smsParam);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $outbound_endpoint);
        curl_setopt($ch,CURLOPT_POST, count($smsParam));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $smsParam_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        
        return $output;
    }

}

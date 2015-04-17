<?php

namespace EasyShop\Notifications;

use EasyShop\Entities\EsQueueStatus as EsQueueStatus;
use EasyShop\Entities\EsQueue as EsQueue;
use EasyShop\Entities\EsQueueType as EsQueueType;

/**
 *  SMS Notification throught Semaphore service
 *  Chainable Methods except for sendSMS
 *
 *  @author stephenjanz <stephenjanz@easyshop.ph>
 */
class MobileNotification
{
    /**
     *  Entity Manager Instance
     */
    private $em;

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
    public function __construct($em, $smsConfig)
    {
        $this->em = $em;
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
        if( preg_match('/^(8|9)[0-9]{9}$/', $this->mobileNum) ){
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

        return false;
    }

    /**
     * Store sms data to queue table.
     * @return boolean
     */
    public function queueSMS()
    {
        if(empty($this->mobileNum) || empty($this->msg)){
            return false;
        }

        $em = $this->em;
        $smsData = [
            'api' => $this->smsConfig['api'],
            'number' => $this->mobileNum,
            'message' => $this->msg,
            'from' => $this->smsConfig['from'],
        ];

        $queueType = $em->getRepository('EasyShop\Entities\EsQueueType')
                        ->find(EsQueueType::TYPE_MOBILE);
        $queueStatus = $em->getRepository('EasyShop\Entities\EsQueueStatus')
                          ->find(EsQueueStatus::QUEUED);

        $smsQueue = new EsQueue();
        $smsQueue->setData(json_encode($smsData))
                   ->setType($queueType)
                   ->setDateCreated(date_create(date("Y-m-d H:i:s")))
                   ->setStatus($queueStatus);
        $em->persist($smsQueue);
        $em->flush();

        if(strtolower(ENVIRONMENT) === 'development'){
            $this->sendSMS();
        }
        
        return true;
    }

}


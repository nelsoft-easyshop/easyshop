<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsDeviceToken as EsDeviceToken;
use EasyShop\Entities\EsApiType as EsApiType;

class Notification extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();

        //Making response json type
        header('Content-type: application/json');

        //Load service 
        $this->em = $this->serviceContainer['entity_manager'];
    }

    /**
     * URL to request add device token 
     * for push notification in mobile.
     */
    public function addDeviceToken()
    {
        $deviceToken = trim($this->input->post('deviceToken'));
        $apiType = trim($this->input->post('isIOS')) === "true" ? EsApiType::TYPE_IOS 
                                                                : EsApiType::TYPE_ANDROID; 

        $token = $this->em->getRepository('EasyShop\Entities\EsDeviceToken')
                          ->findOneBy(['deviceToken' => $deviceToken]);
 
        if(!$token && strlen($deviceToken) > 0){ 
            $newToken = new EsDeviceToken();
            $newToken->setDeviceToken($deviceToken);
            $newToken->setIsActive(EsDeviceToken::DEFAULT_ACTIVE);
            $newToken->setApiType($this->em->find('EasyShop\Entities\EsApiType', $apiType));
            $newToken->setDateadded(date_create());
            $this->em->persist($newToken);
            $this->em->flush();
        } 

        //always show 404 after request. not expecting any return after request.
        show_404();
    }
}


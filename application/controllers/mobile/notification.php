<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsDeviceToken as EsDeviceToken;
use EasyShop\Entities\EsApiType as EsApiType;
use EasyShop\Entities\OauthClients as OauthClients;

class Notification extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();

        //Load service 
        $this->em = $this->serviceContainer['entity_manager'];
    }

    /**
     * URL to request add device token 
     * for push notification in mobile.
     */
    public function addDeviceToken()
    {
        header('Content-type: application/json');
        $jwtContainer = $this->serviceContainer['json_web_token'];
        $jwt = trim($this->input->post('jwt'));
        $isSuccess = false; 
        $clientId = strtolower(ENVIRONMENT) === 'production'
                    ? OauthClients::PROD_CLIENTID
                    : OauthClients::DEV_CLIENTID;

        try {
            if($jwt){
                $oauthClients = $this->em->getRepository('EasyShop\Entities\OauthClients')
                                         ->find($clientId);
                $jwtObject = $jwtContainer->decode($jwt, $oauthClients->getClientSecret());
                if($jwtObject
                    && $oauthClients
                    && isset($jwtObject->api_type) 
                    && isset($jwtObject->deviceToken) 
                    && isset($jwtObject->client_id)){

                    if($jwtObject->client_id === $clientId){
                        $apiType = $this->em->getRepository('EasyShop\Entities\EsApiType')
                                            ->findOneBy(['apiType' => $jwtObject->api_type]);

                        if($apiType){
                            $isTokenSupported = $this->supportDeviceToken($jwtObject->deviceToken, $apiType->getIdApiType());
                            if($isTokenSupported){
                                $token = $this->em->getRepository('EasyShop\Entities\EsDeviceToken')
                                                  ->findOneBy(['deviceToken' => $jwtObject->deviceToken]);
                                if(!$token){
                                    $newToken = new EsDeviceToken();
                                    $newToken->setDeviceToken($jwtObject->deviceToken);
                                    $newToken->setIsActive(EsDeviceToken::DEFAULT_ACTIVE);
                                    $newToken->setApiType($apiType);
                                    $newToken->setDateadded(date_create());
                                    $this->em->persist($newToken);
                                    $this->em->flush();
                                }
                                $isSuccess = true;
                            }
                        }
                    }
                } 
            }
        }
        catch (Exception $e) {
            $isSuccess = false;
        }

        echo json_encode(['isSuccess' => $isSuccess]);
    }

    /**
     * Validate of device token is valid
     * @param  string  $token 
     * @param  integer $apiType
     * @return boolean
     */
    private function supportDeviceToken($token, $apiType)
    {
        if($apiType === EsApiType::TYPE_IOS){
            return (ctype_xdigit($token) && 64 === strlen($token));
        }
        elseif($apiType === EsApiType::TYPE_ANDROID){
            return (bool) preg_match('/^[0-9a-zA-Z\-\_]+$/i', $token);
        }
    }
}

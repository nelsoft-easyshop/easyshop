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
        $configLoader = $this->serviceContainer['config_loader'];
        $mcryptContainer = $this->serviceContainer['mcrypt'];

        $jwt = trim($this->input->post('jwt'));
        $mcrypt = trim($this->input->post('mcrypt'));
        $isSuccess = false; 
        $clientId = strtolower(ENVIRONMENT) === 'production'
                    ? OauthClients::PROD_CLIENTID
                    : OauthClients::DEV_CLIENTID;

        try {
            $oauthClients = $this->em->getRepository('EasyShop\Entities\OauthClients')
                                     ->find($clientId);
            $decryptString = null;
            if($jwt){
                $decryptString = $jwtContainer->decode($jwt, $oauthClients->getClientSecret(), true);
            }
            elseif($mcrypt){
                $mcryptConfig =  $configLoader->getItem('mcrypt');
                $mcryptContainer->setKey($mcryptConfig['16byte_key']);
                $mcryptContainer->setIv($mcryptConfig['16byte_iv']);
                $decryptString = json_decode($mcryptContainer->decrypt($mcrypt));
            }

            if($oauthClients
                && isset($decryptString->api_type) 
                && isset($decryptString->deviceToken) 
                && isset($decryptString->client_id)){

                if($decryptString->client_id === $clientId){
                    $apiType = $this->em->getRepository('EasyShop\Entities\EsApiType')
                                        ->findOneBy(['apiType' => $decryptString->api_type]);

                    if($apiType){
                        $isTokenSupported = $this->supportDeviceToken($decryptString->deviceToken, $apiType->getIdApiType());
                        if($isTokenSupported){
                            $token = $this->em->getRepository('EasyShop\Entities\EsDeviceToken')
                                              ->findOneBy(['deviceToken' => $decryptString->deviceToken]);
                            if(!$token){
                                $newToken = new EsDeviceToken();
                                $newToken->setDeviceToken($decryptString->deviceToken);
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


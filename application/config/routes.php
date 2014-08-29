<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['(?i)default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['(?i)404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

require_once __DIR__ . '/../src/EasyShop/Core/Configuration/Configuration.php';
$configService = new EasyShop\Core\Configuration\Configuration();

$enable_splash = false;
$bypass_IP = '124.104.99.90';

if($configService->isConfigFileExists()){
    $serverConfig = $configService->getConfigValue();
    if(isset($serverConfig['maintenance_on'])){
        $enable_splash = $serverConfig['maintenance_on'];
    }
    if(isset($serverConfig['maintenance_bypass_ip'])){
        $bypass_IP = $serverConfig['maintenance_bypass_ip'];
    }
}


$headers = apache_request_headers();
if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){
    $clientIP = ($_SERVER["HTTP_CF_CONNECTING_IP"]);
}
else if(isset($headers['X-Forwarded-For'])){
    $clientIP = $headers['X-Forwarded-For'];
}
else{
    $clientIP = $_SERVER['REMOTE_ADDR'];
}


if ($enable_splash && ($bypass_IP !== $clientIP) ){
    $route['default_controller'] = "home/splash";
    $route['404_override'] = 'home/pagenotfound';
    $route['(:any)'] = "home/splash";   
}
else {
    $route['default_controller'] = "home";
    #$route['404_override'] = 'home/pagenotfound';
    $route['404_override'] = 'home/userprofile';
    
    # CATEGORY
    $route['(?i)category/(:any)'] = 'product/category_page/$1'; # janz serafico
    $route['(?i)category_more'] = 'product/category_page_more'; # ryan vasquez
    $route['(?i)cat/all'] = 'product/categories_all'; # ryan vasquez

    #SEARCHING
    $route['(?i)search/(:any)'] = 'product_search/searchfaster/$1'; # ryan vasquez
    $route['search_more'] = 'product_search/search_more'; # ryan vasquez
    $route['search/suggest'] = 'product_search/sch_onpress'; # ryan vasquez

    #ADVANCE SEARCH
    $route['(?i)advsrch'] = 'product_search/advance_search'; # new search - rain jorque
    $route['(?i)advsrch/more'] = 'product_search/advance_search_more'; # new search - rain jorque

    #PASSWORD
    $route['(?i)chngepaswd'] = 'register/changepass'; # rain jorque 

    #ITEMS
    $route['(?i)item/(:any)'] = 'product/item/$1'; # sam gavinio


    #SELL ITEM
    $route['(?i)sell/step1'] = 'productUpload/step1'; # ryan vasquez
    $route['(?i)sell/step2'] = 'productUpload/step2'; # ryan vasquez
    $route['sell/processing'] = 'productUpload/step2_2'; # ryan vasquez
    $route['(?i)sell/step3'] = 'productUpload/step3'; # ryan vasquez
    $route['(?i)sell/step4'] = 'productUpload/step4'; # ryan vasquez
    $route['(?i)sell/finishupload'] = 'productUpload/finishProductUpload'; #js

    # USER
    $route['(?i)me'] = 'memberpage'; # janz

    #EDIT ITEM
    $route['(?i)sell/edit/step1'] = 'productUpload/editStep1'; # sam gavinio
    $route['(?i)sell/edit/step2'] = 'productUpload/step2edit2'; # sam gavinio
    $route['sell/edit/processing'] = 'productUpload/step2edit2Submit';  # sam gavinio

    #REMOVE DRAFT
    $route['(?i)sell/draft/remove'] = 'productUpload/deleteDraft';  # sam gavinio

    #LANDING PAGE 
    $route['(?i)registration/success'] = 'register/success';  # sam gavinio

    #PAYMENT CASH ON DELIVERY
    $route['(?i)pay/cashondelivery'] = 'payment/payCashOnDelivery';  # ryan vasquez
    #PAYMENT DIRECT BANK DEPOSIT
    $route['(?i)pay/directbank'] = 'payment/payCashOnDelivery';  # ryan vasquez
    #PAYMENT PAYPAL
    $route['(?i)pay/setting/paypal'] = 'payment/paypal_setexpresscheckout';  # ryan vasquez
    $route['(?i)pay/paypal'] = 'payment/paypal';  # ryan vasquez

    $route['(?i)payment/success/(:any)'] = 'payment/paymentSuccess/$1';

    #MAINTENANCE ROUTE
    $route['(?i)maintenance'] = 'home/splash';

    #PROMO CATEGORY PAGE
    $route['(?i)deals'] = 'promo/EasyDeals/category_promo';
    $route['(?i)scratch-and-win'] = 'promo/ScratchCard/scratchCardPromo';

    $route['(?i)policy'] = 'home/policy';
    $route['(?i)terms'] = 'home/terms';
    $route['(?i)faq'] = 'home/faq';
    $route['(?i)contact'] = 'home/contact';
    $route['(?i)guide/buy'] = 'home/guide_buy';
    $route['(?i)guide/sell'] = 'home/guide_sell';

    $route['(?i)subscribe'] = 'register/subscribe';
    
    #WEBSERVICE
    $route['homewebservice'] = 'webservice/homewebservice';
    $route['accountservice'] = 'webservice/accountservice';
}

/* End of file routes.php */
/* Location: ./application/config/routes.php */
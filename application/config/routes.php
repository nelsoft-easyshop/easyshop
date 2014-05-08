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

//$route['default_controller'] = "home/comingSoon";
$route['default_controller'] = "landingpage";
$route['404_override'] = 'home/pagenotfound';

# CATEGORY
$route['(?i)category/(:num)/(:any)'] = 'product/categorySearch/$1/$2'; # ryan vasquez
$route['(?i)category/loadproduct'] = 'product/loadOtherCategorySearch'; # ryan vasquez
$route['(?i)category/all'] = 'product/categories_all'; # ryan vasquez

#SEARCHING
$route['(?i)search/(:any)'] = 'product/sch/$1'; # ryan vasquez
$route['search/load_search_other_product'] = 'product/sch_scroll'; # ryan vasquez

#ADVANCE SEARCH
$route['(?i)advsrch'] = 'product_search/advsrch'; # new search - rain jorque
$route['(?i)advsrch/scroll_product'] = 'product_search/load_product'; # new search - rain jorque

#PASSWORD
$route['(?i)chngepaswd'] = 'register/changepass'; # rain jorque 

#ITEMS
$route['(?i)item/(:any)'] = 'product/item/$1'; # ryan vasquez
#$route['(?i)item/(:any)'] = 'product/item/$1'; # ryan vasquez
$route['search/suggest'] = 'product/sch_onpress'; # ryan vasquez

#SELL ITEM
$route['(?i)sell/step1'] = 'productUpload/step1'; # ryan vasquez
$route['(?i)sell/step2'] = 'productUpload/step2'; # ryan vasquez
$route['sell/processing'] = 'productUpload/step2_2'; # ryan vasquez
$route['(?i)sell/step3'] = 'productUpload/step3'; # ryan vasquez
$route['sell/shippinginfo'] = 'productUpload/step3Submit';
$route['sell/preview'] = 'productUpload/previewItem';
$route['(?i)sell/step4'] = 'productUpload/step4'; # ryan vasquez

#User and Vendor
$route['(?i)me'] = 'memberpage'; # janz
$route['(?i)vendor/(:any)'] = 'memberpage/vendor/$1'; # janz

#EDIT ITEM
$route['(?i)sell/edit/step1'] = 'productUpload/editStep1'; # sam gavinio
$route['(?i)sell/edit/step2'] = 'productUpload/editStep2'; # sam gavinio
$route['sell/edit/processing2'] = 'productUpload/editStep2Submit';  # sam gavinio

#REMOVE DRAFT
$route['(?i)sell/draft/remove'] = 'productUpload/deleteDraft';  # sam gavinio

#LANDING PAGE 
$route['(?i)registration/success'] = 'landingpage/success/register';  # sam gavinio
$route['(?i)subscription/success'] = 'landingpage/success/subscribe';  # sam gavinio

#PAYMENT CASH ON DELIVERY
$route['(?i)pay/cashondelivery'] = 'payment/payCashOnDelivery';  # ryan vasquez
#PAYMENT PAYPAL
$route['(?i)pay/setting/paypal'] = 'payment/paypal_setexpresscheckout';  # ryan vasquez
$route['(?i)pay/paypal'] = 'payment/paypal';  # ryan vasquez

$route['(?i)payment/success/(:any)'] = 'payment/paymentSuccess/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
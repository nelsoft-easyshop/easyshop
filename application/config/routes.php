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
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home/comingSoon";
$route['404_override'] = 'home/pagenotfound';

# CATEGORY
$route['category/(:num)/(:any)'] = 'product/searchbycategory/$1/$2'; # ryan vasquez
$route['category/load_other_product'] = 'product/load_product'; # ryan vasquez

$route['category/all'] = 'product/categories_all'; # ryan vasquez

#SEARCHING
$route['search/(:any)'] = 'product/sch/$1'; # ryan vasquez
$route['search/load_search_other_product'] = 'product/sch_scroll'; # ryan vasquez

# ADVANCE SEARCH
$route['advance_search/load_other_product'] = 'product_search/load_product'; # rain jorque

#ITEMS
$route['item/(:num)/(:any)'] = 'product/view/$1/$2'; # ryan vasquez

#SELL ITEM
 
$route['sell/step1'] = 'productUpload/step1'; # ryan vasquez
$route['sell/step2'] = 'productUpload/step2'; # ryan vasquez
$route['sell/processing'] = 'productUpload/step2_2'; # ryan vasquez
$route['sell/step3'] = 'productUpload/step3'; # ryan vasquez
$route['sell/shippinginfo'] = 'productUpload/step3Submit';
$route['sell/step4'] = 'productUpload/step4'; # ryan vasquez

#User and Vendor
$route['me'] = 'memberpage'; # janz
$route['vendor/(:any)'] = 'memberpage/vendor/$1'; # janz

#EDIT ITEM
$route['sell/edit/step1'] = 'productUpload/editStep1'; # sam gavinio
$route['sell/edit/step2'] = 'productUpload/editStep2'; # sam gavinio
$route['sell/edit/processing2'] = 'productUpload/editStep2Submit';  # sam gavinio

/* End of file routes.php */
/* Location: ./application/config/routes.php */
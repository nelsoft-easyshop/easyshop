<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *  Memberpage controller
 *
 *  @author Sam Gavinio
 *  @author Stephen Janz Serafico
 *  @author Rain Jorque
 *
 */

use EasyShop\Entities\EsMember as EsMember;
use EasyShop\Entities\EsCat as EsCat;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsOrderProductStatus as EsOrderProductStatus;
use EasyShop\Entities\EsMemberFeedback as EsMemberFeedback;
use EasyShop\Entities\EsLocationLookup as EsLocationLookup;
use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\Entities\EsOrderStatus;

class Memberpage extends MY_Controller
{

    /**
     * Content xml file location within resource
     *
     * @var string
     */
    private $contentXmlFile;

    /**
     * Number of feeds item per page
     *
     * @var integer
     */
    public $salesPerPage = 10;

    /**
     * Number of feeds item per page
     *
     * @var integer
     */
    public $feedbackLimit = 10;

    /**
     * Number of transaction per page
     *
     * @var integer
     */
    public $transactionRowCount = 10;

    /**
     *  Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("memberpage_model");
        $this->load->model('register_model');
        $this->load->model('product_model');
        $this->load->model('payment_model');
        $this->form_validation->set_error_delimiters('', '');
        $this->qrManager = $this->serviceContainer['qr_code_manager'];
        $xmlResourceService = $this->serviceContainer['xml_resource'];
        $this->contentXmlFile =  $xmlResourceService->getContentXMLfile();
        $this->accountManager = $this->serviceContainer['account_manager'];        
        $this->em = $this->serviceContainer['entity_manager'];
        $this->transactionManager = $this->serviceContainer['transaction_manager'];
        $this->esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        $this->esMemberRepo = $this->em->getRepository('EasyShop\Entities\EsMember');
        $this->esOrderProductRepo = $this->em->getRepository('EasyShop\Entities\EsOrderProduct');
    }

    
    /**
     * display dashboard view
     * @return view
     */
    public function index()
    {
       $userManager = $this->serviceContainer['user_manager'];
        $productManager = $this->serviceContainer['product_manager'];
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $esVendorSubscribeRepo = $this->em->getRepository('EasyShop\Entities\EsVendorSubscribe');
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        $esOrderProductRepo = $this->em->getRepository('EasyShop\Entities\EsOrderProduct');
        $memberId = $this->session->userdata('member_id');
        $feedbackLimit = $this->feedbackLimit;
        $salesPerPage = $this->salesPerPage;
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find($memberId);
        if($member){
            $paginationData['isHyperLink'] = false;
            $userAvatarImage = $userManager->getUserImage($memberId);
            $userBannerImage = $userManager->getUserImage($memberId,"banner");
            $userFollowers = $esVendorSubscribeRepo->getFollowers($memberId);
            $userFollowing = $esVendorSubscribeRepo->getUserFollowing($memberId);
            $userProductCount = $esProductRepo->getUserProductCount($memberId);
            $deleteConditions = [EsProduct::ACTIVE];
            $draftConditions = [EsProduct::ACTIVE];
            $userActiveProductCount = $esProductRepo->getUserProductCount($memberId, $deleteConditions, $draftConditions);
            $userActiveProducts = $productManager->getProductsByUser($memberId, $deleteConditions, $draftConditions);
            $paginationData['lastPage'] = ceil($userActiveProductCount / $productManager::PRODUCT_COUNT_DASHBOARD);
            $activeProductsData = [
                'products' => $userActiveProducts,
                'pagination' => $this->load->view('pagination/default', $paginationData, true),
            ];
            $activeProductView = $this->load->view('partials/dashboard-products', $activeProductsData, true);
            $deleteConditions = [EsProduct::DELETE];
            $draftConditions = [EsProduct::ACTIVE,EsProduct::DRAFT];
            $userDeletedProductCount =  $esProductRepo->getUserProductCount($memberId, $deleteConditions, $draftConditions);
            
            $deleteConditions = [EsProduct::ACTIVE];
            $draftConditions = [EsProduct::DRAFT];
            $userDraftedProductCount = $esProductRepo->getUserProductCount($memberId, $deleteConditions, $draftConditions);
            
            $profilePercentage = $userManager->getProfileCompletePercent($member);  
            $reviewTotalCount = $esMemberFeedbackRepo->getUserTotalFeedBackCount($memberId, false);
            $feedBackTotalCount = $esMemberFeedbackRepo->getUserTotalFeedBackCount($memberId);
            $memberRating = $esMemberFeedbackRepo->getUserFeedbackAverageRating($memberId);
            $allFeedBackView = $this->load->view('pages/user/dashboard/dashboard-feedbacks', null, true);
            $ongoingBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId);
            $ongoingSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId);
            $completeBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId, false);
            $completeSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId, false);
            $member->validatedStoreName = $member->getStoreName();
            $dashboardHomeData = [
                'member' => $member,
                'avatarImage' => $userAvatarImage,
                'bannerImage' => $userBannerImage,
                'countryId' => EsLocationLookup::PHILIPPINES_LOCATION_ID,
                'followerCount' => $userFollowers['count'],
                'followingCount' => $userFollowing['count'],
                'productCount' => $userProductCount,
                'activeProductCount' => $userActiveProductCount,
                'deletedProductCount' => $userDeletedProductCount,
                'draftedProductCount' => $userDraftedProductCount,
                'soldProductCount' => $ongoingSoldTransactionsCount["productCount"] + $completeSoldTransactionsCount["productCount"],
                'activeProductView' => $activeProductView,
                'memberRating' => $memberRating,
                'feedBackTotalCount' => $feedBackTotalCount,
                'reviewTotalCount' => $reviewTotalCount,
                'profilePercentage' => $profilePercentage,
                'allFeedBackView' => $allFeedBackView,
                'ongoingBoughtTransactionsCount' => $ongoingBoughtTransactionsCount,
                'ongoingSoldTransactionsCount' => $ongoingSoldTransactionsCount["transactionsCount"],
                'completeBoughtTransactionsCount' => $completeBoughtTransactionsCount,
                'completeSoldTransactionsCount' => $completeSoldTransactionsCount["transactionsCount"]
            ];

            $dashboardHomeView = $this->load->view('pages/user/dashboard/dashboard-home', $dashboardHomeData, true);
            $dashboardData['dashboardHomeView'] = $dashboardHomeView;
            $dashboardData['tab'] = $this->input->get('tab');
            
            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                'title' =>  "Dashboard | Easyshop.ph",
            ];
    
            $this->load->spark('decorator');    
            $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/user/dashboard/dashboard-primary',$dashboardData);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));
        }
        else{
            redirect('/login', 'refresh');
        }
    }

    
    /**
     * Qr code generator view
     *
     */
    public function generateQrCode()
    {
        if(!$this->session->userdata('member_id')){
            redirect('/', 'refresh');
        }
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($this->session->userdata('member_id'));
        $storeLink = base_url() . $member->getSlug();
        $this->qrManager->save($storeLink, $member->getSlug(), 'L', $this->qrManager->getImageSizeForPrinting(), 0);
        $data = [
            'qrCodeImageName' => $this->qrManager->getImagePath($member->getSlug()),
            'slug' => $member->getSlug(),
            'storeLink' =>$storeLink
        ];

        $this->load->view("pages/user/dashboard/dashboard-qr-code", $data);
    }

    /**
     *  Provides change email functionality
     *  @return JSON
     */
    public function edit_email()
    {
        $um = $this->serviceContainer['user_manager'];
        $memberId = $this->session->userdata('member_id');

        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];

        $rules = $formValidation->getRules('personal_info');
        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                    ->setMethod('POST')
                    ->add('email', 'text', array('constraints' => $rules['email']))
                    ->getForm();    
        $form->submit([
            'email' => $this->input->post('email')
        ]);        

        if($form->isValid()){
            $formData = $form->getData();
            $validEmail = (string)$formData['email'];
            $um->setUser($memberId)
               ->setEmail($validEmail);
            $boolResult = $um->save();

            $serverResponse = array(
                'result' => $boolResult ? 'success' : 'error'
                , 'error' => $boolResult ? '' : $um->errorInfo()
            );
        }
        else{
            $serverResponse = array(
                'result' => 'fail'
                , 'error' => $formErrorHelper->getFormErrors($form)
            );
        }

        echo json_encode($serverResponse);                          
    }

    /**
     *  Used to edit personal data.
     *  Personal Information tab - immediately visible section (e.g. Nickname, birthday, mobile, etc.)
     *
     *  @return JSON
     */
    public function edit_personal()
    {
        $em = $this->serviceContainer['entity_manager'];
        $um = $this->serviceContainer['user_manager'];
        $memberId = $this->session->userdata('member_id');
        $memberEntity = $em->find('EasyShop\Entities\EsMember', $memberId);

        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];

        $rules = $formValidation->getRules('personal_info');
        $form = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                    ->setMethod('POST')
                    ->add('fullname', 'text')
                    ->add('gender', 'text', ['constraints' => $rules['gender']])
                    ->add('dateofbirth', 'text', ['constraints' => $rules['dateofbirth']])
                    ->add('mobile', 'text', ['constraints' => $rules['mobile']])
                    ->getForm();

        $form->submit([
             'fullname' => $this->input->post('fullname')
            , 'gender' => $this->input->post('gender')
            , 'dateofbirth' => $this->input->post('dateofbirth')
            , 'mobile' => $this->input->post('mobile')
        ]);

        if($form->isValid()){
            $formData = $form->getData();
            $validFullname = (string)$formData['fullname'];
            $validGender = strlen($formData['gender']) === 0 ? EasyShop\Entities\EsMember::DEFAULT_GENDER : strtoupper($formData['gender']);
            $validDateOfBirth = strlen($formData['dateofbirth']) === 0 ? EasyShop\Entities\EsMember::DEFAULT_DATE : $formData['dateofbirth'];
            $validMobile = (string)$formData['mobile'];
            $um->setUser($memberId)
               ->setMobile($validMobile)
               ->setMemberMisc([
                     'setFullname' => $validFullname
                    , 'setGender' => $validGender
                    , 'setBirthday' => new DateTime($validDateOfBirth)
                    , 'setLastmodifieddate' => new DateTime('now')
                ]);

            $boolResult = $um->save();

            $serverResponse = array(
                'result' => $boolResult ? 'success' : 'error'
                , 'error' => $boolResult ? '' : $um->errorInfo()
            );
        }
        else{
            $serverResponse = array(
                'result' => 'fail'
                , 'error' => $formErrorHelper->getFormErrors($form)
            );
        }

        echo json_encode($serverResponse);
    }

    /**
     *  Used to edit address under Personal Information Tab
     *  Returns json encoded success, fail, or error, with error message
     *
     *  @return JSON
     */
    public function edit_address()
    {
        if(($this->input->post('personal_profile_address_btn'))&&($this->form_validation->run('personal_profile_address')))
        {
            $postdata = array(
                'stateregion' => $this->input->post('stateregion'),
                'city' => $this->input->post('city'),
                'address' => $this->input->post('address'),
                'country' => $this->input->post('country'),
                'addresstype' => 0,
                'consignee' => '',
                'mobile' => '',
                'telephone' => '',
                'lat' => $this->input->post('temp_lat'),
                'lng' => $this->input->post('temp_lng')
            );

            $temp = array(
                'stateregion_orig' => $this->input->post('stateregion_orig'),
                'city_orig' => $this->input->post('city_orig'),
                'address_orig' => $this->input->post('address_orig'),
                'map_lat' => $this->input->post('map_lat'),
                'map_lng' => $this->input->post('map_lng')
            );

            if( ( ($temp['stateregion_orig'] != $postdata['stateregion']) || ($temp['city_orig'] != $postdata['city']) || ($temp['address_orig'] != $postdata['address']) ) 
                && ($temp['map_lat'] == $postdata['lat'] && $temp['map_lng'] == $postdata['lng']) ) {
                $postdata['lat'] = 0;
                $postdata['lng'] = 0;
            }

            $uid = $this->session->userdata('member_id');
            $addressID = $this->memberpage_model->getAddress($uid,0)['id_address'];
            $result = $this->memberpage_model->editAddress($uid, $postdata, $addressID);

            $data = $this->memberpage_model->get_member_by_id($uid);

            $data['result'] = $result ? 'success':'fail';
            $data['errmsg'] = $result ? '' : 'Database update error.';
        }
        else{
            $data['result'] = 'error';
            $data['errmsg'] = 'Failed to validate form.';
        }
        $this->output->set_output(json_encode($data));
    }


    /**
     *  Export Sold transactions to CSV file
     */
    public function exportSellTransactions()
    {       
        $soldTransaction["transactions"] = $this->transactionManager
                                                ->getSoldTransactionDetails(
                                                                          $this->session->userdata('member_id'),
                                                                          (bool) $this->input->get("isOngoing"),
                                                                          0,
                                                                          PHP_INT_MAX,
                                                                          $this->input->get("invoiceNo"),
                                                                          $this->input->get("paymentMethod")
                                                                          );

        $prodSpecs = "";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=soldtransactions.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, [' Transaction Number '
                                , 'Product Name'
                                , 'Date of Transaction'
                                ,'Buyers Name'
                                ,'Order Quantity'
                                ,'Payment Method'
                                ,'Price'
                                ,'Product Specifications']);
        foreach($soldTransaction["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                if(isset($product["attr"])) {
                    foreach($product["attr"] as $attr => $attrValue ) {
                         $prodSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue))." / ";
                    }
                }
                else {
                    $prodSpecs = "N/A";
                }

                fputcsv($output, [$value["invoiceNo"]
                                  , html_escape($product["name"])
                                  , $value["dateadded"]->format('Y-m-d H:i:s')
                                  , html_escape($value["buyerStoreName"])
                                  , $value["orderQuantity"]
                                  , ucwords(strtolower($value["paymentMethod"]))
                                  , number_format((float)$product["price"], 2, '.', '')
                                  , $prodSpecs
                ]);                   
                $prodSpecs = "";                
            }
        }
    }

    /**
     *  Export Buy transactions to CSV file
     */
    public function exportBuyTransactions()
    {             
        $boughTransactions["transactions"] = $this->transactionManager
                                                  ->getBoughtTransactionDetails(
                                                                                $this->session->userdata('member_id'),
                                                                                (bool) $this->input->get("isOngoing"),
                                                                                0,
                                                                                PHP_INT_MAX,
                                                                                $this->input->get("invoiceNo"),
                                                                                $this->input->get("paymentMethod")
                                                                              );      

        $prodSpecs = "";
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=boughttransactions.csv');
        $output = fopen('php://output', 'w');

        fputcsv($output, [' Transaction Number '
                                , 'Product Name'
                                , 'Date of Transaction'
                                ,'Sellers Name'
                                ,'Order Quantity'
                                ,'Payment Method'
                                ,'Price'
                                ,'Product Specifications']);

        foreach($boughTransactions["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $buyerName = $product["sellerStoreName"];
                if(isset($product["attr"])) {
                    foreach($product["attr"] as $attr => $attrValue ) {
                         $prodSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue))." / ";
                    }
                }
                else {
                    $prodSpecs = "N/A";
                }     
                fputcsv($output, [ $value["invoiceNo"]
                                   , html_escape($product["name"])
                                   , $value["dateadded"]->format('Y-m-d H:i:s')
                                   , html_escape($buyerName)
                                   , $value["orderQuantity"]
                                   , ucwords(strtolower($value["paymentMethod"]))
                                   , number_format($product["price"], 2, '.', '')
                                   , $prodSpecs
                ]);    
                $prodSpecs = "";
                $buyerName = "";                                      
            }
        }
    }

    /**
     * Returns bought on-going transactions of the user
     *  @return VIEW
     */
    public function printBuyTransactions()
    {   

        $transactions["transactions"] = $this->transactionManager
                                             ->getBoughtTransactionDetails(
                                                                            $this->session->userdata('member_id'),
                                                                            (bool) $this->input->post("isOngoing"),
                                                                            0,
                                                                            PHP_INT_MAX,
                                                                            $this->input->post("invoiceNo"),
                                                                            $this->input->post("paymentMethod")
                                                                          );

        foreach ($transactions["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $data = [];
                $productSpecs = "";

                if(isset($product["attr"]) && count($product["attr"] > 0)) {
                     foreach($product["attr"] as $attr => $attrValue ) {
                        $productSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue))." / ";
                     }
                }

                $data = [
                    "invoiceNo" => $value["invoiceNo"],
                    "productName" => $product["name"],
                    "sellerStoreName" => $product["sellerStoreName"],
                    "productSpecs" => $productSpecs,
                    "dateAdded" => $value["dateadded"]->format('Y-m-d H:i:s'),
                    "orderQuantity" => $product["orderQuantity"],
                    "paymentMethod" => $value["paymentMethod"],                
                    "productPrice" => $product["price"],
                    "productId" => $product["idOrderProduct"]
                ];

                $boughtTransactions["transactions"][] = $data;
            }

        }
        $this->load->view("pages/user/printboughttransactions", $boughtTransactions);
    }

    /**
     * Returns sold on-going transactions of the user
     *  @return VIEW
     */
    public function printSellTransactions()
    {

        $transactions["transactions"] = $this->transactionManager
                                             ->getSoldTransactionDetails(
                                                                      $this->session->userdata('member_id'),
                                                                      (bool) $this->input->post("isOngoing"),
                                                                      0,
                                                                      PHP_INT_MAX,
                                                                      $this->input->post("invoiceNo"),
                                                                      $this->input->post("paymentMethod")
                                                                      );  

        foreach ($transactions["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $data = [];   
                $productSpecs = "";                 
                if(isset($product["attr"]) && count($product["attr"] > 0)) {
                     foreach($product["attr"] as $attr => $attrValue ) {
                        $productSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue))." / ";
                     }
                }

                $data = [
                    "invoiceNo" => $value["invoiceNo"],
                    "productName" => $product["name"],
                    "buyerStoreName" => $value["buyerStoreName"],
                    "productSpecs" => $productSpecs,
                    "dateAdded" => $value["dateadded"]->format('Y-m-d H:i:s'),
                    "orderQuantity" => $product["orderQuantity"],
                    "paymentMethod" => $value["paymentMethod"],                
                    "productPrice" => $product["price"],
                    "productId" => $product["idOrderProduct"]
                ];

                $soldTransactions["transactions"][] = $data;
            }


        }

        $this->load->view("pages/user/printselltransactionspage", $soldTransactions);
    }

    /**
     *  External callback function used in form_validation of CodeIgniter
     *
     *  @return boolean
     */
    public function external_callbacks( $postdata, $param )
    {
        $param_values = explode( ',', $param );
        $model = $param_values[0];
        $this->load->model( $model );
        $method = $param_values[1];
        if( count( $param_values ) > 2 ) {
            array_shift( $param_values );
            array_shift( $param_values );
            $argument = $param_values;
        }
        if( isset( $argument )){
            $callback_result = $this->$model->$method( $postdata, $argument );
        }
        else{
            $callback_result = $this->$model->$method( $postdata );
        }
        
        return $callback_result;
    }

    /**
     *  Used to edit information under Delivery Address Tab. 
     *  Able to detect changes in address necessary for updating
     *      latitude and longitude values in database, used for Google Maps.
     *  Returns json encoded success or fail with error message
     *
     *  @return JSON
     */
    public function edit_consignee_address()
    {

        if($this->input->post('c_deliver_address_btn')) {
            $userMgr = $this->serviceContainer['user_manager'];
            $result = $userMgr->setAddress(
                    $this->input->post('c_address'),
                    $this->input->post('c_stateregion'),
                    $this->input->post('c_city'),
                    $this->session->userdata('member_id'),
                    $this->input->post('consignee'),
                    $this->input->post('c_mobile'),
                    $this->input->post('c_telephone'),
                    $this->input->post('temp_lat'),
                    $this->input->post('temp_lng')
                );
            echo json_encode($result);
        }
    }


    /**
     *  Used to add feedback to SELLER or BUYER under Transactions Tab
     *  Returns 1 on success, 0 otherwise
     *
     *  @return integer
     */
    public function addFeedback()
    {
        if($this->input->post('order_id') && $this->input->post('feedback-field') && $this->form_validation->run('add_feedback_transaction')){
            $result = false;
            $data = [
                'uid' => $this->session->userdata('member_id'),
                'for_memberid' => $this->input->post('for_memberid'),
                'feedb_msg' => $this->input->post('feedback-field'),
                'feedb_kind' => $this->input->post('feedb_kind'),
                'order_id' => $this->input->post('order_id'),
                'rating1' => $this->input->post('rating1'),
                'rating2' => $this->input->post('rating2'),
                'rating3' => $this->input->post('rating3')
            ];

            if ( !(bool) $data['feedb_kind']) {
                $transacData = [
                    'buyer' => $data['uid'],
                    'seller' => $data['for_memberid'],
                    'order_id' => $data['order_id']
                ];
            }
            else if ( (bool) $data['feedb_kind']) {
                $transacData = [
                    'buyer' => $data['for_memberid'],
                    'seller' => $data['uid'],
                    'order_id' => $data['order_id']
                ];
            }
            $doesTransactionExists = $this->transactionManager->doesTransactionExist($transacData['order_id'], $transacData['buyer'], $transacData['seller']);
            if ($doesTransactionExists) {
                $member = $this->esMemberRepo->find($data['uid']);
                $forMember = $this->esMemberRepo->find($data['for_memberid']);
                $order = $this->em->getRepository('EasyShop\Entities\EsOrder')->find($data['order_id']);
                $doesFeedbackExists = $this->esMemberFeedbackRepo
                                           ->findOneBy([
                                               'member' => $member,
                                               'forMemberid' => $forMember,
                                               'feedbKind' => $data['feedb_kind'],
                                               'order' => $order
                                           ]);
                if (! (bool) $doesFeedbackExists) {
                    $result = $this->esMemberFeedbackRepo
                                   ->addFeedback(
                                       $member,
                                       $forMember,
                                       $data['feedb_msg'],
                                       $data['feedb_kind'],
                                       $order,
                                       $data['rating1'],
                                       $data['rating2'],
                                       $data['rating3']
                                   );
                }
            }

            echo (bool) $result;
        }
        else{
            echo false;
        }
    }

    /**
     *  Function used to handle user responses on transactions under Transactions Tab
     *  Forward to seller (status = 1), return to buyer (status = 2), Cash On Delivery (status = 3)
     *
     *  Also handles DragonPay and Bank Deposit response functions
     *  Returns json encoded success or fail with error message
     *
     *  @return JSON
     */
    public function transactionResponse() {
        $serverResponse = [
            'result' => 'fail',
            'error' => 'Failed to validate form'
        ];

        $data['transaction_num'] = $this->input->post('transaction_num');
        $data['invoice_num'] = $this->input->post('invoice_num');
        $data['member_id'] = $this->session->userdata('member_id');

        $emailService = $this->serviceContainer['email_notification'];
        $smsService = $this->serviceContainer['mobile_notification'];

        $this->config->load('email', true);
        $imageArray = $this->config->config['images'];
        $imageArray[] = "/assets/images/appbar.home.png";
        $imageArray[] = "/assets/images/appbar.message.png";

        /**
         *  DEFAULT RESPONSE HANDLER
         *  Item Received / Cancel Order / Complete(CoD)
         */
        if( $this->input->post('buyer_response') || $this->input->post('seller_response') || $this->input->post('cash_on_delivery') ){

            if ( $this->input->post('buyer_response') ) {
                $data['order_product_id'][0] = $this->input->post('buyer_response');
                $data['status'] = EsOrderProductStatus::FORWARD_SELLER;
            }
            else if ( $this->input->post('seller_response') ) {
                $data['order_product_id'][0] = $this->input->post('seller_response');
                $data['status'] = EsOrderProductStatus::RETURNED_BUYER;
            }
            else if ( $this->input->post('cash_on_delivery') ) {
                $data['order_product_id'][0] = $this->input->post('cash_on_delivery');
                $data['status'] = EsOrderProductStatus::CASH_ON_DELIVERY;
            }

            if ( (bool) stripos($data['order_product_id'][0], '-')) {
                $productIds = explode('-', $data['order_product_id'][0]);
                $data['order_product_id'] = $productIds;
            }

            if (is_array($data['order_product_id'])) {
                foreach ($data['order_product_id'] as $orderProductId) {
                    $result = $this->transactionManager->updateTransactionStatus($data['status'], $orderProductId, $data['transaction_num'], $data['invoice_num'], $data['member_id']);
                    if( $result['o_success'] >= 1 ) {
                        $parseData = $this->transactionManager->getOrderProductTransactionDetails($data['transaction_num'], $orderProductId, $data['member_id'], $data['invoice_num'], $data['status']);
                        $parseData['store_link'] = base_url() . $parseData['user_slug'];
                        $parseData['msg_link'] = base_url() . "messages/#" . $parseData['user'];
                        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                                 ->getSocialMediaLinks();
                        $parseData['facebook'] = $socialMediaLinks["facebook"];
                        $parseData['twitter'] = $socialMediaLinks["twitter"];

                        $hasNotif = false;
                        if (
                            (int) $data['status'] === (int) EsOrderProductStatus::FORWARD_SELLER ||
                            (int) $data['status'] === (int) EsOrderProductStatus::RETURNED_BUYER ||
                            (int) $data['status'] === (int) EsOrderProductStatus::CASH_ON_DELIVERY
                        ) {
                            $hasNotif = true;
                        }
                        switch ($data['status']) {
                            case EsOrderProductStatus::FORWARD_SELLER :
                                $emailSubject = $this->lang->line('notification_forwardtoseller');
                                $emailMsg = $this->parser->parse('emails/email_itemreceived',$parseData,true);
                                $smsMsg = $parseData['user'] . ' has just confirmed receipt of your product from Invoice # : ' . $parseData['invoice_no'];
                                break;
                            case EsOrderProductStatus::RETURNED_BUYER :
                                $emailSubject = $this->lang->line('notification_returntobuyer');
                                $emailMsg = $this->parser->parse('emails/return_payment', $parseData, true);
                                $smsMsg = $parseData['user'] . ' has just confirmed to return your payment for a product in Invoice # : ' . $parseData['invoice_no'];
                                break;
                            case EsOrderProductStatus::CASH_ON_DELIVERY :
                                $emailSubject = $this->lang->line('notification_forwardtoseller');
                                $emailMsg = $this->parser->parse('emails/email_cod_complete', $parseData, true);
                                $smsMsg = $parseData['user'] . ' has just completed your CoD transaction with Invoice # : ' . $parseData['invoice_no'];
                                break;
                        }
                    }
                }

                if($hasNotif){
                    $emailService->setRecipient($parseData['email'])
                                 ->setSubject($emailSubject)
                                 ->setMessage($emailMsg, $imageArray)
                                 ->sendMail();
                    $smsService->setMobile($parseData['mobile'])
                               ->setMessage($smsMsg)
                               ->sendSms();
                }
            }
            $serverResponse['error'] = $result['o_success'] >= 1 ? '' : 'Server unable to update database.';
            $serverResponse['result'] = $result['o_success'] >= 1 ? 'success':'fail';

        /**
         *  DRAGONPAY HANDLER
         */
        }
        else if ( $this->input->post('dragonpay') ) {
            $this->load->library('dragonpay');

            $getTransaction = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                                  ->findOneBy([
                                                      'idOrder' => $data['transaction_num'],
                                                      'invoiceNo' => $data['invoice_num'],
                                                      'buyer' => $data['member_id']
                                                  ]);

            if ( (int) count($getTransaction) === 1) {
                $dragonpayResult = $this->dragonpay->getStatus($getTransaction->getTransactionId());

                if ($dragonpayResult == 'S') {
                    $serverResponse['result'] = 'success';
                    $serverResponse['error'] = '';
                }
                else if($dragonpayResult == 'P' || $dragonpayResult == 'U'){
                    $serverResponse['error'] = 'Kindly follow the instructions in the e-mail sent to you by Dragonpay.';
                }
            }
            else{
                $serverResponse['error'] = 'Transaction does not exist.';
            }
            /**
             *  BANK DEPOSIT HANDLER
             */
        }
        else if( $this->input->post('bank_deposit') && $this->form_validation->run('bankdeposit') ) {
            $getTransaction = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                       ->findOneBy([
                                           'idOrder' => $data['transaction_num'],
                                            'invoiceNo' => $data['invoice_num'],
                                            'buyer' => $data['member_id']
                                       ]);

            if ( (int) count($getTransaction) === 1 ) {
                $postData = [
                    'order_id' => $data['transaction_num'],
                    'bank' => $this->input->post('bank'),
                    'ref_num' => $this->input->post('ref_num'),
                    'amount' => preg_replace('/,/', '', $this->input->post('amount')),
                    'date_deposit' => date("Y-m-d H:i:s", strtotime($this->input->post('date'))),
                    'comment' => $this->input->post('comment')
                ];
                $result = $this->payment_model->addBankDepositDetails($postData);
                $serverResponse['result'] = $result ? 'success' : 'fail';
                $serverResponse['error'] = $result ? '' : 'Failed to insert details into database.';
            }
            else{
                $serverRespone['error'] = 'Transaction does not exist.';
            }
        }
        echo json_encode($serverResponse);
    }

    /**
     *  Used when adding Shipping Comments under Transactions Tab
     *
     *  @return JSON
     */
    public function addShippingComment()
    {
        $serverResponse['result'] = 'fail';
        $serverResponse['error'] = 'Failed to validate form.';

        $em = $this->serviceContainer['entity_manager'];
        $emailService = $this->serviceContainer['email_notification'];
        $orderProductIds[0] = $this->input->post('order_product');
        $productShippingCommentRepo = $em->getRepository("EasyShop\Entities\EsProductShippingComment");
        if ( (bool) stripos($orderProductIds[0], '-')) {
            $productIds = explode('-', $orderProductIds[0]);
            $orderProductIds = $productIds;
        }
        foreach ($orderProductIds as $orderProductId) {
            if( $this->form_validation->run('addShippingComment') ){
                $postData = [
                    'comment' => $this->input->post('comment'),
                    'order_product' => $orderProductId,
                    'member_id' => $this->session->userdata('member_id'),
                    'transact_num' => $this->input->post('transact_num'),
                    'courier' => $this->input->post('courier'),
                    'tracking_num' => $this->input->post('tracking_num'),
                    'expected_date' => $this->input->post('expected_date') ? date("Y-m-d H:i:s", strtotime($this->input->post('expected_date'))) : "0000-00-00 00:00:00",
                    'delivery_date' => date("Y-m-d H:i:s", strtotime($this->input->post('delivery_date')))
                ];

                $memberEntity = $em->find("EasyShop\Entities\EsMember", $postData['member_id']);
                $orderEntity = $em->find("EasyShop\Entities\EsOrder", $postData['transact_num']);
                $orderProductEntity  = $this->esOrderProductRepo
                                            ->findOneBy([
                                                "idOrderProduct" => $postData['order_product'],
                                                "seller" => $memberEntity,
                                                "order" => $orderEntity
                                            ]);
                $shippingCommentEntity = $em->getRepository("EasyShop\Entities\EsProductShippingComment")
                                            ->findOneBy(["orderProduct" => $orderProductEntity,
                                                "member" => $memberEntity
                                            ]);
                $shippingCommentEntitySize = count($shippingCommentEntity);

                if ( $shippingCommentEntitySize === 1 ) {
                    $exactShippingComment = $productShippingCommentRepo->getExactShippingComment($postData);
                }

                if( count($orderProductEntity) === 1 ) {
                    $esShippingComment = $productShippingCommentRepo->findOneBy(['orderProduct' => $orderProductEntity, 'member' => $memberEntity]);
                    if ($esShippingComment) {
                        $newEsShippingComment = $productShippingCommentRepo->updateShippingComment($esShippingComment, $orderProductEntity, $postData['comment'], $memberEntity, $postData['tracking_num'], $postData['courier'], $postData['expected_date'], $postData['delivery_date']);
                    }
                    else {
                        $newEsShippingComment = $productShippingCommentRepo->addShippingComment($orderProductEntity, $postData['comment'], $memberEntity, $postData['tracking_num'], $postData['courier'], $postData['expected_date'], $postData['delivery_date']);
                    }
                    $isShippingCommentModified = (bool) $newEsShippingComment;
                    $serverResponse['result'] = $isShippingCommentModified ? 'success' : 'fail';
                    $serverResponse['error'] = $isShippingCommentModified ? '' : 'Failed to insert in database.';

                    if( $isShippingCommentModified && ( $shippingCommentEntitySize === 0 || count($exactShippingComment) === 0 ) ){
                        $buyerEntity = $orderEntity->getBuyer();
                        $buyerEmail = $buyerEntity->getEmail();
                        $buyerEmailSubject = $this->lang->line('notification_shipping_comment');
                        $this->config->load('email', true);
                        $imageArray = $this->config->config['images'];

                        $parseData = $postData;
                        $socialMediaLinks = $this->serviceContainer['social_media_manager']
                                                 ->getSocialMediaLinks();
                        $parseData = array_merge($parseData, [
                            "seller" => $memberEntity->getUsername(),
                            "store_link" => base_url() . $memberEntity->getSlug(),
                            "msg_link" => base_url() . "messages/#" . $memberEntity->getUsername(),
                            "buyer" => $buyerEntity->getUsername(),
                            "invoice" => $orderEntity->getInvoiceNo(),
                            "product_name" => $orderProductEntity->getProduct()->getName(),
                            "expected_date" => $postData['expected_date'] === "0000-00-00 00:00:00" ?: date("Y-M-d", strtotime($postData['expected_date'])),
                            "delivery_date" => date("Y-M-d", strtotime($postData['delivery_date'])),
                            "facebook" => $socialMediaLinks["facebook"],
                            "twitter" => $socialMediaLinks["twitter"]
                        ]);
                        $buyerEmailMsg = $this->parser->parse("emails/email_shipping_comment", $parseData, true);

                        $emailService->setRecipient($buyerEmail)
                                     ->setSubject($buyerEmailSubject)
                                     ->setMessage($buyerEmailMsg, $imageArray)
                                     ->queueMail();
                    }

                }
                else{
                    $serverResponse['error'] = 'Server data mismatch. Possible hacking attempt';
                }
            }
        }
        echo json_encode($serverResponse);
    }

    /**
     *  Used for the Reject Item functionality under Transactions Tab
     *  Returns json encoded success or fail with error message
     *
     *  @return JSON
     */
    public function rejectItem()
    {
        $data = [
            'order_product' => $this->input->post('order_product'),
            'transact_num' => $this->input->post('transact_num'),
            'member_id' => $this->input->post('seller_id'),
            'method' => $this->input->post('method')
        ];

        $serverResponse = [
            'result' => 'fail',
            'error' => 'Transaction does not exist.'
        ];

        $checkOrderProductBasic = $this->esOrderProductRepo
                                       ->findOneBy([
                                           'idOrderProduct' => $data['order_product'],
                                           'order' => $data['transact_num'],
                                           'seller' => $data['member_id']
                                       ]);

        if ($checkOrderProductBasic) {
            $order = $this->em->getRepository('EasyShop\Entities\EsOrder')->find($data['transact_num']);
            $seller = $this->esMemberRepo->find($data['member_id']);
            $esOrderProduct = $this->esOrderProductRepo
                                   ->findOneBy([
                                       'idOrderProduct' => $data['order_product'],
                                       'order' => $order,
                                       'seller' => $seller
                                   ]);
            $isReject = $data['method'] === "reject";
            $rejectTransaction = $this->esOrderProductRepo->updateIsReject($isReject, $esOrderProduct);

            if ( (bool) $rejectTransaction) {
                $historyData['order_product_id'] = $this->esOrderProductRepo->find($data['order_product']);
                if ($data['method'] === 'reject') {
                    $historyData['comment'] = 'REJECTED';
                    $historyData['order_product_status'] = $this->em->getRepository('EasyShop\Entities\EsOrderProductStatus')->find(EsOrderProductStatus::STATUS_REJECT);
                }
                else if ($data['method'] === 'unreject') {
                    $historyData['comment'] = 'UNREJECTED';
                    $historyData['order_product_status'] = $this->em->getRepository('EasyShop\Entities\EsOrderProductStatus')->find(EsOrderProductStatus::ON_GOING);
                }
                $this->em->getRepository('EasyShop\Entities\EsOrderProductHistory')->createHistoryLog($historyData['order_product_id'], $historyData['order_product_status'], $historyData['comment']);
            }
            $serverResponse['result'] = $rejectTransaction ? 'success' : 'fail';
            $serverResponse['error'] = $rejectTransaction ? '' : 'Failed to update database.';
        }

        echo json_encode($serverResponse);
    }

    /**
     *  Used for subscribing/following vendors.
     *  Returns json encoded success or fail with error message
     *
     *  @return JSON
     */
    public function vendorSubscription()
    {
        $um = $this->serviceContainer['user_manager'];

        $memberId = $this->session->userdata('member_id');
        $sellername = $this->input->post('name');

        $subscriptionStatus = $um->getVendorSubscriptionStatus($memberId, $sellername);

        $boolResult = false;
        $serverResponse = array(
            'result' => 'fail'
            , 'error' => "Failed to check subscription status."
        );

        if($subscriptionStatus === "followed"){
            $boolResult = $um->unsubscribeToVendor($memberId, $sellername);
        }
        else if($subscriptionStatus === "unfollowed"){
            $boolResult = $um->subscribeToVendor($memberId, $sellername);
        }

        $serverResponse['result'] = $boolResult ? 'success':'fail';
        $serverResponse['error'] = $boolResult ? '' : 'Failed to update database.';

        echo json_encode($serverResponse);
    }
    
    /**
     *  Used to modify store description in vendor page
     *  Returns json encoded success or fail with error message
     *
     *  @return JSON
     */
    public function updatedStoreDescription()
    {
        $serverResponse = array(
            'result' => 'fail',
            'error' => 'Failed to submit form.'
        );
        
        if($this->input->post('store_desc')){
            $desc = $this->input->post('desc');
            $member_id = $this->session->userdata('member_id');
            $boolResult = $this->memberpage_model->updateStoreDesc($member_id, $desc);
            
            $serverResponse['result'] = $boolResult ? 'success' : 'fail';
            $serverResponse['error'] = $boolResult ? '' : 'Unable to update database.';
        }
        
        echo json_encode($serverResponse);
    }
    
    /**
     *  Used to send email / SMS when verifying email or mobile
     *  NOTE: ONLY EMAIL FUNCTIONALITY IS USED AT THE MOMENT
     *  Returns json_encoded success, fail, or data error with error message
     *
     *  @return JSON
     */
    public function verify()
    {
        $result = 'data-error';
        if($this->input->post('reverify') === 'true'){
            $memberId = $this->session->userdata('member_id');
            $member = $this->em->find('EasyShop\Entities\EsMember', $memberId);
            if($this->input->post('field') === 'email' && $this->input->post('data') == $member->getEmail()){
                $verificationSendingResponse = $this->accountManager
                                                     ->sendAccountVerificationLinks($member, false);
                if($verificationSendingResponse['isSuccessful']){
                    $this->serviceContainer['cart_manager']
                         ->getCartObject()
                         ->destroy();
                    $result = 'success';
                }
                else{
                    $result = $verificationSendingResponse['error'];
                }
            }
        }
        echo json_encode($result);
    }

    /**
     *  Used for verifying mobile verification code input by user
     *  NOTE : NOT USED AT THE MOMENT
     *
     *  @return integer
     */
    public function verify_mobilecode()
    {
        if($this->input->post('mobileverify') === 'true'){
            $user_mobilecode = html_escape($this->input->post('data'));

            if($user_mobilecode === $this->session->userdata('mobilecode')){
                $data = array(
                    'is_contactno_verify' => 1,
                    'member_id' => $this->session->userdata('member_id')
                );
                $this->session->unset_userdata('mobilecode');
                $this->register_model->update_verification_status($data);
                echo 1;
            }
            else{
                echo 0;
            }
        }
    }
    
    /**
     *  Fetch bank info
     *
     *  @return JSON
     */
    public function bank_info()
    {
        $q = $this->input->get('q');
        if(!empty($q)){
            $bank_names = $this->memberpage_model->get_bank($q, 'name');
            echo json_encode($bank_names);
        }
    }
    
    /**
     *  Fetch billing info/details of user
     *
     *  @return JSON
     */
    public function billing_info()
    {
        # if(($this->input->post('bi_acct_no')) && ($this->form_validation->run('billing_info'))){
        if($this->input->post('bi_acct_no')){
            $member_id = $this->session->userdata('member_id');
            $bi_payment_type = $this->input->post('bi_payment_type');
            $bi_user_account = ""; // pang paypal ito or any online account
            $bi_bank = $this->input->post('bi_bank');
            $bi_acct_name = $this->input->post('bi_acct_name');
            $bi_acct_no = $this->input->post('bi_acct_no');
            $data = array(
                'member_id' => $member_id,
                'payment_type' => $bi_payment_type,
                'user_account' => $bi_user_account,
                'bank_id' => $bi_bank,
                'bank_account_name' => $bi_acct_name,
                'bank_account_number' => $bi_acct_no
            );
            
            if($this->memberpage_model->isBankAccountUnique($data)){
                $result = $this->memberpage_model->billing_info($data);
                echo '{"e":"1","d":"success","id":'.$result.'}';
            }
            else{
                echo '{"e":"0","d":"duplicate"}';
            }
        }
        else{
            echo '{"e":"0","d":"fail"}';
        }
    }
    
    /**
     *  Used to update user's billing info
     *
     *  @return JSON
     */
    public function billing_info_u()
    {
        if($this->input->post('bi_id')){
            $member_id = $this->session->userdata('member_id');
            $bi_id = $this->input->post('bi_id');
            $bi_bank = $this->input->post('bi_bank');
            $bi_payment_type = $this->input->post('bi_payment_type');
            $bi_acct_name = $this->input->post('bi_acct_name');
            $bi_acct_no = $this->input->post('bi_acct_no');
            $bi_def = $this->input->post('bi_def');
            $bi_user_account = "";
            $data = array(
                'member_id' => $member_id,
                'payment_type' => $bi_payment_type,
                'ibi' => $bi_id,
                'bank_id' => $bi_bank,
                'bank_account_name' => $bi_acct_name,
                'bank_account_number' => $bi_acct_no,
                'is_default' => $bi_def,
                'user_account' => $bi_user_account,
            );
            if($this->memberpage_model->isBankAccountUnique($data)){
                $this->memberpage_model->billing_info_update($data);
                $return = '{"e":"1","d":"success"}';
            }
            else{
                $return = '{"e":"0","d":"duplicate"}';
            }
        }
        else{
            $return = '{"e":"0","d":"fail"}';
        }
        echo $return;
    }
    
    /**
     *  Used to delete user's billing info
     */
    public function billing_info_d()
    {
        if($this->input->post('bi_id')){
            $member_id = $this->session->userdata('member_id');
            $bi_id = $this->input->post('bi_id');
            $member_id = $this->session->userdata('member_id');
            $data = array(
                'member_id' => $member_id,
                'ibi' => $bi_id
            );
            $this->memberpage_model->billing_info_delete($data);
        }
    }
    
    /**
     *  Used for setting default billing info
     */
    public function billing_info_f()
    {
        if($this->input->post('bi_id')){
            $member_id = $this->session->userdata('member_id');
            $bi_id = $this->input->post('bi_id');
            $member_id = $this->session->userdata('member_id');
            $data = array(
                'member_id' => $member_id,
                'ibi' => $bi_id
            );
            $this->memberpage_model->billing_info_default($data);
        }
    }

    public function removeUserImage()
    {
        $return['error'] = TRUE;
        $return['msg'] = "Something went wrong please try again later.";
        $return['img'] = "";
        $memberId = $this->session->userdata('member_id');

        $userMgr = $this->serviceContainer['user_manager'];

        $remove = $userMgr->removeUserImage($memberId);
        if($remove){
            $return['error'] = FALSE;
            $return['msg'] = "";
            $return['img'] = $remove;
        }

        echo json_encode($return);
    }


    /**
     * Request for transaction details - ajax
     *
     */
    public function getTransactionsForPagination()
    {
        $page = (int) trim($this->input->get('page'));
        $requestType = trim($this->input->get('request'));
        $memberId = $this->session->userdata('member_id');
        $paginationData = [
            'isHyperLink' => false,
            'currentPage' => $page
        ];
        $transactionNumber = '';
        $paymentMethod = '';
        if (trim($this->input->get('searchFor')) === 'transactionNumber') {
            $transactionNumber =  trim( (string) $this->input->get('value'));
        }
        if (trim($this->input->get('searchFor')) === 'paymentMethod') {
            $paymentMethod =  trim( (int) $this->input->get('value'));
        }
        switch ($requestType) {
            case 'ongoing-bought':
                $ongoingBoughtTransactionsCount = $this->transactionManager
                                                       ->getBoughtTransactionCount(
                                                           $memberId,
                                                           true,
                                                           $paymentMethod,
                                                           $transactionNumber
                                                       );
                $paginationData['lastPage'] = ceil($ongoingBoughtTransactionsCount / $this->transactionRowCount);
                $ongoingBoughtTransactionData = [
                    'transaction' => $this->transactionManager
                                          ->getBoughtTransactionDetails(
                                              $memberId,
                                              true,
                                              $this->transactionRowCount * ($page - 1),
                                              $this->transactionRowCount,
                                              $transactionNumber,
                                              $paymentMethod
                                          ),
                    'count' => $ongoingBoughtTransactionsCount,
                    'pagination' => $this->load->view('pagination/default', $paginationData, true),
                ];
                $transactionView = $this->load->view('partials/dashboard-transaction-ongoing-bought', $ongoingBoughtTransactionData, true);
                break;
            case 'ongoing-sold':
                $ongoingSoldTransactionsCount = $this->transactionManager
                                                     ->getSoldTransactionCount(
                                                         $memberId,
                                                         true,
                                                         $paymentMethod,
                                                         $transactionNumber
                                                     );
                $paginationData['lastPage'] = ceil($ongoingSoldTransactionsCount["transactionsCount"] / $this->transactionRowCount);
                $ongoingSoldTransactionData = [
                    'transaction' => $this->transactionManager
                                          ->getSoldTransactionDetails(
                                              $memberId,
                                              true,
                                              $this->transactionRowCount * ($page - 1),
                                              $this->transactionRowCount,
                                              $transactionNumber,
                                              $paymentMethod
                                          ),
                    'count' => $ongoingSoldTransactionsCount["transactionsCount"],
                    'pagination' => $this->load->view('pagination/default', $paginationData, true),
                ];
                $transactionView = $this->load->view('partials/dashboard-transaction-ongoing-sold', $ongoingSoldTransactionData, true);
                break;
            case 'complete-bought':
                $completeBoughtTransactionsCount = $this->transactionManager
                                                       ->getBoughtTransactionCount(
                                                           $memberId,
                                                           false,
                                                           $paymentMethod,
                                                           $transactionNumber
                                                       );
                $paginationData['lastPage'] = ceil($completeBoughtTransactionsCount / $this->transactionRowCount);
                $completeBoughtTransactionsData = [
                    'transaction' => $this->transactionManager
                                          ->getBoughtTransactionDetails(
                                              $memberId,
                                              false,
                                              $this->transactionRowCount * ($page - 1),
                                              $this->transactionRowCount,
                                              $transactionNumber,
                                              $paymentMethod
                                          ),
                    'count' => $completeBoughtTransactionsCount,
                    'pagination' => $this->load->view('pagination/default', $paginationData, true),
                ];
                $transactionView = $this->load->view('partials/dashboard-transaction-complete-bought', $completeBoughtTransactionsData, true);
                break;
            case 'complete-sold':
                $completeSoldTransactionsCount = $this->transactionManager
                                                      ->getSoldTransactionCount(
                                                          $memberId,
                                                          false,
                                                          $paymentMethod,
                                                          $transactionNumber
                                                      );
                $paginationData['lastPage'] = ceil($completeSoldTransactionsCount["transactionsCount"] / $this->transactionRowCount);

                $completeSoldTransactionsData = [
                    'transaction' => $this->transactionManager
                                          ->getSoldTransactionDetails(
                                              $memberId,
                                              false,
                                              $this->transactionRowCount * ($page - 1),
                                              $this->transactionRowCount,
                                              $transactionNumber,
                                              $paymentMethod
                                          ),
                    'count' => $completeSoldTransactionsCount["transactionsCount"],
                    'pagination' => $this->load->view('pagination/default', $paginationData, true),
                ];
                $transactionView = $this->load->view('partials/dashboard-transaction-complete-sold', $completeSoldTransactionsData, true);
                break;
            default:
                break;
        }

        $responseData = [
            'html' => $transactionView,
        ];

        echo json_encode($responseData);
    }



    /**
     * Request first sales page
     * @return json
     */
    public function requestSalesPage()
    {
        $esOrderProductRepo = $this->em->getRepository('EasyShop\Entities\EsOrderProduct');

        $memberId = $this->session->userdata('member_id');
        $salesPerPage = $this->salesPerPage;

        $currentSales = $esOrderProductRepo->getOrderProductTransaction($memberId,
                                                                EsOrderProductStatus::FORWARD_SELLER,
                                                                $salesPerPage);
        $currentTotalSales = $esOrderProductRepo->getSumOrderProductTransaction($memberId,
                                                                                EsOrderProductStatus::FORWARD_SELLER);
        $currentSalesCount = $esOrderProductRepo->getCountOrderProductTransaction($memberId,
                                                                                  EsOrderProductStatus::FORWARD_SELLER);

        $paginationData['isHyperLink'] = false;
        $paginationData['lastPage'] = ceil($currentSalesCount / $salesPerPage);
        $currentSalesViewData  = [
            'sales' => $currentSales,
            'type' => EsOrderProductStatus::FORWARD_SELLER,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];
        $currentSalesView = $this->load->view('partials/dashboard-sales', $currentSalesViewData, true);

        $historySales = $esOrderProductRepo->getOrderProductTransaction($memberId,
                                                                        EsOrderProductStatus::PAID_FORWARDED,
                                                                        $salesPerPage);
        $historyTotalSales = $esOrderProductRepo->getSumOrderProductTransaction($memberId,
                                                                                EsOrderProductStatus::PAID_FORWARDED);
        $historySalesCount = $esOrderProductRepo->getCountOrderProductTransaction($memberId,
                                                                                  EsOrderProductStatus::PAID_FORWARDED);
        $paginationData['lastPage'] = ceil($historySalesCount / $salesPerPage);
        $historySalesViewData = [
            'sales' => $historySales,
            'type' => EsOrderProductStatus::PAID_FORWARDED,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];
        $historySalesView = $this->load->view('partials/dashboard-sales', $historySalesViewData, true);

        $salesViewData = [
            'currentSales' => $currentSalesView,
            'currentTotalSales' => $currentTotalSales,
            'historySales' => $historySalesView,
            'historyTotalSales' => $historyTotalSales,
        ];
        $salesView = $this->load->view('pages/user/dashboard/dashboard-sales', $salesViewData, true);

        $returnArray = [
            'salesView' => $salesView,
            'currentSales' => $currentSalesView,
            'historySales' => $historySalesView,
        ];

        echo json_encode($returnArray);
    }

    /**
     * update product is_delete to 1 
     * @return json
     */
    public function softDeleteProduct()
    {
        $memberId = $this->session->userdata('member_id');
        $productId = $this->input->get('product_id'); 
        $productManager = $this->serviceContainer['product_manager'];
        $deleteResponse = $productManager->updateIsDeleteStatus($productId, $memberId, EsProduct::DELETE);

        $responseArray = [
            'isSuccess' => $deleteResponse,
            'message' => $deleteResponse ? "" : "You can't delete this item.",
        ];

        echo json_encode($responseArray);
    }

    /**
     * update product is_delete to 2
     * @return json
     */
    public function hardDeleteProduct()
    {
        $memberId = $this->session->userdata('member_id');
        $productId = $this->input->get('product_id'); 
        $productManager = $this->serviceContainer['product_manager'];
        $deleteResponse = $productManager->updateIsDeleteStatus($productId, $memberId, EsProduct::FULL_DELETE);

        $responseArray = [
            'isSuccess' => $deleteResponse,
            'message' => $deleteResponse ? "" : "You can't delete this item.",
        ];

        echo json_encode($responseArray);
    }

    /**
     * update product is_delete to 1
     * @return json
     */
    public function restoreProduct()
    {
        $memberId = $this->session->userdata('member_id');
        $productId = $this->input->get('product_id'); 
        $productManager = $this->serviceContainer['product_manager'];
        $restoreResponse = $productManager->updateIsDeleteStatus($productId, $memberId, EsProduct::ACTIVE);

        $responseArray = [
            'isSuccess' => $restoreResponse,
            'message' => $restoreResponse ? "" : "You can't restore this item.",
        ];

        echo json_encode($responseArray);
    }

    /**
     * send notification to user and deactivate account
     * @param id
     * @param password
     * @return mixed
     */
    public function sendDeactivateNotification()
    {
        $member = $this->accountManager
                       ->authenticateMember($this->input->post('username'), $this->input->post('password'), false, true);  
        if ($member['member']) {
            $authenticatedMember = true;
            if($this->session->userdata('member_id') && ($member["member"]->getIdMember() !== $this->session->userdata('member_id'))) {
                $result = 'Invalid Username/Password';
                $authenticatedMember = false;
            }             
            if($authenticatedMember) {
                $this->load->library('encrypt');
                $this->load->library('parser');
                $hash = serialize([
                    'memberId' => $member['member']->getIdMember(),
                ]);                
                $result = $this->encrypt->encode($hash);
                $parseData = array(
                    'username' => $member['member']->getUsername(),
                    'hash' => $result,
                    'site_url' => site_url('memberpage/showActivateAccount')
                );        
                $imageArray = $this->config->config['images'];
                $imageArray[] = "/assets/images/appbar.home.png";
                $imageArray[] = "/assets/images/appbar.message.png";
                $this->emailNotification = $this->serviceContainer['email_notification'];
                $message = $this->parser->parse('emails/email_deactivate_account', $parseData, true);
                $this->emailNotification->setRecipient($member['member']->getEmail());
                $this->emailNotification->setSubject($this->lang->line('deactivate_subject'));
                $this->emailNotification->setMessage($message,$imageArray);
                $this->emailNotification->sendMail();
                $this->em->getRepository('EasyShop\Entities\EsMember')
                         ->accountActivation($member['member'], false);
            }
        }     
        else {
            $result = 'Invalid Username/Password';
        }
        
        echo json_encode($result);
    }

    /**
     * Flags member as activated
     * @return json
     */
    public function doReactivateAccount()
    {

        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));

        $authenticationResult = $this->accountManager
                                     ->authenticateMember($this->input->get('username'), 
                                                          $this->input->get('password'), 
                                                          false, 
                                                          true);  
        $isActivationRequestValid = $authenticationResult['member']
                                    && $authenticationResult['member']->getIdMember() === (int)$getData["memberId"] 
                                    && (bool)$authenticationResult['member']->getIsActive() === false ;
        $response = false;
        if($this->input->get("activateAccountButton") && $isActivationRequestValid) {
            $this->em
                 ->getRepository('EasyShop\Entities\EsMember')
                 ->accountActivation($authenticationResult["member"], true);          
            $response = true;
        }

        $result = [
            "result" => ($response) ? "success" : "error",
        ];
        echo json_encode($result);        
    }

    /**
     * Show activate account page
     */
    public function showActivateAccount()
    {

        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));

        if ((int)($getData["memberId"]) === 0 || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }
        else {
             $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy([
                                    'idMember' => $getData["memberId"],
                                    'isActive' => 0
                                ]);

            if (!$member) {
                redirect('/login', 'refresh');
            }
            else {
                $view = $this->input->get('view') ? $this->input->get('view') : NULL;
                $bodyData = [
                    'username' => $member->getUsername(),
                    'idMember' => $getData["memberId"],            
                    'hash' => $this->input->get('h')            
                ];
                $headerData = [
                    "memberId" => $this->session->userdata('member_id'),
                    'title' =>  "Reactivate you account | Easyshop.ph",
                    'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
                ];
    
                $this->load->spark('decorator');    
                $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
                $this->load->view('pages/user/MemberPageAccountActivate', $bodyData);
                $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));           
            }            
        }
    }

    /**
     * get next product set based on page number
     * @return json
     */
    public function productMemberPagePaginate()
    {
        $productManager = $this->serviceContainer['product_manager'];
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');

        $memberId = $this->session->userdata('member_id');
        $page = $this->input->get('page') ? trim($this->input->get('page')) : 1;
        $requestType = trim($this->input->get('request'));
        $sortType = trim($this->input->get('sort'));
        $searchString = trim($this->input->get('search_string'));
 
        $deleteConditions = [EsProduct::ACTIVE];
        $draftConditions = [EsProduct::ACTIVE];

        if(strtolower($requestType) === "deleted"){ 
            $deleteConditions = [EsProduct::DELETE];
            $draftConditions = [EsProduct::ACTIVE,EsProduct::DRAFT];
        }
        elseif (strtolower($requestType) === "drafted"){ 
            $deleteConditions = [EsProduct::ACTIVE];
            $draftConditions = [EsProduct::DRAFT];
        }

        $userProductCount = $esProductRepo->getUserProductCount($memberId,
                                                                $deleteConditions, 
                                                                $draftConditions, 
                                                                $searchString);
        $userProducts = $productManager->getProductsByUser($memberId,
                                                           $deleteConditions,
                                                           $draftConditions,
                                                           $productManager::PRODUCT_COUNT_DASHBOARD*($page-1),
                                                           $searchString,
                                                           $sortType); 

        $paginationData = [
            'lastPage' => ceil($userProductCount/$productManager::PRODUCT_COUNT_DASHBOARD)
            ,'isHyperLink' => false
            , 'currentPage' => $page
        ];

        $viewData = [
            'products' => $userProducts,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];

        $responseArray = [
            'html' => $this->load->view('partials/dashboard-products', $viewData, true),
        ];

        echo json_encode($responseArray);
    }

    /**
     * get next set of feedbacks based on page number
     * @return json
     */
    public function feedbackMemberPagePaginate()
    {
        $userManager = $this->serviceContainer['user_manager'];
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        
        $page = (int) ($this->input->get('page')) ? trim($this->input->get('page')) : 1;
        $requestType = (int) trim($this->input->get('request'));
        $memberId = $this->session->userdata('member_id');
        $feedbackLimit = $this->feedbackLimit;
        $allFeedbacks = $userManager->getFormattedFeedbacks($memberId);
        $paginationData = [
            'isHyperLink' => false,
            'currentPage' => $page
        ];

        $feedbacks = $esMemberFeedbackRepo->getUserFeedbackByType($memberId,
                                                                  $requestType,
                                                                  $feedbackLimit,
                                                                  $page - 1);
        // add user image on each feedback
       foreach ($feedbacks as $key => $feedback) {
            $feedbacks[$key]['revieweeAvatarImage'] = $userManager->getUserImage($feedback['revieweeId'], "small");
            $feedbacks[$key]['reviewerAvatarImage'] = $userManager->getUserImage($feedback['reviewerId'], "small");
        }

        switch($requestType){
            case EsMemberFeedback::TYPE_AS_BUYER: 
                $paginationData['lastPage'] =  ceil(count($allFeedbacks['otherspost_buyer']) / $feedbackLimit);
                break;
            case EsMemberFeedback::TYPE_AS_SELLER: 
                $paginationData['lastPage'] =  ceil(count($allFeedbacks['otherspost_seller']) / $feedbackLimit);
                break;
            case EsMemberFeedback::TYPE_FOR_OTHERS_AS_SELLER: 
                $paginationData['lastPage'] =  ceil(count($allFeedbacks['youpost_seller']) / $feedbackLimit);
                break;
            case EsMemberFeedback::TYPE_FOR_OTHERS_AS_BUYER: 
                $paginationData['lastPage'] =  ceil(count($allFeedbacks['youpost_buyer']) / $feedbackLimit);
                break;
            default:
                $paginationData['lastPage'] =  ceil($allFeedbacks['totalFeedbackCount'] / $feedbackLimit);
                break;
        }

        $feedbacksData = [
            'feedbacks' => $feedbacks,
            'memberId' => $memberId,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ]; 

        $responseData = [
            'html' => $this->load->view('partials/dashboard-feedback', $feedbacksData, true),
        ];

        echo json_encode($responseData);
    }

    /**
     * Get next list of sales based on request type
     * @return json
     */
    public function salesMemberPagePaginate()
    {
        $page = (int) ($this->input->get('page')) ? trim($this->input->get('page')) : 1;
        $requestType = (int) trim($this->input->get('request'));
        $dateFrom = $this->input->get('date_from') ? date('Y-m-d 00:00:00', strtotime($this->input->get('date_from'))) : null;
        $dateTo = $this->input->get('date_to') ? date('Y-m-d 23:59:59', strtotime($this->input->get('date_to'))) : null;
        $memberId = $this->session->userdata('member_id');
        $salesPerPage = $this->salesPerPage;

        $esOrderProductRepo = $this->em->getRepository('EasyShop\Entities\EsOrderProduct');

        $sales = $esOrderProductRepo->getOrderProductTransaction($memberId,
                                             $requestType,
                                             $salesPerPage,
                                             $page - 1,
                                             $dateFrom,
                                             $dateTo);
        $totalSales = $esOrderProductRepo->getSumOrderProductTransaction($memberId,
                                                                         $requestType,
                                                                         $dateFrom,
                                                                         $dateTo);
        $salesCount = $esOrderProductRepo->getCountOrderProductTransaction($memberId,
                                                                           $requestType,
                                                                           $dateFrom,
                                                                           $dateTo);

        $paginationData = [
            'isHyperLink' => false,
            'currentPage' => $page,
            'lastPage' =>  ceil($salesCount / $salesPerPage),
        ];

        $salesViewData  = [
            'sales' => $sales,
            'type' => $requestType,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];
        $salesView = $this->load->view('partials/dashboard-sales', $salesViewData, true);

        $responseData = [
            'html' => $salesView,
            'netAmount' => number_format($totalSales,2,'.',','),
        ];

        echo json_encode($responseData);
    }

    
    /**
     * Update the store name 
     *
     * @return json
     */
    public function updateStoreName()
    {   
        $memberId = $this->session->userdata('member_id');
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];
        $entityManager = $this->serviceContainer['entity_manager'];
        $jsonResponse = ['isSuccessful' => false,
                         'errors' => []];        
                         
        if($this->input->post('storename')){
            $rules = $formValidation->getRules('store_setup');
            $formBuild = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                                     ->setMethod('POST');
            $formBuild->add('storename', 'text', array('constraints' => $rules['shop_name']));
            $formData['storename'] = $this->input->post('storename');$form = $formBuild->getForm();
            $form->submit($formData);
            
            if($form->isValid()){
                $member = $entityManager->getRepository('EasyShop\Entities\EsMember')
                                        ->find($memberId);
                $isUpdated = false;
                if($member){
                    $isUpdated = $this->serviceContainer['user_manager']
                                      ->updateStorename($member, $formData['storename']);
                    if($isUpdated){
                        $jsonResponse['updatedValue'] = $formData['storename'];
                    }
                    else{
                        $jsonResponse['errors'] = 'This store name is not available';
                    }
                }
                $jsonResponse['isSuccessful'] = $isUpdated;
            }
            else{
                $jsonResponse['errors'] = reset($formErrorHelper->getFormErrors($form))[0];
            }
        }
        
        echo json_encode($jsonResponse); 
    }
                 
    /**
     * Update the store slug 
     *
     * @return json
     */
    public function updateStoreSlug()
    {   
        $memberId = $this->session->userdata('member_id');
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];
        $entityManager = $this->serviceContainer['entity_manager'];
        $jsonResponse = ['isSuccessful' => false,
                         'errors' => []];        
                         
        if($this->input->post('storeslug')){
            $rules = $formValidation->getRules('store_setup');
            $formBuild = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                                     ->setMethod('POST');
            $formBuild->add('storeslug', 'text', array('constraints' => $rules['shop_slug']));
            $formData['storeslug'] = $this->input->post('storeslug');
            $form = $formBuild->getForm();
            $form->submit($formData);
            
            if($form->isValid()){
                $member = $entityManager->getRepository('EasyShop\Entities\EsMember')
                                          ->find($memberId);
                $isUpdated = false;
                if($member){
                    $routes = $this->router->routes;
                    $isUpdated = $this->serviceContainer['user_manager']
                                      ->updateSlug($member, $formData['storeslug'], $routes);
                    if($isUpdated){
                        $jsonResponse['updatedValue'] = $formData['storeslug'];
                    }
                    else{
                        $jsonResponse['errors'] = 'This store link is not available';
                    }
                }
                $jsonResponse['isSuccessful'] = $isUpdated;
            }
            else{
                $jsonResponse['errors'] = reset($formErrorHelper->getFormErrors($form))[0];
            }
        }
        echo json_encode($jsonResponse); 
    }
   
    /**
     * Update the store color scheme 
     *
     * @return json
     */
    public function updateStoreColorScheme()
    {
        $entityManager = $this->serviceContainer['entity_manager'];
        $response = ['isSuccessful' => 'false'];
        if($this->input->post('colorId')){
            $memberId = $this->session->userdata('member_id');
            $member = $entityManager->getRepository('EasyShop\Entities\EsMember')
                                    ->findOneBy(['idMember' => $memberId]);     
            $color = $entityManager->getRepository('EasyShop\Entities\EsStoreColor')
                                   ->find($this->input->post('colorId'));
            if($color !== null && $member !== null){
                $member->setStoreColor($color);
                $isSuccessful = true;
                try{
                    $entityManager->flush();
                }
                catch(\Doctrine\ORM\Query\QueryException $e){
                    $isSuccessful = false;
                    $response['errors'] = 'Sorry, something went wrong. Try again in a while.';
                }
                $response['isSuccessful'] = $isSuccessful ? 'true' : 'false';
            }
        }
        echo json_encode($response);
    }
    
    /**
     * Gets Delivery Address
     *
     * @return JSON
     */
    public function getDeliveryAddress()
    {
        $esAddressRepo = $this->em->getRepository('EasyShop\Entities\EsAddress');
        $esLocationLookupRepo = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');        
        $memberId = $this->session->userdata('member_id');
        $response = [];

        if($memberId){
            $address = $esAddressRepo->getConsigneeAddress($memberId, EsAddress::TYPE_DELIVERY, true);       
            $stateregionID =  ($address["address"] !== null && (int) $address["stateRegion"] !== 0 ) ? $address["stateRegion"] : 0;
            $locationLookup =  $esLocationLookupRepo->getLocationLookup(true);

            $consigneeCityLookup = ($stateregionID !== 0) ? $locationLookup["cityLookup"][$stateregionID] : null;
            $response = [
                "address" => $address["address"],
                "cities" =>  $locationLookup["json_city"],
                "consigneeCityLookup" =>  $consigneeCityLookup,
                "cityLookup" =>  $locationLookup["cityLookup"],
                "stateRegionLists" => $locationLookup["stateRegionLookup"],
                "countryId" =>  EsLocationLookup::PHILIPPINES_LOCATION_ID,
                "consigneeStateRegionId" => $stateregionID,
                "consigneeCityId" =>  ($address["address"] !== null && (int) $address["city"] !== 0) ? $address["city"] : 0
            ];

        }
        echo json_encode($response);
    }

    /**
     * Gets the store settings
     *
     * @return JSON
     */
    public function getStoreSettings()
    {
        $memberId = $this->session->userdata('member_id');
        $response = [];
        if($memberId){
            $response['colors'] = $this->serviceContainer['entity_manager']
                                       ->getRepository('EasyShop\Entities\EsStoreColor')
                                       ->getAllColors(true);
            $response['storeCategories'] = array_values($this->serviceContainer['category_manager']
                                                             ->getAllUserProductParentCategory($memberId));
        }
        echo json_encode($response);
    }
    

    /**
     * Returns all the payment accounts of the logged-in user
     *
     * @return JSON
     */
    public function getPaymentAccounts()
    {
        $memberId = $this->session->userdata('member_id');
        $response = [];
        if($memberId){
            $response['paymentAccount'] = $this->serviceContainer['entity_manager']
                                               ->getRepository('EasyShop\Entities\EsBillingInfo')
                                               ->getMemberPaymentAccountsAsArray($memberId);   
            $response['bankList'] = $this->serviceContainer['entity_manager']
                                         ->getRepository('EasyShop\Entities\EsBankInfo')
                                         ->getAllBanks(true); 
        }
        echo json_encode($response);
    }
    
    /**
     * Creates a new payment account
     *
     * @return JSON
     */
    public function createPaymentAccount()
    {
        $memberId = $this->session->userdata('member_id');
        
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];

        $jsonResponse = ['isSuccessful' => false,
            'errors' => [],
            'newId' => 0,
            'isDefault' => false,
        ];               
        if($this->input->post()){
            $rules = $formValidation->getRules('payment_account');
            $formBuild = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                                     ->setMethod('POST');
            $rules['account-number'][] = new EasyShop\FormValidation\Constraints\IsAccountNumberUnique(['memberId' => $memberId]); 
            $formBuild->add('account-bank-id', 'text', array('constraints' => $rules['account-bank-id']));
            $formBuild->add('account-name', 'text', array('constraints' => $rules['account-name']));
            $formBuild->add('account-number', 'text', array('constraints' => $rules['account-number']));
            $formData['account-bank-id'] = (int)$this->input->post('account-bank-id');
            $formData['account-name'] = $this->input->post('account-name');
            $formData['account-number'] = $this->input->post('account-number');
            $form = $formBuild->getForm();
            $form->submit($formData);        
                  
            if($form->isValid()){
                $newAccount = $this->serviceContainer['entity_manager']
                                   ->getRepository('EasyShop\Entities\EsBillingInfo')
                                   ->createNewPaymentAccount($memberId, 
                                        $formData['account-name'], 
                                        $formData['account-number'], 
                                        $formData['account-bank-id']
                                    );
                if($newAccount){
                    $jsonResponse['isSuccessful'] = true;
                    $jsonResponse['isDefault'] = $newAccount->getIsDefault();
                    $jsonResponse['newId'] = $newAccount->getIdBillingInfo();
                }
            }else{
                $jsonResponse['errors'] = reset($formErrorHelper->getFormErrors($form));
            }
        }
        echo json_encode($jsonResponse); 
    }
    
    /**
     * Update default account
     *
     * @return JSON
     */
    public function changeDefaultPaymentAccount()
    {
        $memberId = $this->session->userdata('member_id');
        $isSuccessful = false;
        if( $this->input->post('payment-account-id') && $memberId ){
            $billingInfoId = $this->input->post('payment-account-id');
            $this->serviceContainer['entity_manager']
                 ->getRepository('EasyShop\Entities\EsBillingInfo')
                 ->updateDefaultAccount($memberId, $billingInfoId);
            $isSuccessful = true;
        }
        echo json_encode($isSuccessful);
    }
    
    
    /**
     * Destroy action for payment account
     *
     * @return JSON
     */
    public function deletePaymentAccount()
    {
        $memberId = $this->session->userdata('member_id');
        $jsonResponse = ['isSuccessful' => false,
                         'defaultId' => 0,
                        ];
        if( $this->input->post('payment-account-id') && $memberId ){
            $billingInfoRepository = $this->serviceContainer['entity_manager']
                                          ->getRepository('EasyShop\Entities\EsBillingInfo');
            $billingInfoId = $this->input->post('payment-account-id');
            $jsonResponse['isSuccessful'] = $billingInfoRepository->deletePaymentAccount($memberId, $billingInfoId);
            $defaultAccount = $billingInfoRepository->getDefaultAccount($memberId);
            if($defaultAccount){
                $jsonResponse['defaultId'] = $defaultAccount->getIdBillingInfo();
            }
        }
        echo json_encode($jsonResponse);
    }
    
    /**
     * Update the payment account
     *
     * @return JSON
     */
    public function updatePaymentAccount()
    {
        $memberId = $this->session->userdata('member_id');  
        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];
        $entityManager = $this->serviceContainer['entity_manager'];
        
        $jsonResponse = [
            'isSuccessful' => false,
            'errors' => [],
        ];          
        if($this->input->post() && $memberId){
            $rules = $formValidation->getRules('payment_account');
            $formBuild = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                                     ->setMethod('POST');                          
            $accountId = (int)$this->input->post('payment-account-id');
            $rules['account-number'][] = new EasyShop\FormValidation\Constraints\IsAccountNumberUnique([
                                                'memberId' => $memberId,
                                                'accountId' => $accountId,
                                            ]);                       
            $formBuild->add('account-id', 'text', array('constraints' => $rules['account-id']));                         
            $formBuild->add('account-bank-id', 'text', array('constraints' => $rules['account-bank-id']));
            $formBuild->add('account-name', 'text', array('constraints' => $rules['account-name']));
            $formBuild->add('account-number', 'text', array('constraints' => $rules['account-number'] ));
            $formData['account-bank-id'] = (int)$this->input->post('bank-id');
            $formData['account-name'] = $this->input->post('account-name');
            $formData['account-number'] = $this->input->post('account-number');
            $formData['account-id'] = $accountId;
            $form = $formBuild->getForm();
            $form->submit($formData);        
            if($form->isValid()){
                $newAccount = $entityManager->getRepository('EasyShop\Entities\EsBillingInfo')
                                            ->findOneBy(['idBillingInfo' => $formData['account-id'],
                                                         'member' => $memberId,
                                                         'isDelete' => false,
                                            ]);
                if($newAccount){
                    $newAccount->setDatemodified(date_create(date("Y-m-d H:i:s")));
                    $newAccount->setBankAccountName($formData['account-name']);
                    $newAccount->setBankAccountNumber($formData['account-number']);
                    $newAccount->setBankId($formData['account-bank-id']);
                    $entityManager->flush();
                    $jsonResponse['isSuccessful'] = true;
                }
            }else{
                $jsonResponse['errors'] = reset($formErrorHelper->getFormErrors($form));
            }
        }
        echo json_encode($jsonResponse);
    }
    
    /**
     * Update the store setting category order
     *
     * @return JSON
     */
    public function updateStoreCategories()
    {
        $memberId = $this->session->userdata('member_id'); 
        $entityManager =  $this->serviceContainer['entity_manager'];
        $isSucessful = false;
        $jsonResponse = [
            'isSuccessful' =>  false,
            'categoryData' => [],
        ];
        if($this->input->post() && $memberId){
            $member = $entityManager->getRepository('EasyShop\Entities\EsMember')
                                    ->findOneBy(['idMember' => $memberId]);
            $categoryData = json_decode($this->input->post('categoryData'));
            $indexedCategoryData = [];
            $hasCategoryError = false;
            foreach($categoryData as $category){
                $indexedCategoryData[$category->categoryid] = $category;
                if(trim($category->name) === ""){
                    $hasCategoryError = true;
                    break;
                }
            }

            if(!$hasCategoryError){
                $savedCategories = $entityManager->getRepository('EasyShop\Entities\EsMemberCat')
                                                 ->getCustomCategoriesObject($memberId, array_keys($indexedCategoryData));
                $categoryDataResult = [];
                foreach($savedCategories as $savedCategory){
                    $memberCategoryId = $savedCategory->getIdMemcat(); 
                    if( isset($indexedCategoryData[$memberCategoryId]) ){
                        $currentCategory = $indexedCategoryData[$memberCategoryId];
                        $savedCategory->setCatName($currentCategory->name);
                        $savedCategory->setSortOrder($currentCategory->order);
                        $categoryDataResult[] = $this->createCategoryStdObject($currentCategory->name,
                                                                            $currentCategory->order,
                                                                            $memberCategoryId);
                        unset($indexedCategoryData[$memberCategoryId]);
                    }
                }
                $newMemberCategories = [];
                foreach($indexedCategoryData as $index=>$newCategory){
                    $newMemberCategories[$index] = new EasyShop\Entities\EsMemberCat();
                    $newMemberCategories[$index]->setMember($member);
                    $newMemberCategories[$index]->setCatName($newCategory->name);
                    $newMemberCategories[$index]->setSortOrder($newCategory->order);
                    $newMemberCategories[$index]->setCreatedDate(date_create(date("Y-m-d H:i:s")));
                    $entityManager->persist($newMemberCategories[$index]);
                }
                $entityManager->flush();
                
                foreach($newMemberCategories as $newMemberCategory){
                    $categoryDataResult[] = $this->createCategoryStdObject($newMemberCategory->getCatName(),
                                                    $newMemberCategory->getSortOrder(),
                                                    $newMemberCategory->getIdMemcat());
                }
                
                
                
                $jsonResponse['isSuccessful'] =  true;
                $this->serviceContainer['sort_utility']->stableUasort($categoryDataResult, function($sortArgumentA, $sortArgumentB) {
                    return $sortArgumentA->order - $sortArgumentB->order;
                });
                $jsonResponse['categoryData'] =  array_values($categoryDataResult);
            }
        }
        
        echo json_encode($jsonResponse);
    }
    
    /**
     * Creates a category standard object 
     *
     * @param string $name
     * @param integer $order
     * @param integer $id
     * @return stdClass
     */
    private function createCategoryStdObject($name, $order, $id)
    {
        $singleCategoryData = new \stdClass();
        $singleCategoryData->name =  $name;
        $singleCategoryData->order =  $order;
        $singleCategoryData->memberCategoryId =  $id;   
        
        return $singleCategoryData;
    }

}

/* End of file memberpage.php */
/* Location: ./application/controllers/memberpage.php */

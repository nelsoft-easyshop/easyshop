<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *  Memberpage controller
 *
 *  @author Sam Gavinio
 *  @author Stephen Janz Serafico
 *  @author Ryan Vazquez
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
use Easyshop\Upload\AssetsUploader as AssetsUploader;
use EasyShop\Product\ProductManager as ProductManager;

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
     * Number of product per category page
     *
     * @var integer
     */
    public $productsPerCategoryPage = 16;

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
        $this->categoryManager = $this->serviceContainer['category_manager'];
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
        $formBuild = $formFactory->createBuilder('form', null, ['csrf_protection' => false])
                                 ->setMethod('POST');                    
        
        /**
         * Overload default IsMobileUnique constraint
         */
        foreach($rules['mobile'] as $key => $mobileRule){
            if($mobileRule instanceof EasyShop\FormValidation\Constraints\IsMobileUnique){
                unset($rules['mobile'][$key]);
                break;
            }
        }
        $rules['mobile'][] = new EasyShop\FormValidation\Constraints\IsMobileUnique(['memberId' => $memberId]);
        $formBuild->add('fullname', 'text');
        $formBuild->add('gender', 'text', ['constraints' => $rules['gender']]);
        $formBuild->add('dateofbirth', 'text', ['constraints' => $rules['dateofbirth']]);
        $formBuild->add('mobile', 'text', ['constraints' => $rules['mobile']]);
        $formData["fullname"] = $this->input->post('fullname');
        $formData["gender"] = $this->input->post('gender');
        $formData["dateofbirth"] = $this->input->post('dateofbirth');
        $formData["mobile"] = $this->input->post('mobile');
        $form = $formBuild->getForm();
        $form->submit($formData); 

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
        $this->serviceContainer['string_utility'];
        $soldTransaction["transactions"] = $this->transactionManager
                                                ->getSoldTransactionDetails(
                                                      $this->session->userdata('member_id'),
                                                      (bool) $this->input->get("isOngoing"),
                                                      0,
                                                      PHP_INT_MAX,
                                                      $this->input->get("invoiceNo"),
                                                      $this->input->get("paymentMethod")
                                                );

        $exportTransactions = [];
        foreach($soldTransaction["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $data = null;            
                $prodSpecs = null;                      
                if(isset($product["attr"])) {
                    $productAttrCount = 0;
                    $attributeCount = count($product["attr"]);                    
                    foreach($product["attr"] as $attr => $attrValue ) {
                        $productAttrCount++;
                        $prodSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue)).($productAttrCount === $attributeCount ? null : " / ");
                    }
                }
                else {
                    $prodSpecs = "N/A";
                }

                $data .= "\n".$value["invoiceNo"];
                $data .= ",".html_escape(str_replace(","," ",$product["name"]));
                $data .= ",".$value["dateadded"]->format('Y-m-d H:i:s');
                $data .= ",".html_escape(str_replace(","," ",$value["buyerStoreName"]));
                $data .= ",".$value["orderQuantity"];
                $data .= ",".ucwords(strtolower($value["paymentMethod"]));
                $data .= ",".number_format((float)$product["price"], 2, '.', '');
                $data .= ",".$prodSpecs;
                $exportTransactions[] = $data;          
            }
        }

        $this->outputToCSVFormat($exportTransactions, "soldtransactions", true);
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
        $exportTransactions = [];
        foreach($boughTransactions["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $data = null;
                $prodSpecs = "";
                if(isset($product["attr"])) {
                    $productAttrCount = 0;
                    $attributeCount = count($product["attr"]);
                    foreach($product["attr"] as $attr => $attrValue ) {
                         $productAttrCount++;
                         $prodSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue)).($productAttrCount === $attributeCount ? "" : " / ");
                    }
                }
                else {
                    $prodSpecs = "N/A";
                }     
                  
                $data .= "\n".$value["invoiceNo"];
                $data .= ",".html_escape(str_replace(","," ",$product["name"]));
                $data .= ",".$value["dateadded"]->format('Y-m-d H:i:s');
                $data .= ",".html_escape(str_replace(","," ",$product["sellerStoreName"]));
                $data .= ",".$value["orderQuantity"];
                $data .= ",".ucwords(strtolower($value["paymentMethod"]));
                $data .= ",".number_format((float)$product["price"], 2, '.', '');
                $data .= ",".$prodSpecs;
                $exportTransactions[] = $data;
            }
        }

        $this->outputToCSVFormat($exportTransactions, "boughttransactions", false);        
    }

    /**
     * Outputs data into CSV file
     * @param  array $data
     * @return CSV file
     */
    private function outputToCSVFormat($data, $filename, $isForSold)
    {
        header("Content-Type: text/csv; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename.csv");
        $output = fopen('php://output', 'w');

        fputcsv($output, [ 
            'Transaction Number ',
            'Product Name',
            'Date of Transaction',
            ($isForSold) ? 'Buyers Name' : 'Sellers Name',
            'Order Quantity',
            'Payment Method',
            'Price',
            'Product Specifications'
        ]);
        fputcsv($output, $data, ' ',' ');
        fclose($output);
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
        $boughtTransactions["transactions"] = []; 
        foreach ($transactions["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $data = [];
                $productSpecs = "";
                if(isset($product["attr"]) && count($product["attr"])> 0) {
                    $productAttrCount = 0;
                    $attributeCount = count($product["attr"]);
                    foreach($product["attr"] as $attr => $attrValue ) {
                            $productAttrCount++;
                            $productSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue)).($productAttrCount === $attributeCount ? "" : " / ");
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
        $soldTransactions["transactions"] = [];
        foreach ($transactions["transactions"] as $value) {
            foreach ($value["product"] as $product) {
                $data = [];   
                $productSpecs = "";                 
                if(isset($product["attr"]) && count($product["attr"] > 0)) {
                    $productAttrCount = 0;
                    $attributeCount = count($product["attr"]);
                    foreach($product["attr"] as $attr => $attrValue ) {
                        $productAttrCount++;
                        $productSpecs .= ucwords(html_escape($attr)).":".ucwords(html_escape($attrValue)).($productAttrCount === $attributeCount ? "" : " / ");
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

        $orderEntity = $this->em->find("EasyShop\Entities\EsOrder", $data['transaction_num']);
        $orderProductStatusEntity = $this->em->find("EasyShop\Entities\EsOrderProductStatus", EsOrderProductStatus::ON_GOING);
        $orderProductEntity = $this->esOrderProductRepo
                                   ->findOneBy([
                                       "order" => $orderEntity,
                                       "status" => $orderProductStatusEntity
                                   ]);
        $serverResponse['isTransactionComplete'] = $orderProductEntity ? false : true;

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
                    'expected_date' => $this->input->post('expected_date') ? date("Y-m-d H:i:s", strtotime($this->input->post('expected_date'))) : "",
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
                        $newEsShippingComment = $productShippingCommentRepo->updateShippingComment($esShippingComment, $orderProductEntity, $postData['comment'], $memberEntity, $postData['courier'], $postData['tracking_num'], $postData['expected_date'], $postData['delivery_date']);
                    }
                    else {
                        $newEsShippingComment = $productShippingCommentRepo->addShippingComment($orderProductEntity, $postData['comment'], $memberEntity, $postData['courier'], $postData['tracking_num'], $postData['expected_date'], $postData['delivery_date']);
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
            'transact_num' => $this->input->post('transact_num'),
            'member_id' => $this->input->post('seller_id'),
            'method' => $this->input->post('method')
        ];
        $data['order_product'][0] = $this->input->post('order_product');
        if ( (bool) stripos($data['order_product'][0], '-')) {
            $productIds = explode('-', $data['order_product'][0]);
            $data['order_product'] = $productIds;
        }
        $serverResponse = [
            'result' => 'fail',
            'error' => 'Transaction does not exist.'
        ];

        if (is_array($data['order_product'])) {
            foreach($data['order_product'] as $orderProductId) {
                $checkOrderProductBasic = $this->esOrderProductRepo
                                               ->findOneBy([
                                                   'idOrderProduct' => $orderProductId,
                                                   'order' => $data['transact_num'],
                                                   'seller' => $data['member_id']
                                               ]);
                if ($checkOrderProductBasic) {
                    $isReject = $data['method'] === "reject";
                    $rejectTransaction = $this->esOrderProductRepo->updateIsReject($isReject, $checkOrderProductBasic);
                    if ( (bool) $rejectTransaction) {
                        $historyData['order_product_id'] = $this->esOrderProductRepo->find($orderProductId);
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
            }
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
                $paginationData['lastPage'] = ceil($ongoingSoldTransactionsCount["productCount"] / $this->transactionRowCount);
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
                $paginationData['lastPage'] = ceil($completeSoldTransactionsCount["productCount"] / $this->transactionRowCount);

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

        $page = $this->input->get('page') ? trim($this->input->get('page')) : 1;
        $requestType = trim($this->input->get('request'));
        $sortType = trim($this->input->get('sort'));
        $searchString = trim($this->input->get('search_string'));
        $viewData = $this->__generateProductListView($memberId, $page, $requestType, $sortType, $searchString);

        $responseArray = [
            'isSuccess' => $deleteResponse,
            'message' => $deleteResponse ? "" : "You can't delete this item.",
            'html' =>  $this->load->view('partials/dashboard-products', $viewData, true),
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

        $page = $this->input->get('page') ? trim($this->input->get('page')) : 1;
        $requestType = trim($this->input->get('request'));
        $sortType = trim($this->input->get('sort'));
        $searchString = trim($this->input->get('search_string'));
        $viewData = $this->__generateProductListView($memberId, $page, $requestType, $sortType, $searchString);
        
        $responseArray = [
            'isSuccess' => $deleteResponse,
            'message' => $deleteResponse ? "" : "You can't delete this item.",
            'html' =>  $this->load->view('partials/dashboard-products', $viewData, true),
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
        
        $page = $this->input->get('page') ? trim($this->input->get('page')) : 1;
        $requestType = trim($this->input->get('request'));
        $sortType = trim($this->input->get('sort'));
        $searchString = trim($this->input->get('search_string'));
        $viewData = $this->__generateProductListView($memberId, $page, $requestType, $sortType, $searchString);

        $responseArray = [
            'isSuccess' => $restoreResponse,
            'message' => $restoreResponse ? "" : "You can't restore this item.",
            'html' =>  $this->load->view('partials/dashboard-products', $viewData, true),
        ];

        echo json_encode($responseArray);
    }

    
    /**
     * Generate the product page and pagination
     *
     * @param integer $page
     * @param $requestType string
     * @param $sortType string
     * @param $searchString string
     * @return mixed
     */
    private function __generateProductListView($memberId, $page, $requestType, $sortType, $searchString)
    {
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

        $productCount = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                 ->getUserProductCount($memberId,
                                                       $deleteConditions, 
                                                       $draftConditions, 
                                                       $searchString);
        $userProducts = $this->serviceContainer['product_manager']
                             ->getProductsByUser($memberId,
                                                 $deleteConditions,
                                                 $draftConditions,
                                                 ProductManager::PRODUCT_COUNT_DASHBOARD*($page-1),
                                                 $searchString,
                                                 $sortType);                                 
        $paginationData = [
            'lastPage' => ceil($productCount/ProductManager::PRODUCT_COUNT_DASHBOARD)
            ,'isHyperLink' => false
            , 'currentPage' => $page
        ];

        
        $viewData = [
            'products' => $userProducts,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];

        return $viewData;
    }
    
    /**
     * Updates user's products status
     * @return JSON
     */
    public function manageUserProduct()
    {
        $member = $this->accountManager
                       ->authenticateMember($this->input->post('username'), 
                                            $this->input->post('password'), 
                                            false, 
                                            true);  
        $actionResult = false;
        $resultMessage = "";
        if ($member['member']) {
            if($this->session->userdata('member_id') && ($member["member"]->getIdMember() !== $this->session->userdata('member_id'))) {
                $resultMessage = 'Invalid Username/Password';
            }
            else {
                $postedAction = strtolower($this->input->post("action"));
                $isActionValid = $actionResult = true;
                switch ($postedAction) {
                    case 'restore':
                        $productStatus = EsProduct::DELETE;
                        $desiredStatus = EsProduct::ACTIVE;
                        break;
                    case 'disable':
                        $productStatus = EsProduct::ACTIVE;
                        $desiredStatus = EsProduct::DELETE;
                        break;
                    case 'delete':
                        $productStatus = EsProduct::DELETE;
                        $desiredStatus = EsProduct::DISABLE;
                        break;
                    default:
                        $isActionValid = false;
                        break;
                }
                $productManager = $this->serviceContainer['product_manager'];
                $isUpdateSuccess = false;
                if($isActionValid) {
                    $isUpdateSuccess = $productManager->updateUserProductStatus(
                                                        $member["member"]->getIdMember(), 
                                                        $productStatus, 
                                                        $desiredStatus
                                                    );
                }
                if(!$isUpdateSuccess) {
                    $resultMessage = "Database Error";
                    $actionResult = false;
                }
            }
        }
        else {
            $resultMessage = 'Invalid Username/Password';
        }

        $result = [
            "result" => $actionResult,
            "message" => $resultMessage
        ];

        echo json_encode($result); 
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
        $getData = $hashUtility->decode($this->input->post('h'));

        $authenticationResult = $this->accountManager
                                     ->authenticateMember($this->input->post('username'), 
                                                          $this->input->post('password'), 
                                                          false, 
                                                          true);  
        $isActivationRequestValid = $authenticationResult['member']
                                    && $authenticationResult['member']->getIdMember() === (int)$getData["memberId"] 
                                    && (bool)$authenticationResult['member']->getIsActive() === false ;
        $response = false;
        if($this->input->post("activateAccountButton") && $isActivationRequestValid) {
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
        $memberId = $this->session->userdata('member_id');
        $page = $this->input->get('page') ? trim($this->input->get('page')) : 1;
        $requestType = trim($this->input->get('request'));
        $sortType = trim($this->input->get('sort'));
        $searchString = trim($this->input->get('search_string'));
        $viewData = $this->__generateProductListView($memberId, $page, $requestType, $sortType, $searchString);
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
     * Gets the store colors
     *
     * @return JSON
     */
    public function getStoreColor()
    {
        $memberId = $this->session->userdata('member_id');
        $response = [];
        if($memberId){
            $response['colors'] = $this->serviceContainer['entity_manager']
                                       ->getRepository('EasyShop\Entities\EsStoreColor')
                                       ->getAllColors(true);
        }
        echo json_encode($response);
    }
    
    /**
     * Gets the store colors
     *
     * @return JSON
     */
    public function getStoreCategories()
    {
        $memberId = $this->session->userdata('member_id');
        $entityManager = $this->serviceContainer['entity_manager'];
        $member = $entityManager->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(['idMember' => $memberId]);
                                    
        $response = [];
        if($member){
            $numberOfCustomCategories = $entityManager->getRepository('EasyShop\Entities\EsMemberCat')
                                                      ->getCountCustomCategories($memberId);
            /**
             * If there are no memberCategories yet, save default categories as
             * new custom categories
             */
            if((int)$numberOfCustomCategories === 0){
                $allUserCategories = $this->serviceContainer['category_manager']
                                          ->getUserCategories($memberId);
            
                foreach($allUserCategories as $index => $category){
                    $datetimeToday = date_create(date("Y-m-d H:i:s"));
                    $newMemberCategory = new EasyShop\Entities\EsMemberCat();
                    $newMemberCategory->setMember($member);
                    $newMemberCategory->setCatName($category['name']);
                    $newMemberCategory->setSortOrder($category['sortOrder']);
                    $newMemberCategory->setCreatedDate($datetimeToday);
                    $newMemberCategory->setlastModifiedDate($datetimeToday);
                    $entityManager->persist($newMemberCategory); 

                    $childCategories = $category['child_cat'];
                    $productIds = $entityManager->getRepository('EasyShop\Entities\EsProduct')
                                                ->getDefaultCategorizedProducts($memberId, $childCategories, PHP_INT_MAX);
                    $products = $entityManager->getRepository('EasyShop\Entities\EsProduct')
                                              ->findByIdProduct($productIds);
                    foreach($products as $product){
                        $memberCategoryProduct = new EasyShop\Entities\EsMemberProdcat();
                        $memberCategoryProduct->setMemcat($newMemberCategory);
                        $memberCategoryProduct->setProduct($product);
                        $memberCategoryProduct->setCreatedDate($datetimeToday);
                        $entityManager->persist($memberCategoryProduct);
                    } 
                } 
                $entityManager->flush();
            }
            
            
            $memberCategories = $entityManager->getRepository('EasyShop\Entities\EsMemberCat')
                                              ->getCustomCategoriesObject($memberId);     
            $resultCategories = [];
            foreach($memberCategories as $memberCategory){
                $resultCategories[] = $this->createCategoryStdObject(    
                                                $memberCategory->getCatName(),
                                                $memberCategory->getSortOrder(),
                                                $memberCategory->getIdMemcat()
                                            );
            }
            $this->serviceContainer['sort_utility']->stableUasort($resultCategories, function($sortArgumentA, $sortArgumentB) {
                return $sortArgumentA->order - $sortArgumentB->order;
            });

            $response['storeCategories'] = $resultCategories;;
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
    public function updateStoreCategoryOrder()
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
            foreach($categoryData as $category){
                $indexedCategoryData[$category->categoryid] = $category;
            }

            $memberCategories = $entityManager->getRepository('EasyShop\Entities\EsMemberCat')
                                              ->getCustomCategoriesObject($memberId, array_keys($indexedCategoryData));
            $datetimeToday = date_create(date("Y-m-d H:i:s"));
            $categoryDataResult = [];
            foreach($memberCategories as $memberCategory){
                $memberCategoryId = $memberCategory->getIdMemcat(); 
                if( isset($indexedCategoryData[$memberCategoryId]) ){
                    $inputCategoryData = $indexedCategoryData[$memberCategoryId];
                    $memberCategory->setSortOrder($inputCategoryData->order);
                    $memberCategory->setlastModifiedDate($datetimeToday);
                    $categoryDataResult[] = $this->createCategoryStdObject($inputCategoryData->name,
                                                                           $inputCategoryData->order,
                                                                           $memberCategoryId);
                }
            }
            $entityManager->flush();

            $jsonResponse['isSuccessful'] =  true;
            $this->serviceContainer['sort_utility']->stableUasort($categoryDataResult, function($sortArgumentA, $sortArgumentB) {
                return $sortArgumentA->order - $sortArgumentB->order;
            });
            $jsonResponse['categoryData'] =  array_values($categoryDataResult);
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


    /**
     * Returns the Custom Category json
     *
     * @return JSON
     */
    public function getCustomCategory()
    {
        $categoryId = (int)$this->input->get('categoryId');
        $searchString = $this->input->get('searchString') ? trim( $this->input->get('searchString') ) : '';
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $page--;
        $page = $page >= 0 ? $page : 0;
        $offset = $page * $this->productsPerCategoryPage;
        $memberId = $this->session->userdata('member_id');
        $response = false;
        $allUserCategories = $this->serviceContainer['category_manager']
                                  ->getUserCategories($memberId);    
        $categories = $this->serviceContainer['entity_manager']
                            ->getRepository('EasyShop\Entities\EsMemberCat')
                            ->getCustomCategoriesObject($memberId, [$categoryId]);    
        if($categories){
            $category = reset($categories);
            $response['categoryName'] = $category->getCatName();
            $response['categoryId'] = $categoryId;
            $response['products'] = [];
            $productIds = $this->serviceContainer['entity_manager']
                                ->getRepository('EasyShop\Entities\EsMemberProdcat')
                                ->getPagedCustomCategoryProducts(
                                    $memberId, 
                                    $categoryId, 
                                    $this->productsPerCategoryPage, 
                                    $offset, 
                                    ["idProduct" => "DESC"],
                                    $searchString
                                );
            $products = $this->serviceContainer['entity_manager']
                                ->getRepository('EasyShop\Entities\EsProduct')
                                ->findByIdProduct($productIds);
            foreach($products as $key => $product){
                $productId = $product->getIdProduct();
                $response['products'][$key]['productName'] = $product->getName();
                $image = $this->serviceContainer['entity_manager']
                                ->getRepository('EasyShop\Entities\EsProductImage')
                                ->getDefaultImage($productId);
                $response['products'][$key]['imageFilename'] = $image->getFilename();
                $response['products'][$key]['imageDirectory'] = $image->getDirectory();
                $response['products'][$key]['id'] = $productId;
            }
    
        }

        echo json_encode($response);
    }    

    /**
     * Get all products of a member
     *
     * @return JSON
     */
    public function getAllMemberProducts()
    {
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $excludeCategoryId = $this->input->get('excludeCategoryId') ? (int)$this->input->get('excludeCategoryId') : 0;
        $searchString =  $this->input->get('searchString') ? trim( $this->input->get('searchString') ) : '';
        $inputExcludeProductIds =  $this->input->get('excludeProductId') ? json_decode( $this->input->get('excludeProductId') ) : [];
        $page--;
        $page = $page >= 0 ? $page : 0;
        $offset = $page * $this->productsPerCategoryPage;
        $memberId = $this->session->userdata('member_id');

        $excludeIds = [];
        if($excludeCategoryId !== 0){
            $excludeIds = $this->serviceContainer['entity_manager']
                               ->getRepository('EasyShop\Entities\EsMemberProdcat')
                               ->getPagedCustomCategoryProducts($memberId, [ $excludeCategoryId ], PHP_INT_MAX);
        }
        
        $excludeIds = array_merge($excludeIds, $inputExcludeProductIds);
  
        $products = $this->serviceContainer['entity_manager']
                         ->getRepository('EasyShop\Entities\EsProduct')
                         ->getUserProducts($memberId, 0, 0, $offset, $this->productsPerCategoryPage, $searchString, "p.idProduct", $excludeIds);
        $response['products'] = [];
        foreach($products as $product){
            $productId = $product->getIdProduct();
            $image = $this->serviceContainer['entity_manager']
                          ->getRepository('EasyShop\Entities\EsProductImage')
                          ->getDefaultImage($productId);
            $response['products'][] = [
                'productName' => $product->getName(),
                'id' => $productId,
                'imageFilename' => $image->getFilename(),
                'imageDirectory' => $image->getDirectory(),
            ];
        }
        
        echo json_encode($response);
    }

    
    /**
     * Performs the database insertion of new member custom category
     *
     * @return JSON
     */
    public function addCustomCategory()
    {
        $memberId =   $this->session->userdata('member_id');
        $categoryName = $this->input->post("categoryName") ? 
                        trim($this->input->post("categoryName")) : '';
        $productIds =  $this->input->post("productIds") ? 
                        json_decode($this->input->post("productIds")) 
                        : [];
        $result = false;
        if($memberId){
              $result = $this->categoryManager
                             ->createCustomCategory(
                                    $categoryName,
                                    $memberId,
                                    $productIds
                              );
        }
      
        echo json_encode($result);
    }
    

    /**
     * Performs the update actions of User Custom Category Products
     *
     * @return JSON
     */
    public function editCustomCategory()
    {
        $memberId =   $this->session->userdata('member_id');
        $result = false;
        if($memberId){
            $memberCategoryId = $this->input->post("categoryId");
            $categoryName = $this->input->post("categoryName") ? 
                            trim($this->input->post("categoryName")) : '';
            $productIds =    $this->input->post("productIds") ? 
                            json_decode($this->input->post("productIds")) 
                            : [];
            $result = $this->categoryManager->editUserCustomCategoryProducts(
                        $memberCategoryId,
                        $categoryName,
                        $productIds,
                        $memberId
                    );
        }
        echo json_encode($result);
    }    

    /**
     * Updates is_delete field to '1' of a custom category
     * @return bool
     */
    public function deleteCustomCategory()
    {
        $customCategoryIds[] = $this->input->post("categoryIds") ? 
                               json_decode($this->input->post("categoryIds")) : [];
        $memberId = $this->session->userdata('member_id');
        $response = false;
        if($memberId){
            $response =  $this->categoryManager
                              ->deleteUserCustomCategory(
                                    $customCategoryIds,
                                    $memberId
                                );
        }
        echo json_encode($response);
    }

}

/* End of file memberpage.php */
/* Location: ./application/controllers/memberpage.php */

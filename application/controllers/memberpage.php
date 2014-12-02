<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
use \Easyshop\Upload\AssetsUploader as AssetsUploader;

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
        // $this->qrManager = $this->serviceContainer['qr_code_manager'];
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
     * sample function for qr code generator
     */
    public function sample()
    {
        $this->qrManager->save("kurtwilkinson/213213/asdasd.com", "asd", 'L', 4, 2);
        echo '<img src="'.getAssetsDomain().$this->qrManager->getImagePath('asd').'"/>';
    }

    /**
     *  Class Index. Renders Memberpage
     */
    public function index()
    {
        $data = $this->fill_header();
        if(!$this->session->userdata('member_id')){
            redirect('/', 'refresh');
        }
        $data['tab'] = $this->input->get('me');
        $data = array_merge($data, $this->fill_view());
        $data['render_logo'] = false;
        $data['render_searchbar'] = false;
        if($this->session->userdata('member_id')) {
            $data['user_details'] = $this->fillUserDetails();
        }
        $data['homeContent'] = $this->fillCategoryNavigation();
        $data['transactionInfo'] = $this->getMemberPageDetails();
        $data = array_merge($data, $this->fill_header());

        $socialMediaLinks = $this->getSocialMediaLinks();
        $footerData['facebook'] = $socialMediaLinks["facebook"];
        $footerData['twitter'] = $socialMediaLinks["twitter"];

        $this->load->view('templates/header_primary', $data);
        $this->load->view('pages/user/dashboard/dashboard-primary', $data);
        $this->load->view('templates/footer_primary', $footerData);

        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];
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
        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                    ->setMethod('POST')
                    ->add('fullname', 'text')
                    ->add('gender', 'text')
                    ->add('dateofbirth', 'text', array('constraints' => $rules['dateofbirth']))
                    ->add('mobile', 'text', array('constraints' => $rules['mobile']))
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
            $validGender = strlen($formData['gender']) === 0 ? EasyShop\Entities\EsMember::DEFAULT_GENDER : $formData['gender'];
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
     *  Used to edit School Information under Personal Information Tab
     *  Returns success, fail, or error, with error message
     *
     *  @return JSON
     */
    public function edit_school()
    {
        if(($this->input->post('personal_profile_school'))&&($this->form_validation->run('personal_profile_school')))
        {
            $arr = $this->input->post();
            for($i = 1; $i<=count($arr)>>2; $i++)
            {
                $postdata = array(
                    'school' => $arr['schoolname'.$i],
                    'year' => $arr['schoolyear'.$i],
                    'level' => $arr['schoollevel'.$i],
                    'school_count' => $arr['schoolcount'.$i],
                );
                $uid = $this->session->userdata('member_id');
                $result = $this->memberpage_model->edit_school_by_id($uid, $postdata);
                //If database entry fails, break
                if(!$result){
                    break;
                }
            }
            $uid = $this->session->userdata('member_id');
            $data = $this->memberpage_model->get_school_by_id($uid);

            $data['result'] = $result ? 'success' : 'fail';
            $data['errmsg'] = $result ? '' : 'Database update error';
        }
        else{
            $data['result'] = 'error';
            $data['errmsg'] = 'Failed to validate form.';
        }

        echo json_encode($data);
    }
    
    /**
     *  Function used to delete address, school, and work
     *      under Personal Information Tab
     *  Returns 1 on success, 0 otherwise
     *
     *  @return integer
     */
    public function deletePersonalInfo()
    {
        $field = html_escape($this->input->post('field'));
        if( $field !== '' ){
            $member_id = $this->session->userdata('member_id');
            $result = $this->memberpage_model->deletePersonalInformation($member_id, $field);
            if($result){
                echo 1;
            }
            else{
                echo 0;
            }
        }
    }

    /**
     *  Export Sold transactions to CSV file
     */
    public function exportSellTransactions()
    {       
        $this->em = $this->serviceContainer['entity_manager'];
        $EsOrderRepository = $this->em->getRepository('EasyShop\Entities\EsOrder'); 
        $EsOrderProductAttributeRepository = $this->em->getRepository('EasyShop\Entities\EsOrderProductAttr');
        $soldTransaction["transactions"] = $EsOrderRepository->getUserSoldTransactions($this->session->userdata('member_id'));

        foreach($soldTransaction["transactions"] as $key => $value) {
            $attr = $EsOrderProductAttributeRepository->getOrderProductAttributes($value["idOrder"]);
            if(count($attr) > 0) {
                array_push($soldTransaction["transactions"][$key], ["attributes" => $attr]);
            }
        }  

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
            if(isset($value["0"])) {
                foreach($value["0"]["attributes"] as $attr) {
                     $prodSpecs .= ucwords($attr["attrName"]).":".ucwords($attr["attrValue"])." / ";
                }
            }
            else {
                $prodSpecs = "N/A";
            }
            fputcsv($output, array( $value["invoiceNo"]
                                    , $value["productname"]
                                    , $value["dateadded"]->format('Y-m-d H:i:s')
                                    , $value["fullname"]
                                    , $value["orderQuantity"]
                                    , ucwords(strtolower($value["paymentMethod"]))
                                    , number_format((float)$value["totalOrderProduct"], 2, '.', '')
                                    , $prodSpecs
            ));
            $prodSpecs = "";
        }
    }

    /**
     *  Export Buy transactions to CSV file
     */
    public function exportBuyTransactions()
    {       
        $this->em = $this->serviceContainer['entity_manager'];
        $EsOrderRepository = $this->em->getRepository('EasyShop\Entities\EsOrder');
        $EsOrderProductAttributeRepository = $this->em->getRepository('EasyShop\Entities\EsOrderProductAttr');
        $boughTransactions["transactions"] = $EsOrderRepository->getUserBoughtTransactions($this->session->userdata('member_id'));
        
        foreach($boughTransactions["transactions"] as $key => $value) {
            $attr = $EsOrderProductAttributeRepository->getOrderProductAttributes($value["idOrder"]);
            if(count($attr) > 0) {
                array_push($boughTransactions["transactions"][$key], array("attributes" => $attr));
            }
        }      

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
            if(isset($value["0"])) {
                foreach($value["0"]["attributes"] as $attr) {
                     $prodSpecs .= ucwords($attr["attrName"]).":".ucwords($attr["attrValue"])." / ";
                }
            }
            else {
                $prodSpecs = "N/A";
            }

            fputcsv($output, array( $value["invoiceNo"]
                                    , $value["productname"]
                                    , $value["dateadded"]->format('Y-m-d H:i:s')
                                    , $value["fullname"]
                                    , $value["orderQuantity"]
                                    , ucwords(strtolower($value["paymentMethod"]))
                                    , number_format((float)$value["total"], 2, '.', '')
                                    , $prodSpecs
            ));
            $prodSpecs = "";
        }
    }

    /**
     * Returns bought on-going transactions of the user
     *  @return VIEW
     */
    public function printBuyTransactions()
    {

        $this->em = $this->serviceContainer['entity_manager'];
        $EsOrderRepository = $this->em->getRepository('EasyShop\Entities\EsOrder');
        $EsOrderProductAttributeRepository = $this->em->getRepository('EasyShop\Entities\EsOrderProductAttr');
        $boughTransactions["transactions"] = $EsOrderRepository->getUserBoughtTransactions($this->session->userdata('member_id'));
        foreach($boughTransactions["transactions"] as $key => $value) {
            $attr = $EsOrderProductAttributeRepository->getOrderProductAttributes($value["idOrder"]);
            if(count($attr) > 0) {
                array_push($boughTransactions["transactions"][$key], array("attributes" => $attr));
            }
        }

        $this->load->view("pages/user/printboughttransactions", $boughTransactions);
    }

    /**
     * Returns sold on-going transactions of the user
     *  @return VIEW
     */
    public function printSellTransactions()
    {
        $this->em = $this->serviceContainer['entity_manager'];
        $EsOrderRepository = $this->em->getRepository('EasyShop\Entities\EsOrder'); 
        $EsOrderProductAttributeRepository = $this->em->getRepository('EasyShop\Entities\EsOrderProductAttr');
        $soldTransaction["transactions"] = $EsOrderRepository->getUserSoldTransactions($this->session->userdata('member_id'));

            foreach($soldTransaction["transactions"] as $key => $value) {
                $attr = $EsOrderProductAttributeRepository->getOrderProductAttributes($value["idOrder"]);
                if(count($attr) > 0) {
                    array_push($soldTransaction["transactions"][$key], ["attributes" => $attr]);
                }
            }
           
        $this->load->view("pages/user/printselltransactionspage", $soldTransaction);
    }
    

    /**
     *  Fetch all data needed when displaying the Member page
     *  
     *  @return array
     */
    public function fill_view()
    {
        $uid = $this->session->userdata('member_id');
        $user_product_count = $this->memberpage_model->getUserItemCount($uid);
        $um = $this->serviceContainer['user_manager'];

        
        $data = array(
            'title' => 'Easyshop.ph - Member Profile',
            'image_profile' => $um->getUserImage($uid),
            'active_products' => $this->memberpage_model->getUserItems($uid,0),
            'deleted_products' => $this->memberpage_model->getUserItems($uid,1),
            'draft_products' => $this->memberpage_model->getUserItems($uid,0,1),
            'active_count' => intval($user_product_count['active']),
            'deleted_count' => intval($user_product_count['deleted']),
            'sold_count' => intval($user_product_count['sold']),
            'draft_count' => intval($user_product_count['draft'])
        );
        $data = array_merge($data, $this->memberpage_model->getLocationLookup());
        $data = array_merge($data,$this->memberpage_model->get_member_by_id($uid));
        $data = array_merge($data,$this->memberpage_model->get_work_by_id($uid));
        $data =  array_merge($data,$this->memberpage_model->get_school_by_id($uid));
        $data['bill'] =  $this->memberpage_model->get_billing_info($uid);
        $data['transaction'] = array(
            'buy' => $this->memberpage_model->getBuyTransactionDetails($this->contentXmlFile, $uid, 0),
            'sell' => $this->memberpage_model->getSellTransactionDetails($uid, 0),
            'complete' => array(
                'buy' => $this->memberpage_model->getBuyTransactionDetails($this->contentXmlFile, $uid, 1),
                'sell' => $this->memberpage_model->getSellTransactionDetails($uid, 1)
            )
        );
        $data['transaction']['count'] = $this->memberpage_model->getTransactionCount($uid);
        $data['allfeedbacks'] = $this->memberpage_model->getFeedback($uid);
        $data['sales'] = array(
            'release' => $this->memberpage_model->getNextPayout($uid),
            'balance' => $this->memberpage_model->getUserBalance($uid)
        );

        //If delivery address is equal to personal address, hide setasdefaultaddress in delivery address tab
        if( $data['cityID']===$data['c_cityID'] && $data['stateregionID']===$data['c_stateregionID'] && 
            $data['address']===$data['c_address'] ){
            $data['show_default_address'] = false;
        }
        else{
            $data['show_default_address'] = true;
        }

        return $data;
    }

    /**
     *  Used to upload avatar image on both Member page and Vendor page
     *  Reloads page on success.
     *
     *  NOTE: For browsers with minimal JS capabilities, this function reloads the page
     *      and displays an error for 3 seconds, then reloads the page to the original URL,
     *      member page or vendor page
     */
    public function upload_img()
    {
        $cropCoordinates = array(
            'x' => $this->input->post('x'),
            'y' => $this->input->post('y'),
            'w' => $this->input->post('w'),
            'h' => $this->input->post('h')
        );
        $isVendor = $this->input->post('vendor') ? true : false;
        $vendorLink = html_escape($this->input->post('url'));
        $memberId = $this->session->userdata('member_id');
        
        $uploadResult = $this->serviceContainer["assets_uploader"]->uploadUserAvatar($memberId, key($_FILES), $cropCoordinates);
        $member = $uploadResult['member'];
  
        $redirectUrl = '/';
        if($member !== null){
            $vendorLink .= (trim($vendorLink) === "") ? "" : "/".$vendorLink;
            $redirectUrl = '/'. ($isVendor ? $member->getSlug().$vendorLink : 'me');
        }      
        if(empty($uploadResult['error']) && $member){
            redirect($redirectUrl);
        }

        $data = ['allowedFileTypes' => AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES,
                    'maxSize' => AssetsUploader::MAX_ALLOWABLE_SIZE_KB,
                    'maxHeight' => AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX,
                    'maxWidth' => AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX,
                    'redirectUrl' => $redirectUrl,];
        $this->load->view('errors/uploadError', $data);
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
     *  Used to edit work information under Personal Information Tab
     *  Returns json encoded success, error, or fail, with error message
     *
     *  @return JSON
     */
    public function edit_work()
    {
        if(($this->input->post('personal_profile_work_btn'))&&($this->form_validation->run('personal_profile_work')))
        {
            $rowcount = count($this->input->post()) - 1;
            $rowcount = $rowcount / 4;
            $postdata = array();
            for($x=1;$x<=$rowcount;$x++){
                $postdata = array(
                    'companyname' => $this->input->post('companyname'.$x),
                    'designation' => $this->input->post('designation'.$x),
                    'year' => $this->input->post('year'.$x),
                    'count' => $this->input->post('workcount'.$x)
                );
                $uid = $this->session->userdata('member_id');
                $result = $this->memberpage_model->edit_work_by_id($uid, $postdata);

                if(!$result){
                    break;
                }
            }
            $uid = $this->session->userdata('member_id');
            $data = $this->memberpage_model->get_work_by_id($uid);

            $data['result'] = $result ? 'success' : 'fail';
            $data['errmsg'] = $result ? '' : 'Database update error.';

        }
        else{
            $data['result'] = 'error';
            $data['errmsg'] = 'Failed to validate form.';
        }

        echo json_encode($data);
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
                $order = $this->entityManager->getRepository('EasyShop\Entities\EsOrder')->find($data['order_id']);
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
        $imageArray = [
            "/assets/images/landingpage/templates/header-img.png",
            "/assets/images/appbar.home.png",
            "/assets/images/appbar.message.png",
            "/assets/images/landingpage/templates/facebook.png",
            "/assets/images/landingpage/templates/twitter.png"
        ];

        /**
         *  DEFAULT RESPONSE HANDLER
         *  Item Received / Cancel Order / Complete(CoD)
         */
        if( $this->input->post('buyer_response') || $this->input->post('seller_response') || $this->input->post('cash_on_delivery') ){
            $memberId = $this->session->userdata('member_id');

            if ( $this->input->post('buyer_response') ) {
                $data['order_product_id'] = $this->input->post('buyer_response');
                $data['status'] = EsOrderProductStatus::FORWARD_SELLER;
            }
            else if ( $this->input->post('seller_response') ) {
                $data['order_product_id'] = $this->input->post('seller_response');
                $data['status'] = EsOrderProductStatus::RETURNED_BUYER;
            }
            else if ( $this->input->post('cash_on_delivery') ) {
                $data['status'] = EsOrderProductStatus::CASH_ON_DELIVERY;
                if (stripos($this->input->post('cash_on_delivery'), '-') === false) {
                    $data['order_product_id'][0] = $this->input->post('cash_on_delivery');
                }
                else {
                    $productIds = explode('-', $this->input->post('cash_on_delivery'));
                    $data['order_product_id'] = $productIds;
                }
            }
            if (is_array($data['order_product_id'])) {
                foreach ($data['order_product_id'] as $orderProductId) {
                    $result = $this->transactionManager->updateTransactionStatus($data['status'], $orderProductId, $data['transaction_num'], $data['invoice_num'], $data['member_id']);
                    if( $result['o_success'] >= 1 ) {
                        $parseData = $this->transactionManager->getOrderProductTransactionDetails($data['transaction_num'], $orderProductId, $data['member_id'], $data['invoice_num'], $data['status']);
                        $parseData['store_link'] = base_url() . $parseData['user_slug'];
                        $parseData['msg_link'] = base_url() . "messages/#" . $parseData['user'];
                        $socialMediaLinks = $this->getSocialMediaLinks();
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

            $getTransaction = $this->entityManager->getRepository('EasyShop\Entities\EsOrder')
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
            $getTransaction = $this->entityManager->getRepository('EasyShop\Entities\EsOrder')
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

        if( $this->form_validation->run('addShippingComment') ){
            $postData = [
                'comment' => $this->input->post('comment'),
                'order_product' => $this->input->post('order_product'),
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
                                        ->findOneBy(["idOrderProduct" => $postData['order_product'],
                                            "seller" => $memberEntity,
                                            "order" => $orderEntity
                                        ]);
            $shippingCommentEntity = $em->getRepository("EasyShop\Entities\EsProductShippingComment")
                ->findOneBy(["orderProduct" => $orderProductEntity,
                    "member" => $memberEntity
                ]);

            if( count($shippingCommentEntity) === 1 ){
                $exactShippingComment = $em->getRepository("EasyShop\Entities\EsProductShippingComment")
                                           ->getExactShippingComment($postData);
            }

            if( count($orderProductEntity) === 1 ){
                $boolAddShippingComment = $this->payment_model->addShippingComment($postData);
                $serverResponse['result'] = $boolAddShippingComment ? 'success' : 'fail';
                $serverResponse['error'] = $boolAddShippingComment ? '' : 'Failed to insert in database.';

                if( $boolAddShippingComment && ( count($shippingCommentEntity) === 0 || count($exactShippingComment) === 0 ) ){
                    $buyerEntity = $orderEntity->getBuyer();
                    $buyerEmail = $buyerEntity->getEmail();
                    $buyerEmailSubject = $this->lang->line('notification_shipping_comment');
                    $imageArray = array(
                        "/assets/images/landingpage/templates/header-img.png",
                        "/assets/images/landingpage/templates/facebook.png",
                        "/assets/images/landingpage/templates/twitter.png"
                    );

                    $parseData = $postData;
                    $socialMediaLinks = $this->getSocialMediaLinks();
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
            $order = $this->entityManager->getRepository('EasyShop\Entities\EsOrder')->find($data['transact_num']);
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
                    $historyData['order_product_status'] = $this->entityManager->getRepository('EasyShop\Entities\EsOrderProductStatus')->find(EsOrderProductStatus::STATUS_REJECT);
                }
                else if ($data['method'] === 'unreject') {
                    $historyData['comment'] = 'UNREJECTED';
                    $historyData['order_product_status'] = $this->entityManager->getRepository('EasyShop\Entities\EsOrderProductStatus')->find(EsOrderProductStatus::ON_GOING);
                }
                $this->entityManager->getRepository('EasyShop\Entities\EsOrderProductHistory')->createHistoryLog($historyData['order_product_id'], $historyData['order_product_status'], $historyData['comment']);
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
    public function vendorStoreDesc()
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
     *  Used to modify store name in vendor page
     *
     *  @return JSON
     */
    public function vendorStoreName()
    {
        $serverResponse = array(
            'result' => false,
            'error' => 'Failed to submit form'
        );

        if($this->input->post('store_name_hidden')){
            $storeName = $this->input->post('store_name');
            $memberId = $this->session->userdata('member_id');
            $userMgr = $this->serviceContainer['user_manager'];

            $isStorenameAvailable = $userMgr->setUser->setStoreName($memberId, $storeName);

            $serverResponse['result'] = $isStorenameAvailable;
            $serverResponse['error'] = $isStorenameAvailable ? '' : 'Store name already used!';

            if($isStorenameAvailable){
                $em = $this->serviceContainer['entity_manager'];
                $currUser = $em->find('EasyShop\Entities\EsMember',$memberId);
                $serverResponse['username'] = $currUser->getUsername();
                $serverResponse['storename'] = $currUser->getStoreName();
            }
        }

        echo json_encode($serverResponse);
    }

    /**
     *  Used for uploading banner in vendor page. 
     */
    public function banner_upload()
    {
        $cropData = array(
            'x' => $this->input->post('x'),
            'y' => $this->input->post('y'),
            'w' => $this->input->post('w'),
            'h' => $this->input->post('h')
        );
        $memberId = $this->session->userdata('member_id');
        $vendorLink = html_escape($this->input->post('url'));
  
        $uploadResult = $this->serviceContainer['assets_uploader']->uploadUserBanner($memberId,key($_FILES), $cropData);
        $member = $uploadResult['member'];

        if(empty($uploadResult['error']) && $member){
            redirect("/".$member->getSlug(). "/" . $vendorLink);
        }
        
        
        $redirectUrl = '/'. ($member !== null ? $member->getSlug() : "");
        $data = ['allowedFileTypes' => AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES,
                    'maxSize' => AssetsUploader::MAX_ALLOWABLE_SIZE_KB,
                    'maxHeight' => AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX,
                    'maxWidth' => AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX,
                    'redirectUrl' => $redirectUrl,];
        $this->load->view('errors/uploadError', $data);
     
    }
        
    /**
     *  Used for changing store URL. When userslug !== username, 
     *      user will not be able to change url again.
     *  Returns json encoded success or fail with error message
     *
     *  @return JSON
     */
    public function editUserSlug()
    {
        # Require config file for list of controllers (filenames) ; returns $controllerConfig
        require_once(APPPATH . 'config/param/controllers.php');

        $serverResponse = array(
            'result' => 'fail',
            'error' => 'Failed to validate form.'
        );
        if( $this->input->post('userslug') && $this->form_validation->run('edit_userslug') ){
            $memberID = $this->session->userdata('member_id');
            $userslug = strtolower($this->input->post('userslug'));
            
            #Check database if this slug can be used, if size>0, slug already used (hence cannot be used again)
            $resultCount = $this->memberpage_model->validateUserSlugChange($memberID,$userslug);
            
            #Check if slug is currently used for routing
            $myRoutes = $this->router->routes;
            $restrictedList = array();
            foreach( $myRoutes as $ro=>$co ){
                #get 1st restricted word from key
                $thisroute = preg_replace('/\(.{2,5}\)/','',$ro);
                $block1 = explode("/", $thisroute);
                #get next restricted work (controller / .php file)
                $block2 = explode("/", $co);
                if( !in_array($block1[0], $restrictedList) ){
                    $restrictedList[] = $block1[0];
                }
                if( !in_array($block2[0], $restrictedList) ){
                    $restrictedList[] = $block2[0];
                }
            }

            #Get union of controller list and restricted list
            $restrictedList = array_unique(array_merge($restrictedList , $controllerConfig));
            
            if( count($resultCount) > 0 || in_array($userslug, $restrictedList)){
                $serverResponse['error'] = "URL already in use.";
            }
            else{
                $boolResult = $this->memberpage_model->editUserSlug($memberID, $userslug);
                $serverResponse['result'] = $boolResult ? 'success':'fail';
                $serverResponse['error'] = $boolResult? '':'Failed to update database. Please try again later';
            }
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
    public function verify(){

        if($this->input->post('reverify') === 'true'){
            $uid = $this->session->userdata('member_id');
            $data = $this->register_model->get_verifcode($uid);


            if($this->input->post('field') === 'mobile' && $this->input->post('data') == $data['contactno'])
            {
                //GENERATE NEW MOBILE CONFIRMATION CODE
                $confirmation_code = $this->register_model->rand_alphanumeric(6);
                $hash = $data['emailcode'];
                $temp = array(
                    'member_id' => $uid,
                    'mobilecode' => $confirmation_code,
                    'emailcode' => $hash,
                    'mobile' => 0,
                    'email' => 0
                );

                if($data['mobilecount'] < 4 || $data['time'] > 30){
                    $result = $this->register_model->send_mobile_msg($data['username'], $data['contactno'], $confirmation_code);
                    if($result === 'success'){
                        $this->session->set_userdata('mobilecode', $confirmation_code);
                        $temp['mobile'] = 1;
                    }
                }
                else{
                    $result = 'exceed';
                }
                
                $this->register_model->store_verifcode($temp);
                echo json_encode($result);
            }
            else if($this->input->post('field') === 'email' && $this->input->post('data') == $data['email'])
            {

                //GENERATE NEW HASH FOR EMAIL VERIFICATION
                $hash = sha1($this->session->userdata('session_id').time());
                $confirmation_code = $data['mobilecode'];
                $temp = array(
                    'member_id' => $uid,
                    'mobilecode' => $confirmation_code,
                    'emailcode' => $hash,
                    'mobile' => 0,
                    'email' => 0
                );

                if($data['emailcount'] < 4 || $data['time'] > 30){
                    $result = $this->register_model->send_email_msg($data['email'], $data['username'], $hash);
                    if($result === 'success'){
                        $temp['email'] = 1;
                    }
                    $this->session->set_userdata('cart_contents', array());
                }
                else{
                    $result = 'exceed';
                }
                
                $this->register_model->store_verifcode($temp);
                echo json_encode($result);
                
            }
            else{
                echo json_encode('dataerror');
            }
        }
        else{
            echo 0;
        }
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
    
    /**
     *  Used by AJAX Requests in Memberpage Dashboard
     *  Fetches product list for Active, Deleted, and Drafted Items
     *  Includes Search, Sort, And order by functionality by parsing the query
     *  Returns json_encoded html data
     *
     *  @return JSON
     */
    public function getMoreUserItems($who = "")
    {
        $itemPerPage = 10;
        
        if($who == "vendor"){
            $activeView = 'vendor_activeproduct_view';
            $member_id = $this->input->get('mid');
        }
        else{
            $activeView = 'memberpage_activeproduct_view';
            $deletedView = 'memberpage_deletedproduct_view';
            $member_id = $this->session->userdata('member_id');
        }
        
        $deleteStatus = intval($this->input->get('s'));
        $draftStatus = intval($this->input->get('s2'));
        $start = intval($this->input->get('p'));
        $rawnf = trim((string)$this->input->get('nf'));
        $nf = '%' . $rawnf . '%'; #name_filter / searched name
        $of = (int)$this->input->get('of'); #order filter
        $osf = (int)$this->input->get('osf'); #order sequence filter
        
        switch($of){
            case 1:
            $myof = 'p.lastmodifieddate';
            break;
            case 2:
            $myof = 'p.name';
            break;
            case 3:
            $myof = 'p.price';
            break;
            case 4:
            $myof = 'availability';
            break;
            case 5:
            $myof = 'sold';
            break;
            default:
            $myof = 'p.lastmodifieddate';
        }
        
        switch($osf){
            case 1:
            $myosf = 'DESC';
            break;
            case 2:
            $myosf = 'ASC';
            break;
            default:
            $myosf = 'DESC';
        }
        
        if( $this->input->get('c') == 'count' ){
            $jsonData['count'] = 0;
            if ( $rawnf !== '' ){
                $jsonData['count'] = $this->memberpage_model->getUserItemSearchCount($member_id,$nf,$deleteStatus, $draftStatus);
            }
        }
        
        $key = $deleteStatus === 0 ? 'active_products' : 'deleted_products';
        $data[$key] = $this->memberpage_model->getUserItems($member_id, $deleteStatus, $draftStatus, $start, $nf,$myof,$myosf, $itemPerPage);
        
        $data['isBulkOptionActive'] = (int)$this->input->get('bulkoption') === 1 ? TRUE:FALSE;

        if($deleteStatus === 0){ #if active items
            $jsonData['html'] = $this->load->view('pages/user/'.$activeView, $data, true);
        }
        else if($deleteStatus === 1){
            $jsonData['html'] = $this->load->view('pages/user/'.$deletedView, $data, true);
        }
        
        echo json_encode($jsonData);
    }
    
    /**
     *  Used by AJAX Requests in Transactions page
     *  Fetches on-going and completed, buy and sold, category transactions
     *  Includes searches, filters, and order by functionality
     *  Returns json encoded html data
     *
     *  @return JSON
     */
    public function getMoreTransactions()
    {
        $itemPerPage = 10;
        
        $completeStatus = intval($this->input->get('s'));
        $k = (string)$this->input->get('k');
        $start = intval($this->input->get('p'));
        $member_id = $this->session->userdata('member_id');
        
        $rawnf = trim((string)$this->input->get('nf'));
        $nf = '%' . $rawnf . '%'; #Transaction Invoice Filter
        $of = (int)$this->input->get('of'); #Payment Filter
        $osf = (int)$this->input->get('osf'); #order sequence filter (ASC or DESC)
        
        switch($of){
            case 0:
            $myof = '1,2,3,5';
            break;
            case 1:
            $myof = '1';
            break;
            case 2:
            $myof = '2';
            break;
            case 3:
            $myof = '3';
            break;
            case 5:
            $myof = '5';
            break;
            default:
            $myof = '1,2,3,5';
        }
        
        switch($osf){
            case 1:
            $myosf = 'DESC';
            break;
            case 2:
            $myosf = 'ASC';
            break;
            default:
            $myosf = 'ASC';
        }
        
        switch($k){
            case 'buy':
            $data['transaction']['buy'] = $this->memberpage_model->getBuyTransactionDetails($this->contentXmlFile, $member_id,$completeStatus,$start,$nf,$myof,$myosf);;
            $view = 'memberpage_tx_buy_view';
            $querySelect = 'buy';
            break;
            case 'sell':                
            $data['transaction']['sell'] = $this->memberpage_model->getSellTransactionDetails($member_id,$completeStatus,$start,$nf,$myof,$myosf);;
            $view = 'memberpage_tx_sell_view';
            $querySelect = 'sell';
            break;
            case 'cbuy':
            $data['transaction']['complete']['buy'] = $this->memberpage_model->getBuyTransactionDetails($this->contentXmlFile, $member_id,$completeStatus,$start,$nf,$myof,$myosf);;
            $view = 'memberpage_tx_cbuy_view';
            $querySelect = 'buy';
            break;
            case 'csell':
            $data['transaction']['complete']['sell'] = $this->memberpage_model->getSellTransactionDetails($member_id,$completeStatus,$start,$nf,$myof,$myosf);;
            $view = 'memberpage_tx_csell_view';
            $querySelect = 'sell';
            break;
        }
        
        if( $this->input->get('c') == 'count' ){
            $jsonData['count'] = $this->memberpage_model->getFilteredTransactionCount($member_id, $completeStatus, $nf, $myof, $myosf, $querySelect);
        }
        
        $jsonData['html'] = $this->load->view('pages/user/'.$view, $data, true);
        
        echo json_encode($jsonData);
    }
    
    /**
     *  Handles details in vendorpage
     *
     *  @return JSON
     */
    public function updateVendorDetails()
    {
        $memberId = $this->session->userdata('member_id');
        $um = $this->serviceContainer['user_manager'];

        $formValidation = $this->serviceContainer['form_validation'];
        $formFactory = $this->serviceContainer['form_factory'];
        $formErrorHelper = $this->serviceContainer['form_error_helper'];

        $rules = $formValidation->getRules('personal_info');
        $form = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                    ->setMethod('POST')
                    ->add('store_name', 'text')
                    ->add('mobile', 'text', array('constraints' => $rules['mobile']))
                    ->add('city', 'text')
                    ->add('stateregion', 'text')
                    ->getForm();

        $form->submit([
            'store_name' => $this->input->post('store_name')
            , 'mobile' => $this->input->post('mobile')
            , 'city' => $this->input->post('city')
            , 'stateregion' => $this->input->post('stateregion')
        ]);

        if( $form->isValid() ){
            $formData = $form->getData();
            $validStoreName = (string)$formData['store_name'];
            $validMobile = (string)$formData['mobile'];
            $validCity = $formData['city'];
            $validStateRegion = $formData['stateregion'];

            $um->setUser($memberId)
                ->setStoreName($validStoreName)
                ->setMobile($validMobile)
                ->setMemberMisc([
                    'setLastmodifieddate' => new DateTime('now')
                ]);

            if( $validCity === "0" && $validStateRegion === "0" ){
                $um->deleteAddressTable(EasyShop\Entities\EsAddress::TYPE_DEFAULT);
            }
            else{
                $um->setAddressTable($validStateRegion, $validCity, "", EasyShop\Entities\EsAddress::TYPE_DEFAULT);
            }

            $boolResult = $um->save();

            $serverResponse = array(
                'result' => $boolResult
                , 'error' => $boolResult ? '' : $um->errorInfo()
                , 'new_data' => $boolResult ? array(
                                        "store_name" => $validStoreName
                                        , "mobile" => $validMobile
                                        , "state_region_id" => $validStateRegion
                                        , "city_id" => $validCity
                                    ) : array()
            );
        }
        else{
            $serverResponse = array(
                'result' => FALSE
                , 'error' => $formErrorHelper->getFormErrors($form)
            );
        }

        echo json_encode($serverResponse);
    }

    /**
     *  AJAX REQUEST HANDLER FOR LOADING PRODUCTS W/O FILTER
     */
    public function vendorLoadProducts()
    {
        $prodLimit = 12;
        $vendorId = $this->input->get('vendorId');
        $vendorName = $this->input->get('vendorName');
        $catId = json_decode($this->input->get('catId'), true);
        $catType = $this->input->get('catType');
        $page = $this->input->get('page');
        $rawOrderBy = intval($this->input->get('orderby'));
        $rawOrder = intval($this->input->get('order'));
        $isCount = intval($this->input->get('count')) === 1 ? TRUE : FALSE;

        $condition = $this->input->get('condition') !== "" ? $this->lang->line('product_condition')[$this->input->get('condition')] : "";
        $lprice = $this->input->get('lowerPrice') !== "" ? floatval($this->input->get('lowerPrice')) : "";
        $uprice = $this->input->get('upperPrice') !== "" ? floatval($this->input->get('upperPrice')) : "";

        $parameter = json_decode($this->input->get('queryString'),TRUE);

        $em = $this->serviceContainer["entity_manager"];
        $searchProductService = $this->serviceContainer['search_product'];
        $pm = $this->serviceContainer["product_manager"];

        switch($rawOrder){
            case 1:
                $order = "DESC";
                break;
            case 2:
                $order = "ASC";
                break;
            default:
                $order = "DESC";
                break;
        }

        switch($rawOrderBy){
            case 1:
                $orderBy = array("clickcount" => $order);
                break;
            case 2:
                $orderSearch = "NEW";
                $orderBy = array("lastmodifieddate" => $order);
                break;
            case 3:
                $orderSearch = "HOT";
                $orderBy = array("isHot"=>$order, "clickcount"=>$order);
                break;
            default:
                $orderSearch = "NULL";
                $orderBy = array("lastmodifieddate"=>$order);
                break;
        }

        switch($catType){
            case 0: // Search
                if($rawOrderBy > 1){
                    $parameter['sortby'] = $orderSearch;
                    $parameter['sorttype'] = $order;
                }
                if($condition != ""){
                    $parameter['condition'] = $condition;
                }
                if(is_numeric($lprice) && is_numeric($uprice)){
                    $parameter['startprice'] = $lprice;
                    $parameter['endprice'] = $uprice;
                }
                $parameter['seller'] = "seller:".$vendorName;
                $parameter['limit'] = $prodLimit;
                $parameter['page'] = $page - 1;
                $search = $searchProductService->getProductBySearch($parameter);
                $products = $search['collection']; 
                $productCount = $search['count'];;
                break;
            case 1: // Custom Categories
                $result = $pm->getVendorDefaultCategoryAndProducts($vendorId, $catId, "custom", $prodLimit, $page, $orderBy, $condition, $lprice, $uprice);
                $products = $result['products'];
                $productCount = $result['filtered_product_count'];
                break;
            case 2: // Default Categories
                $result = $pm->getVendorDefaultCategoryAndProducts($vendorId, $catId, "default", $prodLimit, $page, $orderBy, $condition, $lprice, $uprice);
                $products = $result['products'];
                $productCount = $result['filtered_product_count'];
                break;
            default: // Default Categories
                $result = $pm->getVendorDefaultCategoryAndProducts($vendorId, $catId, "default", $prodLimit, $page, $orderBy, $condition, $lprice, $uprice);
                $products = $result['products'];
                $productCount = $result['filtered_product_count'];
                break;
        }

        $arrCat = array(
            'page' => $page,
            'products' => $products
        );
        $parseData = array('arrCat'=>$arrCat);
        
        $pageCount = $productCount > 0 ? ceil($productCount/$prodLimit) : 1;

        $paginationData = array(
            'lastPage' => $pageCount
            , 'isHyperLink' => false
            , 'currentPage' => $page
        );
        $parseData['arrCat']['pagination'] = $this->load->view("pagination/default", $paginationData, true);
        $serverResponse = array(
            'htmlData' => $this->load->view("pages/user/display_product", $parseData, true)
            , 'isCount' => $isCount
            , 'pageCount' => $pageCount
            , 'paginationData' => $this->load->view("pagination/default", $paginationData, true)
        );

        echo json_encode($serverResponse);
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


    // new implementation starts here
    /**
     * Request for transaction details - ajax
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
                $ongoingBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId);
                $paginationData['lastPage'] = ceil($ongoingBoughtTransactionsCount / $this->transactionRowCount);
                $ongoingBoughtTransactionData = [
                    'transaction' => $this->transactionManager
                                          ->getBoughtTransactionDetails(
                                              $memberId,
                                              true,
                                              $this->transactionRowCount * $page,
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
                $ongoingSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId);
                $paginationData['lastPage'] = ceil($ongoingSoldTransactionsCount / $this->transactionRowCount);
                $ongoingSoldTransactionData = [
                    'transaction' => $this->transactionManager
                                          ->getSoldTransactionDetails(
                                              $memberId,
                                              true,
                                              $this->transactionRowCount * $page,
                                              $this->transactionRowCount,
                                             $transactionNumber,
                                              $paymentMethod
                                          ),
                    'count' => $ongoingSoldTransactionsCount,
                    'pagination' => $this->load->view('pagination/default', $paginationData, true),
                ];
                $transactionView = $this->load->view('partials/dashboard-transaction-ongoing-sold', $ongoingSoldTransactionData, true);
                break;
            case 'complete-bought':
                $completeBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId, false);
                $paginationData['lastPage'] = ceil($completeBoughtTransactionsCount / $this->transactionRowCount);
                $completeBoughtTransactionsData = [
                    'transaction' => $this->transactionManager
                                          ->getBoughtTransactionDetails(
                                              $memberId,
                                              false,
                                              $this->transactionRowCount * $page,
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
                $completeSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId, false);
                $paginationData['lastPage'] = ceil($completeSoldTransactionsCount / $this->transactionRowCount);
                $completeSoldTransactionsData = [
                    'transaction' => $this->transactionManager
                                          ->getSoldTransactionDetails(
                                              $memberId,
                                              false,
                                              $this->transactionRowCount * $page,
                                              $this->transactionRowCount,
                                              $transactionNumber,
                                              $paymentMethod
                                          ),
                    'count' => $completeSoldTransactionsCount,
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
     * Get all transaction data
     * @return mixed
     */
    private function getTransactionDetails()
    {
        $memberId = $this->session->userdata('member_id');

        $transaction = [
            'ongoing' => [
                'bought' => $this->transactionManager->getBoughtTransactionDetails($memberId, true, 0, $this->transactionRowCount),
                'sold' => $this->transactionManager->getSoldTransactionDetails($memberId, true, 0, $this->transactionRowCount)
            ],
            'complete' => [
                'bought' => $this->transactionManager->getBoughtTransactionDetails($memberId, false, 0, $this->transactionRowCount),
                'sold' => $this->transactionManager->getSoldTransactionDetails($memberId, false, 0, $this->transactionRowCount)
            ]
        ];

        $ongoingBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId);
        $paginationData['lastPage'] = ceil($ongoingBoughtTransactionsCount / $this->transactionRowCount);
        $ongoingBoughtTransactionData = [
            'transaction' => $transaction['ongoing']['bought'],
            'count' => $ongoingBoughtTransactionsCount,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];
        $ongoingBoughtTransactionView = $this->load->view('partials/dashboard-transaction-ongoing-bought', $ongoingBoughtTransactionData, true);

        $ongoingSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId);
        $paginationData['lastPage'] = ceil($ongoingSoldTransactionsCount / $this->transactionRowCount);
        $ongoingSoldTransactionData = [
            'transaction' => $transaction['ongoing']['sold'],
            'count' => $ongoingSoldTransactionsCount,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];
        $ongoingSoldTransactionView = $this->load->view('partials/dashboard-transaction-ongoing-sold', $ongoingSoldTransactionData, true);

        $completeBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId, false);
        $paginationData['lastPage'] = ceil($completeBoughtTransactionsCount / $this->transactionRowCount);
        $completeBoughtTransactionsData = [
            'transaction' => $transaction['complete']['bought'],
            'count' => $completeBoughtTransactionsCount,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];
        $completeBoughtTransactionView = $this->load->view('partials/dashboard-transaction-complete-bought', $completeBoughtTransactionsData, true);

        $completeSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId, false);
        $paginationData['lastPage'] = ceil($completeSoldTransactionsCount / $this->transactionRowCount);
        $completeSoldTransactionsData = [
            'transaction' => $transaction['complete']['sold'],
            'count' => $completeSoldTransactionsCount,
            'pagination' => $this->load->view('pagination/default', $paginationData, true),
        ];
        $completeSoldTransactionView = $this->load->view('partials/dashboard-transaction-complete-sold', $completeSoldTransactionsData, true);

        $data = [
            'ongoing' => [
                'bought' => $ongoingBoughtTransactionView,
                'sold' => $ongoingSoldTransactionView,
            ],
            'complete' => [
                'bought' => $completeBoughtTransactionView,
                'sold' => $completeSoldTransactionView,
            ]
        ];

        return $data;
    }

    /**
     * display dashboard view
     * @return view
     */
    public function newMemberpage()
    {
        $userManager = $this->serviceContainer['user_manager'];
        $productManager = $this->serviceContainer['product_manager'];

        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct');
        $esVendorSubscribeRepo = $this->em->getRepository('EasyShop\Entities\EsVendorSubscribe');
        $esMemberFeedbackRepo = $this->em->getRepository('EasyShop\Entities\EsMemberFeedback');
        $esOrderProductRepo = $this->em->getRepository('EasyShop\Entities\EsOrderProduct');
        $esAddressRepo = $this->em->getRepository('EasyShop\Entities\EsAddress');
        $esLocationLookupRepo = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');

        $headerData = $this->fill_header();
        $memberId = $this->session->userdata('member_id');
        $feedbackLimit = $this->feedbackLimit;
        $salesPerPage = $this->salesPerPage;

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                           ->find($memberId);

        if($member){

            $address = $esAddressRepo->getAddressDetails($memberId, EsAddress::TYPE_DELIVERY);
            $locationLookup = $esLocationLookupRepo->getLocationLookup(true);
            $stateRegionId = $address[0]->getCountry()->getIdLocation();
            $cityId = $address[0]->getCity()->getIdLocation();
            $consigneAddress = $address[0]->getAddress();
            $addressLatitude  = $address[0]->getLat();
            $addressLongitude = $address[0]->getLng();

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
            $userDeletedProducts = $productManager->getProductsByUser($memberId, $deleteConditions, $draftConditions); 
            $paginationData['lastPage'] = ceil($userDeletedProductCount / $productManager::PRODUCT_COUNT_DASHBOARD);
            $deletedProductsData = [
                'products' => $userDeletedProducts,
                'pagination' => $this->load->view('pagination/default', $paginationData, true),
            ];
            $deletedProductView = $this->load->view('partials/dashboard-products', $deletedProductsData, true);
            
            $deleteConditions = [EsProduct::ACTIVE];
            $draftConditions = [EsProduct::DRAFT];
            $userDraftedProductCount = $esProductRepo->getUserProductCount($memberId, $deleteConditions, $draftConditions);
            $userDraftedProducts = $productManager->getProductsByUser($memberId, $deleteConditions, $draftConditions);
            $paginationData['lastPage'] = ceil($userDraftedProductCount / $productManager::PRODUCT_COUNT_DASHBOARD);
            $draftedProductsData = [
                'products' => $userDraftedProducts,
                'pagination' => $this->load->view('pagination/default', $paginationData, true),
            ];
            $draftedProductView = $this->load->view('partials/dashboard-products', $draftedProductsData, true);
            
            $profilePercentage = $userManager->getProfileCompletePercent($member);  
            $userSoldProductCount = $esProductRepo->getUserSoldProductCount($memberId);

            $feedBackTotalCount = $esMemberFeedbackRepo->getUserTotalFeedBackCount($memberId);
            $memberRating = $esMemberFeedbackRepo->getUserFeedbackAverageRating($memberId);
            $feedbacks = $esMemberFeedbackRepo->getUserFeedbackByType($memberId,
                                                                      EsMemberFeedback::TYPE_ALL,
                                                                      $feedbackLimit);
            // add user image on each feedback
           foreach ($feedbacks as $key => $feedback) {
                $feedbacks[$key]['revieweeAvatarImage'] = $userManager->getUserImage($feedback['revieweeId'], "small");
                $feedbacks[$key]['reviewerAvatarImage'] = $userManager->getUserImage($feedback['reviewerId'], "small");
            }
            $paginationData['lastPage'] = ceil($feedBackTotalCount / $feedbackLimit);
            $feedbacksData = [
                'feedbacks' => $feedbacks,
                'memberId' => $memberId,
                'pagination' => $this->load->view('pagination/default', $paginationData, true),
            ];

            $feedBackView = $this->load->view('partials/dashboard-feedback', $feedbacksData, true);
            $allFeedBackViewData['feedBackView'] = $feedBackView;
            $allFeedBackView = $this->load->view('pages/user/dashboard/dashboard-feedbacks', $allFeedBackViewData, true);

            $currentSales = $esOrderProductRepo->getOrderProductTransaction($memberId,
                                                                            EsOrderProductStatus::FORWARD_SELLER,
                                                                            $salesPerPage);
            $currentTotalSales = $esOrderProductRepo->getSumOrderProductTransaction($memberId,
                                                                                    EsOrderProductStatus::FORWARD_SELLER);
            $currentSalesCount = $esOrderProductRepo->getCountOrderProductTransaction($memberId,
                                                                                      EsOrderProductStatus::FORWARD_SELLER);
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
            $ongoingBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId);
            $ongoingSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId);
            $completeBoughtTransactionsCount = $this->transactionManager->getBoughtTransactionCount($memberId, false);
            $completeSoldTransactionsCount = $this->transactionManager->getSoldTransactionCount($memberId, false);

            $salesView = $this->load->view('pages/user/dashboard/dashboard-sales', $salesViewData, true);            
            $member->validatedStoreName = $member->getStoreName();

            $dashboardHomeData = [
                'member' => $member,
                'avatarImage' => $userAvatarImage,
                'bannerImage' => $userBannerImage,
                'countryId' => EsLocationLookup::PHILIPPINES_LOCATION_ID,
                'stateRegionLists' => $locationLookup["stateRegionLookup"],
                'cities' => $locationLookup["json_city"],
                'consigneeAddress' => $consigneAddress,
                'address' => $address[0],
                'latitude' => $addressLatitude ,
                'longitude' => $addressLongitude,
                'consigneeStateRegionId' => $stateRegionId,
                'consigneeCityId' => $cityId,
                'followerCount' => $userFollowers['count'],
                'followingCount' => $userFollowing['count'],
                'productCount' => $userProductCount,
                'activeProductCount' => $userActiveProductCount,
                'deletedProductCount' => $userDeletedProductCount,
                'draftedProductCount' => $userDraftedProductCount,
                'soldProductCount' => $userSoldProductCount,
                'activeProductView' => $activeProductView,
                'deletedProductView' => $deletedProductView,
                'draftedProductView' => $draftedProductView,
                'memberRating' => $memberRating,
                'feedBackTotalCount' => $feedBackTotalCount,
                'profilePercentage' => $profilePercentage,
                'allFeedBackView' => $allFeedBackView,
                'salesView' => $salesView,
                'transactionInfo' => $this->getTransactionDetails(),
                'ongoingBoughtTransactionsCount' => $ongoingBoughtTransactionsCount,
                'ongoingSoldTransactionsCount' => $ongoingSoldTransactionsCount,
                'completeBoughtTransactionsCount' => $completeBoughtTransactionsCount,
                'completeSoldTransactionsCount' => $completeSoldTransactionsCount
            ];

            $dashboardHomeView = $this->load->view('pages/user/dashboard/dashboard-home', $dashboardHomeData, true);
            $dashboardData['dashboardHomeView'] = $dashboardHomeView;

            $headerData['metadescription'] = "";
            $headerData['title'] = "Dashboard | Easyshop.ph";
            $headerData['user_details'] = $this->fillUserDetails();
            $headerData['homeContent'] = $this->fillCategoryNavigation();

            $socialMediaLinks = $this->getSocialMediaLinks();
            $footerData['facebook'] = $socialMediaLinks["facebook"];
            $footerData['twitter'] = $socialMediaLinks["twitter"];

            $this->load->view('templates/header_primary', $headerData);
            $this->load->view('pages/user/dashboard/dashboard-primary',$dashboardData);
            $this->load->view('templates/footer_primary', $footerData);
        }
        else{
            redirect('/login', 'refresh');
        }
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
        $this->load->library('encrypt');
        if ((!$this->input->post('id') && !$this->input->post('password')) || $this->input->post('id') != $this->session->userdata('member_id')) {
            $result = false;
        }
        else {
            $member = $this->em
                        ->getRepository('EasyShop\Entities\EsMember')
                            ->find($this->input->post('id'));
            $doesMemberExists = $this->accountManager
                                    ->authenticateMember($member->getUsername(), $this->input->post('password'));
            if (!$member) {
                $result = false;
            }
            else if (!$doesMemberExists['member']) {
                $result = 'Incorrect Password';
            }
            else {
                $result = $this->encrypt->encode($doesMemberExists['member']->getIdMember());
                $this->load->library('parser');
                $parseData = array(
                    'username' => $member->getUsername(),
                    'hash' => $this->encrypt->encode($member->getIdMember()),
                    'site_url' => site_url('memberpage/showActivateAccount')
                );        

                $this->emailNotification = $this->serviceContainer['email_notification'];
                $message = $this->parser->parse('emails/email_deactivate_account', $parseData, true);
                $this->emailNotification->setRecipient($member->getEmail());
                $this->emailNotification->setSubject($this->lang->line('deactivate_subject'));
                $this->emailNotification->setMessage($message);
                $this->emailNotification->sendMail();
                $this->em->getRepository('EasyShop\Entities\EsMember')->accountActivation($member, false);
            }
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

        $member = $this->em->getRepository('EasyShop\Entities\EsMember')
            ->findOneBy([
                'idMember' => $getData[0],
                'isActive' => 0
            ]);

        if($this->input->get("activateAccountButton") && $member) {
            $this->em->getRepository('EasyShop\Entities\EsMember')->accountActivation($member, true);          
            $result = [
                "username" => $member->getUsername(),
                "result" => "success"
            ];
            echo json_encode($result);
        }
    }

    /**
     * Show activate account page
     */
    public function showActivateAccount()
    {

        $hashUtility = $this->serviceContainer['hash_utility'];
        $getData = $hashUtility->decode($this->input->get('h'));

        if (intval($getData[0]) === 0 || !$this->input->get('h')) {
            redirect('/login', 'refresh');
        }
        else {
             $member = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy([
                                    'idMember' => $getData[0],
                                    'isActive' => 0
                                ]);

            if (!$member) {
                redirect('/login', 'refresh');
            }
            else {
                $view = $this->input->get('view') ? $this->input->get('view') : NULL;
                $data = array(
                    'title' => 'Your Online Shopping Store in the Philippines | Easyshop.ph',
                    'metadescription' => 'Enjoy the benefits of one-stop shopping at the comforts of your own home.',
                    'relCanonical' => base_url(),
                    'username' => $member->getUsername(),
                    'idMember' => $getData[0],            
                    'hash' => $this->input->get('h')            
                );
                $data = array_merge($data, $this->fill_header());
                $socialMediaLinks = $this->getSocialMediaLinks();
                $em = $this->serviceContainer["entity_manager"];
                if($data['logged_in']){
                    $memberId = $this->session->userdata('member_id');
                    $data['logged_in'] = true;
                    $data['user_details'] = $em->getRepository("EasyShop\Entities\EsMember")
                                               ->find($memberId);
                    $data['user_details']->profileImage = ltrim($this->serviceContainer['user_manager']->getUserImage($memberId, 'small'), '/');
                }                
                $data["homeContent"] = $this->serviceContainer['xml_cms']->getHomeData(true);        
                $viewData['facebook'] = $socialMediaLinks["facebook"];
                $viewData['twitter'] = $socialMediaLinks["twitter"];

                $this->load->view('templates/header_primary', $data);
                $this->load->view('pages/user/MemberPageAccountActivate', $data);
                $this->load->view('templates/footer_primary', $viewData);                    
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
        $jsonResponse = ['isSuccessful' => 'false',
                         'errors' => []];        
                         
        if($this->input->post('storename')){
            $rules = $formValidation->getRules('store_setup');
            $formBuild = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                                     ->setMethod('POST');
            $formBuild->add('storename', 'text');
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
                $jsonResponse['isSuccessful'] = $isUpdated ? 'true' : 'false';
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
        $jsonResponse = ['isSuccessful' => 'false',
                         'errors' => []];        
                         
        if($this->input->post('storeslug')){
            $rules = $formValidation->getRules('store_setup');
            $formBuild = $formFactory->createBuilder('form', null, array('csrf_protection' => false))
                                     ->setMethod('POST');
            $formBuild->add('storeslug', 'text');
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
                $jsonResponse['isSuccessful'] = $isUpdated ? 'true' : 'false';
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
     * Gets the store settings
     *
     */
    public function getStoreSettings()
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
    

    

}

/* End of file memberpage.php */
/* Location: ./application/controllers/memberpage.php */

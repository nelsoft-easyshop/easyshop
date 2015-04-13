<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsProductItem as EsProductItem;
use EasyShop\Entities\EsLocationLookup as EsLocationLookup;
use EasyShop\Entities\EsProductShippingHead as EsProductShippingHead;
use EasyShop\Entities\EsProductShippingDetail as EsProductShippingDetail;
use Easyshop\Upload\AssetsUploader as AssetsUploader;

class productUpload extends MY_Controller 
{
    public $max_file_size_mb;
    public $img_dimension = [];
    public $maxFileSizeInMb;

    public function __construct()
    { 
        parent::__construct(); 
        $this->load->model("product_model");
        if(!$this->session->userdata('usersession') && !$this->check_cookie()){
            redirect('/login', 'refresh');
        }

        $this->max_file_size_mb = 5;
        $this->maxFileSizeInMb = ($this->max_file_size_mb * 1024) * 1024;

        /* Uploaded images dimensions: (w,h) */
        $this->img_dimension['usersize'] = array(1024,768);
        $this->img_dimension['small'] = array(400,535);
        $this->img_dimension['categoryview'] = array(220,200);
        $this->img_dimension['thumbnail'] = array(60,80);

        $this->em = $this->serviceContainer['entity_manager'];

        $this->output->set_header("HTTP/1.0 200 OK");
        $this->output->set_header("HTTP/1.1 200 OK");
        $this->output->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }


    /**
     *  Display view for selecting category for the listing
     *  of the product
     */
    public function step1()
    {
        $this->load->model("user_model");
        $uid = $this->session->userdata('member_id');
        $userdetails = $this->user_model->getUserById($uid);

        if ($this->input->post('p_id')) {
            $product_id = $data_item['product_id_edit'] = $this->input->post('p_id');
            $product = $this->product_model->getProductEdit($product_id, $uid);
            $cat_tree = $this->product_model->getParentId($product['cat_id']);
            $data_item['cat_tree_edit'] = json_encode($cat_tree);
            $data_item['other_cat_name'] = ($this->input->post('other_cat_name')) ? $this->input->post('other_cat_name') : $product['otherCategory'];
            $data_item['categoryId'] = $product['cat_id'];
        }
        else{
            if($this->input->post('step2_content')){
                $data_item['step2_content'] = $this->input->post('step2_content');
            }
            if($this->input->post('c_id')){
                $cat_id = $data_item['categoryId'] = $this->input->post('c_id');
                $other_cat_name = $this->input->post('other_cat_name');
                $cat_tree = $this->product_model->getParentId($cat_id);
                $data_item['cat_tree_edit'] = json_encode($cat_tree);
                $data_item['other_cat_name'] = $other_cat_name; 
            }
        }

        $userdetails = $this->user_model->getUserById($uid);

        $draftItems = $this->product_model->getDraftItems($uid);

        for ($i=0; $i < sizeof($draftItems); $i++) { 
            $parents = $this->product_model->getParentId($draftItems[$i]['cat_id']); # getting all the parent from selected category    
            $str_parents_to_last = "";
            $lastElement = end($parents);

            foreach($parents as $k => $v) { # creating the bread crumbs from parent category to the last selected category
                $str_parents_to_last = $str_parents_to_last  .' '. $v['name'];
                if($v != $lastElement) {
                    $str_parents_to_last = $str_parents_to_last.' &#10140;';
                }
            }

            if($draftItems[$i]['cat_other_name'] != ""){
                $str_parents_to_last = $str_parents_to_last.' &#10140; ' . $draftItems[$i]['cat_other_name'];
            }
            $draftItems[$i]['crumbs'] = $str_parents_to_last;
        } 
 
        $data_item['draftItems'] = $draftItems;

        # getting first category level from database.
        $is_admin = (intval($userdetails['is_admin']) === 1);
        $data_item['firstlevel'] = $this->product_model->getFirstLevelNode(false, false,$is_admin);

        if($this->session->userdata('usersession') && ($userdetails['is_contactno_verify'] || $userdetails['is_email_verify']) ){
            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                'title' => 'Sell Product | Easyshop.ph',
                'metadescription' => 'Take your business online by selling your items at Easyshop.ph',
                'relCanonical' => '',
                'renderSearchbar' => false, 
            ];
            $this->load->spark('decorator');    
            $this->load->view('templates/header_alt2',  $this->decorator->decorate('header', 'view', $headerData)); 
            $this->load->view('pages/product/product_upload_step1_view',$data_item);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
        }
        else{
            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                'title' => 'Verify your account to proceed | Easyshop.ph',
            ];

            $this->load->spark('decorator');    
            $this->load->view('templates/header_primary',  $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('errors/account-unverified');
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view'));  
        }

        
    }

    /**
     * Display all child category of the selected category
     * and diplay on view
     *
     * @return JSON
     */
    public function getChild() # this function for getting the under category from selected category 
    {
        header('Content-Type: application/json');
        $this->load->model('user_model');
        $id = $response['cat_id'] = $this->input->post('cat_id');     
  
        $uid =  $this->session->userdata('member_id');
        $is_admin = false;

        if($uid){
            $user_access_level = $this->user_model->getUserById($uid);
            $is_admin = (intval($user_access_level['is_admin']) === 1);
        }

        $response['node'] = $this->product_model->getDownLevelNode($id, $is_admin); 
        $response['level'] = $this->input->post('level');
        $data['html'] = $this->load->view('pages/product/product_upload_step1_view2',$response,TRUE);
        die(json_encode($data));
    }

    /**
     *  Display form and data needed for this step 
     *  create temporary directory for the storing of the uploaded images
     */
    public function step2()
    { 
        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Sell Product | Easyshop.ph',
            'metadescription' => 'Take your business online by selling your items at Easyshop.ph',
            'relCanonical' => '',
            'renderSearchbar' => false, 
        ];  
        if($this->input->post('hidden_attribute')){ # if no item selected cant go to the link. it will redirect to step 1
            $this->load->model("user_model");
            $id = $this->input->post('hidden_attribute'); 
            $response['memid'] = $this->session->userdata('member_id');
            $userdetails = $this->user_model->getUserById($response['memid']);
            $is_admin = (intval($userdetails['is_admin']) === 1);

            $this->config->load('protected_category', TRUE);
            $protected_categories = $this->config->config['protected_category'];

            if( !is_numeric($id) ||  (!$is_admin && in_array($id, $protected_categories))){
                redirect('/sell/step1/', 'refresh');
            }

            $otherCategory = html_escape($this->input->post('othernamecategory'));
            $parents = $this->product_model->getParentId($id); # getting all the parent from selected category 
            $attribute = $this->product_model->getAttributesByParent($parents);
            $str_parents_to_last = '';
            $attributeArray = array();

            if($id == 1){
                $str_parents_to_last = $otherCategory; 
            }else{
                $lastElement = end($parents);
                 
                foreach($parents as $k => $v) { # creating the bread crumbs from parent category to the last selected category
                    $str_parents_to_last = $str_parents_to_last  .' '. $v['name'];
                     
                    if($v != $lastElement) {
                        $str_parents_to_last = $str_parents_to_last.' &#10140;';
                    }
                }

                if(!$otherCategory == ""){
                    $str_parents_to_last = $str_parents_to_last.' &#10140; ' . $otherCategory;
                }

            }   

            $response['parent_to_last'] = $str_parents_to_last;
 
            foreach ($attribute as $key => $value) { 
                $attrName = ucfirst(strtolower($value['cat_name']));
                $attributeArray[$attrName] = array(); 
                
                $data = $this->product_model->getLookItemListById($value['attr_lookuplist_id']) ;
                foreach ($data as $key2 => $value2) {
                    array_push($attributeArray[$attrName], $value2['name']);
                }
            } 

            $response['tempId'] = strtolower(substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,50 ) ,1 ) .substr( md5( time() ), 1));
            $response['attributeArray'] = $attributeArray;
            $response['id'] = $id; # id is the selected category
            $response['otherCategory'] = $otherCategory; # id is the selected category
            $response['sell'] = true;
            $response['img_max_dimension'] = $this->img_dimension['usersize'];
            $response['maxImageSize'] = $this->maxFileSizeInMb;

            $date = date("Ymd");

            $tempDirectory =  'assets/temp_product/'. $response['tempId'].'_'.$response['memid'].'_'.$date.'/';
            $response['tempdirectory'] = $tempDirectory;
            $this->session->set_userdata('tempId', $response['tempId']);
            $this->session->set_userdata('tempDirectory',  $tempDirectory);

            mkdir($tempDirectory, 0777, true);
            mkdir($tempDirectory.'categoryview/', 0777, true);
            mkdir($tempDirectory.'small/', 0777, true);
            mkdir($tempDirectory.'thumbnail/', 0777, true);
            mkdir($tempDirectory.'other/', 0777, true);
            mkdir($tempDirectory.'other/categoryview/', 0777, true);
            mkdir($tempDirectory.'other/small/', 0777, true);
            mkdir($tempDirectory.'other/thumbnail/', 0777, true);

            if($this->input->post('step1_content')){
                $response['step1_content'] = $this->input->post('step1_content');
            }
            
            $this->load->spark('decorator');    
            $this->load->view('templates/header_alt2',  $this->decorator->decorate('header', 'view', $headerData)); 
            $this->load->view('pages/product/product_upload_step2_view',$response);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
        }else{
            redirect('/sell/step1/', 'refresh');
        }
    }

    /**
     *  Display item details of the selected
     *  product to be modify
     */
    public function step2edit()
    {
        $stringUtility = $this->serviceContainer['string_utility'];
        if($this->input->post('p_id')){
            $product_id = $response['p_id'] = $this->input->post('p_id');
        }
        else{
            redirect('me', 'refresh');
        }

        $response['tempId'] = $tempId = strtolower(substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,mt_rand( 0 ,50 ) ,1 ) .substr( md5( time() ), 1));
        $response['memid'] = $member_id = $this->session->userdata('member_id');
        $dir    = './assets/product/';

        if(!glob($dir."{$product_id}_{$member_id}*", GLOB_BRACE)){
            $date = date("Ymd");
            $tempDirectory = './assets/product/'. $product_id.'_'.$member_id.'_'.$date.'/';
            mkdir($tempDirectory, 0777, true);
        }

        $path = glob($dir."{$product_id}_{$member_id}*", GLOB_BRACE)[0].'/'; 
        $product = $this->product_model->getProductEdit($product_id, $member_id);
        $response['id'] = $cat_id = ($this->input->post('hidden_attribute')) ? $this->input->post('hidden_attribute') : $product['cat_id'];  

        if($product['brand_other_name'] === '' 
            && $product['brand_id'] == 1){
            $product['brandname'] = '';
        }
        elseif($product['brand_other_name'] === '' 
            && $product['brand_id'] != 1){
            $product['brandname'] = $this->product_model->getBrandById($product['brand_id'])[0]['name'];
        }
        else{
            $product['brandname'] = $product['brand_other_name'];
        }

        // Loading Categories breadcrumbs
        $otherCategory  = $response['otherCategory'] = html_escape($this->input->post('othernamecategory'));
        $breadcrumbs = '';
        $parents = $this->product_model->getParentId($cat_id); 

        if($cat_id == 1){
            $breadcrumbs = $otherCategory;
        }
        else{
            $lastElement = end($parents);
            foreach($parents as $k => $v) {
                $breadcrumbs .= ($v != $lastElement) ? $v['name'].' &#10140; ' : $v['name'];
            }

            if(!$otherCategory == ""){
                $breadcrumbs = $breadcrumbs.' &#10140; ' . $otherCategory;
            }
        }  
        // Loading images
        $images = $this->product_model->getProductImages($product_id);
        $mainImages = $arrayNameOnly = array();  
        foreach($images as $imagekey => $imagevalue){
            if(strpos(($imagevalue['path']),'other') === FALSE){
                $file = explode('_',$imagevalue['file']);
                unset($file[0]);
                $tempFile =  $tempId.'_'.implode('_', $file);  
                $imagevalue['temp'] = $tempFile;
                $imagevalue['type'] = end(explode('.', end($file)));
                array_push($mainImages, $imagevalue);
            }
            array_push($arrayNameOnly, $imagevalue['file']);
        }

        $response['main_images'] = $mainImages;

        // Loading attributes
        $attribute = $this->product_model->getAttributesByParent($parents); 
        $attributeArray = $eachAttribute = $soloAttribute = array();

        foreach ($attribute as $key => $value) { 
            $attrName = ucfirst(strtolower($value['cat_name']));
            $attributeArray[$attrName] = array(); 
            $data = $this->product_model->getLookItemListById($value['attr_lookuplist_id']) ;
            foreach ($data as $key2 => $value2) {
                array_push($attributeArray[$attrName], $value2['name']);
            }
        } 

        foreach ($this->product_model->getProductAttributes($product_id, 'NAME') as $key => $value) {
            $key =  ucfirst(strtolower(str_replace("'", "", $key)));
            if (!array_key_exists($key, $attributeArray)) {
                $attributeArray[$key] = array();
            }

            foreach ($value as $key1 => $value1) {
                if($value1['datatype'] == 0 || $value1['datatype'] == 5){
                    if (!array_key_exists($value1['value'], $attributeArray[$key])) {
                        array_push($attributeArray[$key], $value1['value']);
                    }
                    $eachAttribute[$key] = $this->product_model->getProductAttributesByHead($product_id,$key);

                    foreach ($eachAttribute[$key] as $key2 => $value2) {
                        $eachAttribute[$key][$key2]['file_path'] = "";
                        if(!$eachAttribute[$key][$key2]['img_path'] == ''){
                            $explodePath = explode('/', $eachAttribute[$key][$key2]['img_path']);
                            $fileName = end($explodePath);
                            $file = explode('_', $fileName);
                            array_shift($file); 
                            array_pop($explodePath);
                            $categoryPath = implode('/', $explodePath) . '/categoryview/'.$fileName; 
                            $eachAttribute[$key][$key2]['file_path'] = getAssetsDomain().$categoryPath;
                            $eachAttribute[$key][$key2]['img_path'] = $tempId.'_'.implode('_', $file); 
                        }
                    }
                }
                else{
                    $soloAttribute[$key] = $value1['value'];
                }
            }
        } 
 
        $response['soloAttribute'] =  $soloAttribute;
        $response['eachAttribute'] =  $eachAttribute;
        
        // Loading Combinations
        $newItemQuantityArray = array();
        $itemQuantity =  $this->product_model->getProductQuantity($product_id, true);
    
        foreach($itemQuantity as $keyid => $value){
            if(count($value['product_attribute_ids'])===1){
                if(($value['product_attribute_ids'][0]['id'] == 0)&&($value['product_attribute_ids'][0]['is_other'] == 0)){
                    $response['noCombination'] = true;
                    $response['noCombinationQuantity'] = $value['quantity'];
                }
            }
            $itemAttrData = $this->product_model->getItemAttributes($keyid);
            $newData = array();
            foreach ($itemAttrData as $keyy => $valuee) {
                $head = ucfirst(strtolower(str_replace("'", "", $valuee['head'])));
                $newData[$head] = $valuee['value'];
            }
            $newItemQuantityArray[$keyid] = array("quantity" => $value['quantity'],"data" => $newData);
        }

        $response['itemQuantity'] = $newItemQuantityArray;
        $response['attributeArray'] = $attributeArray;
        $response['parent_to_last'] = $breadcrumbs;
        $response['product_details'] = $product;
        $response['cleanDescription'] = isset($product['description']) 
                                        ? $stringUtility->purifyHTML($product['description'])
                                        : "";
        $response['is_edit'] = 'is_edit';
        $response['img_max_dimension'] = $this->img_dimension['usersize'];
        $response['maxImageSize'] = $this->maxFileSizeInMb;
        $date = end(explode('_', explode('/', $path)[3]));
 
        $tempdirectory = $tempId.'_'.$member_id.'_'.$date;
        $tempdirectory = $response['tempdirectory'] = './assets/temp_product/'.$tempdirectory.'/';

        $this->session->set_userdata('tempId', $response['tempId']); 
        $this->session->set_userdata('tempDirectory',  $tempdirectory);
        $this->session->set_userdata('originalPath',  $path);

        mkdir($tempdirectory, 0777, true);
        mkdir($tempdirectory.'categoryview/', 0777, true);
        mkdir($tempdirectory.'small/', 0777, true);
        mkdir($tempdirectory.'thumbnail/', 0777, true);
        mkdir($tempdirectory.'other/', 0777, true);
        if (!file_exists ($tempdirectory.'other/categoryview')){
            mkdir($tempdirectory.'other/categoryview/', 0777, true);
        }
        if (!file_exists ($tempdirectory.'other/small')){
            mkdir($tempdirectory.'other/small/', 0777, true);
        }
        if (!file_exists ($tempdirectory.'other/thumbnail')){
            mkdir($tempdirectory.'other/thumbnail/', 0777, true);
        }

        $headerData = [
            "memberId" => $this->session->userdata('member_id'),
            'title' => 'Edit Product | Easyshop.ph',
            'metadescription' => 'Take your business online by selling your items at Easyshop.ph',
            'relCanonical' => '',
            'renderSearchbar' => false, 
        ]; 
        $this->load->spark('decorator');    
        $this->load->view('templates/header_alt2',  $this->decorator->decorate('header', 'view', $headerData));  
        $this->load->view('pages/product/product_upload_step2_view',$response);
        $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
    }

    /**
     *  Upload image for attributes of the product
     *  
     *  @return JSON
     */
    public function fallBackUploadimage()
    {
        $imageUtility = $this->serviceContainer['image_utility'];
        $assetsUploader = $this->serviceContainer["assets_uploader"]; 
        $this->config->load('image_dimensions', true);
        $imageDimensions = $this->config->config['image_dimensions'];
        $tempDirectory = $this->session->userdata('tempDirectory');  
        $pathDirectory = $tempDirectory;
        $tempId = $this->session->userdata('tempId');
        $member_id = $this->session->userdata('member_id');
        if($this->input->post('pictureName')){
            $filename = trim($this->input->post('pictureName'));
            $inputFile = "files";
            $filenameArray = [$filename];

            if($_FILES[$inputFile]['error'][0] !== UPLOAD_ERR_OK
                || $_FILES[$inputFile]['size'][0] >= $this->maxFileSizeInMb){
                die('{"result":"false","msg":"Please select valid image type. Allowed type: .PNG,.JPEG,.GIF Allowed max size: 5mb. Allowed max dimension 5000px","err":"1"}');
            }
        }
        else{
            $filenameArray = $filename = trim($this->input->post('pictureNameOther'));
            $inputFile = "attr-image-input";
            $pathDirectory = $tempDirectory.'/other/';

            if($_FILES[$inputFile]['error'] !== UPLOAD_ERR_OK
                || $_FILES[$inputFile]['size'] >= $this->maxFileSizeInMb){
                die('{"result":"false","msg":"Please select valid image type. Allowed type: .PNG,.JPEG,.GIF Allowed max size: 5mb. Allowed max dimension 5000px","err":"1"}');
            }
        }

        if (strpos($filename, $tempId."_".$member_id) === false) {
            die('{"result":"false","msg":"Invalid filename. Please try again later.","err":"1"}');
        } 

        if (!file_exists ($pathDirectory)){
            mkdir($pathDirectory, 0777, true);
        }

        $this->upload->initialize([
            "upload_path" => $pathDirectory,
            "overwrite" => false,
            "file_name"=> $filenameArray,
            "encrypt_name" => false,
            "remove_spaces" => true,
            "allowed_types" => AssetsUploader::ALLOWABLE_IMAGE_MIME_TYPES,
            "max_size" => AssetsUploader::MAX_ALLOWABLE_SIZE_KB,
            "max_width" => AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX,
            "max_height" => AssetsUploader::MAX_ALLOWABLE_DIMENSION_PX,
            "xss_clean" => false
        ]); 

        if ($this->upload->do_multi_upload($inputFile)){
            $imageUtility->imageResize($pathDirectory.$filename, 
                                       $pathDirectory."small/".$filename,
                                       $imageDimensions["productImagesSizes"]["small"]);

            $imageUtility->imageResize($pathDirectory."small/".$filename, 
                                       $pathDirectory."categoryview/".$filename,
                                       $imageDimensions["productImagesSizes"]["categoryview"]);

            $imageUtility->imageResize($pathDirectory."categoryview/".$filename, 
                                       $pathDirectory."thumbnail/".$filename,
                                       $imageDimensions["productImagesSizes"]["thumbnail"]);

            die('{"result":"ok","imageName":"'.$filename.'","err":"0"}'); 
        }
        else{
            die('{"result":"false","msg":"'.$this->upload->display_errors().'","err":"1"}');
        }
    }

    /**
     *  Upload image for primary and other
     *  alternative of the image of the product
     *
     *  @return JSON
     */
    public function uploadimage()
    {  
        $imageUtility = $this->serviceContainer['image_utility'];
        $pathDirectory = $this->session->userdata('tempDirectory');
        $tempId = $this->session->userdata('tempId');
        $member_id = $this->session->userdata('member_id');
        $filescnttxt = $this->input->post('filescnttxt'); 
        $imageCollections = json_decode($this->input->post('imageCollections'));
        $afstartArray = json_decode(trim($this->input->post('afstart'))); 
        $filenames_ar = [];
        $text = ""; 
        $error = 0; 

        $this->config->load('image_dimensions', true);
        $imageDimensions = $this->config->config['image_dimensions']; 
        $assetsUploader = $this->serviceContainer["assets_uploader"]; 
        foreach($_FILES['files']['name'] as $key => $value ) { 
            $filenames_ar[$key] = $afstartArray[$key];
            if($_FILES['files']['size'][$key] >= $this->maxFileSizeInMb
               || $_FILES['files']['error'][$key] !== UPLOAD_ERR_OK
               || !$assetsUploader->checkValidFileType($_FILES['files']['tmp_name'][$key])
               || !$assetsUploader->checkValidFileDimension($_FILES['files']['tmp_name'][$key])){
                unset($filenames_ar[$key]);
            }

            if(isset($filenames_ar[$key])){
                if (strpos($filenames_ar[$key], $tempId."_".$member_id) === false) {
                   $return = [
                        'msg' => "Invalid filename. Please try again later.", 
                        'fcnt' => $filescnttxt,
                        'err' => 1
                    ];

                    die(json_encode($return));
                }
            }
        }

        $filenames_ar = array_values($filenames_ar);  
        if(count($filenames_ar) <= 0){
            $return = [
                'msg' => "Please select valid image type.\nAllowed type: .PNG,.JPEG,.GIF\nAllowed max size: 5mb. Allowed max dimension 5000px", 
                'fcnt' => $filescnttxt,
                'err' => 1
            ];

            die(json_encode($return));
        }

        if (!file_exists ($pathDirectory)){
            mkdir($pathDirectory, 0777, true);
        }
        foreach ($imageCollections as $key => $base64String) { 
            $img = str_replace('data:image/jpeg;base64,', '', $base64String);
            $fileNameArray = explode(".", $filenames_ar[$key]);
            array_pop($fileNameArray);
            $filename = implode(".", $fileNameArray).".jpeg";
            $tempFilePath = $pathDirectory.$filename;
            $imageData = base64_decode($img);
 
            if((bool)getimagesizefromstring($imageData) 
                && $assetsUploader->checkValidFileType($base64String)){
                file_put_contents($tempFilePath, $imageData);
                $imageUtility->imageResize($pathDirectory.$filename, 
                                           $pathDirectory."small/".$filename,
                                           $imageDimensions["productImagesSizes"]["small"]);

                $imageUtility->imageResize($pathDirectory."small/".$filename, 
                                           $pathDirectory."categoryview/".$filename,
                                           $imageDimensions["productImagesSizes"]["categoryview"]);

                $imageUtility->imageResize($pathDirectory."categoryview/".$filename, 
                                           $pathDirectory."thumbnail/".$filename,
                                           $imageDimensions["productImagesSizes"]["thumbnail"]);
            }
        }

        $return = [
            'msg' => $text, 
            'fcnt' => $filescnttxt,
            'err' => $error
        ];

        die(json_encode($return));
    }

    /**
     *  Upload image for attributes of the product
     *  
     *  @return JSON
     */
    public function uploadimageOther()
    {
        $imageUtility = $this->serviceContainer['image_utility'];
        $this->config->load('image_dimensions', true);
        $imageDimensions = $this->config->config['image_dimensions'];
        $tempDirectory = $this->session->userdata('tempDirectory'); 
        $filename = trim($this->input->post('pictureNameOther'));
        $tempId = $this->session->userdata('tempId');
        $member_id = $this->session->userdata('member_id');
        $imageCollections = json_decode($this->input->post('imageCollections'))[0];
        $assetsUploader = $this->serviceContainer["assets_uploader"]; 
        $fileNameArray = explode('.', $filename); 
        array_pop($fileNameArray);
        $filename = implode(".", $fileNameArray).".jpeg";

        if(!$assetsUploader->checkValidFileType($_FILES['attr-image-input']['tmp_name'])
            || !$assetsUploader->checkValidFileDimension($_FILES['attr-image-input']['tmp_name'])
            || $_FILES['attr-image-input']['error'] !== UPLOAD_ERR_OK
            || $_FILES['attr-image-input']['size'] >= $this->maxFileSizeInMb){
            die('{"result":"false","msg":"Please select valid image type. Allowed type: .PNG,.JPEG,.GIF Allowed max size: 5mb. Allowed max dimension 5000px"}');
        }

        if (strpos($filename, $tempId."_".$member_id) === false) { 
            die('{"result":"false","msg":"Invalid filename. Please try again later."}');
        } 

        $pathDirectory = $tempDirectory.'/other/';

        if (!file_exists ($pathDirectory)){
            mkdir($pathDirectory, 0777, true);
        } 

        $img = str_replace('data:image/jpeg;base64,', '', $imageCollections);
        $tempFilePath = $pathDirectory.$filename;
        $imageFile = base64_decode($img);
        if($assetsUploader->checkValidFileType($imageCollections) 
            && (bool)getimagesizefromstring($imageFile)){
            file_put_contents($tempFilePath, $imageFile);
            $imageUtility->imageResize($pathDirectory.$filename, 
                                       $pathDirectory."small/".$filename,
                                       $imageDimensions["productImagesSizes"]["small"]);

            $imageUtility->imageResize($pathDirectory."small/".$filename, 
                                       $pathDirectory."categoryview/".$filename,
                                       $imageDimensions["productImagesSizes"]["categoryview"]);

            $imageUtility->imageResize($pathDirectory."categoryview/".$filename, 
                                       $pathDirectory."thumbnail/".$filename,
                                       $imageDimensions["productImagesSizes"]["thumbnail"]);

            echo '{"result":"ok","imageName":"'.$filename.'"}';
        }
    }

    /**
     * Process and validate user's inputted data into form
     * insert into database
     * proceed to step3
     *
     * @return JSON 
     */
    public function step2_2() # function for processing the adding of new item
    {
        $stringUtility = $this->serviceContainer['string_utility'];
        $this->load->model("user_model");
        $combination = json_decode($this->input->post('combination'),true); 
        $attributes = json_decode($this->input->post('attributes'),true);
        $data = $this->input->post('data');
        $cat_id = $this->input->post('id');
        $postCategory= $stringUtility->removeSpecialCharsExceptSpace($this->input->post('otherCategory'));
        $otherCategory = trim($stringUtility->removeNonUTF($postCategory));
        $brand_id =  $stringUtility->removeNonUTF($this->input->post('prod_brand')); 
        $brand_valid = false;
        $otherBrand = '';
        $product_title = trim($stringUtility->removeNonUTF($this->input->post('prod_title')));
        $product_brief = trim($stringUtility->removeNonUTF($this->input->post('prod_brief_desc')));
        $product_description =  substr(trim($this->input->post('prod_description')), 0, 65000);
        $product_price = ($this->input->post('prod_price') == "")? '0' : str_replace(',', '', $this->input->post('prod_price'));
        $product_discount = ($this->input->post('discount'))?floatval($this->input->post('discount')):0;
        $product_discount = ($product_discount <= 100)?$product_discount:100;
        $discountedPrice = bcsub($product_price, bcmul($product_price, bcdiv($product_discount, 100, 4), 4));
        $product_condition = $stringUtility->removeNonUTF($this->input->post('prod_condition'));
        $sku = trim($stringUtility->removeNonUTF($this->input->post('prod_sku')));
        $keyword = trim($stringUtility->removeNonUTF($this->input->post('prod_keyword')));
        $style_id = 1;
        $member_id =  $this->session->userdata('member_id');
        $tempDirectory = $this->session->userdata('tempDirectory');
        $date = date("Ymd");
        $isNotSavingAsDraft = $this->input->post('savedraft') ? false : true;

        if(intval($brand_id,10) == 1){
            $brand_valid = true;
            $otherBrand = $stringUtility->removeNonUTF($this->input->post('brand_sch'));
            $brand_id = 1;
        }
        else{
            if($this->product_model->getBrandById($brand_id)){
                $brand_valid = true;
            }
        }

        if($brand_valid === FALSE){ 
            $brand_id = 1;
            $otherBrand = '';
        }

        $currentCombination = [];
        foreach ($combination as $value) {
            $combinationValue = implode("", array_map('strtolower', $value['data']));
            if(!in_array($combinationValue, $currentCombination)){
                $uniqueCombination[] = $value;
            }
            $currentCombination[] = $combinationValue;
        }
        if($isNotSavingAsDraft){
            if (!in_array($product_condition, $this->lang->line('product_condition'))){
                die('{"e":"0","d":"Condition selected not available. Please select another."}');
            }

            if(count($currentCombination) !== count(array_unique($currentCombination))){
                die('{"e":"0","d":"Same combination is not allowed!"}');
            }
        }
        else{
            $product_condition = $this->lang->line('product_condition')[0];
            $combination = $uniqueCombination; 
        }

        if((strlen(trim($product_title)) == 0 
            || $product_title == "" 
            || strlen(trim($product_price)) == 0  
            || strlen(trim($product_description)) == 0) && $isNotSavingAsDraft){

            die('{"e":"0","d":"Fill (*) All Required Fields Properly!"}');      
        }
        else{ 
            if((int) $product_price <= 0 || (int) $discountedPrice <= 0){
                die('{"e":"0","d":"Invalid price. Price must be equal or greater than P1."}');
            }

            $arraynameoffiles = json_decode($this->input->post('arraynameoffiles')); 
            $arraynameoffiles = (count($arraynameoffiles) > 0) ? $arraynameoffiles : array();
            if($isNotSavingAsDraft){
                if(count($arraynameoffiles) <= 0){ 
                    die('{"e":"0","d":"Please select at least one photo for your listing."}');
                }

                if(strlen(trim($product_title)) < EsProduct::MINIMUM_PRODUCT_NAME_LEN){ 
                    die('{"e":"0","d":"Product name must be atleast '.EsProduct::MINIMUM_PRODUCT_NAME_LEN.' characters!"}');
                }
            }

            $removeThisPictures = json_decode($this->input->post('removeThisPictures')); 
            $primaryId = $this->input->post('primaryPicture');
            $primaryName =""; 
            $arrayNameOnly = array();

            foreach($arraynameoffiles as $key => $value ) {

                $explodearraynameoffiles = explode('||', $value);
                $nameOfFile = $explodearraynameoffiles[0];
                $arrayNameOnly[$key] = strtolower($nameOfFile); 
                if($primaryId == $key){
                    $primaryName = strtolower($nameOfFile); 
                }

                if (in_array($key, $removeThisPictures) || $arraynameoffiles[$key] == "") {
                    unset($arraynameoffiles[$key]);
                    unset($arrayNameOnly[$key]);
                } 
            }

            $arraynameoffiles = array_values($arraynameoffiles);
            $arrayNameOnly = array_values($arrayNameOnly); 
            $key = array_search ($primaryName, $arrayNameOnly);
            if(!count($arraynameoffiles) <= 0){
                $temp = $arraynameoffiles[0];
                $arraynameoffiles[0] = $arraynameoffiles[$key];
                $arraynameoffiles[$key] = $temp;

                $temp = $arrayNameOnly[0];
                $arrayNameOnly[0] = $arrayNameOnly[$key];
                $arrayNameOnly[$key] = $temp;
            }

            if($isNotSavingAsDraft){
                if(count($arraynameoffiles) <= 0){ 
                    die('{"e":"0","d":"Please select at least one photo for your listing."}');
                }
            }

            $allowed =  array('gif','png' ,'jpg','jpeg'); # available format only for image 

            $categoryDetails = $this->product_model->selectCategoryDetails($cat_id);
            $categoryName =  $categoryDetails['name'];
            $categorykeywords =  $categoryDetails['keywords']; 

            $brandName = ($otherBrand === '')?$this->product_model->getBrandById($brand_id)[0]['name']:$otherBrand;
            $username = $this->user_model->getUserById($member_id)['username'];

            $search_keyword = preg_replace('!\s+!', ' ',$brandName .' '. $product_title .' '. $otherCategory . ' ' . $categoryName . ' '. $categorykeywords . ' '.$keyword.' '.$username);

            $product_id = $this->product_model->addNewProduct($product_title,$sku,$product_brief,$product_description,$keyword,$brand_id,$cat_id,$style_id,$member_id,$product_price,$product_discount,$product_condition,$otherCategory, $otherBrand,$search_keyword);
            # product_id = is the id_product for the new item. if 0 no new item added process will stop
            
            #image directory
            $path_directory = './assets/product/'.$product_id.'_'.$member_id.'_'.$date.'/';
            $other_path_directory = $path_directory.'other/';

            # creating new directory for each product
            if(!mkdir($path_directory)) {  
                die('{"e":"0","d":"There was a problem. \n Please try again! - Error[0010]"}'); 
            }
            if(!mkdir($other_path_directory)) { 
                die('{"e":"0","d":"There was a problem. \n Please try again! - Error[0012]"}');
            }
            if(!mkdir($path_directory.'categoryview/')) { 
                die('{"e":"0","d":"There was a problem. \n Please try again! - Error[0012]"}');
            }
            if(!mkdir($path_directory.'small/')) { 
                die('{"e":"0","d":"There was a problem. \n Please try again! - Error[0012]"}');
            }
            if(!mkdir($path_directory.'thumbnail/')) { 
                die('{"e":"0","d":"There was a problem. \n Please try again! - Error[0012]"}');
            }

            if($product_id > 0){ # id_product is 0 means no item inserted. the process will stop.

                foreach ($arraynameoffiles as $key => $value) {
                    $explodearraynameoffiles = explode('||', $arraynameoffiles[$key]);
                    $nameOfFile = $explodearraynameoffiles[0];
                    $nameOfFileArray = explode('_', $nameOfFile);
                    unset($nameOfFileArray[0]);
                    $newName =  $product_id.'_'.implode('_', $nameOfFileArray);
                    $path = $path_directory.$newName;
                    $typeOfFile = $explodearraynameoffiles[1];
                    $is_primary = ($key == 0 ? 1 : 0);
                    $product_image = $this->product_model->addNewProductImage($path,$typeOfFile,$product_id,$is_primary);
                }

                # start of saving other/custom attribute 
                foreach ($attributes as $key => $valuex) {
                    $attrName = $stringUtility->removeNonUTF($key);
                    $attrName = $attrName === "" ? "no name" : $attrName;
                    $others_id = $this->product_model->addNewAttributeByProduct_others_name($product_id,$attrName);
                    foreach ($valuex as $keyvalue => $value) {
                        $imageid = 0;
                        $attributeValue = $stringUtility->removeNonUTF($value['value']);
                        $attributeValue = $attributeValue === "" ? "no value" : $attributeValue;
                        if($value['image'] != ""){ 
                            $nameOfFileArray = explode('_', $value['image']);
                            $fileType = end(explode('.', $value['image']));
                            unset($nameOfFileArray[0]);
                            $newOtherName =  $product_id.'_'.implode('_', $nameOfFileArray);
                            $arrayNameOnly[] = $value['image'];
                            $imageid = $this->product_model->addNewProductImage($other_path_directory.$newOtherName,$fileType,$product_id,0);
                        }
                        $this->product_model->addNewAttributeByProduct_others_name_value($others_id,$attributeValue,$value['price'],$imageid);
                    }
                }
                #end of other 
 
                if(!count($arraynameoffiles) <= 0){ 
                    $this->serviceContainer["assets_uploader"]->uploadImageDirectory($tempDirectory, $path_directory, $product_id, $arrayNameOnly);
                }

                #saving combination
                if(count($combination) <= 0){
                    $allQuantity = (int) $this->input->post('allQuantity') > EsProductItem::MAX_QUANTITY 
                                   ? EsProductItem::MAX_QUANTITY
                                   : (int) $this->input->post('allQuantity');
                    $idProductItem = $this->product_model->addNewCombination($product_id, $allQuantity);
                }
                else{
                    foreach ($combination as $key => $value) {
                        $combQuantity = (int) $value['quantity'] > EsProductItem::MAX_QUANTITY
                                        ? EsProductItem::MAX_QUANTITY
                                        : (int) $value['quantity'];
                        $idProductItem = $this->product_model->addNewCombination($product_id, $combQuantity);
                        foreach ($value['data'] as $keydata => $valuedata) {
                            $productAttributeId = $this->product_model->selectProductAttributeOther($keydata,$valuedata,$product_id);
                            $this->product_model->addNewCombinationAttribute($idProductItem,$productAttributeId,1);
                        }
                    }
                }
                #end of saving combination

                //update search keywords
                $productManager = $this->serviceContainer['product_manager'];
                $productManager->generateSearchKeywords($product_id);

                $this->session->set_userdata('originalPath',  $path_directory);
                die('{"e":"1","d":"'.$product_id.'"}'); 
            }else{
                die('{"e":"0","d":"There was a problem. \n Please try again later! - Error[0011]"}');
            }
        }
    }

    /**
     * Process and validate user's inputted data into form
     * update the database
     * proceed to step3
     *
     * @return JSON 
     */
    public function step2editSubmit()
    {
        $stringUtility = $this->serviceContainer['string_utility'];

        $this->load->model("user_model");
        $combination = json_decode($this->input->post('combination'),true); 
        $attributes = json_decode($this->input->post('attributes'),true);
        $product_id = $this->input->post('p_id');
        $memberId = $this->session->userdata('member_id');
        $cat_id = $this->input->post('id');
        $product_title = trim($stringUtility->removeNonUTF($this->input->post('prod_title')));
        $product_brief = trim($stringUtility->removeNonUTF($this->input->post('prod_brief_desc')));
        $product_description = substr(trim($this->input->post('prod_description')), 0, 65000);
        $product_price = ($this->input->post('prod_price') == "")? '0' : str_replace(',', '', $this->input->post('prod_price'));
        $product_discount = ($this->input->post('discount'))?floatval($this->input->post('discount')):0;
        $product_discount = ($product_discount <= 100)?$product_discount:100;
        $discountedPrice = bcsub($product_price, bcmul($product_price, bcdiv($product_discount, 100, 4), 4));
        $product_condition = $stringUtility->removeNonUTF($this->input->post('prod_condition'));
        $postCategory= $stringUtility->removeSpecialCharsExceptSpace($this->input->post('otherCategory'));
        $otherCategory = trim($stringUtility->removeNonUTF($postCategory));
        $sku = trim($stringUtility->removeNonUTF($this->input->post('prod_sku')));
        $brand_id =  $stringUtility->removeNonUTF($this->input->post('prod_brand')); 
        $keyword = trim($stringUtility->removeNonUTF($this->input->post('prod_keyword')));
        $style_id = 1;
        $brand_valid = false;
        $otherBrand = "";
        $primaryName = "";
        $username = $this->user_model->getUserById($memberId)['username'];
        $dir = './assets/product/'; 
        $originalPath = $path = glob($dir."{$product_id}_{$memberId}*", GLOB_BRACE)[0].'/';
        $tempDirectory = $this->session->userdata('tempDirectory');
        $isNotSavingAsDraft = $this->input->post('savedraft') ? false : true;

        $currentCombination = [];
        $uniqueCombination = [];
        foreach ($combination as $value) {
            $combinationValue = implode("", array_map('strtolower', $value['data']));
            if(!in_array($combinationValue, $currentCombination)){
                $uniqueCombination[] = $value;
            }
            $currentCombination[] = $combinationValue;
        }
        if($isNotSavingAsDraft){
            if (!in_array($product_condition, $this->lang->line('product_condition'))){
                die('{"e":"0","d":"Condition selected not available. Please select another."}');     
            }

            if(count($currentCombination) !== count(array_unique($currentCombination))){
                die('{"e":"0","d":"Same combination is not allowed!"}');
            }

            if(strlen(trim($product_title)) < EsProduct::MINIMUM_PRODUCT_NAME_LEN){ 
                die('{"e":"0","d":"Product name must be atleast '.EsProduct::MINIMUM_PRODUCT_NAME_LEN.' characters!"}');
            }
        }
        else{
            $product_condition = $this->lang->line('product_condition')[0];
            $combination = $uniqueCombination; 
        }

        // Loading Combinations
        $newItemQuantityArray = [];
        $itemQuantity =  $this->product_model->getProductQuantity($product_id, true);   
        
        if((strlen(trim($product_title)) == 0 
            || $product_title == "" 
            || strlen(trim($product_price)) == 0  
            || strlen(trim($product_description)) == 0) && $isNotSavingAsDraft){

            die('{"e":"0","d":"Fill (*) All Required Fields Properly!"}');
        }

        if((int) $product_price <= 0  || (int) $discountedPrice <= 0){
            die('{"e":"0","d":"Invalid price. Price must be greater than 0."}');
        }

        foreach($itemQuantity as $keyid => $value){
            if(count($value['product_attribute_ids'])===1){
                if(($value['product_attribute_ids'][0]['id'] == 0)&&($value['product_attribute_ids'][0]['is_other'] == 0)){
                    $noCombination = true;
                    $itemIdNoCombination =$keyid;
                }
            }
            $itemAttrData = $this->product_model->getItemAttributes($keyid);
            $newData = array();
            foreach ($itemAttrData as $keyy => $valuee) {
                $head = ucfirst(strtolower(str_replace("'", "", $valuee['head'])));
                $newData[$head] = $valuee['value'];
            }
            $newItemQuantityArray[$keyid] = array("quantity" => $value['quantity'],"data" => $newData);
        }

        $retainShippingDetails = array();
        if(count($combination) > 0){
            foreach ($combination as $key => $value) {
                if($value['itemid'] != 0){
                    $arraysAreEqual = ($value['data'] == $newItemQuantityArray[$value['itemid']]['data']);
                    if($arraysAreEqual){  
                        $shippingDetails = $this->product_model->getShippingDetailsByItemId($value['itemid']);
                        $retainShippingDetails[$key] = $shippingDetails;
                    }
                }
            }
        }
        else{
            if(isset($noCombination) && count($combination) <= 0){
                $shippingDetails = $this->product_model->getShippingDetailsByItemId($itemIdNoCombination);
                $retainShippingDetails[0] = $shippingDetails;
            }
        }
  
        if(intval($brand_id,10) == 1){
            $brand_valid = true;
            $otherBrand = $stringUtility->removeNonUTF($this->input->post('brand_sch'));
            $brand_id = 1;
        }
        else{
            if($this->product_model->getBrandById($brand_id)){
                $brand_valid = true;
            } 
        }

        if($brand_valid === false){ 
            $brand_id = 1;
            $otherBrand = "";
        }

        $brandName = ($otherBrand === '')?$this->product_model->getBrandById($brand_id)[0]['name']:$otherBrand;
        $categoryDetails = $this->product_model->selectCategoryDetails($cat_id);
        $categoryName =  $categoryDetails['name'];
        $categorykeywords =  $categoryDetails['keywords']; 
        $search_keyword = preg_replace('!\s+!', ' ',$brandName .' '. $product_title .' '. $otherCategory . ' ' . $categoryName . ' '. $categorykeywords . ' '.$keyword.' '.$username);
 
        $arraynameoffiles = json_decode($this->input->post('arraynameoffiles')); 
        $arraynameoffiles = count($arraynameoffiles) > 0 ? $arraynameoffiles : [];

        if($isNotSavingAsDraft){
            if(count($arraynameoffiles) <= 0){ 
                die('{"e":"0","d":"Please select at least one photo for your listing."}');
            }
        }

        $removeThisPictures = json_decode($this->input->post('removeThisPictures')); 
        $primaryId = $this->input->post('primaryPicture'); 
        $arrayNameOnly = [];

        foreach($arraynameoffiles as $key => $value ) {
            $nameOfFile = explode('||', $value)[0];
            $arrayNameOnly[$key] = strtolower($nameOfFile); 
            if($primaryId == $key){
                $primaryName = strtolower($nameOfFile); 
            }

            if(in_array($key, $removeThisPictures) || $arraynameoffiles[$key] == "") {
                unset($arraynameoffiles[$key],$arrayNameOnly[$key]); 
            } 
        }

        $arraynameoffiles = array_values($arraynameoffiles);
        $arrayNameOnly = array_values($arrayNameOnly); 
        $key = array_search ($primaryName, $arrayNameOnly);

        if(!count($arraynameoffiles) <= 0){
            $temp = $arraynameoffiles[0];
            $arraynameoffiles[0] = $arraynameoffiles[$key];
            $arraynameoffiles[$key] = $temp;

            $temp = $arrayNameOnly[0];
            $arrayNameOnly[0] = $arrayNameOnly[$key];
            $arrayNameOnly[$key] = $temp;
        }

        if($isNotSavingAsDraft){
            if(count($arraynameoffiles) <= 0){ 
                die('{"e":"0","d":"Please select at least one photo for your listing."}');
            }
        }

        $product_details = array('product_id' => $product_id,
            'name' => $product_title,
            'sku' => $sku,
            'brief' => $product_brief,
            'description' => $product_description,
            'keyword' => $keyword,
            'brand_id' => $brand_id,
            'style_id' => $style_id,
            'cat_id' => $cat_id,
            'price' => $product_price,
            'condition' => $product_condition,
            'brand_other_name' => $otherBrand,
            'cat_other_name' => $otherCategory,
            'discount' => $product_discount,
            'search_keyword' => $search_keyword,
            );

        $rowCount = $this->product_model->editProduct($product_details, $memberId);

        if($rowCount>0){
            $this->product_model->deleteShippingInfomation($product_id, array());
            $removeProductDetails = $this->product_model->removeProductDetails($product_id); 

            # saving images
            foreach ($arraynameoffiles as $key => $value) {
                $explodearraynameoffiles = explode('||', $arraynameoffiles[$key]);
                $nameOfFile = $explodearraynameoffiles[0];
                $nameOfFileArray = explode('_', $nameOfFile);
                unset($nameOfFileArray[0]);
                $newName =  $product_id.'_'.implode('_', $nameOfFileArray);  
                $path = $originalPath.$newName;
                $typeOfFile = $explodearraynameoffiles[1];
                $is_primary = ($key == 0 ? 1 : 0);
                $product_image = $this->product_model->addNewProductImage($path,$typeOfFile,$product_id,$is_primary);
            }

            # start of saving other/custom attribute 
            foreach ($attributes as $key => $valuex) {
                $attrName = $stringUtility->removeNonUTF($key);
                $attrName = $attrName === "" ? "no name" : $attrName;
                $others_id = $this->product_model->addNewAttributeByProduct_others_name($product_id,$attrName);
                foreach ($valuex as $keyvalue => $value) {
                    $imageid = 0;
                        $attributeValue = $stringUtility->removeNonUTF($value['value']);
                        $attributeValue = $attributeValue === "" ? "no value" : $attributeValue;
                    if($value['image'] != ""){ 
                        $nameOfFileArray = explode('_', $value['image']);
                        $fileType = end(explode('.', $value['image']));
                        unset($nameOfFileArray[0]);
                        $newOtherName =  $product_id.'_'.implode('_', $nameOfFileArray);  
                        array_push($arrayNameOnly, $value['image']);
                        $imageid = $this->product_model->addNewProductImage($originalPath.'other/'.$newOtherName,$fileType,$product_id,0);
                    }
                    $this->product_model->addNewAttributeByProduct_others_name_value($others_id,$attributeValue,$value['price'],$imageid);
                }
            }
            
            $this->serviceContainer["assets_uploader"]->uploadImageDirectory($tempDirectory, $originalPath, $product_id, $arrayNameOnly);

            #saving combination
            if(count($combination) <= 0){
                $allQuantity = (int) $this->input->post('allQuantity') > EsProductItem::MAX_QUANTITY 
                               ? EsProductItem::MAX_QUANTITY
                               : (int) $this->input->post('allQuantity');
                $idProductItem = $this->product_model->addNewCombination($product_id, $allQuantity);
                if(isset($noCombination) && count($combination) <= 0){
                    foreach ($retainShippingDetails as $key => $value) {
                        foreach ($value as $key2 => $value2) {
                            $shippingId = $this->product_model->storeShippingPrice($value2['location_id'],$value2['price'],$product_id);
                            $this->product_model->storeProductShippingMap($shippingId,$idProductItem);
                        }
                    }
                }
            }
            else{
                foreach ($combination as $key => $value) { 
                    $combQuantity = (int) $value['quantity'] > EsProductItem::MAX_QUANTITY
                                    ? EsProductItem::MAX_QUANTITY
                                    : (int) $value['quantity'];
                    $idProductItem = $this->product_model->addNewCombination($product_id, $combQuantity);
                    if(count($retainShippingDetails) > 0){
                        if (array_key_exists($key,$retainShippingDetails)){
                            foreach ($retainShippingDetails[$key] as $key => $valueShip) {
                                $shippingId = $this->product_model->storeShippingPrice($valueShip['location_id'],$valueShip['price'],$product_id);
                                $this->product_model->storeProductShippingMap($shippingId,$idProductItem);
                            }
                        }
                    }
                    foreach ($value['data'] as $keydata => $valuedata) {
                        $productAttributeId = $this->product_model->selectProductAttributeOther($keydata,$valuedata,$product_id);
                        $this->product_model->addNewCombinationAttribute($idProductItem,$productAttributeId,1);
                    }
                }
            }

            //update search keywords
            $productManager = $this->serviceContainer['product_manager'];
            $productManager->generateSearchKeywords($product_id);

            die('{"e":"1","d":"'.$product_id.'"}'); 
        }
    }
    
    public function step3_addPreference()
    {
        $serverResponse['result'] = 'fail';
        $serverResponse['error'] = 'Failed to validate form';
        
        if( $this->input->post('data') && $this->input->post('name') ){
            $preferenceData = $this->input->post('data');
            $preferenceName = trim($this->input->post('name'));
            $member_id = $this->session->userdata('member_id');
            
            $preferenceCount = $this->em->getRepository('EasyShop\Entities\EsProductShippingPreferenceHead')
                                        ->findBy([
                                            'title' => $preferenceName,
                                            'member' => $member_id,
                                        ]);
            if(count($preferenceCount) <= 0){
                // Insert name vs memid in shipping pref head
                // Returns last id inserted on success, false on failure
                $resultHead = $this->product_model->storeShippingPreferenceHead($member_id, $preferenceName);
                
                $serverResponse['result'] = $resultHead ? 'success' : 'fail';
                $serverResponse['error'] = $resultHead ? '' : 'Failed to store preference head.';
                
                // if success, enter shipping details
                if($resultHead){
                    foreach($preferenceData as $loc=>$price){
                        $resultDetail = $this->product_model->storeShippingPreferenceDetail($resultHead, $loc, $price);
                        
                        if(!$resultDetail){
                            $serverResponse['result'] = 'fail';
                            $serverResponse['error'] = 'Failed to insert preference. Data stored may be incomplete.';
                            break;
                        }
                    }
                    if($resultDetail){
                        $preferences = $this->product_model->getShippingPreference($member_id);
                        $preferencesName = $preferences['name'];
                        end($preferencesName);
                        $arrayKey = key($preferencesName);
                        $serverResponse['shipping_preference']['new'] = [
                            $arrayKey => $preferencesName[$arrayKey]
                        ];
                        $serverResponse['shipping_preference']['data'] = $preferences;
                    }
                }
            }
            else{
                $serverResponse['result'] = 'fail';
                $serverResponse['error'] = 'Preference name already exist!';
            }
        }
        echo json_encode($serverResponse, JSON_FORCE_OBJECT);
    }
    
    public function step3_deletePreference()
    {
        $serverResponse['result'] = 'fail';
        $serverResponse['error'] = 'Failed to validate form.';
        
        if($this->input->post('head')){
            $member_id = $this->session->userdata('member_id');
            $headId = $this->input->post('head');
            
            // Check if shipping preference ID is owned by current user
            $result = $this->product_model->getShippingPreferenceHead($headId, $member_id);
        
            // if shipping preference is owned by current user
            if( count($result) > 0 ){
                $deleteResult = $this->product_model->deleteShippingPreference($headId, $member_id);
                
                $serverResponse['result'] = $deleteResult ? 'success' : 'fail';
                $serverResponse['error'] = $deleteResult ? '' : 'Failed to delete database entry.' ;
                
            } else {
                $serverResponse['result'] = 'fail';
                $serverResponse['error'] = 'Server data mismatch. Try again later.';
            }
        }
    
        echo json_encode($serverResponse, JSON_FORCE_OBJECT);
    }

    /**
     * Getting all children using search
     * @return json
     */
    public function getAllChildren()
    {
        $cat_arr = json_decode($this->input->get('cat_array'));
        $level = 1;
        $prev_cat_name ='';
        foreach($cat_arr as $idx=>$id){
            $data[$idx]['cat_id'] = $response['cat_id'] = $id;
            $data[$idx]['name'] = $response['name'] = $this->product_model->selectCategoryDetails($id)['name'];
            $data[$idx]['node'] = $response['node'] = $this->product_model->getDownLevelNode($id); # get all down level category based on selected parent category            
            $data[$idx]['level'] = $response['level'] = $level;
            $data[$idx]['html'] = $this->load->view('pages/product/product_upload_step1_view2',$response,TRUE);
            $level++;
        }

        echo json_encode($data);
    }

    /**
     * Renders upload step 3
     *
     */
    public function step3()
    {
        if( $this->input->post('prod_h_id') ){
            $this->load->model("memberpage_model");
            $memberId =  $this->session->userdata('member_id');
            $productID = $this->input->post('prod_h_id');
            
            $product = $this->product_model->getProductById($productID, true);

            if(empty($product) || (intval($product['sellerid']) !== intval($memberId))){
                redirect('sell/step1', 'refresh');
            }
            
            if( $this->input->post('is_edit') ){
                $this->product_model->finalizeProduct($productID , $memberId, $product['is_cod']);
            }
            else{
                #Update product entry in es_product to be ready for purchase
                $product['slug'] = $this->product_model->finalizeProduct($productID , $memberId, $product['is_cod']);
            }
            
            $data = array(
                'product' => $product,
                'billing_info' => $this->memberpage_model->get_billing_info($memberId),
                'bank_list' =>  $this->memberpage_model->getAllBanks(),
                #Shipping queries
                'shiploc' => $this->product_model->getLocation(),
                'attr' => $this->product_model->getPrdShippingAttr($productID),
                'product_id' => $productID,
                'shipping_summary' => $this->product_model->getShippingSummary($productID),
                'shipping_preference' => $this->product_model->getShippingPreference($memberId)
            );
            $data['json_check_data'] = json_encode($data['shipping_summary']['shipping_locations']);
            $data['json_shippingpreference'] = json_encode($data['shipping_preference'], JSON_FORCE_OBJECT);
            
            if($this->input->post('is_edit')){
                $data['is_edit'] = true;
            }
            
            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                'title' => 'Sell Product | Easyshop.ph',
                'metadescription' => 'Take your business online by selling your items at Easyshop.ph',
                'relCanonical' => '',
                'renderSearchbar' => false, 
            ]; 
            
            $this->load->spark('decorator');    
            $this->load->view('templates/header_alt2',  $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/product/product_upload_step3_view',$data);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
        }
        else{
            redirect('/sell/step1/', 'refresh');
        }
    }
    
    /**
    * Handler for additional info in product uploads
    * Update billing info, CoD and meetup in product table
    * Upload shipping details if for delivery
    */
    public function step4()
    {
        $esProductRepo = $this->em->getRepository('EasyShop\Entities\EsProduct'); 
        $esBillingInfoRepo = $this->em->getRepository('EasyShop\Entities\EsBillingInfo'); 
        $esProductItemRepo = $this->em->getRepository('EasyShop\Entities\EsProductItem'); 
        $esLookupRepo = $this->em->getRepository('EasyShop\Entities\EsLocationLookup'); 
        $productShippingManager = $this->serviceContainer['product_shipping_location_manager'];

        $memberId = $this->session->userdata('member_id');
        $deliveryOption = $this->input->post('delivery_option') 
                          ? (string) trim($this->input->post('delivery_option')) 
                          : "meetup";
        $shipWithinDays = trim($this->input->post('ship_within')) !== ""
                          ? trim($this->input->post('ship_within'))
                          : null;
        $productId = (int) $this->input->post('prod_h_id');
        $isAllowCod = (bool) $this->input->post('allow_cod'); 
        $isMeetup = $deliveryOption === "meetup";
        $isDelivery = $isMeetup === false;
        $deliveryCost = trim($this->input->post('prod_delivery_cost'));
        $shipPrice = $this->input->post('shipprice');
        $shipLoc = $this->input->post('shiploc');
        $shipAttr = $this->input->post('shipattr');
        $isCanContinue = false;
        $productItemIds = [];
        $postProductIds = [];
        $serverResponse = [
            'result' => "fail",
            'error' => "Operation Start."
        ];
   
        try {
            $product = $esProductRepo->findOneBy([
                            'idProduct' => $productId,
                            'member' => $memberId,
                       ]);
            if($product){
                if($isMeetup || $isDelivery){
                    $defaultPaymentAccount = $esBillingInfoRepo->getDefaultAccount($memberId);
                    $billingId = $defaultPaymentAccount ? $defaultPaymentAccount->getIdBillingInfo() : 0;                    
                    $productShippingManager->deleteProductShippingInfo($productId);
                    $isCanContinue = true;
                    if($isMeetup === false){
                        if($isDelivery){
                            $productItems = $esProductItemRepo->findBy(['product' => $productId]);
                            foreach ($productItems as $item) {
                                $productItemIds[] = $item->getIdProductItem();
                            }

                            if( $deliveryCost === "free" ){
                                $location = $esLookupRepo->find(EsLocationLookup::PHILIPPINES_LOCATION_ID);
                                foreach( $productItemIds as $itemIds ){
                                    $shippingHead = new EsProductShippingHead();
                                    $shippingHead->setLocation($location);
                                    $shippingHead->setPrice(0);
                                    $shippingHead->setProduct($product);
                                    $this->em->persist($shippingHead);

                                    $productItem = $esProductItemRepo->find($itemIds);

                                    $shippingDetails = new EsProductShippingDetail();
                                    $shippingDetails->setShipping($shippingHead);
                                    $shippingDetails->setProductItem($productItem);
                                    $this->em->persist($shippingDetails);
                                }
                                $this->em->flush();
                            }
                            elseif($deliveryCost === "details"){
                                foreach( $shipAttr  as $attr){
                                    foreach( $attr as $itemId){
                                        if( !(in_array((int)$itemId,$postProductIds)) ){
                                            $postProductIds[] = $itemId;
                                        }
                                    }
                                }

                                $difference = array_diff($postProductIds,$productItemIds);
                                if(empty($difference) === true){
                                    foreach( $shipPrice as $groupkey => $pricegroup ){
                                        foreach( $pricegroup as $inputkey => $price ){
                                            $priceValue = $price !== "" ? str_replace(',', '', $price) : 0;
                                            if( is_numeric($priceValue) && $priceValue >= 0 && !preg_match('/[a-zA-Z\+]/', $priceValue) ){
                                                if( isset($shipLoc[$groupkey][$inputkey]) && count($shipLoc[$groupkey][$inputkey]) > 0){
                                                    if( isset($shipAttr[$groupkey]) && count($shipAttr[$groupkey]) > 0 ){
                                                        foreach( $shipAttr[$groupkey] as $attrCombinationId){
                                                            $productItem = $esProductItemRepo->find($attrCombinationId);
                                                            foreach( $shipLoc[$groupkey][$inputkey] as $locationId ){
                                                                $location = $esLookupRepo->find($locationId);
                                                                $shippingHead = new EsProductShippingHead();
                                                                $shippingHead->setLocation($location);
                                                                $shippingHead->setPrice($priceValue);
                                                                $shippingHead->setProduct($product);
                                                                $this->em->persist($shippingHead);

                                                                $shippingDetails = new EsProductShippingDetail();
                                                                $shippingDetails->setShipping($shippingHead);
                                                                $shippingDetails->setProductItem($productItem);
                                                                $this->em->persist($shippingDetails);
                                                            }
                                                        }
                                                        $this->em->flush();
                                                    }
                                                }
                                            }
                                            else{
                                                throw new Exception("Price Invalid."); 
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else{
                            $isAllowCod = false;
                            $isMeetup = true;
                        }
                    }
                    else{
                        $isAllowCod = false;
                    }

                    if($isCanContinue){
                        $product->setIsCod($isAllowCod);
                        $product->setBillingInfoId($billingId);
                        $product->setIsMeetup($isMeetup);
                        $product->setShipsWithinDays($shipWithinDays);
                        $product->setLastmodifieddate(date_create(date("Y-m-d H:i:s")));
                        $this->em->flush();
                        
                        $numberOfCustomCategories = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                                                         ->getNumberOfCustomCategories($memberId, true);
                        /**
                         * If there are no memberCategories yet, save default categories as
                         * new custom categories and then migrate the product to its default
                         * category
                         */
                        if((int)$numberOfCustomCategories === 0){
                            $this->serviceContainer['category_manager']
                                 ->migrateUserCategories($memberId);
                        }
                        $this->serviceContainer['category_manager']
                             ->migrateProductToDefaultCustomCategory($product);
                             
                        $serverResponse['result'] = 'success';
                        $serverResponse['error'] = '';
                    }
                }
                else{
                    throw new Exception("Incomplete Details submitted. Please select at least one delivery option.");
                }
            }
            else{
                throw new Exception("Invalid operation this is not your product.");
            } 
        }
        catch (Exception $e) {
            // you may want to react on the Exception here
            $serverResponse['error'] = $e->getMessage();
        }

        echo json_encode($serverResponse);
    }

    /**
     * Product preview after uploading
     * @return view
     */
    public function finishProductPreview()
    {
        header('Content-Type:text/html; charset=UTF-8');
        $productRepository = $this->em->getRepository('EasyShop\Entities\EsProduct'); 
        $productImageRepository = $this->em->getRepository('EasyShop\Entities\EsProductImage');

        $stringUtility = $this->serviceContainer['string_utility'];
        $userManager = $this->serviceContainer['user_manager'];
        $productManager = $this->serviceContainer['product_manager'];
        $collectionHelper = $this->serviceContainer['collection_helper'];
        $productShippingManager = $this->serviceContainer['product_shipping_location_manager'];

        $productId = $this->input->post('prod_h_id');
        $productEntity = $productRepository->find($productId);

        if($productEntity){
            $product = $productManager->getProductDetails($productEntity);
            $productImages = $productImageRepository->getProductImages($productId);
            $avatarImage = $userManager->getUserImage($product->getMember()->getIdMember());
            $billingInfo = $productRepository->getProductBillingInfo($product->getMember()->getIdMember(), $productId);
            $isFreeShippingNationwide = $productManager->isFreeShippingNationwide($productId);
            $productAttributeDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                                ->getProductAttributeDetailByName($productId);
            $productAttributes = $collectionHelper->organizeArray($productAttributeDetails,true,true);
            $filterAttributes = $productManager->separateAttributesOptions($productAttributes); 
            $productAttributes = $filterAttributes['productOptions'];
            $shippingLocation = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                         ->getShippingDetailsByProductId($productId);

            $paymentMethod = $this->config->item('Promo')[0]['payment_method']; 

            if((int) $product->getIsPromote() === \Easyshop\Entities\EsProduct::PRODUCT_IS_PROMOTE_ON && (!$product->getEndPromo())){ 
                $paymentMethod = $this->config->item('Promo')[$product->getPromoType()]['payment_method']; 
            }

            $productPreviewData = [
                'product' => $product,
                'productDescription' => $stringUtility->purifyHTML($product->getDescription()),
                'productImages' => $productImages,
                'avatarImage' => $avatarImage,
                'isFreeShippingNationwide' => $isFreeShippingNationwide,
                'productAttributes' => $productAttributes,
                'paymentMethod' => $paymentMethod,
                'shippingLocation' => $shippingLocation,
            ];

            $shippingDetails = $productShippingManager->getProductShippingSummary($productId);
            $shippingAttribute = $productShippingManager->getShippingAttribute($productId);

            $mainViewData = [
                'product' => $product,
                'productBillingInfo' => $billingInfo,
                'productView' => $this->load->view('pages/product/product_upload_step4_product_preview',$productPreviewData, true),
                'shipping_summary' => $shippingDetails,
                'attr' => $shippingAttribute,
            ];
            
            $headerData = [
                "memberId" => $this->session->userdata('member_id'),
                'title' => 'Sell Product | Easyshop.ph',
                'metadescription' => 'Take your business online by selling your items at Easyshop.ph',
                'relCanonical' => '',
                'renderSearchbar' => false, 
            ];

            $this->load->spark('decorator');
            $this->load->view('templates/header_alt2',  $this->decorator->decorate('header', 'view', $headerData));
            $this->load->view('pages/product/product_upload_step4_view', $mainViewData);
            $this->load->view('templates/footer_primary', $this->decorator->decorate('footer', 'view')); 
        }
        else{
            redirect('sell/step1', 'refresh');
        }
    }
}


/* End of file productUpload.php */
/* Location: ./application/controllers/productUpload.php */

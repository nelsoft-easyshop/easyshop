<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class productUpload extends MY_Controller 
{
    public $max_file_size_mb;
    public $img_dimension = array();

    function __construct()
    { 
        parent::__construct(); 
        $this->load->model("product_model");
        if(!$this->session->userdata('usersession') && !$this->check_cookie()){
            redirect('/login', 'refresh');
        }

        $this->max_file_size_mb = 5;
        /* Uploaded images dimensions: (w,h) */
        $this->img_dimension['usersize'] = array(1024,768);
        $this->img_dimension['small'] = array(400,535);
        $this->img_dimension['categoryview'] = array(220,200);
        $this->img_dimension['thumbnail'] = array(60,80);

        $this->em = $this->serviceContainer['entity_manager']; 
    }

    function fill_view()
    {
        $data = array(
            'title' => 'Sell Product | Easyshop.ph',
                    'metadescription' => 'Take your business online by selling your items at Easyshop.ph',
            'render_searchbar' => false, 
                );
        $data = array_merge($data, $this->fill_header());

        return $data;
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
        
        $data = $this->fill_view();
        $data['$render_searchbar'] = false; 
        $this->load->view('templates/header', $data); 

        if($data['logged_in'] && ($userdetails['is_contactno_verify'] || $userdetails['is_email_verify']) ){
            $this->load->view('pages/product/product_upload_step1_view',$data_item);
        }
        else{
            $this->load->view('pages/product/product_upload_error');
        }

        $this->load->view('templates/footer'); 
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
        $data = $this->fill_view();
        $data['$render_searchbar'] = false; 
        $this->load->view('templates/header', $data); 
        if($this->input->post('hidden_attribute')){ # if no item selected cant go to the link. it will redirect to step 1
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

            $date = date("Ymd");
            $tempDirectory = './assets/temp_product/'. $response['tempId'].'_'.$response['memid'].'_'.$date.'/';
            $response['tempdirectory'] = $tempDirectory;
            $this->session->set_userdata('tempId', $response['tempId']);
            $this->session->set_userdata('tempDirectory',  $tempDirectory);
            mkdir($tempDirectory, 0777, true);
            mkdir($tempDirectory.'categoryview/', 0777, true);
            mkdir($tempDirectory.'small/', 0777, true);
            mkdir($tempDirectory.'thumbnail/', 0777, true);
            mkdir($tempDirectory.'other/', 0777, true);

            if($this->input->post('step1_content')){
                $response['step1_content'] = $this->input->post('step1_content');
            }
    
            $this->load->view('pages/product/product_upload_step2_view',$response);
            $this->load->view('templates/footer');
        }else{
            redirect('/sell/step1/', 'refresh');
        }
    }

    /**
     *  Display item details of the selected
     *  product to be modify
     */
    public function step2edit2()
    {
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
                        if(!$eachAttribute[$key][$key2]['img_path'] == ''){
                            $file = explode('_',end(explode('/', $eachAttribute[$key][$key2]['img_path'])));
                            unset($file[0]); 
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
        $response['is_edit'] = 'is_edit';
        $response['img_max_dimension'] = $this->img_dimension['usersize'];
        $date = end(explode('_', explode('/', $path)[3]));
 
        $tempdirectory = $tempId.'_'.$member_id.'_'.$date;
        $tempdirectory = $response['tempdirectory'] = './assets/temp_product/'.$tempdirectory.'/';

        $this->session->set_userdata('tempId', $response['tempId']); 
        $this->session->set_userdata('tempDirectory',  $tempdirectory);
        $this->session->set_userdata('originalPath',  $path);

        directory_copy($path, $tempdirectory,$tempId,$arrayNameOnly); 
        
        $data = array('title'=>'Edit Product');
        $data = array_merge($data,$this->fill_view());
        $data['$render_searchbar'] = false; 

        $this->load->view('templates/header', $data);   
        $this->load->view('pages/product/product_upload_step2_view',$response);
        $this->load->view('templates/footer');
    }

    /**
     *  Upload image for primary and other
     *  alternative of the image of the product
     *
     *  @return JSON
     */
    public function uploadimage()
    {  
        $pathDirectory = $this->session->userdata('tempDirectory');
        $filescnttxt = $this->input->post('filescnttxt');
        $afstart = $this->input->post('afstart');
        $afstartArray = json_decode($afstart); 
        $filenames_ar = array(); 
        $text = "";
        $error = 0;
        $allowed =  array('gif','png' ,'jpg','jpeg'); // available format only for image

        foreach($_FILES['files']['name'] as $key => $value ) {

            $file_ext = explode('.', $value);
            $file_ext = strtolower(end($file_ext)); 
            $filenames_ar[$key] = $afstartArray[$key];

            if(!in_array(strtolower($file_ext),$allowed))
            {
                unset($_FILES['files']['name'][$key]);
                unset($_FILES['files']['type'][$key]);
                unset($_FILES['files']['tmp_name'][$key]);
                unset($_FILES['files']['error'][$key]);
                unset($_FILES['files']['size'][$key]);
                unset($filenames_ar[$key]); 
            }

            if(isset($_FILES['files']['name'][$key])){
                if($_FILES['files']['size'][$key] >= ($this->max_file_size_mb * 1024) * 1024){ # size of image must be 5mb only
                    unset($_FILES['files']['name'][$key]);
                    unset($_FILES['files']['type'][$key]);
                    unset($_FILES['files']['tmp_name'][$key]);
                    unset($_FILES['files']['error'][$key]);
                    unset($_FILES['files']['size'][$key]);
                    unset($filenames_ar[$key]); 
                }
            }
        }

        $_FILES['files']['name'] = array_values($_FILES['files']['name']);
        $_FILES['files']['type'] = array_values($_FILES['files']['type']);
        $_FILES['files']['tmp_name'] = array_values($_FILES['files']['tmp_name']);
        $_FILES['files']['error'] = array_values($_FILES['files']['error']);
        $_FILES['files']['size'] = array_values($_FILES['files']['size']);
        $filenames_ar = array_values($filenames_ar); 

        if (!file_exists ($pathDirectory)){
            mkdir($pathDirectory, 0777, true);;
        }

        if(count($filenames_ar) <= 0){
            $return = array( 
                'msg' => "Please select valid image type.\nAllowed type: .PNG,.JPEG,.GIF\nAllowed max size: 5mb", 
                'fcnt' => $filescnttxt,
                'err' => 1
                );

            die(json_encode($return));
        }
         
        $this->upload->initialize(array( 
            "upload_path" => $pathDirectory,
            "overwrite" => FALSE,
            "file_name"=> $filenames_ar,
            "encrypt_name" => FALSE,
            "remove_spaces" => TRUE,
            "allowed_types" => "jpg|jpeg|png|gif",
            "max_size" => $this->max_file_size_mb * 1024,
            "xss_clean" => FALSE
            )
        );

        if($this->upload->do_multi_upload('files')){
            $file_data = $this->upload->get_multi_upload_data();
            for ($i=0; $i < sizeof($filenames_ar); $i++) { 
                $this->es_img_resize($filenames_ar[$i],$pathDirectory, 'small/', $this->img_dimension['small']); 
                $this->es_img_resize($filenames_ar[$i],$pathDirectory.'small/', '../categoryview/', $this->img_dimension['categoryview']);
                $this->es_img_resize($filenames_ar[$i],$pathDirectory.'categoryview/','../thumbnail/', $this->img_dimension['thumbnail']);
                //If user uploaded image is too large, resize and overwrite original image
                if(isset($file_data[$i])){
                    if(($file_data[$i]['image_width'] > $this->img_dimension['usersize'][0]) || ($file_data[$i]['image_height'] > $this->img_dimension['usersize'][1])){
                        $this->es_img_resize($file_data[$i]['file_name'],$pathDirectory,'', $this->img_dimension['usersize']);
                    }
                }
            }
        }
        else{
            $text = $this->upload->display_errors();
            if($text == '<p>The uploaded file exceeds the maximum allowed size in your PHP configuration file.</p>'){
                $text ='File is too large. Please select another image';
            }
            $error = 1;
        }
          
        $return = array( 
            'msg' => $text, 
            'fcnt' => $filescnttxt,
            'err' => $error
            );

        die(json_encode($return));
    }

    /**
     *  Upload image for attributes of the product
     *  
     *  @return JSON
     */
    public function uploadimageOther()
    {
        $temp_product_id = $this->session->userdata('tempId');
        $tempDirectory = $this->session->userdata('tempDirectory');
        $memberId =  $this->session->userdata('member_id');
        $filename =  $this->input->post('pictureName');
        $date = date("Ymd");
        $fulldate = date("YmdGis"); 
        $allowed =  array('gif','png' ,'jpg','jpeg'); // available format only for image
        $fileExtension = explode('.', $filename);
        $fileExtension = strtolower(end($fileExtension));

        if(!in_array(strtolower($fileExtension),$allowed))
        {
            die('{"result":"false","msg":"Invalid file type. Please choose another image."}');
        }

        $pathDirectory = $tempDirectory.'/other/';

        if (!file_exists ($pathDirectory)){
            mkdir($pathDirectory, 0777, true);
        }

        $this->upload->initialize(array( 
            "upload_path" => $pathDirectory,
            "overwrite" => FALSE,
            "file_name"=> $filename,
            "encrypt_name" => FALSE,
            "remove_spaces" => TRUE,
            "allowed_types" => "jpg|jpeg|png|gif",
            "max_size" => $this->max_file_size_mb * 1024,
            "xss_clean" => FALSE
            )); 
 
        if ($this->upload->do_multi_upload('attr-image-input')){ 
            $this->es_img_resize($filename,$pathDirectory, 'small/', $this->img_dimension['small']); 
            $this->es_img_resize($filename,$pathDirectory.'small/', '../categoryview/', $this->img_dimension['categoryview']);
            $this->es_img_resize($filename,$pathDirectory.'categoryview/','../thumbnail/', $this->img_dimension['thumbnail']);
            die('{"result":"ok"}');
        }
        else{
            die('{"result":"false","msg":"'.$this->upload->display_errors().'"}');
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
        $this->load->model("user_model");
        $combination = json_decode($this->input->post('combination'),true); 
        $attributes = json_decode($this->input->post('attributes'),true);
        $data = $this->input->post('data');
        $cat_id = $this->input->post('id');
        $otherCategory = $this->input->post('otherCategory');
        $brand_id =  $this->input->post('prod_brand'); 
        $brand_valid = false;
        $otherBrand = '';
        $product_title = trim($this->input->post('prod_title'));
        $product_brief = trim($this->input->post('prod_brief_desc'));
        $product_description =  substr(trim($this->input->post('prod_description')), 0, 65000);
        $product_price = ($this->input->post('prod_price') == "")? '0' : str_replace(',', '', $this->input->post('prod_price'));
        $product_discount = ($this->input->post('discount'))?floatval($this->input->post('discount')):0;
        $product_discount = ($product_discount <= 100)?$product_discount:100;
        $product_condition = $this->input->post('prod_condition');
        $sku = trim($this->input->post('prod_sku'));
        $keyword = trim($this->input->post('prod_keyword'));
        $style_id = 1;
        $member_id =  $this->session->userdata('member_id');
        $tempDirectory = $this->session->userdata('tempDirectory');
        $date = date("Ymd");
        $fulldate = date("YmdGis");
        $isNotSavingAsDraft = $this->input->post('savedraft') ? false : true;

        if(intval($brand_id,10) == 1){
            $brand_valid = true;
            $otherBrand = $this->input->post('brand_sch');
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
        
        if($isNotSavingAsDraft){
            if (!in_array($product_condition, $this->lang->line('product_condition'))){
                die('{"e":"0","d":"Condition selected not available. Please select another."}');     
            }
        }

        if($isNotSavingAsDraft){
            $currentCombination = [];
            foreach ($combination as $value) {
                $currentCombination[] = implode("", array_map('strtolower', $value['data'])); 
            }
            if(count($currentCombination) !== count(array_unique($currentCombination))){
                die('{"e":"0","d":"Same combination is not allowed!"}');
            }
        }

        if((strlen(trim($product_title)) == 0 
            || $product_title == "" 
            || strlen(trim($product_price)) == 0 
            || $product_price <= 0 
            || strlen(trim($product_description)) == 0) && $isNotSavingAsDraft){

            die('{"e":"0","d":"Fill (*) All Required Fields Properly!"}');      
        }
        else{
            
            $arraynameoffiles = json_decode($this->input->post('arraynameoffiles')); 
            $arraynameoffiles = (count($arraynameoffiles) > 0) ? $arraynameoffiles : array();
            if($isNotSavingAsDraft){
                if(count($arraynameoffiles) <= 0){ 
                    die('{"e":"0","d":"Please select at least one photo for your listing."}');
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
                    $others_id = $this->product_model->addNewAttributeByProduct_others_name($product_id,$key);
                    foreach ($valuex as $keyvalue => $value) {
                        $imageid = 0;
                        if($value['image'] != ""){ 
                            $nameOfFileArray = explode('_', $value['image']);
                            $fileType = end(explode('.', $value['image']));
                            unset($nameOfFileArray[0]);
                            $newOtherName =  $product_id.'_'.implode('_', $nameOfFileArray); 
                            array_push($arrayNameOnly, $value['image']);
                            $imageid = $this->product_model->addNewProductImage($other_path_directory.$newOtherName,$fileType,$product_id,0);
                        }
                        $this->product_model->addNewAttributeByProduct_others_name_value($others_id,$value['value'],$value['price'],$imageid);
                    }
                }
                #end of other 
 
                if(!count($arraynameoffiles) <= 0){ 
                    directory_copy($tempDirectory, $path_directory,$product_id,$arrayNameOnly); 
                }

                #saving combination
                if(count($combination) <= 0){
                    $idProductItem = $this->product_model->addNewCombination($product_id,$this->input->post('allQuantity'));
                }
                else{
                    foreach ($combination as $key => $value) {
                        $idProductItem = $this->product_model->addNewCombination($product_id,$value['quantity']);
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
    public function step2edit2Submit()
    {
        $this->load->model("user_model");
        $combination = json_decode($this->input->post('combination'),true); 
        $attributes = json_decode($this->input->post('attributes'),true);
        $product_id = $this->input->post('p_id');
        $memberId = $this->session->userdata('member_id');
        $cat_id = $this->input->post('id');
        $product_title = trim($this->input->post('prod_title'));
        $product_brief = trim($this->input->post('prod_brief_desc'));
        $product_description = substr(trim($this->input->post('prod_description')), 0, 65000);
        $product_price = ($this->input->post('prod_price') == "")? '0' : str_replace(',', '', $this->input->post('prod_price'));
        $product_discount = ($this->input->post('discount'))?floatval($this->input->post('discount')):0;
        $product_discount = ($product_discount <= 100)?$product_discount:100;
        $product_condition = $this->input->post('prod_condition');
        $otherCategory = html_escape($this->input->post('otherCategory')); 
        $sku = trim($this->input->post('prod_sku'));
        $brand_id =  $this->input->post('prod_brand'); 
        $keyword = trim($this->input->post('prod_keyword'));
        $style_id = 1;
        $brand_valid = false;
        $otherBrand = ""; $primaryName ="";
        $username = $this->user_model->getUserById($memberId)['username'];
        $dir = './assets/product/'; 
        $originalPath = $path = glob($dir."{$product_id}_{$memberId}*", GLOB_BRACE)[0].'/';
        $tempDirectory = $this->session->userdata('tempDirectory');
        $isNotSavingAsDraft = $this->input->post('savedraft') ? false : true;

        if($isNotSavingAsDraft){
            $currentCombination = [];
            foreach ($combination as $value) {
                $currentCombination[] = implode("", array_map('strtolower', $value['data'])); 
            }
            if(count($currentCombination) !== count(array_unique($currentCombination))){
                die('{"e":"0","d":"Same combination is not allowed!"}');
            }
        }

        // Loading Combinations
        $newItemQuantityArray = [];
        $itemQuantity =  $this->product_model->getProductQuantity($product_id, true);   
        
        if((strlen(trim($product_title)) == 0 
            || $product_title == "" 
            || strlen(trim($product_price)) == 0 
            || $product_price <= 0 
            || strlen(trim($product_description)) == 0) && $isNotSavingAsDraft){

            die('{"e":"0","d":"Fill (*) All Required Fields Properly!"}');
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
            $otherBrand = $this->input->post('brand_sch');
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
                $others_id = $this->product_model->addNewAttributeByProduct_others_name($product_id,$key);
                foreach ($valuex as $keyvalue => $value) {
                    $imageid = 0;
                    if($value['image'] != ""){ 
                        $nameOfFileArray = explode('_', $value['image']);
                        $fileType = end(explode('.', $value['image']));
                        unset($nameOfFileArray[0]);
                        $newOtherName =  $product_id.'_'.implode('_', $nameOfFileArray);  
                        array_push($arrayNameOnly, $value['image']);
                        $imageid = $this->product_model->addNewProductImage($originalPath.'other/'.$newOtherName,$fileType,$product_id,0);
                    }
                    $this->product_model->addNewAttributeByProduct_others_name_value($others_id,$value['value'],$value['price'],$imageid);
                }
            }
            directory_copy($tempDirectory, $originalPath,$product_id,$arrayNameOnly); 

            #saving combination
            if(count($combination) <= 0){
                $idProductItem = $this->product_model->addNewCombination($product_id,$this->input->post('allQuantity'));
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
                    $idProductItem = $this->product_model->addNewCombination($product_id,$value['quantity']);
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
    
    function step3_addPreference()
    {
        $serverResponse['result'] = 'fail';
        $serverResponse['error'] = 'Failed to validate form';
        
        if( $this->input->post('data') && $this->input->post('name') ){
            $preferenceData = $this->input->post('data');
            $preferenceName = $this->input->post('name');
            
            $member_id = $this->session->userdata('member_id');
            
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
                    $serverResponse['shipping_preference'] = $this->product_model->getShippingPreference($member_id);
                }
            }
        }
        echo json_encode($serverResponse, JSON_FORCE_OBJECT);
    }
    
    function step3_deletePreference()
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
     * Delete draft items
     *
     * @return string
     */
    public function deleteDraft()
    {
        $productId = $this->input->post('p_id');
        $memberId =  $this->session->userdata('member_id');
        $output = $this->product_model->deleteDraft($memberId,$productId);
        if($output == 0){
            $data = '{"e":"0","m":"Sorry, something went wrong. Please try again later"}';
        }else{
            $data = '{"e":"1","d":"Draft item successfully removed."}';
        }

        die($data);
    }
    
    public function previewItem(){
        if($this->input->post('p_id'))
            $id = $this->input->post('p_id');
        else
            redirect('sell/step1', 'refresh'); 
        #$modal is true unless the string 'false' modal parameter is posted
        if($this->input->post('modal') == 'false'){
            $modal = false;
        }else{
            $modal = true;
        }
        $this->load->model("memberpage_model");
        $memberId =  $this->session->userdata('member_id');
        $product_row = $this->product_model->getProductById($id, true);

        if(empty($product_row) || (intval($product_row['sellerid']) !== intval($memberId))){
            redirect('sell/step1', 'refresh');
        }
        $quantities = $this->product_model->getProductQuantity($id);
        $availability = "varies";
     
        foreach($quantities as $qty){
            if(count($qty['product_attribute_ids'])===1){
                if(($qty['product_attribute_ids'][0]['id'] == 0)&&($qty['product_attribute_ids'][0]['is_other'] == 0)){
                    $availability = $qty['quantity'];
                }
            }   
        }
        
        $product_options = $this->product_model->getProductAttributes($id, 'NAME');
        $product_options = $this->product_model->implodeAttributesByName($product_options);

        $preview_data = array(
            'breadcrumbs' =>  $this->product_model->getParentId($product_row['cat_id']),
            'product' => $product_row,
            'billing_info' => $this->memberpage_model->get_billing_info($memberId),
            'bank_list' =>  $this->memberpage_model->getAllBanks(),
            'main_categories' => $this->product_model->getFirstLevelNode(TRUE),
            'product_images' => $this->product_model->getProductImages($id),
            'product_options' => $product_options,
            'availability' => $availability,
            'modal' => $modal,
        );
        if($modal){ 
            $this->load->view('pages/product/product_upload_preview',$preview_data);
        }
            else{
            $data = $this->fill_view();
            $this->load->view('templates/header', $data);
            $this->load->view('pages/product/product_upload_preview',$preview_data);
            $this->load->view('templates/footer');
        }
    }

    public function getAllChildren(){
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

    private function es_img_resize($filename,$path_directory,$added_path,$dimension){
        $filename = strtolower($filename);
        $path_to_result_directory = $path_directory.$added_path; 
        $path_to_image_directory = $path_directory;

        $config['image_library'] = 'GD2';
        $config['source_image'] = $path_to_image_directory . $filename;
        $config['maintain_ratio'] = true;
        $config['quality'] = '85%';
        $config['new_image'] = $path_to_result_directory . $filename;
        $config['width'] = $dimension[0];
        $config['height'] = $dimension[1];

        if(!file_exists($path_to_result_directory)) {  
            if(!mkdir($path_to_result_directory)) {  
                die('{"e":"0","d":"There was a problem. \n Please try again later! - Error[0015]"}');  
            }   
        }

        $this->image_lib->initialize($config); 
        $this->image_lib->resize();
        $this->image_lib->clear();
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
            $data = array_merge($data, $this->fill_view());
            $data['json_check_data'] = json_encode($data['shipping_summary']['shipping_locations']);
            $data['json_shippingpreference'] = json_encode($data['shipping_preference'], JSON_FORCE_OBJECT);
            
            if($this->input->post('is_edit')){
                $data['is_edit'] = true;
            }
            
            $this->load->view('templates/header', $data);
            $this->load->view('pages/product/product_upload_step3_view',$data);
            $this->load->view('templates/footer');
        }
        else{
            redirect('/sell/step1/', 'refresh');
        }
    }
    
    /**
     * Handler for additional info in product uploads
     * Update billing info, CoD and meetup in product table
     * Upload shipping details if for delivery
     *
     */
    public function step4()
    {    
        $this->load->model('memberpage_model');
        $memberID =  $this->session->userdata('member_id');
        $deliveryOption = $this->input->post('delivery_option') ? $this->input->post('delivery_option') : array();
        $shipWithinDays = trim($this->input->post('ship_within'));

        $serverResponse = array(
            'result' => 'fail',
            'error' => 'Incomplete Details submitted. Please select at least one delivery option.'
        );
        
        if( $this->input->post('prod_h_id') && (in_array("meetup", $deliveryOption) || in_array("delivery", $deliveryOption)) ){
            $productID = $this->input->post('prod_h_id');
            $billingID = (int)$this->input->post('billing_info_id');
            $isMeetup = in_array("meetup", $deliveryOption) ? 1:0;
            $isDelivery = in_array("delivery", $deliveryOption) ? true:false;
            $isCOD = strtolower($this->input->post('allow_cod')) === 'on' ? 1:0;
            
            $isEdit = $this->input->post('is_edit') ? true:false;
            
            # Check if billing id is owned by user in database
            if( $billingID !== 0 ){
                $payment_accounts = $this->memberpage_model->get_billing_info($memberID);
                if(!(array_key_exists($billingID,$payment_accounts))){
                    $serverResponse['error'] = 'Billing ID database mismatch!';
                    echo json_encode($serverResponse);
                    exit();
                }
            }
            
            # Fetch for database Product Item ID to be used in processing later. (for this ProductID)
            $dbProductItemID_temp = $this->product_model->getProductItem($productID, $memberID);
            foreach( $dbProductItemID_temp as $arr ){
                $dbProductItemID[] = $arr['id_product_item'];
            }
            
            # DELETE EXISTING SHIPPING SUMMARY ENTRIES IN DATABASE
            $this->product_model->deleteShippingSummaryOnEdit($dbProductItemID);
            
            $myProceedVar = true;
            
            # If delivery option "for delivery" is selected
            if($isDelivery){
                # DeliveryCost can be one of the ff: free, details, or off
                $deliveryCost = $this->input->post('prod_delivery_cost');
                if( $deliveryCost === "free" ){
                    $locationID = 1; #FOR PHILIPPINES
                    $priceValue = 0; #FREE
                    foreach( $dbProductItemID as $attrCombinationID ){
                        $shippingID = $this->product_model->storeShippingPrice($locationID,$priceValue,$productID);
                        $this->product_model->storeProductShippingMap($shippingID, $attrCombinationID);
                    }
                # If shipping details provided, start verification of details
                }else if( $deliveryCost === "details"){
                    $shipPrice = $this->input->post('shipprice');
                    $shipLoc = $this->input->post('shiploc');
                    $shipAttr = $this->input->post('shipattr');
                    
                    #Fetch client ProductItemID based on submitted(checked) attributes
                    $clientProductItemID = array();
                    foreach( $shipAttr  as $grouparr){
                        foreach( $grouparr as $pid){
                            if( !(in_array((int)$pid,$clientProductItemID)) ){
                                $clientProductItemID[] = $pid;
                            }
                        }
                    }

                    // Reorder array values for proper checking of equality
                    sort($clientProductItemID);
                    sort($dbProductItemID);
                    
                    #If ProductItemID matches for client and server, also verifies that all attributes have been checked
                    #or provided with a shipping location
                    if( $clientProductItemID == $dbProductItemID ){
                        # Cycle through each price and check if 
                        foreach( $shipPrice as $groupkey => $pricegroup ){
                            foreach( $pricegroup as $inputkey => $price ){
                                $priceValue = $price !== "" ? str_replace(',', '', $price) : 0;
                                # Check if price in submitted input field is provided (numeric and not blank)
                                if( is_numeric($priceValue) && $priceValue >= 0 && !preg_match('/[a-zA-Z\+]/', $priceValue) ){
                                    #check if shipping location is provided for the price
                                    if( isset($shipLoc[$groupkey][$inputkey]) && count($shipLoc[$groupkey][$inputkey]) > 0){
                                        # Check if attributes are provided for that group
                                        if( isset($shipAttr[$groupkey]) && count($shipAttr[$groupkey]) > 0 ){
                                            # -- ALGO START FOR INSERTION TO DATABASE --
                                            # Cycle through each locationID in groupkey and inputkey
                                            foreach( $shipAttr[$groupkey] as $attrCombinationID){                                                
                                                foreach( $shipLoc[$groupkey][$inputkey] as $locationID ){
                                                    $shippingID = $this->product_model->storeShippingPrice($locationID,$priceValue,$productID);
                                                    $this->product_model->storeProductShippingMap($shippingID, $attrCombinationID);
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    $serverResponse['error'] = "Invalid price provided";
                                    echo json_encode($serverResponse);
                                    exit();
                                }
                            }
                        }
                    }else{
                        $serverResponse['error'] = "Please provide shipping details for all item properties.";
                        $myProceedVar = false;
                    }
                }
            }
            // EXECUTE ONLY IF NO ERRORS HAVE BEEN ENCOUNTERED IN STEPS ABOVE (delivery details)
            if($myProceedVar){
                $shipWithinDays = (trim($shipWithinDays) === "") ? null : $shipWithinDays;
                $prodUploadBoolResult = $this->product_model->updateProductUploadAdditionalInfo($productID, $memberID, $billingID, $isCOD, $isMeetup,$shipWithinDays);
                $serverResponse['result'] = $prodUploadBoolResult ? 'success' : 'fail';
                $serverResponse['error'] = $prodUploadBoolResult ? '' : 'Error updating database.';
            }
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
            $headerData = $this->fill_view();
            $product = $productManager->getProductDetails($productEntity);
            $productImages = $productImageRepository->getProductImages($productId);
            $avatarImage = $userManager->getUserImage($product->getMember()->getIdMember());
            $billingInfo = $productRepository->getProductBillingInfo($product->getMember()->getIdMember(), $productId);
            $isFreeShippingNationwide = $productManager->isFreeShippingNationwide($productId);
            $productAttributeDetails = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                                ->getProductAttributeDetailByName($productId);
            $productAttributes = $collectionHelper->organizeArray($productAttributeDetails,true,true);

            $paymentMethod = $this->config->item('Promo')[0]['payment_method']; 

            if((int) $product->getIsPromote() === $productManager::PRODUCT_IS_PROMOTE && (!$product->getEndPromo())){ 
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

            $this->load->view('templates/header', $headerData);
            $this->load->view('pages/product/product_upload_step4_view',$mainViewData);
            $this->load->view('templates/footer');
        }
        else{
            redirect('sell/step1', 'refresh');
        }
    }
}


/* End of file productUpload.php */
/* Location: ./application/controllers/productUpload.php */

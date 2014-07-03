<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage extends MY_Controller {

    public $img_dimension = array();
    public $file;
    public $var;

    function __construct() {
        parent::__construct();
        $this->load->model('product_model');
        $this->load->model('user_model');

        $uid = $this->session->userdata('member_id');
         
        $is_admin = false;
        if($uid){
            $user_access_level = $this->user_model->getUserAccessDetails($uid);
            $is_admin = (intval($user_access_level['is_admin']) === 1);
            if(!$is_admin){
               die('Forbidden directory <br> Click here <a href="/home">Home</a>'); 
            }
        }else{
            die('Forbidden directory <br> Click here <a href="/home">Home</a>');
        }

        $this->declareEnvironment();
        $this->img_dimension['usersize'] = array(589,352);
    }

    function declareEnvironment(){
        if(ES_PRODUCTION){

            // LIVE
            $this->file = APPPATH . "resources/page/home_files_prod.xml"; 
            $this->var = 'page/home_files_prod';
        }else{
            // DEVELOPMENT
            $this->file = APPPATH . "resources/page/home_files_dev.xml"; 
            $this->var = 'page/home_files_dev';
     
        }
    }
    function index()
    { 
         
        $home_content = $this->product_model->getHomeContent('page/home_files_dev');
        $viewdata['bannerImages'] = $home_content['mainSlide'];   
        $viewdata['productSlide'] = $home_content['productSlide'];    

        $viewdata['productSlide_title'] = $home_content['productSlide_title']; 

        $viewdata['productSideBanner'] = $home_content['productSideBanner'];     
        $this->load->view('pages/manage/manage_banner',$viewdata);
    }

    function editSlideProduct()
    {
        $file = $this->file;
        $productSideBanner = $this->input->post('productsidebanner');
        $item = $this->input->post('item');
        $title = $this->input->post('productslide_title');


        $doc = new SimpleXMLElement(file_get_contents($file));
        $target = current($doc->xpath('//productSlide_title'));
        $target->value = $title; 
        $doc->asXml($file);

        $productdata = $this->product_model->getProductBySlug($productSideBanner, false);
        if (empty($productdata)){
            die('{"e":"1","m":"Product side banner slug not available!"}');
        }

        $doc = new SimpleXMLElement(file_get_contents($file));
        $target = current($doc->xpath('//productSideBanner'));
        $target->value = $productSideBanner; 
        $doc->asXml($file);



        foreach ($item as $key => $value) {
            $cnt = $key+1;
            $productdata = $this->product_model->getProductBySlug($item[$key], false);
            if (empty($productdata)){
                die('{"e":"1","m":"Product slide '.$cnt.' slug not available!"}');
            }  

            $doc = new SimpleXMLElement(file_get_contents($file));
            $target = current($doc->xpath('//productSlide['.$cnt.']'));
            $target->value = $item[$key]; 
            $doc->asXml($file);
        }

        die('{"e":"0","m":"Success"}');
    }

    function uploadMainSlide()
    { 
        $filename = date('yhmdhs');
        $path_directory = 'assets/images/mainslide';
        $allowed =  array('gif','png' ,'jpg','jpeg'); # available format only for image
      

        $file_ext = explode('.', $_FILES['files']['name']);
        $file_ext = strtolower(end($file_ext));  

        if(!in_array(strtolower($file_ext),$allowed))
        {
            die('{"e":"1","m":"Not Allowed type!","f":"'.$filename.'.'.$file_ext.'"}');
        }

        $this->upload->initialize(array( 
            "upload_path" => $path_directory,
            "overwrite" => FALSE, 
            "encrypt_name" => FALSE,
            "file_name"=> $filename,
            "remove_spaces" => TRUE,
            "allowed_types" => "jpg|jpeg|png|gif", 
            "xss_clean" => FALSE
            ));     

        $error = 0;

        if ($this->upload->do_multi_upload('files')){

            $file = $this->file;

            $string = '    
    <mainSlide> 
        <value>'.$path_directory.'/'.$filename.'.'.$file_ext.'</value> 
        <type>image</type>
        <imagemap>
            <coordinate>0,0,0,0</coordinate>
            <target>home</target>
        </imagemap>
    </mainSlide>';
            
            $this->addXml($file,$string,'//mainSlide[last()]');

            $file_data = $this->upload->get_multi_upload_data();
            if(isset($file_data[0])){
                if(($file_data[0]['image_width'] != $this->img_dimension['usersize'][0]) || ($file_data[0]['image_height'] != $this->img_dimension['usersize'][1])){
                    $this->es_img_resize($file_data[0]['file_name'],$path_directory,'', $this->img_dimension['usersize']);
                }   
            }




            die('{"e":"0","m":"uploaded","f":"'.$filename.'.'.$file_ext.'","d":"'.$filename.'","u":"'.$path_directory.'/'.$filename.'.'.$file_ext.'"}');
        }else{
            die('{"e":"1","m":"'.$this->upload->display_errors().'"}');
        }
    }

    function removeMainSlide()
    {   
        $targetNode = $this->input->get('node');
        $file = $this->file;
        $this->deleteXml($file,'//mainSlide[value="'.$targetNode.'"]');

        die('{"e":"0","m":"removed"}');
         
    }

    function moveNodeXml($move = 'up')
    {
        $file = $this->file;
        $targetNode = $this->input->get('node');
        $pos = $this->getPositionXml($file,'mainSlide','value',$targetNode);
        $node = $originalNode = $pos['counter'];
        $count = $pos['total']; 
        $sxe = new SimpleXMLElement(file_get_contents($file));
        $target = current($sxe->xpath('//mainSlide['.$node.']'));
        
        if($move == 'down'){
            if($count == $originalNode){
                die();
            } 
        }else{
            if($originalNode == 1){
                die();
            }
        }

        $this->deleteXml($file,'//mainSlide['.$originalNode.']');

        $string = '    
    <mainSlide> 
        <value>'.$target->value.'</value> 
        <type>'.$target->type.'</type>
        <imagemap>
            <coordinate>'.$target->imagemap->coordinate.'</coordinate>
            <target>'.$target->imagemap->target.'</target>
        </imagemap>
    </mainSlide>'; 

        $position = ($move == 'down' ? $node : $node-=2 );
        if($move == 'down'){
            $this->addXml($file,$string,'//mainSlide['.$position.']');  
        }else{
            if($position < 1){ 
                $this->addXml($file,$string,'//mainSlide[1]',false);   
            }else{
                $this->addXml($file,$string,'//mainSlide['.$position.']');   
            }

        }   

    }   

    function updateNodeXml()
    {
        $file = $this->file;
        $targetNode = $this->input->get('node');
        $targetLink = $this->input->get('target');
        $x = $this->input->get('getx');
        $x2 = $this->input->get('getxx');
        $y = $this->input->get('gety');
        $y2 = $this->input->get('getyy');  

        $coordinate = $x.','.$y.','.$x2.','.$y2;
        $doc = new SimpleXMLElement(file_get_contents($file));

        $target = current($doc->xpath('//mainSlide[value="'.$targetNode.'"]'));
 
        $target->imagemap->coordinate = $coordinate;
        $target->imagemap->target = $targetLink;
  
        $doc->asXml($file);
        die('{"e":"0","m":"updated"}');
    }

    function getPositionXml($file,$tag,$valueNode,$value)
    {
        $sxe = new SimpleXMLElement(file_get_contents($file));
        $counter = 1;
         
        
        foreach ($sxe->$tag as $k => $v) {
            if($v->$valueNode == $value){
                return array('counter' => $counter, 'total' => count($sxe->$tag));;
            }
            $counter++;
        }
    }

    function addXml($file,$xml_string,$target_node,$move = true)
    {
        $sxe = new SimpleXMLElement(file_get_contents($file));
        $insert = new SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));
        $this->simplexml_insert_after($insert, $target,$move);
        $sxe->asXML($file);
    }

    function deleteXml($file,$targetNode)
    {
        $doc = new SimpleXMLElement(file_get_contents($file));
        $target = current($doc->xpath($targetNode));
        $dom = dom_import_simplexml($target);
        $dom->parentNode->removeChild($dom);
        $doc->asXml($file);
    }

    function simplexml_insert_after(SimpleXMLElement $insert, SimpleXMLElement $target,$move = true)
    {
        $target_dom = dom_import_simplexml($target);

        $document = $target_dom->ownerDocument;
        $insert_dom = $document->importNode(dom_import_simplexml($insert), true);

        $parentNode = $target_dom->parentNode;

        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);
                $parentNode->insertBefore($document->createTextNode("\t"), $result);
            } else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        }else{
            $result =  $parentNode->insertBefore($document->createTextNode("\n"), $target_dom);
            $parentNode->insertBefore($insert_dom,$result);   
            $parentNode->insertBefore($document->createTextNode("\n"), $result); 
        }
        return $result;
    }

    private function es_img_resize($filename,$path_directory,$added_path,$dimension){
        $filename = strtolower($filename);
        $path_to_result_directory = $path_directory.$added_path; 
        $path_to_image_directory = $path_directory;

        $config['image_library'] = 'GD2';
        $config['source_image'] = $path_to_image_directory . $filename;
        $config['maintain_ratio'] = true;
        $config['quality'] = '90%';
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
}


/* End of file manage.php */
/* Location: ./application/controllers/manage.php */
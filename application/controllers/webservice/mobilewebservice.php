<?php 

use EasyShop\Entities\EsProduct; 

/**
 * Mobile Webservice Class
 *
 * @author Inon baguio
 */
class MobileWebService extends MY_Controller 
{

    /**
     * The XML file service
     */
    private $xmlCmsService;

    /**
     * The XML resource servie
     */
    private $xmlFileService;

    /**
     * The entity manager
     */    
    private $em;

    /**
     * The JSONP data
     */    
    private $json;

    /**
     * The Mobile XML resource
     */    
    private $file;

    /**
     * @var string
     *
     * Default value for $type
     */
    const DEFAULT_MAINSLIDE_TYPE = "image";

    public function __construct() 
    {
        parent::__construct();

        $this->xmlCmsService = $this->serviceContainer['xml_cms'];
        $this->xmlFileService = $this->serviceContainer['xml_resource'];
        $this->em = $this->serviceContainer['entity_manager'];
        $this->file  = APPPATH . "resources/". $this->xmlFileService->getMobileXMLfile().".xml"; 
        $this->json = file_get_contents(APPPATH . "resources/json/jsonp.json");    
        $this->slugerrorjson = file_get_contents(APPPATH . "resources/json/slugerrorjson.json");
        $this->authenticateRequest = $this->serviceContainer['webservice_manager'];        
        if($this->input->get()) {        
            $this->isAuthenticated = $this->authenticateRequest->authenticate($this->input->get(), 
                                                                              $this->input->get('hash'),
                                                                              true);
            if(!$this->isAuthenticated) {
                throw new Exception("Unauthorized Request.");
            }               
        }  
    }

    /**
     *  Removes mainSlides
     *  @return JSON
     */
    public function removeContent() 
    {
        $map = simplexml_load_file($this->file);        
        $index =  (int)$this->input->get("index");
        $nodeName =  $this->input->get("nodename");        
        $productindex =  (int)$this->input->get("productindex");        
        $file = $this->file;
        $jsonFile = $this->json;        
        if($nodeName === "mainSlide") {
            if(count($map->mainSlide) > 1){
                $this->xmlCmsService->removeXML($file,$nodeName,$index);
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);                  
            }
        }
        if($nodeName === "boxContent") {
            $subIndex = (int)$this->input->get("subIndex");
            if(count($map->section[$index]->boxContent) > 1){
                $index = $index === 0 ? 1 : $index + 1;
                $subIndex = $subIndex === 0 ? 1 : $subIndex + 1;
                $result = $this->xmlCmsService->removeXmlNode($file,$nodeName,$index, $subIndex);
                if($result) {
                    return $this->output
                                ->set_content_type('application/json')
                                ->set_output($jsonFile);                  
                }
            }            
        }
    }

    /**
     *  Method to display the contents of the mobile_home_files.xml from the function call from Easyshop.ph.admin
     *  @return string
     */
    public function getContents() 
    {
        $this->output
            ->set_content_type('text/plain') 
            ->set_output(file_get_contents($this->file));
    }

    /**
     *  Add Main Slide Node
     *  @return string
     */
    public function addMainSlide()
    {
        $value = $this->input->get("value");
        $type = self::DEFAULT_MAINSLIDE_TYPE;
        $coordinate = trim($this->input->get("coordinate"));
        $target = trim($this->input->get("target")); 
        $target = $target !== "" ? $target : "/";
        $action = trim($this->input->get("actionType"));
        $this->config->load("image_path");
        $filename = date('yhmdhs');
        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = $this->config->item('mobile_img_directory');

        $this->upload->initialize([
            "upload_path" => $path_directory,
            "overwrite" => false, 
            "encrypt_name" => false,
            "file_name" => $filename,
            "remove_spaces" => true,
            "allowed_types" => "jpg|jpeg|png|gif", 
            "xss_clean" => false
        ]); 
        
        if ( ! $this->upload->do_upload("myfile")) {
          
            $error = [
                'error' => $this->upload->display_errors()
            ];
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($error));
        } 
        else {
            $uploadData = $this->upload->data();  
            $value = $path_directory.$filename.'.'.$file_ext;
            $string = $this->xmlCmsService->getString("mainSlide", $value, $coordinate, $target, $action);
            $isXmlInsertSuccessful = $this->xmlCmsService->addXmlFormatted($this->file,$string,'/map/mainSlide[last()]',"\t","\n\n");

            if(strtolower(ENVIRONMENT) !== 'development' && $isXmlInsertSuccessful){
                $this->serviceContainer['aws_uploader']
                     ->uploadFile($uploadData['full_path'],  $value);
                unlink($uploadData['full_path']);
            } 

            if($addXml === true) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json); 
            }   
        }


    }

    /**
     *  Set values on specific mainSlide
     *  @return JSON
     */
    public function setMainSlide()
    {
        $filename = date('yhmdhs');
        $index = (int)$this->input->get("index");
        $value = $this->input->get("value");
        $type = self::DEFAULT_MAINSLIDE_TYPE;
        $order = $this->input->get("order");
        $coordinate = $this->input->get("coordinate");
        $actionType = trim($this->input->get("actionType"));
        $target = trim($this->input->get("target")); 
        $target = $target !== "" ? $target : "/";
        $this->config->load("image_path");

        $map = simplexml_load_file($this->file);
        $value = !empty($_FILES['myfile']['name']) ? $value : $map->mainSlide[$index]->value;

        if(!empty($_FILES['myfile']['name'])){
            $file_ext = explode('.', $_FILES['myfile']['name']);
            $file_ext = strtolower(end($file_ext));  
            $path_directory = $this->config->item('mobile_img_directory');
            $value = $path_directory.$filename.".".$file_ext;
            $this->upload->initialize([
                "upload_path" => $path_directory,
                "overwrite" => false, 
                "encrypt_name" => false,
                "file_name" => $filename,
                "remove_spaces" => true,
                "allowed_types" => "jpg|jpeg|png|gif", 
                "xss_clean" => false
            ]);

            if ( ! $this->upload->do_upload("myfile")) {
                $error = [
                    'error' => $this->upload->display_errors()
                ];
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($error));
            }  
            else {
                $uploadData = $this->upload->data();  
                $map->mainSlide[$index]->value = $value;
                $map->mainSlide[$index]->type = self::DEFAULT_MAINSLIDE_TYPE;
                $map->mainSlide[$index]->imagemap->target = $target;
                $map->mainSlide[$index]->actionType = $actionType;
                
                if($map->asXML($this->file)) {
                   
                    if(strtolower(ENVIRONMENT) !== 'development'){
                        $this->serviceContainer['aws_uploader']
                             ->uploadFile(
                                 $uploadData['full_path'],  
                                 $value
                             );
                        unlink($uploadData['full_path']);
                    } 

                    return $this->output
                                ->set_content_type('application/json')
                                ->set_output($this->json);
                } 
            }            
        }
        else {

            if(!isset($order) || $order == NULL){
                $map->mainSlide[$index]->value = $value;
                $map->mainSlide[$index]->type = self::DEFAULT_MAINSLIDE_TYPE;
                $map->mainSlide[$index]->imagemap->target = $target;
                $map->mainSlide[$index]->actionType = $actionType; 
            }
            else {

                $order = (int) $order;  
                $tempValue = (string) $map->mainSlide[$order]->value;
                $tempOrder = (string) $map->mainSlide[$order]->type;
                $tempTarget = (string) $map->mainSlide[$order]->imagemap->target;
                $tempType = (string) $map->mainSlide[$order]->actionType;

                $map->mainSlide[$order]->value = $map->mainSlide[$index]->value;
                $map->mainSlide[$order]->type = $map->mainSlide[$index]->type;
                $map->mainSlide[$order]->imagemap->target = $map->mainSlide[$index]->imagemap->target;
                $map->mainSlide[$order]->actionType = $map->mainSlide[$index]->actionType;

                $map->mainSlide[$index]->value = $tempValue;
                $map->mainSlide[$index]->type = $tempOrder;
                $map->mainSlide[$index]->imagemap->target = $tempTarget;
                $map->mainSlide[$index]->actionType = $tempType;

            }
            if($map->asXML($this->file)) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);

            }             
        }
    
    }  

    /**
     *  Set section head nodes
     *  @return JSON
     */
    public function setSectionHead()
    {
        $index = (int)$this->input->get("index");
        $map = simplexml_load_file($this->file);

        $map->section[$index]->name = $this->input->get("name") === "" ? $map->section[$index]->name : $this->input->get("name");
        $map->section[$index]->bgcolor = $this->input->get("bgcolor") === ""  ? $map->section[$index]->bgcolor : $this->input->get("bgcolor");
        $map->section[$index]->type = $this->input->get("type") === ""  ? $map->section[$index]->type : $this->input->get("type");

        if($map->asXML($this->file)) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);
        }
    }

    /**
     *  Add boXContent Nodes
     *  @return JSON
     */
    public function addBoxContent()
    {
        $index = (int)$this->input->get("sectionIndex");
        $boxIndex = (int)$this->input->get("boxIndex");

        $value = $this->input->get("value");
        $type = $this->input->get("type");
        $target = trim($this->input->get("target"));
        $actionType = trim($this->input->get("actionType"));
        $target = $target === "" ? "/" : $target;
        $string = $this->xmlCmsService->getString("boxContent",$value, $type, $target, $actionType); 
        $map = simplexml_load_file($this->file);

        $index = $index == 0 ? 1 : $index + 1;   

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
            return $this->output
                ->set_content_type('application/json')
                ->set_output( $this->slugerrorjson);
        }
        else {
            $addXml = $this->xmlCmsService->addXmlFormatted($this->file,
                                                            $string,
                                                            '/map/section['.$index.']/boxContent[last()]',
                                                            "\t\t",
                                                            "\n");                
            if($addXml === true) {
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($this->json);            
            } 
        }


    }

    /**
     *  Sets boxContents values
     *  @return JSON
     */
    public function setBoxContent()
    {

        $index = (int)$this->input->get("sectionIndex");
        $boxIndex = (int)$this->input->get("boxIndex");
        $order = $this->input->get("order");
        $value = $this->input->get("value");
        $type = $this->input->get("type");
        $target = trim($this->input->get("target")); 
        $target = $target !== "" ? $target : "/";
        $actionType = $this->input->get("actionType");
        $string = $this->xmlCmsService->getString("boxContent",$value, $type, $target, $actionType);

        $map = simplexml_load_file($this->file);

        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                        ->findBy(['slug' => $value]);
                        
        if(!$product){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output( $this->slugerrorjson);
        }
        else {
            if($order === "" || $order == NULL){
                $order = (int) $order;
                $map->section[$index]->boxContent[$boxIndex]->value = $value;
                $map->section[$index]->boxContent[$boxIndex]->type = $type ;
                $map->section[$index]->boxContent[$boxIndex]->target = $target;
                $map->section[$index]->boxContent[$boxIndex]->actionType = $actionType; 
            }
            else {
                $order = (int) $order;         
                $tempValue = (string)$map->section[$index]->boxContent[$order]->value;
                $tempOrder = (string)$map->section[$index]->boxContent[$order]->type;
                $tempTarget = (string)$map->section[$index]->boxContent[$order]->target;
                $tempActionType = (string)$map->section[$index]->boxContent[$order]->actionType;

                $map->section[$index]->boxContent[$order]->value = $map->section[$index]->boxContent[$boxIndex]->value;
                $map->section[$index]->boxContent[$order]->type = $map->section[$index]->boxContent[$boxIndex]->type;
                $map->section[$index]->boxContent[$order]->target = $map->section[$index]->boxContent[$boxIndex]->target;
                $map->section[$index]->boxContent[$order]->actionType = $map->section[$index]->boxContent[$boxIndex]->actionType;

                $map->section[$index]->boxContent[$boxIndex]->value = $tempValue;
                $map->section[$index]->boxContent[$boxIndex]->type = $tempOrder;
                $map->section[$index]->boxContent[$boxIndex]->target = $tempTarget;
                $map->section[$index]->boxContent[$boxIndex]->actionType = $tempActionType;

            }
            if($map->asXML($this->file)) {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($this->json);
            }            
        }          
    }
}





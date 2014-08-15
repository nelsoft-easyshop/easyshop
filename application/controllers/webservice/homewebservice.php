<?php 


class Homewebservice extends MY_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->declareEnvironment();

        if($this->input->post())
        {
            $this->authentication($this->input->post(), $this->input->post('hash'));
        }  
    }
    

    
    function declareEnvironment(){

        $env = strtolower(ENVIRONMENT);
        $this->file = APPPATH . "resources/page/home_files.xml"; 
        $this->json = file_get_contents(APPPATH . "resources/page/test.json");
    }

    public function index() {
        $this->load->view("pages/home_cms");
    }


    public function getContents() {

        $this->output
        ->set_content_type('text/plain') // You could also use ".jpeg" which will have the full stop removed before looking in config/mimes.php
        ->set_output(file_get_contents($this->file));
    }

    public function getNode() {
        
        $query = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   
                
    //posted data
        $userid = $this->input->post("userid");
        $nodename =  $this->input->post("nodename");
        $hash = $this->input->post("hash");
        $index = $this->input->post("index");
        $innernode = $this->input->post("innernode");
        $nodename = "text";
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
        if($index > count($query->$nodename))
        {
            exit("Index out of Bounds");
        }
        else
        {

            $nodename1 = ($innernode == "" ? $nodename : $nodename."/".$innernode);

            $query = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   
            $query = $query->xpath(' /map/'.$nodename1);
            
            return json_encode($query);
            
        }       
    }

    public function setProductTitle() {

        $jsonFile = $this->json;
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
        
        $userid = $this->input->get("userid");
        $value =  $this->input->get("productslidetitle");
        $hash = $this->input->get("hash");

        $value = ($value == "") ? $map->productSlide_title->value : $value;

        $map->productSlide_title->value = $value;

        if($map->asXML(APPPATH . "resources/page/home_files.xml"))
        {
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
        }


    }


    public function setProductSideBanner() {
        $jsonFile = $this->json;
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
        
        $userid = $this->input->get("userid");
        $value =  $this->input->get("value");
        $hash = $this->input->get("hash");
        $value = ($value == "") ? $map->productSideBanner->value : $value;

        $map->productSideBanner->value= $value;

        if($map->asXML(APPPATH . "resources/page/home_files.xml"))
        {
            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
        }

    }

    public function setMainSlide() {
        $jsonFile = $this->json;
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");  
        $index = $this->input->get("index");
        $userid =  $this->input->get("userid");
        $hash =  $this->input->get("hash");
        $value =  $this->input->get("value");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $order =  $this->input->get("order");
        $nodeName =  $this->input->get("nodename");
        $type = "image";
        $file = $this->file;

        $coordinate = ($coordinate == "") ? "0,0,0,0" : $coordinate;
        
        if($index > count($map->mainSlide) - 1 || $order > count($map->mainSlide) - 1 || $index < 0 || $order < 0)
        {
            exit("error");
        }
        else
        {

            $index = (int)($index); 
            
            if($order == "")
            {
                
                $sxe = new SimpleXMLElement(file_get_contents($file));
                $map->mainSlide[$index]->value = $value;
                $map->mainSlide[$index]->imagemap->coordinate = $coordinate;
                $map->mainSlide[$index]->imagemap->target = $target;
                
                if($map->asXML(APPPATH . "resources/page/home_files.xml")){
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
                } 
                    
            } else {
                if($index <= $order) {
                    
                    $value = ($value == "" ? $map->mainSlide[$index]->value : $value);
                    $type = ($type == "" ? $map->mainSlide[$index]->type : $type);
                    $coordinate = ($coordinate == "" ? $map->mainSlide[$index]->imagemap->coordinate : $coordinate);
                    $target = ($target == "" ? $map->mainSlide[$index]->imagemap->target : $target);

$string = '    
        <mainSlide> 
        <value>'.$value.'</value> 
        <type>image</type>
        <imagemap>
            <coordinate>'.$coordinate.'</coordinate>
            <target>'.$target.'</target>
        </imagemap>
    </mainSlide>'; 
                    $index = ($index == 0 ? 1 : $index + 1);
                    $order = ($order == 0 ? 1 : $order + 1);
                    $this->addXml($file,$string,'/map/mainSlide['.$order.']');
                    $this->removeXML($file,$nodeName,$index);
                } else  {
                    if($order == 0)
                    {
                       
                        $value = ($value == "" ? $map->mainSlide[$index]->value : $value);
                        $type = ($type == "" ? $map->mainSlide[$index]->type : $type);
                        $coordinate = ($coordinate == "" ? $map->mainSlide[$index]->imagemap->coordinate : $coordinate);
                        $target = ($target == "" ? $map->mainSlide[$index]->imagemap->target : $target);

$string = '    
        <mainSlide> 
        <value>'.$value.'</value> 
        <type>image</type>
        <imagemap>
            <coordinate>'.$coordinate.'</coordinate>
            <target>'.$target.'</target>
        </imagemap>
    </mainSlide>'; 
                        $orindex = $index;
                        $ororder = $order;
                        $index = ($index == 0 ? 1 : $index + 1);
                        $this->removeXML($file,$nodeName,$index);
                        $order = ($order == 0 ? 1 : $order);
                        $this->addXml($file,$string,'/map/mainSlide['.$order.']');
                        $two = 1;
                        $plusin = $two;
                        $plusor = $two;

                        $this->swapXmlForSetMainSlide($orindex,$ororder, $plusin, $plusor, $value,$type,$coordinate,$target);
                    } else {
                
                        $value = ($value == "" ? $map->mainSlide[$index]->value : $value);
                        $type = ($type == "" ? $map->mainSlide[$index]->type : $type);
                        $coordinate = ($coordinate == "" ? $map->mainSlide[$index]->imagemap->coordinate : $coordinate);
                        $target = ($target == "" ? $map->mainSlide[$index]->imagemap->target : $target);

$string = '    
        <mainSlide> 
        <value>'.$value.'</value> 
        <type>image</type>
        <imagemap>
            <coordinate>'.$coordinate.'</coordinate>
            <target>'.$target.'</target>
        </imagemap>
    </mainSlide>'; 

                            $index = ($index == 0 ? 1 : $index + 1);
                            $this->removeXML($file,$nodeName,$index);
                            $order = ($order == 0 ? 1 : $order);
                            $this->addXml($file,$string,'/map/mainSlide['.$order.']');
                                          
                    }
                }

                            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
            }   
        }
    }

    public function addMainSlide() {

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");  

        $count = count($map->mainSlide);

        $filename = date('yhmdhs');
        $index = $count;
        $userid =  $this->input->post("userid");
        $hash =  $this->input->post("hash");
        $value =  $this->input->post("value");
        $coordinate =  $this->input->post("coordinate");
        $target =  $this->input->post("target");
        
        $nodeName =  "mainSlide";
        $type = "image";
        $coordinate = ($coordinate == "") ? "0,0,0,0" : $coordinate;

        $index = (int)($index); 

        $file_ext = explode('.', $_FILES['myfile']['name']);
        $file_ext = strtolower(end($file_ext));  
        $path_directory = 'assets/images/mainslide';

        $this->upload->initialize(array( 
        "upload_path" => $path_directory,
        "overwrite" => FALSE, 
        "encrypt_name" => FALSE,
        "file_name" => $filename,
        "remove_spaces" => TRUE,
        "allowed_types" => "jpg|jpeg|png|gif", 
        "xss_clean" => FALSE
        ));   

        if ( ! $this->upload->do_upload("myfile"))
        {
            $error = array('error' => $this->upload->display_errors());
            redirect('https://easyshop.ph.admin/cms/home/?error=1', 'refresh');

        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
                        $file = $this->file;
            
        $string = '    
        <mainSlide> 
        <value>assets/images/mainslide/'.$filename.'.'.$file_ext.'</value> 
        <type>image</type>
        <imagemap>
            <coordinate>'.$coordinate.'</coordinate>
            <target>'.$target.'</target>
        </imagemap>
    </mainSlide>'; 
                $orindex = $index;
                $index = ($index == 0 ? 1 : $index);

                if($orindex == 0)
                {
                    $this->addXml($file,$string,'/map/mainSlide['.$index.']');
                    $this->swapXmlForAddMainSlide($orindex, $index,$value,$type,$coordinate,$target);
                
                } else {
                    $this->addXml($file,$string,'/map/mainSlide['.$index.']');
                }
            redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

        }
            
    }

    public function addProductSlide() {

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");  
        $count = count($map->productSlide) - 1;
        $index = $count;
        $userid =  $this->input->post("userid");
        $hash =  $this->input->post("hash");
        $value =  $this->input->post("value");
        $nodeName =  "productSlide";
        $type = "product";
        $file = $this->file;


                $file = $this->file;

                $index = (int)($index); 
                
                $value = ($value == "" ? $map->productSlide[$index]->value : $value);
            
                    $string = '    
    <productSlide> 
        <value>'.$value.'</value> 
        <type>product</type>
        
    </productSlide>'; 
                    $orindex = $index;
                    $index = ($index == 0 ? 1 : $index);

                    if($orindex == 0)
                    {
                        $this->addXml($file,$string,'/map/productSlide[last()]');
                        $this->swapXmlForAddProductSlide($orindex, $index,$value);
                        redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');
                    } else {
                        $this->addXml($file,$string,'/map/productSlide[last()]');
                        redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                    }
                    
            
    }

    public function swapXmlForAddProductSlide($orindex,$neworindex,$value)
    {
        $orindex = (int) $orindex;
        $neworindex = (int) $neworindex;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
        $map->productSlide[$neworindex]->value = $map->productSlide[$orindex]->value;        
        $map->productSlide[$orindex]->value = $value;

        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }
    public function swapXmlForAddMainSlide($orindex,$neworindex,$value,$type,$coordinate,$target)
    {
        $orindex = (int) $orindex;
        $neworindex = (int) $neworindex;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");

        $map->mainSlide[$neworindex]->value = $map->mainSlide[$orindex]->value;
        $map->mainSlide[$neworindex]->type = $map->mainSlide[$orindex]->type;
        $map->mainSlide[$neworindex]->imagemap->coordinate = $map->mainSlide[$orindex]->imagemap->coordinate;
        $map->mainSlide[$neworindex]->imagemap->target = $map->mainSlide[$orindex]->imagemap->target;
         
        $map->mainSlide[$orindex]->value = $value;
        $map->mainSlide[$orindex]->type = $type;
        $map->mainSlide[$orindex]->imagemap->coordinate = $coordinate;
        $map->mainSlide[$orindex]->imagemap->target = $target;

        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }

    public function swapXmlForSetMainSlide($orindex,$ororder, $plusin, $plusor, $value,$type,$coordinate,$target)
    {
        $orindex =  (int) $orindex;
        $ororder =  (int) $ororder;
        $plusor =  (int) $plusor;
    
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
 
        $map->mainSlide[$plusor]->value = $map->mainSlide[$ororder]->value;
        $map->mainSlide[$plusor]->type = $map->mainSlide[$ororder]->type;
        $map->mainSlide[$plusor]->imagemap->coordinate = $map->mainSlide[$ororder]->imagemap->coordinate;
        $map->mainSlide[$plusor]->imagemap->target = $map->mainSlide[$ororder]->imagemap->target;

        $map->mainSlide[$ororder]->value = $value;
        $map->mainSlide[$ororder]->type = $type;
        $map->mainSlide[$ororder]->imagemap->coordinate = $coordinate;
        $map->mainSlide[$ororder]->imagemap->target = $target;

        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }

    public function setSectionHead() 
    {
        $jsonFile = $this->json;
        $index = $this->input->get("index");
        $userid =  $this->input->get("userid");
        $hash = $this->input->get("hash");
        $type = $this->input->get("type");
        $value =  $this->input->get("value");
        $css_class = $this->input->get("css_class");
        $title = $this->input->get("title");
        $layout = $this->input->get("layout");

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");

        if($index > count($map->section) - 1 || $index < 0)
        {
            exit('Index out of bounds');
        }
        else
        {
            $index = (int)$index;
            $type = $type == "" ? $map->section[$index]->type : $type;
            $value = $value == "" ? $map->section[$index]->value : $value;
            $css_class = $css_class == "" ? $map->section[$index]->css_class : $css_class;
            $title = $title == "" ? $map->section[$index]->title : $title;
            $layout = $layout == "" ? $map->section[$index]->layout : $layout;
            
            $map->section[$index]->type = $type;
            $map->section[$index]->value = $value;
            $map->section[$index]->css_class = $css_class;
            $map->section[$index]->title = $title;
            $map->section[$index]->layout = $layout;

            if($map->asXML(APPPATH . "resources/page/home_files.xml"))
            {
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
            }

        }
    

        
    }

    public function setSectionProduct()
    {
        $jsonFile = $this->json;
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   
                
        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = $this->input->get("type");
        $value =  $this->input->get("value");

        $index = (int)$index;
        $productindex = (int)$productindex;



        
    
        $map->section[$index]->product_panel[$productindex]->value = $value;
        $map->section[$index]->product_panel[$productindex]->type = $type;
            if($map->asXML(APPPATH . "resources/page/home_files.xml"))
            {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonFile);
            }


    }

    public function setSectionMainPanel()
    {
        $file = $this->file;
        $jsonFile = $this->json;

        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $productindex = $this->input->get("productindex");
        $type = $this->input->get("type");
        $value =  $this->input->get("value");
        $coordinate =  $this->input->get("coordinate");
        $target =  $this->input->get("target");
        $nodeName = "product_panel_main";
        
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   
        
        $index = (int)$index;
        $productindex = (int)$productindex;

/*          if($index > count($map->section) - 1 || $productindex > count($map->section[$index]->product_panel_main) - 1 || $index < 0 || $productindex < 0)
                {
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode("Index out of bounds"));
                }
            else
            {*/
                if(strtolower($type) == "image")
                {
                    if($coordinate != "" && $target != "")
                    {
                        
                        $coordinate = $coordinate == "" ? "0,0,0,0" : $coordinate;
                        $target = $target == "" ? "" : $target;
    $q = '
                                                                                                                                                                                                <product_panel_main>
            <value>'.$value.'</value> 
            <type>'.$type.'</type>
            <imagemap>
                <coordinate>'.$coordinate.'</coordinate>
                <target>'.$target.'</target>
            </imagemap>
        </product_panel_main>'; 

                        $index = ($index == 0 ? 1 : $index + 1);
                        $productindex = ($productindex == 0 ? 1 : $productindex + 1);
                        $this->addXmlChild($file,$q,'/map/section['.$index.']/product_panel_main['.$productindex.']');
                        $this->removeXMLForSetSectionMainPanel($file,$nodeName,$index,$productindex);
                        return $this->output
                                    ->set_content_type('application/json')
                                    ->set_output($jsonFile);
                            
                    }
                    else
                    {
                            return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode("error"));
                    }
                        
                } else {    
                        
                    if($coordinate != "" && $target != "")
                    {

                        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode("error"));
                    } else {
                        $map->section[$index]->product_panel_main[$productindex]->value = $value;
                        $map->section[$index]->product_panel_main[$productindex]->type = $type;
                        if($map->asXML(APPPATH . "resources/page/home_files.xml"))
                        {
                            return $this->output
                                        ->set_content_type('application/json')
                                        ->set_output($jsonFile);
                        }
                        else
                        {
                            return $this->output
                                ->set_content_type('application/json')
                                ->set_output(json_encode("error"));
                        }
                    }

                }
             
            //}
    }

    public function addSectionMainPanel()
    {

        $file = $this->file;
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   
        $index = $this->input->post("index");
        $userid = $this->input->post("userid");
        $hash = $this->input->post("hash");
        $productindex = $this->input->post("productindex");
        $type = strtolower($this->input->post("type"));
        $value =  $this->input->post("value");
        $coordinate =  $this->input->post("coordinate");
        $target =  $this->input->post("target");
        $nodeName = "product_panel_main";
        
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   

        $index = (int)$index;
        $productindex = (int)$productindex;

            if($index > count($map->section) || $productindex > count($map->section[$index]->product_panel_main) || $index < 0 || $productindex < 0)
            {
                exit("Index out of bounds");
            }
            else
            {
                if(strtolower($type) == "image" && isset($coordinate) && isset($target))
                {           
                    $coordinate = $coordinate == "" ? "0,0,0,0" : $coordinate;
                    $target = $target == "" ? "" : $target;
                    if($productindex == 0)
                    {
                        if(isset($map->section[$index]->product_panel_main[$productindex]->coordinate) && isset($map->section[$index]->product_panel_main[$productindex]->target))
                        {   
                        
                            $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                                $string = '    
      <product_panel_main>
            <value>'.$value.'</value> 
            <type>'.$type.'</type>
            <imagemap>
                <coordinate>'.$coordinate.'</coordinate>
                <target>'.$target.'</target>
            </imagemap>
        </product_panel_main>'; 
                                 $this->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                                 $this->swapXmlForAddSectionMainSlide_notimage2($newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                                 redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                        } else {
                            
        $string = '                 
    <product_panel_main>
            <value>asd</value> 
            <type>asd</type>
            <imagemap>
                <coordinate>asd</coordinate>
                <target>asd</target>
            </imagemap>
        </product_panel_main>'; 

                            $newindex = ($index == 0 ? 1 : $index);
                            $newprodindex = ($productindex == 0 ? 1 : $productindex);
                    
                            $this->swapXmlForAddSectionMainSlide_image($newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                            redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                        }
                            
                    } else {
                        
                        $index = ($index == 0 ? 1 : $index + 1);
                        $productindex = ($productindex == 0 ? 1 : $productindex);
                            
                        $string = '    
        <product_panel_main>
           <value>'.$value.'</value> 
            <type>'.$type.'</type>
            <imagemap>
                <coordinate>'.$coordinate.'</coordinate>
                <target>'.$target.'</target>
            </imagemap>
        </product_panel_main>'; 

                        $index = ($index == 0 ? 1 : $index);
                        $productindex = ($productindex == 0 ? 1 : $productindex);
                
                        $this->addXmlChild($file,$string,'/map/section['.$index.']/product_panel_main['.$productindex.']');
                        redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                            
                            }       
                }
                
                else if(strtolower($type) != "image")
                {   
                        
                    if($coordinate != "" || $target != "")
                    {
                        echo "Only Image";
                    } else {    

                        if($productindex == 0)
                        {
                            if(!isset($map->section[$index]->product_panel_main[$productindex]->coordinate) && !isset($map->section[$index]->product_panel_main[$productindex]->target))
                            {
                                
                                $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                                $string = '    
            <product_panel_main>
                <value>'.$value.'</value> 
                <type>'.$type.'</type>
            </product_panel_main>'; 
                                $this->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');          
                                redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                            } else {
                            
                                $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                                $string = '    
                                <product_panel_main>
                                   <value>'.$value.'</value> 
                                    <type>'.$type.'</type>
                                    <coordinate>'.$coordinate.'</coordinate>
                                    <target>'.$target.'</target>
                                </product_panel_main>'; 
                                $this->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                                $this->swapXmlForAddSectionMainSlide_notimage2($newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target);
                                redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                            }
                            
                        } else {
                            
                                $newindex = ($index == 0 ? 1 : $index);
                                $newprodindex = ($productindex == 0 ? 1 : $productindex);
                                $string = '    
            <product_panel_main>
           <value>'.$value.'</value> 
            <type>'.$type.'</type>
        </product_panel_main>'; 
                                $this->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel_main['.$newprodindex.']');
                                redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');

                        }       
                    
                    }

                }

            }

    }

    public function swapXmlForAddSectionMainSlide_image($newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target)
    {

        $file = $this->file;
        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");

        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        $map->section[$index]->product_panel_main[$newprodindex]->imagemap->coordinate = $map->section[$index]->product_panel_main[$productindex]->imagemap->coordinate;
        $map->section[$index]->product_panel_main[$newprodindex]->imagemap->target = $map->section[$index]->product_panel_main[$productindex]->imagemap->target;

        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $value;
        $map->section[$index]->product_panel_main[$productindex]->imagemap->coordinate = $coordinate;
        $map->section[$index]->product_panel_main[$productindex]->imagemap->target = $target;

        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }
    public function swapXmlForAddSectionMainSlide_notimage2($newprodindex,$newindex,$index,$productindex,$value,$type,$coordinate,$target)
    {

        $file = $this->file;
        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
     
        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        $map->section[$index]->product_panel_main[$newprodindex]->coordinate = $map->section[$index]->product_panel_main[$productindex]->coordinate;
        $map->section[$index]->product_panel_main[$newprodindex]->target = $map->section[$index]->product_panel_main[$productindex]->target;

        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $value;
        $map->section[$index]->product_panel_main[$productindex]->coordinate = $coordinate;
        $map->section[$index]->product_panel_main[$productindex]->target = $target;
        
        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }
    public function swapXmlForAddSectionMainSlide_notimage1($newprodindex,$newindex,$index,$productindex,$value,$type)
    {
    
        $newprodindex = (int) $newprodindex;
        $newindex = (int) $newindex;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
    
        $map->section[$index]->product_panel_main[$newprodindex]->value = $map->section[$index]->product_panel_main[$productindex]->value;
        $map->section[$index]->product_panel_main[$newprodindex]->type = $map->section[$index]->product_panel_main[$productindex]->type;
        
        $map->section[$index]->product_panel_main[$productindex]->value = $value;
        $map->section[$index]->product_panel_main[$productindex]->type = $type;

        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }
    public function removeXMLForSetSectionMainPanel($file,$nodeName,$index,$productindex)
    {
        
        $index = (int)$index;
        $productindex = (int)$productindex;

        $referred = "/map/section[".$index.']/product_panel_main['.$productindex.']';
        $doc = new SimpleXMLElement(file_get_contents($file));
        if($target = current($doc->xpath($referred)))
        {
        $dom = dom_import_simplexml($target);

        
          $dom->parentNode->removeChild($dom);
          if($doc->asXml($file))
            return true;
          else
            return false;
        } else {
                return false;
            }
            
        
        
    }
    public function setProductSlide()
    {
        $jsonFile = $this->json;
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   

        $index = $this->input->get("index");
        $userid = $this->input->get("userid");
        $hash = $this->input->get("hash");
        $value =  $this->input->get("value");
        $order =  $this->input->get("order");
        $type =  $this->input->get("type");

        $nodeName =  $this->input->get("nodename");
        $index = (int)$index;

            if($index > count($map->productSlide) - 1    || $order > count($map->productSlide) - 1 || $index < 0 || $order < 0)
            {
                exit("Index out of bounds");
            }
            else
            {
                $file = $this->file;
                $value = ($value == "" ? $map->productSlide[$index]->value : $value);
        
                if($order == "")
                {
                    
                    $map->productSlide[$index]->value = $value;
                    $type->productSlide[$index]->type = $type;
                    if($map->asXML(APPPATH . "resources/page/home_files.xml"))
                        return true;
                    else
                        return false;

                    } else {
                         
                        if($index <= $order) {
                        
                           $string = '    
           <productSlide>
        <value>'.$value.'</value> 
        <type>'.$type.'</type>
   </productSlide>'; 

                            $index = ($index == 0 ? 1 : $index + 1);
                            $order = ($order == 0 ? 1 : $order + 1);
                            $this->addXml($file,$string,'/map/productSlide['.$order.']');
                            $this->removeXML($file,$nodeName,$index);
                        } else {

                            if($order == 0)
                            {
                                 $value = ($value == "" ? $map->productSlide[$index]->value : $value);
                            
                                $string = '    
            <productSlide>
        <value>'.$value.'</value> 
        <type>'.$type.'</type>
   </productSlide>'; 
                                $orindex = $index;
                                $ororder = $order;
                                $index = ($index == 0 ? 1 : $index + 1);
                                $this->removeXML($file,$nodeName,$index);
                                $order = ($order == 0 ? 1 : $order);
                                $this->addXml($file,$string,'/map/productSlide['.$order.']');
                                $this->swapXmlForSetProductSlide($orindex,$ororder,$value);

                            } else {
                                 $value = ($value == "" ? $map->productSlide[$index]->value : $value);
                    
                                $string = '
            <productSlide>
        <value>'.$value.'</value> 
        <type>'.$type.'</type>
   </productSlide>';  
                             
                                $index = ($index == 0 ? 1 : $index + 1);
                                $this->removeXML($file,$nodeName,$index);
                                $order = ($order == 0 ? 1 : $order);
                                $this->addXml($file,$string,'/map/productSlide['.$order.']');
                             
                            }
                        }


                    }

            return $this->output
                        ->set_content_type('application/json')
                        ->set_output($jsonFile);
            }

    }

    public function swapXmlForSetProductSlide($orindex,$ororder,$value)
    {
    
        $orindex = (int) $orindex;
        $ororder = (int) $ororder;
        if($orindex > 1)
            $orindex = $orindex - 1;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");

        $map->productSlide[$orindex]->value = $map->productSlide[$ororder]->value;

        $map->productSlide[$ororder]->value = $value;

        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }

    public function addSectionProduct()
    {
        //print_r($this->input->post());
        //echo "asd";
        $file = $this->file;
        $index = $this->input->post("index");
        $userid = $this->input->post("userid");
        $hash = $this->input->post("hash");
        $productindex = $this->input->post("productindex");
        $type = strtolower($this->input->post("type"));
        $value =  $this->input->post("value");
        
        echo "<br/>productindex".$productindex;
        echo "<br/>value".$value;

        $type = ($type == "") ? "product" : $type;

        $index = (int)$index;
        $productindex = (int)$productindex;

        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");   
        
        if($index > count($map->section) - 1 || $productindex > count($map->section[$index]->product_panel) - 1 || $index < 0 || $productindex < 0)
        {
            redirect('https://easyshop.ph.admin/cms/home/?error=1', 'refresh');
        }
        else
        {
            $node = $map->section[$index]->product_panel[$productindex];
    
              $string = '   <product_panel>
            <value>'.$value.'</value> 
            <type>'.$type.'</type>
        </product_panel>'; 

            
            $newprodindex = ($productindex == 0 ? 1 : $productindex);
            $newindex = ($index == 0 ? 1 : $index);
            
            
                if($index > 0)
                {

                    $newindex += 1;
                    $this->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                    if($productindex == 0)
                        $this->swapXmlForSectionProduct($newprodindex,$newindex,$value,$type,$index,$productindex);
                        redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');
                } else {
                    $this->addXmlChild($file,$string,'/map/section['.$newindex.']/product_panel['.$newprodindex.']');
                    $this->swapXmlForSectionProduct($newprodindex,$newindex,$value,$type,$index,$productindex);
                    redirect('https://easyshop.ph.admin/cms/home/?success=1', 'refresh');
                }   
        }

    }

    function swapXmlForSectionProduct($newprodindex,$newindex,$value,$type,$index,$productindex)
    {
        $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
            
        $map->section[$index]->product_panel[$newprodindex]->value = $map->section[$index]->product_panel[$productindex]->value;
        $map->section[$index]->product_panel[$newprodindex]->type = $map->section[$index]->product_panel[$productindex]->type;
    
        $map->section[$index]->product_panel[$productindex]->value = $value;
        $map->section[$index]->product_panel[$productindex]->type = $type;
        $map->asXML(APPPATH . "resources/page/home_files.xml");
        
    }

    function addXmlChild($file,$xml_string,$target_node,$move = true)
    {
        
        
        $sxe = new SimpleXMLElement(file_get_contents($file));
        $insert = new SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));

        $this->simplexml_insert_after_child($insert, $target,$move);
         if($sxe->asXml($file))
         {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode("success"));
         }

    }
    function addXml($file,$xml_string,$target_node,$move = true)
    {
        
        
        $sxe = new SimpleXMLElement(file_get_contents($file));
        $insert = new SimpleXMLElement($xml_string);
        $target = current($sxe->xpath($target_node));

        $this->simplexml_insert_after($insert, $target,$move);
         if($sxe->asXml($file))
         {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode("success"));
         }
    }
    function simplexml_insert_after_child(SimpleXMLElement $insert, SimpleXMLElement $target,$move = true)
    {
        $target_dom = dom_import_simplexml($target);

        $document = $target_dom->ownerDocument;
        $insert_dom = $document->importNode(dom_import_simplexml($insert), true);
        $document->formatOutput = true;
        $parentNode = $target_dom->parentNode;

        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);

            } else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        } else {
            $result =  $parentNode->insertBefore($document->createTextNode("\n"), $target_dom);
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
    }
    function simplexml_insert_after(SimpleXMLElement $insert, SimpleXMLElement $target,$move = true)
    {
        $target_dom = dom_import_simplexml($target);

        $document = $target_dom->ownerDocument;
        $insert_dom = $document->importNode(dom_import_simplexml($insert), true);
        $document->formatOutput = true;
        $parentNode = $target_dom->parentNode;

        if($move){
            if ($target_dom->nextSibling) {
                $result =  $parentNode->insertBefore($insert_dom, $target_dom->nextSibling);
                $parentNode->insertBefore($document->createTextNode("\n"), $result);


            } else {
                $result =  $target_dom->parentNode->appendChild($insert_dom);
            }
        } else {
            $result =  $parentNode->insertBefore($document->createTextNode("\n"), $target_dom);
            $parentNode->insertBefore($insert_dom,$result);   

        }
        return $result;
    }
    
    public function removeXML($file,$nodeName,$index)
    {
    
        $referred = "//".$nodeName.'['.$index.']';
        $doc = new SimpleXMLElement(file_get_contents($file));
        if($target = current($doc->xpath($referred)))
        {
        $dom = dom_import_simplexml($target);

        
          $dom->parentNode->removeChild($dom);
          $doc->asXml($file);


        } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode("Parameter out of Bounds"));
            }
        
    }
    public function addType()
    {
        $file = $this->file;
        $userid = $this->input->post("userid");
        $value =  $this->input->post("value");

$string = '
    <typeNode>
        <value>'.$value.'</value>
    </typeNode >

';
        
        if($this->addXml($file,$string,'/map/typeNode[last()]'))
        {
            return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode("success"));
        }
        else
        {
                        return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode("error"));
        }
    }
    public function settext() 
    {

        $jsonFile = $this->json;
        $userid = $this->input->get("userid");
        $value =  $this->input->get("value");
        $hash = $this->input->get("hash");

            $map = simplexml_load_file(APPPATH . "resources/page/home_files.xml");
            $value = $value == "" ? $map->text->value : $value;
            $map->text->value= $value;
            if($map->asXML(APPPATH . "resources/page/home_files.xml"))
            {
                
                return $this->output
                            ->set_content_type('application/json')
                            ->set_output($jsonFile);
            }
    }
}
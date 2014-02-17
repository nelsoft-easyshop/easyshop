<?php

class home_xml extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('product_model', '', TRUE);
    }

    function getFilenameID($file) {

        $xml = simplexml_load_file(APPPATH . "resources/page/" . $file . ".xml");
       
        $simple = json_decode(json_encode($xml), 1);
        $data = array();
        foreach ($simple as $key => $product):
            if (is_array($product) && $key != "mainSlide"):
                foreach ($product as $id => $key2):
                    $result = $this->product_model->getProduct_withImage($key2);
                    if (!empty($result)):
                        $data[$key][$id] = $result[0];
                    else:
                        $data[$key][$id] = "empty";
                    endif;
                endforeach;
            else:
                $data[$key] = $product;
            endif;
        endforeach;
        $data['category1_pid_main'] = $this->product_model->getProduct_withImage($data['category1_pid_main']);
        
        return $data;
    }

}

/* End of file home_xml.php */
/* Location: ./application/libraries/home_xml.php */
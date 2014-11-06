<?php

class MY_Exceptions extends CI_Exceptions {
        
    /**
     * render the 404 page
     *
     */
    public function show_404($page = "", $doLogError = TRUE)
    {
        include APPPATH . 'config/routes.php';

        if($page === ""){
            $page = $_SERVER['REQUEST_URI'];
        }

        if ($doLogError){
            log_message('error', '404 Page Not Found --> '.$page);
        }

        if(!empty($route['404_override']) ){
            $CI =& get_instance();

            $data = array('title' => 'Page Not Found | Easyshop.ph',);

            if($CI->session->userdata('member_id')) {
                $data['user_details'] = $CI->fillUserDetails();
            }
            $data['homeContent'] = $CI->fillCategoryNavigation();  
            $data = array_merge($data, $CI->fill_header());
            $CI->output->set_status_header('404'); 
            $CI->load->view('templates/header_primary', $data);
            $CI->load->view('pages/general_error');
            $CI->load->view('templates/footer_primary');
            echo $CI->output->get_output();
            exit;
        } 
        else {
            $heading = "404 Page Not Found";
            $message = "The page you requested was not found.";
            echo $this->show_error($heading, $message, 'error_404', 404);
            exit;
        }
    }

} 